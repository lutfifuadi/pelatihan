<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pelatihan;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\ActivityLog;
use App\Enums\EnrollmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnrollmentTransferTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Pelatihan $pelatihanA;
    private Pelatihan $pelatihanB;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@transfer.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);

        $this->pelatihanA = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 'BATCH-A',
            'deskripsi' => 'Test A',
            'is_active' => true,
            'kuota' => 10,
        ]);

        $this->pelatihanB = Pelatihan::create([
            'nama' => 'Pelatihan B',
            'batch' => 'BATCH-B',
            'deskripsi' => 'Test B',
            'is_active' => true,
            'kuota' => 10,
        ]);
    }

    private function createEnrollment(User $user, Pelatihan $pelatihan, string $status): Enrollment
    {
        $data = [
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => $status,
        ];

        if ($status === 'approved') {
            $data['approved_at'] = now();
        } elseif ($status === 'rejected') {
            $data['rejected_at'] = now();
        } elseif ($status === 'waitlist') {
            $data['waitlist_promoted_at'] = now();
        }

        return Enrollment::create($data);
    }

    private function transfer(Enrollment $enrollment, Pelatihan $tujuan, string $notes = 'Alihkan test'): mixed
    {
        return $this->post(route('admin.enrollments.transfer', $enrollment), [
            'pelatihan_id' => $tujuan->id,
            'notes' => $notes,
        ]);
    }

    // TC-013: Transfer approved ke pelatihan lain (kuota cukup)
    public function test_transfer_approved_to_other_pelatihan_with_quota(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'approved');
        $oldPelatihanId = $enrollment->pelatihan_id;

        $response = $this->transfer($enrollment, $this->pelatihanB, 'Pindah karena alasan teknis');

        $response->assertSessionHas('success');
        $enrollment->refresh();

        $this->assertNotEquals($oldPelatihanId, $enrollment->pelatihan_id);
        $this->assertEquals($this->pelatihanB->id, $enrollment->pelatihan_id);
        $this->assertEquals(EnrollmentStatus::Approved, $enrollment->status);
        $this->assertStringContainsString('[Alihkan:', $enrollment->notes);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'transferred',
            'subject_id' => $enrollment->id,
        ]);
    }

    // TC-014: Transfer approved ke pelatihan yang kuotanya penuh -> jadi waitlist
    public function test_transfer_approved_to_full_pelatihan_becomes_waitlist(): void
    {
        $pelatihanPenuh = Pelatihan::create([
            'nama' => 'Pelatihan Penuh',
            'batch' => 'BATCH-FULL',
            'deskripsi' => 'Penuh',
            'is_active' => true,
            'kuota' => 1,
        ]);

        $existingPeserta = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($existingPeserta, $pelatihanPenuh, 'approved');

        $pesertaBaru = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($pesertaBaru, $this->pelatihanA, 'approved');

        $response = $this->transfer($enrollment, $pelatihanPenuh, 'Dipindahkan tapi kuota penuh');

        $response->assertSessionHas('success');
        $enrollment->refresh();

        $this->assertEquals($pelatihanPenuh->id, $enrollment->pelatihan_id);
        $this->assertEquals(EnrollmentStatus::Waitlist, $enrollment->status);
        $this->assertNull($enrollment->waitlist_promoted_at);
    }

    // TC-015: Transfer waitlist ke pelatihan lain -> tetap waitlist
    public function test_transfer_waitlist_stays_waitlist(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'waitlist');

        $response = $this->transfer($enrollment, $this->pelatihanB, 'Pindah cadangan');

        $response->assertSessionHas('success');
        $enrollment->refresh();

        $this->assertEquals($this->pelatihanB->id, $enrollment->pelatihan_id);
        $this->assertEquals(EnrollmentStatus::Waitlist, $enrollment->status);
    }

    // TC-016: Validasi — pelatihan tujuan sama dengan asal
    public function test_transfer_to_same_pelatihan_returns_error(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'approved');

        $response = $this->transfer($enrollment, $this->pelatihanA);

        $response->assertSessionHas('error');
        $enrollment->refresh();

        $this->assertEquals($this->pelatihanA->id, $enrollment->pelatihan_id);
    }

    // TC-017: Validasi — pelatihan tujuan tidak aktif
    public function test_transfer_to_inactive_pelatihan_returns_error(): void
    {
        $pelatihanNonaktif = Pelatihan::create([
            'nama' => 'Pelatihan Nonaktif',
            'batch' => 'BATCH-NA',
            'deskripsi' => 'Nonaktif',
            'is_active' => false,
        ]);

        $peserta = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'approved');

        $response = $this->transfer($enrollment, $pelatihanNonaktif);

        $response->assertSessionHas('error');
        $enrollment->refresh();

        $this->assertEquals($this->pelatihanA->id, $enrollment->pelatihan_id);
    }

    // TC-018: Validasi — peserta sudah terdaftar di pelatihan tujuan
    public function test_transfer_to_pelatihan_where_user_already_registered_returns_error(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($peserta, $this->pelatihanB, 'approved');
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'approved');

        $response = $this->transfer($enrollment, $this->pelatihanB);

        $response->assertSessionHas('error');
        $enrollment->refresh();

        $this->assertEquals($this->pelatihanA->id, $enrollment->pelatihan_id);
    }

    // TC-019: Transfer approved -> auto-promote waitlist di pelatihan asal
    public function test_transfer_approved_auto_promotes_waitlist(): void
    {
        $pesertaApproved = User::factory()->create(['role' => 'peserta']);
        $enrollmentApproved = $this->createEnrollment($pesertaApproved, $this->pelatihanA, 'approved');

        $pesertaWaitlist = User::factory()->create(['role' => 'peserta']);
        $enrollmentWaitlist = $this->createEnrollment($pesertaWaitlist, $this->pelatihanA, 'waitlist');

        $this->transfer($enrollmentApproved, $this->pelatihanB, 'Pindah, promote waitlist');

        $enrollmentWaitlist->refresh();
        $this->assertEquals(EnrollmentStatus::Approved, $enrollmentWaitlist->status);
        $this->assertNotNull($enrollmentWaitlist->approved_at);
        $this->assertNotNull($enrollmentWaitlist->waitlist_promoted_at);
    }

    // TC-020: Transfer -> hapus attendances & certificates
    public function test_transfer_deletes_attendances_and_certificates(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanA, 'approved');

        $attendance = Attendance::create([
            'enrollment_id' => $enrollment->id,
            'pertemuan_ke' => 1,
            'status' => 'hadir',
            'date' => now(),
        ]);

        $certificate = Certificate::create([
            'enrollment_id' => $enrollment->id,
            'certificate_number' => 'CERT-TEST-001',
            'issued_at' => now(),
        ]);

        $this->transfer($enrollment, $this->pelatihanB, 'Hapus data lama');

        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
        $this->assertDatabaseMissing('certificates', ['id' => $certificate->id]);
    }
}

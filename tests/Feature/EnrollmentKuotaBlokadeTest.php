<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnrollmentKuotaBlokadeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Pelatihan $pelatihan;
    private Pelatihan $pelatihanTakTerbatas;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@kuotatest.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);

        $this->pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Terbatas',
            'batch' => 'BATCH-KUOTA-1',
            'deskripsi' => 'Test',
            'is_active' => true,
            'kuota' => 2,
        ]);

        $this->pelatihanTakTerbatas = Pelatihan::create([
            'nama' => 'Pelatihan Tak Terbatas',
            'batch' => 'BATCH-KUOTA-2',
            'deskripsi' => 'Test',
            'is_active' => true,
            'kuota' => null,
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
        } elseif ($status === 'waitlist') {
            $data['waitlist_promoted_at'] = now();
        } elseif ($status === 'rejected') {
            $data['rejected_at'] = now();
        }

        return Enrollment::create($data);
    }

    private function fillKuota(): void
    {
        $user1 = User::factory()->create(['email' => 'kuota1@test.test']);
        $user2 = User::factory()->create(['email' => 'kuota2@test.test']);
        $this->createEnrollment($user1, $this->pelatihan, 'approved');
        $this->createEnrollment($user2, $this->pelatihan, 'approved');
    }

    // ===================== AC-001: Approve individu diblokade =====================

    public function test_approve_diblokade_ketika_kuota_penuh(): void
    {
        $this->fillKuota();

        $pesertaBaru = User::factory()->create(['email' => 'baru@test.test']);
        $enrollment = $this->createEnrollment($pesertaBaru, $this->pelatihan, 'pending');

        $response = $this->post(route('admin.enrollments.approve', $enrollment));

        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'Gagal meng-approve. Kuota pelatihan "Pelatihan Terbatas" sudah penuh.');

        $enrollment->refresh();
        $this->assertEquals('pending', $enrollment->status);
    }

    public function test_approve_berjalan_normal_ketika_kuota_tersedia(): void
    {
        $peserta = User::factory()->create(['email' => 'tersedia@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihan, 'pending');

        $response = $this->post(route('admin.enrollments.approve', $enrollment));

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
        $this->assertNotNull($enrollment->approved_at);
    }

    public function test_approve_berjalan_normal_ketika_kuota_null(): void
    {
        $peserta = User::factory()->create(['email' => 'null@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanTakTerbatas, 'pending');

        $response = $this->post(route('admin.enrollments.approve', $enrollment));

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
    }

    // ===================== AC-002: Approve massal =====================

    public function test_approve_all_hanya_meng_approve_sesuai_sisa_kuota_fcfs(): void
    {
        $user1 = User::factory()->create(['email' => 'massal1@test.test']);
        $user2 = User::factory()->create(['email' => 'massal2@test.test']);
        $user3 = User::factory()->create(['email' => 'massal3@test.test']);

        $e1 = $this->createEnrollment($user1, $this->pelatihan, 'pending');
        $e2 = $this->createEnrollment($user2, $this->pelatihan, 'pending');
        $e3 = $this->createEnrollment($user3, $this->pelatihan, 'pending');

        // Kuota = 2, semua pending = 3 -> hanya 2 yang approve (FCFS by created_at)
        $response = $this->post(route('admin.enrollments.approve-all', $this->pelatihan));

        $response->assertSessionHas('success');
        $response->assertSessionHas('success', function (string $message) {
            return str_contains($message, '2 pendaftaran berhasil di-approve')
                && str_contains($message, '1 pendaftaran tidak bisa di-approve karena kuota penuh.');
        });

        $this->assertEquals('approved', $e1->fresh()->status);
        $this->assertEquals('approved', $e2->fresh()->status);
        $this->assertEquals('pending', $e3->fresh()->status);
    }

    public function test_approve_all_kembalikan_error_ketika_kuota_sudah_penuh(): void
    {
        $this->fillKuota();

        $peserta = User::factory()->create(['email' => 'penuhmassal@test.test']);
        $this->createEnrollment($peserta, $this->pelatihan, 'pending');

        $response = $this->post(route('admin.enrollments.approve-all', $this->pelatihan));

        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'Tidak dapat meng-approve. Kuota pelatihan "Pelatihan Terbatas" sudah penuh.');
    }

    public function test_approve_all_berjalan_normal_ketika_kuota_null(): void
    {
        $user1 = User::factory()->create(['email' => 'unlimited1@test.test']);
        $user2 = User::factory()->create(['email' => 'unlimited2@test.test']);
        $user3 = User::factory()->create(['email' => 'unlimited3@test.test']);

        $e1 = $this->createEnrollment($user1, $this->pelatihanTakTerbatas, 'pending');
        $e2 = $this->createEnrollment($user2, $this->pelatihanTakTerbatas, 'pending');
        $e3 = $this->createEnrollment($user3, $this->pelatihanTakTerbatas, 'pending');

        $response = $this->post(route('admin.enrollments.approve-all', $this->pelatihanTakTerbatas));

        $response->assertSessionHas('success');

        $this->assertEquals('approved', $e1->fresh()->status);
        $this->assertEquals('approved', $e2->fresh()->status);
        $this->assertEquals('approved', $e3->fresh()->status);
    }

    // ===================== AC-003: Change status ke approved diblokade =====================

    public function test_change_status_to_approved_diblokade_ketika_kuota_penuh(): void
    {
        $this->fillKuota();

        $peserta = User::factory()->create(['email' => 'changestatus@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihan, 'pending');

        $response = $this->post(route('admin.enrollments.change-status', $enrollment), [
            'status' => 'approved',
            'notes' => 'Test',
        ]);

        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'Tidak dapat mengubah status ke approved. Kuota pelatihan "Pelatihan Terbatas" sudah penuh.');

        $enrollment->refresh();
        $this->assertEquals('pending', $enrollment->status);
    }

    public function test_change_status_to_approved_berjalan_normal_ketika_kuota_tersedia(): void
    {
        $peserta = User::factory()->create(['email' => 'csnormal@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihan, 'pending');

        $response = $this->post(route('admin.enrollments.change-status', $enrollment), [
            'status' => 'approved',
            'notes' => 'Disetujui',
        ]);

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
        $this->assertNotNull($enrollment->approved_at);
    }

    public function test_change_status_to_approved_berjalan_normal_ketika_kuota_null(): void
    {
        $peserta = User::factory()->create(['email' => 'csnull@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanTakTerbatas, 'pending');

        $response = $this->post(route('admin.enrollments.change-status', $enrollment), [
            'status' => 'approved',
            'notes' => 'Disetujui',
        ]);

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
    }

    // ===================== AC-004: Promote waitlist diblokade =====================

    public function test_promote_diblokade_ketika_kuota_penuh(): void
    {
        $this->fillKuota();

        $peserta = User::factory()->create(['email' => 'promoteme@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihan, 'waitlist');

        $response = $this->post(route('admin.enrollments.promote', $enrollment));

        $response->assertSessionHas('error');
        $response->assertSessionHas('error', 'Tidak dapat mempromosikan peserta. Kuota pelatihan "Pelatihan Terbatas" sudah penuh.');

        $enrollment->refresh();
        $this->assertEquals('waitlist', $enrollment->status);
    }

    public function test_promote_berjalan_normal_ketika_kuota_tersedia(): void
    {
        $peserta = User::factory()->create(['email' => 'promotenormal@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihan, 'waitlist');

        $response = $this->post(route('admin.enrollments.promote', $enrollment));

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
        $this->assertNotNull($enrollment->approved_at);
        $this->assertNotNull($enrollment->waitlist_promoted_at);
    }

    public function test_promote_berjalan_normal_ketika_kuota_null(): void
    {
        $peserta = User::factory()->create(['email' => 'promotenull@test.test']);
        $enrollment = $this->createEnrollment($peserta, $this->pelatihanTakTerbatas, 'waitlist');

        $response = $this->post(route('admin.enrollments.promote', $enrollment));

        $response->assertSessionHas('success');

        $enrollment->refresh();
        $this->assertEquals('approved', $enrollment->status);
    }

    // ===================== AC-006: Kuota null tidak diblokade =====================

    public function test_semua_approve_berjalan_normal_ketika_kuota_null(): void
    {
        $user1 = User::factory()->create(['email' => 'allnull1@test.test']);
        $user2 = User::factory()->create(['email' => 'allnull2@test.test']);

        $e1 = $this->createEnrollment($user1, $this->pelatihanTakTerbatas, 'pending');
        $e2 = $this->createEnrollment($user2, $this->pelatihanTakTerbatas, 'waitlist');

        // approve individu
        $resp1 = $this->post(route('admin.enrollments.approve', $e1));
        $resp1->assertSessionHas('success');

        // promote
        $resp2 = $this->post(route('admin.enrollments.promote', $e2));
        $resp2->assertSessionHas('success');

        $this->assertEquals('approved', $e1->fresh()->status);
        $this->assertEquals('approved', $e2->fresh()->status);
    }
}

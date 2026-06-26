<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pelatihan;
use App\Models\Enrollment;
use App\Models\ActivityLog;
use App\Events\PendaftaranRejected;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EnrollmentRejectAllTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Pelatihan $pelatihan;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@rejectall.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);

        $this->pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test Reject All',
            'batch' => 'BATCH-REJECT',
            'deskripsi' => 'Test Reject All',
            'is_active' => true,
            'kuota' => 10,
        ]);
    }

    private function createEnrollment(User $user, string $status): Enrollment
    {
        return Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $this->pelatihan->id,
            'status' => $status,
            'approved_at' => in_array($status, ['approved', 'confirmed']) ? now() : null,
            'rejected_at' => $status === 'rejected' ? now() : null,
            'waitlist_promoted_at' => $status === 'waitlist' ? now() : null,
        ]);
    }

    private function rejectAll(): mixed
    {
        return $this->post(route('admin.enrollments.reject-all', [
            'pelatihan' => $this->pelatihan->id,
        ]));
    }

    // AC-004: Reject massal berhasil
    public function test_reject_all_rejects_all_pending_enrollments(): void
    {
        $users = User::factory()->count(3)->create(['role' => 'peserta']);
        foreach ($users as $user) {
            $this->createEnrollment($user, 'pending');
        }

        $response = $this->rejectAll();

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $this->assertEquals(0, Enrollment::where('pelatihan_id', $this->pelatihan->id)
            ->where('status', 'pending')
            ->count());

        $rejected = Enrollment::where('pelatihan_id', $this->pelatihan->id)
            ->where('status', 'rejected')
            ->get();

        $this->assertCount(3, $rejected);

        foreach ($rejected as $enrollment) {
            $this->assertNotNull($enrollment->rejected_at);
            $this->assertStringContainsString('[Reject All:', $enrollment->notes);
        }
    }

    // AC-005: Notifikasi WA dispatch untuk setiap peserta
    public function test_reject_all_dispatches_notification_for_each_pending(): void
    {
        Event::fake();

        $users = User::factory()->count(2)->create(['role' => 'peserta']);
        foreach ($users as $user) {
            $this->createEnrollment($user, 'pending');
        }

        $this->rejectAll();

        Event::assertDispatched(PendaftaranRejected::class, 2);
    }

    // AC-006: Tidak ada pending = error
    public function test_reject_all_returns_error_when_no_pending(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($user, 'approved');

        $response = $this->rejectAll();

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Tidak ada pendaftaran pending', session('error'));
    }

    // AC-007: Promosi waitlist — tidak ada slot kosong, waitlist tetap
    public function test_reject_all_does_not_promote_waitlist_when_quota_full(): void
    {
        $this->pelatihan->update(['kuota' => 1]);

        // 1 approved fills quota
        $approvedUser = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($approvedUser, 'approved');

        // 2 pending
        $pendingUsers = User::factory()->count(2)->create(['role' => 'peserta']);
        foreach ($pendingUsers as $user) {
            $this->createEnrollment($user, 'pending');
        }

        // 1 waitlist
        $waitlistUser = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($waitlistUser, 'waitlist');

        $this->rejectAll();

        // Quota still full (1 approved), so waitlist stays
        $waitlistEnrollment = Enrollment::where('user_id', $waitlistUser->id)->first();
        $this->assertEquals('waitlist', $waitlistEnrollment->status);
    }

    // AC-007: Promosi waitlist — ada slot kosong setelah reject
    public function test_reject_all_promotes_waitlist_when_slots_freed(): void
    {
        $this->pelatihan->update(['kuota' => 3]);

        // 2 pending
        $pendingUsers = User::factory()->count(2)->create(['role' => 'peserta']);
        foreach ($pendingUsers as $user) {
            $this->createEnrollment($user, 'pending');
        }

        // 1 waitlist
        $waitlistUser = User::factory()->create(['role' => 'peserta']);
        $this->createEnrollment($waitlistUser, 'waitlist');

        // Before: 2 pending, 0 approved → after reject: 2 rejected, 0 approved, kuota 3 → 3 slots free
        $this->rejectAll();

        $waitlistEnrollment = Enrollment::where('user_id', $waitlistUser->id)->first();
        $this->assertEquals('approved', $waitlistEnrollment->status);
        $this->assertNotNull($waitlistEnrollment->approved_at);
        $this->assertNotNull($waitlistEnrollment->waitlist_promoted_at);
    }

    // AC-004: Hanya pending yang di-reject, status lain tidak berubah
    public function test_reject_all_only_affects_pending_enrollments(): void
    {
        $approvedUser = User::factory()->create(['role' => 'peserta']);
        $rejectedUser = User::factory()->create(['role' => 'peserta']);
        $waitlistUser = User::factory()->create(['role' => 'peserta']);

        $this->createEnrollment($approvedUser, 'approved');
        $this->createEnrollment($rejectedUser, 'rejected');
        $this->createEnrollment($waitlistUser, 'waitlist');

        $response = $this->rejectAll();
        $response->assertSessionHas('error', 'Tidak ada pendaftaran pending untuk pelatihan ini.');

        // Pastikan data tidak berubah
        $this->assertEquals('approved', Enrollment::where('user_id', $approvedUser->id)->first()->status);
        $this->assertEquals('rejected', Enrollment::where('user_id', $rejectedUser->id)->first()->status);
        $this->assertEquals('waitlist', Enrollment::where('user_id', $waitlistUser->id)->first()->status);
    }

    // AC-004: Cek activity log tercatat
    public function test_reject_all_logs_activity(): void
    {
        $users = User::factory()->count(2)->create(['role' => 'peserta']);
        foreach ($users as $user) {
            $this->createEnrollment($user, 'pending');
        }

        $this->rejectAll();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'rejected',
            'subject_type' => 'Enrollment',
            'subject_id' => $this->pelatihan->id,
        ]);
    }

    // AC-006: Error ketika pelatihan tidak punya enrollment sama sekali
    public function test_reject_all_returns_error_when_no_enrollments(): void
    {
        $response = $this->rejectAll();

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Tidak ada pendaftaran pending', session('error'));
    }

    // AC-001/AC-002: Tombol muncul/muncul berdasarkan filter — backend: index page
    public function test_index_page_shows_reject_all_button_when_pelatihan_filtered(): void
    {
        $response = $this->get(route('admin.enrollments.index', ['pelatihan_id' => $this->pelatihan->id]));
        $response->assertStatus(200);
        $response->assertSee('Reject All Pending');
    }

    public function test_index_page_hides_reject_all_button_without_pelatihan_filter(): void
    {
        $response = $this->get(route('admin.enrollments.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Reject All Pending');
    }

    // AC-008: Update badge count — verify counts reset after reject all
    public function test_reject_all_updates_pending_count_to_zero(): void
    {
        $users = User::factory()->count(2)->create(['role' => 'peserta']);
        foreach ($users as $user) {
            $this->createEnrollment($user, 'pending');
        }

        $this->rejectAll();

        $this->assertEquals(0, Enrollment::where('pelatihan_id', $this->pelatihan->id)
            ->where('status', 'pending')
            ->count());
    }
}

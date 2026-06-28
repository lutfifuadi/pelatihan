<?php

namespace Tests\Unit\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Dinas;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\Setting;
use App\Models\User;
use App\Services\EnrollmentCooldownService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class EnrollmentCooldownServiceTest extends TestCase
{
    use RefreshDatabase;

    private EnrollmentCooldownService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EnrollmentCooldownService();
    }

    /**
     * Test get cooldown days.
     */
    public function test_get_cooldown_days(): void
    {
        // Default is 30 days
        $this->assertEquals(30, $this->service->getCooldownDays());

        Setting::updateOrCreate(['key' => 'cooldown_period_days'], ['value' => '15']);
        $this->assertEquals(15, $this->service->getCooldownDays());

        Setting::updateOrCreate(['key' => 'cooldown_period_days'], ['value' => '-5']);
        $this->assertEquals(30, $this->service->getCooldownDays()); // invalid should fallback to 30

        Setting::updateOrCreate(['key' => 'cooldown_period_days'], ['value' => 'invalid']);
        $this->assertEquals(30, $this->service->getCooldownDays()); // invalid should fallback to 30
    }

    /**
     * Test get last enrollment.
     */
    public function test_get_last_enrollment(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $this->assertNull($this->service->getLastEnrollment($user, $pelatihan));

        $enrollment1 = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
            'created_at' => now()->subDays(2),
        ]);

        // Delete the first one to bypass unique constraint when creating the second one
        $enrollment1->delete();

        $enrollment2 = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Rejected,
            'created_at' => now(),
        ]);

        $last = $this->service->getLastEnrollment($user, $pelatihan);
        $this->assertNotNull($last);
        $this->assertEquals($enrollment2->id, $last->id);
    }

    /**
     * Test get enrollment status: None.
     */
    public function test_get_enrollment_status_none(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $status = $this->service->getEnrollmentStatus($user, $pelatihan);

        $this->assertEquals('none', $status['status']);
        $this->assertTrue($status['can_register']);
        $this->assertNull($status['message']);
    }

    /**
     * Test get enrollment status: Active.
     */
    public function test_get_enrollment_status_active(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $activeStatuses = ['pending', 'approved', 'waitlist', 'waiting_wa_confirmation', 'waiting_newbimma_check'];

        foreach ($activeStatuses as $statusVal) {
            $enumStatus = EnrollmentStatus::fromValue($statusVal);
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'pelatihan_id' => $pelatihan->id,
                'dinas_id' => $dinas->id,
                'status' => $enumStatus,
            ]);

            $status = $this->service->getEnrollmentStatus($user, $pelatihan);

            $this->assertEquals('active', $status['status']);
            $this->assertFalse($status['can_register']);
            $this->assertNotNull($status['message']);

            // Cleanup for next loop
            $enrollment->delete();
        }
    }

    /**
     * Test get enrollment status: Completed.
     */
    public function test_get_enrollment_status_completed(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $completedStatuses = ['confirmed']; // 'completed' & 'passed' are not inside current EnrollmentStatus enum but supported in active/completed lists in service. We can test with 'confirmed' which is in enum.

        foreach ($completedStatuses as $statusVal) {
            $enumStatus = EnrollmentStatus::fromValue($statusVal);
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'pelatihan_id' => $pelatihan->id,
                'dinas_id' => $dinas->id,
                'status' => $enumStatus,
            ]);

            $status = $this->service->getEnrollmentStatus($user, $pelatihan);

            $this->assertEquals('completed', $status['status']);
            $this->assertFalse($status['can_register']);
            $this->assertEquals('Anda telah menyelesaikan pelatihan ini sebelumnya. Anda tidak dapat mendaftar kembali pada pelatihan yang sama.', $status['message']);

            $enrollment->delete();
        }
    }

    /**
     * Test get enrollment status: Rejected Cooldown.
     */
    public function test_get_enrollment_status_rejected_cooldown(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        Setting::updateOrCreate(['key' => 'cooldown_period_days'], ['value' => '10']);

        // Set updated_at/created_at to 2 days ago (cooldown is 10 days, so we still have 8 days left)
        $now = Carbon::parse('2026-06-28 10:00:00');
        Carbon::setTestNow($now);

        $enrollment = new Enrollment();
        $enrollment->user_id = $user->id;
        $enrollment->pelatihan_id = $pelatihan->id;
        $enrollment->dinas_id = $dinas->id;
        $enrollment->status = EnrollmentStatus::Rejected;
        $enrollment->created_at = $now->copy()->subDays(2);
        $enrollment->updated_at = $now->copy()->subDays(2);
        $enrollment->save();

        $status = $this->service->getEnrollmentStatus($user, $pelatihan);

        $this->assertEquals('rejected_cooldown', $status['status']);
        $this->assertFalse($status['can_register']);
        $this->assertNotNull($status['remaining_time']);
        $this->assertEquals('8 hari 0 jam', $status['remaining_text']);
        $this->assertEquals($now->copy()->subDays(2)->addDays(10)->format('Y-m-d H:i:s'), $status['can_register_at']->format('Y-m-d H:i:s'));

        Carbon::setTestNow(); // Reset time mock
    }

    /**
     * Test get enrollment status: Rejected Available.
     */
    public function test_get_enrollment_status_rejected_available(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        Setting::updateOrCreate(['key' => 'cooldown_period_days'], ['value' => '5']);

        // Set updated_at/created_at to 6 days ago (cooldown is 5 days, so cooldown has ended)
        $now = Carbon::parse('2026-06-28 10:00:00');
        Carbon::setTestNow($now);

        $enrollment = new Enrollment();
        $enrollment->user_id = $user->id;
        $enrollment->pelatihan_id = $pelatihan->id;
        $enrollment->dinas_id = $dinas->id;
        $enrollment->status = EnrollmentStatus::Rejected;
        $enrollment->created_at = $now->copy()->subDays(6);
        $enrollment->updated_at = $now->copy()->subDays(6);
        $enrollment->save();

        $status = $this->service->getEnrollmentStatus($user, $pelatihan);

        $this->assertEquals('rejected_available', $status['status']);
        $this->assertTrue($status['can_register']);
        $this->assertEquals('Pendaftaran Anda sebelumnya ditolak/dibatalkan. Anda kini diperbolehkan untuk mendaftar kembali.', $status['message']);

        Carbon::setTestNow(); // Reset time mock
    }

    /**
     * Test can register method helper.
     */
    public function test_can_register(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $this->assertTrue($this->service->canRegister($user, $pelatihan));

        Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->assertFalse($this->service->canRegister($user, $pelatihan));
    }

    /**
     * Test validate registration allowed throws validation exception when not allowed.
     */
    public function test_validate_registration_allowed_throws_exception(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->expectException(ValidationException::class);
        $this->service->validateRegistrationAllowed($user, $pelatihan);
    }

    /**
     * Test validate registration allowed passes when allowed.
     */
    public function test_validate_registration_allowed_passes(): void
    {
        $user = User::factory()->create();
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $this->service->validateRegistrationAllowed($user, $pelatihan);
        $this->assertTrue(true); // If no exception, test passes
    }
}

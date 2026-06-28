<?php

namespace Tests\Unit\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Dinas;
use App\Models\Enrollment;
use App\Models\KtaMember;
use App\Models\Pelatihan;
use App\Models\Setting;
use App\Models\User;
use App\Services\KtaVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KtaVerificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private KtaVerificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new KtaVerificationService();
    }

    /**
     * Test normalisasi NIK.
     */
    public function test_normalize_nik(): void
    {
        $this->assertEquals('1234567890123456', $this->service->normalizeNik('1234-5678-9012-3456'));
        $this->assertEquals('1234567890123456', $this->service->normalizeNik('1234 5678 9012 3456'));
        $this->assertEquals('1234567890123456', $this->service->normalizeNik('1234567890123456'));
        $this->assertEquals('', $this->service->normalizeNik(null));
        $this->assertEquals('', $this->service->normalizeNik('abc'));
    }

    /**
     * Test verifikasi user KTA.
     */
    public function test_verify_user_kta(): void
    {
        // 1. User dengan NIK terdaftar dan aktif
        $kta = KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234-5678-9012-3456']);

        $verified = $this->service->verify($user);
        $this->assertNotNull($verified);
        $this->assertEquals($kta->id, $verified->id);

        // 2. User dengan NIK terdaftar tapi Tidak Aktif
        $kta->update(['status_kta' => 'Tidak Aktif']);
        $this->assertNull($this->service->verify($user));

        // 3. User dengan NIK tidak terdaftar
        $userNonKta = User::factory()->create(['nik' => '9999999999999999']);
        $this->assertNull($this->service->verify($userNonKta));
    }

    /**
     * Test get mode verifikasi KTA.
     */
    public function test_get_mode(): void
    {
        // Default mode is off
        $this->assertEquals(KtaVerificationService::MODE_OFF, $this->service->getMode());

        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_PRIORITY]);
        $this->assertEquals(KtaVerificationService::MODE_PRIORITY, $this->service->getMode());

        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_AUTO_APPROVE]);
        $this->assertEquals(KtaVerificationService::MODE_AUTO_APPROVE, $this->service->getMode());

        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => 'invalid_mode']);
        $this->assertEquals(KtaVerificationService::MODE_OFF, $this->service->getMode());
    }

    /**
     * Test logic enrollment untuk mode OFF.
     */
    public function test_apply_enrollment_logic_mode_off(): void
    {
        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_OFF]);

        KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234567890123456']);
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->service->applyEnrollmentLogic($enrollment, $user);

        // Tidak ada perubahan karena mode OFF
        $enrollment->refresh();
        $this->assertEquals(EnrollmentStatus::Pending, $enrollment->status);
        $this->assertFalse($enrollment->is_kta_priority);
    }

    /**
     * Test logic enrollment untuk mode PRIORITY.
     */
    public function test_apply_enrollment_logic_mode_priority(): void
    {
        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_PRIORITY]);

        KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234567890123456']);
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->service->applyEnrollmentLogic($enrollment, $user);

        // Status tetap pending tetapi is_kta_priority menjadi true
        $enrollment->refresh();
        $this->assertEquals(EnrollmentStatus::Pending, $enrollment->status);
        $this->assertTrue($enrollment->is_kta_priority);
    }

    /**
     * Test logic enrollment untuk mode AUTO_APPROVE dengan kuota tersedia & WA Wajib tidak aktif.
     */
    public function test_apply_enrollment_logic_mode_auto_approve_success(): void
    {
        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_AUTO_APPROVE]);
        Setting::updateOrCreate(['key' => 'validate_whatsapp'], ['value' => '0']); // WA tidak aktif

        KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234567890123456']);
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->service->applyEnrollmentLogic($enrollment, $user);

        // Status berubah menjadi approved, is_kta_priority true, dan approved_at terisi
        $enrollment->refresh();
        $this->assertEquals(EnrollmentStatus::Approved, $enrollment->status);
        $this->assertTrue($enrollment->is_kta_priority);
        $this->assertNotNull($enrollment->approved_at);
    }

    /**
     * Test logic enrollment untuk mode AUTO_APPROVE dengan kuota tersedia & WA Wajib aktif.
     */
    public function test_apply_enrollment_logic_mode_auto_approve_waiting_wa(): void
    {
        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_AUTO_APPROVE]);
        Setting::updateOrCreate(['key' => 'validate_whatsapp'], ['value' => '1']); // WA aktif

        KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234567890123456']);
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 10,
            'dinas_id' => $dinas->id,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->service->applyEnrollmentLogic($enrollment, $user);

        // Status berubah menjadi waiting_wa_confirmation, is_kta_priority true, ada verification_code & expires_at
        $enrollment->refresh();
        $this->assertEquals(EnrollmentStatus::WaitingWaConfirmation, $enrollment->status);
        $this->assertTrue($enrollment->is_kta_priority);
        $this->assertNotNull($enrollment->verification_code);
        $this->assertNotNull($enrollment->verification_code_expires_at);
    }

    /**
     * Test logic enrollment untuk mode AUTO_APPROVE jika kuota pelatihan sudah penuh.
     */
    public function test_apply_enrollment_logic_mode_auto_approve_kuota_penuh(): void
    {
        Setting::updateOrCreate(['key' => 'kta_verification_mode'], ['value' => KtaVerificationService::MODE_AUTO_APPROVE]);

        KtaMember::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Budi Santoso',
            'status_kta' => 'Aktif',
            'wilayah' => 'Jawa Barat',
        ]);

        $user = User::factory()->create(['nik' => '1234567890123456']);
        $dinas = Dinas::create(['nama_dinas' => 'Dinas A', 'singkatan' => 'DA']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 1,
            'kuota' => 1,
            'dinas_id' => $dinas->id,
        ]);

        // Isi kuota penuh
        $otherUser = User::factory()->create();
        Enrollment::create([
            'user_id' => $otherUser->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Approved,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'dinas_id' => $dinas->id,
            'status' => EnrollmentStatus::Pending,
        ]);

        $this->service->applyEnrollmentLogic($enrollment, $user);

        // Status diturunkan ke waitlist (cadangan) tetapi tetap prioritas KTA
        $enrollment->refresh();
        $this->assertEquals(EnrollmentStatus::Waitlist, $enrollment->status);
        $this->assertTrue($enrollment->is_kta_priority);
        $this->assertNull($enrollment->approved_at);
    }
}

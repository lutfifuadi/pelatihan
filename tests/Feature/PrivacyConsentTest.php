<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ConsentLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyConsentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration requires NIK consent and creates a consent log.
     */
    public function test_registration_requires_nik_consent()
    {
        $userData = [
            'name' => 'Budi Santoso',
            'nik' => '3273012345678901',
            'whatsapp' => '088989470609',
            'email' => 'budi.santoso@example.com',
            'sumber_informasi' => 'koordinator',
        ];

        // 1. Check validation fails when consent is missing
        $response = $this->post(route('landing.register'), $userData);
        $response->assertSessionHasErrors(['consent_nik']);
        
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * Test registration with consent accepted.
     */
    public function test_registration_success_with_consent()
    {
        $userData = [
            'name' => 'Budi Santoso',
            'nik' => '3273012345678901',
            'whatsapp' => '088989470609',
            'email' => 'budi.santoso@example.com',
            'sumber_informasi' => 'koordinator',
            'consent_nik' => '1',
        ];

        $response = $this->post(route('landing.register'), $userData);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('landing.sukses'));

        $this->assertDatabaseHas('users', [
            'nik' => '3273012345678901',
            'email' => 'budi.santoso@example.com',
        ]);

        $user = User::where('nik', '3273012345678901')->first();

        // Verify consent log is created
        $this->assertDatabaseHas('consent_logs', [
            'user_id' => $user->id,
            'consent_type' => 'nik_collection',
            'consent_text' => 'Saya dengan ini menyatakan setuju dan memberikan izin secara sadar kepada Disbudpar Kota Bandung untuk memproses data pribadi saya (termasuk NIK) murni untuk kepentingan administrasi dan verifikasi kepesertaan pelatihan ini sesuai dengan Kebijakan Privasi.',
        ]);
    }

    /**
     * Test legal routes are accessible.
     */
    public function test_legal_routes_accessible()
    {
        $this->get(route('privacy-policy'))->assertStatus(200);
        $this->get(route('disclaimer'))->assertStatus(200);
        $this->get(route('verifikasi-kontak'))->assertStatus(200);
    }

    /**
     * Test official contact verification.
     */
    public function test_official_contact_verification()
    {
        // 1. Success matching
        $response = $this->post(route('verifikasi-kontak.check'), [
            'phone' => '+62 889-8947-0609',
        ]);
        $response->assertSessionHas('success');

        // JSON response
        $responseJson = $this->postJson(route('verifikasi-kontak.check'), [
            'phone' => '0889-8947-0609',
        ]);
        $responseJson->assertJson([
            'status' => 'success',
            'message' => 'Nomor resmi terdaftar.',
        ]);

        // 2. Failure matching
        $responseFail = $this->post(route('verifikasi-kontak.check'), [
            'phone' => '081234567890',
        ]);
        $responseFail->assertSessionHas('error');

        $responseFailJson = $this->postJson(route('verifikasi-kontak.check'), [
            'phone' => '081234567890',
        ]);
        $responseFailJson->assertJson([
            'status' => 'danger',
            'message' => 'Nomor tidak terdaftar / waspada penipuan.',
        ]);
    }
}

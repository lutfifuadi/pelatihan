<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        Faq::create([
            'question' => 'Test FAQ?',
            'answer' => 'Test Jawaban.',
            'order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_home_page_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('content.landing.beranda');
    }

    public function test_daftar_route_redirects_to_home(): void
    {
        $response = $this->get('/daftar');

        $response->assertRedirect('/#beranda');
    }

    public function test_register_with_valid_data(): void
    {
        $response = $this->post('/daftar', [
            'name' => 'Test User',
            'nik' => '3273010101000002',
            'whatsapp' => '081234567891',
            'email' => 'test@daftar.test',
            'sumber_informasi' => 'sosmed',
            'consent_nik' => '1',
        ]);

        $response->assertRedirect(route('landing.sukses'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'test@daftar.test',
            'role' => 'peserta',
        ]);
    }

    public function test_check_nik_returns_json(): void
    {
        User::factory()->create([
            'nik' => '3273010101000003',
        ]);

        $response = $this->post('/daftar/cek-nik', [
            'nik' => '3273010101000003',
        ]);

        $response->assertJson([
            'exists' => true,
        ]);

        $response2 = $this->post('/daftar/cek-nik', [
            'nik' => '3273010101000099',
        ]);

        $response2->assertJson([
            'exists' => false,
        ]);
    }

    public function test_check_wa_returns_json(): void
    {
        $response = $this->post('/daftar/cek-wa', [
            'number' => '081234567899',
        ]);

        $response->assertJsonStructure([
            'status',
            'exists',
            'message',
        ]);
    }

    public function test_check_wa_bypass_when_disabled(): void
    {
        // Set validate_whatsapp ke '0' (OFF)
        \App\Models\Setting::updateOrCreate(
            ['key' => 'validate_whatsapp'],
            ['value' => '0', 'group' => 'general', 'label' => 'Validasi Otomatis Nomor WhatsApp']
        );

        $response = $this->post('/daftar/cek-wa', [
            'number' => '081234567899',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'exists' => true,
            'message' => 'Validasi nomor dinonaktifkan.',
        ]);
    }

    public function test_pelatihan_detail_page(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Test Pelatihan',
            'batch' => 'BATCH TEST',
            'deskripsi' => 'Deskripsi test',
            'is_active' => true,
        ]);

        $response = $this->get('/pelatihan/' . $pelatihan->id);

        $response->assertStatus(200);
    }
}

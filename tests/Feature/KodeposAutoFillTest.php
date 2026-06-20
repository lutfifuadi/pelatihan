<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KodeposAutoFillTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat marker file "installed" agar routing berfungsi
        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }
    }

    // ========================================================================
    // TC-1: API response mengandung field kodepos
    // ========================================================================
    public function test_api_kelurahan_response_mengandung_kodepos(): void
    {
        // Arrange
        $kecamatan = Kecamatan::create(['name' => 'Andir']);
        $ciroyom = Kelurahan::create([
            'name' => 'Ciroyom',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
            'kodepos' => '40182',
        ]);
        Kelurahan::create([
            'name' => 'Garuda',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
            'kodepos' => '40184',
        ]);
        Kelurahan::create([
            'name' => 'Campaka',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
            'kodepos' => null,
        ]);

        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        // Assert: Response JSON must include 'kodepos' field
        $response->assertStatus(200);
        $response->assertJsonCount(3);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'kodepos'],
        ]);

        // Assert: Ciroyom has kodepos 40182
        $response->assertJsonFragment([
            'id' => $ciroyom->id,
            'name' => 'Ciroyom',
            'kodepos' => '40182',
        ]);
    }

    // ========================================================================
    // TC-2: Pilih kelurahan → kodepos terisi otomatis
    // ========================================================================
    public function test_kelurahan_dengan_kodepos_terisi(): void
    {
        // Arrange
        $kecamatan = Kecamatan::create(['name' => 'Andir']);
        $kelurahan = Kelurahan::create([
            'name' => 'Ciroyom',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
            'kodepos' => '40182',
        ]);

        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        // Assert
        $response->assertStatus(200);
        $response->assertJson([[
            'id' => $kelurahan->id,
            'name' => 'Ciroyom',
            'kodepos' => '40182',
        ]]);
    }

    // ========================================================================
    // TC-3: Ganti kecamatan → kodepos berubah sesuai kelurahan baru
    // ========================================================================
    public function test_ganti_kecamatan_kodepos_berubah(): void
    {
        // Arrange: Buat 2 kecamatan dengan kodepos berbeda
        $kec1 = Kecamatan::create(['name' => 'Andir']);
        $kel1 = Kelurahan::create([
            'name' => 'Ciroyom',
            'kecamatan_id' => $kec1->id,
            'is_active' => true,
            'kodepos' => '40182',
        ]);

        $kec2 = Kecamatan::create(['name' => 'Cibeunying Kidul']);
        $kel2 = Kelurahan::create([
            'name' => 'Cicadas',
            'kecamatan_id' => $kec2->id,
            'is_active' => true,
            'kodepos' => '40121',
        ]);

        // Act: API ke kecamatan 1
        $response1 = $this->get('/api/kelurahan?kecamatan_id=' . $kec1->id);
        $response1->assertStatus(200);
        $response1->assertJson([[
            'kodepos' => '40182',
        ]]);

        // Act: API ke kecamatan 2
        $response2 = $this->get('/api/kelurahan?kecamatan_id=' . $kec2->id);
        $response2->assertStatus(200);
        $response2->assertJson([[
            'kodepos' => '40121',
        ]]);

        // Assert: Kodepos berbeda
        $this->assertNotEquals(
            $response1->json()[0]['kodepos'],
            $response2->json()[0]['kodepos']
        );
    }

    // ========================================================================
    // TC-4: Pilih kelurahan tanpa kodepos → tidak error, kodepos null
    // ========================================================================
    public function test_kelurahan_tanpa_kodepos_tidak_error(): void
    {
        // Arrange: Kelurahan dengan kodepos = null
        $kecamatan = Kecamatan::create(['name' => 'Andir']);
        $kelurahan = Kelurahan::create([
            'name' => 'Campaka',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
            'kodepos' => null,
        ]);

        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        // Assert: Response tetap sukses dengan kodepos null
        $response->assertStatus(200);
        $response->assertJson([[
            'id' => $kelurahan->id,
            'name' => 'Campaka',
            'kodepos' => null,
        ]]);
    }

    // ========================================================================
    // TC-5: API tanpa kecamatan_id → array kosong
    // ========================================================================
    public function test_api_tanpa_kecamatan_id_kembali_kosong(): void
    {
        // Act
        $response = $this->get('/api/kelurahan');

        // Assert
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    // ========================================================================
    // TC-6: API dengan kecamatan_id tidak valid → array kosong
    // ========================================================================
    public function test_api_kecamatan_id_tidak_valid_kembali_kosong(): void
    {
        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=99999');

        // Assert
        $response->assertStatus(200);
        $response->assertJson([]);
    }

    // ========================================================================
    // TC-7: Halaman form pendaftaran dapat diakses (login required)
    // ========================================================================
    public function test_form_pendaftaran_memerlukan_login(): void
    {
        // Act: Akses tanpa login
        $response = $this->get(route('dashboard.peserta.form-pendaftaran'));

        // Assert: Harus redirect ke login
        $response->assertRedirect(route('login'));
    }

    // ========================================================================
    // TC-8: Halaman form pendaftaran mengandung script auto-fill kodepos
    // ========================================================================
    public function test_halaman_mengandung_auto_fill_script(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        $kec = Kecamatan::create(['name' => 'Andir']);
        \App\Models\PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test User',
        ]);

        // Act
        $response = $this->actingAs($user)
            ->get(route('dashboard.peserta.form-alamat'));

        // Assert: Script auto-fill kodepos ada di halaman
        $response->assertStatus(200);
        $content = $response->getContent();

        // Cek elemen kunci dari script auto-fill kodepos
        $this->assertStringContainsString('fetchKelurahans', $content,
            'Fungsi fetchKelurahans harus ada');
        $this->assertStringContainsString('/api/kelurahan?kecamatan_id=', $content,
            'Endpoint API kelurahan harus ada di JavaScript');
        $this->assertStringContainsString('updateKodepos', $content,
            'Fungsi updateKodepos harus ada');
    }

    // ========================================================================
    // TC-9: Halaman mengandung script WhatsApp checker
    // ========================================================================
    public function test_halaman_mengandung_whatsapp_checker(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        Kecamatan::create(['name' => 'Andir']);
        \App\Models\PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test User',
        ]);

        // Act - WhatsApp checker ada di halaman form-alamat
        $response = $this->actingAs($user)
            ->get(route('dashboard.peserta.form-alamat'));

        // Assert
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('checkWa', $content,
            'Fungsi checkWa harus ada');
        $this->assertStringContainsString('waFeedback', $content,
            'Feedback WhatsApp harus ada');
        $this->assertStringContainsString('convertWaNumber', $content,
            'Fungsi convertWaNumber harus ada');
    }

    // ========================================================================
    // TC-10: Halaman mengandung Select2
    // ========================================================================
    public function test_halaman_mengandung_select2(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        Kecamatan::create(['name' => 'Andir']);

        // Act
        $response = $this->actingAs($user)
            ->get(route('dashboard.peserta.form-pendaftaran'));

        // Assert
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('select2', $content,
            'Library Select2 harus di-load');
        $this->assertStringContainsString('reinitSelect2', $content,
            'Fungsi reinitSelect2 harus ada');
    }

    // ========================================================================
    // TC-11: Halaman mengandung step indicator dan multiStepForm Alpine component
    // ========================================================================
    public function test_halaman_mengandung_step_indicator(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        Kecamatan::create(['name' => 'Andir']);
        \App\Models\PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test User',
        ]);

        // Act - Step indicator ada di halaman form-alamat
        $response = $this->actingAs($user)
            ->get(route('dashboard.peserta.form-alamat'));

        // Assert
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('step-indicator', $content,
            'Step indicator harus ada');
    }

    // ========================================================================
    // TC-12: Validasi client-side memerlukan kodepos
    // ========================================================================
    public function test_validasi_kodepos_ada_di_js(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);
        Kecamatan::create(['name' => 'Andir']);
        \App\Models\PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'Test User',
        ]);

        // Act - Validasi kodepos ada di halaman form-alamat
        $response = $this->actingAs($user)
            ->get(route('dashboard.peserta.form-alamat'));

        // Assert: Validasi kodepos ada di JavaScript
        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertStringContainsString('kodepos.trim()', $content,
            'Validasi kodepos harus ada');
        $this->assertStringContainsString('Kode pos wajib diisi', $content,
            'Pesan error validasi kodepos harus ada');
    }

    // ========================================================================
    // TC-13: User factory berfungsi (regression)
    // ========================================================================
    public function test_user_factory_berfungsi(): void
    {
        // Arrange & Act
        $user = User::factory()->create([
            'role' => 'peserta',
            'email_verified_at' => now(),
        ]);

        // Assert
        $this->assertNotNull($user);
        $this->assertEquals('peserta', $user->role);
        $this->assertTrue($user->hasVerifiedEmail());
    }

    // ========================================================================
    // TC-14: Data kelurahan diurutkan berdasarkan nama
    // ========================================================================
    public function test_kelurahan_diurutkan_berdasarkan_nama(): void
    {
        // Arrange
        $kecamatan = Kecamatan::create(['name' => 'Andir']);
        Kelurahan::create(['name' => 'Maleber', 'kecamatan_id' => $kecamatan->id, 'is_active' => true]);
        Kelurahan::create(['name' => 'Campaka', 'kecamatan_id' => $kecamatan->id, 'is_active' => true]);
        Kelurahan::create(['name' => 'Ciroyom', 'kecamatan_id' => $kecamatan->id, 'is_active' => true]);

        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        // Assert: Data terurut berdasarkan nama
        $data = $response->json();
        $names = array_column($data, 'name');
        $this->assertEquals(['Campaka', 'Ciroyom', 'Maleber'], $names);
    }

    // ========================================================================
    // TC-15: Hanya kelurahan aktif yang ditampilkan
    // ========================================================================
    public function test_hanya_kelurahan_aktif_yang_ditampilkan(): void
    {
        // Arrange
        $kecamatan = Kecamatan::create(['name' => 'Andir']);
        Kelurahan::create(['name' => 'Ciroyom', 'kecamatan_id' => $kecamatan->id, 'is_active' => true]);
        Kelurahan::create(['name' => 'Campaka', 'kecamatan_id' => $kecamatan->id, 'is_active' => false]); // tidak aktif

        // Act
        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        // Assert: Hanya Ciroyom yang muncul
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['name' => 'Ciroyom']);
        $response->assertJsonMissing(['name' => 'Campaka']);
    }

    // ========================================================================
    // TC-16: Pastikan existing tests masih passing (regression)
    // ========================================================================
    public function test_existing_api_tetap_berfungsi(): void
    {
        // Test from existing ApiTest
        $kecamatan = Kecamatan::create(['name' => 'Cicendo']);
        Kelurahan::create([
            'name' => 'Pasirkaliki',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);
        Kelurahan::create([
            'name' => 'Pajajaran',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);

        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);
    }
}

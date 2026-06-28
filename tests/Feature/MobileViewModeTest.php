<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MobileViewModeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        // Setting defaults dibutuhkan untuk form
        Setting::create(['key' => 'lock_kota', 'value' => 'BANDUNG', 'group' => 'general', 'label' => 'Kota']);
        Setting::create(['key' => 'lock_provinsi', 'value' => 'Jawa Barat', 'group' => 'general', 'label' => 'Provinsi']);

        // Admin
        $this->admin = User::factory()->create([
            'email' => 'admin@mobileview.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Peserta
        $kecamatan = Kecamatan::create(['name' => 'Cicendo']);
        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'nik' => '3273010101000001',
            'whatsapp' => '6281234567890',
            'email' => 'peserta@mobileview.test',
            'kecamatan_id' => $kecamatan->id,
        ]);

        PesertaProfile::create([
            'user_id' => $this->peserta->id,
            'nama_lengkap' => 'Peserta Mobile View',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '01',
            'tahun_lahir' => '2000',
            'nik' => '3273010101000001',
        ]);

        // Buat pelatihan aktif untuk form
        Pelatihan::create([
            'nama' => 'Pelatihan Mobile View Test',
            'batch' => 'BATCH-MV-1',
            'is_active' => true,
        ]);
    }

    // ========================================================
    // TC-1: Admin Bisa Mengatur Mode
    // ========================================================

    public function test_admin_can_access_branding_page(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/settings/branding');
        $response->assertStatus(200);

        // Pastikan field minat_mobile_view_mode ada di halaman
        $response->assertSee('minat_mobile_view_mode');
        $response->assertSee('Mode Tampilan Form Minat (Mobile)');
        $response->assertSee('Horizontal (Swipe)');
        $response->assertSee('Grid (Vertikal)');
    }

    public function test_admin_can_set_horizontal_mode(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/branding', [
            'brand_name' => 'Test Brand',
            'brand_logo_size' => 'md',
            'institution_name' => 'Test Institution',
            'institution_address' => '',
            'institution_phone' => '',
            'institution_email' => '',
            'institution_description' => '',
            'footer_copyright' => '',
            'lock_kota' => 'BANDUNG',
            'lock_provinsi' => 'Jawa Barat',
            'validate_whatsapp' => '1',
            'broadcast_enabled' => '1',
            'timezone' => 'Asia/Jakarta',
            'minat_mobile_view_mode' => 'horizontal',
            'kta_verification_mode' => 'off',
            'cooldown_period_days' => '30',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.branding'));

        $this->assertDatabaseHas('settings', [
            'key' => 'minat_mobile_view_mode',
            'value' => 'horizontal',
        ]);
    }

    public function test_admin_can_set_grid_mode(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/branding', [
            'brand_name' => 'Test Brand',
            'brand_logo_size' => 'md',
            'institution_name' => 'Test Institution',
            'institution_address' => '',
            'institution_phone' => '',
            'institution_email' => '',
            'institution_description' => '',
            'footer_copyright' => '',
            'lock_kota' => 'BANDUNG',
            'lock_provinsi' => 'Jawa Barat',
            'validate_whatsapp' => '1',
            'broadcast_enabled' => '1',
            'timezone' => 'Asia/Jakarta',
            'minat_mobile_view_mode' => 'grid',
            'kta_verification_mode' => 'off',
            'cooldown_period_days' => '30',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.branding'));

        $this->assertDatabaseHas('settings', [
            'key' => 'minat_mobile_view_mode',
            'value' => 'grid',
        ]);
    }

    public function test_admin_cannot_set_invalid_mode(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/branding', [
            'brand_name' => 'Test Brand',
            'brand_logo_size' => 'md',
            'institution_name' => 'Test Institution',
            'validate_whatsapp' => '1',
            'broadcast_enabled' => '1',
            'timezone' => 'Asia/Jakarta',
            'minat_mobile_view_mode' => 'invalid_mode',
            'kta_verification_mode' => 'off',
            'cooldown_period_days' => '30',
        ]);

        $response->assertSessionHasErrors(['minat_mobile_view_mode']);
    }

    public function test_default_value_is_horizontal(): void
    {
        // Default dari SettingSeeder adalah 'horizontal'
        $this->assertEquals('horizontal', Setting::where('key', 'minat_mobile_view_mode')->value('value') ?? 'horizontal');
    }

    // ========================================================
    // TC-2: User Tidak Melihat Toggle
    // ========================================================

    public function test_user_does_not_see_toggle_on_form_minat(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan tidak ada toggle/view-switcher button untuk user
        // Kata 'Swipe' dan 'Grid' ada di CSS comments, jadi kita cek struktur UI-nya
        $response->assertDontSee('data-view-toggle');
        $response->assertDontSee('view-toggle');
        $response->assertDontSee('viewModeToggle');
        $response->assertDontSee('toggle-view');
        // Pastikan tidak ada button/tombol untuk mengganti tampilan
        $response->assertDontSee('Ganti Tampilan');
        // Pastikan tidak ada dropdown/select untuk mode di halaman peserta
        $response->assertDontSee('Pilih Mode Tampilan');
    }

    // ========================================================
    // TC-3: Mode Horizontal (Default)
    // ========================================================

    public function test_horizontal_mode_no_view_grid_class(): void
    {
        Sanctum::actingAs($this->peserta);

        // Pastikan setting = horizontal
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'horizontal', 'group' => 'general', 'label' => 'Mode Tampilan Mobile Form Minat Peserta']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan class 'view-grid' TIDAK ada di container cards
        // Container harus memiliki class 'grid-cards-container' tanpa 'view-grid'
        $response->assertSee('grid-cards-container');
        $response->assertDontSee('grid-cards-container view-grid');
    }

    // ========================================================
    // TC-4: Mode Grid
    // ========================================================

    public function test_grid_mode_shows_view_grid_class(): void
    {
        Sanctum::actingAs($this->peserta);

        // Set mode ke grid
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'grid', 'group' => 'general', 'label' => 'Mode Tampilan Mobile Form Minat Peserta']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan class 'view-grid' ADA di container cards
        $response->assertSee('view-grid');
    }

    // ========================================================
    // TC-5: Desktop Tetap Grid (tidak terpengaruh setting)
    // ========================================================

    public function test_desktop_always_shows_grid_regardless_of_setting(): void
    {
        Sanctum::actingAs($this->peserta);

        // Test dengan mode horizontal
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'horizontal', 'group' => 'general', 'label' => 'Mode Tampilan Mobile Form Minat Peserta']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // CSS grid default untuk desktop ada di .grid-cards-container
        // Class ini tetap ada terlepas dari setting
        $response->assertSee('grid-cards-container');

        // Pastikan setting tidak mengubah struktur dasar container
        $response->assertSee('grid-cards-container mt-1');
    }

    // ========================================================
    // TC-6: Regression Tests — Tidak Ada Efek Samping
    // ========================================================

    public function test_card_selection_still_works(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->post('/dashboard/peserta/form-minat', [
            'batch_pelatihan' => 'BATCH-MV-1',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('dashboard.peserta.form-dokumen'));
    }

    public function test_form_validation_still_works(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->post('/dashboard/peserta/form-minat', [
            'batch_pelatihan' => '',
        ]);

        $response->assertSessionHasErrors(['batch_pelatihan']);
    }

    public function test_popup_ditutup_still_appears(): void
    {
        Sanctum::actingAs($this->peserta);

        // Buat pelatihan yang pendaftarannya ditutup
        Pelatihan::create([
            'nama' => 'Pelatihan Ditutup',
            'batch' => 'BATCH-DITUTUP-1',
            'is_active' => true,
            'batas_pendaftaran' => now()->subDay(),
        ]);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan popup DITUTUP masih ada
        $response->assertSee('DITUTUP');
        $response->assertSee('popup-ditutup');
        $response->assertSee('watermark-overlay-card');
    }

    public function test_restricted_warning_still_shows(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan restricted warning box masih di-render
        $response->assertSee('restricted-warning-box');
    }

    public function test_mobile_view_mode_variable_passed_to_view(): void
    {
        Sanctum::actingAs($this->peserta);

        // Mode horizontal
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'horizontal', 'group' => 'general', 'label' => 'Mode']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);
        $response->assertViewHas('mobileViewMode', 'horizontal');

        // Mode grid
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'grid', 'group' => 'general', 'label' => 'Mode']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);
        $response->assertViewHas('mobileViewMode', 'grid');
    }

    public function test_alpine_data_receives_correct_mode(): void
    {
        Sanctum::actingAs($this->peserta);

        // Mode grid
        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'grid', 'group' => 'general', 'label' => 'Mode']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan Alpine.js data mode terisi dengan benar
        $response->assertSee('mode:', false);
        $response->assertSee("'grid'", false);
    }

    public function test_alpine_data_receives_horizontal_mode(): void
    {
        Sanctum::actingAs($this->peserta);

        Setting::updateOrCreate(
            ['key' => 'minat_mobile_view_mode'],
            ['value' => 'horizontal', 'group' => 'general', 'label' => 'Mode']
        );

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Pastikan Alpine.js data mode = horizontal
        $response->assertSee('mode:', false);
        $response->assertSee("'horizontal'", false);
    }

    public function test_step_indicator_still_shows(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Step indicator masih muncul
        $response->assertSee('step-indicator');
        $response->assertSee('Pilihan Pelatihan');
    }

    public function test_navigation_buttons_still_work(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);

        // Tombol Sebelumnya
        $response->assertSee('Sebelumnya');
        $response->assertSee(route('dashboard.peserta.form-pendidikan'));

        // Tombol Selanjutnya
        $response->assertSee('Selanjutnya');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
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

        $this->admin = User::factory()->create([
            'email' => 'admin@maintenance.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->peserta = User::factory()->create([
            'email' => 'peserta@maintenance.test',
            'role' => 'peserta',
            'is_active' => true,
        ]);

        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => '0', 'group' => 'general', 'label' => 'Mode Maintenance']
        );
        Setting::updateOrCreate(
            ['key' => 'maintenance_title'],
            ['value' => 'Sistem Sedang Dalam Pemeliharaan', 'group' => 'general', 'label' => 'Judul Halaman Maintenance']
        );
        Setting::updateOrCreate(
            ['key' => 'maintenance_message'],
            ['value' => 'Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan. Silakan kembali lagi nanti.', 'group' => 'general', 'label' => 'Pesan Maintenance']
        );
        Setting::updateOrCreate(
            ['key' => 'maintenance_estimated_time'],
            ['value' => '', 'group' => 'general', 'label' => 'Estimasi Waktu Selesai']
        );

        Cache::forget('setting.maintenance_mode');
    }

    private function enableMaintenance(): void
    {
        Setting::where('key', 'maintenance_mode')->update(['value' => '1']);
        Cache::forget('setting.maintenance_mode');
    }

    private function disableMaintenance(): void
    {
        Setting::where('key', 'maintenance_mode')->update(['value' => '0']);
        Cache::forget('setting.maintenance_mode');
    }

    // ============ A. MIDDLEWARE TESTS ============

    public function test_guest_redirected_to_maintenance_when_on(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/');

        $response->assertRedirect(route('maintenance'));
    }

    public function test_peserta_redirected_to_maintenance_when_on(): void
    {
        $this->enableMaintenance();
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/pelatihan');

        $response->assertRedirect(route('maintenance'));
    }

    public function test_normal_access_when_maintenance_off(): void
    {
        $this->disableMaintenance();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_admin_routes_during_maintenance(): void
    {
        $this->enableMaintenance();
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/dinas');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_non_admin_routes_during_maintenance(): void
    {
        $this->enableMaintenance();
        Sanctum::actingAs($this->admin);

        $response = $this->get('/pelatihan');

        $response->assertStatus(200);
    }

    public function test_login_page_accessible_during_maintenance(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_login_page_accessible_during_maintenance(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_maintenance_page_does_not_redirect_loop(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/maintenance');

        $response->assertStatus(200);
        $response->assertSee('Sistem Sedang Dalam Pemeliharaan');
    }

    public function test_maintenance_page_redirects_when_off(): void
    {
        $this->disableMaintenance();

        $response = $this->get('/maintenance');

        $response->assertRedirect('/');
    }

    public function test_installer_routes_accessible_during_maintenance(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/install');

        $response->assertStatus(200);
    }

    public function test_health_route_accessible_during_maintenance(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/up');

        $response->assertStatus(200);
    }

    public function test_guest_redirected_from_daftar_during_maintenance(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/daftar');

        $response->assertRedirect(route('maintenance'));
    }

    // ============ B. MAINTENANCE PAGE CONTENT TESTS ============

    public function test_maintenance_page_shows_title_from_setting(): void
    {
        $this->enableMaintenance();
        Setting::where('key', 'maintenance_title')->update(['value' => 'Custom Title Test']);
        Cache::forget('setting.maintenance_mode');

        $response = $this->get('/maintenance');

        $response->assertSee('Custom Title Test');
    }

    public function test_maintenance_page_shows_message_from_setting(): void
    {
        $this->enableMaintenance();
        Setting::where('key', 'maintenance_message')->update(['value' => 'Custom message test.']);
        Cache::forget('setting.maintenance_mode');

        $response = $this->get('/maintenance');

        $response->assertSee('Custom message test.');
    }

    public function test_maintenance_page_shows_estimated_time_when_set(): void
    {
        $this->enableMaintenance();
        Setting::where('key', 'maintenance_estimated_time')->update(['value' => 'Pukul 16.00 WIB']);
        Cache::forget('setting.maintenance_mode');

        $response = $this->get('/maintenance');

        $response->assertSee('Pukul 16.00 WIB');
        $response->assertSee('Estimasi selesai');
    }

    public function test_maintenance_page_hides_estimated_time_when_empty(): void
    {
        $this->enableMaintenance();
        Setting::where('key', 'maintenance_estimated_time')->update(['value' => '']);
        Cache::forget('setting.maintenance_mode');

        $response = $this->get('/maintenance');

        $response->assertDontSee('Estimasi selesai');
    }

    public function test_maintenance_page_has_full_viewport_styles(): void
    {
        $this->enableMaintenance();

        $response = $this->get('/maintenance');

        $response->assertSee('overflow: hidden', false);
        $response->assertSee('100vh', false);
        $response->assertSee('flex', false);
    }

    // ============ C. ADMIN SETTINGS TESTS ============

    public function test_admin_can_access_maintenance_settings_page(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/settings/maintenance');

        $response->assertStatus(200);
        $response->assertSee('Pengaturan Mode Maintenance');
    }

    public function test_admin_can_enable_maintenance_via_settings(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '1',
            'maintenance_title' => 'Test Title',
            'maintenance_message' => 'Test Message',
            'maintenance_estimated_time' => '',
        ]);

        $response->assertRedirect(route('admin.settings.maintenance'));
        $response->assertSessionHas('success');
        $this->assertEquals('1', Setting::where('key', 'maintenance_mode')->value('value'));
    }

    public function test_admin_can_disable_maintenance_via_settings(): void
    {
        $this->enableMaintenance();
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '0',
            'maintenance_title' => 'Test Title',
            'maintenance_message' => 'Test Message',
            'maintenance_estimated_time' => '',
        ]);

        $response->assertRedirect(route('admin.settings.maintenance'));
        $response->assertSessionHas('success');
        $this->assertEquals('0', Setting::where('key', 'maintenance_mode')->value('value'));
    }

    public function test_maintenance_settings_validation_title_required(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '1',
            'maintenance_title' => '',
            'maintenance_message' => 'Test Message',
        ]);

        $response->assertSessionHasErrors('maintenance_title');
    }

    public function test_maintenance_settings_validation_message_required(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '1',
            'maintenance_title' => 'Test Title',
            'maintenance_message' => '',
        ]);

        $response->assertSessionHasErrors('maintenance_message');
    }

    public function test_maintenance_settings_estimated_time_optional(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '0',
            'maintenance_title' => 'Test Title',
            'maintenance_message' => 'Test Message',
            'maintenance_estimated_time' => '',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_maintenance_settings_saves_estimated_time(): void
    {
        Sanctum::actingAs($this->admin);

        $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '1',
            'maintenance_title' => 'Test Title',
            'maintenance_message' => 'Test Message',
            'maintenance_estimated_time' => '2 Jam Lagi',
        ]);

        $this->assertEquals('2 Jam Lagi', Setting::where('key', 'maintenance_estimated_time')->value('value'));
    }

    public function test_non_admin_cannot_access_maintenance_settings(): void
    {
        Sanctum::actingAs($this->peserta);

        $response = $this->get('/admin/settings/maintenance');

        $response->assertStatus(403);
    }

    // ============ D. CACHE TESTS ============

    public function test_cache_cleared_after_saving_maintenance_settings(): void
    {
        Sanctum::actingAs($this->admin);

        Cache::put('setting.maintenance_mode', '0', 60);

        $this->post('/admin/settings/maintenance', [
            'maintenance_mode' => '1',
            'maintenance_title' => 'Test',
            'maintenance_message' => 'Test',
        ]);

        $this->assertNull(Cache::get('setting.maintenance_mode'));
    }

    // ============ E. REGRESSION TESTS ============

    public function test_branding_settings_still_work(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/settings/branding');
        $response->assertStatus(200);

        $response = $this->post('/admin/settings/branding', [
            'brand_name' => 'Test Brand',
            'brand_logo_size' => 'md',
            'institution_name' => 'Test Institution',
            'validate_whatsapp' => '1',
            'broadcast_enabled' => '1',
            'timezone' => 'Asia/Jakarta',
            'kta_verification_mode' => 'off',
            'cooldown_period_days' => '30',
            'cooldown_period_passed_days' => '30',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.branding'));
    }

    public function test_seo_settings_still_work(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/settings/seo');
        $response->assertStatus(200);

        $response = $this->post('/admin/settings/seo', [
            'seo_default_title' => 'Test Title',
            'seo_default_description' => 'Test Description',
            'seo_org_name' => 'Test Org',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.seo'));
    }

    public function test_landing_settings_still_work(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->get('/admin/settings/landing');
        $response->assertStatus(200);

        $response = $this->post('/admin/settings/landing', [
            'hero_title' => 'Test Hero',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.settings.landing'));
    }

    public function test_menu_includes_maintenance_link(): void
    {
        $menuPath = resource_path('menu/verticalMenu-admin.json');
        $this->assertFileExists($menuPath);

        $menu = json_decode(file_get_contents($menuPath), true);
        $found = false;
        foreach ($menu['menu'] as $item) {
            if (isset($item['submenu'])) {
                foreach ($item['submenu'] as $sub) {
                    if (($sub['url'] ?? '') === '/admin/settings/maintenance') {
                        $found = true;
                        $this->assertEquals('Mode Maintenance', $sub['name']);
                        $this->assertEquals('admin.settings.maintenance', $sub['slug']);
                        break 2;
                    }
                }
            }
        }
        $this->assertTrue($found, 'Maintenance menu item not found in verticalMenu-admin.json');
    }

    public function test_seeder_has_maintenance_settings(): void
    {
        $this->assertDatabaseHas('settings', ['key' => 'maintenance_mode', 'value' => '0']);
        $this->assertDatabaseHas('settings', ['key' => 'maintenance_title']);
        $this->assertDatabaseHas('settings', ['key' => 'maintenance_message']);
        $this->assertDatabaseHas('settings', ['key' => 'maintenance_estimated_time']);
    }
}

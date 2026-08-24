<?php

namespace Tests\Feature;

use App\Exceptions\FeatureDisabledException;
use App\Facades\Feature;
use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use App\Services\FeatureManager;
use App\Services\SettingsManager;
use App\Support\FeatureDefaults;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureToggleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'is_active' => true,
            'must_change_password' => false,
        ]);

        app(SettingsManager::class)->flush();
    }

    public function test_feature_defaults_has_definitions_and_metadata(): void
    {
        $defs = FeatureDefaults::definitions();
        $this->assertNotEmpty($defs);
        $this->assertTrue(FeatureDefaults::has('fitur_daftar_koordinator'));
        $this->assertTrue(FeatureDefaults::has('fitur_pendaftaran_publik'));
        $this->assertFalse(FeatureDefaults::has('fitur_non_existent_key'));

        $meta = FeatureDefaults::get('fitur_daftar_koordinator');
        $this->assertNotNull($meta);
        $this->assertEquals('fitur_daftar_koordinator', $meta['key']);
        $this->assertEquals('Pendaftaran & Publik', $meta['category']);

        $categories = FeatureDefaults::categories();
        $this->assertContains('Pendaftaran & Publik', $categories);
        $this->assertContains('Integrasi & Layanan', $categories);
        $this->assertContains('Operasional & Sertifikasi', $categories);
    }

    public function test_feature_manager_reads_default_and_custom_values(): void
    {
        // Default is ON (1)
        $this->assertTrue(Feature::isOn('fitur_daftar_koordinator'));
        $this->assertFalse(Feature::isOff('fitur_daftar_koordinator'));

        // Ubah menjadi OFF
        Feature::set('fitur_daftar_koordinator', false);
        $this->assertFalse(Feature::isOn('fitur_daftar_koordinator'));
        $this->assertTrue(Feature::isOff('fitur_daftar_koordinator'));

        // Ubah kembali menjadi ON
        Feature::set('fitur_daftar_koordinator', true);
        $this->assertTrue(Feature::isOn('fitur_daftar_koordinator'));
    }

    public function test_feature_manager_guard_throws_exception_when_off(): void
    {
        Feature::set('fitur_daftar_koordinator', true);
        // Guard tidak throw saat ON
        Feature::guard('fitur_daftar_koordinator');

        Feature::set('fitur_daftar_koordinator', false);
        $this->expectException(FeatureDisabledException::class);
        Feature::guard('fitur_daftar_koordinator');
    }

    public function test_helper_functions_work_correctly(): void
    {
        Feature::set('fitur_verifikasi_kta', true);
        $this->assertTrue(feature('fitur_verifikasi_kta'));
        $this->assertTrue(feature_is_on('fitur_verifikasi_kta'));
        $this->assertFalse(feature_is_off('fitur_verifikasi_kta'));
        $this->assertTrue(settingBool('fitur_verifikasi_kta'));

        Feature::set('fitur_verifikasi_kta', false);
        $this->assertFalse(feature('fitur_verifikasi_kta'));
        $this->assertFalse(feature_is_on('fitur_verifikasi_kta'));
        $this->assertTrue(feature_is_off('fitur_verifikasi_kta'));
        $this->assertFalse(settingBool('fitur_verifikasi_kta'));
    }

    public function test_middleware_blocks_access_when_feature_is_disabled(): void
    {
        // Fitur aktif -> endpoint bisa diakses
        Feature::set('fitur_daftar_koordinator', true);
        $responseOn = $this->get(route('koordinator.register'));
        $responseOn->assertStatus(200);

        // Fitur nonaktif -> endpoint diblokir 403
        Feature::set('fitur_daftar_koordinator', false);
        $responseOff = $this->get(route('koordinator.register'));
        $responseOff->assertStatus(403);

        // JSON request returns JSON response with 403
        $responseJson = $this->getJson(route('koordinator.register'));
        $responseJson->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_admin_can_access_feature_settings_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.settings.fitur'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Fitur');
        $response->assertSee('fitur_daftar_koordinator');
    }

    public function test_non_admin_cannot_access_feature_settings_page(): void
    {
        $response = $this->actingAs($this->peserta)->get(route('admin.settings.fitur'));
        $response->assertStatus(403);

        $guestResponse = $this->get(route('admin.settings.fitur'));
        $guestResponse->assertStatus(403);
    }

    public function test_admin_can_toggle_feature_via_ajax_and_logs_activity(): void
    {
        Feature::set('fitur_push_notification', true);

        $response = $this->actingAs($this->admin)->postJson(route('admin.settings.fitur.toggle'), [
            'key'   => 'fitur_push_notification',
            'value' => 0,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'key'     => 'fitur_push_notification',
                'is_on'   => false,
            ]);

        $this->assertFalse(Feature::isOn('fitur_push_notification'));

        // Verifikasi log aktivitas tercatat
        $this->assertDatabaseHas('activity_logs', [
            'user_id'      => $this->admin->id,
            'subject_type' => 'Pengaturan Fitur',
            'action'       => 'updated',
        ]);
    }

    public function test_admin_can_bulk_toggle_features(): void
    {
        $response = $this->actingAs($this->admin)->postJson(route('admin.settings.fitur.bulk'), [
            'state' => 0,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'state'   => false,
            ]);

        $this->assertFalse(Feature::isOn('fitur_daftar_koordinator'));
        $this->assertFalse(Feature::isOn('fitur_sync_google_drive'));
        $this->assertFalse(Feature::isOn('fitur_export_laporan'));

        // Bulk toggle ON
        $responseOn = $this->actingAs($this->admin)->postJson(route('admin.settings.fitur.bulk'), [
            'state' => 1,
        ]);

        $responseOn->assertStatus(200)
            ->assertJson([
                'success' => true,
                'state'   => true,
            ]);

        $this->assertTrue(Feature::isOn('fitur_daftar_koordinator'));
        $this->assertTrue(Feature::isOn('fitur_sync_google_drive'));
    }

    public function test_admin_can_access_dedicated_fitur_route(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.fitur.index'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Fitur');
        $response->assertSee('fitur_daftar_koordinator');
    }

    public function test_admin_can_reset_features_to_defaults(): void
    {
        // Turn off everything first
        Feature::set('fitur_daftar_koordinator', false);
        Feature::set('fitur_sync_google_drive', false);

        $response = $this->actingAs($this->admin)->postJson(route('admin.settings.fitur.reset'));
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertTrue(Feature::isOn('fitur_daftar_koordinator'));
        $this->assertTrue(Feature::isOn('fitur_sync_google_drive'));
    }
}

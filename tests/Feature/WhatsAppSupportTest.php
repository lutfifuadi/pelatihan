<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WhatsappNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WhatsAppSupportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@wa.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);
    }

    // ====================
    // 1. MODEL TEST
    // ====================

    public function test_model_can_be_created_with_factory(): void
    {
        $wa = WhatsappNumber::factory()->create();

        $this->assertModelExists($wa);
        $this->assertDatabaseHas('whatsapp_numbers', [
            'id' => $wa->id,
            'label' => $wa->label,
            'number' => $wa->number,
        ]);
    }

    public function test_scope_active_only_returns_active_numbers(): void
    {
        $active1 = WhatsappNumber::factory()->create(['is_active' => true]);
        $active2 = WhatsappNumber::factory()->create(['is_active' => true]);
        WhatsappNumber::factory()->inactive()->create();

        $result = WhatsappNumber::active()->get();

        $this->assertCount(2, $result);
        $this->assertTrue($result->pluck('id')->contains($active1->id));
        $this->assertTrue($result->pluck('id')->contains($active2->id));
    }

    public function test_scope_sorted_returns_ordered_by_sort_order(): void
    {
        $wa1 = WhatsappNumber::factory()->create(['sort_order' => 3]);
        $wa2 = WhatsappNumber::factory()->create(['sort_order' => 1]);
        $wa3 = WhatsappNumber::factory()->create(['sort_order' => 2]);

        $result = WhatsappNumber::sorted()->get();

        $this->assertEquals($wa2->id, $result[0]->id);
        $this->assertEquals($wa3->id, $result[1]->id);
        $this->assertEquals($wa1->id, $result[2]->id);
    }

    public function test_scope_active_sorted_combined(): void
    {
        $wa1 = WhatsappNumber::factory()->create(['sort_order' => 2, 'is_active' => true]);
        $wa2 = WhatsappNumber::factory()->create(['sort_order' => 1, 'is_active' => true]);
        WhatsappNumber::factory()->create(['sort_order' => 0, 'is_active' => false]);

        $result = WhatsappNumber::active()->sorted()->get();

        $this->assertCount(2, $result);
        $this->assertEquals($wa2->id, $result[0]->id);
        $this->assertEquals($wa1->id, $result[1]->id);
    }

    // ====================
    // 2. API/CONTROLLER TEST
    // ====================

    public function test_index_returns_all_numbers(): void
    {
        $wa1 = WhatsappNumber::factory()->create(['sort_order' => 2]);
        $wa2 = WhatsappNumber::factory()->create(['sort_order' => 1]);

        $response = $this->getJson('/admin/whatsapp-numbers');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $this->assertEquals($wa2->id, $response->json()[0]['id']);
        $this->assertEquals($wa1->id, $response->json()[1]['id']);
    }

    public function test_store_creates_new_number(): void
    {
        $response = $this->postJson('/admin/whatsapp-numbers', [
            'label' => 'CS Telkom',
            'number' => '6281234567890',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Nomor berhasil ditambahkan',
            ]);

        $this->assertDatabaseHas('whatsapp_numbers', [
            'label' => 'CS Telkom',
            'number' => '6281234567890',
        ]);
    }

    public function test_store_fails_with_invalid_number(): void
    {
        $response = $this->postJson('/admin/whatsapp-numbers', [
            'label' => 'Test',
            'number' => 'abc123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['number']);
    }

    public function test_store_fails_with_duplicate_number(): void
    {
        WhatsappNumber::factory()->create(['number' => '6281234567890']);

        $response = $this->postJson('/admin/whatsapp-numbers', [
            'label' => 'Duplikat',
            'number' => '6281234567890',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['number']);
    }

    public function test_store_fails_with_short_number(): void
    {
        $response = $this->postJson('/admin/whatsapp-numbers', [
            'label' => 'Test',
            'number' => '12345',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['number']);
    }

    public function test_store_fails_without_label(): void
    {
        $response = $this->postJson('/admin/whatsapp-numbers', [
            'number' => '6281234567890',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['label']);
    }

    public function test_update_changes_number(): void
    {
        $wa = WhatsappNumber::factory()->create();

        $response = $this->putJson('/admin/whatsapp-numbers/' . $wa->id, [
            'label' => 'Updated Label',
            'number' => '6289999999999',
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Nomor berhasil diupdate']);

        $this->assertDatabaseHas('whatsapp_numbers', [
            'id' => $wa->id,
            'label' => 'Updated Label',
            'number' => '6289999999999',
        ]);
    }

    public function test_destroy_deletes_number(): void
    {
        $wa = WhatsappNumber::factory()->create();

        $response = $this->deleteJson('/admin/whatsapp-numbers/' . $wa->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Nomor berhasil dihapus']);

        $this->assertDatabaseMissing('whatsapp_numbers', ['id' => $wa->id]);
    }

    public function test_reorder_updates_sort_order(): void
    {
        $wa1 = WhatsappNumber::factory()->create(['sort_order' => 0]);
        $wa2 = WhatsappNumber::factory()->create(['sort_order' => 0]);

        $response = $this->postJson('/admin/whatsapp-numbers/reorder', [
            'ids' => [$wa2->id, $wa1->id],
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Urutan berhasil diupdate']);

        $this->assertDatabaseHas('whatsapp_numbers', ['id' => $wa2->id, 'sort_order' => 0]);
        $this->assertDatabaseHas('whatsapp_numbers', ['id' => $wa1->id, 'sort_order' => 1]);
    }

    public function test_toggle_active_changes_status(): void
    {
        $wa = WhatsappNumber::factory()->create(['is_active' => true]);

        $response = $this->postJson('/admin/whatsapp-numbers/' . $wa->id . '/toggle-active');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Status berhasil diubah',
                'is_active' => false,
            ]);

        $this->assertDatabaseHas('whatsapp_numbers', ['id' => $wa->id, 'is_active' => false]);

        // Toggle back
        $response2 = $this->postJson('/admin/whatsapp-numbers/' . $wa->id . '/toggle-active');

        $response2->assertStatus(200)
            ->assertJson(['is_active' => true]);

        $this->assertDatabaseHas('whatsapp_numbers', ['id' => $wa->id, 'is_active' => true]);
    }

    public function test_toggle_active_on_inactive_activates(): void
    {
        $wa = WhatsappNumber::factory()->inactive()->create();

        $response = $this->postJson('/admin/whatsapp-numbers/' . $wa->id . '/toggle-active');

        $response->assertStatus(200)
            ->assertJson(['is_active' => true]);
    }

    // ====================
    // 3. FRONTEND RENDERING TEST
    // ====================

    public function test_floating_icon_appears_when_active_numbers_exist(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS Test',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('floating-wa-wrapper');
        $response->assertSee('CS Test');
        $response->assertSee('wa.me/6281234567890');
    }

    public function test_floating_icon_does_not_appear_when_no_active_numbers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }

    public function test_floating_icon_does_not_appear_when_all_numbers_inactive(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'Nonaktif',
            'number' => '6281234567890',
            'is_active' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }

    public function test_floating_icon_does_not_appear_on_admin_pages(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/dashboard/admin');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }

    public function test_floating_icon_does_not_appear_on_login_page(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }

    public function test_floating_icon_does_not_appear_on_register_page(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }

    public function test_floating_icon_appears_on_pelatihan_index(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        \App\Models\Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'Batch 1',
            'is_active' => true,
        ]);

        $response = $this->get('/pelatihan');

        $response->assertStatus(200);
        $response->assertSee('floating-wa-wrapper');
    }

    public function test_floating_icon_appears_on_pelatihan_show(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $pelatihan = \App\Models\Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'Batch 1',
            'is_active' => true,
        ]);

        $response = $this->get('/pelatihan/' . $pelatihan->id);

        $response->assertStatus(200);
        $response->assertSee('floating-wa-wrapper');
    }

    public function test_floating_icon_appears_on_verify_certificate_page(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/verifikasi-sertifikat');

        $response->assertStatus(200);
        $response->assertSee('floating-wa-wrapper');
    }

    public function test_floating_icon_does_not_appear_on_forgot_password_page(): void
    {
        WhatsappNumber::factory()->create([
            'label' => 'CS',
            'number' => '6281234567890',
            'is_active' => true,
        ]);

        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
        $response->assertDontSee('floating-wa-wrapper');
    }
}



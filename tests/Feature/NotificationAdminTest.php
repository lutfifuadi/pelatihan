<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationAdminTest extends TestCase
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
            'email' => 'admin@test.test',
            'role' => 'admin',
            'is_active' => true,
            'whatsapp' => '6281234567890',
        ]);

        Sanctum::actingAs($this->admin);
    }

    public function test_admin_index_requires_admin_role(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($user);

        $response = $this->get('/admin/notifications');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_notification_log(): void
    {
        $response = $this->get('/admin/notifications');

        $response->assertStatus(200);
    }

    public function test_admin_can_filter_notification_log(): void
    {
        Notification::create([
            'user_id' => $this->admin->id,
            'channel' => 'whatsapp',
            'recipient' => '6281234567890',
            'title' => 'WA Notif',
            'body' => 'Body WA',
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $this->admin->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'InApp Notif',
            'body' => 'Body InApp',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->get('/admin/notifications?channel=whatsapp');
        $response->assertStatus(200);

        $response = $this->get('/admin/notifications?status=pending');
        $response->assertStatus(200);
    }

    public function test_admin_can_view_notification_detail(): void
    {
        $notification = Notification::create([
            'user_id' => $this->admin->id,
            'channel' => 'whatsapp',
            'recipient' => '6281234567890',
            'title' => 'Detail Test',
            'body' => 'Detail Body',
            'status' => 'pending',
        ]);

        $response = $this->getJson("/admin/notifications/{$notification->id}");

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $notification->id,
            'title' => 'Detail Test',
        ]);
    }

    public function test_admin_can_resend_failed_notification(): void
    {
        $notification = Notification::create([
            'user_id' => $this->admin->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Failed Notif',
            'body' => 'Body',
            'status' => 'failed',
            'failed_reason' => 'Test failure',
        ]);

        $response = $this->post("/admin/notifications/{$notification->id}/resend");

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => 'sent',
        ]);
    }

    public function test_admin_cannot_resend_non_failed_notification(): void
    {
        $notification = Notification::create([
            'user_id' => $this->admin->id,
            'channel' => 'whatsapp',
            'recipient' => '6281234567890',
            'title' => 'Pending Notif',
            'body' => 'Body',
            'status' => 'pending',
        ]);

        $response = $this->post("/admin/notifications/{$notification->id}/resend");

        $response->assertSessionHas('error');
    }

    public function test_admin_can_view_templates_list(): void
    {
        NotificationTemplate::create([
            'key' => 'test_template',
            'name' => 'Test Template',
            'title' => 'Test Title',
            'body' => 'Test Body {nama}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response = $this->get('/admin/notification-templates');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_template(): void
    {
        $response = $this->post('/admin/notification-templates', [
            'key' => 'template_baru',
            'name' => 'Template Baru',
            'title' => 'Halo {nama}',
            'body' => 'Selamat datang {nama} di {pelatihan}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.notification-templates.index'));

        $this->assertDatabaseHas('notification_templates', [
            'key' => 'template_baru',
            'name' => 'Template Baru',
        ]);
    }

    public function test_admin_can_create_in_app_template(): void
    {
        $response = $this->post('/admin/notification-templates', [
            'key' => 'in_app_notif',
            'name' => 'In-App Notifikasi',
            'title' => 'Notifikasi Baru',
            'body' => 'Ini adalah notifikasi in-app',
            'channel' => 'in_app',
            'is_active' => true,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notification_templates', [
            'key' => 'in_app_notif',
            'channel' => 'in_app',
        ]);
    }

    public function test_admin_can_edit_template(): void
    {
        $template = NotificationTemplate::create([
            'key' => 'edit_template',
            'name' => 'Edit Me',
            'title' => 'Original Title',
            'body' => 'Original Body',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response = $this->get("/admin/notification-templates/{$template->id}/edit");
        $response->assertStatus(200);

        $response = $this->put("/admin/notification-templates/{$template->id}", [
            'key' => 'edit_template',
            'name' => 'Edited Name',
            'title' => 'Edited Title',
            'body' => 'Edited Body {nama}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('notification_templates', [
            'id' => $template->id,
            'name' => 'Edited Name',
        ]);
    }

    public function test_admin_can_delete_template(): void
    {
        $template = NotificationTemplate::create([
            'key' => 'delete_template',
            'name' => 'Delete Me',
            'title' => 'Title',
            'body' => 'Body',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response = $this->delete("/admin/notification-templates/{$template->id}");

        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('notification_templates', [
            'id' => $template->id,
        ]);
    }

    public function test_admin_broadcast_page_renders(): void
    {
        Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-001',
            'deskripsi' => 'Deskripsi test',
            'is_active' => true,
        ]);

        $response = $this->get('/admin/notifications/broadcast');

        $response->assertStatus(200);
    }

    public function test_admin_broadcast_send_to_all_peserta(): void
    {
        User::factory()->create([
            'role' => 'peserta',
            'is_active' => true,
            'whatsapp' => '6281111111111',
        ]);

        $template = NotificationTemplate::create([
            'key' => 'broadcast_test',
            'name' => 'Broadcast Test',
            'title' => 'Broadcast',
            'body' => 'Halo {nama}, ini broadcast',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response = $this->post('/admin/notifications/broadcast/send', [
            'target' => 'all_peserta',
            'template_id' => $template->id,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_admin_broadcast_returns_error_no_recipients(): void
    {
        $response = $this->post('/admin/notifications/broadcast/send', [
            'target' => 'all_peserta',
            'custom_message' => 'Test saja',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_can_test_template(): void
    {
        $template = NotificationTemplate::create([
            'key' => 'test_send',
            'name' => 'Test Send',
            'title' => 'Test {nama}',
            'body' => 'Test body {pelatihan}',
            'channel' => 'whatsapp',
            'is_active' => true,
        ]);

        $response = $this->post("/admin/notification-templates/{$template->id}/test");

        $response->assertSessionHas('success');
    }

    public function test_admin_create_template_validation(): void
    {
        $response = $this->post('/admin/notification-templates', [
            'key' => '',
            'name' => '',
            'body' => '',
            'channel' => 'invalid',
        ]);

        $response->assertSessionHasErrors(['key', 'name', 'body', 'channel']);
    }
}

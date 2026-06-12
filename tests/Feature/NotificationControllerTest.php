<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotificationPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->user = User::factory()->create([
            'whatsapp' => '6281234567890',
        ]);

        Sanctum::actingAs($this->user);
    }

    public function test_unread_endpoint_returns_json(): void
    {
        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Test Notif',
            'body' => 'Test Body',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->getJson('/notifications/unread');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'count',
            'items' => [
                '*' => ['id', 'title', 'body', 'channel', 'read_at', 'time_ago', 'created_at'],
            ],
        ]);
        $response->assertJson([
            'count' => 1,
        ]);
    }

    public function test_unread_count_is_correct(): void
    {
        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Unread 1',
            'body' => 'Body 1',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Unread 2',
            'body' => 'Body 2',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $read = Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Read',
            'body' => 'Body Read',
            'status' => 'sent',
            'sent_at' => now(),
            'read_at' => now(),
        ]);

        $response = $this->getJson('/notifications/unread');

        $response->assertJson([
            'count' => 2,
        ]);
    }

    public function test_mark_as_read_updates_status(): void
    {
        $notification = Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Test',
            'body' => 'Body',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->postJson("/notifications/{$notification->id}/read");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'read_at' => now(),
        ]);
    }

    public function test_mark_as_read_denies_other_users(): void
    {
        $otherUser = User::factory()->create();

        $notification = Notification::create([
            'user_id' => $otherUser->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Test',
            'body' => 'Body',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->postJson("/notifications/{$notification->id}/read");

        $response->assertStatus(403);
    }

    public function test_mark_all_as_read(): void
    {
        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Notif 1',
            'body' => 'Body 1',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Notif 2',
            'body' => 'Body 2',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->post('/notifications/read-all');

        $response->assertSessionHas('success');

        $unreadCount = Notification::where('user_id', $this->user->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(0, $unreadCount);
    }

    public function test_index_page_renders(): void
    {
        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Test',
            'body' => 'Body',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $response = $this->get('/notifications');

        $response->assertStatus(200);
    }

    public function test_index_page_filters_by_status(): void
    {
        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Unread',
            'body' => 'Body',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'in_app',
            'recipient' => 'in_app',
            'title' => 'Read',
            'body' => 'Body',
            'status' => 'sent',
            'sent_at' => now(),
            'read_at' => now(),
        ]);

        $response = $this->get('/notifications?status=unread');
        $response->assertStatus(200);
    }

    public function test_preferences_page_renders(): void
    {
        $response = $this->get('/notifications/preferences');

        $response->assertStatus(200);
    }

    public function test_preferences_creates_default_if_not_exists(): void
    {
        $response = $this->get('/notifications/preferences');

        $response->assertStatus(200);

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $this->user->id,
            'whatsapp_enabled' => true,
            'email_enabled' => true,
            'in_app_enabled' => true,
        ]);
    }

    public function test_update_preferences(): void
    {
        $response = $this->post('/notifications/preferences', [
            'whatsapp_enabled' => false,
            'email_enabled' => true,
            'in_app_enabled' => true,
            'quiet_hours_start' => '22:00',
            'quiet_hours_end' => '06:00',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $this->user->id,
            'whatsapp_enabled' => 0,
            'email_enabled' => 1,
            'in_app_enabled' => 1,
        ]);
    }

    public function test_update_preferences_quiet_hours_optional(): void
    {
        $response = $this->post('/notifications/preferences', [
            'whatsapp_enabled' => true,
            'email_enabled' => true,
            'in_app_enabled' => false,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_notification_preferences', [
            'user_id' => $this->user->id,
            'in_app_enabled' => 0,
        ]);
    }

    public function test_unread_endpoint_returns_empty_for_no_notifications(): void
    {
        $response = $this->getJson('/notifications/unread');

        $response->assertStatus(200);
        $response->assertJson([
            'count' => 0,
            'items' => [],
        ]);
    }
}

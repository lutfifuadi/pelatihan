<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Jobs\SendPushNotificationJob;
use App\Models\PushNotification;
use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PushNotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (! file_exists($installed)) {
            touch($installed);
        }

        // VAPID keys agar WebPushService bisa di-instantiate tanpa error
        // public key: 65 bytes, private key: 32 bytes setelah base64_decode
        config([
            'services.web_push.vapid_public_key'  => 'Te0uYp8EYcfpRctYJQo0Ocrh6N3p3kqTXeD0NMQjTtObCo2bdZQD+Au6AtPmHv7M+Ou4g4lkp9hGKZcHIr1ZdjM=',
            'services.web_push.vapid_private_key' => 'KWYquCtV+Zl7O3wdsWjBtyCpWViiEsEtgEfM9tEImwY=',
            'services.web_push.enabled'            => true,
        ]);

        $this->admin = User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $this->peserta = User::factory()->create([
            'role'      => 'peserta',
            'is_active' => true,
        ]);
    }

    // ========================================================================
    //  GET  /admin/push-notifications  (index)
    // ========================================================================

    public function test_admin_can_view_push_notifications_index(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Notif Satu',
            'body'        => 'Body satu',
            'target_type' => 'all',
        ]);
        PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Notif Dua',
            'body'        => 'Body dua',
            'target_type' => 'filtered',
        ]);

        // Act
        $response = $this->get(route('admin.push-notifications.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Notif Satu');
        $response->assertSee('Notif Dua');
    }

    public function test_non_admin_cannot_access_push_notifications_index(): void
    {
        // Arrange
        $this->actingAs($this->peserta);

        // Act
        $response = $this->get(route('admin.push-notifications.index'));

        // Assert
        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        // Act — tanpa login, auth:sanctum middleware redirect ke /login
        $response = $this->get(route('admin.push-notifications.index'));

        // Assert
        $response->assertRedirect();
    }

    // ========================================================================
    //  POST /admin/push-notifications  (store)
    // ========================================================================

    public function test_admin_can_create_push_notification_with_valid_data(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post(route('admin.push-notifications.store'), [
            'title'       => 'Notifikasi Baru',
            'body'        => 'Isi notifikasi baru',
            'target_type' => 'all',
        ]);

        // Assert
        $response->assertRedirect();

        $this->assertDatabaseHas('push_notifications', [
            'title'       => 'Notifikasi Baru',
            'body'        => 'Isi notifikasi baru',
            'admin_id'    => $this->admin->id,
            'target_type' => 'all',
        ]);
    }

    public function test_create_push_notification_requires_title(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post(route('admin.push-notifications.store'), [
            'body'        => 'Body without title',
            'target_type' => 'all',
        ]);

        // Assert
        $response->assertSessionHasErrors('title');
    }

    public function test_create_push_notification_requires_body(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post(route('admin.push-notifications.store'), [
            'title'       => 'Title without body',
            'target_type' => 'all',
        ]);

        // Assert
        $response->assertSessionHasErrors('body');
    }

    public function test_create_push_notification_requires_valid_target_type(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post(route('admin.push-notifications.store'), [
            'title'       => 'Invalid Target',
            'body'        => 'Body',
            'target_type' => 'invalid-type',
        ]);

        // Assert
        $response->assertSessionHasErrors('target_type');
    }

    public function test_create_push_notification_with_filtered_target(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        // Act
        $response = $this->post(route('admin.push-notifications.store'), [
            'title'          => 'Filtered Push',
            'body'           => 'Only filtered users',
            'target_type'    => 'filtered',
            'target_filters' => [
                'status' => ['pending', 'approved'],
            ],
        ]);

        // Assert
        $response->assertRedirect();

        $this->assertDatabaseHas('push_notifications', [
            'title'       => 'Filtered Push',
            'target_type' => 'filtered',
        ]);
    }

    // ========================================================================
    //  GET  /admin/push-notifications/{id}  (show)
    // ========================================================================

    public function test_admin_can_view_push_notification_detail(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        $notification = PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Detail Notif',
            'body'        => 'Isi detail',
            'target_type' => 'all',
        ]);

        // Act
        $response = $this->get(route('admin.push-notifications.show', $notification));

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Detail Notif');
        $response->assertSee('Isi detail');
    }

    // ========================================================================
    //  POST /admin/push-notifications/{id}/send
    // ========================================================================

    public function test_send_push_notification_returns_success_when_no_subscriptions(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        $notification = PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Send Test',
            'body'        => 'Will be sent (0 targets)',
            'target_type' => 'all',
            'sent_at'     => null,
        ]);

        // Act
        $response = $this->post(route('admin.push-notifications.send', $notification));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHas(
            'success',
            'Notifikasi sedang dikirim di background. Pantau riwayat untuk hasil.'
        );
    }

    public function test_send_already_sent_notification_returns_error(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        $notification = PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Already Sent',
            'body'        => 'Already sent body',
            'target_type' => 'all',
            'sent_at'     => now(),
        ]);

        // Act
        $response = $this->post(route('admin.push-notifications.send', $notification));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Notifikasi sudah pernah dikirim.');
    }

    public function test_send_with_active_subscriptions_dispatches_job(): void
    {
        // Arrange
        $this->actingAs($this->admin);

        Queue::fake();

        // Buat subscription aktif
        PushSubscription::create([
            'endpoint'      => 'https://fcm.googleapis.com/send-1',
            'p256dh_key'    => 'key1',
            'auth_key'      => 'auth1',
            'subscribed_at' => now(),
            'expired_at'    => null,
        ]);

        $notification = PushNotification::create([
            'admin_id'    => $this->admin->id,
            'title'       => 'Has Targets',
            'body'        => 'Should have 1 target',
            'target_type' => 'all',
            'sent_at'     => null,
        ]);

        // Act
        $response = $this->post(route('admin.push-notifications.send', $notification));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Notifikasi sedang dikirim di background. Pantau riwayat untuk hasil.');

        Queue::assertPushed(SendPushNotificationJob::class);

        // total_target harus terisi (estimasi sebelum dispatch job)
        $notification->refresh();
        $this->assertEquals(1, $notification->total_target);
        // sent_at tetap null karena pengiriman via job (background), dan Queue::fake() mencegah eksekusi job
        $this->assertNull($notification->sent_at);
    }
}

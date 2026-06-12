<?php

namespace Tests\Feature;

use App\Jobs\SendWhatsAppNotification;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Models\UserNotificationPreference;
use App\Services\NotificationService;
use App\Services\NotificationTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private NotificationService $notificationService;
    private User $user;
    private NotificationTemplate $template;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $templateService = $this->app->make(NotificationTemplateService::class);
        $this->notificationService = new NotificationService($templateService);

        $this->user = User::factory()->create([
            'whatsapp' => '6281234567890',
        ]);

        $this->template = NotificationTemplate::create([
            'key' => 'welcome_peserta',
            'name' => 'Welcome Peserta',
            'title' => 'Halo {nama}',
            'body' => 'Selamat datang di pelatihan {pelatihan}',
            'channel' => 'in_app',
            'is_active' => true,
        ]);
    }

    public function test_send_by_template_creates_notification(): void
    {
        $result = $this->notificationService->sendByTemplate(
            $this->user,
            'welcome_peserta',
            ['nama' => 'Andi', 'pelatihan' => 'Microsoft Office']
        );

        $this->assertNotNull($result);
        $this->assertDatabaseHas('notifications', [
            'id' => $result->id,
            'user_id' => $this->user->id,
            'notification_template_id' => $this->template->id,
            'channel' => 'in_app',
            'status' => 'sent',
        ]);
        $this->assertNotNull($result->sent_at);
        $this->assertEquals('Halo Andi', $result->title);
        $this->assertEquals('Selamat datang di pelatihan Microsoft Office', $result->body);
    }

    public function test_send_by_template_returns_null_when_template_not_found(): void
    {
        $result = $this->notificationService->sendByTemplate(
            $this->user,
            'non_existent_key',
            ['nama' => 'Andi']
        );

        $this->assertNull($result);
        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_cannot_send_whatsapp_when_user_disabled_preference(): void
    {
        UserNotificationPreference::create([
            'user_id' => $this->user->id,
            'whatsapp_enabled' => false,
            'email_enabled' => true,
            'in_app_enabled' => true,
        ]);

        $this->template->update(['channel' => 'whatsapp']);

        $result = $this->notificationService->sendByTemplate(
            $this->user,
            'welcome_peserta',
            ['nama' => 'Andi', 'pelatihan' => 'Microsoft Office']
        );

        $this->assertNull($result);
    }

    public function test_cannot_send_whatsapp_when_no_number(): void
    {
        $userWithoutWA = User::factory()->create(['whatsapp' => null]);

        $result = $this->notificationService->send(
            $userWithoutWA,
            'whatsapp',
            'Test Title',
            'Test Body'
        );

        $this->assertNull($result);
    }

    public function test_render_template_replaces_placeholders(): void
    {
        $rendered = $this->notificationService->renderTemplate($this->template, [
            'nama' => 'Budi',
            'pelatihan' => 'Web Dev',
        ]);

        $this->assertEquals('Halo Budi', $rendered['title']);
        $this->assertEquals('Selamat datang di pelatihan Web Dev', $rendered['body']);
    }

    public function test_render_template_replaces_app_name_placeholder(): void
    {
        $template = NotificationTemplate::create([
            'key' => 'test_app_name',
            'name' => 'Test App Name',
            'title' => 'Welcome to {app_name}',
            'body' => 'Hello from {app_name}',
            'channel' => 'in_app',
            'is_active' => true,
        ]);

        $rendered = $this->notificationService->renderTemplate($template, []);

        $this->assertEquals('Welcome to ' . config('app.name'), $rendered['title']);
        $this->assertEquals('Hello from ' . config('app.name'), $rendered['body']);
    }

    public function test_send_creates_notification_without_template(): void
    {
        $result = $this->notificationService->send(
            $this->user,
            'in_app',
            'Direct Title',
            'Direct Body',
            ['custom_key' => 'custom_value']
        );

        $this->assertNotNull($result);
        $this->assertNull($result->notification_template_id);
        $this->assertEquals('Direct Title', $result->title);
        $this->assertEquals('Direct Body', $result->body);
        $this->assertEquals('sent', $result->status);
        $this->assertEquals(['custom_key' => 'custom_value'], $result->data);
    }

    public function test_send_creates_pending_notification_for_whatsapp(): void
    {
        $result = $this->notificationService->send(
            $this->user,
            'whatsapp',
            'WA Title',
            'WA Body'
        );

        $this->assertNotNull($result);
        $this->assertEquals('whatsapp', $result->channel);
        $this->assertEquals('pending', $result->status);
        $this->assertEquals('6281234567890', $result->recipient);
        $this->assertNull($result->sent_at);
    }

    public function test_cannot_send_email_when_no_email(): void
    {
        $userWithoutEmail = User::factory()->create(['email' => '']);

        $result = $this->notificationService->send(
            $userWithoutEmail,
            'email',
            'Email Title',
            'Email Body'
        );

        $this->assertNull($result);
    }

    public function test_canNotify_returns_true_without_preferences(): void
    {
        $result = $this->notificationService->canNotify($this->user, 'whatsapp');

        $this->assertTrue($result);
    }

    public function test_canNotify_returns_false_when_disabled(): void
    {
        UserNotificationPreference::create([
            'user_id' => $this->user->id,
            'whatsapp_enabled' => false,
            'email_enabled' => true,
            'in_app_enabled' => true,
        ]);

        $this->assertFalse($this->notificationService->canNotify($this->user, 'whatsapp'));
        $this->assertTrue($this->notificationService->canNotify($this->user, 'email'));
        $this->assertTrue($this->notificationService->canNotify($this->user, 'in_app'));
    }

    public function test_canNotify_respects_quiet_hours(): void
    {
        UserNotificationPreference::create([
            'user_id' => $this->user->id,
            'whatsapp_enabled' => true,
            'quiet_hours_start' => '00:00',
            'quiet_hours_end' => '23:59',
        ]);

        $this->assertFalse($this->notificationService->canNotify($this->user, 'whatsapp'));
    }

    public function test_process_pending_notifications_dispatches_jobs(): void
    {
        Queue::fake();

        Notification::create([
            'user_id' => $this->user->id,
            'channel' => 'whatsapp',
            'recipient' => '6281234567890',
            'title' => 'Test',
            'body' => 'Test body',
            'status' => 'pending',
        ]);

        $processed = $this->notificationService->processPendingNotifications();

        $this->assertEquals(1, $processed);

        // Pastikan job di-dispatch (tidak langsung send)
        Queue::assertPushed(SendWhatsAppNotification::class, function ($job) {
            return $job->recipient === '6281234567890' && $job->notificationId > 0;
        });

        // Status tetap 'pending' karena job belum diproses
        $this->assertDatabaseHas('notifications', [
            'status' => 'pending',
        ]);
    }
}

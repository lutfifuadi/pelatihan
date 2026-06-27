<?php

namespace Tests\Unit\Services;

use App\Models\PushNotification;
use App\Models\PushSubscription;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPushServiceTest extends TestCase
{
    use RefreshDatabase;

    private WebPushService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Some providers / middleware check for this file
        $installed = storage_path('installed');
        if (! file_exists($installed)) {
            touch($installed);
        }

        // Set valid VAPID keys agar konstruktor WebPush tidak error
        // public key: 65 bytes, private key: 32 bytes setelah base64_decode
        config([
            'services.web_push.vapid_public_key'  => 'Te0uYp8EYcfpRctYJQo0Ocrh6N3p3kqTXeD0NMQjTtObCo2bdZQD+Au6AtPmHv7M+Ou4g4lkp9hGKZcHIr1ZdjM=',
            'services.web_push.vapid_private_key' => 'KWYquCtV+Zl7O3wdsWjBtyCpWViiEsEtgEfM9tEImwY=',
            'services.web_push.enabled'            => true,
        ]);

        $this->service = new WebPushService();
    }

    // ========================================================================
    //  countTargets()
    // ========================================================================

    public function test_count_targets_returns_correct_number_of_active_subscriptions(): void
    {
        // Arrange
        PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/test-1',
            'p256dh_key'     => 'key1',
            'auth_key'       => 'auth1',
            'subscribed_at'  => now(),
            'expired_at'     => null,
        ]);
        PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/test-2',
            'p256dh_key'     => 'key2',
            'auth_key'       => 'auth2',
            'subscribed_at'  => now(),
            'expired_at'     => now()->addDay(),     // masih aktif
        ]);
        // Expired — tidak dihitung
        PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/test-3',
            'p256dh_key'     => 'key3',
            'auth_key'       => 'auth3',
            'subscribed_at'  => now(),
            'expired_at'     => now()->subDay(),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $notification = PushNotification::create([
            'admin_id'    => $admin->id,
            'title'       => 'Count Test',
            'body'        => 'Body',
            'target_type' => 'all',
        ]);

        // Act
        $count = $this->service->countTargets($notification);

        // Assert
        $this->assertEquals(2, $count);
    }

    // ========================================================================
    //  getTargetSubscriptions()
    // ========================================================================

    public function test_get_target_subscriptions_returns_only_non_expired(): void
    {
        // Arrange
        $active = PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/active-1',
            'p256dh_key'     => 'k1',
            'auth_key'       => 'a1',
            'subscribed_at'  => now(),
            'expired_at'     => null,
        ]);
        $alsoActive = PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/active-2',
            'p256dh_key'     => 'k2',
            'auth_key'       => 'a2',
            'subscribed_at'  => now(),
            'expired_at'     => now()->addDay(),
        ]);
        PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/expired-1',
            'p256dh_key'     => 'k3',
            'auth_key'       => 'a3',
            'subscribed_at'  => now(),
            'expired_at'     => now()->subDay(),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $notification = PushNotification::create([
            'admin_id'    => $admin->id,
            'title'       => 'Target Test',
            'body'        => 'Body',
            'target_type' => 'all',
        ]);

        // Act
        $subscriptions = $this->service->getTargetSubscriptions($notification);

        // Assert
        $this->assertCount(2, $subscriptions);
        $this->assertTrue($subscriptions->pluck('id')->contains($active->id));
        $this->assertTrue($subscriptions->pluck('id')->contains($alsoActive->id));
    }

    // ========================================================================
    //  deleteExpiredSubscription()
    // ========================================================================

    public function test_delete_expired_subscription_marks_subscription_as_expired(): void
    {
        // Arrange
        $subscription = PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/to-expire',
            'p256dh_key'     => 'key',
            'auth_key'       => 'auth',
            'subscribed_at'  => now(),
            'expired_at'     => null,
        ]);

        // Act
        $this->service->deleteExpiredSubscription($subscription);

        // Assert
        $this->assertNotNull($subscription->fresh()->expired_at);
        $this->assertTrue($subscription->fresh()->expired_at->lte(now()));
    }

    // ========================================================================
    //  send() — tanpa subscription
    // ========================================================================

    public function test_send_returns_zero_summary_when_no_subscriptions(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $notification = PushNotification::create([
            'admin_id'    => $admin->id,
            'title'       => 'Empty Test',
            'body'        => 'No targets',
            'target_type' => 'all',
        ]);

        // Act
        $result = $this->service->send($notification);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('failed', $result);
        $this->assertArrayHasKey('expired', $result);

        $this->assertSame([
            'total'   => 0,
            'success' => 0,
            'failed'  => 0,
            'expired' => 0,
        ], $result);

        // sent_at & total_target tetap diupdate meskipun 0 target
        $notification->refresh();
        $this->assertNotNull($notification->sent_at);
        $this->assertSame(0, $notification->total_target);
    }

    // ========================================================================
    //  send() — disabled via config
    // ========================================================================

    public function test_send_does_not_send_when_push_disabled_in_config(): void
    {
        // Arrange
        config(['services.web_push.enabled' => false]);

        // Ada subscription aktif tapi fitur disabled → tetap 0
        PushSubscription::create([
            'endpoint'       => 'https://fcm.googleapis.com/disabled-test',
            'p256dh_key'     => 'key',
            'auth_key'       => 'auth',
            'subscribed_at'  => now(),
            'expired_at'     => null,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $notification = PushNotification::create([
            'admin_id'    => $admin->id,
            'title'       => 'Disabled Test',
            'body'        => 'Should not send',
            'target_type' => 'all',
        ]);

        // Act
        $result = $this->service->send($notification);

        // Assert
        $this->assertSame([
            'total'   => 0,
            'success' => 0,
            'failed'  => 0,
            'expired' => 0,
        ], $result);
    }

    // ========================================================================
    //  send() — format summary array
    // ========================================================================

    public function test_send_returns_correct_summary_array_structure(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'admin']);
        $notification = PushNotification::create([
            'admin_id'    => $admin->id,
            'title'       => 'Summary Format Test',
            'body'        => 'Check keys',
            'target_type' => 'all',
        ]);

        // Act
        $result = $this->service->send($notification);

        // Assert — struktur array yang diharapkan oleh controller
        $this->assertIsArray($result);
        $this->assertEquals(
            ['total', 'success', 'failed', 'expired'],
            array_keys($result)
        );
        $this->assertIsInt($result['total']);
        $this->assertIsInt($result['success']);
        $this->assertIsInt($result['failed']);
        $this->assertIsInt($result['expired']);
    }
}

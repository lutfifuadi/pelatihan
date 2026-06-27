<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\PushSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PushSubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (! file_exists($installed)) {
            touch($installed);
        }
    }

    // ========================================================================
    //  POST /api/push/subscribe
    // ========================================================================

    public function test_subscribe_returns_201_with_valid_data(): void
    {
        // Arrange
        $payload = [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test-abc-123',
            'keys'     => [
                'p256dh' => 'BNcRvLqHN8x9zJWk9xExampleP256dhKey',
                'auth'   => '9x9zJWk9xExampleAuthKey',
            ],
        ];

        // Act
        $response = $this->postJson('/api/push/subscribe', $payload);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['id', 'platform'],
        ]);
        $response->assertJson([
            'success' => true,
            'message' => 'Subscription berhasil disimpan.',
        ]);

        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint'   => $payload['endpoint'],
            'p256dh_key' => $payload['keys']['p256dh'],
            'auth_key'   => $payload['keys']['auth'],
        ]);
    }

    public function test_subscribe_updates_existing_subscription_with_same_endpoint(): void
    {
        // Arrange
        PushSubscription::create([
            'endpoint'      => 'https://fcm.googleapis.com/fcm/send/same-endpoint',
            'p256dh_key'    => 'old-key',
            'auth_key'      => 'old-auth',
            'subscribed_at' => now()->subDay(),
            'expired_at'    => null,
        ]);

        // Act — kirim ulang dengan endpoint sama, keys berbeda
        $response = $this->postJson('/api/push/subscribe', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/same-endpoint',
            'keys'     => [
                'p256dh' => 'new-key',
                'auth'   => 'new-auth',
            ],
        ]);

        // Assert
        $response->assertStatus(201);

        // Hanya 1 record — bukan duplikat
        $this->assertEquals(1, PushSubscription::count());

        // Data sudah di-update
        $this->assertDatabaseHas('push_subscriptions', [
            'endpoint'   => 'https://fcm.googleapis.com/fcm/send/same-endpoint',
            'p256dh_key' => 'new-key',
            'auth_key'   => 'new-auth',
        ]);
    }

    public function test_subscribe_returns_422_when_data_missing(): void
    {
        // Act
        $response = $this->postJson('/api/push/subscribe', []);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['endpoint', 'keys']);
    }

    public function test_subscribe_returns_422_when_keys_incomplete(): void
    {
        // Act
        $response = $this->postJson('/api/push/subscribe', [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/test',
            'keys'     => [
                'p256dh' => 'only-p256dh',   // auth missing
            ],
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['keys.auth']);
    }

    public function test_subscribe_returns_422_when_endpoint_not_url(): void
    {
        // Act
        $response = $this->postJson('/api/push/subscribe', [
            'endpoint' => 'not-a-valid-url',
            'keys'     => [
                'p256dh' => 'some-key',
                'auth'   => 'some-auth',
            ],
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['endpoint']);
    }

    // ========================================================================
    //  GET /api/push/vapid-public-key
    // ========================================================================

    public function test_vapid_public_key_returns_200_with_key(): void
    {
        // Arrange
        config(['services.web_push.vapid_public_key' => 'Te0uYp8EYcfpRctYJQo0Ocrh6N3p3kqTXeD0NMQjTtObCo2bdZQD+Au6AtPmHv7M+Ou4g4lkp9hGKZcHIr1ZdjM=']);

        // Act
        $response = $this->getJson('/api/push/vapid-public-key');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'publicKey',
        ]);
        $response->assertJson([
            'success'   => true,
            'publicKey' => 'Te0uYp8EYcfpRctYJQo0Ocrh6N3p3kqTXeD0NMQjTtObCo2bdZQD+Au6AtPmHv7M+Ou4g4lkp9hGKZcHIr1ZdjM=',
        ]);
    }

    public function test_vapid_public_key_returns_503_without_config(): void
    {
        // Arrange — pastikan key tidak tersedia di config maupun env
        config(['services.web_push.vapid_public_key' => null]);
        unset($_ENV['VAPID_PUBLIC_KEY']);
        unset($_SERVER['VAPID_PUBLIC_KEY']);
        putenv('VAPID_PUBLIC_KEY');

        // Act
        $response = $this->getJson('/api/push/vapid-public-key');

        // Assert
        $response->assertStatus(503);
        $response->assertJson([
            'success' => false,
            'message' => 'VAPID public key belum dikonfigurasi.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushSubscriptionRequest;
use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    /**
     * Simpan atau update subscription push notification.
     */
    public function store(StorePushSubscriptionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $endpoint = $validated['endpoint'];
        $keys = $validated['keys'];

        $userAgent = $request->header('User-Agent');
        $platform = $this->detectPlatform($userAgent);

        try {
            $subscription = PushSubscription::updateOrCreate(
                ['endpoint' => $endpoint],
                [
                    'user_id' => auth()->id(),
                    'p256dh_key' => $keys['p256dh'],
                    'auth_key' => $keys['auth'],
                    'user_agent' => $userAgent,
                    'platform' => $platform,
                    'subscribed_at' => now(),
                    'expired_at' => null,
                ]
            );

            Log::info('Push subscription saved', [
                'subscription_id' => $subscription->id,
                'user_id' => auth()->id(),
                'platform' => $platform,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription berhasil disimpan.',
                'data' => [
                    'id' => $subscription->id,
                    'platform' => $subscription->platform,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to save push subscription', [
                'error' => $e->getMessage(),
                'endpoint' => $endpoint,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan subscription.',
            ], 500);
        }
    }

    /**
     * Kembalikan VAPID public key untuk digunakan frontend.
     */
    public function vapidPublicKey(): JsonResponse
    {
        $publicKey = config('services.web_push.vapid_public_key')
            ?? env('VAPID_PUBLIC_KEY');

        if (empty($publicKey)) {
            Log::warning('VAPID public key belum dikonfigurasi.');

            return response()->json([
                'success' => false,
                'message' => 'VAPID public key belum dikonfigurasi.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'publicKey' => $publicKey,
        ]);
    }

    /**
     * Deteksi platform dari user-agent string.
     */
    private function detectPlatform(?string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $ua = strtolower($userAgent);

        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad') || str_contains($ua, 'ipod')) {
            return 'ios';
        }

        if (str_contains($ua, 'android')) {
            return 'android';
        }

        if (str_contains($ua, 'windows') || str_contains($ua, 'macintosh') || str_contains($ua, 'linux')) {
            return 'desktop';
        }

        return 'unknown';
    }
}

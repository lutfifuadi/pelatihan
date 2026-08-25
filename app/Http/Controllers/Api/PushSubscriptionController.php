<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushSubscriptionRequest;
use App\Models\PushSubscription;
use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    /**
     * Simpan atau update subscription push notification (Publik / Standar).
     */
    public function store(StorePushSubscriptionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $endpoint = $validated['endpoint'];
        $keys = $validated['keys'];

        $userAgent = $request->header('User-Agent');
        $platform = $this->detectPlatform($userAgent);
        $endpointHash = hash('sha256', $endpoint);

        try {
            $subscription = PushSubscription::updateOrCreate(
                ['endpoint' => $endpoint],
                [
                    'endpoint_hash' => $endpointHash,
                    'user_id' => auth()->id(),
                    'p256dh_key' => $keys['p256dh'],
                    'auth_key' => $keys['auth'],
                    'content_encoding' => $request->input('content_encoding', 'aes128gcm'),
                    'user_agent' => $userAgent,
                    'platform' => $platform,
                    'is_active' => true,
                    'failed_count' => 0,
                    'last_failed_at' => null,
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
     * Pendaftaran / pembaruan push subscription akun User login (Peserta / Admin).
     */
    public function subscribeUser(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'endpoint'         => 'required|string',
            'keys.p256dh'      => 'required|string',
            'keys.auth'        => 'required|string',
            'device_label'     => 'nullable|string|max:150',
            'browser'          => 'nullable|string|max:50',
            'content_encoding' => 'nullable|string|max:30',
        ]);

        $endpoint = $request->input('endpoint');
        $endpointHash = hash('sha256', $endpoint);
        $userAgent = $request->header('User-Agent');
        $platform = $this->detectPlatform($userAgent);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint_hash' => $endpointHash],
            [
                'user_id'          => $user->id,
                'endpoint'         => $endpoint,
                'p256dh_key'       => $request->input('keys.p256dh'),
                'auth_key'         => $request->input('keys.auth'),
                'content_encoding' => $request->input('content_encoding', 'aes128gcm'),
                'device_label'     => $request->input('device_label', 'Browser Perangkat'),
                'browser'          => $request->input('browser', $platform),
                'platform'         => $platform,
                'user_agent'       => $userAgent,
                'is_active'        => true,
                'failed_count'     => 0,
                'last_failed_at'   => null,
                'subscribed_at'    => now(),
                'expired_at'       => null,
            ]
        );

        return response()->json([
            'success'  => true,
            'message'  => 'Notifikasi browser berhasil diaktifkan untuk akun ' . $user->name . '.',
            'token_id' => $subscription->id,
        ]);
    }

    /**
     * Unsubscribe push notification untuk akun User login.
     */
    public function unsubscribeUser(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $endpointHash = hash('sha256', $request->endpoint);
        $deleted = PushSubscription::where('endpoint_hash', $endpointHash)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => $deleted ? 'Berhasil menonaktifkan notifikasi browser.' : 'Perangkat tidak ditemukan.',
        ]);
    }

    /**
     * Cek status subscription push notification user login.
     */
    public function getUserStatus(Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['active' => false, 'matched' => false], 401);
        }

        $endpoint = $request->input('endpoint');
        if (!$endpoint) {
            $hasActive = PushSubscription::where('user_id', $user->id)->active()->exists();
            return response()->json([
                'active'  => $hasActive,
                'matched' => $hasActive,
            ]);
        }

        $endpointHash = hash('sha256', $endpoint);
        $sub = PushSubscription::where('endpoint_hash', $endpointHash)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'active'        => $sub ? (bool) $sub->is_active : false,
            'matched'       => (bool) $sub,
            'registered_at' => $sub?->subscribed_at?->format('d M Y H:i'),
        ]);
    }

    /**
     * Test push notification instan ke akun user login.
     */
    public function testUserPush(Request $request, WebPushService $webPushService): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $namaUser = $user->name ?? 'Pengguna';
        $payload = [
            'title'   => '🔔 Uji Coba Notifikasi Pelatihanku',
            'body'    => "Halo {$namaUser}! Ini adalah notifikasi uji coba dari Pelatihanku. Perangkat Anda siap menerima info pelatihan.",
            'url'     => url('/dashboard/peserta'),
            'tag'     => 'test-push-' . time(),
        ];

        $res = $webPushService->sendToUser($user, $payload);

        if ($res['success']) {
            return response()->json([
                'success' => true,
                'message' => "Notifikasi berhasil dikirim ke {$res['sent_count']} perangkat aktif Anda.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $res['message'] ?? 'Gagal mengirim notifikasi uji coba. Pastikan izin notifikasi browser telah aktif.',
        ], 422);
    }

    /**
     * Handler jika browser mereset push subscription key secara otomatis.
     */
    public function refreshSubscription(Request $request): JsonResponse
    {
        $oldEndpoint = $request->input('old_endpoint');
        $newSub = $request->input('new_subscription');

        if (!$newSub || empty($newSub['endpoint'])) {
            return response()->json(['success' => false], 400);
        }

        $newEndpoint = $newSub['endpoint'];
        $newHash = hash('sha256', $newEndpoint);

        if ($oldEndpoint) {
            $oldHash = hash('sha256', $oldEndpoint);
            $token = PushSubscription::where('endpoint_hash', $oldHash)->first();
            if ($token) {
                $token->update([
                    'endpoint' => $newEndpoint,
                    'endpoint_hash' => $newHash,
                    'p256dh_key' => $newSub['keys']['p256dh'] ?? $token->p256dh_key,
                    'auth_key' => $newSub['keys']['auth'] ?? $token->auth_key,
                    'is_active' => true,
                ]);
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => true]);
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


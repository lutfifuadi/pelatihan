<?php

namespace App\Services;

use App\Models\PushNotification;
use App\Models\PushNotificationRecipient;
use App\Models\PushSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\MessageSentReport;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    private WebPush $webPush;

    private int $batchSize;

    public function __construct()
    {
        $this->batchSize = (int) config('services.web_push.batch_size', 100);

        $auth = [
            'VAPID' => [
                'subject' => config('services.web_push.vapid_subject', 'mailto:admin@pelatihanku.com'),
                'publicKey' => $this->getVapidPublicKey(),
                'privateKey' => $this->getVapidPrivateKey(),
            ],
        ];

        $this->webPush = new WebPush($auth);
        $this->webPush->setReuseVAPIDHeaders(true);
    }

    private function getVapidPublicKey(): ?string
    {
        return \Illuminate\Support\Facades\Cache::remember('vapid_public_key', 3600, function () {
            $setting = \App\Models\Setting::where('key', 'vapid_public_key')->first();
            return $setting?->value ?? config('services.web_push.vapid_public_key');
        });
    }

    private function getVapidPrivateKey(): ?string
    {
        return \Illuminate\Support\Facades\Cache::remember('vapid_private_key', 3600, function () {
            $setting = \App\Models\Setting::where('key', 'vapid_private_key')->first();
            
            if (!$setting || empty($setting->value)) {
                return config('services.web_push.vapid_private_key');
            }

            // Asumsi menggunakan enkripsi bawaan Laravel
            return \Illuminate\Support\Facades\Crypt::decryptString($setting->value);
        });
    }

    /**
     * Kirim notifikasi push ke semua subscription sesuai target.
     *
     * @return array{total: int, success: int, failed: int, expired: int}
     */
    public function send(PushNotification $notification): array
    {
        if (! config('services.web_push.enabled', true)) {
            Log::warning('Push notification disabled via config.', ['notification_id' => $notification->id]);

            return ['total' => 0, 'success' => 0, 'failed' => 0, 'expired' => 0];
        }

        $subscriptions = $this->getTargetSubscriptions($notification);
        $totalTarget = $subscriptions->count();

        $notification->update([
            'total_target' => $totalTarget,
            'sent_at' => now(),
        ]);

        if ($totalTarget === 0) {
            return ['total' => 0, 'success' => 0, 'failed' => 0, 'expired' => 0];
        }

        $payload = json_encode([
            'title' => $notification->title,
            'body' => $notification->body,
            'url' => $notification->link_url ?? url('/'),
            'icon' => url('/icons/icon-192x192.png'),
            'badge' => url('/icons/badge-72x72.png'),
            'tag' => 'pelatihanku-push-'.$notification->id,
            'notification_id' => $notification->id,
        ]);

        $summary = [
            'total' => $totalTarget,
            'success' => 0,
            'failed' => 0,
            'expired' => 0,
        ];

        foreach ($subscriptions->chunk($this->batchSize) as $chunk) {
            $batchSummary = $this->sendBatch($notification, $chunk, $payload);

            $summary['success'] += $batchSummary['success'];
            $summary['failed'] += $batchSummary['failed'];
            $summary['expired'] += $batchSummary['expired'];
        }

        Log::info('Push notification sent', [
            'notification_id' => $notification->id,
            'summary' => $summary,
        ]);

        return $summary;
    }

    /**
     * Ambil subscription sesuai target notifikasi.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, PushSubscription>
     */
    public function getTargetSubscriptions(PushNotification $notification)
    {
        $query = PushSubscription::query()
            ->where(function (Builder $q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            });

        if ($notification->target_type === 'filtered' && is_array($notification->target_filters)) {
            $filters = $notification->target_filters;

            $query->where(function (Builder $q) use ($filters) {
                // Filter hanya untuk subscription yang terasosiasi dengan user
                $q->whereNotNull('user_id');

                // Filter berdasarkan status enrollment
                if (! empty($filters['status'])) {
                    $statuses = (array) $filters['status'];
                    $q->whereHas('user.enrollments', function (Builder $enrollmentQuery) use ($statuses) {
                        $enrollmentQuery->whereIn('status', $statuses);
                    });
                }

                // Filter berdasarkan daerah/kelurahan
                if (! empty($filters['daerah']) || ! empty($filters['kelurahan_id'])) {
                    $kelurahanIds = array_filter(array_merge(
                        (array) ($filters['daerah'] ?? []),
                        (array) ($filters['kelurahan_id'] ?? [])
                    ));

                    if (! empty($kelurahanIds)) {
                        $q->whereHas('user.pesertaProfile', function (Builder $profileQuery) use ($kelurahanIds) {
                            $profileQuery->whereIn('kelurahan_id', $kelurahanIds);
                        });
                    }
                }

                // Filter berdasarkan pelatihan yang diikuti
                if (! empty($filters['pelatihan']) || ! empty($filters['pelatihan_id'])) {
                    $pelatihanIds = array_filter(array_merge(
                        (array) ($filters['pelatihan'] ?? []),
                        (array) ($filters['pelatihan_id'] ?? [])
                    ));

                    if (! empty($pelatihanIds)) {
                        $q->whereHas('user.enrollments', function (Builder $enrollmentQuery) use ($pelatihanIds) {
                            $enrollmentQuery->whereIn('pelatihan_id', $pelatihanIds);
                        });
                    }
                }
            });
        }

        return $query->get();
    }

    /**
     * Kirim satu batch subscription.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, PushSubscription>  $subscriptions
     */
    private function sendBatch(PushNotification $notification, Collection $subscriptions, string $payload): array
    {
        $this->webPush->flush();

        $queuedEndpoints = [];

        foreach ($subscriptions as $subscription) {
            try {
                $pushSubscription = Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->p256dh_key,
                    'authToken' => $subscription->auth_key,
                    'contentEncoding' => 'aesgcm',
                ]);

                $this->webPush->queueNotification($pushSubscription, $payload);
                $queuedEndpoints[] = $subscription->endpoint;
            } catch (\Exception $e) {
                Log::warning('Failed to queue push notification for subscription', [
                    'notification_id' => $notification->id,
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);

                PushNotificationRecipient::create([
                    'notification_id' => $notification->id,
                    'subscription_id' => $subscription->id,
                    'status' => 'failed',
                    'error_message' => 'Invalid subscription keys: '.$e->getMessage(),
                ]);
            }
        }

        $summary = [
            'success' => 0,
            'failed' => 0,
            'expired' => 0,
        ];

        try {
            foreach ($this->webPush->flush() as $report) {
                /** @var MessageSentReport $report */
                $summary = $this->handleReport($notification, $report, $subscriptions, $summary);
            }
        } catch (\Exception $e) {
            Log::error('WebPush flush failed for batch', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            // Tandai semua subscription dalam batch ini sebagai failed
            foreach ($subscriptions as $subscription) {
                PushNotificationRecipient::create([
                    'notification_id' => $notification->id,
                    'subscription_id' => $subscription->id,
                    'status' => 'failed',
                    'error_message' => 'Batch encryption error: '.$e->getMessage(),
                ]);
                $summary['failed']++;
            }
        }

        return $summary;
    }

    /**
     * Handle hasil pengiriman dari WebPush.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, PushSubscription>  $subscriptions
     * @param  array{success: int, failed: int, expired: int}  $summary
     * @return array{success: int, failed: int, expired: int}
     */
    private function handleReport(
        PushNotification $notification,
        MessageSentReport $report,
        Collection $subscriptions,
        array $summary
    ): array {
        $endpoint = $report->getEndpoint();
        $subscription = $subscriptions->firstWhere('endpoint', $endpoint);

        if (! $subscription) {
            return $summary;
        }

        if ($report->isSuccess()) {
            PushNotificationRecipient::create([
                'notification_id' => $notification->id,
                'subscription_id' => $subscription->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
            $summary['success']++;

            return $summary;
        }

        $error = $report->getReason();
        $statusCode = $report->getResponse()?->getStatusCode();

        // 410 Gone = subscription expired/hilang
        // 404 Not Found = subscription tidak dikenali
        $isExpired = in_array($statusCode, [410, 404], true);

        if ($isExpired) {
            $this->deleteExpiredSubscription($subscription);
            $status = 'expired';
            $summary['expired']++;
        } else {
            $status = 'failed';
            $summary['failed']++;
        }

        PushNotificationRecipient::create([
            'notification_id' => $notification->id,
            'subscription_id' => $subscription->id,
            'status' => $status,
            'error_message' => $error,
        ]);

        Log::warning('Push notification delivery failed', [
            'notification_id' => $notification->id,
            'subscription_id' => $subscription->id,
            'status_code' => $statusCode,
            'error' => $error,
        ]);

        return $summary;
    }

    /**
     * Hapus subscription yang sudah expired.
     */
    public function deleteExpiredSubscription(PushSubscription $subscription): void
    {
        $subscription->update(['expired_at' => now()]);

        Log::info('Push subscription marked as expired', [
            'subscription_id' => $subscription->id,
            'platform' => $subscription->platform,
        ]);
    }

    /**
     * Kirim Web Push Notification langsung ke akun User (Peserta / Admin).
     */
    public function sendToUser(\App\Models\User|int $user, array $payloadData): array
    {
        $userId = ($user instanceof \App\Models\User) ? $user->id : $user;
        $subscriptions = PushSubscription::where('user_id', $userId)->active()->get();

        if ($subscriptions->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Tidak ada token push browser yang aktif untuk user ini.',
                'sent_count' => 0,
                'failed_count' => 0,
            ];
        }

        $sentCount = 0;
        $failedCount = 0;
        $invalidSubscriptionIds = [];
        
        $title = $payloadData['title'] ?? 'Notifikasi Pelatihanku';
        $body = $payloadData['body'] ?? 'Ada pembaruan penting untuk Anda.';
        $url = $payloadData['url'] ?? ($payloadData['data']['url'] ?? url('/dashboard/peserta'));
        $icon = $payloadData['icon'] ?? url('/icons/icon-192x192.png');
        $badge = $payloadData['badge'] ?? url('/icons/badge-72x72.png');
        $tag = $payloadData['tag'] ?? ('pelatihanku-user-' . $userId . '-' . time());

        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'icon' => $icon,
            'badge' => $badge,
            'tag' => $tag,
            'vibrate' => [200, 100, 200, 100, 200, 100, 400],
            'requireInteraction' => true,
            'data' => [
                'url' => $url,
                'user_id' => $userId,
                'timestamp' => time(),
            ],
            'actions' => [
                [
                    'action' => 'open_url',
                    'title' => 'Buka Aplikasi',
                ],
            ],
        ]);

        foreach ($subscriptions as $subscription) {
            try {
                $pushSubscription = Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->p256dh_key,
                    'authToken' => $subscription->auth_key,
                    'contentEncoding' => $subscription->content_encoding ?: 'aes128gcm',
                ]);

                $this->webPush->queueNotification($pushSubscription, $payload);
            } catch (\Throwable $e) {
                Log::warning("WebPush User queue error (Subscription ID {$subscription->id}): " . $e->getMessage());
                $failedCount++;
            }
        }

        try {
            foreach ($this->webPush->flush() as $report) {
                $endpoint = (string) $report->getRequest()->getUri();
                $matchedSub = $subscriptions->first(fn($s) => $s->endpoint === $endpoint || hash('sha256', $s->endpoint) === hash('sha256', $endpoint));

                if ($report->isSuccess()) {
                    $sentCount++;
                    if ($matchedSub) {
                        $matchedSub->update([
                            'last_used_at' => now(),
                            'failed_count' => 0,
                        ]);
                    }
                } else {
                    $failedCount++;
                    Log::warning("WebPush User delivery failed for {$endpoint}: " . $report->getReason());

                    if ($matchedSub) {
                        $matchedSub->increment('failed_count');
                        $matchedSub->update(['last_failed_at' => now()]);

                        if ($report->isSubscriptionExpired() || $matchedSub->failed_count >= 3) {
                            $invalidSubscriptionIds[] = $matchedSub->id;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error("WebPush User flush error: " . $e->getMessage());
        }

        if (!empty($invalidSubscriptionIds)) {
            PushSubscription::whereIn('id', $invalidSubscriptionIds)->update([
                'is_active' => false,
                'expired_at' => now(),
            ]);
            Log::info("WebPush User: Menonaktifkan " . count($invalidSubscriptionIds) . " subscription kedaluwarsa.");
        }

        return [
            'success' => $sentCount > 0,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
        ];
    }

    /**
     * Hitung jumlah target subscription tanpa mengirim.
     */
    public function countTargets(PushNotification $notification): int
    {
        return $this->getTargetSubscriptions($notification)->count();
    }
}

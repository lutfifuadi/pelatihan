<?php

namespace App\Jobs;

use App\Models\PushNotification;
use App\Services\WebPushService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendPushNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PushNotification $notification
    ) {
        $this->onQueue('push-notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Jangan kirim ulang jika sudah pernah dikirim
        if ($this->notification->sent_at !== null) {
            Log::info('SendPushNotificationJob: Notification already sent, skipping.', [
                'notification_id' => $this->notification->id,
                'sent_at' => $this->notification->sent_at->toIso8601String(),
            ]);

            return;
        }

        // Skip jika tidak ada target
        if ($this->notification->total_target == 0) {
            Log::info('SendPushNotificationJob: No targets to send, skipping.', [
                'notification_id' => $this->notification->id,
                'total_target' => $this->notification->total_target,
            ]);

            return;
        }

        Log::info('SendPushNotificationJob: Starting push notification delivery.', [
            'notification_id' => $this->notification->id,
            'total_target' => $this->notification->total_target,
        ]);

        try {
            /** @var WebPushService $webPushService */
            $webPushService = app(WebPushService::class);
            $summary = $webPushService->send($this->notification);

            Log::info('SendPushNotificationJob: Push notification delivered successfully.', [
                'notification_id' => $this->notification->id,
                'summary' => $summary,
            ]);
        } catch (\Exception $e) {
            Log::error('SendPushNotificationJob: Failed to send push notification.', [
                'notification_id' => $this->notification->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppNotification implements ShouldQueue
{
    use Queueable;

    public string $recipient;
    public string $message;
    public int $notificationId;

    public int $tries = 3;

    public function backoff(): array
    {
        return [5, 15, 30]; // exponential backoff: 5s, 15s, 30s
    }

    public function __construct(string $recipient, string $message, int $notificationId)
    {
        $this->recipient = $recipient;
        $this->message = $message;
        $this->notificationId = $notificationId;
        $this->onConnection('database');
    }

    public function handle(): void
    {
        $notification = Notification::find($this->notificationId);

        if (!$notification) {
            return;
        }

        $success = WhatsAppService::sendMessage($this->recipient, $this->message);

        if ($success) {
            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } else {
            $notification->update([
                'status' => 'failed',
                'failed_reason' => 'WhatsApp API returned failure after ' . $this->attempts() . ' attempt(s)',
            ]);

            throw new \RuntimeException("Failed to send WhatsApp to {$this->recipient}");
        }
    }

    public function failed(?\Throwable $exception): void
    {
        $notification = Notification::find($this->notificationId);

        if ($notification && $notification->status !== 'sent') {
            $notification->update([
                'status' => 'failed',
                'failed_reason' => $exception ? $exception->getMessage() : 'Max attempts exceeded',
            ]);
        }
    }
}

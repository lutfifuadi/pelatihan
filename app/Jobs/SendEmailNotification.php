<?php

namespace App\Jobs;

use App\Mail\NotificationMail;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification implements ShouldQueue
{
    use Queueable;

    public string $recipient;
    public string $title;
    public string $body;
    public array $data;
    public int $notificationId;

    public int $tries = 3;

    public function backoff(): array
    {
        return [5, 15, 30]; // exponential backoff: 5s, 15s, 30s
    }

    public function __construct(string $recipient, string $title, string $body, array $data, int $notificationId)
    {
        $this->recipient = $recipient;
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->notificationId = $notificationId;
        $this->onConnection('database');
    }

    public function handle(): void
    {
        $notification = Notification::find($this->notificationId);

        if (!$notification) {
            Log::warning("SendEmailNotification: Notification #{$this->notificationId} not found.");
            return;
        }

        try {
            Mail::to($this->recipient)->send(new NotificationMail(
                title: $this->title,
                body: $this->body,
                data: $this->data,
            ));

            $notification->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            Log::info("SendEmailNotification: Email sent to {$this->recipient} for notification #{$this->notificationId}");
        } catch (\Throwable $e) {
            $notification->update([
                'status' => 'failed',
                'failed_reason' => $e->getMessage(),
            ]);

            Log::error("SendEmailNotification: Failed to send email to {$this->recipient} for notification #{$this->notificationId}: {$e->getMessage()}");

            throw $e;
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

        Log::error("SendEmailNotification: Job failed for notification #{$this->notificationId} to {$this->recipient}. Reason: " . ($exception ? $exception->getMessage() : 'Max attempts exceeded'));
    }
}

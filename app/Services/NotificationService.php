<?php

namespace App\Services;

use App\Jobs\SendEmailNotification;
use App\Jobs\SendWhatsAppNotification;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Models\UserNotificationPreference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function __construct(
        private NotificationTemplateService $templateService
    ) {}

    public function sendByTemplate(User $user, string $templateKey, array $data = [], ?string $channel = null): ?Notification
    {
        $template = $this->templateService->getByKey($templateKey);

        if (!$template) {
            Log::warning("NotificationService: Template '{$templateKey}' not found or inactive.");
            return null;
        }

        $rendered = $this->renderTemplate($template, $data);
        $targetChannel = $channel ?? $template->channel ?? 'in_app';

        if (!$this->canNotify($user, $targetChannel)) {
            Log::info("NotificationService: User {$user->id} cannot receive {$targetChannel} notifications.");
            return null;
        }

        return $this->send(
            $user,
            $targetChannel,
            $rendered['title'],
            $rendered['body'],
            array_merge($data, ['template_key' => $templateKey]),
            $template->id
        );
    }

    public function send(User $user, string $channel, string $title, string $body, array $data = [], ?int $templateId = null): ?Notification
    {
        $recipient = match ($channel) {
            'whatsapp' => $user->whatsapp,
            'email' => $user->email,
            default => 'in_app',
        };

        if ($channel === 'whatsapp' && empty($recipient)) {
            Log::warning("NotificationService: User {$user->id} has no WhatsApp number.");
            return null;
        }

        if ($channel === 'email' && empty($recipient)) {
            Log::warning("NotificationService: User {$user->id} has no email address.");
            return null;
        }

        $notification = Notification::create([
            'user_id' => $user->id,
            'notification_template_id' => $templateId,
            'channel' => $channel,
            'recipient' => $recipient,
            'title' => $title,
            'body' => $body,
            'data' => $data,
            'status' => $channel === 'in_app' ? 'sent' : 'pending',
        ]);

        if ($channel === 'in_app') {
            $notification->update(['sent_at' => now()]);
            return $notification;
        }

        if ($channel === 'whatsapp' && $recipient) {
            SendWhatsAppNotification::dispatch($recipient, $body, $notification->id)
                ->onConnection('database');
        }

        if ($channel === 'email' && $recipient) {
            SendEmailNotification::dispatch($recipient, $title, $body, $data, $notification->id)
                ->onConnection('database');
        }

        return $notification;
    }

    public function renderTemplate(NotificationTemplate $template, array $data): array
    {
        $title = $template->title;
        $body = $template->body;

        $replacements = array_merge($data, [
            'app_name' => config('app.name'),
        ]);

        foreach ($replacements as $placeholder => $value) {
            $title = str_replace('{' . $placeholder . '}', (string) $value, $title);
            $body = str_replace('{' . $placeholder . '}', (string) $value, $body);
        }

        return [
            'title' => $title,
            'body' => $body,
        ];
    }

    public function sendWhatsApp(string $number, string $message): bool
    {
        return WhatsAppService::sendMessage($number, $message);
    }

    public function canNotify(User $user, string $channel): bool
    {
        $preference = UserNotificationPreference::where('user_id', $user->id)->first();

        if (!$preference) {
            return true;
        }

        $enabled = match ($channel) {
            'whatsapp' => $preference->whatsapp_enabled,
            'email' => $preference->email_enabled,
            'in_app' => $preference->in_app_enabled,
            default => true,
        };

        if (!$enabled) {
            return false;
        }

        if ($preference->quiet_hours_start && $preference->quiet_hours_end) {
            $now = Carbon::now()->format('H:i:s');
            $start = $preference->quiet_hours_start;
            $end = $preference->quiet_hours_end;

            if ($start <= $end) {
                if ($now >= $start && $now <= $end) {
                    return false;
                }
            } else {
                if ($now >= $start || $now <= $end) {
                    return false;
                }
            }
        }

        return true;
    }

    public function processPendingNotifications(): int
    {
        $pending = Notification::where('status', 'pending')
            ->where('channel', 'whatsapp')
            ->whereNull('sent_at')
            ->get();

        $processed = 0;

        foreach ($pending as $notification) {
            // Dispatch ke queue job (bukan kirim langsung) — konsisten dengan send()
            SendWhatsAppNotification::dispatch(
                $notification->recipient,
                $notification->body,
                $notification->id
            )->onConnection('database');

            $processed++;
        }

        // Proses juga notifikasi email yang pending
        $pendingEmail = Notification::where('status', 'pending')
            ->where('channel', 'email')
            ->whereNull('sent_at')
            ->get();

        foreach ($pendingEmail as $notification) {
            SendEmailNotification::dispatch(
                $notification->recipient,
                $notification->title,
                $notification->body,
                $notification->data ?? [],
                $notification->id
            )->onConnection('database');

            $processed++;
        }

        return $processed;
    }
}

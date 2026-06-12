<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendTestNotification extends Command
{
    protected $signature = 'notifications:test {user? : User ID untuk test}
        {--channel=whatsapp : Channel notifikasi (whatsapp/in-app)}
        {--template=welcome_peserta : Template key notifikasi}';

    protected $description = 'Kirim notifikasi test ke user tertentu';

    public function handle(NotificationService $notificationService): int
    {
        $userId = $this->argument('user');
        $channel = $this->option('channel');
        $template = $this->option('template');

        $user = $userId ? User::find($userId) : User::first();

        if (!$user) {
            $this->error('User tidak ditemukan.');
            return self::FAILURE;
        }

        $this->info("Mengirim notifikasi ke user: {$user->name} (ID: {$user->id})");
        $this->line("Channel: {$channel}");
        $this->line("Template: {$template}");

        $notification = $notificationService->sendByTemplate(
            $user,
            $template,
            ['nama_peserta' => $user->name],
            $channel
        );

        if ($notification) {
            $this->info("Notifikasi berhasil dikirim (ID: {$notification->id}, Status: {$notification->status}).");
            return self::SUCCESS;
        }

        $this->error('Gagal mengirim notifikasi. Periksa template dan preferensi user.');
        return self::FAILURE;
    }
}

<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class ProcessNotificationQueue extends Command
{
    protected $signature = 'notifications:process-queue';

    protected $description = 'Proses semua notifikasi yang masih pending di queue';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Memproses notifikasi pending...');

        $processed = $notificationService->processPendingNotifications();

        $this->info("Jumlah notifikasi diproses: {$processed}.");

        return self::SUCCESS;
    }
}

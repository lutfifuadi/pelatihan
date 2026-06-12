<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPendingNotifications implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->onConnection('database');
    }

    public function handle(NotificationService $notificationService): void
    {
        $notificationService->processPendingNotifications();
    }
}

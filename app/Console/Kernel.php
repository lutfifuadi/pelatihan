<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('notifications:send-reminders')
            ->dailyAt('07:00')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/notification-reminders.log'));

        $schedule->command('notifications:process-queue')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/notification-queue.log'));

        $schedule->command('notifications:cleanup 30 --force')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/notification-cleanup.log'));
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

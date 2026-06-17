<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ---------------------------------------------------------------------------
//  Backup Database — Jadwal Otomatis
// ---------------------------------------------------------------------------

Schedule::command('backup:database --compress --keep=30')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/backup-scheduler.log'))
    ->description('Backup database harian (kompres, simpan 30 hari)');

Schedule::command('backup:clean --keep=30')
    ->weeklyOn(0, '03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/backup-scheduler.log'))
    ->description('Bersihkan backup database yang lebih dari 30 hari');

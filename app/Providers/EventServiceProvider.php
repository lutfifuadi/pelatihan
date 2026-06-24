<?php

namespace App\Providers;

use App\Listeners\SendNotificationListener;
use App\Listeners\SyncMaintenanceModeListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        SendNotificationListener::class,
        SyncMaintenanceModeListener::class,
    ];

    public function boot(): void
    {
        parent::boot();
    }
}

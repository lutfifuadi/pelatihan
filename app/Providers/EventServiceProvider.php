<?php

namespace App\Providers;

use App\Listeners\SendNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        SendNotificationListener::class,
    ];

    public function boot(): void
    {
        parent::boot();
    }
}

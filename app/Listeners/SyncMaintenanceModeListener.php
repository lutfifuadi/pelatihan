<?php

namespace App\Listeners;

use App\Models\Setting;
use Illuminate\Foundation\Events\MaintenanceModeDisabled;
use Illuminate\Foundation\Events\MaintenanceModeEnabled;
use Illuminate\Support\Facades\Cache;

class SyncMaintenanceModeListener
{
    public function subscribe($events): void
    {
        $events->listen(
            MaintenanceModeEnabled::class,
            [self::class, 'handleEnabled']
        );

        $events->listen(
            MaintenanceModeDisabled::class,
            [self::class, 'handleDisabled']
        );
    }

    public function handleEnabled(): void
    {
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => '1', 'group' => 'general', 'label' => 'Mode Maintenance']
        );
        Cache::forget('setting.maintenance_mode');
    }

    public function handleDisabled(): void
    {
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => '0', 'group' => 'general', 'label' => 'Mode Maintenance']
        );
        Cache::forget('setting.maintenance_mode');
    }
}

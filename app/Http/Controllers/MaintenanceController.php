<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenanceMode = Cache::remember('setting.maintenance_mode', 60, function () {
            return Setting::where('key', 'maintenance_mode')->value('value');
        });

        if ($maintenanceMode !== '1') {
            return redirect('/');
        }

        $settings = Setting::whereIn('key', [
            'maintenance_title',
            'maintenance_message',
            'maintenance_estimated_time',
        ])->get()->keyBy('key');

        return view('errors.maintenance', [
            'title' => $settings['maintenance_title']->value ?? 'Sistem Sedang Dalam Pemeliharaan',
            'message' => $settings['maintenance_message']->value ?? 'Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan. Silakan kembali lagi nanti.',
            'estimatedTime' => $settings['maintenance_estimated_time']->value ?? null,
        ]);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/*') || $request->is('admin')) {
            return $next($request);
        }

        if ($request->is('login') || $request->is('install*') || $request->is('maintenance') || $request->is('up')) {
            return $next($request);
        }

        $maintenanceMode = Cache::remember('setting.maintenance_mode', 60, function () {
            return Setting::where('key', 'maintenance_mode')->value('value');
        });

        if ($maintenanceMode === '1') {
            if ($request->user() && $request->user()->role === 'admin') {
                return $next($request);
            }

            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}

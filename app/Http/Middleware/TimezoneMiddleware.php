<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class TimezoneMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if settings table exists and we are not in the console/installing
            if (!app()->runningInConsole() && Schema::hasTable('settings')) {
                $timezone = Setting::where('key', 'timezone')->value('value');
                if ($timezone) {
                    date_default_timezone_set($timezone);
                    config(['app.timezone' => $timezone]);
                }
            }
        } catch (\Exception $e) {
            // Silence exceptions to avoid breaking application during installation or migrations
        }

        return $next($request);
    }
}

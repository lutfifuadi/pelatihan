<?php

namespace App\Http\Middleware;

use App\Facades\Feature;
use App\Support\FeatureDefaults;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $featureKey
     */
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if (Feature::isOff($featureKey)) {
            $meta = FeatureDefaults::get($featureKey);
            $label = $meta['label'] ?? $featureKey;
            $message = "Fitur \"{$label}\" sedang dinonaktifkan oleh administrator.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            abort(403, $message);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        $installed = file_exists(storage_path('installed'));

        if ($installed && $request->is('install*')) {
            return redirect('/');
        }

        if (!$installed && !$request->is('install*')) {
            return redirect()->route('installer.step1');
        }

        return $next($request);
    }
}

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LocaleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(LocaleMiddleware::class);

        $middleware->alias([
            'role'              => \App\Http\Middleware\RoleMiddleware::class,
            'user.active'       => \App\Http\Middleware\CheckUserActive::class,
            'redirect.if.installed' => \App\Http\Middleware\RedirectIfInstalled::class,
        ]);

        // Middleware untuk cek user aktif, dijalankan setelah session & auth
        $middleware->web(\App\Http\Middleware\CheckUserActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Jika APP_KEY belum di-set, redirect ke installer
        $exceptions->render(function (\Illuminate\Encryption\MissingAppKeyException $e, \Illuminate\Http\Request $request) {
            $installed = file_exists(storage_path('installed'));
            if (!$installed) {
                return redirect('/install');
            }
            throw $e;
        });
    })->create();

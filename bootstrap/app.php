<?php

use App\Http\Middleware\CanImpersonate;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\CheckUserActive;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\RedirectIfInstalled;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TimezoneMiddleware;
use Illuminate\Encryption\MissingAppKeyException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(LocaleMiddleware::class);
        $middleware->web(TimezoneMiddleware::class);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'user.active' => CheckUserActive::class,
            'redirect.if.installed' => RedirectIfInstalled::class,
            'can.impersonate' => CanImpersonate::class,
            'pendaftaran.completed' => \App\Http\Middleware\RedirectIfPendaftaranCompleted::class,
        ]);

        // Middleware untuk cek user aktif, dijalankan setelah session & auth
        $middleware->web(CheckUserActive::class);
        $middleware->web(CheckMaintenanceMode::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Jika APP_KEY belum di-set, redirect ke installer
        $exceptions->render(function (MissingAppKeyException $e, Request $request) {
            $installed = file_exists(storage_path('installed'));
            if (! $installed) {
                return redirect('/install');
            }
            throw $e;
        });
    })->create();

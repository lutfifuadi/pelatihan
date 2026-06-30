<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Daftar route name yang dikecualikan dari pengecekan must_change_password.
     * Termasuk: login, logout, halaman profil (tempat ganti password).
     */
    protected array $exceptRoutes = [
        'login',
        'logout',
        'password.request',
        'password.reset',
        'password.email',
        'password.confirm',
        'password.update',
        'profile.show',
        'user-profile-information.update',
        'user-password.update',
        'admin.login',
        'admin.password.request',
        'verification.notice',
        'verification.verify',
        'verification.send',
        'home',
        'two-factor.login',
        'two-factor.challenge',
        'impersonate.leave',
    ];

    /**
     * Handle an incoming request.
     *
     * Jika user login dan memiliki must_change_password = true,
     * redirect ke halaman profil (Jetstream profile.show) agar user mengganti password.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya cek jika user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Jika user harus ganti password
            if ($user->must_change_password) {
                $currentRouteName = $request->route()?->getName();

                // Lewati jika route saat ini ada dalam daftar pengecualian
                if ($currentRouteName && in_array($currentRouteName, $this->exceptRoutes)) {
                    return $next($request);
                }

                // Redirect ke halaman profil Jetstream (tempat ganti password)
                // dengan pesan flash agar user tahu kenapa diarahkan ke sini
                return redirect()
                    ->route('profile.show')
                    ->with('must_change_password_notice', 'Demi keamanan akun, Anda diwajibkan mengganti password sebelum melanjutkan.');
            }
        }

        return $next($request);
    }
}

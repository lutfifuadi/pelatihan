<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Roles: admin, instruktur, koordinator, peserta
     * 
     * Jika route membutuhkan role 'admin' dan user belum login,
     * redirect ke halaman login admin. Selain itu redirect ke login biasa.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            // Jika route membutuhkan role admin, arahkan ke admin login
            if (in_array('admin', $roles)) {
                return redirect()->route('admin.login');
            }

            return redirect()->route('auth-login-basic');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}

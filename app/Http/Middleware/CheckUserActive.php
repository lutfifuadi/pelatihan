<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     * Cek apakah user active = true.
     * - Koordinator nonaktif tetap bisa akses (frontend yang handle popup).
     * - Role lain yang nonaktif akan di-logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->is_active) {
            // Jika sedang dalam sesi impersonasi admin, tetap izinkan akses inspeksi
            if ($request->session()->has('impersonator_id')) {
                return $next($request);
            }

            // Koordinator nonaktif tetap bisa melanjutkan request, dengan flag popup
            if (Auth::user()->role === 'koordinator') {
                session()->flash('account_disabled', true);
                return $next($request);
            }

            // Untuk role lain (peserta, instruktur, admin), logout
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Akun Anda belum diaktifkan oleh admin. Silakan hubungi admin untuk aktivasi.');
        }

        return $next($request);
    }
}

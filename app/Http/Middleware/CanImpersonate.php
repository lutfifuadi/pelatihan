<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanImpersonate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = Auth::user();

        // 1. Pengguna aktif harus ada, memiliki role admin, dan berstatus is_active = 1
        if (!$currentUser || $currentUser->role !== 'admin' || !$currentUser->is_active) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda harus menjadi admin yang aktif untuk melakukan impersonasi.');
        }

        // 2. Mengambil target user dari parameter route {user}
        $targetUser = $request->route('user');
        
        if (!$targetUser instanceof User) {
            $targetUser = User::find($targetUser);
        }

        if (!$targetUser) {
            return redirect()->back()->with('error', 'Pengguna target tidak ditemukan.');
        }

        // 3. Admin tidak boleh meng-impersonate dirinya sendiri
        if ($currentUser->id === $targetUser->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat meng-impersonate diri Anda sendiri.');
        }

        // 4. Admin tidak boleh meng-impersonate admin lain
        if ($targetUser->role === 'admin') {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan meng-impersonate admin lain.');
        }

        // 5. Target user harus aktif (is_active = 1)
        if (!$targetUser->is_active) {
            return redirect()->back()->with('error', 'Tidak dapat meng-impersonate pengguna yang tidak aktif.');
        }

        return $next($request);
    }
}

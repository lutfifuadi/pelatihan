<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Handle GET request on impersonate route to prevent 405 Method Not Allowed.
     */
    public function handleGet($user)
    {
        return redirect()->route('admin.users.index')
            ->with('warning', 'Fitur impersonate tidak dapat diakses langsung via URL. Silakan gunakan tombol resmi di tabel pengguna.');
    }

    /**
     * Impersonate target user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function take(Request $request, User $user)
    {
        $admin = Auth::user();

        if (!$admin || $admin->role !== 'admin' || !$admin->is_active) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda harus menjadi admin yang aktif untuk melakukan impersonasi.');
        }

        // Simpan data admin asli sebelum switch login
        $adminId = $admin->id;
        $adminName = $admin->name;

        // Tulis log aktivitas menggunakan App\Services\ActivityLogger
        ActivityLogger::log(
            action: 'login',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Admin {$adminName} melakukan impersonasi sebagai {$user->name} ({$user->role})"
        );

        // Lakukan login target user pada guard web
        Auth::guard('web')->login($user);

        // Perbarui user resolver pada current request lifecycle
        $request->setUserResolver(fn () => $user);

        // Simpan data session impersonasi & sinkronkan hash password untuk AuthenticateSession / Sanctum
        $session = $request->session();
        $session->put([
            'impersonator_id'       => $adminId,
            'impersonator_name'     => $adminName,
            'password_hash_web'     => $user->getAuthPassword(),
            'password_hash_sanctum' => $user->getAuthPassword(),
            'active_role'           => $user->role,
        ]);

        // Simpan session secara eksplisit sebelum redirect
        $session->save();

        // Redirect ke dashboard yang sesuai berdasarkan role target user
        return match ($user->role) {
            'instruktur'  => redirect()->route('dashboard.instruktur'),
            'koordinator' => redirect()->route('dashboard.koordinator'),
            'peserta'     => redirect()->route('dashboard.peserta'),
            default       => redirect()->to('/home'),
        };
    }

    /**
     * Stop impersonating and return to admin session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(Request $request)
    {
        $adminId = session('impersonator_id');

        if (!$adminId) {
            return redirect()->to('/home')->with('error', 'Tidak ada session impersonasi yang aktif.');
        }

        $admin = User::find($adminId);

        if (!$admin) {
            session()->forget(['impersonator_id', 'impersonator_name']);
            return redirect()->route('login')->with('error', 'Admin tidak ditemukan.');
        }

        // Login kembali dengan akun admin asli
        Auth::guard('web')->login($admin);

        // Perbarui user resolver pada current request lifecycle
        $request->setUserResolver(fn () => $admin);

        // Bersihkan session impersonasi & kembalikan hash password admin
        $session = $request->session();
        $session->forget(['impersonator_id', 'impersonator_name']);
        $session->put([
            'password_hash_web'     => $admin->getAuthPassword(),
            'password_hash_sanctum' => $admin->getAuthPassword(),
            'active_role'           => $admin->role,
        ]);

        // Simpan session secara eksplisit
        $session->save();

        // Redirect ke /admin/users dengan notifikasi sukses
        return redirect()->route('admin.users.index')->with('success', 'Kembali ke Panel Administrator');
    }
}

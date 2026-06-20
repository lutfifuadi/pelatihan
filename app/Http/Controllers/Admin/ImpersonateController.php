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
     * Impersonate target user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function take(Request $request, User $user)
    {
        $admin = Auth::user();

        // Simpan ID admin yang sedang login ke session impersonator_id
        session(['impersonator_id' => $admin->id]);

        // Tulis log aktivitas menggunakan App\Services\ActivityLogger
        ActivityLogger::log(
            action: 'login',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Admin {$admin->name} melakukan impersonasi sebagai {$user->name} ({$user->role})"
        );

        // Lakukan login dengan target user
        Auth::guard('web')->loginUsingId($user->id);

        // Regenerasi session
        $request->session()->regenerate();

        // Redirect ke dashboard yang sesuai berdasarkan role target user
        return match ($user->role) {
            'instruktur'  => redirect()->route('dashboard.instruktur'),
            'koordinator' => redirect()->route('dashboard.koordinator'),
            'peserta'     => redirect()->route('dashboard.peserta'),
            default       => redirect()->to('/home'),
        };
    }

    /**
     * Stop impersonating and leave to admin session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(Request $request)
    {
        // Pastikan session('impersonator_id') ada
        if (!session()->has('impersonator_id')) {
            return redirect()->to('/home')->with('error', 'Tidak ada session impersonasi yang aktif.');
        }

        $adminId = session('impersonator_id');
        $admin = User::find($adminId);

        if (!$admin) {
            return redirect()->to('/home')->with('error', 'Admin tidak ditemukan.');
        }

        // Login kembali dengan admin asli
        Auth::guard('web')->loginUsingId($adminId);

        // Hapus impersonator_id dari session
        session()->forget('impersonator_id');

        // Regenerasi session
        $request->session()->regenerate();

        // Redirect ke /admin/users dengan pesan sukses "Kembali ke Panel Administrator"
        return redirect()->route('admin.users.index')->with('success', 'Kembali ke Panel Administrator');
    }
}

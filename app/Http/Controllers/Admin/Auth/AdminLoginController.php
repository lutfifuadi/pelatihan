<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request.
     * Only users with role 'admin' can login here.
     * Uses email (not NIK) for authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login with email + password
        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            // Only allow admin role
            if ($user->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses ke halaman admin.',
                ])->onlyInput('email');
            }

            // Check if account is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun admin Anda telah dinonaktifkan. Silakan hubungi super admin.',
                ])->onlyInput('email');
            }

            // Clear intended URL if it points to notifications or API to prevent loop/wrong redirection
            $intended = session()->get('url.intended');
            if ($intended && (str_contains($intended, '/notifications') || str_contains($intended, '/api/'))) {
                session()->forget('url.intended');
            }

            return redirect()->route('dashboard.admin'); // Admin login page should always redirect to admin dashboard, not intended
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Show the admin forgot password form.
     */
    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan.',
                ]);
            }

            // Clear intended URL if it points to notifications or API to prevent loop/wrong redirection
            $intended = session()->get('url.intended');
            if ($intended && (str_contains($intended, '/notifications') || str_contains($intended, '/api/'))) {
                session()->forget('url.intended');
            }

            // Redirect based on role
            return match ($user->role) {
                'admin' => redirect()->intended(route('dashboard.admin')),
                'instruktur' => redirect()->intended(route('dashboard.instruktur')),
                default => redirect()->intended(route('dashboard.peserta')),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}

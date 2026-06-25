<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-register-basic');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'nik' => ['required', 'string', 'size:16', 'unique:users,nik'],
            'status_tokoh' => ['required', 'string', 'max:255'],
            'sumber_informasi' => ['required', 'string', 'max:255'],
            'sumber_informasi_detail' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'nik' => $validated['nik'],
            'status_tokoh' => $validated['status_tokoh'],
            'sumber_informasi' => $validated['sumber_informasi'],
            'sumber_informasi_detail' => $validated['sumber_informasi_detail'] ?? null,
            'role' => 'peserta', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.peserta');
    }
}

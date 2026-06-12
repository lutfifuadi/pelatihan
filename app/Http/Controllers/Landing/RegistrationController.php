<?php

namespace App\Http\Controllers\Landing;

use App\Events\PesertaRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Handle the registration form submission.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nik'      => 'required|string|digits_between:15,16|unique:users,nik',
            'whatsapp' => 'required|string|max:20',
            'email'    => 'required|email|unique:users,email',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar. Silakan login saja.',
            'nik.digits_between'   => 'NIK harus 15 atau 16 digit.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login saja.',
        ]);

        // Auto-convert WA number: 08xxx → 628xxx
        $whatsapp = $request->whatsapp;
        $whatsapp = ltrim($whatsapp, '+');
        if (str_starts_with($whatsapp, '0')) {
            $whatsapp = '62' . substr($whatsapp, 1);
        } elseif (!str_starts_with($whatsapp, '62')) {
            $whatsapp = '62' . $whatsapp;
        }

        $user = User::create([
            'name'     => strtoupper($request->name),
            'nik'      => $request->nik,
            'whatsapp' => $whatsapp,
            'email'    => $request->email,
            'password' => Hash::make('pelatihanku2026'),
            'role'     => 'peserta',
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verified (manual registration)
        ]);

        // Dispatch event notifikasi
        PesertaRegistered::dispatch($user);

        // Auto-login the user
        auth()->login($user);

        return redirect()->route('landing.sukses');
    }

    /**
     * Show the success confirmation page.
     */
    public function sukses()
    {
        seo()->staticPage('daftar-sukses');

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        return view('content.landing.konfirmasi', compact('user'));
    }

    /**
     * Check if NIK already exists (for AJAX/live validation).
     */
    public function checkNik(Request $request)
    {
        $exists = User::where('nik', $request->nik)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK sudah terdaftar. Silakan login saja.' : null,
        ]);
    }

    /**
     * Check if WhatsApp number is registered on WhatsApp.
     */
    public function checkWa(Request $request)
    {
        $number = $request->input('number');
        if (!$number) {
            return response()->json([
                'status' => false,
                'exists' => false,
                'message' => 'Nomor tidak valid.',
            ]);
        }

        // Auto-convert: 08xxx → 628xxx
        $number = ltrim($number, '+');
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        try {
            $waUrl = \App\Models\Setting::where('key', 'whatsapp_api_url')->value('value')
                ?? env('WA_API_URL', 'https://wa.test/check-number');
            $waKey = \App\Models\Setting::where('key', 'whatsapp_api_key')->value('value')
                ?? env('WA_API_KEY', 'test-key');
            $waSender = \App\Models\Setting::where('key', 'whatsapp_sender')->value('value')
                ?? env('WA_SENDER', '62888888888');

            $response = Http::timeout(10)->get($waUrl, [
                'api_key' => $waKey,
                'sender'  => $waSender,
                'number'  => $number,
            ]);

            $data = $response->json();

            if ($response->successful() && ($data['status'] ?? false)) {
                $exists = $data['msg']['exists'] ?? false;
                return response()->json([
                    'status' => true,
                    'exists' => $exists,
                    'message' => $exists
                        ? 'Nomor WhatsApp terdaftar ✓'
                        : 'Nomor WhatsApp tidak terdaftar. Pastikan nomor ini aktif di WhatsApp.',
                ]);
            }

            // API returned error
            return response()->json([
                'status' => false,
                'exists' => false,
                'message' => 'Gagal memeriksa nomor. Coba lagi nanti.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'exists' => false,
                'message' => 'Gagal terhubung ke server. Coba lagi nanti.',
            ]);
        }
    }
}

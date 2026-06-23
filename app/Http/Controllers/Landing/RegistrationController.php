<?php

namespace App\Http\Controllers\Landing;

use App\Events\PesertaRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

        // Generate random password 12 karakter yang unik untuk setiap user
        $plainPassword = 'pelatihanku2026';
        $hashedPassword = Hash::make($plainPassword);

        $user = User::create([
            'name'     => strtoupper($request->name),
            'nik'      => $request->nik,
            'whatsapp' => $whatsapp,
            'email'    => $request->email,
            'password' => $hashedPassword,
            'role'     => 'peserta',
            'is_active' => true,
            'email_verified_at' => now(), // Auto-verified (manual registration)
        ]);

        // Log aktivitas pendaftaran
        ActivityLogger::log(
            action: 'created',
            subjectType: 'Peserta',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Peserta {$user->name} berhasil mendaftar akun",
        );

        // Kirim password ke WhatsApp user (jika nomor tersedia)
        if ($whatsapp) {
            try {
                WhatsAppService::sendMessage(
                    $whatsapp,
                    "🎉 *Pendaftaran Berhasil!*\n\n"
                    . "Halo *{$user->name}*,\n\n"
                    . "Akun Anda telah berhasil dibuat. Berikut adalah detail login Anda:\n\n"
                    . "🆔 *Username (NIK)*: `{$user->nik}`\n"
                    . "🔑 *Password*: `{$plainPassword}`\n\n"
                    . "🔗 *Link Login*: " . url('/login') . "\n\n"
                    . "⚠️ *Segera ganti password Anda setelah login pertama.*\n\n"
                    . "Terima kasih.\n"
                    . "- " . (\App\Models\Setting::where('key', 'institution_name')->value('value') ?? config('app.name'))
                );
            } catch (\Exception $e) {
                Log::warning("Gagal mengirim password via WhatsApp ke {$whatsapp}: " . $e->getMessage());
            }
        }

        // Dispatch event notifikasi
        PesertaRegistered::dispatch($user);

        event(new \App\Events\DashboardUpdated());

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

        // Cek setting validate_whatsapp dari database
        $validateWhatsapp = \App\Models\Setting::where('key', 'validate_whatsapp')->value('value') ?? '1';
        if ($validateWhatsapp === '0') {
            return response()->json([
                'status' => true,
                'exists' => true,
                'message' => 'Validasi nomor dinonaktifkan.',
            ]);
        }

        try {
            $checkResult = WhatsAppService::checkNumber($number);

            if ($checkResult !== null) {
                $exists = $checkResult['exists'] ?? false;
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

<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactVerificationController extends Controller
{
    /**
     * Show the contact verification form.
     */
    public function index()
    {
        return view('content.landing.verifikasi-kontak');
    }

    /**
     * Check if the input phone/whatsapp number is an official admin contact.
     */
    public function check(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:30',
        ], [
            'phone.required' => 'Nomor WhatsApp/Telepon wajib diisi.',
        ]);

        $input = $request->input('phone');

        // Normalisasi nomor input: hilangkan spasi, strip, tanda tambah (+), dan konversi awalan 0 menjadi 62
        $normalized = preg_replace('/[^0-9]/', '', $input);
        
        if (str_starts_with($normalized, '0')) {
            $normalized = '62' . substr($normalized, 1);
        }

        // Ambil daftar nomor admin resmi dari config
        $officialContacts = config('privacy.official_contacts', []);

        $isOfficial = in_array($normalized, $officialContacts, true);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $isOfficial ? 'success' : 'danger',
                'message' => $isOfficial 
                    ? 'Nomor resmi terdaftar.' 
                    : 'Nomor tidak terdaftar / waspada penipuan.',
                'input' => $input,
                'normalized' => $normalized,
            ]);
        }

        if ($isOfficial) {
            return back()->with('success', 'Nomor resmi terdaftar sebagai Admin Pelatihanku.');
        }

        return back()->with('error', 'Nomor TIDAK TERDAFTAR! Harap waspada terhadap segala modus penipuan yang mengatasnamakan admin.');
    }
}

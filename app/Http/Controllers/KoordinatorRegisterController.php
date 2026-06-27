<?php

namespace App\Http\Controllers;

use App\Events\PesertaRegistered;
use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KoordinatorRegisterController extends Controller
{
    /**
     * Tampilkan halaman form pendaftaran koordinator.
     */
    public function showForm()
    {
        seo()->staticPage('daftar-koordinator')
             ->addJsonLd(seo()->breadcrumbSchema([
                 ['label' => 'Beranda', 'url' => url('/')],
                 ['label' => 'Daftar Koordinator', 'url' => url('/daftar-koordinator')],
             ]));

        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.koordinator.register', compact('kecamatans'));
    }

    /**
     * Proses pendaftaran koordinator.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'whatsapp'      => 'required|string|max:20',
            'kecamatan_id'  => 'required|exists:kecamatans,id',
            'kelurahan_id'  => 'required|exists:kelurahans,id',
            'nik'           => 'required|string|digits_between:15,16|unique:users,nik',
        ], [
            'whatsapp.required'     => 'Nomor WhatsApp wajib diisi.',
            'kecamatan_id.required' => 'Pilih wilayah kecamatan terlebih dahulu.',
            'kecamatan_id.exists'   => 'Kecamatan yang dipilih tidak valid.',
            'kelurahan_id.required' => 'Pilih kelurahan terlebih dahulu.',
            'kelurahan_id.exists'   => 'Kelurahan yang dipilih tidak valid.',
            'nik.required'          => 'NIK wajib diisi sebagai username login.',
            'nik.unique'            => 'NIK ini sudah terdaftar.',
            'nik.digits_between'    => 'NIK harus 15 atau 16 digit.',
        ]);

        // Auto-convert whatsapp number
        $whatsapp = $request->whatsapp;
        if ($whatsapp) {
            $whatsapp = ltrim($whatsapp, '+');
            if (str_starts_with($whatsapp, '0')) {
                $whatsapp = '62' . substr($whatsapp, 1);
            } elseif (!str_starts_with($whatsapp, '62')) {
                $whatsapp = '62' . $whatsapp;
            }
        }

        // Auto-generate email dari NIK: {nik}@pelatihanku.my.id
        $email = $request->nik . '@pelatihanku.my.id';

        // Auto-generate password
        $plainPassword = 'katakuncikoordinator';
        $hashedPassword = Hash::make($plainPassword);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $email,
            'password'          => $hashedPassword,
            'role'              => 'koordinator',
            'kecamatan_id'      => $request->kecamatan_id,
            'kelurahan_id'      => $request->kelurahan_id,
            'nik'               => $request->nik,
            'whatsapp'          => $whatsapp,
            'is_active'         => false, // Menunggu approval admin
            'email_verified_at' => now(),
        ]);


        // Dispatch event notifikasi
        PesertaRegistered::dispatch($user);

        return redirect()->route('koordinator.register.sukses');
    }

    /**
     * Halaman sukses pendaftaran.
     */
    public function sukses()
    {
        seo()->staticPage('daftar-koordinator-sukses');

        return view('content.koordinator.sukses');
    }
}

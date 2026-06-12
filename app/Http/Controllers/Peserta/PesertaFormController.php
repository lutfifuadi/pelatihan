<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaFormController extends Controller
{
    public function saveTab1(Request $request)
    {
        $user = auth()->user();

        // Update NIK di tabel users (username)
        if ($request->filled('nik') && $request->nik !== $user->nik) {
            $user->update(['nik' => $request->nik]);
        }

        // Update nama di tabel users
        if ($request->filled('nama_lengkap') && $request->nama_lengkap !== $user->name) {
            $user->update(['name' => $request->nama_lengkap]);
        }

        // Simpan / update ke peserta_profiles
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'bulan_lahir' => $request->bulan_lahir,
                'tahun_lahir' => $request->tahun_lahir,
                'nik' => $request->nik,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Data Tab 1 tersimpan']);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Update NIK & nama di tabel users jika berubah
        if ($request->filled('nik') && $request->nik !== $user->nik) {
            $user->update(['nik' => $request->nik]);
        }
        if ($request->filled('nama_lengkap') && $request->nama_lengkap !== $user->name) {
            $user->update(['name' => $request->nama_lengkap]);
        }

        // Hanya update field Tab 1 & 2 — tanpa overwrite data Tab 3-5
        $profile = PesertaProfile::firstOrNew(['user_id' => $user->id]);
        $profile->nama_lengkap = $request->nama_lengkap;
        $profile->jenis_kelamin = $request->jenis_kelamin;
        $profile->tempat_lahir = $request->tempat_lahir;
        $profile->tanggal_lahir = $request->tanggal_lahir;
        $profile->bulan_lahir = $request->bulan_lahir;
        $profile->tahun_lahir = $request->tahun_lahir;
        $profile->nik = $request->nik;
        $profile->alamat_ktp = $request->alamat_ktp;
        $profile->rt = $request->rt;
        $profile->rw = $request->rw;
        $profile->kelurahan = $request->kelurahan;
        $profile->kecamatan = $request->kecamatan;
        $profile->kota = $request->kota;
        $profile->provinsi = $request->provinsi;
        $profile->kodepos = $request->kodepos;
        $profile->whatsapp = $request->whatsapp;
        $profile->email = $request->email;
        $linkMedsos = $request->input('link_medsos');
        if (is_string($linkMedsos)) {
            $linkMedsos = json_decode($linkMedsos, true) ?? [];
        }
        $profile->link_medsos = $linkMedsos;
        // Jangan ubah is_completed — biarkan false sampai tahap akhir
        $profile->save();

        // Tulis ke file txt
        $tglLahir = $request->input('tanggal_lahir', '') . ' ' . $request->input('bulan_lahir', '') . ' ' . $request->input('tahun_lahir', '');
        $text = "========================================\n";
        $text .= "        DATA PENDAFTARAN PESERTA\n";
        $text .= "========================================\n";
        $text .= "Tanggal Daftar  : " . now()->format('Y-m-d H:i:s') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "DATA PRIBADI\n";
        $text .= "----------------------------------------\n";
        $text .= "Nama Lengkap    : " . $request->input('nama_lengkap', '') . "\n";
        $text .= "Jenis Kelamin   : " . $request->input('jenis_kelamin', '') . "\n";
        $text .= "Tempat Lahir    : " . $request->input('tempat_lahir', '') . "\n";
        $text .= "Tanggal Lahir   : " . trim($tglLahir) . "\n";
        $text .= "NIK KTP         : " . $request->input('nik', '') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "ALAMAT KTP & KONTAK\n";
        $text .= "----------------------------------------\n";
        $text .= "Alamat KTP      : " . $request->input('alamat_ktp', '') . "\n";
        $text .= "RT              : " . $request->input('rt', '') . "\n";
        $text .= "RW              : " . $request->input('rw', '') . "\n";
        $text .= "Kelurahan       : " . $request->input('kelurahan', '') . "\n";
        $text .= "Kecamatan       : " . $request->input('kecamatan', '') . "\n";
        $text .= "Kota/Kabupaten  : " . $request->input('kota', '') . "\n";
        $text .= "Provinsi        : " . $request->input('provinsi', '') . "\n";
        $text .= "Kode Pos        : " . $request->input('kodepos', '') . "\n";
        $text .= "WhatsApp        : " . $request->input('whatsapp', '') . "\n";
        $text .= "Email           : " . $request->input('email', '') . "\n";
        $linkMedsosList = json_decode($request->input('link_medsos', '[]'), true);
        $text .= "Link Medsos     : " . (is_array($linkMedsosList) ? implode(', ', array_map(function($m) { return ($m['platform'] ?? '') . ': ' . ($m['url'] ?? ''); }, $linkMedsosList)) : '') . "\n";
        $text .= "========================================\n\n";
        file_put_contents(base_path('.planing/data-user.txt'), $text, FILE_APPEND | LOCK_EX);

        return redirect()->route('dashboard.peserta.form-pendidikan')->with('success', 'Data pribadi & alamat tersimpan!');
    }

    public function pendidikan()
    {
        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();
        return view('content.dashboard.peserta.form-pendidikan', compact('user', 'profile'));
    }

    public function savePendidikan(Request $request)
    {
        $user = auth()->user();
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'nama_institusi' => $request->nama_institusi,
                'jurusan' => $request->jurusan,
                'tahun_lulus' => $request->tahun_lulus,
                'status_pekerjaan' => $request->status_pekerjaan,
                'nama_perusahaan' => $request->nama_perusahaan,
            ]
        );
        return redirect()->route('dashboard.peserta.form-minat')->with('success', 'Data pendidikan tersimpan');
    }

    public function minat()
    {
        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        $query = Pelatihan::where('is_active', true);

        if ($user->kecamatan_id) {
            $query->where(function ($q) use ($user) {
                $q->whereHas('kecamatans', function ($q) use ($user) {
                    $q->where('kecamatan_id', $user->kecamatan_id);
                })->orWhereDoesntHave('kecamatans');
            });
        }

        $pelatihans = $query->with('dinas')->orderBy('batch')->get();

        $previousTrainings = PesertaProfile::where('user_id', $user->id)
            ->whereNotNull('pelatihan_id')
            ->with('pelatihan.dinas')
            ->get();

        $dinasRestrictions = [];
        foreach ($previousTrainings as $prev) {
            if ($prev->pelatihan && $prev->pelatihan->dinas) {
                $dinasId = $prev->pelatihan->dinas_id;
                $oneYearFromNow = $prev->created_at->addYear();
                if (!isset($dinasRestrictions[$dinasId]) || $prev->created_at > $dinasRestrictions[$dinasId]['date']) {
                    $dinasRestrictions[$dinasId] = [
                        'date' => $prev->created_at,
                        'available_after' => $oneYearFromNow,
                        'dinas_name' => $prev->pelatihan->dinas->nama_dinas,
                        'last_pelatihan' => $prev->pelatihan->nama,
                    ];
                }
            }
        }

        // Pre-compute batchList untuk Alpine.js (hindari error @json dengan closure)
        $batchList = $pelatihans->map(function ($p) use ($dinasRestrictions) {
            $tgl = $p->tanggal_mulai
                ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d-m-Y') . ($p->tanggal_selesai ? ' s/d ' . \Carbon\Carbon::parse($p->tanggal_selesai)->format('d-m-Y') : '')
                : 'COMING SOON';
            $kecNames = $p->kecamatans ? $p->kecamatans->pluck('name')->toArray() : [];
            $dinasId = $p->dinas_id;
            $restricted = isset($dinasRestrictions[$dinasId]);
            $restrictedUntil = $restricted ? $dinasRestrictions[$dinasId]['available_after']->format('d/m/Y') : null;
            $restrictedDinas = $restricted ? $dinasRestrictions[$dinasId]['dinas_name'] : null;
            $lastPelatihan = $restricted ? $dinasRestrictions[$dinasId]['last_pelatihan'] : null;
            return [
                'value' => $p->batch,
                'label' => $p->batch . ' : ' . $p->nama . ($tgl ? ' (' . $tgl . ')' : ''),
                'kecamatans' => $kecNames,
                'dinas_name' => $p->dinas->nama_dinas ?? '-',
                'restricted' => $restricted,
                'restricted_until' => $restrictedUntil,
                'restricted_dinas' => $restrictedDinas,
                'last_pelatihan' => $lastPelatihan,
            ];
        })->values();

        return view('content.dashboard.peserta.form-minat', compact('user', 'profile', 'pelatihans', 'dinasRestrictions', 'batchList'));
    }

    public function saveMinat(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'batch_pelatihan' => 'required|string',
        ]);

        $selectedPelatihan = Pelatihan::where('batch', $request->batch_pelatihan)->with('dinas')->first();

        if (!$selectedPelatihan) {
            return redirect()->back()->with('error', 'Pelatihan yang dipilih tidak valid.');
        }

        if ($selectedPelatihan->dinas_id) {
            $lastRegistration = PesertaProfile::where('user_id', $user->id)
                ->whereHas('pelatihan', function ($q) use ($selectedPelatihan) {
                    $q->where('dinas_id', $selectedPelatihan->dinas_id);
                })
                ->whereNotNull('pelatihan_id')
                ->latest('created_at')
                ->first();

            if ($lastRegistration) {
                $oneYearLater = $lastRegistration->created_at->addYear();
                if (now()->lessThan($oneYearLater)) {
                    $dinasName = $selectedPelatihan->dinas->nama_dinas;
                    $lastPelatihanName = $lastRegistration->pelatihan->nama ?? 'Pelatihan sebelumnya';
                    $availableDate = $oneYearLater->format('d/m/Y');

                    return redirect()->back()
                        ->with('error', "Anda sudah mengikuti \"{$lastPelatihanName}\" di {$dinasName}. Anda dapat mendaftar pelatihan lain di dinas yang sama setelah {$availableDate}.")
                        ->withInput();
                }
            }
        }

        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'batch_pelatihan' => $request->batch_pelatihan,
                'pelatihan_id' => $selectedPelatihan->id,
            ]
        );

        return redirect()->route('dashboard.peserta.form-dokumen')->with('success', 'Data minat tersimpan');
    }

    public function dokumen()
    {
        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();
        return view('content.dashboard.peserta.form-dokumen', compact('user', 'profile'));
    }

    public function saveDokumen(Request $request)
    {
        $fotoProfil = null;
        $scanKtp = null;

        if ($request->hasFile('foto_profil')) {
            $fotoProfil = $request->file('foto_profil')->store('uploads/peserta', 'public');
        }

        if ($request->hasFile('scan_ktp')) {
            $scanKtp = $request->file('scan_ktp')->store('uploads/peserta', 'public');
        }

        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        $updateData = [
            'is_completed' => true,
        ];

        if ($fotoProfil) {
            $updateData['foto_profil'] = basename($fotoProfil);
        }
        if ($scanKtp) {
            $updateData['scan_ktp'] = basename($scanKtp);
        }

        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            $updateData
        );

        // Tulis ke file data-user.txt
        $profile = PesertaProfile::where('user_id', $user->id)->first();
        $bidangMinat = $profile->bidang_minat ?? [];
        if (is_string($bidangMinat)) $bidangMinat = [$bidangMinat];

        $tglLahir = trim(($profile->tanggal_lahir ?? '') . ' ' . ($profile->bulan_lahir ?? '') . ' ' . ($profile->tahun_lahir ?? ''));

        $text = "========================================\n";
        $text .= "        DATA PENDAFTARAN PESERTA\n";
        $text .= "========================================\n";
        $text .= "Tanggal Daftar  : " . now()->format('Y-m-d H:i:s') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "DATA PRIBADI\n";
        $text .= "----------------------------------------\n";
        $text .= "Nama Lengkap    : " . ($profile->nama_lengkap ?? '') . "\n";
        $text .= "Jenis Kelamin   : " . ($profile->jenis_kelamin ?? '') . "\n";
        $text .= "Tempat Lahir    : " . ($profile->tempat_lahir ?? '') . "\n";
        $text .= "Tanggal Lahir   : " . $tglLahir . "\n";
        $text .= "NIK KTP         : " . ($profile->nik ?? '') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "ALAMAT KTP & KONTAK\n";
        $text .= "----------------------------------------\n";
        $text .= "Alamat KTP      : " . ($profile->alamat_ktp ?? '') . "\n";
        $text .= "RT              : " . ($profile->rt ?? '') . "\n";
        $text .= "RW              : " . ($profile->rw ?? '') . "\n";
        $text .= "Kelurahan       : " . ($profile->kelurahan ?? '') . "\n";
        $text .= "Kecamatan       : " . ($profile->kecamatan ?? '') . "\n";
        $text .= "Kota/Kabupaten  : " . ($profile->kota ?? '') . "\n";
        $text .= "Provinsi        : " . ($profile->provinsi ?? '') . "\n";
        $text .= "Kode Pos        : " . ($profile->kodepos ?? '') . "\n";
        $text .= "WhatsApp        : " . ($profile->whatsapp ?? '') . "\n";
        $text .= "Email           : " . ($profile->email ?? '') . "\n";
        $linkMedsos = $profile->link_medsos ?? [];
        if (is_string($linkMedsos)) $linkMedsos = json_decode($linkMedsos, true) ?? [];
        $text .= "Link Medsos     : " . (is_array($linkMedsos) ? implode(', ', array_map(function($m) { return ($m['platform'] ?? '') . ': ' . ($m['url'] ?? ''); }, $linkMedsos)) : '') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "PENDIDIKAN & PEKERJAAN\n";
        $text .= "----------------------------------------\n";
        $text .= "Pendidikan      : " . ($profile->pendidikan_terakhir ?? '') . "\n";
        $text .= "Institusi       : " . ($profile->nama_institusi ?? '') . "\n";
        $text .= "Jurusan         : " . ($profile->jurusan ?? '') . "\n";
        $text .= "Tahun Lulus     : " . ($profile->tahun_lulus ?? '') . "\n";
        $text .= "Status Pekerjaan: " . ($profile->status_pekerjaan ?? '') . "\n";
        $text .= "Perusahaan      : " . ($profile->nama_perusahaan ?? '') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "MINAT PELATIHAN\n";
        $text .= "----------------------------------------\n";
        $text .= "Bidang Minat    : " . implode(', ', $bidangMinat) . "\n";
        $text .= "Tujuan          : " . ($profile->tujuan_pelatihan ?? '') . "\n";
        $text .= "Preferensi Jadwal: " . ($profile->preferensi_jadwal ?? '') . "\n";
        $text .= "Preferensi Mode : " . ($profile->preferensi_mode ?? '') . "\n";
        $text .= "----------------------------------------\n";
        $text .= "DOKUMEN\n";
        $text .= "----------------------------------------\n";
        $text .= "Foto Profil     : " . ($fotoProfil ? basename($fotoProfil) : ($profile->foto_profil ?? '-')) . "\n";
        $text .= "Scan KTP        : " . ($scanKtp ? basename($scanKtp) : ($profile->scan_ktp ?? '-')) . "\n";
        $text .= "========================================\n\n";

        $filePath = base_path('.planing/data-user.txt');
        file_put_contents($filePath, $text, FILE_APPEND | LOCK_EX);

        return redirect()->route('dashboard.peserta')->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan.');
    }
}

<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class PesertaFormController extends Controller
{
    public function saveTab1(Request $request)
    {
        $request->validate([
            'nik' => 'nullable|string|digits_between:15,16',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:200',
            'tanggal_lahir' => 'nullable|string|max:2',
            'bulan_lahir' => 'nullable|string|max:20',
            'tahun_lahir' => 'nullable|string|digits:4',
        ]);

        $user = auth()->user();

        // Update NIK di tabel users (username)
        if ($request->filled('nik') && $request->nik !== $user->nik) {
            $user->update(['nik' => $request->nik]);
        }

        // Update nama di tabel users
        if ($request->filled('nama_lengkap') && $request->nama_lengkap !== $user->name) {
            $user->update(['name' => strip_tags($request->nama_lengkap)]);
        }

        // Simpan / update ke peserta_profiles
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => strip_tags($request->nama_lengkap),
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => strip_tags($request->tempat_lahir),
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
        $request->validate([
            'nik' => 'nullable|string|digits_between:15,16',
            'nama_lengkap' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tempat_lahir' => 'nullable|string|max:200',
            'tanggal_lahir' => 'nullable|string|max:2',
            'bulan_lahir' => 'nullable|string|max:20',
            'tahun_lahir' => 'nullable|string|digits:4',
            'alamat_ktp' => 'nullable|string|max:500',
            'rt' => 'nullable|string|max:5',
            'rw' => 'nullable|string|max:5',
            'kelurahan' => 'nullable|string|max:200',
            'kecamatan' => 'nullable|string|max:200',
            'kota' => 'nullable|string|max:200',
            'provinsi' => 'nullable|string|max:200',
            'kodepos' => 'nullable|string|max:10',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

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
        $request->validate([
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'nama_institusi' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'tahun_lulus' => 'nullable|string|digits:4',
            'status_pekerjaan' => 'nullable|string|max:100',
            'nama_perusahaan' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'nama_institusi' => strip_tags($request->nama_institusi),
                'jurusan' => strip_tags($request->jurusan),
                'tahun_lulus' => $request->tahun_lulus,
                'status_pekerjaan' => $request->status_pekerjaan,
                'nama_perusahaan' => strip_tags($request->nama_perusahaan),
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
        $request->validate([
            'foto_profil' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'webp'])
                    ->max(2 * 1024), // Maks 2MB
            ],
            'scan_ktp' => [
                'nullable',
                File::types(['jpg', 'jpeg', 'png', 'pdf'])
                    ->max(5 * 1024), // Maks 5MB
            ],
        ], [
            'foto_profil.max' => 'Ukuran foto profil maksimal 2MB.',
            'scan_ktp.max' => 'Ukuran scan KTP maksimal 5MB.',
        ]);

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

        return redirect()->route('dashboard.peserta')->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan.');
    }
}

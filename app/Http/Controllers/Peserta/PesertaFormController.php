<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use App\Services\FormConfigService;
use Illuminate\Http\Request;

class PesertaFormController extends Controller
{
    protected $formConfig;

    public function __construct(FormConfigService $formConfig)
    {
        $this->formConfig = $formConfig;
    }

    public function saveTab1(Request $request)
    {
        $rules = $this->formConfig->buildValidationRules('data_pribadi');
        $request->validate($rules);

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
        $rulesTab1 = $this->formConfig->buildValidationRules('data_pribadi');
        $rulesTab2 = $this->formConfig->buildValidationRules('alamat_kontak');
        $rules = array_merge($rulesTab1, $rulesTab2);
        $request->validate($rules);

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
        $fields = $this->formConfig->getFieldsBySection('pendidikan');
        $pendidikanOptions = $this->formConfig->getOptions('pendidikan_terakhir');
        $pekerjaanOptions = $this->formConfig->getOptions('status_pekerjaan');
        return view('content.dashboard.peserta.form-pendidikan', compact('user', 'profile', 'fields', 'pendidikanOptions', 'pekerjaanOptions'));
    }

    public function savePendidikan(Request $request)
    {
        $rules = $this->formConfig->buildValidationRules('pendidikan');
        $request->validate($rules);

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

        $fields = $this->formConfig->getFieldsBySection('minat');

        return view('content.dashboard.peserta.form-minat', compact('user', 'profile', 'pelatihans', 'dinasRestrictions', 'batchList', 'fields'));
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
        $fields = $this->formConfig->getFieldsBySection('dokumen');
        return view('content.dashboard.peserta.form-dokumen', compact('user', 'profile', 'fields'));
    }

    public function saveDokumen(Request $request)
    {
        // Build validation rules untuk dokumen, kecuali checkbox konfirmasi
        $rules = $this->formConfig->buildValidationRules('dokumen');
        // Hapus validasi checkbox konfirmasi dari rules (pindah ke halaman review)
        unset($rules['konfirmasi']);
        $request->validate($rules);

        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        // Kumpulkan semua jawaban pertanyaan dari section dokumen
        $fields = $this->formConfig->getFieldsBySection('dokumen');
        $jawaban = [];

        foreach ($fields as $field) {
            if ($field->type === 'checkbox') continue; // skip konfirmasi

            if ($field->type === 'radio_other') {
                $jawaban[$field->field_key] = $request->input($field->field_key);
                // Simpan juga input "other" jika ada
                $otherKey = $field->field_key . '_other';
                if ($request->filled($otherKey)) {
                    $jawaban[$otherKey] = $request->input($otherKey);
                }
            } else {
                if ($request->filled($field->field_key)) {
                    $jawaban[$field->field_key] = $request->input($field->field_key);
                }
            }
        }

        $profile->jawaban_pertanyaan = $jawaban;
        // JANGAN set is_completed = true — biarkan false sampai submitFinal()
        $profile->save();

        return redirect()->route('dashboard.peserta.form-review')->with('success', 'Jawaban pertanyaan tersimpan. Silakan review data Anda sebelum menyelesaikan pendaftaran.');
    }

    public function review()
    {
        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        return view('content.dashboard.peserta.form-review', compact('user', 'profile'));
    }

    public function submitFinal(Request $request)
    {
        $request->validate([
            'konfirmasi' => 'required|accepted',
        ], [
            'konfirmasi.required' => 'Anda harus menyetujui pernyataan data benar.',
            'konfirmasi.accepted' => 'Anda harus menyetujui pernyataan data benar.',
        ]);

        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect()->back()->with('error', 'Data profil tidak ditemukan.');
        }

        $profile->is_completed = true;
        $profile->save();

        return redirect()->route('dashboard.peserta')->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan.');
    }
}

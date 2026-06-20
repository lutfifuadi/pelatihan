<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use App\Models\Setting;
use App\Services\FormConfigService;
use Illuminate\Http\Request;

class PesertaFormController extends Controller
{
    protected $formConfig;

    public function __construct(FormConfigService $formConfig)
    {
        $this->formConfig = $formConfig;
    }

    // ========================================================================
    // STEP 1 - DATA PRIBADI
    // ========================================================================

    /**
     * Tampilkan form Data Pribadi (Step 1)
     * Pola PMBM: load dari session, fallback ke database
     */
    public function formPendaftaran()
    {
        // 1. Ambil dari session
        $data = session('peserta.form.step1', []);

        // 2. Ambil dari database jika ada
        $profile = PesertaProfile::where('user_id', auth()->id())->first();
        if ($profile) {
            $dbFields = [
                'nama_lengkap'  => $profile->nama_lengkap,
                'nik'           => $profile->nik,
                'jenis_kelamin' => $profile->jenis_kelamin,
                'tempat_lahir'  => $profile->tempat_lahir,
                'tanggal_lahir' => $profile->tanggal_lahir,
                'bulan_lahir'   => $profile->bulan_lahir,
                'tahun_lahir'   => $profile->tahun_lahir,
            ];

            foreach ($dbFields as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === null) {
                    if ($value !== null && $value !== '') {
                        $data[$key] = $value;
                    }
                }
            }

            session()->put('peserta.form.step1', $data);
        } else {
            // Fallback ke data user
            $user = auth()->user();
            if (empty($data['nama_lengkap'])) {
                $data['nama_lengkap'] = $user->name ?? '';
            }
            if (empty($data['nik'])) {
                $data['nik'] = $user->nik ?? '';
            }
            if (empty($data['email'])) {
                $data['email'] = $user->email ?? '';
            }
        }

        // Config dari database
        $fieldsDataPribadi = $this->formConfig->getFieldsBySection('data_pribadi');

        return view('content.dashboard.peserta.form-pendaftaran', compact('data', 'fieldsDataPribadi'));
    }

    /**
     * Simpan Data Pribadi (Step 1) - via form POST
     * Pola PMBM: simpan ke session + database (progressive)
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $rules = $this->formConfig->buildValidationRules('data_pribadi');
        $request->validate($rules);

        // 2. Simpan ke SESSION
        $input = $request->except('_token');
        session()->put('peserta.form.step1', $input);
        session()->save();

        // 3. Simpan ke DATABASE (progressive)
        $user = auth()->user();
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_filter([
                'nama_lengkap'  => strip_tags($input['nama_lengkap'] ?? ''),
                'nik'           => $input['nik'] ?? '',
                'jenis_kelamin' => $input['jenis_kelamin'] ?? '',
                'tempat_lahir'  => strip_tags($input['tempat_lahir'] ?? ''),
                'tanggal_lahir' => $input['tanggal_lahir'] ?? '',
                'bulan_lahir'   => $input['bulan_lahir'] ?? '',
                'tahun_lahir'   => $input['tahun_lahir'] ?? '',
            ], fn($v) => $v !== null && $v !== '')
        );

        // 4. Redirect ke step berikutnya
        return redirect()->route('dashboard.peserta.form-alamat')
            ->with('success', 'Data pribadi berhasil disimpan.');
    }

    // ========================================================================
    // STEP 2 - ALAMAT & KONTAK
    // ========================================================================

    /**
     * Tampilkan form Alamat & Kontak (Step 2)
     * Pola PMBM: load dari session, fallback ke database
     */
    public function formAlamat()
    {
        // 1. Ambil dari session, merge dengan old input
        $data = array_merge(session('peserta.form.step2', []), old() ?? []);

        // 2. Ambil dari database jika ada
        $user = auth()->user();
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        // Cek apakah data pribadi sudah diisi (database atau session)
        $step1Data = session('peserta.form.step1', []);
        $hasDataPribadi = $profile && $profile->nama_lengkap;
        $hasSessionData = !empty($step1Data['nama_lengkap']);
        if (!$hasDataPribadi && !$hasSessionData) {
            return redirect()->route('dashboard.peserta.form-pendaftaran')
                ->with('error', 'Silakan isi data pribadi terlebih dahulu.');
        }

        if ($profile) {
            $dbFields = [
                'alamat_ktp'    => $profile->alamat_ktp,
                'rt'            => $profile->rt,
                'rw'            => $profile->rw,
                'kelurahan_id'  => $profile->kelurahan_id,
                'kecamatan_id'  => $user->kecamatan_id ?? ($profile->kecamatan ? (\App\Models\Kecamatan::where('name', $profile->kecamatan)->value('id')) : null),
                'kota'          => $profile->kota ?? Setting::where('key', 'lock_kota')->value('value') ?? 'BANDUNG',
                'provinsi'      => $profile->provinsi ?? Setting::where('key', 'lock_provinsi')->value('value') ?? 'Jawa Barat',
                'kodepos'       => $profile->kodepos,
                'whatsapp'      => $profile->whatsapp,
                'email'         => $profile->email,
                'link_medsos'   => $profile->link_medsos,
            ];

            foreach ($dbFields as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === null) {
                    if ($value !== null && $value !== '') {
                        $data[$key] = $value;
                    }
                }
            }

            // Fallback email ke user
            if (empty($data['email'])) {
                $data['email'] = $user->email ?? '';
            }
            if (empty($data['whatsapp'])) {
                $data['whatsapp'] = $user->whatsapp ?? '';
            }

            session()->put('peserta.form.step2', $data);
        } else {
            // Fallback ke data user jika profile belum dibuat
            if (empty($data['email'])) {
                $data['email'] = $user->email ?? '';
            }
            if (empty($data['whatsapp'])) {
                $data['whatsapp'] = $user->whatsapp ?? '';
            }
            if (empty($data['kecamatan_id'])) {
                $data['kecamatan_id'] = $user->kecamatan_id ?? '';
            }
            if (empty($data['kelurahan_id'])) {
                $data['kelurahan_id'] = $user->kelurahan_id ?? '';
            }
        }

        // Data pendukung untuk view
        $kecamatans = Kecamatan::orderBy('name')->get();
        $lockKota = Setting::where('key', 'lock_kota')->value('value') ?? 'BANDUNG';
        $lockProvinsi = Setting::where('key', 'lock_provinsi')->value('value') ?? 'Jawa Barat';
        $fieldsAlamatKontak = $this->formConfig->getFieldsBySection('alamat_kontak');
        $platformOptions = $this->formConfig->getOptions('platform_medsos');

        return view('content.dashboard.peserta.form-alamat', compact(
            'data', 'kecamatans', 'fieldsAlamatKontak', 'platformOptions',
            'lockKota', 'lockProvinsi'
        ));
    }

    /**
     * Simpan Alamat & Kontak (Step 2)
     * Pola PMBM: simpan ke session + database (progressive)
     */
    public function storeAlamat(Request $request)
    {
        // 1. Validasi
        $rules = $this->formConfig->buildValidationRules('alamat_kontak');
        $rules['rt'] = 'required|string|max:3';
        $rules['rw'] = 'required|string|max:3';
        $rules['whatsapp'] = 'required|string|max:20';
        $rules['email'] = 'required|email';
        $request->validate($rules);

        $user = auth()->user();

        // 2. Simpan ke SESSION
        $input = $request->except('_token');

        // Parse link medsos
        $linkMedsos = $input['link_medsos'] ?? [];
        if (is_string($linkMedsos)) {
            $linkMedsos = json_decode($linkMedsos, true) ?? [];
        }
        $input['link_medsos'] = $linkMedsos;

        session()->put('peserta.form.step2', $input);
        session()->save();

        // 3. Simpan ke DATABASE (progressive)
        $kecamatan = Kecamatan::find($request->kecamatan_id);
        $kecamatanName = $kecamatan ? $kecamatan->name : '';

        $kelurahan = Kelurahan::find($request->kelurahan_id);
        $kelurahanName = $kelurahan ? $kelurahan->name : '';

        $profile = PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_filter([
                'alamat_ktp'   => strip_tags($input['alamat_ktp'] ?? ''),
                'rt'           => $input['rt'] ?? '',
                'rw'           => $input['rw'] ?? '',
                'kelurahan_id' => $input['kelurahan_id'] ?? null,
                'kelurahan'    => $kelurahanName,
                'kecamatan'    => $kecamatanName,
                'kota'         => $input['kota'] ?? 'BANDUNG',
                'provinsi'     => $input['provinsi'] ?? 'Jawa Barat',
                'kodepos'      => $input['kodepos'] ?? '',
                'whatsapp'     => $input['whatsapp'] ?? '',
                'email'        => $input['email'] ?? '',
                'link_medsos'  => $linkMedsos,
            ], fn($v) => $v !== null && $v !== '')
        );

        // Update user's kecamatan_id dan kelurahan_id
        if ($request->filled('kecamatan_id')) {
            $user->kecamatan_id = $request->kecamatan_id;
        }
        if ($request->filled('kelurahan_id')) {
            $user->kelurahan_id = $request->kelurahan_id;
        }
        if ($user->isDirty()) {
            $user->save();
        }

        // 4. Redirect ke step berikutnya
        return redirect()->route('dashboard.peserta.form-pendidikan')
            ->with('success', 'Data alamat & kontak tersimpan!');
    }

    // ========================================================================
    // STEP 3 - PENDIDIKAN & PEKERJAAN
    // ========================================================================

    /**
     * Tampilkan form Pendidikan & Pekerjaan (Step 3)
     * Pola PMBM: load dari session, fallback ke database
     */
    public function pendidikan()
    {
        // 1. Ambil dari session
        $data = session('peserta.form.step3', []);

        // 2. Ambil dari database jika ada
        $profile = PesertaProfile::where('user_id', auth()->id())->first();
        if ($profile) {
            $dbFields = [
                'pendidikan_terakhir' => $profile->pendidikan_terakhir,
                'nama_institusi'      => $profile->nama_institusi,
                'jurusan'             => $profile->jurusan,
                'tahun_lulus'         => $profile->tahun_lulus,
                'status_pekerjaan'    => $profile->status_pekerjaan,
                'nama_perusahaan'     => $profile->nama_perusahaan,
            ];

            foreach ($dbFields as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === null) {
                    if ($value !== null && $value !== '') {
                        $data[$key] = $value;
                    }
                }
            }

            session()->put('peserta.form.step3', $data);
        }

        // Data pendukung untuk view
        $fields = $this->formConfig->getFieldsBySection('pendidikan');
        $pendidikanOptions = $this->formConfig->getOptions('pendidikan_terakhir');
        $pekerjaanOptions = $this->formConfig->getOptions('status_pekerjaan');

        return view('content.dashboard.peserta.form-pendidikan', compact(
            'data', 'fields', 'pendidikanOptions', 'pekerjaanOptions'
        ));
    }

    /**
     * Simpan Pendidikan & Pekerjaan (Step 3)
     * Pola PMBM: simpan ke session + database (progressive)
     */
    public function savePendidikan(Request $request)
    {
        // 1. Validasi
        $rules = $this->formConfig->buildValidationRules('pendidikan');
        $request->validate($rules);

        $user = auth()->user();

        // 2. Simpan ke SESSION
        $input = $request->except('_token');
        session()->put('peserta.form.step3', $input);
        session()->save();

        // 3. Simpan ke DATABASE (progressive)
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_filter([
                'pendidikan_terakhir' => $input['pendidikan_terakhir'] ?? '',
                'nama_institusi'      => strip_tags($input['nama_institusi'] ?? ''),
                'jurusan'             => strip_tags($input['jurusan'] ?? ''),
                'tahun_lulus'         => $input['tahun_lulus'] ?? '',
                'status_pekerjaan'    => $input['status_pekerjaan'] ?? '',
                'nama_perusahaan'     => strip_tags($input['nama_perusahaan'] ?? ''),
            ], fn($v) => $v !== null && $v !== '')
        );

        // 4. Redirect ke step berikutnya
        return redirect()->route('dashboard.peserta.form-minat')
            ->with('success', 'Data pendidikan tersimpan');
    }

    // ========================================================================
    // STEP 4 - MINAT PELATIHAN
    // ========================================================================

    /**
     * Tampilkan form Minat Pelatihan (Step 4)
     * Pola PMBM: load dari session, fallback ke database
     */
    public function minat()
    {
        // 1. Ambil dari session
        $data = session('peserta.form.step4', []);

        // 2. Ambil dari database jika ada
        $profile = PesertaProfile::where('user_id', auth()->id())->first();
        if ($profile) {
            $dbFields = [
                'batch_pelatihan' => $profile->batch_pelatihan,
            ];

            foreach ($dbFields as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === null) {
                    if ($value !== null && $value !== '') {
                        $data[$key] = $value;
                    }
                }
            }

            session()->put('peserta.form.step4', $data);
        }

        // Data pendukung untuk view (sama seperti sebelumnya)
        $user = auth()->user();

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

        return view('content.dashboard.peserta.form-minat', compact(
            'data', 'pelatihans', 'dinasRestrictions', 'batchList', 'fields'
        ));
    }

    /**
     * Simpan Minat Pelatihan (Step 4)
     * Pola PMBM: simpan ke session + database (progressive)
     */
    public function saveMinat(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi
        $request->validate([
            'batch_pelatihan' => 'required|string',
        ]);

        $selectedPelatihan = Pelatihan::where('batch', $request->batch_pelatihan)->with('dinas')->first();
        if (!$selectedPelatihan) {
            return redirect()->back()->with('error', 'Pelatihan yang dipilih tidak valid.');
        }

        // Cek restriksi dinas (1 tahun)
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

        // 2. Simpan ke SESSION
        $input = $request->except('_token');
        session()->put('peserta.form.step4', $input);
        session()->save();

        // 3. Simpan ke DATABASE (progressive)
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            array_filter([
                'batch_pelatihan' => $input['batch_pelatihan'] ?? '',
                'pelatihan_id'    => $selectedPelatihan->id,
            ], fn($v) => $v !== null && $v !== '')
        );

        // 4. Redirect ke step berikutnya
        return redirect()->route('dashboard.peserta.form-dokumen')
            ->with('success', 'Data minat tersimpan');
    }

    // ========================================================================
    // STEP 5 - DOKUMEN & PERTANYAAN
    // ========================================================================

    /**
     * Tampilkan form Dokumen & Pertanyaan (Step 5)
     * Pola PMBM: load dari session, fallback ke database
     */
    public function dokumen()
    {
        // 1. Ambil dari session
        $data = session('peserta.form.step5', []);

        // 2. Ambil dari database jika ada
        $profile = PesertaProfile::where('user_id', auth()->id())->first();
        if ($profile && !empty($profile->jawaban_pertanyaan)) {
            $jawaban = $profile->jawaban_pertanyaan;
            if (is_string($jawaban)) {
                $jawaban = json_decode($jawaban, true) ?? [];
            }
            foreach ($jawaban as $key => $value) {
                if (!isset($data[$key]) || $data[$key] === '' || $data[$key] === null) {
                    if ($value !== null && $value !== '') {
                        $data[$key] = $value;
                    }
                }
            }
            session()->put('peserta.form.step5', $data);
        }

        $fields = $this->formConfig->getFieldsBySection('dokumen');

        return view('content.dashboard.peserta.form-dokumen', compact('data', 'fields'));
    }

    /**
     * Simpan Dokumen & Pertanyaan (Step 5)
     * Pola PMBM: simpan ke session + database (progressive)
     */
    public function saveDokumen(Request $request)
    {
        // 1. Validasi
        $rules = $this->formConfig->buildValidationRules('dokumen');
        unset($rules['konfirmasi']);
        $request->validate($rules);

        $user = auth()->user();

        // 2. Simpan ke SESSION
        $input = $request->except('_token');

        // Kumpulkan jawaban pertanyaan
        $fields = $this->formConfig->getFieldsBySection('dokumen');
        $jawaban = [];
        foreach ($fields as $field) {
            if ($field->type === 'checkbox') continue;
            if ($field->type === 'radio_other') {
                $jawaban[$field->field_key] = $request->input($field->field_key);
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
        $input['jawaban_pertanyaan'] = $jawaban;

        session()->put('peserta.form.step5', $input);
        session()->save();

        // 3. Simpan ke DATABASE (progressive)
        $profile = PesertaProfile::where('user_id', $user->id)->first();
        if ($profile) {
            $profile->jawaban_pertanyaan = $jawaban;
            $profile->save();
        }

        // 4. Redirect ke step berikutnya
        return redirect()->route('dashboard.peserta.form-review')
            ->with('success', 'Jawaban pertanyaan tersimpan. Silakan review data Anda sebelum menyelesaikan pendaftaran.');
    }

    // ========================================================================
    // STEP 6 - REVIEW & SUBMIT FINAL
    // ========================================================================

    /**
     * Tampilkan halaman Review Data (Step 6)
     * Pola PMBM: kumpulkan semua data dari session + database
     */
    public function review()
    {
        // 1. Kumpulkan semua data dari session
        $allData = [];
        for ($i = 1; $i <= 5; $i++) {
            $stepData = session("peserta.form.step{$i}", []);
            $allData = array_merge($allData, $stepData);
        }

        // 2. Ambil dari database jika session kosong
        $profile = PesertaProfile::where('user_id', auth()->id())->first();

        return view('content.dashboard.peserta.form-review', compact('allData', 'profile'));
    }

    /**
     * Submit final pendaftaran (Step 6)
     * Pola PMBM: kumpulkan semua data, validasi final, simpan, redirect
     */
    public function submitFinal(Request $request)
    {
        $request->validate([
            'konfirmasi' => 'required|accepted',
        ], [
            'konfirmasi.required' => 'Anda harus menyetujui pernyataan data benar.',
            'konfirmasi.accepted' => 'Anda harus menyetujui pernyataan data benar.',
        ]);

        $user = auth()->user();

        // 1. Kumpulkan semua data dari session
        $allData = [];
        for ($i = 1; $i <= 5; $i++) {
            $stepData = session("peserta.form.step{$i}", []);
            $allData = array_merge($allData, $stepData);
        }

        // 2. Ambil dari database jika session kosong
        $profile = PesertaProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            // Buat profile jika belum ada (seharusnya sudah ada dari step sebelumnya)
            $profile = PesertaProfile::create(['user_id' => $user->id]);
        }

        // Update jawaban pertanyaan dari session step5 jika ada
        if (isset($allData['jawaban_pertanyaan'])) {
            $profile->jawaban_pertanyaan = $allData['jawaban_pertanyaan'];
        }

        // Tandai sebagai completed
        $profile->is_completed = true;
        $profile->save();

        // ===== AUTO-CREATE ENROLLMENT =====
        $enrollment = null;
        if ($profile->pelatihan_id || !empty($allData['batch_pelatihan'])) {
            $pelatihan = $profile->pelatihan;
            if (!$pelatihan && !empty($allData['batch_pelatihan'])) {
                $pelatihan = \App\Models\Pelatihan::where('batch', $allData['batch_pelatihan'])->first();
            }

            if ($pelatihan) {
                // Tentukan status enrollment
                $status = 'pending';
                if ($pelatihan->auto_approve) {
                    $status = 'approved';
                } elseif ($pelatihan->kuota) {
                    $approvedCount = \App\Models\Enrollment::where('pelatihan_id', $pelatihan->id)
                        ->where('status', 'approved')
                        ->count();
                    if ($approvedCount >= $pelatihan->kuota) {
                        $status = 'waitlist';
                    }
                }

                $enrollment = \App\Models\Enrollment::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'pelatihan_id' => $pelatihan->id,
                    ],
                    [
                        'status' => $status,
                        'approved_at' => $status === 'approved' ? now() : null,
                    ]
                );
            }
        }

        // ===== DISPATCH EVENT NOTIFIKASI =====
        try {
            if ($enrollment && $enrollment->pelatihan) {
                event(new \App\Events\PesertaRegistered($user, $enrollment->pelatihan));
            }
        } catch (\Exception $e) {
            // Jangan sampai notifikasi gagal menggagalkan pendaftaran
            \Illuminate\Support\Facades\Log::warning('Gagal dispatch PesertaRegistered: ' . $e->getMessage());
        }

        // 3. Clear session form
        session()->forget('peserta.form');
        session()->save();

        // 4. Redirect ke halaman sukses
        return redirect()->route('dashboard.peserta.pendaftaran-sukses')
            ->with('success', 'Pendaftaran berhasil! Data Anda telah tersimpan.');
    }

    /**
     * Tampilkan halaman sukses setelah submit final
     */
    public function pendaftaranSukses()
    {
        $user = auth()->user();
        $profile = \App\Models\PesertaProfile::where('user_id', $user->id)
            ->with(['pelatihan.dinas'])
            ->first();

        if (!$profile || !$profile->is_completed) {
            return redirect()->route('dashboard.peserta')
                ->with('error', 'Anda belum menyelesaikan pendaftaran.');
        }

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->with(['pelatihan.dinas'])
            ->first();

        return view('content.dashboard.peserta.pendaftaran-sukses', compact('profile', 'enrollment', 'user'));
    }

    // ========================================================================
    // STATUS PENDAFTARAN
    // ========================================================================

    /**
     * Tampilkan halaman Status Pendaftaran peserta
     * Menampilkan timeline alur seleksi, detail pelatihan, profil, dan tombol aksi
     */
    public function statusPendaftaran()
    {
        $user = auth()->user();
        $profile = \App\Models\PesertaProfile::where('user_id', $user->id)
            ->with(['pelatihan.dinas'])
            ->first();

        if (!$profile) {
            return redirect()->route('dashboard.peserta')
                ->with('error', 'Anda belum memiliki data pendaftaran.');
        }

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->with(['pelatihan.dinas'])
            ->first();

        return view('content.dashboard.peserta.status-pendaftaran', compact(
            'profile', 'enrollment', 'user'
        ));
    }

    // ========================================================================
    // METHOD LAMA YANG TETAP DIPERTAHANKAN (tidak dihapus)
    // ========================================================================

    /**
     * Simpan Tab 1 via AJAX (untuk form wizard / multi-step)
     */
    public function saveTab1(Request $request)
    {
        try {
            $rules = $this->formConfig->buildValidationRules('data_pribadi');
            $request->validate($rules);

            $user = auth()->user();

            // 1. Simpan ke SESSION (pola PMBM)
            $input = $request->except('_token');
            session()->put('peserta.form.step1', $input);
            session()->save();

            // 2. Simpan ke DATABASE (progressive)
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

            return response()->json([
                'success' => true,
                'message' => 'Data pribadi tersimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data pribadi: ' . $e->getMessage(),
            ], 500);
        }
    }
}

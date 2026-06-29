<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request untuk validasi update biodata peserta oleh admin.
 *
 * Memisahkan validasi antara field User dan field PesertaProfile,
 * serta menyediakan helper method getUserData() dan getProfileData()
 * untuk kemudahan akses di controller.
 */
class UpdatePesertaBiodataRequest extends FormRequest
{
    /**
     * Tentukan apakah user yang membuat request ini diizinkan.
     * Hanya admin dan super_admin yang boleh mengubah biodata peserta.
     */
    public function authorize(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'super_admin']);
    }

    protected function prepareForValidation()
    {
        if ($this->has('nama_lengkap')) {
            $this->merge([
                'name' => $this->input('nama_lengkap')
            ]);
        }
        if ($this->has('whatsapp')) {
            $this->merge([
                'phone' => $this->input('whatsapp')
            ]);
        }
        if ($this->has('pelatihan_id')) {
            $pelatihan = \App\Models\Pelatihan::find($this->input('pelatihan_id'));
            if ($pelatihan) {
                $this->merge([
                    'batch_pelatihan' => $pelatihan->batch
                ]);
            } else {
                $this->merge([
                    'batch_pelatihan' => null
                ]);
            }
        }
    }

    /**
     * Aturan validasi untuk semua field yang dikirim dari form edit biodata.
     *
     * Field dikelompokkan:
     * - User fields: name, email, phone, whatsapp, bio, nik, status_tokoh,
     *                sumber_informasi, sumber_informasi_detail, kecamatan_id, kelurahan_id
     * - PesertaProfile fields: nama_lengkap, jenis_kelamin, tempat_lahir,
     *                          tanggal/bulan/tahun_lahir, alamat & wilayah,
     *                          pendidikan, pekerjaan, bidang_minat, preferensi, dokumen
     */
    public function rules(): array
    {
        /** @var \App\Models\User $user */
        $user   = $this->route('user');
        $userId = $user?->id;

        return [
            // ==============================
            // USER FIELDS
            // ==============================
            'name'                    => [$this->input('section') ? 'nullable' : 'required', 'string', 'max:255'],
            'email'                   => [$this->input('section') ? 'nullable' : 'required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'phone'                   => ['nullable', 'string', 'min:10', 'max:15'],
            'whatsapp'                => ['nullable', 'string', 'min:10', 'max:15'],
            'bio'                     => ['nullable', 'string', 'max:1000'],
            'nik'                     => ['nullable', 'digits:16', "unique:users,nik,{$userId}"],
            'status_tokoh'            => ['nullable', 'boolean'],
            'sumber_informasi'        => ['nullable', 'string', 'max:100'],
            'sumber_informasi_detail' => ['nullable', 'string', 'max:255'],
            'kecamatan_id'            => ['nullable', 'exists:kecamatans,id'],
            'kelurahan_id'            => ['nullable', 'exists:kelurahans,id'],
            'section'                 => ['nullable', 'string', 'in:identitas,alamat,pendidikan,preferensi,dokumen'],

            // ==============================
            // PESERTA PROFILE FIELDS
            // ==============================

            // Identitas
            'nama_lengkap'            => [$this->input('section') === 'identitas' || !$this->input('section') ? 'required' : 'nullable', 'string', 'max:255'],
            'jenis_kelamin'           => ['nullable', 'in:Laki-laki,Perempuan'],
            'tempat_lahir'            => ['nullable', 'string', 'max:100'],
            'tanggal_lahir'           => ['nullable', 'integer', 'min:1', 'max:31'],
            'bulan_lahir'             => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun_lahir'             => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],

            // Alamat & Wilayah
            'alamat_ktp'              => ['nullable', 'string', 'max:500'],
            'rt'                      => ['nullable', 'string', 'max:3'],
            'rw'                      => ['nullable', 'string', 'max:3'],
            'kelurahan'               => ['nullable', 'string', 'max:100'],
            'kecamatan'               => ['nullable', 'string', 'max:100'],
            'kota'                    => ['nullable', 'string', 'max:100'],
            'provinsi'                => ['nullable', 'string', 'max:100'],
            'kodepos'                 => ['nullable', 'digits:5'],

            // Kontak tambahan di profil
            'link_medsos'             => ['nullable', 'url', 'max:255'],

            // Pendidikan
            'pendidikan_terakhir'     => ['nullable', 'string', 'max:100'],
            'nama_institusi'          => ['nullable', 'string', 'max:255'],
            'jurusan'                 => ['nullable', 'string', 'max:255'],
            'tahun_lulus'             => ['nullable', 'integer', 'min:1970', 'max:' . date('Y')],

            // Pekerjaan
            'status_pekerjaan'        => ['nullable', 'string', 'max:100'],
            'nama_perusahaan'         => ['nullable', 'string', 'max:255'],

            // Minat & Preferensi
            'bidang_minat'            => ['nullable', 'array'],
            'bidang_minat.*'          => ['string', 'max:100'],
            'tujuan_pelatihan'        => ['nullable', 'string', 'max:1000'],
            'preferensi_jadwal'       => ['nullable', 'string', 'max:100'],
            'preferensi_mode'         => ['nullable', 'in:Online,Offline,Hybrid'],
            'pelatihan_id'            => ['nullable', 'exists:pelatihan,id'],
            'batch_pelatihan'         => ['nullable', 'string', 'max:255'],
            'jawaban_pertanyaan'      => ['nullable', 'array'],

            // Dokumen Upload
            'foto_profil'             => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'scan_ktp'                => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    /**
     * Pesan error kustom yang lebih informatif untuk pengguna.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required'  => 'Nama lengkap wajib diisi.',
            'name.required'          => 'Nama (username) wajib diisi.',
            'email.required'         => 'Alamat email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email sudah digunakan oleh peserta lain.',
            'nik.digits'             => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique'             => 'NIK sudah terdaftar untuk peserta lain.',
            'phone.min'              => 'Nomor telepon tidak valid (minimal 10 digit).',
            'phone.max'              => 'Nomor telepon tidak valid (maksimal 15 digit).',
            'whatsapp.min'           => 'Nomor WhatsApp tidak valid (minimal 10 digit).',
            'whatsapp.max'           => 'Nomor WhatsApp tidak valid (maksimal 15 digit).',
            'kodepos.digits'         => 'Kode pos harus terdiri dari 5 digit angka.',
            'tahun_lahir.min'        => 'Tahun lahir tidak valid.',
            'tahun_lahir.max'        => 'Tahun lahir tidak boleh melebihi tahun sekarang.',
            'tahun_lulus.min'        => 'Tahun lulus tidak valid.',
            'tahun_lulus.max'        => 'Tahun lulus tidak boleh melebihi tahun sekarang.',
            'jenis_kelamin.in'       => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'preferensi_mode.in'     => 'Preferensi mode harus Online, Offline, atau Hybrid.',
            'link_medsos.url'        => 'Link media sosial harus berupa URL yang valid (contoh: https://...).',
            'foto_profil.image'      => 'Foto profil harus berupa file gambar.',
            'foto_profil.mimes'      => 'Foto profil harus berformat JPG atau PNG.',
            'foto_profil.max'        => 'Ukuran foto profil maksimal 2MB.',
            'scan_ktp.file'          => 'Scan KTP harus berupa file yang valid.',
            'scan_ktp.mimes'         => 'Scan KTP harus berformat JPG, PNG, atau PDF.',
            'scan_ktp.max'           => 'Ukuran scan KTP maksimal 5MB.',
            'kecamatan_id.exists'    => 'Kecamatan yang dipilih tidak ditemukan.',
            'kelurahan_id.exists'    => 'Kelurahan yang dipilih tidak ditemukan.',
        ];
    }

    /**
     * Label atribut yang lebih ramah pengguna untuk pesan validasi.
     */
    public function attributes(): array
    {
        return [
            'name'                    => 'nama (username)',
            'email'                   => 'alamat email',
            'phone'                   => 'nomor telepon',
            'whatsapp'                => 'nomor WhatsApp',
            'bio'                     => 'bio',
            'nik'                     => 'NIK',
            'status_tokoh'            => 'status tokoh',
            'sumber_informasi'        => 'sumber informasi',
            'sumber_informasi_detail' => 'detail sumber informasi',
            'kecamatan_id'            => 'kecamatan',
            'kelurahan_id'            => 'kelurahan',
            'nama_lengkap'            => 'nama lengkap',
            'jenis_kelamin'           => 'jenis kelamin',
            'tempat_lahir'            => 'tempat lahir',
            'tanggal_lahir'           => 'tanggal lahir',
            'bulan_lahir'             => 'bulan lahir',
            'tahun_lahir'             => 'tahun lahir',
            'alamat_ktp'              => 'alamat KTP',
            'rt'                      => 'RT',
            'rw'                      => 'RW',
            'kelurahan'               => 'kelurahan (teks)',
            'kecamatan'               => 'kecamatan (teks)',
            'kota'                    => 'kota',
            'provinsi'                => 'provinsi',
            'kodepos'                 => 'kode pos',
            'link_medsos'             => 'link media sosial',
            'pendidikan_terakhir'     => 'pendidikan terakhir',
            'nama_institusi'          => 'nama institusi',
            'jurusan'                 => 'jurusan',
            'tahun_lulus'             => 'tahun lulus',
            'status_pekerjaan'        => 'status pekerjaan',
            'nama_perusahaan'         => 'nama perusahaan',
            'bidang_minat'            => 'bidang minat',
            'tujuan_pelatihan'        => 'tujuan pelatihan',
            'preferensi_jadwal'       => 'preferensi jadwal',
            'preferensi_mode'         => 'preferensi mode',
            'foto_profil'             => 'foto profil',
            'scan_ktp'                => 'scan KTP',
        ];
    }

    /**
     * Kembalikan hanya field yang termasuk model User.
     *
     * Digunakan di controller untuk $user->update($request->getUserData()).
     *
     * @return array<string, mixed>
     */
    public function getUserData(): array
    {
        $keys = [
            'name',
            'email',
            'phone',
            'whatsapp',
            'bio',
            'nik',
            'status_tokoh',
            'sumber_informasi',
            'sumber_informasi_detail',
            'kecamatan_id',
            'kelurahan_id',
        ];

        if ($this->input('section')) {
            $filteredKeys = [];
            foreach ($keys as $key) {
                if ($this->has($key)) {
                    $filteredKeys[] = $key;
                }
            }
            $data = $this->only($filteredKeys);
            if ($this->has('status_tokoh')) {
                $data['status_tokoh'] = $this->boolean('status_tokoh');
            }
            return $data;
        }

        return $this->only($keys) + [
            // Pastikan status_tokoh selalu boolean (bukan null yang ambigu)
            'status_tokoh' => $this->boolean('status_tokoh'),
        ];
    }

    /**
     * Kembalikan hanya field yang termasuk model PesertaProfile.
     *
     * Tidak termasuk file upload (foto_profil, scan_ktp) karena
     * ditangani secara terpisah via Storage di controller.
     *
     * @return array<string, mixed>
     */
    public function getProfileData(): array
    {
        $keys = [
            'nama_lengkap',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'bulan_lahir',
            'tahun_lahir',
            'alamat_ktp',
            'rt',
            'rw',
            'kelurahan_id',
            'kelurahan',
            'kecamatan',
            'kota',
            'provinsi',
            'kodepos',
            'link_medsos',
            'pendidikan_terakhir',
            'nama_institusi',
            'jurusan',
            'tahun_lulus',
            'status_pekerjaan',
            'nama_perusahaan',
            'bidang_minat',
            'tujuan_pelatihan',
            'preferensi_jadwal',
            'preferensi_mode',
            'pelatihan_id',
            'batch_pelatihan',
            'jawaban_pertanyaan',
        ];

        if ($this->input('section')) {
            $filteredKeys = [];
            foreach ($keys as $key) {
                if ($this->has($key)) {
                    $filteredKeys[] = $key;
                }
            }
            // Khusus bidang_minat, jika submit section preferensi tapi tidak terkirim, kita set empty array []
            if ($this->input('section') === 'preferensi' && !$this->has('bidang_minat')) {
                $data = $this->only($filteredKeys);
                $data['bidang_minat'] = [];
                return $data;
            }
            return $this->only($filteredKeys);
        }

        return $this->only($keys);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaProfile extends Model
{
    /**
     * Pilihan pendidikan terakhir.
     * Digunakan oleh controller editBiodata untuk dropdown.
     */
    public const PENDIDIKAN_OPTIONS = [
        'SD'          => 'SD / Sederajat',
        'SMP'         => 'SMP / Sederajat',
        'SMA'         => 'SMA / SMK / Sederajat',
        'D1'          => 'D1',
        'D2'          => 'D2',
        'D3'          => 'D3',
        'D4'          => 'D4 / S1 Terapan',
        'S1'          => 'S1 / Sarjana',
        'S2'          => 'S2 / Magister',
        'S3'          => 'S3 / Doktor',
        'Lainnya'     => 'Lainnya',
    ];

    /**
     * Pilihan status pekerjaan.
     * Digunakan oleh controller editBiodata untuk dropdown.
     */
    public const PEKERJAAN_OPTIONS = [
        'Pelajar/Mahasiswa'    => 'Pelajar / Mahasiswa',
        'Karyawan Swasta'      => 'Karyawan Swasta',
        'PNS/ASN'              => 'PNS / ASN',
        'TNI/Polri'            => 'TNI / Polri',
        'Wiraswasta'           => 'Wiraswasta',
        'Freelance'            => 'Freelance / Pekerja Lepas',
        'Wirausaha'            => 'Wirausaha / Pengusaha',
        'Petani/Nelayan'       => 'Petani / Nelayan',
        'Ibu Rumah Tangga'     => 'Ibu Rumah Tangga',
        'Tidak Bekerja'        => 'Tidak Bekerja',
        'Lainnya'              => 'Lainnya',
    ];

    /**
     * Pilihan bidang minat (multi-select).
     * Digunakan oleh controller editBiodata untuk dropdown multi-select.
     * Disimpan sebagai JSON array di database.
     */
    public const MINAT_OPTIONS = [
        'Teknologi Informasi'         => 'Teknologi Informasi',
        'Desain Grafis'               => 'Desain Grafis',
        'Kewirausahaan'               => 'Kewirausahaan',
        'Pertanian & Perkebunan'      => 'Pertanian & Perkebunan',
        'Kesehatan'                   => 'Kesehatan',
        'Pendidikan'                  => 'Pendidikan',
        'Hukum & Kebijakan Publik'    => 'Hukum & Kebijakan Publik',
        'Komunikasi & Jurnalistik'    => 'Komunikasi & Jurnalistik',
        'Seni & Budaya'               => 'Seni & Budaya',
        'Lingkungan Hidup'            => 'Lingkungan Hidup',
        'Sosial & Kemasyarakatan'     => 'Sosial & Kemasyarakatan',
        'Keuangan & Perbankan'        => 'Keuangan & Perbankan',
        'Pariwisata'                  => 'Pariwisata',
        'Lainnya'                     => 'Lainnya',
    ];

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'bulan_lahir',
        'tahun_lahir',
        'nik',
        'alamat_ktp',
        'rt',
        'rw',
        'kelurahan_id',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kodepos',
        'whatsapp',
        'email',
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
        'foto_profil',
        'scan_ktp',
        'batch_pelatihan',
        'pelatihan_id',
        'jawaban_pertanyaan',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'bidang_minat'       => 'array',
            'link_medsos'        => 'array',
            'jawaban_pertanyaan' => 'array',
            'is_completed'       => 'boolean',
        ];
    }

    /**
     * Relasi: PesertaProfile dimiliki oleh User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: PesertaProfile memiliki satu Kelurahan (via kelurahan_id).
     */
    public function dataKelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    /**
     * Alias relasi kelurahan untuk kemudahan akses di view/controller.
     */
    public function kelurahan(): BelongsTo
    {
        return $this->dataKelurahan();
    }

    /**
     * Relasi: PesertaProfile memiliki satu Kecamatan melalui kelurahan.
     * Digunakan untuk menampilkan data kecamatan pada form edit biodata.
     *
     * Catatan: kolom kecamatan_id tidak ada di peserta_profiles (hanya ada di users).
     * Kecamatan dapat diakses via $profile->dataKelurahan->kecamatan
     * atau via $profile->user->kecamatan.
     */

    /**
     * Relasi: PesertaProfile terkait dengan Pelatihan (via pelatihan_id).
     */
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }
}

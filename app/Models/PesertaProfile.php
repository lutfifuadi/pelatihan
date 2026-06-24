<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaProfile extends Model
{
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
            'bidang_minat' => 'array',
            'link_medsos' => 'array',
            'is_completed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataKelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->dataKelurahan();
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }
}

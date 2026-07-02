<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanPelatihan extends Model
{
    use HasFactory;

    protected $table = 'pengumuman_pelatihans';

    protected $fillable = [
        'pelatihan_id',
        'user_id',
        'judul',
        'konten',
        'is_private', // true jika privat (hanya siswa terdaftar), false jika publik
        'is_pinned',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Relasi ke model Pelatihan.
     */
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    /**
     * Relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

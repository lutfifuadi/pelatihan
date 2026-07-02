<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KtaMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'status_kta',
        'wilayah',
        'keterangan',
    ];

    /**
     * Mutator untuk nama_lengkap agar selalu huruf besar.
     */
    protected function setNamaLengkapAttribute($value)
    {
        $this->attributes['nama_lengkap'] = mb_strtoupper($value);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;
use App\Models\Traits\HasSeo;
use Illuminate\Support\Str;

class Pelatihan extends Model
{
    use HasSeo;
    protected $table = 'pelatihan';

    protected $fillable = [
        'nama',
        'batch',
        'deskripsi',
        'batas_pendaftaran',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'is_active',
        'dinas_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'batas_pendaftaran' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function dinas()
    {
        return $this->belongsTo(Dinas::class);
    }

    public function kecamatans()
    {
        return $this->belongsToMany(Kecamatan::class, 'kecamatan_pelatihan');
    }

    public function seoTitle(): ?string
    {
        return $this->nama . ' | Pelatihan';
    }

    public function seoDescription(): ?string
    {
        return Str::limit($this->deskripsi, 160);
    }

    public function seoKeywords(): ?string
    {
        return 'pelatihan, ' . $this->nama;
    }
}

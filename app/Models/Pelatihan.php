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
        'auto_approve',
        'is_active',
        'dinas_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'auto_approve' => 'boolean',
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

    public function pesertaProfiles()
    {
        return $this->hasMany(PesertaProfile::class, 'pelatihan_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function pendingEnrollments()
    {
        return $this->hasMany(Enrollment::class)->where('status', 'pending');
    }

    public function approvedEnrollments()
    {
        return $this->hasMany(Enrollment::class)->where('status', 'approved');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class)->orderBy('pertemuan_ke');
    }

    public function activeSchedules()
    {
        return $this->hasMany(Schedule::class)->active()->orderBy('pertemuan_ke');
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

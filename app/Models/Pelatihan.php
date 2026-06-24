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

    public function isPendaftaranDitutup(): bool
    {
        if (is_null($this->batas_pendaftaran)) {
            return false;
        }
        return now()->startOfDay()->gt($this->batas_pendaftaran);
    }

    public function isKuotaPenuh(): bool
    {
        if (is_null($this->kuota)) {
            return false;
        }
        return $this->approvedEnrollments()->count() >= $this->kuota;
    }

    public function sisaKuota(): int
    {
        if (is_null($this->kuota)) {
            return PHP_INT_MAX;
        }
        return max(0, $this->kuota - $this->approvedEnrollments()->count());
    }

    public function scopeWithRegistrationStatus($query)
    {
        return $query->selectRaw("*,
            CASE
                WHEN batas_pendaftaran IS NOT NULL AND batas_pendaftaran < CURDATE() THEN 1
                ELSE 0
            END as is_ditutup
        ");
    }

    public function getIsDitutupAttribute(): bool
    {
        return $this->isPendaftaranDitutup();
    }
}

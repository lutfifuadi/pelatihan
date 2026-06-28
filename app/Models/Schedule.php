<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = [
        'pelatihan_id',
        'judul',
        'deskripsi',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'tipe',
        'pertemuan_ke',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relasi ke pelatihan.
     */
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relasi many-to-many ke instruktur (User) melalui pivot schedule_instruktur.
     */
    public function instrukturs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'schedule_instruktur')
                    ->withPivot('is_utama')
                    ->withTimestamps();
    }

    /**
     * Ambil instruktur utama dari jadwal ini.
     */
    public function instrukturUtama(): BelongsToMany
    {
        return $this->instrukturs()->wherePivot('is_utama', true);
    }
}

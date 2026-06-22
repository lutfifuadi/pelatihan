<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Pelatihan;

class Kecamatan extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi: Satu kecamatan bisa memiliki banyak user (koordinator/peserta)
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Satu kecamatan memiliki banyak kelurahan
     */
    public function kelurahans(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function pelatihans(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'kecamatan_pelatihan');
    }
}

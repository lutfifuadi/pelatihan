<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleInstruktur extends Model
{
    protected $table = 'schedule_instruktur';

    public $timestamps = false;

    protected $fillable = [
        'schedule_id',
        'user_id',
        'is_utama',
    ];

    protected function casts(): array
    {
        return [
            'is_utama' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke schedule.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Relasi ke user (instruktur).
     */
    public function instruktur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

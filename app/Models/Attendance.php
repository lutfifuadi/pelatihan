<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'enrollment_id',
        'pertemuan_ke',
        'status',
        'date',
        'verified_method',
        'latitude_panitia',
        'longitude_panitia',
        'distance_from_center',
        'ip_address',
        'device_user',
        'scanner_by',
        'bypassed_by',
        'bypass_reason',
        'corrected_by',
        'corrected_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'pertemuan_ke' => 'integer',
            'latitude_panitia' => 'decimal:8',
            'longitude_panitia' => 'decimal:8',
            'distance_from_center' => 'integer',
            'corrected_at' => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanner_by');
    }

    public function bypassedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bypassed_by');
    }

    public function correctedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }

    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }
}

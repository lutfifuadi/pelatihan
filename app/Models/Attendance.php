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
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'pertemuan_ke' => 'integer',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }
}

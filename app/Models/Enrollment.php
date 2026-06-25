<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'pelatihan_id',
        'status',
        'notes',
        'approved_at',
        'rejected_at',
        'waitlist_promoted_at',
        'verification_code',
        'verification_code_expires_at',
        'wa_confirmed_at',
        'wa_confirmed_by',
        'newbimma_checked_at',
        'newbimma_checked_by',
        'newbimma_result',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'waitlist_promoted_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'wa_confirmed_at' => 'datetime',
            'newbimma_checked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    // Scope helper
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeWaitlist($query)
    {
        return $query->where('status', 'waitlist');
    }

    public function scopeWaitingConfirmation($query)
    {
        return $query->where('status', 'waiting_wa_confirmation');
    }

    public function scopeWaitingNewbimmaCheck($query)
    {
        return $query->where('status', 'waiting_newbimma_check');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}

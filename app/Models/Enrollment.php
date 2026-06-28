<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id',
        'pelatihan_id',
        'dinas_id',
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
        'is_kta_priority',
    ];

    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'waitlist_promoted_at' => 'datetime',
            'verification_code_expires_at' => 'datetime',
            'wa_confirmed_at' => 'datetime',
            'newbimma_checked_at' => 'datetime',
            'is_kta_priority' => 'boolean',
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

    public function dinas(): BelongsTo
    {
        return $this->belongsTo(Dinas::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Get human-readable label for the current status.
     */
    public function statusLabel(): string
    {
        return $this->status?->label() ?? '-';
    }

    // Scope helper
    public function scopePending($query)
    {
        return $query->where('status', EnrollmentStatus::Pending);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', EnrollmentStatus::Approved);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', EnrollmentStatus::Rejected);
    }

    public function scopeWaitlist($query)
    {
        return $query->where('status', EnrollmentStatus::Waitlist);
    }

    public function scopeWaitingConfirmation($query)
    {
        return $query->where('status', EnrollmentStatus::WaitingWaConfirmation);
    }

    public function scopeWaitingNewbimmaCheck($query)
    {
        return $query->where('status', EnrollmentStatus::WaitingNewbimmaCheck);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', EnrollmentStatus::Confirmed);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PushNotificationRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'subscription_id',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Relasi: Log penerima milik satu notifikasi.
     */
    public function notification(): BelongsTo
    {
        return $this->belongsTo(PushNotification::class, 'notification_id');
    }

    /**
     * Relasi: Log penerima milik satu subscription.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(PushSubscription::class, 'subscription_id');
    }

    /**
     * Scope: Filter berdasarkan status pengiriman.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}

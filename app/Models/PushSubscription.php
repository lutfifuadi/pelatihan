<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PushSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'endpoint',
        'endpoint_hash',
        'p256dh_key',
        'auth_key',
        'content_encoding',
        'device_label',
        'browser',
        'is_active',
        'failed_count',
        'last_failed_at',
        'last_used_at',
        'user_agent',
        'platform',
        'subscribed_at',
        'expired_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'failed_count' => 'integer',
        'last_failed_at' => 'datetime',
        'last_used_at' => 'datetime',
        'subscribed_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Relasi: Subscription dimiliki oleh satu user (nullable).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Subscription memiliki banyak log pengiriman notifikasi.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(PushNotificationRecipient::class, 'subscription_id');
    }

    /**
     * Scope: Subscription yang aktif dan belum expired.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            });
    }

    /**
     * Scope: Subscription yang sudah expired.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_at')
            ->where('expired_at', '<=', now());
    }
}

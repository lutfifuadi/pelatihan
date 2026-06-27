<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PushNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'link_url',
        'target_type',
        'target_filters',
        'total_target',
        'sent_at',
    ];

    protected $casts = [
        'target_filters' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Relasi: Notifikasi dibuat oleh satu admin (user).
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi: Notifikasi memiliki banyak log penerima.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(PushNotificationRecipient::class, 'notification_id');
    }

    /**
     * Scope: Notifikasi yang sudah dikirim.
     */
    public function scopeSent($query)
    {
        return $query->whereNotNull('sent_at');
    }

    /**
     * Scope: Notifikasi yang masih pending (belum dikirim).
     */
    public function scopePending($query)
    {
        return $query->whereNull('sent_at');
    }
}

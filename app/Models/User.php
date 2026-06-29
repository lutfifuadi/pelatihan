<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\Kelurahan;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kecamatan_id',
        'kelurahan_id',
        'phone',
        'avatar',
        'bio',
        'is_active',
        'nik',
        'status_tokoh',
        'sumber_informasi',
        'sumber_informasi_detail',
        'google_drive_photo_url',
        'google_drive_ktp_url',
        'google_drive_folder_id',
        'whatsapp',
    ];

    /**
     * Relasi: User (koordinator) memiliki satu kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Relasi: User memiliki satu kelurahan
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Relasi: User memiliki satu profil peserta
     */
    public function pesertaProfile()
    {
        return $this->hasOne(PesertaProfile::class);
    }

    /**
     * Relasi: User memiliki banyak enrollment (pendaftaran pelatihan)
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Relasi: User memiliki banyak activity logs.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relasi: User memiliki banyak push subscription (browser push).
     */
    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    /**
     * Relasi: User (admin) memiliki banyak notifikasi yang dikirim.
     */
    public function pushNotifications(): HasMany
    {
        return $this->hasMany(PushNotification::class, 'admin_id');
    }

    /**
     * Relasi many-to-many ke schedules (sebagai instruktur).
     */
    public function schedulesAsInstruktur(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'schedule_instruktur')
                    ->withPivot('is_utama')
                    ->withTimestamps();
    }

    /**
     * Relasi: User memiliki banyak audit logs sebagai subjek (target) perubahan.
     * Digunakan untuk melihat riwayat perubahan biodata peserta oleh admin.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'target_id')
                    ->where('target_entity', 'User');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            // status_tokoh disimpan sebagai string di DB ('0'/'1'/null),
            // namun di-cast boolean agar konsisten saat diakses di kode.
            'status_tokoh'      => 'boolean',
        ];
    }
}

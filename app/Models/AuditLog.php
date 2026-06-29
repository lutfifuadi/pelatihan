<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'actor_role',
        'action_type',
        'target_entity',
        'target_id',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_data'  => 'array',
            'new_data'  => 'array',
            'target_id' => 'integer',
        ];
    }

    /**
     * Relasi: AuditLog dimiliki oleh User sebagai aktor (admin/super_admin).
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Catat satu record audit log.
     *
     * Digunakan oleh controller (misal: PesertaController::updateBiodata)
     * untuk mencatat perubahan data yang dilakukan admin.
     *
     * Contoh penggunaan:
     * ```php
     * AuditLog::record(
     *     action: 'update_biodata_by_admin',
     *     subjectType: 'User',
     *     subjectId: $user->id,
     *     oldValues: ['user' => $oldUserData, 'profile' => $oldProfileData],
     *     newValues: ['user' => $newUserData, 'profile' => $newProfileData],
     *     notes: "Biodata diubah oleh admin: " . auth()->user()->name,
     * );
     * ```
     *
     * @param  string       $action       Nilai dari enum action_type di tabel audit_logs
     * @param  string       $subjectType  Nama entitas target (misal: 'User', 'PesertaProfile')
     * @param  int          $subjectId    ID dari entitas target
     * @param  array        $oldValues    Data sebelum perubahan (hanya field yang berubah)
     * @param  array        $newValues    Data setelah perubahan (hanya field yang berubah)
     * @param  string|null  $notes        Keterangan tambahan (opsional)
     * @return static
     */
    public static function record(
        string $action,
        string $subjectType,
        int $subjectId,
        array $oldValues = [],
        array $newValues = [],
        ?string $notes = null,
    ): static {
        /** @var \App\Models\User|null $actor */
        $actor = auth()->check() ? auth()->user() : null;

        return static::create([
            'actor_id'      => $actor?->id,
            'actor_role'    => $actor?->role ?? 'admin',
            'action_type'   => $action,
            'target_entity' => $subjectType,
            'target_id'     => $subjectId,
            'description'   => $notes,
            'old_data'      => $oldValues,
            'new_data'      => $newValues,
            'ip_address'    => Request::ip(),
            'user_agent'    => Request::userAgent(),
        ]);
    }
}

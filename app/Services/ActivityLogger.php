<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat aktivitas admin ke dalam log.
     *
     * @param string $action       Tipe aksi: created, updated, deleted, approved, rejected, login, export
     * @param string $subjectType  Nama entitas: Pelatihan, Peserta, Enrollment, Sertifikat, dll
     * @param int|null $subjectId  ID entitas
     * @param string|null $subjectName Nama/identifikasi entitas agar mudah dibaca
     * @param string|null $description Deskripsi aktivitas
     * @param array|null $oldValues Data sebelum perubahan (JSON)
     * @param array|null $newValues Data setelah perubahan (JSON)
     * @return ActivityLog
     */
    public static function log(
        string $action,
        string $subjectType,
        ?int $subjectId = null,
        ?string $subjectName = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ?ActivityLog {
        $user = Auth::user();
        if (!$user) {
            return null;
        }
        $request = Request::instance();

        $log = ActivityLog::create([
            'user_id'      => $user?->id,
            'action'       => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'subject_name' => $subjectName,
            'description'  => $description,
            'old_values'   => $oldValues ? json_encode($oldValues) : null,
            'new_values'   => $newValues ? json_encode($newValues) : null,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
        ]);

        try {
            event(new \App\Events\DashboardUpdated());
        } catch (\Throwable $e) {
            // Bypass if broadcast is offline
        }

        return $log;
    }

    /**
     * Helper: Catat aktivitas "created".
     *
     * @param mixed $subject     Instance model (harus punya id dan atribut yang bisa diidentifikasi)
     * @param string|null $description
     * @return ActivityLog
     */
    public static function created($subject, ?string $description = null): ActivityLog
    {
        $subjectType = class_basename($subject);
        $subjectName = static::getSubjectName($subject);
        $newValues   = $subject->getAttributes();

        return static::log(
            action: 'created',
            subjectType: $subjectType,
            subjectId: $subject->id,
            subjectName: $subjectName,
            description: $description ?? static::defaultDescription('created', $subjectType, $subjectName),
            newValues: $newValues,
        );
    }

    /**
     * Helper: Catat aktivitas "updated".
     *
     * @param mixed $subject     Instance model sebelum diubah (data lama)
     * @param array $oldValues   Data sebelum perubahan
     * @param array $newValues   Data setelah perubahan
     * @param string|null $description
     * @return ActivityLog
     */
    public static function updated($subject, array $oldValues, array $newValues, ?string $description = null): ActivityLog
    {
        $subjectType = class_basename($subject);
        $subjectName = static::getSubjectName($subject);

        return static::log(
            action: 'updated',
            subjectType: $subjectType,
            subjectId: $subject->id,
            subjectName: $subjectName,
            description: $description ?? static::defaultDescription('updated', $subjectType, $subjectName),
            oldValues: $oldValues,
            newValues: $newValues,
        );
    }

    /**
     * Helper: Catat aktivitas "deleted".
     *
     * @param mixed $subject     Instance model yang akan dihapus
     * @param string|null $description
     * @return ActivityLog
     */
    public static function deleted($subject, ?string $description = null): ActivityLog
    {
        $subjectType = class_basename($subject);
        $subjectName = static::getSubjectName($subject);
        $oldValues   = $subject->getAttributes();

        return static::log(
            action: 'deleted',
            subjectType: $subjectType,
            subjectId: $subject->id,
            subjectName: $subjectName,
            description: $description ?? static::defaultDescription('deleted', $subjectType, $subjectName),
            oldValues: $oldValues,
        );
    }

    /**
     * Helper: Catat aktivitas kustom (action bebas).
     *
     * @param string $action
     * @param string $subjectType
     * @param string $description
     * @param int|null $subjectId
     * @param string|null $subjectName
     * @return ActivityLog
     */
    public static function action(string $action, string $subjectType, string $description, ?int $subjectId = null, ?string $subjectName = null): ActivityLog
    {
        return static::log(
            action: $action,
            subjectType: $subjectType,
            subjectId: $subjectId,
            subjectName: $subjectName,
            description: $description,
        );
    }

    /**
     * Ambil nama/identifikasi dari subject model.
     */
    private static function getSubjectName($subject): ?string
    {
        if (method_exists($subject, 'getActivityLogName')) {
            return $subject->getActivityLogName();
        }

        return $subject->name
            ?? $subject->nama
            ?? $subject->title
            ?? $subject->judul
            ?? "#{$subject->id}";
    }

    /**
     * Buat deskripsi default berdasarkan aksi.
     */
    private static function defaultDescription(string $action, string $subjectType, ?string $subjectName): string
    {
        $messages = [
            'created' => "{$subjectType} {$subjectName} berhasil dibuat",
            'updated' => "{$subjectType} {$subjectName} berhasil diperbarui",
            'deleted' => "{$subjectType} {$subjectName} berhasil dihapus",
            'approved' => "{$subjectType} {$subjectName} berhasil disetujui",
            'rejected' => "{$subjectType} {$subjectName} berhasil ditolak",
        ];

        return $messages[$action] ?? "{$action} {$subjectType} {$subjectName}";
    }
}

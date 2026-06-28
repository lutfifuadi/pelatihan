<?php

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Models\KtaMember;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KtaVerificationService
{
    /**
     * Mode verifikasi KTA yang tersedia.
     */
    public const MODE_OFF = 'off';
    public const MODE_PRIORITY = 'priority';
    public const MODE_AUTO_APPROVE = 'auto_approve';

    /**
     * Normalisasi NIK: hapus spasi dan karakter non-angka.
     */
    public function normalizeNik(?string $nik): string
    {
        if ($nik === null) {
            return '';
        }

        return preg_replace('/\D/', '', $nik);
    }

    /**
     * Cari keanggotaan KTA aktif berdasarkan NIK user.
     *
     * @return KtaMember|null
     */
    public function verify(User $user): ?KtaMember
    {
        $nik = $this->normalizeNik($user->nik);

        if ($nik === '') {
            return null;
        }

        return KtaMember::where('nik', $nik)
            ->where('status_kta', 'Aktif')
            ->first();
    }

    /**
     * Terapkan aksi verifikasi KTA pada enrollment berdasarkan mode sistem.
     */
    public function applyEnrollmentLogic(Enrollment $enrollment, User $user): void
    {
        $mode = $this->getMode();

        if ($mode === self::MODE_OFF) {
            return;
        }

        $ktaMember = $this->verify($user);

        if (!$ktaMember) {
            return;
        }

        match ($mode) {
            self::MODE_AUTO_APPROVE => $this->applyAutoApprove($enrollment, $user, $ktaMember),
            self::MODE_PRIORITY => $this->applyPriority($enrollment, $user, $ktaMember),
            default => null,
        };
    }

    /**
     * Ambil mode verifikasi KTA dari pengaturan sistem.
     */
    public function getMode(): string
    {
        $mode = Setting::where('key', 'kta_verification_mode')->value('value');

        return in_array($mode, [self::MODE_OFF, self::MODE_PRIORITY, self::MODE_AUTO_APPROVE], true)
            ? $mode
            : self::MODE_OFF;
    }

    /**
     * Cek apakah fitur Wajib Chat WA aktif.
     *
     * Menggunakan setting `validate_whatsapp` sebagai indikator modul WA aktif.
     */
    public function isWajibChatWaActive(): bool
    {
        return (Setting::where('key', 'validate_whatsapp')->value('value') ?? '1') === '1';
    }

    /**
     * Terapkan logika auto-approve untuk anggota KTA aktif.
     *
     * Urutan pengecekan (sesuai PRD BR-003):
     * 1. Cek kuota: jika penuh → waitlist + is_kta_priority (tidak lanjut ke WA)
     * 2. Jika kuota tersedia:
     *    a. Jika Wajib Chat WA aktif → waiting_wa_confirmation
     *    b. Jika tidak → approved + approved_at
     */
    private function applyAutoApprove(Enrollment $enrollment, User $user, KtaMember $ktaMember): void
    {
        $changes = [
            'is_kta_priority' => true,
        ];

        // Load relasi pelatihan jika belum ter-load
        $pelatihan = $enrollment->pelatihan ?? $enrollment->load('pelatihan')->pelatihan;

        // Langkah 1: Cek kuota (prioritas utama)
        if ($pelatihan && $pelatihan->isKuotaPenuh()) {
            // Kuota penuh: masukkan waitlist dengan flag prioritas KTA
            $changes['status'] = EnrollmentStatus::Waitlist;
        } elseif ($this->isWajibChatWaActive()) {
            // Langkah 2a: Kuota tersedia, tapi Wajib Chat WA aktif
            $changes['status'] = EnrollmentStatus::WaitingWaConfirmation;
            $changes['verification_code'] = \App\Services\VerificationCodeService::generate($enrollment);
            $changes['verification_code_expires_at'] = now()->addHours(24);
        } else {
            // Langkah 2b: Kuota tersedia, tidak ada Wajib Chat WA → auto-approve langsung
            $changes['status'] = EnrollmentStatus::Approved;
            $changes['approved_at'] = now();
        }

        DB::transaction(function () use ($enrollment, $changes, $user, $ktaMember) {
            $oldValues = $enrollment->only(['status', 'is_kta_priority', 'approved_at', 'verification_code']);

            $enrollment->update($changes);

            $this->logAction(
                $enrollment,
                $user,
                $ktaMember,
                "Sistem menyetujui pendaftaran {$user->name} secara otomatis karena keanggotaan KTA valid",
                $oldValues,
                $changes
            );
        });
    }

    /**
     * Terapkan logika prioritas untuk anggota KTA aktif.
     */
    private function applyPriority(Enrollment $enrollment, User $user, KtaMember $ktaMember): void
    {
        if ($enrollment->is_kta_priority) {
            return;
        }

        $oldValues = $enrollment->only(['is_kta_priority']);

        $enrollment->update([
            'is_kta_priority' => true,
        ]);

        $this->logAction(
            $enrollment,
            $user,
            $ktaMember,
            "Sistem menandai pendaftaran {$user->name} sebagai prioritas KTA",
            $oldValues,
            ['is_kta_priority' => true]
        );
    }

    /**
     * Catat aktivitas auto-approve atau penandaan prioritas ke activity log.
     *
     * Menggunakan ActivityLogger::logSystem() jika tidak ada Auth user (proses sistem),
     * fallback ke ActivityLogger::log() jika user sedang login (proses normal).
     */
    private function logAction(
        Enrollment $enrollment,
        User $user,
        KtaMember $ktaMember,
        string $description,
        array $oldValues,
        array $newValues
    ): void {
        try {
            $maskedNik = $this->maskNik($ktaMember->nik);
            $fullDescription = $description . " (NIK: {$maskedNik}, Wilayah: {$ktaMember->wilayah})";

            ActivityLogger::logAsSystem(
                action: 'auto_approved',
                subjectType: 'Enrollment',
                subjectId: $enrollment->id,
                subjectName: $user->name,
                description: $fullDescription,
                oldValues: $oldValues,
                newValues: $newValues,
                actorUser: $user,
            );
        } catch (\Exception $e) {
            Log::warning('Gagal mencatat activity log KTA: ' . $e->getMessage());
        }
    }

    /**
     * Masking NIK untuk keamanan privasi di log.
     */
    private function maskNik(?string $nik): string
    {
        if (!$nik) {
            return '-';
        }

        $length = strlen($nik);
        if ($length <= 8) {
            return $nik;
        }

        return substr($nik, 0, 4) . str_repeat('*', $length - 8) . substr($nik, -4);
    }
}

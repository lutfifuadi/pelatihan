<?php

namespace App\Services;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class EnrollmentCooldownService
{
    /**
     * Status enrollment yang dianggap masih aktif (belum selesai/ditolak).
     *
     * @var array<string>
     */
    protected array $activeStatuses = [
        'pending',
        'approved',
        'waitlist',
        'waiting_wa_confirmation',
        'waiting_newbimma_check',
    ];

    /**
     * Status enrollment yang dianggap selesai/lulus.
     *
     * @var array<string>
     */
    protected array $completedStatuses = [
        'confirmed',
        'completed',
        'passed',
    ];

    /**
     * Status enrollment yang dianggap ditolak/dibatalkan.
     *
     * @var array<string>
     */
    protected array $rejectedStatuses = [
        'rejected',
        'cancelled',
    ];

    /**
     * Ambil durasi cooldown (hari) dari pengaturan sistem.
     */
    public function getCooldownDays(): int
    {
        $days = Setting::where('key', 'cooldown_period_days')->value('value');

        if (!is_numeric($days) || (int) $days < 0) {
            return 30;
        }

        return (int) $days;
    }

    /**
     * Ambil durasi cooldown lulus (hari) dari pengaturan sistem.
     */
    public function getCooldownPassedDays(): int
    {
        $days = Setting::where('key', 'cooldown_period_passed_days')->value('value');

        if (!is_numeric($days) || (int) $days < 0) {
            return 365;
        }

        return (int) $days;
    }

    /**
     * Cari riwayat enrollment terakhir user pada pelatihan & dinas tertentu.
     */
    public function getLastEnrollment(User $user, Pelatihan $pelatihan): ?Enrollment
    {
        return Enrollment::where('user_id', $user->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->when($pelatihan->dinas_id, function ($query) use ($pelatihan) {
                $query->where('dinas_id', $pelatihan->dinas_id);
            })
            ->latest('created_at')
            ->first();
    }

    /**
     * Dapatkan status riwayat pendaftaran terakhir beserta informasi cooldown.
     *
     * @return array<string, mixed>
     */
    public function getEnrollmentStatus(User $user, Pelatihan $pelatihan): array
    {
        $enrollment = $this->getLastEnrollment($user, $pelatihan);

        if (!$enrollment) {
            return [
                'status' => 'none',
                'enrollment' => null,
                'enrollment_status' => null,
                'enrollment_status_label' => null,
                'can_register' => true,
                'message' => null,
                'can_register_at' => null,
                'remaining_time' => null,
                'remaining_text' => null,
            ];
        }

        $statusValue = $enrollment->status instanceof EnrollmentStatus
            ? $enrollment->status->value
            : $enrollment->status;

        if (in_array($statusValue, $this->activeStatuses, true)) {
            return [
                'status' => 'active',
                'enrollment' => $enrollment,
                'enrollment_status' => $statusValue,
                'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
                'can_register' => false,
                'message' => $this->getActiveMessage($statusValue),
                'can_register_at' => null,
                'remaining_time' => null,
                'remaining_text' => null,
            ];
        }

        if (in_array($statusValue, $this->completedStatuses, true)) {
            return $this->buildCompletedStatus($enrollment, $statusValue);
        }

        if (in_array($statusValue, $this->rejectedStatuses, true)) {
            return $this->buildRejectedStatus($enrollment, $statusValue);
        }

        // Fallback: perlakukan status tidak dikenal sebagai boleh daftar ulang
        return [
            'status' => 'none',
            'enrollment' => $enrollment,
            'enrollment_status' => $statusValue,
            'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
            'can_register' => true,
            'message' => null,
            'can_register_at' => null,
            'remaining_time' => null,
            'remaining_text' => null,
        ];
    }

    /**
     * Cek apakah user diperbolehkan mendaftar pelatihan ini.
     */
    public function canRegister(User $user, Pelatihan $pelatihan): bool
    {
        return $this->getEnrollmentStatus($user, $pelatihan)['can_register'];
    }

    /**
     * Validasi bahwa user boleh mendaftar pelatihan ini.
     *
     * @throws ValidationException
     */
    public function validateRegistrationAllowed(User $user, Pelatihan $pelatihan): void
    {
        $status = $this->getEnrollmentStatus($user, $pelatihan);

        if ($status['can_register']) {
            return;
        }

        throw ValidationException::withMessages([
            'pelatihan_id' => $status['message'] ?? 'Anda tidak dapat mendaftar pelatihan ini saat ini.',
        ]);
    }

    /**
     * Bangun status untuk enrollment yang telah selesai/lulus.
     *
     * @return array<string, mixed>
     */
    private function buildCompletedStatus(Enrollment $enrollment, string $statusValue): array
    {
        $cooldownDays = $this->getCooldownPassedDays();
        $lastUpdated = $enrollment->updated_at ?? $enrollment->created_at;
        $canRegisterAt = $lastUpdated ? $lastUpdated->copy()->addDays($cooldownDays) : null;

        if (!$canRegisterAt || now()->greaterThanOrEqualTo($canRegisterAt)) {
            return [
                'status' => 'completed_available',
                'enrollment' => $enrollment,
                'enrollment_status' => $statusValue,
                'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
                'can_register' => true,
                'message' => 'Anda telah menyelesaikan pelatihan ini sebelumnya. Anda kini diperbolehkan untuk mendaftar kembali.',
                'can_register_at' => $canRegisterAt,
                'remaining_time' => null,
                'remaining_text' => null,
            ];
        }

        $remaining = $this->calculateRemainingTime($canRegisterAt);

        return [
            'status' => 'completed_cooldown',
            'enrollment' => $enrollment,
            'enrollment_status' => $statusValue,
            'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
            'can_register' => false,
            'message' => "Anda telah menyelesaikan pelatihan ini sebelumnya. Sesuai kebijakan, Anda dapat mendaftar kembali dalam {$remaining['text']} (mulai tanggal {$canRegisterAt->format('d-m-Y H:i')}).",
            'can_register_at' => $canRegisterAt,
            'remaining_time' => $remaining['parts'],
            'remaining_text' => $remaining['text'],
        ];
    }

    /**
     * Bangun status untuk enrollment yang ditolak/dibatalkan.
     *
     * @param array<string> $statusValue
     * @return array<string, mixed>
     */
    private function buildRejectedStatus(Enrollment $enrollment, string $statusValue): array
    {
        $cooldownDays = $this->getCooldownDays();
        $lastUpdated = $enrollment->updated_at ?? $enrollment->created_at;
        $canRegisterAt = $lastUpdated ? $lastUpdated->copy()->addDays($cooldownDays) : null;

        if (!$canRegisterAt || now()->greaterThanOrEqualTo($canRegisterAt)) {
            return [
                'status' => 'rejected_available',
                'enrollment' => $enrollment,
                'enrollment_status' => $statusValue,
                'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
                'can_register' => true,
                'message' => 'Pendaftaran Anda sebelumnya ditolak/dibatalkan. Anda kini diperbolehkan untuk mendaftar kembali.',
                'can_register_at' => $canRegisterAt,
                'remaining_time' => null,
                'remaining_text' => null,
            ];
        }

        $remaining = $this->calculateRemainingTime($canRegisterAt);

        return [
            'status' => 'rejected_cooldown',
            'enrollment' => $enrollment,
            'enrollment_status' => $statusValue,
            'enrollment_status_label' => $enrollment->status?->label() ?? $statusValue,
            'can_register' => false,
            'message' => "Pendaftaran Anda sebelumnya ditolak/dibatalkan. Anda dapat mendaftar kembali dalam {$remaining['text']} (mulai tanggal {$canRegisterAt->format('d-m-Y H:i')}).",
            'can_register_at' => $canRegisterAt,
            'remaining_time' => $remaining['parts'],
            'remaining_text' => $remaining['text'],
        ];
    }

    /**
     * Hitung sisa waktu sebelum boleh daftar ulang.
     *
     * @return array{text: string, parts: array{days: int, hours: int, minutes: int}}
     */
    private function calculateRemainingTime(Carbon $canRegisterAt): array
    {
        $diff = now()->diff($canRegisterAt);

        $days = (int) $diff->days;
        $hours = (int) $diff->h;
        $minutes = (int) $diff->i;

        if ($days > 0) {
            $text = "{$days} hari {$hours} jam";
        } elseif ($hours > 0) {
            $text = "{$hours} jam {$minutes} menit";
        } else {
            $text = "{$minutes} menit";
        }

        return [
            'text' => $text,
            'parts' => [
                'days' => $days,
                'hours' => $hours,
                'minutes' => $minutes,
            ],
        ];
    }

    /**
     * Pesan informatif untuk enrollment dengan status aktif.
     */
    private function getActiveMessage(string $statusValue): string
    {
        return match ($statusValue) {
            'pending' => 'Anda telah terdaftar pada pelatihan ini dan sedang menunggu verifikasi.',
            'approved', 'waitlist' => 'Anda telah terdaftar dan disetujui untuk mengikuti pelatihan ini.',
            'waiting_wa_confirmation' => 'Anda telah terdaftar pada pelatihan ini dan sedang menunggu konfirmasi WhatsApp.',
            'waiting_newbimma_check' => 'Anda telah terdaftar pada pelatihan ini dan sedang menunggu pengecekan Newbimma.',
            default => 'Anda telah terdaftar pada pelatihan ini.',
        };
    }
}

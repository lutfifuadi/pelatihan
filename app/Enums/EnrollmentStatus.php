<?php

namespace App\Enums;

enum EnrollmentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case WaitingWaConfirmation = 'waiting_wa_confirmation';
    case WaitingNewbimmaCheck = 'waiting_newbimma_check';
    case Confirmed = 'confirmed';
    case Rejected = 'rejected';
    case Waitlist = 'waitlist';

    /**
     * Get all enum values as an array.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get human-readable label in Bahasa Indonesia.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Disetujui',
            self::WaitingWaConfirmation => 'Menunggu Chat WA',
            self::WaitingNewbimmaCheck => 'Cek Newbimma',
            self::Confirmed => 'Terkonfirmasi',
            self::Rejected => 'Ditolak',
            self::Waitlist => 'Cadangan',
        };
    }

    /**
     * Find the enum case from a given string value.
     *
     * @param string $value
     * @return self|null
     */
    public static function fromValue(string $value): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Get CSS badge class for UI.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'badge-premium badge-premium-warning',
            self::Approved => 'badge-premium badge-premium-success',
            self::WaitingWaConfirmation => 'badge-wa-warning',
            self::WaitingNewbimmaCheck => 'badge-newbimma-info',
            self::Confirmed => 'badge-confirmed-success',
            self::Rejected => 'badge-premium badge-premium-danger',
            self::Waitlist => 'badge-premium badge-premium-info',
        };
    }
}

<?php

namespace App\Services;

use App\Models\Enrollment;
use Illuminate\Support\Str;

class VerificationCodeService
{
    /**
     * Generate kode verifikasi unik untuk enrollment.
     * Format: PTHK-{id_enrollment}-{6 karakter random uppercase}
     * Karakter non-ambigu: tanpa 0/O, 1/I/L
     */
    public static function generate(Enrollment $enrollment): string
    {
        $random = '';
        $characters = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        for ($i = 0; $i < 6; $i++) {
            $random .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return "PTHK-{$enrollment->id}-{$random}";
    }

    /**
     * Cek apakah kode masih berlaku (belum expired)
     */
    public static function isValid(Enrollment $enrollment): bool
    {
        if (!$enrollment->verification_code_expires_at) {
            return false;
        }
        return now()->lessThan($enrollment->verification_code_expires_at);
    }
}

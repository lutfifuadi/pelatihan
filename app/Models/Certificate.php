<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'enrollment_id',
        'certificate_number',
        'issued_at',
        'file_path',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * Generate nomor sertifikat unik.
     * Format: SERTIFIKAT/{tahun}/{bulan}/{random}
     */
    public static function generateNumber(): string
    {
        $prefix = 'SERTIFIKAT/' . date('Y') . '/' . date('m') . '/';
        do {
            $number = $prefix . Str::upper(Str::random(8));
        } while (static::where('certificate_number', $number)->exists());

        return $number;
    }
}

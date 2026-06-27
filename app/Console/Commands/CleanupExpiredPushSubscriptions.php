<?php

namespace App\Console\Commands;

use App\Models\PushSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPushSubscriptions extends Command
{
    protected $signature = 'push:cleanup-expired';

    protected $description = 'Hapus permanent subscription yang sudah expired lebih dari 30 hari';

    public function handle(): int
    {
        $cutoff = Carbon::now()->subDays(30);

        $query = PushSubscription::whereNotNull('expired_at')
            ->where('expired_at', '<', $cutoff);

        $count = $query->count();

        if ($count === 0) {
            $this->info('[Push Cleanup] Tidak ada subscription expired yang perlu dibersihkan.');
            Log::info('[Push Cleanup] Tidak ada subscription expired yang perlu dibersihkan.');

            return self::SUCCESS;
        }

        $this->info("[Push Cleanup] Membersihkan {$count} subscription expired...");
        Log::info("[Push Cleanup] Membersihkan {$count} subscription expired...", [
            'cutoff_date' => $cutoff->toDateTimeString(),
        ]);

        // Recipient records akan terhapus otomatis via foreign key cascade (onDelete('cascade'))
        $query->forceDelete();

        $this->info("[Push Cleanup] Berhasil membersihkan {$count} subscription expired.");
        Log::info("[Push Cleanup] Berhasil membersihkan {$count} subscription expired.", [
            'deleted_count' => $count,
        ]);

        return self::SUCCESS;
    }
}

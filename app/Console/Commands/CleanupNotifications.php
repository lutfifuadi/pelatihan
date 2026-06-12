<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupNotifications extends Command
{
    protected $signature = 'notifications:cleanup {days=30 : Hapus notifikasi lebih dari N hari} {--force : Skip konfirmasi}';

    protected $description = 'Hapus notifikasi lama untuk menjaga performa database';

    public function handle(): int
    {
        $days = (int) $this->argument('days');
        $force = $this->option('force');
        $cutoff = Carbon::now()->subDays($days);

        $query = Notification::whereIn('status', ['sent', 'failed'])
            ->where('created_at', '<', $cutoff);

        $count = $query->count();

        if ($count === 0) {
            $this->info("Tidak ada notifikasi lama yang perlu dihapus.");
            return self::SUCCESS;
        }

        $this->warn("Akan menghapus {$count} notifikasi dengan status sent/failed lebih dari {$days} hari.");

        if (!$force && !$this->confirm('Lanjutkan penghapusan?')) {
            $this->info('Dibatalkan.');
            return self::SUCCESS;
        }

        $query->delete();

        $this->info("Berhasil menghapus {$count} notifikasi lama.");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanOldBackups extends Command
{
    /**
     * Nama command untuk Artisan.
     *
     * @var string
     */
    protected $signature = 'backup:clean
                            {--keep=30 : Jumlah hari backup disimpan (default: 30)}
                            {--path= : Folder backup (default: storage/app/backups)}';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Membersihkan file backup database yang sudah lebih dari N hari';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $keepDays = (int) $this->option('keep');
        $backupPath = $this->option('path') ?: storage_path('app/backups');

        if (!is_dir($backupPath)) {
            $this->warn("Folder backup tidak ditemukan: {$backupPath}");
            Log::channel('backup')->warning('Cleanup: folder backup tidak ditemukan', [
                'path' => $backupPath,
            ]);
            return Command::SUCCESS;
        }

        $this->info("Membersihkan backup lebih dari {$keepDays} hari...");
        $this->line("  Folder: {$backupPath}");

        $cutoff = now()->subDays($keepDays)->timestamp;
        $pattern = rtrim($backupPath, '/\\') . DIRECTORY_SEPARATOR . 'backup_*.sql*';

        $files = glob($pattern);
        $deletedCount = 0;
        $totalSize = 0;

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                $totalSize += filesize($file);
                unlink($file);
                $deletedCount++;
                $this->line("  Hapus: " . basename($file));
            }
        }

        if ($deletedCount > 0) {
            $freedSpace = $this->formatSize($totalSize);
            $this->info("Berhasil menghapus {$deletedCount} file backup ({$freedSpace}).");

            Log::channel('backup')->info("Cleanup: dihapus {$deletedCount} file backup", [
                'keep_days'  => $keepDays,
                'total_size' => $totalSize,
                'freed'      => $freedSpace,
                'path'       => $backupPath,
            ]);
        } else {
            $this->info("Tidak ada file backup yang perlu dibersihkan.");

            Log::channel('backup')->info('Cleanup: tidak ada file yang perlu dibersihkan', [
                'keep_days' => $keepDays,
                'path'      => $backupPath,
            ]);
        }

        return Command::SUCCESS;
    }

    /**
     * Format ukuran bytes ke human-readable.
     */
    private function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

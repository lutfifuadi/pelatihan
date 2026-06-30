<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NullifyDuplicateWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     * Gunakan --execute untuk benar-benar menjalankan update.
     * Tanpa flag ini, command hanya menampilkan preview (dry-run).
     *
     * @var string
     */
    protected $signature = 'db:nullify-duplicate-whatsapp
                            {--execute : Jalankan update sesungguhnya (tanpa flag ini = dry-run)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Null-kan nomor WhatsApp duplikat di tabel users. User dengan ID terkecil (pendaftar pertama) dipertahankan; sisanya di-null-kan.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = ! $this->option('execute');

        $this->newLine();
        if ($isDryRun) {
            $this->warn('═══════════════════════════════════════════════════════════');
            $this->warn('  MODE: DRY-RUN — Tidak ada data yang diubah              ');
            $this->warn('  Tambahkan --execute untuk menjalankan update             ');
            $this->warn('═══════════════════════════════════════════════════════════');
        } else {
            $this->error('═══════════════════════════════════════════════════════════');
            $this->error('  MODE: EXECUTE — Data AKAN diubah secara permanen!       ');
            $this->error('═══════════════════════════════════════════════════════════');

            if (! $this->confirm('Apakah Anda yakin ingin melanjutkan?', false)) {
                $this->info('Dibatalkan oleh user.');
                return self::FAILURE;
            }
        }

        $this->newLine();
        $this->info('📋 Mencari nomor WhatsApp duplikat...');
        $this->newLine();

        // Temukan semua nomor WA yang dipakai lebih dari 1 user
        $duplicateNumbers = DB::select("
            SELECT whatsapp, COUNT(*) as total
            FROM users
            WHERE whatsapp IS NOT NULL AND whatsapp != ''
            GROUP BY whatsapp
            HAVING COUNT(*) > 1
            ORDER BY whatsapp
        ");

        if (empty($duplicateNumbers)) {
            $this->info('✅ Tidak ditemukan nomor WhatsApp duplikat. Database sudah bersih!');
            return self::SUCCESS;
        }

        $this->line(sprintf(
            'Ditemukan <comment>%d nomor WA duplikat</comment> yang perlu ditangani.',
            count($duplicateNumbers)
        ));
        $this->newLine();

        // Kumpulkan semua user yang akan di-null-kan
        $usersToNullify = [];

        foreach ($duplicateNumbers as $dup) {
            $waNumber = $dup->whatsapp;
            $total    = $dup->total;

            // Ambil semua user dengan nomor ini, urutkan: ID terkecil (pendaftar pertama) duluan
            $users = DB::select("
                SELECT id, name, email, whatsapp, created_at
                FROM users
                WHERE whatsapp = ?
                ORDER BY id ASC
            ", [$waNumber]);

            $this->line("📱 <comment>{$waNumber}</comment> ({$total} user):");

            foreach ($users as $index => $user) {
                if ($index === 0) {
                    // User pertama (ID terkecil) → DIPERTAHANKAN
                    $this->line(sprintf(
                        "   <info>✅ DIPERTAHANKAN</info> — ID: %-5d | %s | %s | Daftar: %s",
                        $user->id,
                        str_pad($user->name, 30),
                        str_pad($user->email, 35),
                        $user->created_at
                    ));
                } else {
                    // User berikutnya → AKAN DI-NULL-KAN
                    $this->line(sprintf(
                        "   <fg=red>❌ AKAN DI-NULL  </> — ID: %-5d | %s | %s | Daftar: %s",
                        $user->id,
                        str_pad($user->name, 30),
                        str_pad($user->email, 35),
                        $user->created_at
                    ));
                    $usersToNullify[] = $user->id;
                }
            }
            $this->newLine();
        }

        // Ringkasan
        $this->line('─────────────────────────────────────────────────────────────');
        $this->line(sprintf(
            'Total user yang akan di-null-kan WA-nya: <comment>%d user</comment>',
            count($usersToNullify)
        ));
        $this->line('ID user: ' . implode(', ', $usersToNullify));
        $this->line('─────────────────────────────────────────────────────────────');
        $this->newLine();

        if ($isDryRun) {
            $this->warn('Ini adalah DRY-RUN. Jalankan dengan flag --execute untuk eksekusi sesungguhnya:');
            $this->line('  php artisan db:nullify-duplicate-whatsapp --execute');
            $this->newLine();
            return self::SUCCESS;
        }

        // === EKSEKUSI UPDATE ===
        $this->info('🔄 Menjalankan update...');

        $updatedCount = 0;
        DB::transaction(function () use ($usersToNullify, &$updatedCount) {
            foreach (array_chunk($usersToNullify, 50) as $chunk) {
                $updatedCount += DB::table('users')
                    ->whereIn('id', $chunk)
                    ->update(['whatsapp' => null]);
            }
        });

        $this->newLine();
        $this->info("✅ Berhasil! {$updatedCount} user telah di-null-kan nomor WhatsApp-nya.");
        $this->newLine();

        // Verifikasi ulang — pastikan tidak ada duplikat tersisa
        $remaining = DB::select("
            SELECT whatsapp, COUNT(*) as total
            FROM users
            WHERE whatsapp IS NOT NULL AND whatsapp != ''
            GROUP BY whatsapp
            HAVING COUNT(*) > 1
        ");

        if (empty($remaining)) {
            $this->info('✅ Verifikasi: Tidak ada duplikat WhatsApp tersisa. Database siap untuk unique index!');
        } else {
            $this->error('⚠️  Masih ada ' . count($remaining) . ' duplikat tersisa. Selidiki lebih lanjut!');
            return self::FAILURE;
        }

        $this->newLine();
        return self::SUCCESS;
    }
}

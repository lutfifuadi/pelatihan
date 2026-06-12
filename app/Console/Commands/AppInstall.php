<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class AppInstall extends Command
{
    protected $signature = 'app:install
        {--db-host=127.0.0.1 : Database host}
        {--db-port=3306 : Database port}
        {--db-name= : Database name}
        {--db-user=root : Database username}
        {--db-pass= : Database password}
        {--admin-name=Admin : Admin name}
        {--admin-email=admin@pelatihan.test : Admin email}
        {--admin-password=password : Admin password}
        {--app-name=Aplikasi Pelatihan : Application name}
        {--force : Skip confirmation prompts}
        {--fresh : Run migrate:fresh (WARNING: will delete all data)}';

    protected $description = 'Install Aplikasi Pelatihan dari CLI (headless)';

    public function handle(): int
    {
        $force = $this->option('force');
        $fresh = $this->option('fresh');

        $this->info('==========================================');
        $this->info('  Instalasi Aplikasi Pelatihan — CLI');
        $this->info('==========================================');
        $this->newLine();

        // ============================================================
        // 1. Validasi environment
        // ============================================================
        $this->info('[1/5] Validasi environment...');

        if (!file_exists(base_path('.env'))) {
            $this->error('File .env tidak ditemukan! Jalankan "cp .env.example .env" dulu.');
            return self::FAILURE;
        }

        if (empty(config('app.key'))) {
            $this->info('  → APP_KEY belum ada, generating...');
            Artisan::call('key:generate', ['--force' => true]);
            $this->info('  → APP_KEY berhasil digenerate.');
        }

        $this->info('  Environment OK.');
        $this->newLine();

        // ============================================================
        // 2. Konfigurasi database
        // ============================================================
        $this->info('[2/5] Konfigurasi database...');

        $dbHost = $this->option('db-host');
        $dbPort = $this->option('db-port');
        $dbName = $this->option('db-name');
        $dbUser = $this->option('db-user');
        $dbPass = $this->option('db-pass');

        // Jika tidak di-set via option, tanya interaktif
        if (empty($dbName) || !$force) {
            $dbHost = $this->ask('DB Host', $dbHost ?: '127.0.0.1');
            $dbPort = $this->ask('DB Port', $dbPort ?: '3306');
            $dbName = $this->ask('DB Name', $dbName ?: 'pelatihanku');
            $dbUser = $this->ask('DB User', $dbUser ?: 'root');
            $dbPass = $this->secret('DB Password (kosongkan jika tidak ada)') ?: '';
        }

        // Test koneksi database
        $this->info('  → Mengecek koneksi database...');
        try {
            $pdo = new \PDO(
                "mysql:host={$dbHost};port={$dbPort}",
                $dbUser,
                $dbPass,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_TIMEOUT => 5]
            );
            $this->info('  ✅ Koneksi database berhasil!');
        } catch (\PDOException $e) {
            $this->error('  ❌ Koneksi database gagal: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Buat database jika belum ada
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->info("  ✅ Database '{$dbName}' siap.");

        // Tulis ke .env
        $this->writeEnv([
            'DB_CONNECTION' => 'mysql',
            'DB_HOST' => $dbHost,
            'DB_PORT' => $dbPort,
            'DB_DATABASE' => $dbName,
            'DB_USERNAME' => $dbUser,
            'DB_PASSWORD' => $dbPass,
        ]);

        Artisan::call('config:clear');
        $this->info('  ✅ Konfigurasi database ditulis ke .env');
        $this->newLine();

        // ============================================================
        // 3. Migrasi database
        // ============================================================
        $this->info('[3/5] Migrasi database...');

        if ($fresh) {
            $warnMsg = '⚠️  Mode FRESH: SEMUA DATA LAMA AKAN DIHAPUS!';
            if (!$force) {
                $this->warn($warnMsg);
                if (!$this->confirm('Lanjutkan migrate:fresh? (y/N)', false)) {
                    $this->info('Dibatalkan.');
                    return self::SUCCESS;
                }
            } else {
                $this->warn($warnMsg);
            }
            Artisan::call('migrate:fresh', ['--force' => true, '--seed' => false]);
        } else {
            Artisan::call('migrate', ['--force' => true]);
        }

        $this->info('  ✅ Migrasi selesai.');
        $this->newLine();

        // ============================================================
        // 4. Seeder & Admin
        // ============================================================
        $this->info('[4/5] Seeder & data awal...');

        // Jalankan seeder
        Artisan::call('db:seed', ['--force' => true]);
        $this->info('  ✅ Database seeded.');

        // Update .env: session/cache/queue ke database
        $this->writeEnv([
            'SESSION_DRIVER' => 'database',
            'CACHE_STORE' => 'database',
            'QUEUE_CONNECTION' => 'database',
        ]);

        // Buat atau update admin
        $adminName = $this->option('admin-name');
        $adminEmail = $this->option('admin-email');
        $adminPassword = $this->option('admin-password');

        if (!$force) {
            $adminName = $this->ask('Nama Admin', $adminName ?: 'Admin');
            $adminEmail = $this->ask('Email Admin', $adminEmail ?: 'admin@pelatihan.test');
            $adminPassword = $this->ask('Password Admin', $adminPassword ?: 'password');
        }

        $admin = User::where('email', $adminEmail)->where('role', 'admin')->first();
        if ($admin) {
            $admin->update([
                'name' => $adminName,
                'password' => Hash::make($adminPassword),
            ]);
            $this->info("  ✅ Admin '{$adminEmail}' sudah ada, password diperbarui.");
        } else {
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]);
            $this->info("  ✅ Admin '{$adminEmail}' berhasil dibuat.");
        }

        $this->newLine();

        // ============================================================
        // 5. Finalisasi
        // ============================================================
        $this->info('[5/5] Finalisasi...');

        // Storage link
        if (!file_exists(public_path('storage'))) {
            Artisan::call('storage:link');
            $this->info('  ✅ Storage link dibuat.');
        } else {
            $this->info('  Storage link sudah ada.');
        }

        // Optimasi cache
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        $this->info('  ✅ Cache dioptimasi.');

        // Tandai sudah terinstall
        file_put_contents(storage_path('installed'), 'installed on ' . date('Y-m-d H:i:s'));
        $this->info('  ✅ File marker installed dibuat.');

        $this->newLine();
        $this->info('==========================================');
        $this->info('  ✅ INSTALASI SELESAI!');
        $this->info('==========================================');
        $this->info("  Admin email: {$adminEmail}");
        $this->info("  Admin password: (sesuai yang diinput)");
        $this->info('==========================================');

        return self::SUCCESS;
    }

    /**
     * Write key-value pairs to .env file.
     */
    private function writeEnv(array $data): void
    {
        $path = base_path('.env');
        $env = file_get_contents($path);

        foreach ($data as $key => $value) {
            $envValue = (preg_match('/[\s"\'#]/', (string) $value))
                ? '"' . addcslashes((string) $value, '"\\') . '"'
                : (string) $value;

            $pattern = "/^#?\s*{$key}=.*$/m";

            if (preg_match($pattern, $env)) {
                $env = preg_replace_callback($pattern, fn() => "{$key}={$envValue}", $env);
            } else {
                $env .= "\n{$key}={$envValue}";
            }
        }

        file_put_contents($path, $env);
    }
}

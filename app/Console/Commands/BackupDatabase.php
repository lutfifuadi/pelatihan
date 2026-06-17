<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    /**
     * Nama command untuk Artisan.
     *
     * @var string
     */
    protected $signature = 'backup:database
                            {--output= : Folder tujuan backup (default: storage/app/backups)}
                            {--compress : Kompres file SQL dengan gzip}
                            {--keep=30 : Jumlah hari backup disimpan (default: 30)}';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Melakukan backup database ke file SQL menggunakan mysqldump';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $outputPath = $this->option('output') ?: storage_path('app/backups');
        $compress = (bool) $this->option('compress');
        $keepDays = (int) $this->option('keep');

        // --------------------------------------------------------------------
        //  1. Pastikan folder output ada
        // --------------------------------------------------------------------
        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0755, true);
            $this->line("Folder backup dibuat: {$outputPath}");
        }

        // --------------------------------------------------------------------
        //  2. Baca konfigurasi database
        // --------------------------------------------------------------------
        $connection = config('database.default');
        $dbConfig = config("database.connections.{$connection}");

        if (!$dbConfig) {
            $this->error("Database connection '{$connection}' tidak ditemukan di config/database.php");
            Log::channel('backup')->error("Backup gagal: koneksi '{$connection}' tidak dikonfigurasi.");
            return Command::FAILURE;
        }

        $driver = $dbConfig['driver'] ?? 'mysql';
        $dbName = $dbConfig['database'] ?? 'unknown';

        if (!in_array($driver, ['mysql', 'mariadb'])) {
            $this->warn("Driver database '{$driver}' terdeteksi. Command ini dioptimalkan untuk MySQL/MariaDB.");
        }

        // --------------------------------------------------------------------
        //  3. Cari mysqldump
        // --------------------------------------------------------------------
        $mysqldumpPath = $this->findMysqldump();

        if (!$mysqldumpPath) {
            $this->warn('mysqldump tidak ditemukan di PATH atau lokasi umum.');
            return $this->backupWithFallback($dbConfig, $outputPath, $compress, $keepDays);
        }

        $this->line("mysqldump ditemukan di: {$mysqldumpPath}");

        // --------------------------------------------------------------------
        //  4. Siapkan nama file
        // --------------------------------------------------------------------
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $filePath = rtrim($outputPath, '/\\') . DIRECTORY_SEPARATOR . $filename;

        $this->info("Memulai backup database '{$dbName}'...");
        $this->line("  File tujuan: {$filePath}");

        // --------------------------------------------------------------------
        //  5. Eksekusi mysqldump
        // --------------------------------------------------------------------
        try {
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $port = $dbConfig['port'] ?? '3306';
            $user = $dbConfig['username'] ?? 'root';
            $password = $dbConfig['password'] ?? '';

            // Gunakan --set-gtid-purged=OFF untuk kompatibilitas, --single-transaction untuk konsistensi
            $command = sprintf(
                '"%s" --user=%s --host=%s --port=%s --routines --events --triggers --single-transaction --opt --set-gtid-purged=OFF --column-statistics=0 %s',
                $mysqldumpPath,
                escapeshellarg($user),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($dbName)
            );

            $this->line("  Menjalankan: mysqldump --user={$user} --host={$host} --port={$port} [args] {$dbName}");
            $this->line('  Proses backup...');

            $descriptorspec = [
                0 => ['pipe', 'r'],  // stdin
                1 => ['pipe', 'w'],  // stdout (data SQL)
                2 => ['pipe', 'w'],  // stderr (error)
            ];

            // Gunakan MYSQL_PWD agar password tidak terlihat di process list
            $env = ['MYSQL_PWD' => $password];
            $process = proc_open($command, $descriptorspec, $pipes, null, $env);

            if (!is_resource($process)) {
                throw new \Exception('Gagal memulai proses mysqldump.');
            }

            fclose($pipes[0]);

            $sqlOutput = stream_get_contents($pipes[1]);
            $errorOutput = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnCode = proc_close($process);

            if ($returnCode !== 0) {
                $errorMsg = trim($errorOutput) ?: "Kode error: {$returnCode}";
                throw new \Exception("mysqldump gagal: {$errorMsg}");
            }

            if (empty(trim($sqlOutput))) {
                throw new \Exception('mysqldump menghasilkan output kosong. Periksa koneksi database.');
            }

            // Tulis ke file
            file_put_contents($filePath, $sqlOutput);

            $rawSize = filesize($filePath);
            $this->info("  Backup berhasil! ({$this->formatSize($rawSize)})");

            Log::channel('backup')->info('Backup database berhasil', [
                'database' => $dbName,
                'file'     => $filePath,
                'size'     => $rawSize,
            ]);

            // --------------------------------------------------------------------
            //  6. Kompres (jika diminta)
            // --------------------------------------------------------------------
            if ($compress) {
                $compressedPath = $filePath . '.gz';
                $gzData = gzencode(file_get_contents($filePath), 9);
                file_put_contents($compressedPath, $gzData);
                unlink($filePath);

                $compressedSize = filesize($compressedPath);
                $this->info("  Backup dikompres: {$this->formatSize($compressedSize)} (dari {$this->formatSize($rawSize)})");

                Log::channel('backup')->info('Backup dikompres dengan gzip', [
                    'file'          => $compressedPath,
                    'size_original' => $rawSize,
                    'size_compressed' => $compressedSize,
                ]);
            }

            // --------------------------------------------------------------------
            //  7. Hapus backup lama
            // --------------------------------------------------------------------
            $this->cleanOldBackups($outputPath, $keepDays);

            $this->info('Backup database selesai!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup gagal: {$e->getMessage()}");

            Log::channel('backup')->error('Backup database gagal', [
                'database' => $dbName,
                'error'    => $e->getMessage(),
            ]);

            return Command::FAILURE;
        }
    }

    // ------------------------------------------------------------------------
    //  METHODS BANTUAN
    // ------------------------------------------------------------------------

    /**
     * Mencari lokasi binary mysqldump di sistem.
     */
    private function findMysqldump(): ?string
    {
        // Coba deteksi via PATH terlebih dahulu
        $binaryName = PHP_OS_FAMILY === 'Windows' ? 'mysqldump.exe' : 'mysqldump';

        if (PHP_OS_FAMILY === 'Windows') {
            $output = [];
            $returnCode = 0;
            exec("where {$binaryName} 2>nul", $output, $returnCode);
            if ($returnCode === 0 && !empty($output[0])) {
                return trim($output[0]);
            }
        } else {
            $output = [];
            $returnCode = 0;
            exec("which {$binaryName} 2>/dev/null", $output, $returnCode);
            if ($returnCode === 0 && !empty($output[0])) {
                return trim($output[0]);
            }
        }

        // Fallback: lokasi umum
        $commonPaths = [
            // Windows - XAMPP
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            // Windows - MySQL
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.6\\bin\\mysqldump.exe',
            // Windows - MariaDB
            'C:\\Program Files\\MariaDB 10.11\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.10\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.9\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.6\\bin\\mysqldump.exe',
            'C:\\Program Files\\MariaDB 10.5\\bin\\mysqldump.exe',
            // Windows - Laragon
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            // Linux
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            // macOS (Homebrew)
            '/usr/local/opt/mysql-client/bin/mysqldump',
            '/opt/homebrew/opt/mysql-client/bin/mysqldump',
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Metode fallback jika mysqldump tidak tersedia.
     * Export database menggunakan PHP PDO langsung (tanpa mysqldump).
     */
    private function backupWithFallback(array $dbConfig, string $outputPath, bool $compress, int $keepDays): int
    {
        $this->warn('mysqldump tidak ditemukan. Menggunakan metode PHP PDO sebagai fallback...');
        $this->line('  Catatan: Metode ini lebih lambat untuk database besar.');
        $this->line('  Install mysqldump untuk performa terbaik.');

        Log::channel('backup')->warning('mysqldump tidak ditemukan, menggunakan fallback PHP PDO');

        $dbName = $dbConfig['database'];
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$dbName}_{$timestamp}.sql";
        $filePath = rtrim($outputPath, '/\\') . DIRECTORY_SEPARATOR . $filename;

        try {
            // --------------------------------------------------------------------
            //  Koneksi PDO langsung ke database
            // --------------------------------------------------------------------
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $port = $dbConfig['port'] ?? '3306';
            $user = $dbConfig['username'] ?? 'root';
            $password = $dbConfig['password'] ?? '';
            $charset = $dbConfig['charset'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset={$charset}";
            $pdo = new \PDO($dsn, $user, $password, [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_TIMEOUT            => 300,
            ]);

            // --------------------------------------------------------------------
            //  Header SQL
            // --------------------------------------------------------------------
            $sql = "-- ============================================================\n";
            $sql .= "-- Backup Database: {$dbName}\n";
            $sql .= "-- Tanggal: " . now()->format('Y-m-d H:i:s') . "\n";
            $sql .= "-- Metode: PHP PDO Fallback\n";
            $sql .= "-- ============================================================\n\n";
            $sql .= "SET NAMES {$charset};\n";
            $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
            $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

            // --------------------------------------------------------------------
            //  Dapatkan daftar tabel
            // --------------------------------------------------------------------
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            if (empty($tables)) {
                throw new \Exception('Tidak ada tabel yang ditemukan di database.');
            }

            $this->line("  Ditemukan " . count($tables) . " tabel. Mulai export...");
            $bar = $this->output->createProgressBar(count($tables));
            $bar->start();

            foreach ($tables as $table) {
                // CREATE TABLE
                $stmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $row = $stmt->fetch(\PDO::FETCH_NUM);
                $createTableSql = $row[1] ?? '';

                $sql .= "-- --------------------------------------------\n";
                $sql .= "-- Table: {$table}\n";
                $sql .= "-- --------------------------------------------\n";
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= "{$createTableSql};\n\n";

                // INSERT DATA
                $stmtCount = $pdo->query("SELECT COUNT(*) FROM `{$table}`");
                $totalRows = (int) $stmtCount->fetchColumn();

                if ($totalRows > 0) {
                    $sql .= "-- Data: {$totalRows} rows\n";

                    // Export per chunk untuk menghindari memory overflow
                    $chunkSize = 500;
                    $offset = 0;

                    $columnsStmt = $pdo->query("SHOW COLUMNS FROM `{$table}`");
                    $columns = $columnsStmt->fetchAll(\PDO::FETCH_COLUMN);
                    $columnList = '`' . implode('`, `', $columns) . '`';

                    while ($offset < $totalRows) {
                        $dataStmt = $pdo->query("SELECT * FROM `{$table}` LIMIT {$chunkSize} OFFSET {$offset}");
                        $rows = $dataStmt->fetchAll(\PDO::FETCH_ASSOC);

                        if (!empty($rows)) {
                            $sql .= "INSERT INTO `{$table}` ({$columnList}) VALUES\n";
                            $valueLines = [];
                            foreach ($rows as $row) {
                                $escapedValues = [];
                                foreach ($row as $value) {
                                    if ($value === null) {
                                        $escapedValues[] = 'NULL';
                                    } elseif (is_numeric($value)) {
                                        $escapedValues[] = $value;
                                    } else {
                                        $escapedValues[] = "'" . addslashes($value) . "'";
                                    }
                                }
                                $valueLines[] = '(' . implode(', ', $escapedValues) . ')';
                            }
                            $sql .= implode(",\n", $valueLines) . ";\n\n";
                        }

                        $offset += $chunkSize;
                    }
                }

                $bar->advance();
            }

            $bar->finish();
            $this->line('');

            // Footer
            $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
            $sql .= "-- Backup selesai: " . now()->format('Y-m-d H:i:s') . "\n";

            // --------------------------------------------------------------------
            //  Tulis ke file
            // --------------------------------------------------------------------
            file_put_contents($filePath, $sql);

            $rawSize = filesize($filePath);
            $this->info("Backup berhasil (PHP fallback): {$filePath} ({$this->formatSize($rawSize)})");

            Log::channel('backup')->info('Backup database berhasil (PHP fallback)', [
                'database' => $dbName,
                'file'     => $filePath,
                'size'     => $rawSize,
                'tables'   => count($tables),
            ]);

            // --------------------------------------------------------------------
            //  Kompres jika diminta
            // --------------------------------------------------------------------
            if ($compress) {
                $compressedPath = $filePath . '.gz';
                $gzData = gzencode(file_get_contents($filePath), 9);
                file_put_contents($compressedPath, $gzData);
                unlink($filePath);

                $compressedSize = filesize($compressedPath);
                $this->info("Backup dikompres: {$this->formatSize($compressedSize)}");
            }

            // --------------------------------------------------------------------
            //  Bersihkan backup lama
            // --------------------------------------------------------------------
            $this->cleanOldBackups($outputPath, $keepDays);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Backup gagal (PHP fallback): {$e->getMessage()}");
            $this->line('');
            $this->warn('=== PENTING: INSTALL MYSQLDUMP UNTUK BACKUP PRODUCTION ===');
            $this->line('  Metode PHP PDO fallback gagal. Backup hanya bisa dilakukan dengan mysqldump.');
            $this->line('');
            $this->line('  Cara install MySQL client:');
            $this->line('  - Linux (Ubuntu/Debian): sudo apt install mysql-client');
            $this->line('  - Linux (RHEL/CentOS):   sudo yum install mysql');
            $this->line('  - Windows: Tambahkan PATH ke folder bin MySQL/MariaDB');
            $this->line('    Contoh: C:\\xampp\\mysql\\bin atau C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin');
            $this->line('');
            $this->line('  Setelah terinstall, jalankan: php artisan backup:database');

            Log::channel('backup')->error('Backup gagal total (PHP fallback)', [
                'error'  => $e->getMessage(),
                'note'   => 'mysqldump tidak tersedia dan fallback PHP PDO gagal',
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Hapus file backup yang lebih tua dari N hari.
     */
    private function cleanOldBackups(string $outputPath, int $keepDays): void
    {
        $cutoff = now()->subDays($keepDays)->timestamp;
        $pattern = rtrim($outputPath, '/\\') . DIRECTORY_SEPARATOR . 'backup_*.sql*';

        $files = glob($pattern);
        $deletedCount = 0;

        foreach ($files as $file) {
            if (is_file($file) && filemtime($file) < $cutoff) {
                unlink($file);
                $deletedCount++;
                $this->line("  Hapus backup lama: " . basename($file));
            }
        }

        if ($deletedCount > 0) {
            $this->info("  {$deletedCount} file backup lama berhasil dihapus.");
            Log::channel('backup')->info("Cleanup: {$deletedCount} file backup lama dihapus", [
                'path'      => $outputPath,
                'keep_days' => $keepDays,
            ]);
        } else {
            $this->line('  Tidak ada file backup lama yang perlu dihapus.');
        }
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

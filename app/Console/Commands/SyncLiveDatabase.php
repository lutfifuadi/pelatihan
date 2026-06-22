<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncLiveDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-live {--target=local : Target database to overwrite (local or dev)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync and overwrite local or dev database from live production database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $target = $this->option('target');
        
        // Define live DB connection configuration
        $liveConfig = [
            'host' => '103.197.191.226',
            'port' => '3306',
            'database' => 'pelatihanku',
            'username' => 'pelatihanku',
            'password' => 'Di6YNZsZnHCmXfMD',
        ];

        // Define target DB connection details based on option
        if ($target === 'local') {
            $targetConfig = [
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'pelatihanku',
                'username' => 'root',
                'password' => '',
            ];
        } elseif ($target === 'dev') {
            $targetConfig = [
                'host' => '103.197.191.226',
                'port' => '3306',
                'database' => 'dev_pelatihanku',
                'username' => 'dev_pelatihanku',
                'password' => 'HyGBmRJKrbJXh8Hn',
            ];
        } else {
            $this->error("Target invalid! Hanya 'local' atau 'dev' yang didukung.");
            return 1;
        }

        $this->info("=== SINKRONISASI DATABASE ===");
        $this->info("Sumber (LIVE)  : {$liveConfig['database']}@{$liveConfig['host']}");
        $this->info("Tujuan (TARGET): {$targetConfig['database']}@{$targetConfig['host']}");
        
        if (!$this->confirm("Apakah Anda yakin ingin MENG-OVERWRITE database tujuan? Seluruh data di tujuan akan hilang!", false)) {
            $this->info("Sinkronisasi dibatalkan.");
            return 0;
        }

        $this->info("Menghubungkan ke database LIVE...");

        // Setup temporary connection configurations dynamically
        config(['database.connections.live_sync_temp' => [
            'driver' => 'mariadb',
            'host' => $liveConfig['host'],
            'port' => $liveConfig['port'],
            'database' => $liveConfig['database'],
            'username' => $liveConfig['username'],
            'password' => $liveConfig['password'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]]);

        config(['database.connections.target_sync_temp' => [
            'driver' => 'mariadb',
            'host' => $targetConfig['host'],
            'port' => $targetConfig['port'],
            'database' => $targetConfig['database'],
            'username' => $targetConfig['username'],
            'password' => $targetConfig['password'],
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]]);

        try {
            $liveConn = DB::connection('live_sync_temp');
            $targetConn = DB::connection('target_sync_temp');
            
            // Test connections
            $liveConn->getPdo();
            $this->info("Koneksi ke LIVE berhasil.");
            
            $targetConn->getPdo();
            $this->info("Koneksi ke TARGET berhasil.");

            // Get tables from Live
            $tablesResult = $liveConn->select('SHOW TABLES');
            $dbNameKey = "Tables_in_{$liveConfig['database']}";
            
            $tables = [];
            foreach ($tablesResult as $row) {
                $tables[] = $row->{$dbNameKey};
            }

            if (empty($tables)) {
                $this->error("Database LIVE tidak memiliki tabel.");
                return 1;
            }

            $this->info("Ditemukan " . count($tables) . " tabel di database LIVE.");

            // Disable Foreign Key checks on target database
            $targetConn->statement('SET FOREIGN_KEY_CHECKS = 0');

            foreach ($tables as $table) {
                $this->comment("Memproses tabel: {$table}...");

                // Get table creation structure
                $createTableStmt = $liveConn->select("SHOW CREATE TABLE `{$table}`");
                $createTableSql = $createTableStmt[0]->{'Create Table'};

                // Drop table on target if exists
                $targetConn->statement("DROP TABLE IF EXISTS `{$table}`");
                
                // Create table on target
                $targetConn->statement($createTableSql);

                // Fetch data from Live in chunks
                $liveConn->table($table)->orderByRaw('1')->chunk(500, function ($rows) use ($targetConn, $table) {
                    $insertData = [];
                    foreach ($rows as $row) {
                        $insertData[] = (array) $row;
                    }
                    if (!empty($insertData)) {
                        $targetConn->table($table)->insert($insertData);
                    }
                });

                $this->info("Tabel {$table} berhasil disinkronkan.");
            }

            // Enable Foreign Key checks on target database
            $targetConn->statement('SET FOREIGN_KEY_CHECKS = 1');

            $this->info("=== SINKRONISASI SELESAI ===");
            $this->info("Database {$targetConfig['database']} berhasil di-overwrite dari live database!");
            return 0;

        } catch (\Exception $e) {
            $this->error("Terjadi error saat sinkronisasi: " . $e->getMessage());
            return 1;
        }
    }
}

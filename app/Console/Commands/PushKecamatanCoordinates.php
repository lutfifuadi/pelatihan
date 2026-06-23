<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PushKecamatanCoordinates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:push-coordinates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push kecamatan coordinates (latitude & longitude) from local to DEV and LIVE databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== START SINKRONISASI KOORDINAT KECAMATAN ===");

        // 1. Ambil data dari database local (koneksi default)
        $localKecamatans = DB::table('kecamatans')
            ->select('name', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($localKecamatans->isEmpty()) {
            $this->error("Tidak ada data koordinat kecamatan yang valid di database lokal.");
            return 1;
        }

        $this->info("Ditemukan " . $localKecamatans->count() . " data kecamatan berkoordinat di lokal.");

        // 2. Definisikan server target
        $servers = [
            'DEV' => [
                'driver' => 'mariadb', // Sesuai dengan SyncLiveDatabase.php yang menggunakan mariadb driver
                'host' => '103.197.191.226',
                'port' => '3306',
                'database' => 'dev_pelatihanku',
                'username' => 'dev_pelatihanku',
                'password' => 'HyGBmRJKrbJXh8Hn',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'LIVE' => [
                'driver' => 'mariadb',
                'host' => '103.197.191.226',
                'port' => '3306',
                'database' => 'pelatihanku',
                'username' => 'pelatihanku',
                'password' => 'Di6YNZsZnHCmXfMD',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ];

        foreach ($servers as $name => $config) {
            $this->newLine();
            $this->info("--- Memproses Server {$name} ({$config['database']}) ---");

            // Setup temporary connection dynamically
            $tempConnectionName = 'temp_push_conn_' . strtolower($name);
            config(["database.connections.{$tempConnectionName}" => $config]);

            try {
                $conn = DB::connection($tempConnectionName);
                
                // Test connection
                $conn->getPdo();
                $this->info("Koneksi ke {$name} berhasil.");

                // Cek apakah tabel kecamatans ada di server remote
                $tableExists = false;
                try {
                    $conn->table('kecamatans')->limit(1)->get();
                    $tableExists = true;
                } catch (\Exception $e) {
                    $this->error("Tabel 'kecamatans' tidak ditemukan di database {$name}.");
                }

                if (!$tableExists) {
                    continue;
                }

                // Mulai update data dengan progress bar
                $bar = $this->output->createProgressBar($localKecamatans->count());
                $bar->start();

                $updatedCount = 0;
                $noChangeCount = 0;
                $notFoundCount = 0;

                foreach ($localKecamatans as $kecamatan) {
                    // Cari baris kecamatan di database remote
                    $remoteKecamatan = $conn->table('kecamatans')
                        ->whereRaw('LOWER(name) = ?', [strtolower($kecamatan->name)])
                        ->first();

                    if ($remoteKecamatan) {
                        // Cek apakah koordinatnya sama
                        if ($remoteKecamatan->latitude == $kecamatan->latitude && $remoteKecamatan->longitude == $kecamatan->longitude) {
                            $noChangeCount++;
                        } else {
                            // Update koordinat
                            $conn->table('kecamatans')
                                ->where('id', $remoteKecamatan->id)
                                ->update([
                                    'latitude' => $kecamatan->latitude,
                                    'longitude' => $kecamatan->longitude,
                                ]);
                            $updatedCount++;
                        }
                    } else {
                        $notFoundCount++;
                    }

                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
                $this->info("Hasil Update Server {$name}:");
                $this->line(" - Berhasil diperbarui: {$updatedCount} kecamatan");
                $this->line(" - Sudah sesuai (tidak ada perubahan): {$noChangeCount} kecamatan");
                $this->line(" - Tidak ditemukan berdasarkan nama: {$notFoundCount} kecamatan");

            } catch (\Exception $e) {
                $this->error("Terjadi error pada server {$name}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("=== PROSES SINKRONISASI SELESAI ===");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchKodepos extends Command
{
    protected $signature = 'fetch:kodepos
                            {--force : Fetch ulang semua kelurahan}';

    protected $description = 'Fetch kode pos untuk semua kelurahan dari API publik';

    /**
     * Normalisasi nama: uppercase, hapus spasi, hapus tanda kurung & isinya.
     */
    private function normalize(string $name): string
    {
        $name = strtoupper($name);
        // Hapus teks dalam kurung: "MALEER (MALEBER)" → "MALEER"
        $name = preg_replace('/\s*\(.*?\)\s*/', ' ', $name);
        // Hapus spasi berlebih
        $name = preg_replace('/\s+/', ' ', $name);
        // Hapus spasi di awal/akhir
        $name = trim($name);
        return $name;
    }

    /**
     * Normalisasi super untuk pencocokan: hapus spasi total.
     */
    private function normalizeStrict(string $name): string
    {
        return str_replace(' ', '', $this->normalize($name));
    }

    public function handle(): int
    {
        $force = $this->option('force');

        $query = Kelurahan::where('is_active', true)->with('kecamatan');
        if (!$force) {
            $query->whereNull('kodepos');
        }
        $kelurahans = $query->get();

        if ($kelurahans->isEmpty()) {
            $this->info('Semua kelurahan sudah punya kode pos.');
            return Command::SUCCESS;
        }

        $total = $kelurahans->count();
        $this->info("Memproses {$total} kelurahan...\n");

        $berhasil = 0;
        $tidakDitemukan = 0;
        $error = 0;
        $processed = 0;

        foreach ($kelurahans as $kel) {
            $processed++;
            $namaKel = $kel->name;
            $namaKec = $kel->kecamatan->name;
            $namaKelNorm = $this->normalize($namaKel);
            $namaKecNorm = $this->normalize($namaKec);

            try {
                // Panggil API kodepos.vercel.app
                $response = Http::timeout(10)
                    ->get('https://kodepos.vercel.app/search', [
                        'q' => $namaKel,
                    ]);

                if (!$response->successful()) {
                    $this->line("✗ {$namaKel} ({$namaKec}) → ERROR HTTP {$response->status()}");
                    $tidakDitemukan++;
                    $this->showProgress($processed, $total);
                    usleep(150_000);
                    continue;
                }

                $data = $response->json();
                $results = $data['data'] ?? $data;
                if (empty($results)) {
                    $this->line("✗ {$namaKel} ({$namaKec}) → TIDAK ADA DATA");
                    $tidakDitemukan++;
                    $this->showProgress($processed, $total);
                    usleep(150_000);
                    continue;
                }

                // Jika single object (bukan array), bungkus
                if (isset($results['code'])) {
                    $results = [$results];
                }

                $found = false;
                $namaKelStrict = $this->normalizeStrict($namaKel);
                $namaKecStrict = $this->normalizeStrict($namaKec);

                foreach ($results as $item) {
                    $district = $item['district'] ?? '';
                    $village = $item['village'] ?? '';
                    $regency = $item['regency'] ?? '';

                    // Hanya yang di Bandung
                    if (stripos($regency, 'Bandung') === false) {
                        continue;
                    }

                    $districtStrict = $this->normalizeStrict($district);
                    $villageStrict = $this->normalizeStrict($village);

                    // Coba strict match dulu
                    $kecMatch = $districtStrict === $namaKecStrict;
                    $kelMatch = $villageStrict === $namaKelStrict;

                    if ($kecMatch && $kelMatch) {
                        $code = $item['code'] ?? null;
                        if ($code) {
                            $kel->update(['kodepos' => (string) $code]);
                            $this->line("✓ {$namaKel} ({$namaKec}) → {$code}");
                            $berhasil++;
                            $found = true;
                            break;
                        }
                    }
                }

                // Fallback 1: strict match kecamatan, partial match kelurahan
                if (!$found) {
                    foreach ($results as $item) {
                        $district = $item['district'] ?? '';
                        $village = $item['village'] ?? '';
                        $regency = $item['regency'] ?? '';

                        if (stripos($regency, 'Bandung') === false) continue;

                        $districtStrict = $this->normalizeStrict($district);
                        $villageNorm = $this->normalize($village);

                        if ($districtStrict === $namaKecStrict &&
                            (str_contains($villageNorm, $namaKelNorm) || str_contains($namaKelNorm, $villageNorm))) {
                            $code = $item['code'] ?? null;
                            if ($code) {
                                $kel->update(['kodepos' => (string) $code]);
                                $this->line("~ {$namaKel} ({$namaKec}) → {$code} (partial)");
                                $berhasil++;
                                $found = true;
                                break;
                            }
                        }
                    }
                }

                // Fallback 2: strict match kecamatan saja
                if (!$found) {
                    foreach ($results as $item) {
                        $district = $item['district'] ?? '';
                        $regency = $item['regency'] ?? '';
                        $village = $item['village'] ?? '';

                        if (stripos($regency, 'Bandung') === false) continue;

                        $districtStrict = $this->normalizeStrict($district);
                        $villageNorm = $this->normalize($village);

                        if ($districtStrict === $namaKecStrict) {
                            $code = $item['code'] ?? null;
                            if ($code) {
                                $kel->update(['kodepos' => (string) $code]);
                                $this->line("~ {$namaKel} ({$namaKec}) → {$code} (kec-only)");
                                $berhasil++;
                                $found = true;
                                break;
                            }
                        }
                    }
                }

                if (!$found) {
                    $this->line("✗ {$namaKel} ({$namaKec}) → TIDAK DITEMUKAN");
                    $tidakDitemukan++;
                }
            } catch (\Exception $e) {
                $this->line("✗ {$namaKel} ({$namaKec}) → ERROR: {$e->getMessage()}");
                $error++;
            }

            usleep(150_000);
            $this->showProgress($processed, $total);
        }

        $this->newLine(2);
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil', $berhasil],
                ['❌ Tidak Ditemukan', $tidakDitemukan],
                ['⚠️ Error', $error],
                ['📊 Total', $total],
            ]
        );

        return Command::SUCCESS;
    }

    private function showProgress(int $current, int $total): void
    {
        $pct = round(($current / $total) * 100);
        $bar = str_repeat('█', intdiv($pct, 5)) . str_repeat('░', 20 - intdiv($pct, 5));
        $this->output->write("\r\e[K[{$bar}] {$pct}% ({$current}/{$total})");
    }
}

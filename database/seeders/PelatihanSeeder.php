<?php

namespace Database\Seeders;

use App\Models\Dinas;
use App\Models\Pelatihan;
use Illuminate\Database\Seeder;

class PelatihanSeeder extends Seeder
{
    public function run(): void
    {
        $disparekraf = Dinas::where('singkatan', 'Disparekraf')->first();
        $disperindag = Dinas::where('singkatan', 'Disperindag')->first();
        $disdikbud = Dinas::where('singkatan', 'Disdikbud')->first();
        $disnakertrans = Dinas::where('singkatan', 'Disnakertrans')->first();
        $diskopukm = Dinas::where('singkatan', 'DiskopUKM')->first();

        $pelatihanList = [
            [
                'nama' => 'Pelatihan Kuliner',
                'batch' => 'BATCH 1',
                'deskripsi' => 'Pelatihan kuliner untuk mengembangkan keterampilan memasak dan pengelolaan usaha makanan.',
                'tanggal_mulai' => '2026-04-15',
                'tanggal_selesai' => '2026-04-16',
                'kuota' => 30,
                'is_active' => true,
                'dinas_id' => $disparekraf?->id ?? null,
                'kecamatan_ids' => [1, 2, 3, 4, 5],
            ],
            [
                'nama' => 'Pelatihan Film, Video dan Animasi',
                'batch' => 'BATCH 2',
                'deskripsi' => 'Pelatihan produksi film, video, dan animasi untuk industri kreatif.',
                'tanggal_mulai' => '2026-05-01',
                'tanggal_selesai' => '2026-05-30',
                'kuota' => 25,
                'is_active' => true,
                'dinas_id' => $disparekraf?->id ?? null,
                'kecamatan_ids' => [6, 7, 8, 9, 10],
            ],
            [
                'nama' => 'Pelatihan Kriya/Kreasi Tangan (DIY)',
                'batch' => 'BATCH 3',
                'deskripsi' => 'Pelatihan kerajinan tangan dan kreasi DIY untuk mengembangkan kreativitas.',
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'kuota' => null,
                'is_active' => true,
                'dinas_id' => $disperindag?->id ?? null,
                'kecamatan_ids' => [11, 12, 13, 14, 15],
            ],
            [
                'nama' => 'Desain Produk',
                'batch' => 'BATCH 4',
                'deskripsi' => 'Pelatihan desain produk untuk mengembangkan keterampilan perancangan produk.',
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'kuota' => null,
                'is_active' => true,
                'dinas_id' => $disperindag?->id ?? null,
                'kecamatan_ids' => [16, 17, 18, 19, 20],
            ],
            [
                'nama' => 'Pelatihan Periklanan/Marketing',
                'batch' => 'BATCH 5',
                'deskripsi' => 'Pelatihan periklanan dan marketing untuk strategi promosi dan pemasaran.',
                'tanggal_mulai' => null,
                'tanggal_selesai' => null,
                'kuota' => null,
                'is_active' => true,
                'dinas_id' => $disparekraf?->id ?? null,
                'kecamatan_ids' => [21, 22, 23, 24, 25],
            ],
        ];

        foreach ($pelatihanList as $pelatihan) {
            $kecamatanIds = $pelatihan['kecamatan_ids'] ?? [];
            unset($pelatihan['kecamatan_ids']);

            $p = Pelatihan::updateOrCreate(
                ['batch' => $pelatihan['batch']],
                $pelatihan
            );

            $p->kecamatans()->sync($kecamatanIds);
        }
    }
}

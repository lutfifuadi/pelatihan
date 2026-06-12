<?php

namespace Database\Seeders;

use App\Models\Dinas;
use Illuminate\Database\Seeder;

class DinasSeeder extends Seeder
{
    public function run(): void
    {
        $dinasList = [
            [
                'nama_dinas' => 'Dinas Tenaga Kerja dan Transmigrasi',
                'singkatan' => 'Disnakertrans',
                'is_active' => true,
            ],
            [
                'nama_dinas' => 'Dinas Pendidikan dan Kebudayaan',
                'singkatan' => 'Disdikbud',
                'is_active' => true,
            ],
            [
                'nama_dinas' => 'Dinas Perindustrian dan Perdagangan',
                'singkatan' => 'Disperindag',
                'is_active' => true,
            ],
            [
                'nama_dinas' => 'Dinas Pariwisata dan Ekonomi Kreatif',
                'singkatan' => 'Disparekraf',
                'is_active' => true,
            ],
            [
                'nama_dinas' => 'Dinas Koperasi dan UKM',
                'singkatan' => 'DiskopUKM',
                'is_active' => true,
            ],
        ];

        foreach ($dinasList as $dinas) {
            Dinas::create($dinas);
        }
    }
}

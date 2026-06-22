<?php

namespace Database\Seeders;

use App\Models\MasterOption;
use Illuminate\Database\Seeder;

class AdditionalOptionsSeeder extends Seeder
{
    /**
     * Tambah opsi baru tanpa mengganggu data existing.
     * Idempotent — aman dijalankan berulang.
     */
    public function run(): void
    {
        $newOptions = [
            // Tambahan Status Pekerjaan
            ['group_key' => 'status_pekerjaan', 'label' => 'Ibu Rumah Tangga', 'value' => 'IRT', 'order' => 10, 'is_active' => true],
            ['group_key' => 'status_pekerjaan', 'label' => 'Freelancer', 'value' => 'FREELANCER', 'order' => 11, 'is_active' => true],
            // Tambahan Pendidikan
            ['group_key' => 'pendidikan_terakhir', 'label' => 'SMK', 'value' => 'SMK', 'order' => 5, 'is_active' => true],
        ];

        foreach ($newOptions as $opt) {
            MasterOption::firstOrCreate(
                ['group_key' => $opt['group_key'], 'value' => $opt['value']],
                $opt
            );
        }

        $this->command->info('✓ Opsi tambahan (IRT, Freelancer, SMK) berhasil ditambahkan!');
    }
}

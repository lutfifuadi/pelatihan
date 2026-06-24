<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WhatsappNumberSeeder extends Seeder
{
    public function run(): void
    {
        $numbers = [
            [
                'label' => 'Pendaftaran',
                'number' => '6281234567890',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'Informasi',
                'number' => '628133334444',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'Teknis',
                'number' => '628565556666',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        DB::table('whatsapp_numbers')->insert($numbers);
    }
}

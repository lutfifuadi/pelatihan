<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 30 Kecamatan di Kota Bandung
     */
    public function run(): void
    {
        $kecamatans = [
            'Andir',
            'Antapani',
            'Arcamanik',
            'Astanaanyar',
            'Babakan Ciparay',
            'Bandung Kidul',
            'Bandung Kulon',
            'Bandung Wetan',
            'Batununggal',
            'Bojongloa Kaler',
            'Bojongloa Kidul',
            'Buahbatu',
            'Cibeunying Kaler',
            'Cibeunying Kidul',
            'Cibiru',
            'Cicendo',
            'Cidadap',
            'Cinambo',
            'Coblong',
            'Gedebage',
            'Kiaracondong',
            'Lengkong',
            'Mandalajati',
            'Panyileukan',
            'Rancasari',
            'Regol',
            'Sukajadi',
            'Sukasari',
            'Sumur Bandung',
            'Ujungberung',
        ];

        foreach ($kecamatans as $name) {
            Kecamatan::create(['name' => $name]);
        }
    }
}

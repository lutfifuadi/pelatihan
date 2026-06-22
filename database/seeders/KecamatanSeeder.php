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
            'Andir' => ['latitude' => -6.9143, 'longitude' => 107.5833],
            'Antapani' => ['latitude' => -6.9150, 'longitude' => 107.6625],
            'Arcamanik' => ['latitude' => -6.9080, 'longitude' => 107.6750],
            'Astanaanyar' => ['latitude' => -6.9322, 'longitude' => 107.6014],
            'Babakan Ciparay' => ['latitude' => -6.9450, 'longitude' => 107.5750],
            'Bandung Kidul' => ['latitude' => -6.9500, 'longitude' => 107.6333],
            'Bandung Kulon' => ['latitude' => -6.9250, 'longitude' => 107.5667],
            'Bandung Wetan' => ['latitude' => -6.9056, 'longitude' => 107.6167],
            'Batununggal' => ['latitude' => -6.9292, 'longitude' => 107.6361],
            'Bojongloa Kaler' => ['latitude' => -6.9280, 'longitude' => 107.5900],
            'Bojongloa Kidul' => ['latitude' => -6.9450, 'longitude' => 107.5950],
            'Buahbatu' => ['latitude' => -6.9550, 'longitude' => 107.6625],
            'Cibeunying Kaler' => ['latitude' => -6.8950, 'longitude' => 107.6250],
            'Cibeunying Kidul' => ['latitude' => -6.9015, 'longitude' => 107.6400],
            'Cibiru' => ['latitude' => -6.9200, 'longitude' => 107.7200],
            'Cicendo' => ['latitude' => -6.9044, 'longitude' => 107.5917],
            'Cidadap' => ['latitude' => -6.8650, 'longitude' => 107.6050],
            'Cinambo' => ['latitude' => -6.9310, 'longitude' => 107.6950],
            'Coblong' => ['latitude' => -6.8850, 'longitude' => 107.6150],
            'Gedebage' => ['latitude' => -6.9550, 'longitude' => 107.7050],
            'Kiaracondong' => ['latitude' => -6.9275, 'longitude' => 107.6475],
            'Lengkong' => ['latitude' => -6.9325, 'longitude' => 107.6225],
            'Mandalajati' => ['latitude' => -6.9020, 'longitude' => 107.6780],
            'Panyileukan' => ['latitude' => -6.9380, 'longitude' => 107.7120],
            'Rancasari' => ['latitude' => -6.9560, 'longitude' => 107.6780],
            'Regol' => ['latitude' => -6.9392, 'longitude' => 107.6083],
            'Sukajadi' => ['latitude' => -6.8883, 'longitude' => 107.5950],
            'Sukasari' => ['latitude' => -6.8720, 'longitude' => 107.5850],
            'Sumur Bandung' => ['latitude' => -6.9167, 'longitude' => 107.6111],
            'Ujungberung' => ['latitude' => -6.9110, 'longitude' => 107.7020],
        ];

        foreach ($kecamatans as $name => $coords) {
            Kecamatan::updateOrCreate(
                ['name' => $name],
                [
                    'latitude' => $coords['latitude'],
                    'longitude' => $coords['longitude']
                ]
            );
        }
    }
}

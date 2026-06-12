<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        $kelurahan = [
            // Kecamatan Andir (id = 1)
            ['name' => 'Campaka', 'kecamatan_id' => 1],
            ['name' => 'Ciroyom', 'kecamatan_id' => 1],
            ['name' => 'Dunguscariang', 'kecamatan_id' => 1],
            ['name' => 'Garuda', 'kecamatan_id' => 1],
            ['name' => 'Kebonjeruk', 'kecamatan_id' => 1],
            ['name' => 'Maleber', 'kecamatan_id' => 1],

            // Kecamatan Antapani (id = 2)
            ['name' => 'Antapani Kidul', 'kecamatan_id' => 2],
            ['name' => 'Antapani Kulon', 'kecamatan_id' => 2],
            ['name' => 'Antapani Tengah', 'kecamatan_id' => 2],
            ['name' => 'Antapani Wetan', 'kecamatan_id' => 2],

            // Kecamatan Arcamanik (id = 3)
            ['name' => 'Cisaranten Bina Harapan', 'kecamatan_id' => 3],
            ['name' => 'Cisaranten Endah', 'kecamatan_id' => 3],
            ['name' => 'Cisaranten Kulon', 'kecamatan_id' => 3],
            ['name' => 'Sukamiskin', 'kecamatan_id' => 3],

            // Kecamatan Astanaanyar (id = 4)
            ['name' => 'Cibadak', 'kecamatan_id' => 4],
            ['name' => 'Karanganyar', 'kecamatan_id' => 4],
            ['name' => 'Karasak', 'kecamatan_id' => 4],
            ['name' => 'Nyengseret', 'kecamatan_id' => 4],
            ['name' => 'Panjunan', 'kecamatan_id' => 4],
            ['name' => 'Pelindunghewan', 'kecamatan_id' => 4],

            // Kecamatan Babakan Ciparay (id = 5)
            ['name' => 'Babakan', 'kecamatan_id' => 5],
            ['name' => 'Babakan Ciparay', 'kecamatan_id' => 5],
            ['name' => 'Cirangrang', 'kecamatan_id' => 5],
            ['name' => 'Margahayu Utara', 'kecamatan_id' => 5],
            ['name' => 'Margasuka', 'kecamatan_id' => 5],
            ['name' => 'Sukahaji', 'kecamatan_id' => 5],

            // Kecamatan Bandung Kidul (id = 6)
            ['name' => 'Batununggal', 'kecamatan_id' => 6],
            ['name' => 'Kujangsari', 'kecamatan_id' => 6],
            ['name' => 'Mengger', 'kecamatan_id' => 6],
            ['name' => 'Wates', 'kecamatan_id' => 6],

            // Kecamatan Bandung Kulon (id = 7)
            ['name' => 'Caringin', 'kecamatan_id' => 7],
            ['name' => 'Cibuntu', 'kecamatan_id' => 7],
            ['name' => 'Gempol Sari', 'kecamatan_id' => 7],
            ['name' => 'Warung Muncang', 'kecamatan_id' => 7],

            // Kecamatan Bandung Wetan (id = 8)
            ['name' => 'Cihapit', 'kecamatan_id' => 8],
            ['name' => 'Citarum', 'kecamatan_id' => 8],
            ['name' => 'Tamansari', 'kecamatan_id' => 8],

            // Kecamatan Batununggal (id = 9)
            ['name' => 'Binong', 'kecamatan_id' => 9],
            ['name' => 'Kacapiring', 'kecamatan_id' => 9],
            ['name' => 'Kebon Gedang', 'kecamatan_id' => 9],
            ['name' => 'Kebon Waru', 'kecamatan_id' => 9],
            ['name' => 'Maleer', 'kecamatan_id' => 9],
            ['name' => 'Samoja', 'kecamatan_id' => 9],

            // Kecamatan Bojongloa Kaler (id = 10)
            ['name' => 'Babakan Tarogong', 'kecamatan_id' => 10],
            ['name' => 'Jamika', 'kecamatan_id' => 10],
            ['name' => 'Kopo', 'kecamatan_id' => 10],
            ['name' => 'Suka Asih', 'kecamatan_id' => 10],
            ['name' => 'Sukabungah', 'kecamatan_id' => 10],

            // Kecamatan Bojongloa Kidul (id = 11)
            ['name' => 'Cibaduyut', 'kecamatan_id' => 11],
            ['name' => 'Cibaduyut Kidul', 'kecamatan_id' => 11],
            ['name' => 'Cibaduyut Wetan', 'kecamatan_id' => 11],
            ['name' => 'Kebon Lega', 'kecamatan_id' => 11],
            ['name' => 'Mekarwangi', 'kecamatan_id' => 11],
            ['name' => 'Situsaeur', 'kecamatan_id' => 11],

            // Kecamatan Buahbatu (id = 12)
            ['name' => 'Cijawura', 'kecamatan_id' => 12],
            ['name' => 'Jatisari', 'kecamatan_id' => 12],
            ['name' => 'Margasari', 'kecamatan_id' => 12],
            ['name' => 'Sekejati', 'kecamatan_id' => 12],

            // Kecamatan Cibeunying Kaler (id = 13)
            ['name' => 'Cigadung', 'kecamatan_id' => 13],
            ['name' => 'Cihaurgeulis', 'kecamatan_id' => 13],
            ['name' => 'Neglasari', 'kecamatan_id' => 13],
            ['name' => 'Sukaluyu', 'kecamatan_id' => 13],

            // Kecamatan Cibeunying Kidul (id = 14)
            ['name' => 'Cicadas', 'kecamatan_id' => 14],
            ['name' => 'Cikutra', 'kecamatan_id' => 14],
            ['name' => 'Padasuka', 'kecamatan_id' => 14],
            ['name' => 'Pasirlayung', 'kecamatan_id' => 14],
            ['name' => 'Sukamaju', 'kecamatan_id' => 14],
            ['name' => 'Sukapada', 'kecamatan_id' => 14],

            // Kecamatan Cibiru (id = 15)
            ['name' => 'Cipadung', 'kecamatan_id' => 15],
            ['name' => 'Cisurupan', 'kecamatan_id' => 15],
            ['name' => 'Palasari', 'kecamatan_id' => 15],
            ['name' => 'Pasirbiru', 'kecamatan_id' => 15],

            // Kecamatan Cicendo (id = 16)
            ['name' => 'Arjuna', 'kecamatan_id' => 16],
            ['name' => 'Husen Sastranegara', 'kecamatan_id' => 16],
            ['name' => 'Pajajaran', 'kecamatan_id' => 16],
            ['name' => 'Pamoyanan', 'kecamatan_id' => 16],
            ['name' => 'Pasirkaliki', 'kecamatan_id' => 16],
            ['name' => 'Sukaraja', 'kecamatan_id' => 16],

            // Kecamatan Cidadap (id = 17)
            ['name' => 'Ciumbuleuit', 'kecamatan_id' => 17],
            ['name' => 'Hegarmanah', 'kecamatan_id' => 17],
            ['name' => 'Ledeng', 'kecamatan_id' => 17],

            // Kecamatan Cinambo (id = 18)
            ['name' => 'Babakan Penghulu', 'kecamatan_id' => 18],
            ['name' => 'Cisaranten Wetan', 'kecamatan_id' => 18],
            ['name' => 'Pakemitan', 'kecamatan_id' => 18],
            ['name' => 'Sukamulya', 'kecamatan_id' => 18],

            // Kecamatan Coblong (id = 19)
            ['name' => 'Cipaganti', 'kecamatan_id' => 19],
            ['name' => 'Dago', 'kecamatan_id' => 19],
            ['name' => 'Lebakgede', 'kecamatan_id' => 19],
            ['name' => 'Lebaksiliwangi', 'kecamatan_id' => 19],
            ['name' => 'Sadangserang', 'kecamatan_id' => 19],
            ['name' => 'Sekeloa', 'kecamatan_id' => 19],

            // Kecamatan Gedebage (id = 20)
            ['name' => 'Cimincrang', 'kecamatan_id' => 20],
            ['name' => 'Cisaranten Kidul', 'kecamatan_id' => 20],
            ['name' => 'Rancabolang', 'kecamatan_id' => 20],
            ['name' => 'Rancanumpang', 'kecamatan_id' => 20],

            // Kecamatan Kiaracondong (id = 21)
            ['name' => 'Babakan Sari', 'kecamatan_id' => 21],
            ['name' => 'Babakan Surabaya', 'kecamatan_id' => 21],
            ['name' => 'Cisaranten', 'kecamatan_id' => 21],
            ['name' => 'Kebon Jayanti', 'kecamatan_id' => 21],
            ['name' => 'Kiaracondong', 'kecamatan_id' => 21],
            ['name' => 'Sukapura', 'kecamatan_id' => 21],

            // Kecamatan Lengkong (id = 22)
            ['name' => 'Burangrang', 'kecamatan_id' => 22],
            ['name' => 'Cijagra', 'kecamatan_id' => 22],
            ['name' => 'Cikawao', 'kecamatan_id' => 22],
            ['name' => 'Lingkar Selatan', 'kecamatan_id' => 22],
            ['name' => 'Malabar', 'kecamatan_id' => 22],
            ['name' => 'Palapis', 'kecamatan_id' => 22],
            ['name' => 'Turangga', 'kecamatan_id' => 22],

            // Kecamatan Mandalajati (id = 23)
            ['name' => 'Jatihandap', 'kecamatan_id' => 23],
            ['name' => 'Karang Pamulang', 'kecamatan_id' => 23],
            ['name' => 'Padasuka', 'kecamatan_id' => 23],

            // Kecamatan Panyileukan (id = 24)
            ['name' => 'Cipadung Kidul', 'kecamatan_id' => 24],
            ['name' => 'Cipadung Kulon', 'kecamatan_id' => 24],
            ['name' => 'Cipadung Wetan', 'kecamatan_id' => 24],
            ['name' => 'Mekarmulya', 'kecamatan_id' => 24],

            // Kecamatan Rancasari (id = 25)
            ['name' => 'C Deri', 'kecamatan_id' => 25],
            ['name' => 'Derwati', 'kecamatan_id' => 25],
            ['name' => 'Manjahlega', 'kecamatan_id' => 25],
            ['name' => 'Mekar Jaya', 'kecamatan_id' => 25],

            // Kecamatan Regol (id = 26)
            ['name' => 'Ancol', 'kecamatan_id' => 26],
            ['name' => 'Balonggede', 'kecamatan_id' => 26],
            ['name' => 'Ciateul', 'kecamatan_id' => 26],
            ['name' => 'Cigereleng', 'kecamatan_id' => 26],
            ['name' => 'Ciseureuh', 'kecamatan_id' => 26],
            ['name' => 'Pasirluyu', 'kecamatan_id' => 26],
            ['name' => 'Pungkur', 'kecamatan_id' => 26],

            // Kecamatan Sukajadi (id = 27)
            ['name' => 'Cipedes', 'kecamatan_id' => 27],
            ['name' => 'Pasteur', 'kecamatan_id' => 27],
            ['name' => 'Sukabungah', 'kecamatan_id' => 27],
            ['name' => 'Sukagalih', 'kecamatan_id' => 27],

            // Kecamatan Sukasari (id = 28)
            ['name' => 'Gegerkalong', 'kecamatan_id' => 28],
            ['name' => 'Isola', 'kecamatan_id' => 28],
            ['name' => 'Sarijadi', 'kecamatan_id' => 28],
            ['name' => 'Sukarasa', 'kecamatan_id' => 28],

            // Kecamatan Sumur Bandung (id = 29)
            ['name' => 'Babakanciamis', 'kecamatan_id' => 29],
            ['name' => 'Braga', 'kecamatan_id' => 29],
            ['name' => 'Kebonpisang', 'kecamatan_id' => 29],
            ['name' => 'Merdeka', 'kecamatan_id' => 29],

            // Kecamatan Ujungberung (id = 30)
            ['name' => 'Cigending', 'kecamatan_id' => 30],
            ['name' => 'Pasanggrahan', 'kecamatan_id' => 30],
            ['name' => 'Pasirendah', 'kecamatan_id' => 30],
            ['name' => 'Pasirjati', 'kecamatan_id' => 30],
            ['name' => 'Pasirwangi', 'kecamatan_id' => 30],
        ];

        DB::table('kelurahans')->insert($kelurahan);
    }
}

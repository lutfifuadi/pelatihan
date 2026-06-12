<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Apakah program ini hanya untuk siswa MAN SABA?',
                'answer' => 'Tidak. Pelatihan ini terbuka secara umum baik untuk siswa aktif MAN SABA, para alumni, pelaku UMKM pemula, maupun masyarakat umum yang berminat besar mengembangkan keterampilan ekonomi kreatif.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah ada biaya untuk mengikuti program ini?',
                'answer' => 'Ya, program ini sepenuhnya dibiayai oleh MAN SABA dalam upaya memberdayakan perekonomian masyarakat sekitar. Tidak ada biaya tersembunyi apa pun dari awal pendaftaran hingga selesai pelatihan.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Bagaimana cara mendaftar?',
                'answer' => 'Cukup klik tombol "Daftar Sekarang" yang tersedia di halaman ini, isi formulir pendaftaran dengan data diri Anda, lalu submit. Setelah itu Anda akan mendapatkan nomor pendaftaran dan instruksi selanjutnya melalui WhatsApp.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Apa saja yang akan saya dapatkan setelah lulus?',
                'answer' => 'Setelah menyelesaikan seluruh rangkaian pelatihan, Anda akan mendapatkan Sertifikat Resmi MAN SABA yang dapat digunakan sebagai portofolio dan penunjang karir atau usaha Anda.',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        DB::table('faqs')->insert($faqs);
    }
}

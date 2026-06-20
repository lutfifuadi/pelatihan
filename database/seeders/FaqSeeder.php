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
                'question' => 'Apakah program ini hanya untuk siswa tertentu?',
                'answer' => 'Tidak. Pelatihan ini terbuka secara umum bagi siapa saja yang berminat mengembangkan keterampilan dan kompetensi diri.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah program ini benar-benar gratis?',
                'answer' => 'Ya, program ini sepenuhnya gratis tanpa biaya pendaftaran maupun biaya pelatihan. Tidak ada biaya tersembunyi apa pun dari awal pendaftaran hingga selesai pelatihan.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Apakah saya akan mendapatkan sertifikat setelah menyelesaikan pelatihan?',
                'answer' => 'Setelah menyelesaikan seluruh rangkaian pelatihan, Anda akan mendapatkan Sertifikat Resmi yang dapat digunakan sebagai portofolio dan penunjang karir atau usaha Anda.',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Berapa lama waktu pelatihan?',
                'answer' => 'Durasi pelatihan bervariasi tergantung program yang dipilih. Informasi lengkap mengenai jadwal dan durasi dapat dilihat di halaman detail pelatihan masing-masing.',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        DB::table('faqs')->insert($faqs);
    }
}

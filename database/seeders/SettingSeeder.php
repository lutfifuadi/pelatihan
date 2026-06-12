<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'whatsapp_api_key', 'value' => '', 'group' => 'whatsapp', 'label' => 'API Key WhatsApp'],
            ['key' => 'whatsapp_send_url', 'value' => '', 'group' => 'whatsapp', 'label' => 'URL Kirim Pesan'],
            ['key' => 'whatsapp_api_url', 'value' => '', 'group' => 'whatsapp', 'label' => 'URL Cek Nomor'],
            ['key' => 'whatsapp_sender', 'value' => '', 'group' => 'whatsapp', 'label' => 'Nomor Pengirim'],
            
            // Branding
            ['key' => 'brand_name', 'value' => 'SABA Kreatif', 'group' => 'general', 'label' => 'Nama Brand Aplikasi'],

            // Identitas Institusi
            ['key' => 'institution_name', 'value' => 'MAN SABA', 'group' => 'general', 'label' => 'Nama Institusi/Lembaga'],
            ['key' => 'institution_address', 'value' => 'MAN SABA, Gedung Pusat Pembelajaran Kreatif', 'group' => 'general', 'label' => 'Alamat Institusi'],
            ['key' => 'institution_phone', 'value' => '+62 812-3456-7890', 'group' => 'general', 'label' => 'Nomor Telepon'],
            ['key' => 'institution_email', 'value' => 'admin@sabakreatif.com', 'group' => 'general', 'label' => 'Email Institusi'],
            ['key' => 'institution_description', 'value' => 'Program Pelatihan Ekonomi Kreatif diselenggarakan oleh MAN SABA sebagai wadah pembekalan kompetensi keahlian praktis yang mandiri, kreatif, dan mandiri secara finansial.', 'group' => 'general', 'label' => 'Deskripsi Institusi'],
        ];

        DB::table('settings')->insert($settings);
    }
}

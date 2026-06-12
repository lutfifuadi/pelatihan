<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'key' => 'welcome_peserta',
                'name' => 'Selamat Datang Peserta',
                'title' => 'Selamat Datang di {pelatihan}',
                'body' => 'Halo {nama}, selamat bergabung di pelatihan {pelatihan}! 🎉',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
            [
                'key' => 'pendaftaran_diterima',
                'name' => 'Pendaftaran Diterima',
                'title' => 'Pendaftaran Diterima',
                'body' => 'Halo {nama}, pendaftaran Anda untuk pelatihan {pelatihan} telah DITERIMA. ✅',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
            [
                'key' => 'pendaftaran_ditolak',
                'name' => 'Pendaftaran Ditolak',
                'title' => 'Pendaftaran Ditolak',
                'body' => 'Mohon maaf {nama}, pendaftaran Anda untuk pelatihan {pelatihan} belum dapat kami terima.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
            [
                'key' => 'tugas_baru',
                'name' => 'Tugas Baru',
                'title' => 'Tugas Baru: {tugas}',
                'body' => 'Halo {nama}, ada tugas baru: {tugas} di pelatihan {pelatihan}. Segera kerjakan! 📝',
                'variables' => ['nama', 'tugas', 'pelatihan'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
            [
                'key' => 'pengingat_jadwal',
                'name' => 'Pengingat Jadwal',
                'title' => 'Pengingat Jadwal Pelatihan',
                'body' => 'Pengingat! Pelatihan {pelatihan} akan dimulai besok {tanggal}. Jangan lupa hadir! ⏰',
                'variables' => ['nama', 'pelatihan', 'tanggal'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
            [
                'key' => 'kelulusan',
                'name' => 'Sertifikat Terbit',
                'title' => 'Selamat Lulus!',
                'body' => 'Selamat {nama}! Anda dinyatakan LULUS dari pelatihan {pelatihan}. 🎓 Sertifikat Anda telah terbit.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'whatsapp',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}

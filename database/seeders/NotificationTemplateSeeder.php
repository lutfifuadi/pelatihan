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
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'pendaftaran_diterima',
                'name' => 'Pendaftaran Diterima',
                'title' => 'Pendaftaran Diterima',
                'body' => 'Halo {nama}, pendaftaran Anda untuk pelatihan {pelatihan} telah DITERIMA. ✅',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'pendaftaran_ditolak',
                'name' => 'Pendaftaran Ditolak',
                'title' => 'Pendaftaran Ditolak',
                'body' => 'Mohon maaf {nama}, pendaftaran Anda untuk pelatihan {pelatihan} belum dapat kami terima.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'masuk_cadangan',
                'name' => 'Masuk Daftar Cadangan',
                'title' => 'Masuk Daftar Cadangan',
                'body' => 'Halo {nama}, kuota utama {pelatihan} sudah terpenuhi. Anda masuk daftar cadangan dan akan dipromosikan jika ada peserta yang mengundurkan diri.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'dipromosikan',
                'name' => 'Dipromosikan dari Cadangan',
                'title' => 'Dipromosikan ke Peserta Utama!',
                'body' => 'Halo {nama}, selamat! Anda telah dipromosikan dari daftar cadangan menjadi peserta utama {pelatihan} 🎉.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'sertifikat_terbit',
                'name' => 'Sertifikat Telah Terbit',
                'title' => 'Sertifikat Telah Terbit!',
                'body' => 'Selamat {nama}! Anda dinyatakan LULUS dari {pelatihan} 🎓. Sertifikat Anda telah terbit dan bisa diunduh di dashboard.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'tugas_baru',
                'name' => 'Tugas Baru',
                'title' => 'Tugas Baru: {tugas}',
                'body' => 'Halo {nama}, ada tugas baru: {tugas} di pelatihan {pelatihan}. Segera kerjakan! 📝',
                'variables' => ['nama', 'tugas', 'pelatihan'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'pengingat_jadwal',
                'name' => 'Pengingat Jadwal',
                'title' => 'Pengingat Jadwal Pelatihan',
                'body' => 'Pengingat! Pelatihan {pelatihan} akan dimulai besok {tanggal}. Jangan lupa hadir! ⏰',
                'variables' => ['nama', 'pelatihan', 'tanggal'],
                'channel' => 'in_app',
                'is_active' => true,
            ],
            [
                'key' => 'kelulusan',
                'name' => 'Sertifikat Terbit (Legacy)',
                'title' => 'Selamat Lulus!',
                'body' => 'Selamat {nama}! Anda dinyatakan LULUS dari pelatihan {pelatihan}. 🎓 Sertifikat Anda telah terbit.',
                'variables' => ['nama', 'pelatihan'],
                'channel' => 'in_app',
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

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
            ['key' => 'whatsapp_check_api_key', 'value' => '', 'group' => 'whatsapp', 'label' => 'API Key Cek Nomor WA'],
            ['key' => 'whatsapp_check_sender', 'value' => '', 'group' => 'whatsapp', 'label' => 'Nomor Pengirim Cek Nomor WA'],
            
            // Branding
            ['key' => 'brand_name', 'value' => 'SABA Kreatif', 'group' => 'general', 'label' => 'Nama Brand Aplikasi'],

            // Identitas Institusi
            ['key' => 'institution_name', 'value' => 'Lembaga Pelatihan', 'group' => 'general', 'label' => 'Nama Institusi/Lembaga'],
            ['key' => 'institution_address', 'value' => 'Gedung Pusat Pembelajaran Kreatif', 'group' => 'general', 'label' => 'Alamat Institusi'],
            ['key' => 'institution_phone', 'value' => '+62 812-3456-7890', 'group' => 'general', 'label' => 'Nomor Telepon'],
            ['key' => 'institution_email', 'value' => 'admin@pelatihanku.my.id', 'group' => 'general', 'label' => 'Email Institusi'],
            ['key' => 'institution_description', 'value' => 'Program pelatihan pengembangan kompetensi dan keterampilan praktis yang mandiri, kreatif, dan berdaya saing.', 'group' => 'general', 'label' => 'Deskripsi Institusi'],

            // ===== LANDING PAGE: Hero Section =====
            ['key' => 'hero_title', 'value' => 'Keahlian Unggul, Kerja & Wirausaha', 'group' => 'landing', 'label' => 'Hero: Judul Utama'],
            ['key' => 'hero_subtitle', 'value' => 'Pusat Pelatihan Terintegrasi', 'group' => 'landing', 'label' => 'Hero: Subjudul'],
            ['key' => 'hero_description', 'value' => 'Tingkatkan keahlian relevan Anda melalui program pelatihan terstruktur yang dirancang khusus untuk menjawab tantangan dunia kerja modern dan mempersiapkan langkah awal wirausaha yang sukses.', 'group' => 'landing', 'label' => 'Hero: Deskripsi'],
            ['key' => 'hero_stat_1_value', 'value' => '100% Gratis', 'group' => 'landing', 'label' => 'Hero: Statistik 1 - Angka'],
            ['key' => 'hero_stat_1_label', 'value' => 'Tanpa Biaya Pendaftaran', 'group' => 'landing', 'label' => 'Hero: Statistik 1 - Label'],
            ['key' => 'hero_stat_2_value', 'value' => 'Kurikulum Relevan', 'group' => 'landing', 'label' => 'Hero: Statistik 2 - Angka'],
            ['key' => 'hero_stat_2_label', 'value' => 'Sesuai Kebutuhan Industri', 'group' => 'landing', 'label' => 'Hero: Statistik 2 - Label'],
            ['key' => 'hero_stat_3_value', 'value' => 'Sertifikat Resmi', 'group' => 'landing', 'label' => 'Hero: Statistik 3 - Angka'],
            ['key' => 'hero_stat_3_label', 'value' => 'Portofolio Kompetensi Diakui', 'group' => 'landing', 'label' => 'Hero: Statistik 3 - Label'],
            ['key' => 'hero_scroll_text', 'value' => 'Scroll ke bawah untuk informasi lanjut', 'group' => 'landing', 'label' => 'Hero: Teks Scroll Info'],

            // ===== LANDING PAGE: Form Registrasi =====
            ['key' => 'form_title', 'value' => 'Daftar Sekarang', 'group' => 'landing', 'label' => 'Form: Judul Kartu'],
            ['key' => 'form_password_info', 'value' => 'Password akun akan diisi otomatis', 'group' => 'landing', 'label' => 'Form: Info Password'],
            ['key' => 'form_password_value', 'value' => 'pelatihanku2026', 'group' => 'landing', 'label' => 'Form: Default Password'],
            ['key' => 'form_button_text', 'value' => 'Daftar Sekarang', 'group' => 'landing', 'label' => 'Form: Tombol Daftar'],
            ['key' => 'form_button_loading', 'value' => 'Memproses Pendaftaran...', 'group' => 'landing', 'label' => 'Form: Loading State'],
            ['key' => 'form_login_text', 'value' => 'Sudah memiliki akun?', 'group' => 'landing', 'label' => 'Form: Teks Login'],
            ['key' => 'form_login_link', 'value' => 'Login di sini', 'group' => 'landing', 'label' => 'Form: Link Login'],

            // ===== LANDING PAGE: Langkah Section =====
            ['key' => 'steps_badge', 'value' => 'Alur Pendaftaran', 'group' => 'landing', 'label' => 'Langkah: Badge'],
            ['key' => 'steps_title', 'value' => 'Mulai Langkah Sukses Anda', 'group' => 'landing', 'label' => 'Langkah: Judul'],
            ['key' => 'steps_subtitle', 'value' => '3 Langkah Mudah Menuju Karier dan Usaha Impian', 'group' => 'landing', 'label' => 'Langkah: Subjudul'],
            ['key' => 'steps_1_title', 'value' => 'Registrasi Akun Mudah', 'group' => 'landing', 'label' => 'Langkah 1: Judul'],
            ['key' => 'steps_1_desc', 'value' => 'Isi formulir pendaftaran online dengan data diri Anda yang valid. Proses pendaftaran sepenuhnya gratis dan hanya memakan waktu kurang dari 3 menit.', 'group' => 'landing', 'label' => 'Langkah 1: Deskripsi'],
            ['key' => 'steps_2_title', 'value' => 'Pembelajaran Interaktif & Praktis', 'group' => 'landing', 'label' => 'Langkah 2: Judul'],
            ['key' => 'steps_2_desc', 'value' => 'Dapatkan akses ke materi pembelajaran yang komprehensif, panduan langkah demi langkah, serta sesi pendampingan langsung bersama instruktur berpengalaman.', 'group' => 'landing', 'label' => 'Langkah 2: Deskripsi'],
            ['key' => 'steps_3_title', 'value' => 'Uji Kompetensi & Sertifikat Resmi', 'group' => 'landing', 'label' => 'Langkah 3: Judul'],
            ['key' => 'steps_3_desc', 'value' => 'Tunjukkan pemahaman Anda melalui evaluasi akhir praktis, raih Sertifikat Kompetensi resmi, dan bersiaplah bersaing di dunia industri atau memulai bisnis Anda.', 'group' => 'landing', 'label' => 'Langkah 3: Deskripsi'],

            // ===== LANDING PAGE: Pelatihan Section =====
            ['key' => 'pelatihan_badge', 'value' => 'Program Unggulan', 'group' => 'landing', 'label' => 'Pelatihan: Badge'],
            ['key' => 'pelatihan_title', 'value' => 'Pelatihan yang Tersedia', 'group' => 'landing', 'label' => 'Pelatihan: Judul'],
            ['key' => 'pelatihan_subtitle', 'value' => 'Pilih kelas sesuai minat Anda. Kuota terbatas, segera daftar sebelum pendaftaran ditutup.', 'group' => 'landing', 'label' => 'Pelatihan: Subjudul'],
            ['key' => 'pelatihan_empty_title', 'value' => 'Belum Ada Pelatihan Aktif', 'group' => 'landing', 'label' => 'Pelatihan: Teks Kosong (Judul)'],
            ['key' => 'pelatihan_empty_desc', 'value' => 'Silakan kembali beberapa saat lagi untuk melihat program pelatihan terbaru kami.', 'group' => 'landing', 'label' => 'Pelatihan: Teks Kosong (Deskripsi)'],

            // ===== LANDING PAGE: Mengapa Section =====
            ['key' => 'why_badge', 'value' => 'Mengapa Memilih Program Kami?', 'group' => 'landing', 'label' => 'Mengapa: Badge'],
            ['key' => 'why_title', 'value' => 'Mengapa Memilih Program Kami?', 'group' => 'landing', 'label' => 'Mengapa: Judul'],
            ['key' => 'why_subtitle', 'value' => 'Sistem pembelajaran komprehensif dan teruji yang dirancang untuk melahirkan tenaga kerja kompeten serta wirausahawan mandiri yang berdaya saing tinggi.', 'group' => 'landing', 'label' => 'Mengapa: Subjudul'],

            // ===== LANDING PAGE: CTA Section =====
            ['key' => 'cta_badge', 'value' => 'SEGERA BERGABUNG', 'group' => 'landing', 'label' => 'CTA: Badge'],
            ['key' => 'cta_title', 'value' => 'Siap Tingkatkan Daya Saing Anda di Dunia Kerja & Wirausaha?', 'group' => 'landing', 'label' => 'CTA: Judul'],
            ['key' => 'cta_subtitle', 'value' => 'Jangan tunda kesempatan berharga untuk memperluas keahlian, membuka peluang karier baru, atau merintis bisnis mandiri. Kuota peserta untuk setiap gelombang sangat terbatas.', 'group' => 'landing', 'label' => 'CTA: Subjudul'],
            ['key' => 'cta_button_text', 'value' => 'Daftar Sekarang — Gratis!', 'group' => 'landing', 'label' => 'CTA: Tombol Daftar'],
            ['key' => 'cta_login_text', 'value' => 'Sudah Punya Akun? Login', 'group' => 'landing', 'label' => 'CTA: Tombol Login'],

            // ===== LANDING PAGE: Tags Kategori =====
            ['key' => 'hero_tag_1_icon', 'value' => 'briefcase', 'group' => 'landing', 'label' => 'Hero: Tag 1 - Icon Tabler'],
            ['key' => 'hero_tag_1_text', 'value' => 'Keterampilan Kerja', 'group' => 'landing', 'label' => 'Hero: Tag 1 - Teks'],
            ['key' => 'hero_tag_2_icon', 'value' => 'chart-line', 'group' => 'landing', 'label' => 'Hero: Tag 2 - Icon Tabler'],
            ['key' => 'hero_tag_2_text', 'value' => 'Bisnis & Wirausaha', 'group' => 'landing', 'label' => 'Hero: Tag 2 - Teks'],
            ['key' => 'hero_tag_3_icon', 'value' => 'device-laptop', 'group' => 'landing', 'label' => 'Hero: Tag 3 - Icon Tabler'],
            ['key' => 'hero_tag_3_text', 'value' => 'Teknologi & Digital', 'group' => 'landing', 'label' => 'Hero: Tag 3 - Teks'],
            ['key' => 'lock_kota', 'value' => 'BANDUNG', 'group' => 'general', 'label' => 'Kota yang Terkunci untuk Pendaftaran'],
            ['key' => 'lock_provinsi', 'value' => 'Jawa Barat', 'group' => 'general', 'label' => 'Provinsi yang Terkunci untuk Pendaftaran'],
            ['key' => 'broadcast_enabled', 'value' => '1', 'group' => 'general', 'label' => 'Aktifkan Broadcast Real-time'],
            ['key' => 'validate_whatsapp', 'value' => '1', 'group' => 'general', 'label' => 'Validasi Otomatis Nomor WhatsApp'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'group' => 'general', 'label' => 'Zona Waktu Aplikasi'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

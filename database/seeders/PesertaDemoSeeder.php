<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user peserta (jika belum ada)
        User::firstOrCreate(
            ['email' => 'peserta@demo.test'],
            [
                'name' => 'Peserta Demo',
                'nik' => '3273010101000001',
                'whatsapp' => '6281234567890',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234567890',
                'avatar' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Catat data profile lengkap ke data-user.txt
        $text = "========================================\n";
        $text .= "        DATA PENDAFTARAN PESERTA\n";
        $text .= "========================================\n";
        $text .= "Tanggal Daftar  : " . now()->format('Y-m-d H:i:s') . "\n";
        $text .= "Nama Lengkap    : Peserta Demo\n";
        $text .= "Tempat Lahir    : Bandung\n";
        $text .= "Tanggal Lahir   : 2000-01-01\n";
        $text .= "Jenis Kelamin   : Laki-laki\n";
        $text .= "Agama           : Islam\n";
        $text .= "NIK             : 3273010101000001\n";
        $text .= "----------------------------------------\n";
        $text .= "ALAMAT & KONTAK\n";
        $text .= "----------------------------------------\n";
        $text .= "Alamat          : Jl. Contoh No. 123\n";
        $text .= "Provinsi        : Jawa Barat\n";
        $text .= "Kota            : Bandung\n";
        $text .= "Kecamatan       : Cicendo\n";
        $text .= "Kode Pos        : 40171\n";
        $text .= "No. HP Alt      : 081234567890\n";
        $text .= "Email Alt       : peserta@demo.test\n";
        $text .= "----------------------------------------\n";
        $text .= "PENDIDIKAN & PEKERJAAN\n";
        $text .= "----------------------------------------\n";
        $text .= "Pendidikan      : S1\n";
        $text .= "Institusi       : Universitas Contoh\n";
        $text .= "Jurusan         : Teknik Informatika\n";
        $text .= "Tahun Lulus     : 2023\n";
        $text .= "Status Pekerjaan: Bekerja\n";
        $text .= "Perusahaan      : PT Contoh Maju\n";
        $text .= "----------------------------------------\n";
        $text .= "MINAT PELATIHAN\n";
        $text .= "----------------------------------------\n";
        $text .= "Bidang Minat    : Teknologi Informasi, Desain Grafis\n";
        $text .= "Tujuan          : Meningkatkan skill di bidang IT\n";
        $text .= "Preferensi Jadwal: Sabtu-Minggu\n";
        $text .= "Preferensi Mode : Online\n";
        $text .= "----------------------------------------\n";
        $text .= "DOKUMEN\n";
        $text .= "----------------------------------------\n";
        $text .= "Foto Profil     : -\n";
        $text .= "Scan KTP        : -\n";
        $text .= "========================================\n\n";

        $dirPath = storage_path('app/demo');
        $filePath = $dirPath . '/data-user.txt';

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $written = file_put_contents($filePath, $text, FILE_APPEND | LOCK_EX);

        if ($written !== false) {
            $this->command->info('✓ Data peserta demo berhasil ditambahkan ke ' . $filePath);
        } else {
            $this->command->warn('⚠ Gagal menulis file data-user.txt, namun seeding tetap dilanjutkan.');
        }
    }
}

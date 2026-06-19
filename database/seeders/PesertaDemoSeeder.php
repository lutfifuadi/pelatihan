<?php

namespace Database\Seeders;

use App\Models\PesertaProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user peserta (jika belum ada)
        $user = User::firstOrCreate(
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

        // 2. Buat / update PesertaProfile dengan data lengkap
        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => 'Peserta Demo',
                'nik' => '3273010101000001',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '01',
                'bulan_lahir' => 'Januari',
                'tahun_lahir' => '2000',
                'alamat_ktp' => 'Jl. Contoh No. 123',
                'rt' => '001',
                'rw' => '002',
                'kecamatan' => 'Cicendo',
                'kelurahan' => 'Husen Sastranegara',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kodepos' => '40171',
                'whatsapp' => '6281234567890',
                'email' => 'peserta@demo.test',
                'link_medsos' => json_encode([
                    ['platform' => 'Instagram', 'url' => 'https://instagram.com/pesertademo'],
                ]),
                'pendidikan_terakhir' => 'S1',
                'nama_institusi' => 'Universitas Contoh',
                'jurusan' => 'Teknik Informatika',
                'tahun_lulus' => '2023',
                'status_pekerjaan' => 'BEKERJA',
                'nama_perusahaan' => 'PT Contoh Maju',
                'batch_pelatihan' => null,
                'pelatihan_id' => null,
                'is_completed' => true,
                'jawaban_pertanyaan' => json_encode([
                    'pengetahuan_asep' => 'Beliau adalah seorang tokoh masyarakat yang peduli terhadap pendidikan dan pelatihan kerja di Kota Bandung.',
                    'alasan_pelatihan' => 'Saya ingin menambah skill baru di bidang teknologi informasi agar bisa bersaing di dunia kerja.',
                    'pengalaman_bisnis' => 'Saya sudah memulai usaha kecil-kecilan sejak 2022, yaitu jualan online melalui marketplace.',
                    'rencana_setelah_pelatihan' => 'Setelah pelatihan, saya ingin membuka jasa desain grafis dan mengembangkan usaha yang sudah ada.',
                    'punya_usaha' => 'Sudah',
                    'jenis_usaha' => 'Kuliner',
                    'usaha_dimiliki' => 'Yang lain',
                    'usaha_dimiliki_other' => 'Katering dan snack box',
                    'nama_usaha' => 'Yang lain',
                    'nama_usaha_other' => 'Cemilan Sehat Bandung',
                    'kendala_usaha' => 'Sulit mendapatkan konsumen baru dan masih kurang modal untuk promosi.',
                ]),
            ]
        );

        // 3. Catat data profile lengkap ke data-user.txt
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
        $text .= "========================================\n";
        $text .= "JAWABAN PERTANYAAN TAHAP 5\n";
        $text .= "----------------------------------------\n";
        $text .= "Pengetahuan Asep Mulyadi : Beliau adalah tokoh masyarakat peduli pendidikan\n";
        $text .= "Alasan Pelatihan         : Ingin menambah skill TI\n";
        $text .= "Pengalaman Bisnis        : Jualan online sejak 2022\n";
        $text .= "Rencana Setelah Pelatihan: Buka jasa desain grafis\n";
        $text .= "Punya Usaha              : Sudah\n";
        $text .= "Jenis Usaha              : Kuliner\n";
        $text .= "Usaha Dimiliki           : Katering dan snack box\n";
        $text .= "Nama Usaha               : Cemilan Sehat Bandung\n";
        $text .= "Kendala Usaha            : Sulit konsumen baru, kurang modal\n";
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

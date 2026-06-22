<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\PesertaProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PesertaSampleSeeder extends Seeder
{
    /**
     * Generate 50 peserta sampel dengan variasi data lengkap.
     * Idempotent — aman dijalankan berulang.
     */
    public function run(): void
    {
        $dataPeserta = $this->generateData();
        $batchSize = 10;
        $chunks = array_chunk($dataPeserta, $batchSize);

        foreach ($chunks as $chunk) {
            foreach ($chunk as $item) {
                $this->createPeserta($item);
            }
        }

        $this->command->info('✓ 50 peserta sampel berhasil dibuat!');
    }

    protected function createPeserta(array $d): void
    {
        $user = User::firstOrCreate(
            ['email' => $d['email']],
            [
                'name' => $d['name'],
                'nik' => $d['nik'],
                'whatsapp' => $d['whatsapp'],
                'phone' => $d['phone'],
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'is_active' => true,
                'email_verified_at' => now(),
                'kecamatan_id' => $d['kecamatan_id'],
                'kelurahan_id' => $d['kelurahan_id'],
                'created_at' => $d['created_at'],
                'updated_at' => $d['created_at'],
            ]
        );

        // Update jika user sudah ada (untuk sebaran tanggal & wilayah)
        if ($user->wasRecentlyCreated === false) {
            $user->timestamps = false;
            $user->created_at = $d['created_at'];
            $user->kecamatan_id = $d['kecamatan_id'];
            $user->kelurahan_id = $d['kelurahan_id'];
            $user->save();
            $user->timestamps = true;
        }

        PesertaProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nama_lengkap' => $d['name'],
                'nik' => $d['nik'],
                'jenis_kelamin' => $d['jenis_kelamin'],
                'tempat_lahir' => $d['tempat_lahir'],
                'tanggal_lahir' => $d['tanggal_lahir'],
                'bulan_lahir' => $d['bulan_lahir'],
                'tahun_lahir' => $d['tahun_lahir'],
                'alamat_ktp' => $d['alamat_ktp'],
                'rt' => $d['rt'],
                'rw' => $d['rw'],
                'kelurahan_id' => $d['kelurahan_id'],
                'kelurahan' => $d['kelurahan'],
                'kecamatan' => $d['kecamatan'],
                'kota' => $d['kota'],
                'provinsi' => $d['provinsi'],
                'kodepos' => $d['kodepos'],
                'whatsapp' => $d['whatsapp'],
                'email' => $d['email'],
                'link_medsos' => json_encode($d['link_medsos']),
                'pendidikan_terakhir' => $d['pendidikan_terakhir'],
                'nama_institusi' => $d['nama_institusi'],
                'jurusan' => $d['jurusan'],
                'tahun_lulus' => $d['tahun_lulus'],
                'status_pekerjaan' => $d['status_pekerjaan'],
                'nama_perusahaan' => $d['nama_perusahaan'],
                'bidang_minat' => json_encode($d['bidang_minat']),
                'tujuan_pelatihan' => $d['tujuan_pelatihan'],
                'preferensi_jadwal' => $d['preferensi_jadwal'],
                'preferensi_mode' => $d['preferensi_mode'],
                'batch_pelatihan' => $d['batch_pelatihan'],
                'pelatihan_id' => $d['pelatihan_id'],
                'is_completed' => $d['is_completed'],
                'jawaban_pertanyaan' => json_encode($d['jawaban_pertanyaan']),
                'created_at' => $d['created_at'],
                'updated_at' => $d['created_at'],
            ]
        );

        // Buat enrollment jika ada pelatihan_id
        if ($d['pelatihan_id'] && $d['enrollment_status']) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'pelatihan_id' => $d['pelatihan_id'],
                ],
                [
                    'status' => $d['enrollment_status'],
                    'created_at' => $d['created_at'],
                    'updated_at' => $d['created_at'],
                ]
            );
        }
    }

    protected function generateData(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // === DATA REFERENSI ===
        $namaPria = [
            'Ahmad Fauzi', 'Budi Santoso', 'Chandra Wijaya', 'Dedi Kurniawan', 'Eko Prasetyo',
            'Fajar Ramadhan', 'Gilang Permana', 'Hendra Gunawan', 'Irfan Hakim', 'Joko Susilo',
            'Kevin Pratama', 'Lukman Nugroho', 'Mochamad Rizky', 'Nanda Firmansyah', 'Oki Setiawan',
            'Putra Mahendra', 'Rafi Ahmad', 'Surya Darma', 'Taufik Hidayat', 'Ujang Kosasih',
            'Vicky Pratama', 'Wahyu Hidayat', 'Yusuf Abdullah', 'Zainal Arifin', 'Aditya Nugraha',
            'Bagas Putra', 'Cahyono', 'Dimas Ardiansyah', 'Erik Tohir', 'Farid Maulana',
        ];
        $namaWanita = [
            'Aisyah Putri', 'Bella Safira', 'Citra Dewi', 'Dewi Sartika', 'Elok Faiqoh',
            'Fitri Handayani', 'Gita Anggraini', 'Hana Marwah', 'Indah Permata', 'Julia Rahmawati',
            'Kartika Sari', 'Lestari Wulandari', 'Maya Sari', 'Nurul Hidayah', 'Olivia Tan',
            'Putri Ayu', 'Rina Marlina', 'Siti Nurhaliza', 'Tiara Amelia', 'Umi Kalsum',
            'Vina Oktaviani', 'Winda Lestari', 'Yuli Astuti', 'Zahra Ramadhani', 'Intan Permata',
            'Ratna Sari', 'Dian Pelangi', 'Riska Amelia', 'Nadia Putri', 'Sari Indah',
        ];
        shuffle($namaPria);
        shuffle($namaWanita);

        // Ambil data kecamatan & kelurahan riil dari database
        $kecamatanDb = [];
        $kelurahanDb = [];
        try {
            $kecamatanDb = \App\Models\Kecamatan::select('id', 'name')->get()->keyBy('id')->toArray();
            $kelurahanDb = \App\Models\Kelurahan::select('id', 'name', 'kecamatan_id')->get()->groupBy('kecamatan_id')->toArray();
        } catch (\Exception $e) {
            // fallback
        }

        $kecamatanIds = array_keys($kecamatanDb);
        $kecamatanNames = array_column($kecamatanDb, 'name', 'id');

        $kotaBandung = ['Bandung', 'Cimahi', 'Bekasi', 'Depok', 'Bogor'];
        $kotaJateng = ['Semarang', 'Surakarta', 'Magelang', 'Pekalongan', 'Tegal'];
        $kotaJatim = ['Surabaya', 'Malang', 'Sidoarjo', 'Gresik', 'Mojokerto'];
        $kotaJakarta = ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Jakarta Utara'];
        $kotaBanten = ['Tangerang', 'Tangerang Selatan', 'Serang', 'Cilegon', 'Ciputat'];

        $provinsiList = ['Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'DKI Jakarta', 'Banten'];
        $kotaByProv = [
            'Jawa Barat' => $kotaBandung,
            'Jawa Tengah' => $kotaJateng,
            'Jawa Timur' => $kotaJatim,
            'DKI Jakarta' => $kotaJakarta,
            'Banten' => $kotaBanten,
        ];

        // Distribusi pendidikan merata: 10 opsi x 5 = 50
        $pendidikanDistribution = ['SD', 'SD', 'SD', 'SD', 'SD', 'SMP', 'SMP', 'SMP', 'SMP', 'SMP', 'SMA', 'SMA', 'SMA', 'SMA', 'SMA', 'SMK', 'SMK', 'SMK', 'SMK', 'SMK', 'D1', 'D1', 'D1', 'D1', 'D1', 'D2', 'D2', 'D2', 'D2', 'D2', 'D3', 'D3', 'D3', 'D3', 'D3', 'S1', 'S1', 'S1', 'S1', 'S1', 'S2', 'S2', 'S2', 'S2', 'S2', 'S3', 'S3', 'S3', 'S3', 'S3'];
        shuffle($pendidikanDistribution);
        $pekerjaanList = ['BEKERJA', 'BELUM BEKERJA', 'WIRAUSAHA', 'PELAJAR/MAHASISWA', 'IRT', 'FREELANCER'];
        $pekerjaanWeight = [32, 18, 18, 15, 10, 7];
        $jadwalList = ['Pagi (08:00-12:00)', 'Siang (13:00-17:00)', 'Sore (16:00-20:00)', 'Fleksibel', 'Sabtu-Minggu'];
        $modeList = ['Online', 'Offline', 'Hybrid'];
        $enrollmentStatuses = ['pending', 'approved', 'rejected', 'waitlist'];
        $enrollmentWeight = [25, 50, 15, 10];

        $institusi = [
            'SD' => ['SD Negeri Cicendo', 'SD Negeri Andir', 'SD Harapan Bangsa', 'SD BPI Bandung', 'SD Merdeka'],
            'SMP' => ['SMP Negeri 1 Bandung', 'SMP Negeri 5 Bandung', 'SMP Negeri 2 Bandung', 'SMP Pasundan', 'SMP BPI Bandung'],
            'SMA' => ['SMA Negeri 1 Bandung', 'SMA Negeri 2 Bandung', 'SMA Negeri 3 Bandung', 'SMA Pasundan', 'SMA BPI Bandung'],
            'SMK' => ['SMK Negeri 1 Bandung', 'SMK Negeri 2 Bandung', 'SMK Pasundan', 'SMK Bina Warga', 'SMK LPPM'],
            'D1' => ['P2K (D1) Bandung', 'LPK Global (D1)', 'Akademi Sekretaris', 'Akademi Bahasa Asing', 'Pusat Kursus Terpadu'],
            'D2' => ['Politeknik Trakindo (D2)', 'Akademi Akuntansi', 'Akademi Pariwisata', 'LP3I (D2)', 'Akademi Keperawatan'],
            'D3' => ['Politeknik Negeri Bandung', 'Politeknik TEDC', 'Universitas Telkom (D3)', 'Polban', 'Politeknik Kesehatan'],
            'S1' => ['Universitas Padjadjaran', 'Institut Teknologi Bandung', 'Universitas Indonesia', 'Universitas Gadjah Mada', 'Universitas Diponegoro'],
            'S2' => ['ITB (S2)', 'UI (S2)', 'UGM (S2)', 'Unpad (S2)', 'ITS (S2)'],
            'S3' => ['ITB (S3)', 'UI (S3)', 'UGM (S3)', 'Unpad (S3)', 'ITS (S3)'],
        ];
        $jurusanList = [
            'Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi', 'Desain Komunikasi Visual',
            'Teknik Elektro', 'Teknik Mesin', 'Ilmu Komunikasi', 'Ekonomi Pembangunan', 'Hukum',
            'Matematika', 'Fisika', 'Biologi', 'Kedokteran', 'Farmasi',
        ];
        $perusahaanList = [
            'PT Telkom Indonesia', 'PT Gojek Indonesia', 'PT Shopee Indonesia', 'PT Bank Mandiri',
            'PT Pertamina', 'PT PLN', 'PT Bukalapak', 'PT Tokopedia', 'PT ABC', 'PT Garuda Indonesia',
            'PT Bio Farma', 'PT Kereta Api Indonesia', 'PT Angkasa Pura', 'PT Jasa Marga', 'PT Wijaya Karya',
            '-', '-', '-', '-', '-',
        ];
        $bidangMinatList = [
            ['Teknologi Informasi', 'Desain Grafis'],
            ['Kuliner', 'Bisnis Digital'],
            ['Pengembangan Diri', 'Komunikasi'],
            ['Kewirausahaan', 'Manajemen'],
            ['Bahasa Asing', 'Public Speaking'],
            ['Fotografi', 'Videografi'],
            ['Akuntansi', 'Perpajakan'],
            ['Kesehatan', 'Kebidanan'],
            ['Pemasaran Digital', 'E-commerce'],
            ['Menjahit', 'Tata Busana'],
            ['Otomotif', 'Elektronika'],
            ['Pertanian', 'Peternakan'],
            ['Desain Interior', 'Arsitektur'],
            ['Musik', 'Seni Rupa'],
            ['Olahraga', 'Kebugaran'],
        ];
        $tujuanList = [
            'Meningkatkan skill dan kompetensi di bidang yang diminati',
            'Mencari peluang kerja baru setelah pelatihan',
            'Mengembangkan usaha yang sudah dirintis',
            'Menambah wawasan dan pengetahuan baru',
            'Persiapan memulai karir di bidang teknologi',
            'Ingin beralih karir ke bidang baru',
            'Memenuhi kebutuhan kompetensi di pekerjaan saat ini',
            'Mengisi waktu luang dengan kegiatan produktif',
        ];
        $jawabanPertanyaan = [
            [
                'pengetahuan_asep' => 'Asep Mulyadi adalah tokoh masyarakat Bandung yang fokus pada pengembangan sumber daya manusia melalui pelatihan dan pendidikan vokasi.',
                'alasan_pelatihan' => 'Saya ingin meningkatkan kompetensi di bidang teknologi informasi untuk bersaing di era digital.',
                'pengalaman_bisnis' => 'Saya sudah memiliki usaha kecil sejak 2021, bergerak di bidang kuliner online.',
                'rencana_setelah_pelatihan' => 'Saya berencana membuka usaha baru dan mengaplikasikan ilmu yang didapat.',
                'punya_usaha' => 'Sudah',
                'jenis_usaha' => 'Kuliner',
                'usaha_dimiliki' => 'Camilan rumahan',
                'nama_usaha' => 'Makanan ringan',
                'kendala_usaha' => 'Modal terbatas dan pemasaran masih manual.',
            ],
            [
                'pengetahuan_asep' => 'Beliau adalah penggiat pelatihan vokasi yang telah membantu banyak anak muda mendapatkan keterampilan kerja.',
                'alasan_pelatihan' => 'Ingin menambah relasi dan pengalaman baru di bidang yang saya sukai.',
                'pengalaman_bisnis' => 'Belum punya pengalaman bisnis, baru tahap belajar.',
                'rencana_setelah_pelatihan' => 'Ingin mencari pekerjaan setelah memiliki sertifikat pelatihan.',
                'punya_usaha' => 'Belum',
                'jenis_usaha' => '',
                'usaha_dimiliki' => '',
                'nama_usaha' => '',
                'kendala_usaha' => 'Belum memiliki modal dan pengalaman.',
            ],
            [
                'pengetahuan_asep' => 'Tokoh visioner yang peduli pada pemberdayaan ekonomi masyarakat melalui berbagai program pelatihan.',
                'alasan_pelatihan' => 'Ada tuntutan dari tempat kerja untuk terus meng-upgrade skill.',
                'pengalaman_bisnis' => 'Pernah mencoba bisnis sampingan jualan pulsa dan kuota.',
                'rencana_setelah_pelatihan' => 'Mengembangkan karir di tempat kerja saat ini.',
                'punya_usaha' => 'Tidak',
                'jenis_usaha' => '',
                'usaha_dimiliki' => '',
                'nama_usaha' => '',
                'kendala_usaha' => 'Tidak ada waktu untuk merintis usaha.',
            ],
            [
                'pengetahuan_asep' => 'Asep Mulyadi dikenal sebagai pembina UMKM yang aktif memberikan pelatihan gratis bagi warga Bandung.',
                'alasan_pelatihan' => 'Mendapat rekomendasi dari teman dan tertarik dengan program pelatihannya.',
                'pengalaman_bisnis' => 'Sedang merintis bisnis online shop sejak 6 bulan lalu.',
                'rencana_setelah_pelatihan' => 'Ingin mengembangkan bisnis online shop agar lebih profesional.',
                'punya_usaha' => 'Sudah',
                'jenis_usaha' => 'Fashion',
                'usaha_dimiliki' => 'Toko online',
                'nama_usaha' => 'Fashionable.id',
                'kendala_usaha' => 'Kesulitan dalam pemasaran digital dan fotografi produk.',
            ],
            [
                'pengetahuan_asep' => 'Beliau adalah motivator dan penggerak ekonomi kreatif di Jawa Barat.',
                'alasan_pelatihan' => 'Ingin memulai usaha sendiri setelah lulus kuliah.',
                'pengalaman_bisnis' => 'Pernah ikut program mahasiswa wirausaha di kampus.',
                'rencana_setelah_pelatihan' => 'Membuka jasa desain grafis dan percetakan.',
                'punya_usaha' => 'Belum',
                'jenis_usaha' => '',
                'usaha_dimiliki' => '',
                'nama_usaha' => '',
                'kendala_usaha' => 'Masih kurang percaya diri untuk memulai.',
            ],
        ];

        $result = [];
        $idx = 0;

        // Ambil data pelatihan dari database
        $pelatihanIds = [];
        try {
            $pelatihanIds = \App\Models\Pelatihan::where('is_active', true)->pluck('id')->toArray();
        } catch (\Exception $e) {
            // fallback
        }
        if (empty($pelatihanIds)) {
            $pelatihanIds = [null];
        }

        for ($i = 0; $i < 50; $i++) {
            $idx++;
            $isPria = $i < 25;
            $nama = $isPria ? $namaPria[$i % count($namaPria)] : $namaWanita[$i % count($namaWanita)];
            $jk = $isPria ? 'L' : 'P';

            // Variasi usia 17-55 tahun
            $usia = rand(17, 55);
            $tahunLahir = now()->year - $usia;
            $bulanAngka = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $tanggalAngka = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $bulanNama = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][(int)$bulanAngka - 1];

            $tempatLahir = $faker->city();

            // Provinsi & kota — semua dari database (Kota Bandung, Jawa Barat)
            $provinsi = 'Jawa Barat';
            $kota = 'Bandung';

            // Kecamatan & kelurahan dari database
            $kecamatanId = null;
            $kecamatanName = '';
            $kelurahanId = null;
            $kelurahanName = '';
            if (!empty($kecamatanIds)) {
                $kecamatanId = $kecamatanIds[array_rand($kecamatanIds)];
                $kecamatanName = $kecamatanNames[$kecamatanId] ?? '';

                // Ambil kelurahan yang sesuai dengan kecamatan_id ini
                if (!empty($kelurahanDb[$kecamatanId])) {
                    $kelList = $kelurahanDb[$kecamatanId];
                    $kelItem = $kelList[array_rand($kelList)];
                    $kelurahanId = $kelItem['id'];
                    $kelurahanName = $kelItem['name'];
                }
            }

            // Pendidikan (distribusi merata dari array)
            $pendidikan = $pendidikanDistribution[$i % 50];
            $instArr = $institusi[$pendidikan] ?? $institusi['SMA'];
            $institusiName = $instArr[array_rand($instArr)];
            $jurusan = ($pendidikan === 'SD' || $pendidikan === 'SMP') ? '-' : $jurusanList[array_rand($jurusanList)];
            $tahunLulusPend = $tahunLahir + ($pendidikan === 'S2' ? 24 : ($pendidikan === 'S1' ? 22 : ($pendidikan === 'D3' ? 21 : ($pendidikan === 'SMK' || $pendidikan === 'SMA' ? 18 : ($pendidikan === 'SMP' ? 15 : 12)))));
            if ($tahunLulusPend > now()->year) $tahunLulusPend = now()->year - rand(1, 5);

            // Pekerjaan
            $pekerjaan = $this->weightedRandom($pekerjaanList, $pekerjaanWeight);
            $perusahaan = (in_array($pekerjaan, ['BEKERJA', 'WIRAUSAHA', 'FREELANCER']))
                ? $perusahaanList[array_rand($perusahaanList)]
                : '-';
            if ($perusahaan === '-' && $pekerjaan === 'BEKERJA') $perusahaan = $perusahaanList[array_rand($perusahaanList)];

            // Minat
            $bidangMinat = $bidangMinatList[array_rand($bidangMinatList)];

            // Jadwal & mode
            $prefJadwal = $jadwalList[array_rand($jadwalList)];
            $prefMode = $modeList[array_rand($modeList)];

            // Waktu dibuat — sebar 12 bulan terakhir
            $createdAt = now()->subDays(rand(0, 365))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            // Pelatihan & enrollment
            $pelatihanId = null;
            $enrollmentStatus = null;
            if (!empty($pelatihanIds) && $pelatihanIds[0] !== null) {
                $pelatihanId = $pelatihanIds[array_rand($pelatihanIds)];
                $enrollmentStatus = $this->weightedRandom($enrollmentStatuses, $enrollmentWeight);
            }

            // NIK
            $nik = '32' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT) . str_pad($idx, 6, '0', STR_PAD_LEFT) . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);

            // WA & Email
            $wa = '628' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
            $phone = '08' . substr($wa, 2);
            $email = strtolower(str_replace(' ', '.', $nama)) . $idx . '@gmail.com';

            // Jawaban pertanyaan
            $jawaban = $jawabanPertanyaan[array_rand($jawabanPertanyaan)];
            // Sesuaikan
            if ($jawaban['punya_usaha'] === 'Belum' || $jawaban['punya_usaha'] === 'Tidak') {
                $jawaban['jenis_usaha'] = '';
                $jawaban['usaha_dimiliki'] = '';
                $jawaban['nama_usaha'] = '';
                $jawaban['kendala_usaha'] = 'Belum memiliki usaha.';
            }

            // Alamat
            $alamat = $faker->streetAddress();

            $result[] = [
                'name' => $nama,
                'nik' => $nik,
                'whatsapp' => $wa,
                'phone' => $phone,
                'email' => $email,
                'jenis_kelamin' => $jk,
                'tempat_lahir' => $tempatLahir,
                'tanggal_lahir' => $tanggalAngka,
                'bulan_lahir' => $bulanNama,
                'tahun_lahir' => (string)$tahunLahir,
                'alamat_ktp' => $alamat,
                'rt' => str_pad(rand(1, 15), 3, '0', STR_PAD_LEFT),
                'rw' => str_pad(rand(1, 10), 3, '0', STR_PAD_LEFT),
                'kecamatan_id' => $kecamatanId,
                'kelurahan_id' => $kelurahanId,
                'kelurahan' => $kelurahanName,
                'kecamatan' => $kecamatanName,
                'kota' => $kota,
                'provinsi' => $provinsi,
                'kodepos' => (string)rand(40000, 40999),
                'link_medsos' => [
                    ['platform' => 'Instagram', 'url' => 'https://instagram.com/' . strtolower(str_replace(' ', '.', $nama))],
                    ['platform' => 'Facebook', 'url' => 'https://facebook.com/' . strtolower(str_replace(' ', '', $nama))],
                ],
                'pendidikan_terakhir' => $pendidikan,
                'nama_institusi' => $institusiName,
                'jurusan' => $jurusan,
                'tahun_lulus' => (string)$tahunLulusPend,
                'status_pekerjaan' => $pekerjaan,
                'nama_perusahaan' => $perusahaan,
                'bidang_minat' => $bidangMinat,
                'tujuan_pelatihan' => $tujuanList[array_rand($tujuanList)],
                'preferensi_jadwal' => $prefJadwal,
                'preferensi_mode' => $prefMode,
                'batch_pelatihan' => null,
                'pelatihan_id' => $pelatihanId,
                'is_completed' => true,
                'jawaban_pertanyaan' => $jawaban,
                'created_at' => $createdAt,
                'enrollment_status' => $enrollmentStatus,
            ];
        }

        return $result;
    }

    protected function weightedRandom(array $items, array $weights): string
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $cumulative = 0;
        foreach ($items as $i => $item) {
            $cumulative += $weights[$i];
            if ($rand <= $cumulative) {
                return $item;
            }
        }
        return $items[0];
    }
}

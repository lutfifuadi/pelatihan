<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dinas;
use App\Models\User;
use App\Models\Pelatihan;
use App\Models\Enrollment;
use App\Models\PesertaProfile;
use App\Exports\EnrollmentExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Maatwebsite\Excel\Facades\Excel;

class ExportEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function tearDown(): void
    {
        app()->forgetInstance('excel');
        Excel::clearResolvedInstance();
        parent::tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@export.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);
    }

    public function test_export_excel_downloadable()
    {
        $dinas = Dinas::factory()->create();
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Export Test',
            'batch' => 'BATCH-001',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '08',
            'tahun_lahir' => '1995',
        ]);
        Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $response = $this->get('/admin/exports/enrollments/excel');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_export_uses_correct_class()
    {
        $dinas = Dinas::factory()->create();
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-001',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
        ]);
        Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        Excel::fake();
        $this->get('/admin/exports/enrollments/excel');

        Excel::assertDownloaded('data-pendaftaran-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function test_headings_have_all_required_columns()
    {
        $export = new EnrollmentExport();
        $headings = $export->headings();

        $requiredColumns = [
            'Nama Peserta',
            'NIK',
            'WhatsApp',
            'Email',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Bulan Lahir',
            'Tahun Lahir',
            'Link Medsos',
            'Alamat KTP',
            'RT',
            'RW',
            'Kelurahan',
            'Kecamatan',
            'Kota',
            'Provinsi',
            'Kodepos',
            'Pendidikan Terakhir',
            'Nama Institusi',
            'Jurusan',
            'Tahun Lulus',
            'Status Pekerjaan',
            'Nama Perusahaan',
            'Nama Pelatihan',
            'Batch',
            'Dinas Penyelenggara',
            'Status Pendaftaran',
            'Tanggal Daftar',
            'Tanggal Approve',
            'Tanggal Ditolak',
            'Tanggal Promosi Cadangan',
            'Catatan',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains($column, $headings, "Column '$column' is missing from headings");
        }

        $this->assertGreaterThanOrEqual(33, count($headings), 'Should have at least 33 columns');
    }

    public function test_map_returns_correct_data_structure()
    {
        $dinas = Dinas::factory()->create(['nama_dinas' => 'Dinas Test']);
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan A',
            'batch' => 'BATCH-001',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'peserta',
            'name' => 'John Doe',
            'nik' => '3273010101950001',
            'whatsapp' => '6281234567890',
            'email' => 'john@example.com',
        ]);

        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '08',
            'tahun_lahir' => '1995',
            'link_medsos' => json_encode([
                ['platform' => 'Instagram', 'url' => 'https://instagram.com/john'],
            ]),
            'alamat_ktp' => 'Jl. Merdeka No.1',
            'rt' => '001',
            'rw' => '002',
            'kelurahan' => 'Kelurahan Test',
            'kecamatan' => 'Kecamatan Test',
            'kota' => 'Kota Test',
            'provinsi' => 'Provinsi Test',
            'kodepos' => '12345',
            'pendidikan_terakhir' => 'S1',
            'nama_institusi' => 'Universitas Test',
            'jurusan' => 'Informatika',
            'tahun_lulus' => '2020',
            'status_pekerjaan' => 'Bekerja',
            'nama_perusahaan' => 'PT Test',
            'jawaban_pertanyaan' => json_encode([
                'motivasi' => 'Ingin belajar',
                'pengalaman' => 'Pernah ikut pelatihan',
            ]),
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'approved',
            'notes' => 'Catatan test',
            'approved_at' => now(),
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertEquals('John Doe', $row[0]);
        $this->assertEquals('3273010101950001', $row[1]);
        $this->assertEquals('6281234567890', $row[2]);
        $this->assertEquals('john@example.com', $row[3]);
        $this->assertEquals('Laki-laki', $row[4]);
        $this->assertEquals('Jakarta', $row[5]);
        $this->assertEquals('15', $row[6]);
        $this->assertEquals('08', $row[7]);
        $this->assertEquals('1995', $row[8]);
        $this->assertStringContainsString('Instagram', $row[9]);
        $this->assertStringContainsString('https://instagram.com/john', $row[9]);
        $this->assertEquals('Jl. Merdeka No.1', $row[10]);
        $this->assertEquals('001', $row[11]);
        $this->assertEquals('002', $row[12]);
        $this->assertEquals('Kelurahan Test', $row[13]);
        $this->assertEquals('Kecamatan Test', $row[14]);
        $this->assertEquals('Kota Test', $row[15]);
        $this->assertEquals('Provinsi Test', $row[16]);
        $this->assertEquals('12345', $row[17]);
        $this->assertEquals('S1', $row[18]);
        $this->assertEquals('Universitas Test', $row[19]);
        $this->assertEquals('Informatika', $row[20]);
        $this->assertEquals('2020', $row[21]);
        $this->assertEquals('Bekerja', $row[22]);
        $this->assertEquals('PT Test', $row[23]);
        $this->assertEquals('Pelatihan A', $row[24]);
        $this->assertEquals('BATCH-001', $row[25]);
        $this->assertEquals('Dinas Test', $row[26]);
        $this->assertEquals('Approved', $row[27]);
        $this->assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}$/', $row[28]);
        $this->assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4} \d{2}:\d{2}$/', $row[29]);
        $this->assertEquals('-', $row[30]);
        $this->assertEquals('-', $row[31]);
        $this->assertEquals('Catatan test', $row[32]);
    }

    public function test_nik_whatsapp_kodepos_stay_as_string()
    {
        $dinas = Dinas::factory()->create();
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-001',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'role' => 'peserta',
            'nik' => '3273010101950001',
            'whatsapp' => '6281234567890',
        ]);

        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'kodepos' => '12345',
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertIsString($row[1], 'NIK should be a string');
        $this->assertEquals('3273010101950001', $row[1], 'NIK should be intact');
        $this->assertIsString($row[2], 'WhatsApp should be a string');
        $this->assertEquals('6281234567890', $row[2], 'WhatsApp should be intact');
        $this->assertIsString($row[17], 'Kodepos should be a string');
        $this->assertEquals('12345', $row[17], 'Kodepos should be intact');
    }

    public function test_empty_profile_returns_dash()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan No Profile',
            'batch' => 'BATCH-002',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertEquals($user->name, $row[0]);
        $this->assertEquals('-', $row[4], 'Jenis Kelamin should be -');
        $this->assertEquals('-', $row[5], 'Tempat Lahir should be -');
        $this->assertEquals('-', $row[10], 'Alamat KTP should be -');
        $this->assertEquals('-', $row[17], 'Kodepos should be -');
        $this->assertEquals('-', $row[18], 'Pendidikan Terakhir should be -');
        $this->assertEquals('-', $row[23], 'Nama Perusahaan should be -');
        $this->assertEquals('-', $row[26], 'Dinas Penyelenggara should be -');
    }

    public function test_pending_status_shows_dash_for_approve_reject_dates()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Status',
            'batch' => 'BATCH-003',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertEquals('Pending', $row[27]);
        $this->assertEquals('-', $row[29], 'Tanggal Approve should be -');
        $this->assertEquals('-', $row[30], 'Tanggal Ditolak should be -');
        $this->assertEquals('-', $row[31], 'Tanggal Promosi Cadangan should be -');
    }

    public function test_export_filtered_by_pelatihan()
    {
        Excel::fake();

        $dinas = Dinas::factory()->create();
        $pelatihan1 = Pelatihan::create([
            'nama' => 'Pelatihan Filter A',
            'batch' => 'BATCH-A',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);
        $pelatihan2 = Pelatihan::create([
            'nama' => 'Pelatihan Filter B',
            'batch' => 'BATCH-B',
            'deskripsi' => 'Test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        $user1 = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create(['user_id' => $user1->id, 'nama_lengkap' => $user1->name]);
        Enrollment::create([
            'user_id' => $user1->id,
            'pelatihan_id' => $pelatihan1->id,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $user2 = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create(['user_id' => $user2->id, 'nama_lengkap' => $user2->name]);
        Enrollment::create([
            'user_id' => $user2->id,
            'pelatihan_id' => $pelatihan2->id,
            'status' => 'pending',
        ]);

        Excel::fake();
        $response = $this->get("/admin/exports/enrollments/excel/{$pelatihan1->id}");
        $response->assertStatus(200);
        $expectedFilename = 'data-pendaftaran-pelatihan-filter-a-' . now()->format('Y-m-d') . '.xlsx';
        Excel::assertDownloaded($expectedFilename);
    }

    public function test_dynamic_jawaban_columns_appear_in_headings()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Jawaban',
            'batch' => 'BATCH-004',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'jawaban_pertanyaan' => json_encode([
                'motivasi_mengikuti' => 'Ingin belajar',
                'pengalaman_kerja' => '5 tahun',
                'keahlian_khusus' => 'Public speaking',
            ]),
        ]);

        Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $headings = $export->headings();

        $this->assertContains('Motivasi Mengikuti', $headings);
        $this->assertContains('Pengalaman Kerja', $headings);
        $this->assertContains('Keahlian Khusus', $headings);
    }

    public function test_map_includes_jawaban_values()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Jawaban',
            'batch' => 'BATCH-004',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'jawaban_pertanyaan' => json_encode([
                'motivasi_mengikuti' => 'Ingin belajar',
                'pengalaman_kerja' => '5 tahun',
            ]),
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);
        $headings = $export->headings();

        $motivasiIndex = array_search('Motivasi Mengikuti', $headings);
        $pengalamanIndex = array_search('Pengalaman Kerja', $headings);

        $this->assertNotFalse($motivasiIndex, 'Motivasi Mengikuti heading should exist');
        $this->assertNotFalse($pengalamanIndex, 'Pengalaman Kerja heading should exist');

        $this->assertEquals('Ingin belajar', $row[$motivasiIndex]);
        $this->assertEquals('5 tahun', $row[$pengalamanIndex]);
    }

    public function test_jawaban_missing_key_returns_dash()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Jawaban Miss',
            'batch' => 'BATCH-005',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'jawaban_pertanyaan' => json_encode([
                'motivasi' => 'Belajar',
            ]),
        ]);

        $enrollment2 = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment2);
        $headings = $export->headings();

        $motivasiIndex = array_search('Motivasi', $headings);
        $this->assertNotFalse($motivasiIndex);
        $this->assertEquals('Belajar', $row[$motivasiIndex]);
    }

    public function test_multiple_enrollments_with_different_jawaban_keys_merges_all_keys()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Multi',
            'batch' => 'BATCH-006',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user1 = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user1->id,
            'nama_lengkap' => $user1->name,
            'jawaban_pertanyaan' => json_encode(['key_a' => 'value_a']),
        ]);
        Enrollment::create([
            'user_id' => $user1->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $user2 = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user2->id,
            'nama_lengkap' => $user2->name,
            'jawaban_pertanyaan' => json_encode(['key_b' => 'value_b']),
        ]);
        Enrollment::create([
            'user_id' => $user2->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $headings = $export->headings();

        $this->assertContains('Key A', $headings);
        $this->assertContains('Key B', $headings);
    }

    public function test_dinas_penyelenggara_is_dash_when_no_dinas()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan No Dinas',
            'batch' => 'BATCH-007',
            'deskripsi' => 'Test',
            'is_active' => true,
            'dinas_id' => null,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertEquals('-', $row[26], 'Dinas Penyelenggara should be - when pelatihan has no dinas');
    }

    public function test_link_medsos_empty_returns_dash()
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Medsos',
            'batch' => 'BATCH-008',
            'deskripsi' => 'Test',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['role' => 'peserta']);
        PesertaProfile::create([
            'user_id' => $user->id,
            'nama_lengkap' => $user->name,
            'link_medsos' => json_encode([]),
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'pending',
        ]);

        $export = new EnrollmentExport();
        $export->collection();
        $row = $export->map($enrollment);

        $this->assertEquals('-', $row[9], 'Link Medsos should be - when empty');
    }

    public function test_non_admin_cannot_export()
    {
        $user = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($user);

        $response = $this->get('/admin/exports/enrollments/excel');
        $response->assertStatus(403);
    }

    public function test_export_without_data_still_returns_valid_file()
    {
        $response = $this->get('/admin/exports/enrollments/excel');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Pelatihan;
use App\Models\PesertaProfile;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PesertaFormTest extends TestCase
{
    use RefreshDatabase;

    private User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        Setting::create(['key' => 'lock_kota', 'value' => 'BANDUNG', 'group' => 'general', 'label' => 'Kota']);
        Setting::create(['key' => 'lock_provinsi', 'value' => 'Jawa Barat', 'group' => 'general', 'label' => 'Provinsi']);

        $kecamatan = Kecamatan::create(['name' => 'Cicendo']);

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'nik' => '3273010101000001',
            'whatsapp' => '6281234567890',
            'email' => 'peserta@form.test',
            'kecamatan_id' => $kecamatan->id,
        ]);

        PesertaProfile::create([
            'user_id' => $this->peserta->id,
            'nama_lengkap' => 'Peserta Lengkap',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '01',
            'tahun_lahir' => '2000',
            'nik' => '3273010101000001',
        ]);

        Sanctum::actingAs($this->peserta);
    }

    public function test_form_pendaftaran_page_accessible(): void
    {
        $response = $this->get('/dashboard/peserta/form-pendaftaran');
        $response->assertStatus(200);
    }

    public function test_save_tab1_stores_data(): void
    {
        $response = $this->post('/dashboard/peserta/save-tab1', [
            'nama_lengkap' => 'Peserta Updated',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '20',
            'bulan_lahir' => '05',
            'tahun_lahir' => '1999',
            'nik' => '3273010101000001',
        ]);

        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'nama_lengkap' => 'Peserta Updated',
        ]);
    }

    public function test_form_pendaftaran_store_redirects(): void
    {
        $response = $this->post('/dashboard/peserta/form-pendaftaran', [
            'nama_lengkap' => 'Peserta Lengkap',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '01',
            'tahun_lahir' => '2000',
            'nik' => '3273010101000001',
            'alamat_ktp' => 'Jl. Test No. 123',
            'rt' => '001',
            'rw' => '002',
            'kelurahan' => 'Test Kel',
            'kecamatan' => 'Cicendo',
            'kota' => 'BANDUNG',
            'provinsi' => 'Jawa Barat',
            'kodepos' => '40171',
            'whatsapp' => '6281234567890',
            'email' => 'peserta@form.test',
        ]);

        $response->assertRedirect(route('dashboard.peserta.form-alamat'));
    }

    public function test_form_alamat_page_accessible(): void
    {
        $response = $this->get('/dashboard/peserta/form-alamat');
        $response->assertStatus(200);
    }

    public function test_save_alamat_redirects(): void
    {
        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Arjuna',
            'kodepos' => '40172',
            'is_active' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-alamat', [
            'provinsi' => 'Jawa Barat',
            'kota' => 'BANDUNG',
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
            'rt' => '001',
            'rw' => '002',
            'alamat_ktp' => 'JL. HOS COKROAMINOTO NO 1',
            'kodepos' => '40172',
            'whatsapp' => '6281234567890',
            'email' => 'peserta@form.test',
            'link_medsos' => '[]',
        ]);

        $response->assertRedirect(route('dashboard.peserta.form-pendidikan'));

        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'rt' => '001',
            'rw' => '002',
            'kelurahan_id' => $kelurahan->id,
            'kecamatan' => $kecamatan->name,
            'kelurahan' => $kelurahan->name,
        ]);
    }

    public function test_form_pendidikan_page_accessible(): void
    {
        $response = $this->get('/dashboard/peserta/form-pendidikan');
        $response->assertStatus(200);
    }

    public function test_save_pendidikan_redirects(): void
    {
        $response = $this->post('/dashboard/peserta/form-pendidikan', [
            'pendidikan_terakhir' => 'S1',
            'nama_institusi' => 'Universitas Test',
            'jurusan' => 'Teknik',
            'tahun_lulus' => '2023',
            'status_pekerjaan' => 'Bekerja',
            'nama_perusahaan' => 'PT Test',
        ]);

        $response->assertRedirect(route('dashboard.peserta.form-minat'));
    }

    public function test_form_minat_page_accessible(): void
    {
        Pelatihan::create([
            'nama' => 'Test Training',
            'batch' => 'BATCH-FORM-1',
            'is_active' => true,
        ]);

        $response = $this->get('/dashboard/peserta/form-minat');
        $response->assertStatus(200);
    }

    public function test_save_minat_redirects(): void
    {
        Pelatihan::create([
            'nama' => 'Test Training',
            'batch' => 'BATCH-FORM-2',
            'is_active' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-minat', [
            'batch_pelatihan' => 'BATCH-FORM-2',
        ]);

        $response->assertRedirect(route('dashboard.peserta.form-dokumen'));
    }

    public function test_form_dokumen_page_accessible(): void
    {
        $response = $this->get('/dashboard/peserta/form-dokumen');
        $response->assertStatus(200);
    }

    public function test_save_dokumen_redirects_and_not_completed(): void
    {
        $response = $this->post('/dashboard/peserta/form-dokumen', [
            'pengetahuan_asep' => 'Beliau adalah seorang tokoh',
            'alasan_pelatihan' => 'Ingin menambah ilmu',
            'pengalaman_bisnis' => 'Sudah berjualan sejak 2020',
            'rencana_setelah_pelatihan' => 'Ingin membuka usaha',
            'punya_usaha' => 'Sudah',
            'jenis_usaha' => 'Kuliner',
            'usaha_dimiliki' => 'Belum Pernah',
            'nama_usaha' => 'Belum Pernah',
        ]);

        $response->assertRedirect(route('dashboard.peserta.form-review'));
        // is_completed should still be 0 (false) after saveDokumen
        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'is_completed' => 0,
        ]);
    }

    public function test_form_review_page_accessible(): void
    {
        // Ensure profile exists
        $this->post('/dashboard/peserta/form-dokumen', [
            'pengetahuan_asep' => 'Test',
            'alasan_pelatihan' => 'Test',
            'pengalaman_bisnis' => 'Test',
            'rencana_setelah_pelatihan' => 'Test',
            'punya_usaha' => 'Sudah',
            'jenis_usaha' => 'Kuliner',
            'usaha_dimiliki' => 'Belum Pernah',
            'nama_usaha' => 'Belum Pernah',
        ]);

        $response = $this->get('/dashboard/peserta/form-review');
        $response->assertStatus(200);
    }

    public function test_submit_final_redirects_and_marks_completed(): void
    {
        // Setup: save dokumen first
        $this->post('/dashboard/peserta/form-dokumen', [
            'pengetahuan_asep' => 'Test',
            'alasan_pelatihan' => 'Test',
            'pengalaman_bisnis' => 'Test',
            'rencana_setelah_pelatihan' => 'Test',
            'punya_usaha' => 'Sudah',
            'jenis_usaha' => 'Kuliner',
            'usaha_dimiliki' => 'Belum Pernah',
            'nama_usaha' => 'Belum Pernah',
        ]);

        $response = $this->post('/dashboard/peserta/form-review', [
            'konfirmasi' => '1',
        ]);

        $response->assertRedirect(route('dashboard.peserta.pendaftaran-sukses'));
        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'is_completed' => 1,
        ]);
    }

    public function test_submit_final_fails_without_confirmation(): void
    {
        // Setup: save dokumen first
        $this->post('/dashboard/peserta/form-dokumen', [
            'pengetahuan_asep' => 'Test',
            'alasan_pelatihan' => 'Test',
            'pengalaman_bisnis' => 'Test',
            'rencana_setelah_pelatihan' => 'Test',
            'punya_usaha' => 'Sudah',
            'jenis_usaha' => 'Kuliner',
            'usaha_dimiliki' => 'Belum Pernah',
            'nama_usaha' => 'Belum Pernah',
        ]);

        $response = $this->post('/dashboard/peserta/form-review', []);

        $response->assertSessionHasErrors('konfirmasi');
        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'is_completed' => 0,
        ]);
    }

    public function test_status_pendaftaran_redirects_without_profile(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($user);

        $response = $this->get('/dashboard/peserta/status');

        $response->assertRedirect(route('dashboard.peserta'));
        $response->assertSessionHas('error');
    }

    public function test_status_pendaftaran_page_accessible_with_no_enrollment(): void
    {
        $response = $this->get('/dashboard/peserta/status');

        $response->assertStatus(200);
        $response->assertSee('Belum Mendaftar');
        $response->assertSee('Peserta Lengkap');
    }

    private function prepareStatusScenario(string $status, ?array $extra = []): Pelatihan
    {
        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Test Kelurahan',
            'kodepos' => '40172',
            'is_active' => true,
        ]);

        $pelatihan = Pelatihan::create([
            'nama' => 'Status Test Pelatihan',
            'batch' => 'STATUS-BATCH-' . strtoupper($status),
            'is_active' => true,
            'tanggal_mulai' => now()->addMonth()->format('Y-m-d'),
        ]);

        $this->peserta->kelurahan_id = $kelurahan->id;
        $this->peserta->save();

        PesertaProfile::where('user_id', $this->peserta->id)->update([
            'alamat_ktp' => 'Jl. Status No. 1',
            'kelurahan_id' => $kelurahan->id,
            'kelurahan' => $kelurahan->name,
            'kecamatan' => $kecamatan->name,
            'kota' => 'BANDUNG',
            'provinsi' => 'Jawa Barat',
            'pelatihan_id' => $pelatihan->id,
        ]);

        \App\Models\Enrollment::create(array_merge([
            'user_id' => $this->peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => $status,
        ], $extra));

        return $pelatihan;
    }

    public function test_status_pendaftaran_page_shows_pending(): void
    {
        $pelatihan = $this->prepareStatusScenario('pending');

        $response = $this->get('/dashboard/peserta/status');

        $response->assertStatus(200);
        $response->assertSee('Menunggu Verifikasi');
        $response->assertSee($pelatihan->nama);
        $response->assertSee('Test Kelurahan');
    }

    public function test_status_pendaftaran_page_shows_approved(): void
    {
        $pelatihan = $this->prepareStatusScenario('approved', ['approved_at' => now()]);

        $response = $this->get('/dashboard/peserta/status');

        $response->assertStatus(200);
        $response->assertSee('Disetujui');
        $response->assertSee('Pelatihan Dimulai');
        $response->assertSee($pelatihan->nama);
    }

    public function test_status_pendaftaran_page_shows_rejected(): void
    {
        $pelatihan = $this->prepareStatusScenario('rejected', [
            'rejected_at' => now(),
            'notes' => 'Dokumen tidak lengkap',
        ]);

        $response = $this->get('/dashboard/peserta/status');

        $response->assertStatus(200);
        $response->assertSee('Ditolak');
        $response->assertSee('Dokumen tidak lengkap');
        $response->assertSee('Pilih Pelatihan Lain');
        $response->assertSee($pelatihan->nama);
    }

    public function test_status_pendaftaran_page_shows_waitlist(): void
    {
        $pelatihan = $this->prepareStatusScenario('waitlist');

        $response = $this->get('/dashboard/peserta/status');

        $response->assertStatus(200);
        $response->assertSee('Cadangan (Waitlist)');
        $response->assertSee($pelatihan->nama);
    }

    // ===================== AC-005: Auto-approve dengan kuota penuh =====================

    public function test_auto_approve_dengan_kuota_penuh_jadi_waitlist(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Auto Approve Penuh',
            'batch' => 'BATCH-AUTO-1',
            'is_active' => true,
            'kuota' => 1,
            'auto_approve' => true,
        ]);

        // Isi kuota dengan 1 approved
        $userExisting = User::factory()->create(['role' => 'peserta']);
        \App\Models\Enrollment::create([
            'user_id' => $userExisting->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Siapkan user baru yang akan mendaftar
        $userBaru = User::factory()->create([
            'role' => 'peserta',
            'email' => 'baru@auto.test',
            'nik' => '3273010101000002',
            'whatsapp' => '6281234567891',
        ]);
        Sanctum::actingAs($userBaru);

        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Test Kel',
            'kodepos' => '40172',
            'is_active' => true,
        ]);

        PesertaProfile::create([
            'user_id' => $userBaru->id,
            'nama_lengkap' => 'Peserta Baru',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '15',
            'bulan_lahir' => '01',
            'tahun_lahir' => '2000',
            'nik' => '3273010101000002',
            'pelatihan_id' => $pelatihan->id,
            'is_completed' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-review', [
            'konfirmasi' => '1',
        ]);

        $response->assertRedirect(route('dashboard.peserta.pendaftaran-sukses'));

        $enrollment = \App\Models\Enrollment::where('user_id', $userBaru->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals('waitlist', $enrollment->status);
        $this->assertNull($enrollment->approved_at);
    }

    public function test_auto_approve_dengan_kuota_tersedia_jadi_approved(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Auto Approve Tersedia',
            'batch' => 'BATCH-AUTO-2',
            'is_active' => true,
            'kuota' => 5,
            'auto_approve' => true,
        ]);

        $userBaru = User::factory()->create([
            'role' => 'peserta',
            'email' => 'tersedia@auto.test',
            'nik' => '3273010101000003',
            'whatsapp' => '6281234567892',
        ]);
        Sanctum::actingAs($userBaru);

        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Test Kel 2',
            'kodepos' => '40173',
            'is_active' => true,
        ]);

        PesertaProfile::create([
            'user_id' => $userBaru->id,
            'nama_lengkap' => 'Peserta Tersedia',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '20',
            'bulan_lahir' => '05',
            'tahun_lahir' => '1999',
            'nik' => '3273010101000003',
            'pelatihan_id' => $pelatihan->id,
            'is_completed' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-review', [
            'konfirmasi' => '1',
        ]);

        $response->assertRedirect(route('dashboard.peserta.pendaftaran-sukses'));

        $enrollment = \App\Models\Enrollment::where('user_id', $userBaru->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals('approved', $enrollment->status);
        $this->assertNotNull($enrollment->approved_at);
    }

    public function test_auto_approve_dengan_kuota_null_jadi_approved(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Auto Approve Unlimited',
            'batch' => 'BATCH-AUTO-3',
            'is_active' => true,
            'kuota' => null,
            'auto_approve' => true,
        ]);

        // Isi dengan banyak approved (tidak terbatas)
        for ($i = 0; $i < 10; $i++) {
            $u = User::factory()->create(['role' => 'peserta']);
            \App\Models\Enrollment::create([
                'user_id' => $u->id,
                'pelatihan_id' => $pelatihan->id,
                'status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        $userBaru = User::factory()->create([
            'role' => 'peserta',
            'email' => 'unlimited@auto.test',
            'nik' => '3273010101000004',
            'whatsapp' => '6281234567893',
        ]);
        Sanctum::actingAs($userBaru);

        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Test Kel 3',
            'kodepos' => '40174',
            'is_active' => true,
        ]);

        PesertaProfile::create([
            'user_id' => $userBaru->id,
            'nama_lengkap' => 'Peserta Unlimited',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '10',
            'bulan_lahir' => '10',
            'tahun_lahir' => '1998',
            'nik' => '3273010101000004',
            'pelatihan_id' => $pelatihan->id,
            'is_completed' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-review', [
            'konfirmasi' => '1',
        ]);

        $response->assertRedirect(route('dashboard.peserta.pendaftaran-sukses'));

        $enrollment = \App\Models\Enrollment::where('user_id', $userBaru->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals('approved', $enrollment->status);
    }

    public function test_non_auto_approve_dengan_kuota_penuh_jadi_waitlist(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Non Auto Penuh',
            'batch' => 'BATCH-AUTO-4',
            'is_active' => true,
            'kuota' => 1,
            'auto_approve' => false,
        ]);

        $userExisting = User::factory()->create(['role' => 'peserta']);
        \App\Models\Enrollment::create([
            'user_id' => $userExisting->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $userBaru = User::factory()->create([
            'role' => 'peserta',
            'email' => 'nonauto@test.test',
            'nik' => '3273010101000005',
            'whatsapp' => '6281234567894',
        ]);
        Sanctum::actingAs($userBaru);

        $kecamatan = Kecamatan::first();
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Test Kel 4',
            'kodepos' => '40175',
            'is_active' => true,
        ]);

        PesertaProfile::create([
            'user_id' => $userBaru->id,
            'nama_lengkap' => 'Peserta Non Auto',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Medan',
            'tanggal_lahir' => '05',
            'bulan_lahir' => '12',
            'tahun_lahir' => '2001',
            'nik' => '3273010101000005',
            'pelatihan_id' => $pelatihan->id,
            'is_completed' => true,
        ]);

        $response = $this->post('/dashboard/peserta/form-review', [
            'konfirmasi' => '1',
        ]);

        $response->assertRedirect(route('dashboard.peserta.pendaftaran-sukses'));

        $enrollment = \App\Models\Enrollment::where('user_id', $userBaru->id)
            ->where('pelatihan_id', $pelatihan->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals('waitlist', $enrollment->status);
    }
}

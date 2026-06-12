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
            'jenis_kelamin' => 'Laki-laki',
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
            'jenis_kelamin' => 'Perempuan',
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
            'jenis_kelamin' => 'Laki-laki',
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

        $response->assertRedirect(route('dashboard.peserta.form-pendidikan'));
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

    public function test_save_dokumen_redirects_and_marks_completed(): void
    {
        $response = $this->post('/dashboard/peserta/form-dokumen', []);

        $response->assertRedirect(route('dashboard.peserta'));
        $this->assertDatabaseHas('peserta_profiles', [
            'user_id' => $this->peserta->id,
            'is_completed' => 1,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }
    }

    public function test_admin_dashboard_accessible_by_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->get('/dashboard/admin');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.admin');
    }

    public function test_peserta_dashboard_accessible_by_peserta(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($peserta);

        $response = $this->get('/dashboard/peserta');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.peserta');
    }

    public function test_peserta_dashboard_completeness_percentage(): void
    {
        $kecamatan = \App\Models\Kecamatan::create(['name' => 'Cicendo']);
        $kelurahan = \App\Models\Kelurahan::create([
            'kecamatan_id' => $kecamatan->id,
            'name' => 'Arjuna',
            'kodepos' => '40172',
            'is_active' => true,
        ]);
        
        $peserta = User::factory()->create([
            'role' => 'peserta',
            'kecamatan_id' => $kecamatan->id,
            'kelurahan_id' => $kelurahan->id,
        ]);
        
        $pelatihan = \App\Models\Pelatihan::create([
            'nama' => 'Test Training',
            'batch' => 'BATCH-TEST-100',
            'is_active' => true,
        ]);

        \App\Models\PesertaProfile::create([
            'user_id' => $peserta->id,
            'nama_lengkap' => 'Peserta Lengkap',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '15',
            'bulan_lahir' => 'Januari',
            'tahun_lahir' => '2000',
            'nik' => '3273010101000001',
            'alamat_ktp' => 'Jl. Test No. 123',
            'rt' => '001',
            'rw' => '002',
            'kelurahan_id' => $kelurahan->id,
            'kelurahan' => $kelurahan->name,
            'kecamatan' => $kecamatan->name,
            'kota' => 'BANDUNG',
            'provinsi' => 'Jawa Barat',
            'kodepos' => '40172',
            'whatsapp' => '6281234567890',
            'email' => 'peserta@test.com',
            'pendidikan_terakhir' => 'S1',
            'nama_institusi' => 'ITB',
            'tahun_lulus' => '2023',
            'status_pekerjaan' => 'Bekerja',
            'pelatihan_id' => $pelatihan->id,
            'is_completed' => true,
        ]);

        Sanctum::actingAs($peserta);

        $response = $this->get('/dashboard/peserta');
        $response->assertStatus(200);
        $response->assertViewHas('data', function($data) {
            return (int) $data['profileCompletion'] === 100;
        });
    }

    public function test_instruktur_dashboard_accessible_by_instruktur(): void
    {
        $instruktur = User::factory()->create(['role' => 'instruktur']);
        Sanctum::actingAs($instruktur);

        $response = $this->get('/dashboard/instruktur');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.instruktur');
    }

    public function test_koordinator_dashboard_accessible_by_koordinator(): void
    {
        $koordinator = User::factory()->create(['role' => 'koordinator']);
        Sanctum::actingAs($koordinator);

        $response = $this->get('/dashboard/koordinator');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.koordinator');
    }

    public function test_peserta_cannot_access_admin_dashboard(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($peserta);

        $response = $this->get('/dashboard/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_peserta_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->get('/dashboard/peserta');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.peserta');
    }

    public function test_admin_can_access_instruktur_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->get('/dashboard/instruktur');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.instruktur');
    }

    public function test_admin_can_access_koordinator_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->get('/dashboard/koordinator');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.koordinator');
    }

    public function test_guest_redirected_from_all_dashboards(): void
    {
        $response = $this->get('/dashboard/admin');
        $response->assertRedirect();

        $response = $this->get('/dashboard/peserta');
        $response->assertRedirect();

        $response = $this->get('/dashboard/instruktur');
        $response->assertRedirect();

        $response = $this->get('/dashboard/koordinator');
        $response->assertRedirect();
    }

    public function test_admin_can_access_instruktur_monitoring_route(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($admin);

        $pelatihan = \App\Models\Pelatihan::create([
            'nama' => 'Monitoring Test Admin',
            'batch' => 'BATCH-MON-ADMIN',
        ]);

        $response = $this->get("/instruktur/pelatihan/{$pelatihan->id}/monitoring");
        $response->assertStatus(200);
    }

    public function test_instruktur_can_access_instruktur_monitoring_route(): void
    {
        $instruktur = User::factory()->create(['role' => 'instruktur']);
        Sanctum::actingAs($instruktur);

        $pelatihan = \App\Models\Pelatihan::create([
            'nama' => 'Monitoring Test Instruktur',
            'batch' => 'BATCH-MON-INSTRUKTUR',
        ]);

        $response = $this->get("/instruktur/pelatihan/{$pelatihan->id}/monitoring");
        $response->assertStatus(200);
    }

    public function test_peserta_cannot_access_instruktur_monitoring_route(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        Sanctum::actingAs($peserta);

        $pelatihan = \App\Models\Pelatihan::create([
            'nama' => 'Monitoring Test Peserta',
            'batch' => 'BATCH-MON-PESERTA',
        ]);

        $response = $this->get("/instruktur/pelatihan/{$pelatihan->id}/monitoring");
        $response->assertStatus(403);
    }
}

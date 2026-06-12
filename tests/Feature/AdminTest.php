<?php

namespace Tests\Feature;

use App\Models\Dinas;
use App\Models\Faq;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@test.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->get('/dashboard/admin');
        $response->assertStatus(200);
        $response->assertViewIs('content.dashboard.admin');
    }

    // === DINAS CRUD ===

    public function test_admin_can_view_dinas_index(): void
    {
        $response = $this->get('/admin/dinas');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_dinas(): void
    {
        $response = $this->post('/admin/dinas', [
            'nama_dinas' => 'Dinas Test',
            'singkatan' => 'DT',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.dinas.index'));
        $this->assertDatabaseHas('dinas', ['nama_dinas' => 'Dinas Test']);
    }

    public function test_admin_can_update_dinas(): void
    {
        $dinas = Dinas::create(['nama_dinas' => 'Dinas Awal', 'singkatan' => 'DA', 'is_active' => true]);

        $response = $this->put('/admin/dinas/' . $dinas->id, [
            'nama_dinas' => 'Dinas Baru',
            'singkatan' => 'DB',
            'is_active' => true,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.dinas.index'));
        $this->assertDatabaseHas('dinas', ['nama_dinas' => 'Dinas Baru']);
    }

    public function test_admin_can_delete_dinas_without_pelatihan(): void
    {
        $dinas = Dinas::create(['nama_dinas' => 'Dinas Hapus Test', 'singkatan' => 'DH', 'is_active' => true]);

        $response = $this->delete('/admin/dinas/' . $dinas->id);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('admin.dinas.index'));
        $this->assertDatabaseMissing('dinas', ['id' => $dinas->id]);
    }

    public function test_admin_can_manage_kecamatan(): void
    {
        $response = $this->get('/admin/kecamatan');
        $response->assertStatus(200);

        $response = $this->post('/admin/kecamatan', ['name' => 'Kecamatan Test']);
        $response->assertRedirect(route('admin.kecamatan.index'));
        $this->assertDatabaseHas('kecamatans', ['name' => 'Kecamatan Test']);

        $kecamatan = Kecamatan::where('name', 'Kecamatan Test')->first();
        $response = $this->put('/admin/kecamatan/' . $kecamatan->id, ['name' => 'Kecamatan Update']);
        $response->assertRedirect(route('admin.kecamatan.index'));
        $this->assertDatabaseHas('kecamatans', ['name' => 'Kecamatan Update']);

        $response = $this->delete('/admin/kecamatan/' . $kecamatan->id);
        $response->assertRedirect(route('admin.kecamatan.index'));
        $this->assertDatabaseMissing('kecamatans', ['id' => $kecamatan->id]);
    }

    public function test_admin_can_manage_kelurahan(): void
    {
        $kecamatan = Kecamatan::create(['name' => 'Kec Untuk Kel']);

        $response = $this->get('/admin/kelurahan');
        $response->assertStatus(200);

        $response = $this->post('/admin/kelurahan', [
            'name' => 'Kelurahan Test',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.kelurahan.index'));
        $this->assertDatabaseHas('kelurahans', ['name' => 'KELURAHAN TEST']);

        $kelurahan = Kelurahan::where('name', 'KELURAHAN TEST')->first();
        $response = $this->put('/admin/kelurahan/' . $kelurahan->id, [
            'name' => 'Kelurahan Update',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.kelurahan.index'));
        $this->assertDatabaseHas('kelurahans', ['name' => 'KELURAHAN UPDATE']);
    }

    public function test_admin_can_manage_faqs(): void
    {
        $response = $this->get('/admin/faqs');
        $response->assertStatus(200);

        $response = $this->post('/admin/faqs', [
            'question' => 'Test Question?',
            'answer' => 'Test Answer.',
            'order' => 1,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'Test Question?']);

        $faq = Faq::where('question', 'Test Question?')->first();
        $response = $this->put('/admin/faqs/' . $faq->id, [
            'question' => 'Updated Question?',
            'answer' => 'Updated Answer.',
        ]);
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'Updated Question?']);

        $response = $this->delete('/admin/faqs/' . $faq->id);
        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_admin_can_manage_pelatihan(): void
    {
        $dinas = Dinas::factory()->create();

        $response = $this->get('/admin/pelatihan');
        $response->assertStatus(200);

        $response = $this->post('/admin/pelatihan', [
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-TEST-1',
            'deskripsi' => 'Deskripsi test',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);
        $response->assertRedirect(route('admin.pelatihan.index'));
        $this->assertDatabaseHas('pelatihan', ['batch' => 'BATCH-TEST-1']);
    }

    public function test_admin_can_view_peserta_list(): void
    {
        User::factory()->create(['role' => 'peserta']);

        $response = $this->get('/admin/peserta');
        $response->assertStatus(200);
    }

    public function test_admin_can_approve_koordinator(): void
    {
        $koordinator = User::factory()->create([
            'role' => 'koordinator',
            'is_active' => false,
        ]);

        $response = $this->post('/admin/koordinator/' . $koordinator->id . '/approve');
        $response->assertRedirect(route('admin.koordinator.pending'));

        $this->assertDatabaseHas('users', [
            'id' => $koordinator->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_reject_koordinator(): void
    {
        $koordinator = User::factory()->create([
            'role' => 'koordinator',
            'is_active' => false,
        ]);

        $response = $this->post('/admin/koordinator/' . $koordinator->id . '/reject');
        $response->assertRedirect(route('admin.koordinator.pending'));

        $this->assertDatabaseMissing('users', ['id' => $koordinator->id]);
    }

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create(['role' => 'peserta']);

        Sanctum::actingAs($user);

        $response = $this->get('/admin/dinas');
        $response->assertStatus(403);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Pelatihan;
use App\Models\PengumumanPelatihan;
use App\Models\User;
use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanPelatihanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $pesertaTerdaftar;
    protected User $pesertaTidakTerdaftar;
    protected Pelatihan $pelatihan;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat admin
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Buat peserta
        $this->pesertaTerdaftar = User::factory()->create([
            'role' => 'peserta',
        ]);
        $this->pesertaTidakTerdaftar = User::factory()->create([
            'role' => 'peserta',
        ]);

        // Buat Pelatihan
        $this->pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Uji Coba',
            'batch' => 'BATCH TEST',
            'deskripsi' => 'Deskripsi Uji Coba',
            'is_active' => true,
        ]);

        // Daftarkan pesertaTerdaftar ke pelatihan (dengan status Approved)
        Enrollment::create([
            'user_id' => $this->pesertaTerdaftar->id,
            'pelatihan_id' => $this->pelatihan->id,
            'status' => EnrollmentStatus::Approved,
        ]);
    }

    /**
     * 1. CRUD Admin (Web View)
     */
    public function test_admin_can_access_announcement_index_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengumuman.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_new_announcement_via_form(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pengumuman.store'), [
            'pelatihan_id' => $this->pelatihan->id,
            'judul' => 'Pengumuman Baru',
            'konten' => 'Isi pengumuman penting',
            'is_private' => true,
            'is_pinned' => true,
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumuman_pelatihans', [
            'judul' => 'Pengumuman Baru',
            'konten' => 'Isi pengumuman penting',
            'is_private' => true,
            'is_pinned' => true,
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_edit_existing_announcement(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Judul Awal',
            'konten' => 'Konten awal',
            'is_private' => false,
            'is_pinned' => false,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.pengumuman.update', $announcement->id), [
            'pelatihan_id' => $this->pelatihan->id,
            'judul' => 'Judul Baru',
            'konten' => 'Konten baru',
            'is_private' => true,
            'is_pinned' => true,
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumuman_pelatihans', [
            'id' => $announcement->id,
            'judul' => 'Judul Baru',
            'konten' => 'Konten baru',
            'is_private' => true,
            'is_pinned' => true,
        ]);
    }

    public function test_admin_can_delete_announcement(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Judul Hapus',
            'konten' => 'Konten hapus',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.pengumuman.destroy', $announcement->id));

        $response->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseMissing('pengumuman_pelatihans', [
            'id' => $announcement->id,
        ]);
    }

    /**
     * 2. Keamanan (Policy / Authorization)
     */
    public function test_non_admin_cannot_access_announcement_crud_routes(): void
    {
        // 403 Forbidden or Redirected
        $responseIndex = $this->actingAs($this->pesertaTerdaftar)->get(route('admin.pengumuman.index'));
        $responseIndex->assertStatus(403);

        $responseCreate = $this->actingAs($this->pesertaTerdaftar)->get(route('admin.pengumuman.create'));
        $responseCreate->assertStatus(403);

        $responseStore = $this->actingAs($this->pesertaTerdaftar)->post(route('admin.pengumuman.store'), [
            'judul' => 'Hacker Judul',
            'konten' => 'Hacker konten',
        ]);
        $responseStore->assertStatus(403);
    }

    public function test_guest_cannot_access_announcement_crud_routes(): void
    {
        $response = $this->get(route('admin.pengumuman.index'));
        $response->assertRedirect('/login');
    }

    public function test_registered_student_can_access_private_announcement(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Pengumuman Privat',
            'konten' => 'Hanya untuk siswa terdaftar',
            'is_private' => true,
        ]);

        // Cek via API privat
        $response = $this->actingAs($this->pesertaTerdaftar)
            ->getJson(route('pelatihan.pengumuman.privat', $this->pelatihan->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['judul' => 'Pengumuman Privat']);
    }

    public function test_unregistered_student_cannot_access_private_announcement(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Pengumuman Privat',
            'konten' => 'Hanya untuk siswa terdaftar',
            'is_private' => true,
        ]);

        // Cek via API privat
        $response = $this->actingAs($this->pesertaTidakTerdaftar)
            ->getJson(route('pelatihan.pengumuman.privat', $this->pelatihan->id));

        $response->assertStatus(403);
    }

    public function test_guest_cannot_access_private_announcement(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Pengumuman Privat',
            'konten' => 'Hanya untuk siswa terdaftar',
            'is_private' => true,
        ]);

        // Route getPrivateAnnouncements dilindungi middleware auth:sanctum
        $response = $this->getJson(route('pelatihan.pengumuman.privat', $this->pelatihan->id));
        $response->assertStatus(401);
    }

    public function test_anyone_including_guest_can_access_public_announcements(): void
    {
        $announcement = PengumumanPelatihan::create([
            'pelatihan_id' => $this->pelatihan->id,
            'user_id' => $this->admin->id,
            'judul' => 'Pengumuman Publik',
            'konten' => 'Untuk semua orang',
            'is_private' => false,
        ]);

        // Cek via API publik (tanpa login)
        $response = $this->getJson(route('pelatihan.pengumuman.publik', $this->pelatihan->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['judul' => 'Pengumuman Publik']);
    }

    /**
     * 3. API Endpoints
     */
    public function test_api_admin_crud_endpoints(): void
    {
        // Get Index
        $responseGet = $this->actingAs($this->admin)
            ->getJson('/api/admin/pengumuman-pelatihan');
        $responseGet->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data']);

        // Post Store
        $responsePost = $this->actingAs($this->admin)
            ->postJson('/api/admin/pengumuman-pelatihan', [
                'pelatihan_id' => $this->pelatihan->id,
                'user_id' => $this->admin->id,
                'judul' => 'API Judul',
                'konten' => 'API Konten',
                'is_private' => false,
            ]);
        $responsePost->assertStatus(201);
        $newId = $responsePost->json('data.id');

        // Put Update
        $responsePut = $this->actingAs($this->admin)
            ->putJson("/api/admin/pengumuman-pelatihan/{$newId}", [
                'judul' => 'API Judul Updated',
                'konten' => 'API Konten Updated',
            ]);
        $responsePut->assertStatus(200);

        // Delete Destroy
        $responseDelete = $this->actingAs($this->admin)
            ->deleteJson("/api/admin/pengumuman-pelatihan/{$newId}");
        $responseDelete->assertStatus(200);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Dinas;
use App\Models\Faq;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
        $peserta1 = User::factory()->create(['role' => 'peserta']);
        $peserta2 = User::factory()->create(['role' => 'peserta']);
        
        $dinas = Dinas::factory()->create();
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test A',
            'batch' => 'BATCH-TEST-A',
            'deskripsi' => 'Deskripsi test A',
            'dinas_id' => $dinas->id,
            'is_active' => true,
        ]);

        \App\Models\PesertaProfile::create([
            'user_id' => $peserta1->id,
            'nama_lengkap' => $peserta1->name,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '20',
            'bulan_lahir' => '06',
            'tahun_lahir' => '2000',
            'pelatihan_id' => $pelatihan->id,
        ]);

        $response = $this->get('/admin/peserta');
        $response->assertStatus(200);

        // Test AJAX request and filters
        $responseAll = $this->getJson('/admin/peserta?filter_pelatihan=all', ['X-Requested-With' => 'XMLHttpRequest']);
        $responseAll->assertStatus(200);
        $responseAll->assertJsonStructure(['rows', 'pagination']);

        $responseSudah = $this->getJson('/admin/peserta?filter_pelatihan=sudah', ['X-Requested-With' => 'XMLHttpRequest']);
        $responseSudah->assertStatus(200);
        $responseSudah->assertSee($peserta1->name);
        $responseSudah->assertDontSee($peserta2->name);

        $responseBelum = $this->getJson('/admin/peserta?filter_pelatihan=belum', ['X-Requested-With' => 'XMLHttpRequest']);
        $responseBelum->assertStatus(200);
        $responseBelum->assertSee($peserta2->name);
        $responseBelum->assertDontSee($peserta1->name);
    }

    public function test_admin_can_view_peserta_detail_with_profile(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        \App\Models\PesertaProfile::create([
            'user_id' => $peserta->id,
            'nama_lengkap' => $peserta->name,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '20',
            'bulan_lahir' => '06',
            'tahun_lahir' => '2000',
        ]);

        $response = $this->get('/admin/peserta/' . $peserta->id);
        $response->assertStatus(200);
        $response->assertSee($peserta->name);
    }

    public function test_admin_can_view_peserta_detail_without_profile(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        // Tanpa profile

        $response = $this->get('/admin/peserta/' . $peserta->id);
        $response->assertStatus(200);
        $response->assertSee($peserta->name);
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

    // === USER MANAGEMENT (Integrated) ===

    public function test_admin_can_view_users_index(): void
    {
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertViewIs('content.admin.users.index');
    }

    public function test_admin_can_search_and_filter_users(): void
    {
        $user1 = User::factory()->create([
            'name' => 'John Doe Search',
            'email' => 'john@search.com',
            'role' => 'instruktur',
            'is_active' => true,
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@smith.com',
            'role' => 'koordinator',
            'is_active' => false,
        ]);

        // Search match
        $response = $this->get('/admin/users?search=John');
        $response->assertStatus(200);
        $response->assertSee('John Doe Search');
        $response->assertDontSee('Jane Smith');

        // Role filter
        $response = $this->get('/admin/users?role=instruktur');
        $response->assertStatus(200);
        $response->assertSee('John Doe Search');
        $response->assertDontSee('Jane Smith');

        // Status filter
        $response = $this->get('/admin/users?status=0');
        $response->assertStatus(200);
        $response->assertSee('Jane Smith');
        $response->assertDontSee('John Doe Search');
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $user = User::factory()->create([
            'role' => 'instruktur',
            'is_active' => true,
        ]);

        $response = $this->patch('/admin/users/' . $user->id . '/toggle-status');
        $response->assertRedirect();
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_cannot_toggle_own_status(): void
    {
        $response = $this->patch('/admin/users/' . $this->admin->id . '/toggle-status');
        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta',
        ]);

        $response = $this->delete('/admin/users/' . $user->id);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $response = $this->delete('/admin/users/' . $this->admin->id);
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_reset_peserta_password_to_default_value(): void
    {
        $peserta = User::factory()->create([
            'name' => 'Peserta Uji',
            'role' => 'peserta',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->post(route('admin.users.reset-password', $peserta));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password peserta Peserta Uji telah direset ke default: pelatihanku2026');

        $this->assertTrue(Hash::check('pelatihanku2026', $peserta->fresh()->password));

        $this->assertDatabaseHas('activity_logs', [
            'subject_id' => $peserta->id,
            'description' => 'Password peserta Peserta Uji telah direset ke default: pelatihanku2026',
        ]);
    }

    public function test_admin_can_reset_non_peserta_password_to_phone_number(): void
    {
        $instruktur = User::factory()->create([
            'name' => 'Instruktur Uji',
            'role' => 'instruktur',
            'whatsapp' => '081234567890',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->post(route('admin.users.reset-password', $instruktur));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password Instruktur Uji telah direset ke nomor HP.');

        $this->assertTrue(Hash::check('081234567890', $instruktur->fresh()->password));

        $this->assertDatabaseHas('activity_logs', [
            'subject_id' => $instruktur->id,
            'description' => 'Password user Instruktur Uji telah direset ke nomor HP',
        ]);
    }

    public function test_admin_can_reset_all_peserta_passwords(): void
    {
        $peserta1 = User::factory()->create([
            'name' => 'Peserta Satu',
            'role' => 'peserta',
            'password' => Hash::make('passone'),
        ]);

        $peserta2 = User::factory()->create([
            'name' => 'Peserta Dua',
            'role' => 'peserta',
            'password' => Hash::make('passtwo'),
        ]);

        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'role' => 'admin',
            'password' => Hash::make('adminpass'),
        ]);

        $response = $this->post(route('admin.users.reset-all-peserta'));
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Berhasil mereset password 2 peserta ke default pelatihanku2026 secara massal.');

        $this->assertTrue(Hash::check('pelatihanku2026', $peserta1->fresh()->password));
        $this->assertTrue(Hash::check('pelatihanku2026', $peserta2->fresh()->password));
        $this->assertFalse(Hash::check('pelatihanku2026', $adminUser->fresh()->password));

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'updated',
            'subject_type' => 'User',
            'subject_name' => 'Semua Peserta',
            'description' => 'Mereset password semua peserta (2 peserta) ke default pelatihanku2026 secara massal',
        ]);
    }

    public function test_admin_cannot_reset_all_peserta_passwords_if_none_exist(): void
    {
        // Delete any participants that might have been created by setUp or factory, but RefreshDatabase is used, so it's fresh.
        User::where('role', 'peserta')->delete();

        $response = $this->post(route('admin.users.reset-all-peserta'));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tidak ada data peserta untuk direset.');
    }
}

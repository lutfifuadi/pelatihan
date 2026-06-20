<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peserta;
    private User $koordinator;
    private User $instruktur;
    private User $otherAdmin;
    private User $inactiveUser;

    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        // Setup Users
        $this->admin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@pelatihan.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->peserta = User::factory()->create([
            'name' => 'Budi Peserta',
            'email' => 'budi@peserta.com',
            'role' => 'peserta',
            'is_active' => true,
        ]);

        $this->koordinator = User::factory()->create([
            'name' => 'Andi Koordinator',
            'email' => 'andi@koordinator.com',
            'role' => 'koordinator',
            'is_active' => true,
        ]);

        $this->instruktur = User::factory()->create([
            'name' => 'Candra Instruktur',
            'email' => 'candra@instruktur.com',
            'role' => 'instruktur',
            'is_active' => true,
        ]);

        $this->otherAdmin = User::factory()->create([
            'name' => 'Other Admin',
            'email' => 'otheradmin@pelatihan.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->inactiveUser = User::factory()->create([
            'name' => 'Nonaktif User',
            'email' => 'nonaktif@peserta.com',
            'role' => 'peserta',
            'is_active' => false,
        ]);
    }

    /**
     * Test admin can take impersonation of a peserta user.
     */
    public function test_admin_can_impersonate_peserta()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->peserta));

        // Redirects to dashboard.peserta
        $response->assertRedirect(route('dashboard.peserta'));

        // Target user is now authenticated
        $this->assertEquals($this->peserta->id, Auth::guard('web')->id());

        // Session stores impersonator_id
        $this->assertEquals($this->admin->id, session('impersonator_id'));
    }

    /**
     * Test admin can take impersonation of an instruktur user.
     */
    public function test_admin_can_impersonate_instruktur()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->instruktur));

        // Redirects to dashboard.instruktur
        $response->assertRedirect(route('dashboard.instruktur'));

        // Target user is now authenticated
        $this->assertEquals($this->instruktur->id, Auth::guard('web')->id());

        // Session stores impersonator_id
        $this->assertEquals($this->admin->id, session('impersonator_id'));
    }

    /**
     * Test admin can take impersonation of a koordinator user.
     */
    public function test_admin_can_impersonate_koordinator()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->koordinator));

        // Redirects to dashboard.koordinator
        $response->assertRedirect(route('dashboard.koordinator'));

        // Target user is now authenticated
        $this->assertEquals($this->koordinator->id, Auth::guard('web')->id());

        // Session stores impersonator_id
        $this->assertEquals($this->admin->id, session('impersonator_id'));
    }

    /**
     * Test admin cannot impersonate themselves.
     */
    public function test_admin_cannot_impersonate_self()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->admin));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Anda tidak dapat meng-impersonate diri Anda sendiri.');
        $this->assertEquals($this->admin->id, Auth::guard('web')->id());
    }

    /**
     * Test admin cannot impersonate other admin.
     */
    public function test_admin_cannot_impersonate_other_admin()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->otherAdmin));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Anda tidak diperbolehkan meng-impersonate admin lain.');
        $this->assertEquals($this->admin->id, Auth::guard('web')->id());
    }

    /**
     * Test admin cannot impersonate inactive user.
     */
    public function test_admin_cannot_impersonate_inactive_user()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('admin.users.impersonate', $this->inactiveUser));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tidak dapat meng-impersonate pengguna yang tidak aktif.');
        $this->assertEquals($this->admin->id, Auth::guard('web')->id());
    }

    /**
     * Test non-admin cannot impersonate.
     */
    public function test_non_admin_cannot_impersonate()
    {
        $response = $this->actingAs($this->peserta, 'web')
            ->post(route('admin.users.impersonate', $this->koordinator));

        $response->assertStatus(403);
    }

    /**
     * Test leave impersonation.
     */
    public function test_can_leave_impersonation()
    {
        $this->actingAs($this->admin, 'web');

        // Impersonate
        $this->post(route('admin.users.impersonate', $this->peserta));

        $this->assertEquals($this->peserta->id, Auth::guard('web')->id());
        $this->assertEquals($this->admin->id, session('impersonator_id'));

        // Leave
        $response = $this->post(route('impersonate.leave'));

        // Redirect back to users table index
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Kembali ke Panel Administrator');

        // Impersonator is logged back in
        $this->assertEquals($this->admin->id, Auth::guard('web')->id());

        // Session value removed
        $this->assertFalse(session()->has('impersonator_id'));
    }

    /**
     * Test leaving when there is no impersonation active.
     */
    public function test_cannot_leave_when_not_impersonating()
    {
        $response = $this->actingAs($this->admin, 'web')
            ->post(route('impersonate.leave'));

        $response->assertRedirect('/home');
        $response->assertSessionHas('error', 'Tidak ada session impersonasi yang aktif.');
    }
}

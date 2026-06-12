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
}

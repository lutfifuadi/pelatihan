<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
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

    public function test_login_basic_route_redirects(): void
    {
        $response = $this->get('/auth/login-basic');
        $response->assertRedirect('/login');
    }

    public function test_register_basic_route_redirects(): void
    {
        $response = $this->get('/auth/register-basic');
        $response->assertRedirect('/register');
    }

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'nik' => '3273010101000001',
        ]);

        $response = $this->post('/login', [
            'nik' => '3273010101000001',
            'password' => 'password',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    public function test_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'peserta',
            'nik' => '3273010101000002',
        ]);

        $response = $this->post('/login', [
            'nik' => '3273010101000002',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_authenticated_user_can_access_protected_page(): void
    {
        $user = User::factory()->create([
            'role' => 'peserta',
        ]);

        Sanctum::actingAs($user);

        $response = $this->get('/dashboard/peserta');

        $response->assertStatus(200);
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get('/dashboard/peserta');

        $response->assertRedirect('/login');
    }
}

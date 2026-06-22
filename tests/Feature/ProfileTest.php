<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileTest extends TestCase
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

    public function test_user_profile_page_is_accessible(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $response = $this->get('/user/profile');

        $response->assertStatus(200);
        $response->assertSee('Profile Information');
        $response->assertSee('Update Password');
    }
}

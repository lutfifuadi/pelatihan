<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pelatihan;
use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PesertaDashboardQrCodeTest extends TestCase
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

    public function test_peserta_can_generate_attendance_token_if_confirmed(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Laravel Specialist',
            'batch' => 'BATCH-01',
            'is_active' => true,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => EnrollmentStatus::Confirmed,
        ]);

        Sanctum::actingAs($peserta);

        // Akses route web baru untuk generate token presensi
        $response = $this->get("/peserta/attendance-token/{$pelatihan->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'qr_token',
            'expires_in',
            'expire_at',
        ]);
    }

    public function test_peserta_cannot_generate_attendance_token_if_not_confirmed(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Laravel Specialist',
            'batch' => 'BATCH-01',
            'is_active' => true,
        ]);

        $enrollment = Enrollment::create([
            'user_id' => $peserta->id,
            'pelatihan_id' => $pelatihan->id,
            'status' => EnrollmentStatus::Pending, // Status pending/tidak confirmed
        ]);

        Sanctum::actingAs($peserta);

        $response = $this->get("/peserta/attendance-token/{$pelatihan->id}");

        $response->assertStatus(403);
    }

    public function test_peserta_cannot_generate_attendance_token_for_non_existent_enrollment(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Laravel Specialist',
            'batch' => 'BATCH-01',
            'is_active' => true,
        ]);

        Sanctum::actingAs($peserta);

        $response = $this->get("/peserta/attendance-token/{$pelatihan->id}");

        $response->assertStatus(404);
    }
}

<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelatihanKuotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_kuota_penuh_returns_true_when_approved_reaches_quota(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-001',
            'kuota' => 2,
            'is_active' => true,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Enrollment::create(['user_id' => $user1->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);
        Enrollment::create(['user_id' => $user2->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);

        $this->assertTrue($pelatihan->fresh()->isKuotaPenuh());
    }

    public function test_is_kuota_penuh_returns_false_when_approved_is_below_quota(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-002',
            'kuota' => 5,
            'is_active' => true,
        ]);

        $user1 = User::factory()->create();
        Enrollment::create(['user_id' => $user1->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);

        $this->assertFalse($pelatihan->fresh()->isKuotaPenuh());
    }

    public function test_is_kuota_penuh_returns_false_when_kuota_is_null(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-003',
            'kuota' => null,
            'is_active' => true,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $user = User::factory()->create();
            Enrollment::create(['user_id' => $user->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);
        }

        $this->assertFalse($pelatihan->fresh()->isKuotaPenuh());
    }

    public function test_sisa_kuota_returns_correct_remaining(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-004',
            'kuota' => 10,
            'is_active' => true,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        Enrollment::create(['user_id' => $user1->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);
        Enrollment::create(['user_id' => $user2->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);
        Enrollment::create(['user_id' => $user3->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'pending']);

        $this->assertEquals(8, $pelatihan->fresh()->sisaKuota());
    }

    public function test_sisa_kuota_returns_zero_when_full(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-005',
            'kuota' => 1,
            'is_active' => true,
        ]);

        $user = User::factory()->create();
        Enrollment::create(['user_id' => $user->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);

        $this->assertEquals(0, $pelatihan->fresh()->sisaKuota());
    }

    public function test_sisa_kuota_returns_max_int_when_kuota_is_null(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-006',
            'kuota' => null,
            'is_active' => true,
        ]);

        $this->assertEquals(PHP_INT_MAX, $pelatihan->fresh()->sisaKuota());
    }

    public function test_sisa_kuota_never_negative(): void
    {
        $pelatihan = Pelatihan::create([
            'nama' => 'Pelatihan Test',
            'batch' => 'BATCH-007',
            'kuota' => 1,
            'is_active' => true,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Enrollment::create(['user_id' => $user1->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);
        Enrollment::create(['user_id' => $user2->id, 'pelatihan_id' => $pelatihan->id, 'status' => 'approved', 'approved_at' => now()]);

        $this->assertEquals(0, $pelatihan->fresh()->sisaKuota());
    }
}

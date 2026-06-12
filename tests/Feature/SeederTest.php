<?php

namespace Tests\Feature;

use App\Models\Dinas;
use App\Models\Faq;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pelatihan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeederTest extends TestCase
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

    public function test_database_seeder_runs_without_errors(): void
    {
        $exitCode = Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);

        $this->assertEquals(0, $exitCode);
    }

    public function test_seeded_data_has_correct_counts(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertCount(30, Kecamatan::all(), 'Seharusnya ada 30 kecamatan');
        $this->assertCount(5, Dinas::all(), 'Seharusnya ada 5 dinas');
        $this->assertCount(4, Faq::all(), 'Seharusnya ada 4 FAQ');
    }

    public function test_seeded_data_has_expected_records(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@pelatihan.test', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'instruktur@pelatihan.test', 'role' => 'instruktur']);
        $this->assertDatabaseHas('users', ['email' => 'peserta@demo.test', 'role' => 'peserta']);

        $this->assertDatabaseHas('kecamatans', ['name' => 'Cicendo']);
        $this->assertDatabaseHas('kecamatans', ['name' => 'Coblong']);

        $this->assertDatabaseHas('dinas', ['singkatan' => 'Disnakertrans']);
        $this->assertDatabaseHas('dinas', ['singkatan' => 'Disdikbud']);
    }

    public function test_kelurahan_are_linked_to_kecamatan(): void
    {
        $this->seed(DatabaseSeeder::class);

        $kecamatan = Kecamatan::where('name', 'Cicendo')->first();
        $this->assertNotNull($kecamatan);

        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan->id)->get();
        $this->assertGreaterThan(0, $kelurahans->count());
    }

    public function test_pelatihan_seeder_creates_training_programs(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('pelatihan', ['batch' => 'BATCH 1']);
        $this->assertDatabaseHas('pelatihan', ['batch' => 'BATCH 2']);
        $this->assertDatabaseHas('pelatihan', ['batch' => 'BATCH 3']);

        $this->assertGreaterThanOrEqual(3, Pelatihan::count());
    }

    public function test_peserta_demo_seeder_writes_data_file(): void
    {
        $this->seed(DatabaseSeeder::class);

        $filePath = storage_path('app/demo/data-user.txt');
        $this->assertFileExists($filePath);

        $content = file_get_contents($filePath);
        $this->assertStringContainsString('Peserta Demo', $content);
        $this->assertStringContainsString('3273010101000001', $content);
    }
}

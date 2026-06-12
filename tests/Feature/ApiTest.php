<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
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

    public function test_api_kelurahan_returns_json(): void
    {
        $kecamatan = Kecamatan::create(['name' => 'Cicendo']);
        Kelurahan::create([
            'name' => 'Pasirkaliki',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);
        Kelurahan::create([
            'name' => 'Pajajaran',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);

        $response = $this->get('/api/kelurahan?kecamatan_id=' . $kecamatan->id);

        $response->assertJsonCount(2);
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);
    }

    public function test_api_kelurahan_returns_empty_without_kecamatan_id(): void
    {
        $response = $this->get('/api/kelurahan');

        $response->assertJson([]);
    }

    public function test_sitemap_xml_accessible(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
    }

    public function test_robots_txt_accessible(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
    }
}

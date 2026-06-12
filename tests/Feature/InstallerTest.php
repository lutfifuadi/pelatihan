<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstallerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $installed = storage_path('installed');
        if (file_exists($installed)) {
            unlink($installed);
        }
    }

    protected function tearDown(): void
    {
        $progressFile = storage_path('framework/install-progress.json');
        if (file_exists($progressFile)) {
            unlink($progressFile);
        }

        parent::tearDown();
    }

    public function test_install_step1_returns_200(): void
    {
        $response = $this->get('/install');

        $response->assertStatus(200);
        $response->assertViewIs('installer.step1');
    }

    public function test_install_step2_submit_with_valid_data(): void
    {
        $response = $this->post('/install/step2', [
            'db_host' => '127.0.0.1',
            'db_port' => '3306',
            'db_name' => 'test_db',
            'db_user' => 'root',
            'db_password' => '',
        ]);

        $response->assertRedirect(route('installer.step3'));
        $this->assertEquals('127.0.0.1', session('install_db_host'));
        $this->assertEquals('test_db', session('install_db_name'));
    }

    public function test_install_step2_test_connection_returns_json(): void
    {
        $response = $this->post('/install/step2/test', [
            'db_host' => '127.0.0.1',
            'db_port' => '3306',
            'db_name' => 'test_db',
            'db_user' => 'root',
            'db_password' => '',
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    public function test_install_progress_returns_json(): void
    {
        $response = $this->get('/install/progress');

        $response->assertJsonStructure([
            'step',
            'label',
        ]);
        $response->assertJson([
            'step' => 0,
            'label' => 'Memulai...',
        ]);
    }

    public function test_install_step2_submit_without_db_name_fails(): void
    {
        $response = $this->post('/install/step2', [
            'db_host' => '127.0.0.1',
            'db_port' => '3306',
            'db_user' => 'root',
        ]);

        $response->assertSessionHasErrors(['db_name']);
    }
}

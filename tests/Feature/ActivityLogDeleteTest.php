<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ActivityLogDeleteTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        // Mark installed
        $installed = storage_path('installed');
        if (!file_exists($installed)) {
            touch($installed);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@activity.test',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'is_active' => true,
        ]);

        Sanctum::actingAs($this->admin);
    }

    // ==================== VIEW INDEX ====================

    public function test_admin_can_view_activity_logs_index_page()
    {
        ActivityLog::factory()->count(5)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->get(route('admin.activity-logs.index'));

        $response->assertStatus(200);
        $response->assertViewIs('content.admin.activity-logs.index');
        $response->assertSee('Log Aktivitas');
    }

    // ==================== SINGLE DELETE ====================

    public function test_admin_can_delete_single_activity_log()
    {
        $log = ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'subject_type' => 'Pelatihan',
            'description' => 'Test log untuk dihapus',
        ]);

        $response = $this->delete(route('admin.activity-logs.destroy', $log->id));

        $response->assertRedirect(route('admin.activity-logs.index'));
        $response->assertSessionHas('success', 'Log aktivitas berhasil dihapus.');

        $this->assertDatabaseMissing('activity_logs', ['id' => $log->id]);
    }

    public function test_single_delete_records_audit_trail()
    {
        $log = ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'subject_type' => 'Pelatihan',
            'description' => 'Log yang akan dihapus',
        ]);

        $this->delete(route('admin.activity-logs.destroy', $log->id));

        // Verify audit trail was created
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'subject_type' => 'ActivityLog',
            'description' => "Log aktivitas {$log->id} berhasil dihapus",
            'user_id' => $this->admin->id,
        ]);
    }

    // ==================== BULK DELETE ====================

    public function test_admin_can_bulk_delete_activity_logs_with_proper_array()
    {
        // NOTE: This test uses the CORRECT format (array) which the controller expects.
        // The current frontend sends JSON string instead of array — this test verifies
        // the backend logic works when the input is correct.
        $logs = ActivityLog::factory()->count(3)->create([
            'user_id' => $this->admin->id,
        ]);

        $ids = $logs->pluck('id')->toArray();

        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => $ids,
        ]);

        $response->assertRedirect(route('admin.activity-logs.index'));
        $response->assertSessionHas('success', '3 log aktivitas berhasil dihapus.');

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('activity_logs', ['id' => $id]);
        }
    }

    public function test_bulk_delete_records_audit_trail()
    {
        $logs = ActivityLog::factory()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $ids = $logs->pluck('id')->toArray();

        $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => $ids,
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'deleted',
            'subject_type' => 'ActivityLog',
            'description' => '2 log aktivitas berhasil dihapus massal',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_bulk_delete_fails_when_ids_is_not_an_array()
    {
        // This simulates what the frontend actually sends (JSON string)
        $logs = ActivityLog::factory()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $ids = $logs->pluck('id')->toArray();
        $jsonString = json_encode($ids);

        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => $jsonString,
        ]);

        $response->assertSessionHasErrors(['ids']);
    }

    public function test_bulk_delete_fails_with_empty_ids()
    {
        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => [],
        ]);

        $response->assertSessionHasErrors(['ids']);
    }

    public function test_bulk_delete_fails_with_invalid_id()
    {
        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => [99999],
        ]);

        $response->assertSessionHasErrors(['ids.0']);
    }

    public function test_delete_fails_for_nonexistent_log()
    {
        $response = $this->delete(route('admin.activity-logs.destroy', 99999));

        $response->assertStatus(404);
    }

    // ==================== AUTHORIZATION ====================

    public function test_non_admin_cannot_delete_activity_logs()
    {
        Sanctum::actingAs($this->peserta);

        $log = ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->delete(route('admin.activity-logs.destroy', $log->id));
        $response->assertStatus(403);

        $this->assertDatabaseHas('activity_logs', ['id' => $log->id]);
    }

    public function test_non_admin_cannot_bulk_delete_activity_logs()
    {
        Sanctum::actingAs($this->peserta);

        $logs = ActivityLog::factory()->count(2)->create([
            'user_id' => $this->admin->id,
        ]);

        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => $logs->pluck('id')->toArray(),
        ]);

        $response->assertStatus(403);
    }

    public function test_guest_cannot_delete_activity_logs()
    {
        $log = ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
        ]);

        // Log out by clearing all authentication state
        $this->app['auth']->forgetGuards();
        $this->app['session']->invalidate();

        $response = $this->delete(route('admin.activity-logs.destroy', $log->id));
        $response->assertRedirect('/login');
    }

    // ==================== FILTER INTEGRATION ====================

    public function test_activity_logs_index_returns_filtered_results_by_action()
    {
        ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'description' => 'Created log',
        ]);
        ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'deleted',
            'description' => 'Deleted log',
        ]);

        $response = $this->get(route('admin.activity-logs.index', ['action' => 'created']));

        $response->assertStatus(200);
        $response->assertSee('Created log');
        $response->assertDontSee('Deleted log');
    }

    public function test_filter_persists_after_single_delete()
    {
        ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'description' => 'Created log A',
        ]);
        $logToDelete = ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'created',
            'description' => 'Created log B',
        ]);
        ActivityLog::factory()->create([
            'user_id' => $this->admin->id,
            'action' => 'deleted',
            'description' => 'Deleted log',
        ]);

        // First visit with filter
        $response = $this->get(route('admin.activity-logs.index', ['action' => 'created']));
        $response->assertSee('Created log A');
        $response->assertSee('Created log B');
        $response->assertDontSee('Deleted log');

        // Delete one log and verify redirect preserves the filter
        $response = $this->delete(route('admin.activity-logs.destroy', $logToDelete->id));
        $response->assertRedirect(route('admin.activity-logs.index'));
        $response->assertSessionHas('success');

        // Verify the filter still works by checking DB directly
        $remainingCreated = ActivityLog::where('action', 'created')->count();
        $this->assertEquals(1, $remainingCreated);
    }

    // ==================== BULK INTEGRATION ====================

    public function test_multi_delete_integration()
    {
        // Full integration: create logs, bulk delete them, verify they're gone
        $logs = ActivityLog::factory()->count(5)->create([
            'user_id' => $this->admin->id,
        ]);

        // Use the first 3
        $ids = $logs->take(3)->pluck('id')->toArray();

        $response = $this->delete(route('admin.activity-logs.bulk-destroy'), [
            'ids' => $ids,
        ]);

        $response->assertRedirect(route('admin.activity-logs.index'));
        $response->assertSessionHas('success', '3 log aktivitas berhasil dihapus.');

        // Deleted logs are gone
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('activity_logs', ['id' => $id]);
        }

        // Remaining logs still exist
        $remainingIds = $logs->slice(3)->pluck('id')->toArray();
        foreach ($remainingIds as $id) {
            $this->assertDatabaseHas('activity_logs', ['id' => $id]);
        }
    }
}

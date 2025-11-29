<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_index_and_ajax_fragment()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // create some logs
        AuditLog::create([ 'user_id' => $admin->id, 'action' => 'create', 'model' => 'User', 'model_id' => 1, 'changes' => ['name' => 'foo'], 'ip_address' => '127.0.0.1' ]);
        AuditLog::create([ 'user_id' => $admin->id, 'action' => 'update', 'model' => 'Restaurante', 'model_id' => 2, 'changes' => ['name' => 'bar'], 'ip_address' => '127.0.0.1' ]);

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('Logs de Auditoria');

        // AJAX fragment
        $ajax = $this->actingAs($admin)->get(route('admin.audit-logs.index'), ['X-Requested-With' => 'XMLHttpRequest']);
        $ajax->assertStatus(200);
        $ajax->assertSee('logs-table-container');
    }

    public function test_destroy_and_bulk_destroy()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $a = AuditLog::create([ 'user_id' => $admin->id, 'action' => 'create', 'model' => 'User', 'model_id' => 1, 'changes' => ['name' => 'foo'], 'ip_address' => '127.0.0.1' ]);
        $b = AuditLog::create([ 'user_id' => $admin->id, 'action' => 'update', 'model' => 'Restaurante', 'model_id' => 2, 'changes' => ['name' => 'bar'], 'ip_address' => '127.0.0.1' ]);

        // single delete (include CSRF token for middleware)
        $res = $this->actingAs($admin)->delete(route('admin.audit-logs.destroy', $a->id), ['_token' => csrf_token()], ['X-Requested-With' => 'XMLHttpRequest']);
        $res->assertStatus(200)->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('audit_logs', ['id' => $a->id]);

        // bulk delete
        $ids = [$b->id];
        $bulk = $this->actingAs($admin)->post(route('admin.audit-logs.bulkDestroy'), ['ids' => $ids], ['X-Requested-With' => 'XMLHttpRequest']);
        $bulk->assertStatus(200)->assertJsonStructure(['message']);

        $this->assertDatabaseMissing('audit_logs', ['id' => $b->id]);
    }
}

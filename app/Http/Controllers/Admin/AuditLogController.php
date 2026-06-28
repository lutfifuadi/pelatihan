<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Tampilkan daftar audit log presensi.
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('actor')->orderBy('created_at', 'desc');

        // Filter based on search (actor name, target_entity, description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('target_entity', 'like', '%' . $search . '%')
                  ->orWhereHas('actor', function ($actorQuery) use ($search) {
                      $actorQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter based on action_type
        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        // Filter based on actor_role
        if ($request->filled('actor_role')) {
            $query->where('actor_role', $request->actor_role);
        }

        // Filter based on date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Paginate
        $logs = $query->paginate(25)->withQueryString();

        // Distinct option values for filters
        $actionTypes = ['create', 'update', 'delete', 'bypass', 'correct', 'export', 'login'];
        $actorRoles = ['admin', 'panitia', 'instruktur'];

        return view('content.admin.audit-logs.index', compact('logs', 'actionTypes', 'actorRoles'));
    }
}

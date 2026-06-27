<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan daftar log aktivitas admin.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->recent();

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by subject_type
        if ($request->filled('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('subject_name', 'like', '%' . $search . '%')
                  ->orWhere('subject_type', 'like', '%' . $search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        // Data untuk dropdown filter
        $actions = ActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $subjectTypes = ActivityLog::select('subject_type')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        return view('content.admin.activity-logs.index', compact('logs', 'actions', 'subjectTypes'));
    }

    /**
     * Hapus satu log aktivitas.
     */
    public function destroy($id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->delete();

        ActivityLogger::action('deleted', 'ActivityLog', "Log aktivitas {$log->id} berhasil dihapus");

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil dihapus.');
    }

    /**
     * Hapus massal log aktivitas.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:activity_logs,id',
        ]);

        $count = ActivityLog::whereIn('id', $request->ids)->delete();

        ActivityLogger::action('deleted', 'ActivityLog', "{$count} log aktivitas berhasil dihapus massal");

        return redirect()->route('admin.activity-logs.index')
            ->with('success', "{$count} log aktivitas berhasil dihapus.");
    }
}

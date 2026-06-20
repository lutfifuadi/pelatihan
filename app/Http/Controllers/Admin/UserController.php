<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user dengan pencarian, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $status = $request->get('status');

        $query = User::query();

        // Pencarian: name, email, nik, whatsapp
        $query->when($search, function ($q, $search) {
            $q->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('whatsapp', 'like', '%' . $search . '%');
            });
        });

        // Filter role: admin, instruktur, koordinator, peserta
        $query->when($request->filled('role'), function ($q) use ($role) {
            $q->where('role', $role);
        });

        // Filter status: is_active
        if ($request->filled('status')) {
            if ($status === '1' || $status === 'active') {
                $query->where('is_active', 1);
            } elseif ($status === '0' || $status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        $users = $query->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $html = view('content.admin.users._table', compact('users'))->render();
            return response()->json([
                'html' => $html
            ]);
        }

        return view('content.admin.users.index', compact('users', 'search', 'role', 'status'));
    }

    /**
     * Ubah status aktif/nonaktif user.
     */
    public function toggleStatus(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak bisa menonaktifkan akun Anda sendiri.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        $oldData = $user->getAttributes();
        $user->is_active = !$user->is_active;
        $user->save();
        $newData = $user->getAttributes();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        // Log aktivitas jika ada ActivityLogger
        ActivityLogger::log(
            action: 'updated',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $user->name,
            description: "Status user {$user->name} {$statusText}",
            oldValues: $oldData,
            newValues: $newData
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status user {$user->name} berhasil {$statusText}.",
                'is_active' => $user->is_active
            ]);
        }

        return back()->with('success', "Status user {$user->name} berhasil {$statusText}.");
    }

    /**
     * Hapus user secara permanen.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $oldData = $user->getAttributes();
        $name = $user->name;
        $user->delete();

        // Log aktivitas
        ActivityLogger::log(
            action: 'deleted',
            subjectType: 'User',
            subjectId: $user->id,
            subjectName: $name,
            description: "User {$name} berhasil dihapus permanen",
            oldValues: $oldData
        );

        return redirect()->route('admin.users.index')
            ->with('success', "User {$name} berhasil dihapus.");
    }
}

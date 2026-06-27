<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kecamatan;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KoordinatorController extends Controller
{
    /**
     * Display a listing of koordinator users.
     */
    public function index()
    {
        $koordinators = User::where('role', 'koordinator')
            ->with('kecamatan')
            ->orderBy('name')
            ->paginate(15);

        return view('content.admin.koordinator.index', compact('koordinators'));
    }

    /**
     * Show the form for creating a new koordinator.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.admin.koordinator.create', compact('kecamatans'));
    }

    /**
     * Store a newly created koordinator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'nik' => 'nullable|string|digits_between:15,16|unique:users,nik',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email ?? $request->nik . '@koordinator.pelatihanku.app',
            'password' => Hash::make($request->password),
            'role' => 'koordinator',
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'nik' => $request->nik,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        event(new \App\Events\DashboardUpdated());

        return redirect()->route('admin.koordinator.index')
            ->with('success', 'Koordinator berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified koordinator.
     */
    public function edit(User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            abort(404);
        }

        $kecamatans = Kecamatan::orderBy('name')->get();
        return view('content.admin.koordinator.edit', compact('koordinator', 'kecamatans'));
    }

    /**
     * Update the specified koordinator.
     */
    public function update(Request $request, User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $koordinator->id,
            'password' => 'nullable|string|min:8',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'nik' => 'nullable|string|digits_between:15,16|unique:users,nik,' . $koordinator->id,
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'phone' => $request->phone,
            'whatsapp' => $request->whatsapp,
            'nik' => $request->nik,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $koordinator->update($data);

        event(new \App\Events\DashboardUpdated());

        return redirect()->route('admin.koordinator.index')
            ->with('success', 'Koordinator berhasil diperbarui.');
    }

    /**
     * Tampilkan daftar koordinator yang menunggu approval (pending).
     */
    public function pending()
    {
        $koordinators = User::where('role', 'koordinator')
            ->where('is_active', false)
            ->with('kecamatan')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('content.admin.koordinator.pending', compact('koordinators'));
    }

    /**
     * Approve (aktifkan) koordinator.
     */
    public function approve(User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            abort(404);
        }

        $koordinator->update(['is_active' => true]);

        ActivityLogger::action('approved', 'Koordinator', "Koordinator {$koordinator->name} berhasil diaktifkan", $koordinator->id, $koordinator->name);

        event(new \App\Events\DashboardUpdated());

        return redirect()->route('admin.koordinator.pending')
            ->with('success', 'Koordinator ' . $koordinator->name . ' berhasil diaktifkan.');
    }

    /**
     * Tolak / hapus koordinator yang masih pending.
     */
    public function reject(User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            abort(404);
        }

        $nama = $koordinator->name;
        $koordinator->delete();

        ActivityLogger::action('rejected', 'Koordinator', "Pendaftaran koordinator {$nama} ditolak dan dihapus", $koordinator->id, $nama);

        event(new \App\Events\DashboardUpdated());

        return redirect()->route('admin.koordinator.pending')
            ->with('success', 'Pendaftaran koordinator ' . $nama . ' ditolak dan dihapus.');
    }

    /**
     * Remove the specified koordinator.
     */
    public function destroy(User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            abort(404);
        }

        $koordinator->delete();

        event(new \App\Events\DashboardUpdated());

        return redirect()->route('admin.koordinator.index')
            ->with('success', 'Koordinator berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif koordinator.
     * 
     * @param User $koordinator
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(User $koordinator)
    {
        if ($koordinator->role !== 'koordinator') {
            return response()->json([
                'success' => false,
                'message' => 'User yang dipilih bukan koordinator.',
            ], 404);
        }

        $oldStatus = $koordinator->is_active;
        $newStatus = !$oldStatus;

        $koordinator->update(['is_active' => $newStatus]);

        $statusLabel = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::action(
            'updated',
            'Koordinator',
            "Koordinator {$koordinator->name} berhasil {$statusLabel} oleh " . auth()->user()->name,
            $koordinator->id,
            $koordinator->name
        );

        event(new \App\Events\DashboardUpdated());

        return response()->json([
            'success' => true,
            'is_active' => $koordinator->fresh()->is_active,
            'message' => "Koordinator {$koordinator->name} berhasil {$statusLabel}.",
        ]);
    }
}

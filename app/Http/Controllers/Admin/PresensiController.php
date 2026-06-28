<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Tampilkan daftar pelatihan untuk rekap/kelola presensi.
     */
    public function index(Request $request)
    {
        $query = Pelatihan::with('dinas')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });

        // Urutkan default berdasarkan created_at desc, atau sesuaikan filter
        $pelatihans = $query->latest()->paginate(15)->withQueryString();

        return view('content.admin.presensi.index', compact('pelatihans'));
    }

    /**
     * Tampilkan rekapitulasi kehadiran per pertemuan (hanya status confirmed).
     */
    public function show(Pelatihan $pelatihan)
    {
        $pelatihan->load('dinas');

        // Mengambil enrollment yang status = confirmed saja, diurutkan berdasarkan nama user
        $enrollments = Enrollment::with(['user', 'attendances'])
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'confirmed')
            ->whereHas('user', function ($q) {
                $q->orderBy('name', 'asc');
            })
            ->get()
            ->sortBy(function ($enrollment) {
                return $enrollment->user?->name;
            });

        // Ambil max pertemuan_ke yang ada di database, jika belum ada set default 3 pertemuan
        $maxPertemuan = Attendance::whereHas('enrollment', function ($q) use ($pelatihan) {
            $q->where('pelatihan_id', $pelatihan->id);
        })->max('pertemuan_ke') ?? 3;

        // Ensure minimum of 3 pertemuans or keep the max if more than 3
        $totalPertemuan = max(3, $maxPertemuan);
        $pertemuans = range(1, $totalPertemuan);

        return view('content.admin.presensi.show', compact('pelatihan', 'enrollments', 'totalPertemuan', 'pertemuans'));
    }

    /**
     * Koreksi data kehadiran manual & catat audit log.
     */
    public function koreksi(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'pertemuan_ke' => 'required|integer|min:1',
            'status' => 'required|in:hadir,sakit,izin,alpa',
            'bypass_reason' => 'required|string|max:255',
        ]);

        $enrollment = Enrollment::findOrFail($request->enrollment_id);
        
        // Cari record attendance lama
        $attendance = Attendance::where('enrollment_id', $request->enrollment_id)
            ->where('pertemuan_ke', $request->pertemuan_ke)
            ->first();

        $oldData = null;
        if ($attendance) {
            $oldData = $attendance->toArray();
            
            // Simpan status lama sebelum diupdate
            $oldStatus = $attendance->status;
            $attendance->status = $request->status;
            $attendance->save();
        } else {
            // Create baru jika belum ada record absensi untuk pertemuan ini
            $attendance = new Attendance();
            $attendance->enrollment_id = $request->enrollment_id;
            $attendance->pertemuan_ke = $request->pertemuan_ke;
            $attendance->status = $request->status;
            $attendance->date = now()->toDateString();
            $attendance->save();
        }

        $newData = $attendance->toArray();

        // Catat ke AuditLog
        AuditLog::create([
            'actor_id' => Auth::id(),
            'actor_role' => 'admin',
            'action_type' => 'correct',
            'target_entity' => 'attendance',
            'target_id' => $attendance->id,
            'description' => "Koreksi kehadiran peserta " . ($enrollment->user?->name ?? 'N/A') . " pada pertemuan ke-{$request->pertemuan_ke} menjadi " . ucfirst($request->status) . ". Alasan: {$request->bypass_reason}",
            'old_data' => $oldData,
            'new_data' => $newData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Status kehadiran berhasil dikoreksi.');
    }
}

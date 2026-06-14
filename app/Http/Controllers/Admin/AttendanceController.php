<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman absensi per pelatihan.
     */
    public function index(Pelatihan $pelatihan)
    {
        $pelatihan->load('dinas');

        $enrollments = Enrollment::with(['user.pesertaProfile', 'attendances'])
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPeserta = $enrollments->count();
        $totalHadir = $enrollments->filter(function ($e) {
            return $e->attendances->where('status', 'hadir')->isNotEmpty();
        })->count();

        // Hitung pertemuan terakhir + 1 untuk form
        $maxPertemuan = Attendance::whereHas('enrollment', function ($q) use ($pelatihan) {
            $q->where('pelatihan_id', $pelatihan->id);
        })->max('pertemuan_ke') ?? 0;

        $nextPertemuan = $maxPertemuan + 1;

        return view('content.admin.attendances.index', compact(
            'pelatihan', 'enrollments', 'totalPeserta', 'totalHadir', 'nextPertemuan'
        ));
    }

    /**
     * Simpan absensi untuk satu pertemuan.
     */
    public function store(Request $request, Pelatihan $pelatihan)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer|min:1',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.enrollment_id' => 'required|exists:enrollments,id',
            'attendances.*.status' => 'required|in:hadir,sakit,izin,alpa',
        ]);

        $pertemuanKe = $request->pertemuan_ke;
        $date = $request->date;

        DB::transaction(function () use ($request, $pertemuanKe, $date, $pelatihan) {
            foreach ($request->attendances as $data) {
                // Cek apakah sudah ada absensi untuk pertemuan ini
                $existing = Attendance::where('enrollment_id', $data['enrollment_id'])
                    ->where('pertemuan_ke', $pertemuanKe)
                    ->first();

                if ($existing) {
                    // Update jika sudah ada
                    $existing->update([
                        'status' => $data['status'],
                        'date' => $date,
                    ]);
                } else {
                    // Buat baru
                    Attendance::create([
                        'enrollment_id' => $data['enrollment_id'],
                        'pertemuan_ke' => $pertemuanKe,
                        'status' => $data['status'],
                        'date' => $date,
                    ]);
                }
            }
        });

        return redirect()->route('admin.attendances.index', $pelatihan)
            ->with('success', "Absensi pertemuan ke-{$pertemuanKe} berhasil disimpan.");
    }

    /**
     * Tampilkan rekapitulasi absensi.
     */
    public function rapport(Pelatihan $pelatihan)
    {
        $pelatihan->load('dinas');

        $enrollments = Enrollment::with(['user.pesertaProfile', 'attendances'])
            ->where('pelatihan_id', $pelatihan->id)
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPertemuan = Attendance::whereHas('enrollment', function ($q) use ($pelatihan) {
            $q->where('pelatihan_id', $pelatihan->id);
        })->max('pertemuan_ke') ?? 0;

        $pertemuans = range(1, $totalPertemuan);

        return view('content.admin.attendances.rapport', compact(
            'pelatihan', 'enrollments', 'totalPertemuan', 'pertemuans'
        ));
    }
}

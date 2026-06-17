<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Tampilkan halaman kalender jadwal.
     */
    public function index()
    {
        $pelatihans = Pelatihan::where('is_active', true)->orderBy('nama')->get();
        return view('content.admin.schedules.index', compact('pelatihans'));
    }

    /**
     * Return JSON events untuk FullCalendar.
     */
    public function data(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $events = collect();

        // 1. Ambil jadwal dari tabel schedules
        $schedulesQuery = Schedule::with('pelatihan')->active();

        if ($start && $end) {
            $schedulesQuery->whereBetween('tanggal', [$start, $end]);
        }

        $schedules = $schedulesQuery->get();

        foreach ($schedules as $schedule) {
            $startDateTime = $schedule->tanggal->format('Y-m-d');
            $endDateTime = $schedule->tanggal->format('Y-m-d');

            if ($schedule->waktu_mulai) {
                $startDateTime .= 'T' . Carbon::parse($schedule->waktu_mulai)->format('H:i:s');
            }

            if ($schedule->waktu_selesai) {
                $endDateTime .= 'T' . Carbon::parse($schedule->waktu_selesai)->format('H:i:s');
            } else {
                // Default end to start + 2 hours if no end time
                $endDateTime = $startDateTime;
            }

            $titleParts = [];
            if ($schedule->pelatihan) {
                $titleParts[] = $schedule->pelatihan->nama;
            }
            $titleParts[] = $schedule->judul;
            if ($schedule->pertemuan_ke) {
                $titleParts[count($titleParts) - 1] = 'Pertemuan ' . $schedule->pertemuan_ke . ': ' . $schedule->judul;
            }

            $events->push([
                'id' => $schedule->id,
                'title' => implode(' - ', $titleParts),
                'start' => $startDateTime,
                'end' => $endDateTime,
                'backgroundColor' => '#7367f0',
                'borderColor' => '#7367f0',
                'textColor' => '#fff',
                'extendedProps' => [
                    'pelatihan_id' => $schedule->pelatihan_id,
                    'pelatihan' => $schedule->pelatihan?->nama ?? '-',
                    'judul' => $schedule->judul,
                    'deskripsi' => $schedule->deskripsi,
                    'tanggal' => $schedule->tanggal->format('Y-m-d'),
                    'waktu_mulai' => $schedule->waktu_mulai ? Carbon::parse($schedule->waktu_mulai)->format('H:i') : '-',
                    'waktu_selesai' => $schedule->waktu_selesai ? Carbon::parse($schedule->waktu_selesai)->format('H:i') : '-',
                    'lokasi' => $schedule->lokasi,
                    'tipe' => $schedule->tipe,
                    'pertemuan_ke' => $schedule->pertemuan_ke,
                    'is_active' => $schedule->is_active,
                ],
            ]);
        }

        // 2. Ambil rentang tanggal pelatihan sebagai event background
        $pelatihans = Pelatihan::where('is_active', true)
            ->whereNotNull('tanggal_mulai')
            ->get();

        foreach ($pelatihans as $pelatihan) {
            $startDate = Carbon::parse($pelatihan->tanggal_mulai)->format('Y-m-d');
            $endDate = $pelatihan->tanggal_selesai
                ? Carbon::parse($pelatihan->tanggal_selesai)->format('Y-m-d')
                : $startDate;

            $events->push([
                'id' => 'pelatihan-' . $pelatihan->id,
                'title' => $pelatihan->nama . ' (' . $pelatihan->batch . ')',
                'start' => $startDate,
                'end' => Carbon::parse($endDate)->addDay()->format('Y-m-d'), // +1 day for full-day display
                'backgroundColor' => 'rgba(115, 103, 240, 0.15)',
                'borderColor' => 'transparent',
                'textColor' => '#7367f0',
                'display' => 'background',
                'classNames' => ['pelatihan-range-event'],
                'extendedProps' => [
                    'is_range' => true,
                    'pelatihan' => $pelatihan->nama,
                    'batch' => $pelatihan->batch,
                ],
            ]);
        }

        return response()->json($events);
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'tipe' => 'required|in:offline,online',
            'pertemuan_ke' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active', true);

            $schedule = Schedule::create($validated);

            // Load relasi untuk response
            $schedule->load('pelatihan');

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan.',
                'data' => $schedule,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jadwal. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Update jadwal.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i|after:waktu_mulai',
            'lokasi' => 'nullable|string|max:255',
            'tipe' => 'required|in:offline,online',
            'pertemuan_ke' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['is_active'] = $request->boolean('is_active', true);

            $schedule->update($validated);
            $schedule->load('pelatihan');

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui.',
                'data' => $schedule,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jadwal. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Hapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal menghapus jadwal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal. Silakan coba lagi.',
            ], 500);
        }
    }
}

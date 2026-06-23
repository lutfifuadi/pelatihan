<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Tampilkan daftar enrollment per pelatihan.
     */
    public function index(Request $request, ?Pelatihan $pelatihan = null)
    {
        $search = $request->get('search');

        $query = Enrollment::with(['user.pesertaProfile', 'pelatihan']);

        if ($pelatihan && $pelatihan->exists) {
            $query->where('pelatihan_id', $pelatihan->id);
        } elseif ($request->filled('pelatihan_id')) {
            $query->where('pelatihan_id', $request->pelatihan_id);
        }

        // Filter search by user name
        $query->when($search, function ($q, $search) {
            $q->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        });

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(20);
        $pelatihans = Cache::remember('pelatihan.active.list', 3600, function () {
            return Pelatihan::where('is_active', true)->orderBy('nama')->get(['id', 'nama', 'batch']);
        });

        // Optimasi: 1 query GROUP BY menggantikan 4 query COUNT terpisah
        // Hitung counts berdasarkan filter pelatihan yang dipilih
        $countQuery = Enrollment::query();
        if ($pelatihan && $pelatihan->exists) {
            $countQuery->where('pelatihan_id', $pelatihan->id);
        } elseif ($request->filled('pelatihan_id')) {
            $countQuery->where('pelatihan_id', $request->pelatihan_id);
        }
        $statusCounts = (clone $countQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->whereIn('status', ['pending', 'approved', 'rejected', 'waitlist'])
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'pending' => $statusCounts['pending'] ?? 0,
            'approved' => $statusCounts['approved'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
            'waitlist' => $statusCounts['waitlist'] ?? 0,
        ];

        // Response AJAX untuk auto-search
        if ($request->ajax()) {
            $rows = view('content.admin.enrollments._table_rows', compact('enrollments'))->render();
            $pagination = $enrollments->hasPages() ? $enrollments->links()->render() : '';
            return response()->json([
                'rows' => $rows,
                'pagination' => $pagination,
            ]);
        }

        return view('content.admin.enrollments.index', compact('enrollments', 'pelatihans', 'pelatihan', 'counts', 'search'));
    }

    /**
     * Approve enrollment.
     */
    public function approve(Enrollment $enrollment)
    {
        DB::transaction(function () use ($enrollment) {
            $enrollment->update([
                'status' => 'approved',
                'approved_at' => now(),
                'notes' => request('notes', $enrollment->notes),
            ]);

            // Dispatch notifikasi WA
            \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
        });

        ActivityLogger::action('approved', 'Enrollment', "Pendaftaran {$enrollment->user?->name} untuk pelatihan {$enrollment->pelatihan?->nama} disetujui", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Pendaftaran berhasil di-approve.');
    }

    /**
     * Reject enrollment.
     */
    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        $enrollment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'notes' => $request->notes,
        ]);

        // Dispatch notifikasi WA
        \App\Events\PendaftaranRejected::dispatch(
            $enrollment->user,
            $enrollment->pelatihan,
            $request->notes
        );

        // Cek apakah ada waitlist yang bisa dipromosikan
        $this->promoteFromWaitlist($enrollment->pelatihan_id);

        ActivityLogger::action('rejected', 'Enrollment', "Pendaftaran {$enrollment->user?->name} untuk pelatihan {$enrollment->pelatihan?->nama} ditolak. Alasan: {$request->notes}", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Pendaftaran ditolak.');
    }

    /**
     * Masukkan ke waiting list (cadangan).
     */
    public function waitlist(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'waitlist',
            'notes' => request('notes', 'Dimasukkan ke daftar cadangan'),
        ]);

        // Dispatch notif masuk_cadangan
        if ($enrollment->user && $enrollment->pelatihan) {
            $this->notificationService->sendByTemplate(
                $enrollment->user,
                'masuk_cadangan',
                [
                    'nama' => $enrollment->user->name,
                    'pelatihan' => $enrollment->pelatihan->nama,
                ]
            );
        }

        ActivityLogger::action('updated', 'Enrollment', "Pendaftaran {$enrollment->user?->name} untuk pelatihan {$enrollment->pelatihan?->nama} dipindahkan ke daftar cadangan", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Peserta dipindahkan ke daftar cadangan.');
    }

    /**
     * Promosikan dari waitlist ke approved.
     */
    public function promote(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'approved',
            'approved_at' => now(),
            'waitlist_promoted_at' => now(),
            'notes' => request('notes', 'Dipromosikan dari daftar cadangan'),
        ]);

        // Dispatch notif dipromosikan
        if ($enrollment->user && $enrollment->pelatihan) {
            $this->notificationService->sendByTemplate(
                $enrollment->user,
                'dipromosikan',
                [
                    'nama' => $enrollment->user->name,
                    'pelatihan' => $enrollment->pelatihan->nama,
                ]
            );
        }

        ActivityLogger::action('approved', 'Enrollment', "Pendaftaran {$enrollment->user?->name} untuk pelatihan {$enrollment->pelatihan?->nama} dipromosikan dari daftar cadangan", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Peserta dipromosikan dari cadangan ke approved.');
    }

    /**
     * Approve all pending enrollments for a specific pelatihan.
     */
    public function approveAll(Request $request, Pelatihan $pelatihan)
    {
        $pendingEnrollments = Enrollment::where('pelatihan_id', $pelatihan->id)
            ->where('status', 'pending')
            ->get();

        if ($pendingEnrollments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pendaftaran pending untuk pelatihan ini.');
        }

        $count = 0;
        DB::transaction(function () use ($pendingEnrollments, &$count) {
            foreach ($pendingEnrollments as $enrollment) {
                $enrollment->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
                $count++;
            }
        });

        ActivityLogger::action('approved', 'Enrollment', "{$count} pendaftaran untuk pelatihan {$pelatihan->nama} ({$pelatihan->batch}) berhasil di-approve massal", $pelatihan->id, $pelatihan->nama);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "{$count} pendaftaran berhasil di-approve untuk pelatihan {$pelatihan->nama}.");
    }

    /**
     * Reset enrollment — hapus pendaftaran agar peserta bisa daftar ulang.
     */
    public function reset(Request $request, Enrollment $enrollment)
    {
        $userName = $enrollment->user?->name ?? 'Unknown';
        $pelatihanNama = $enrollment->pelatihan?->nama ?? 'Unknown';
        $enrollmentId = $enrollment->id;

        DB::transaction(function () use ($enrollment) {
            $user = $enrollment->user;
            if ($user && $user->pesertaProfile) {
                $user->pesertaProfile->update([
                    'is_completed' => false,
                    'pelatihan_id' => null,
                    'batch_pelatihan' => null,
                ]);
            }

            $enrollment->delete();
        });

        ActivityLogger::action('deleted', 'Enrollment', "Pendaftaran {$userName} untuk pelatihan {$pelatihanNama} di-reset oleh admin", $enrollmentId, $userName);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "Pendaftaran {$userName} untuk pelatihan {$pelatihanNama} berhasil di-reset. Peserta dapat mendaftar ulang.");
    }

    /**
     * Otomatis promosikan waitlist jika ada slot kosong.
     */
    private function promoteFromWaitlist($pelatihanId)
    {
        $pelatihan = Pelatihan::find($pelatihanId);
        if (!$pelatihan || !$pelatihan->kuota) return;

        $approvedCount = Enrollment::where('pelatihan_id', $pelatihanId)
            ->where('status', 'approved')
            ->count();

        $availableSlots = $pelatihan->kuota - $approvedCount;

        if ($availableSlots > 0) {
            // Ambil data enrollment yang akan dipromosikan (dengan relasi)
            $enrollmentsToPromote = Enrollment::with(['user', 'pelatihan'])
                ->where('pelatihan_id', $pelatihanId)
                ->where('status', 'waitlist')
                ->orderBy('created_at', 'asc')
                ->limit($availableSlots)
                ->get();

            if ($enrollmentsToPromote->isNotEmpty()) {
                // Dispatch notif dipromosikan untuk setiap peserta sebelum batch update
                foreach ($enrollmentsToPromote as $enrollment) {
                    if ($enrollment->user && $enrollment->pelatihan) {
                        $this->notificationService->sendByTemplate(
                            $enrollment->user,
                            'dipromosikan',
                            [
                                'nama' => $enrollment->user->name,
                                'pelatihan' => $enrollment->pelatihan->nama,
                            ]
                        );
                    }
                }

                // Batch update status
                Enrollment::whereIn('id', $enrollmentsToPromote->pluck('id'))
                    ->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'waitlist_promoted_at' => now(),
                    ]);
            }
        }
    }

    /**
     * Tampilkan detail enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user.pesertaProfile', 'pelatihan']);
        return view('content.admin.enrollments.show', compact('enrollment'));
    }

    /**
     * Broadcast DashboardUpdated event safely — catch broadcast errors.
     */
    private function broadcastDashboardUpdate(): void
    {
        // Pastikan cache dihapus secara aman di sini
        Cache::forget('dashboard.admin.stats');

        try {
            event(new \App\Events\DashboardUpdated());
        } catch (\Throwable $e) {
            // Broadcast server might not be available (local dev, etc.)
            \Illuminate\Support\Facades\Log::warning('Dashboard broadcast skipped: ' . $e->getMessage());
        }
    }
}

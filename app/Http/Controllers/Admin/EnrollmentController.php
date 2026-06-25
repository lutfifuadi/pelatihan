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

        $enrollments = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $pelatihans = Pelatihan::where('is_active', true)->orderBy('nama')->get(['id', 'nama', 'batch']);

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
                'counts' => $counts,
            ]);
        }

        return view('content.admin.enrollments.index', compact('enrollments', 'pelatihans', 'pelatihan', 'counts', 'search'));
    }

    /**
     * Approve enrollment.
     */
    public function approve(Enrollment $enrollment)
    {
        // Cek kuota sebelum approve
        if ($enrollment->pelatihan->isKuotaPenuh()) {
            return redirect()->back()->with('error', 'Gagal meng-approve. Kuota pelatihan "' . $enrollment->pelatihan->nama . '" sudah penuh.');
        }

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
        if ($enrollment->pelatihan->isKuotaPenuh()) {
            return redirect()->back()->with('error', 'Tidak dapat mempromosikan peserta. Kuota pelatihan "' . $enrollment->pelatihan->nama . '" sudah penuh.');
        }

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
            ->orderBy('created_at', 'asc')
            ->get();

        if ($pendingEnrollments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pendaftaran pending untuk pelatihan ini.');
        }

        $sisaKuota = $pelatihan->sisaKuota();
        $enrollmentsToApprove = $pendingEnrollments->take($sisaKuota);

        if ($enrollmentsToApprove->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak dapat meng-approve. Kuota pelatihan "' . $pelatihan->nama . '" sudah penuh.');
        }

        $count = 0;
        DB::transaction(function () use ($enrollmentsToApprove, &$count) {
            foreach ($enrollmentsToApprove as $enrollment) {
                $enrollment->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
                $count++;
            }
        });

        $totalPending = $pendingEnrollments->count();
        $remainingPending = $totalPending - $count;

        $message = "{$count} pendaftaran berhasil di-approve untuk pelatihan {$pelatihan->nama}.";
        if ($remainingPending > 0) {
            $message .= " {$remainingPending} pendaftaran tidak bisa di-approve karena kuota penuh.";
        }

        ActivityLogger::action('approved', 'Enrollment', "{$count} pendaftaran untuk pelatihan {$pelatihan->nama} ({$pelatihan->batch}) berhasil di-approve massal", $pelatihan->id, $pelatihan->nama);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', $message);
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
     * Ubah status enrollment (pending/approved/rejected/waitlist) secara manual.
     */
    public function changeStatus(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,waitlist',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $enrollment->status;
        $newStatus = $request->status;

        if ($newStatus === 'approved' && $enrollment->pelatihan->isKuotaPenuh()) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah status ke approved. Kuota pelatihan "' . $enrollment->pelatihan->nama . '" sudah penuh.');
        }

        DB::transaction(function () use ($request, $enrollment, $oldStatus, $newStatus) {
            if ($oldStatus !== $newStatus) {
                $this->updateTimestamps($enrollment, $newStatus);
            }

            $enrollment->update(['status' => $newStatus]);

            $enrollment->update(['notes' => '[Ubah Status: ' . now()->format('d/m/Y H:i') . '] ' . $request->notes]);

            switch ($newStatus) {
                case 'approved':
                    \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
                    break;
                case 'rejected':
                    \App\Events\PendaftaranRejected::dispatch(
                        $enrollment->user,
                        $enrollment->pelatihan,
                        $request->notes
                    );
                    break;
                case 'waitlist':
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
                    break;
            }

            if ($oldStatus === 'approved' && $newStatus !== 'approved') {
                $this->promoteFromWaitlist($enrollment->pelatihan_id, $enrollment->id);
            }
        });

        ActivityLogger::action('status_changed', 'Enrollment',
            "Status {$enrollment->user?->name} untuk {$enrollment->pelatihan?->nama}: {$oldStatus} \u{2192} {$newStatus}. Alasan: {$request->notes}",
            $enrollment->id,
            $enrollment->user?->name
        );

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "Status pendaftaran {$enrollment->user?->name} berhasil diubah menjadi {$newStatus}.");
    }

    /**
     * Transfer peserta ke pelatihan lain.
     */
    public function transfer(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'pelatihan_id' => 'required|exists:pelatihan,id',
            'notes' => 'required|string|max:500',
        ]);

        if ((int)$request->pelatihan_id === $enrollment->pelatihan_id) {
            return back()->with('error', 'Pelatihan tujuan harus berbeda dari pelatihan saat ini.');
        }

        $pelatihanTujuan = Pelatihan::findOrFail($request->pelatihan_id);

        if (!$pelatihanTujuan->is_active) {
            return back()->with('error', 'Pelatihan tujuan tidak aktif.');
        }

        $existingEnrollment = Enrollment::where('user_id', $enrollment->user_id)
            ->where('pelatihan_id', $request->pelatihan_id)
            ->exists();

        if ($existingEnrollment) {
            return back()->with('error', 'Peserta sudah terdaftar di pelatihan ' . $pelatihanTujuan->nama . '.');
        }

        $approvedCount = Enrollment::where('pelatihan_id', $request->pelatihan_id)
            ->where('status', 'approved')->count();

        $kuotaPenuh = $pelatihanTujuan->kuota && $approvedCount >= $pelatihanTujuan->kuota;

        $pelatihanAsal = $enrollment->pelatihan;
        $statusSaatIni = $enrollment->status;

        DB::transaction(function () use ($request, $enrollment, $pelatihanTujuan, $pelatihanAsal, $statusSaatIni, $kuotaPenuh) {
            $enrollment->update(['pelatihan_id' => $request->pelatihan_id]);

            if ($statusSaatIni === 'approved' && $kuotaPenuh) {
                $enrollment->update(['status' => 'waitlist', 'waitlist_promoted_at' => null]);
            }

            $enrollment->update(['notes' => '[Alihkan: ' . now()->format('d/m/Y H:i') . '] ' . $request->notes]);

            $enrollment->attendances()->delete();
            $enrollment->certificate()->delete();

            if ($statusSaatIni === 'approved') {
                $this->promoteFromWaitlist($pelatihanAsal->id, $enrollment->id);
            }

            if ($enrollment->user && $pelatihanTujuan) {
                $this->notificationService->sendByTemplate(
                    $enrollment->user,
                    'dialihkan',
                    [
                        'nama' => $enrollment->user->name,
                        'pelatihan_asal' => $pelatihanAsal->nama,
                        'pelatihan_tujuan' => $pelatihanTujuan->nama,
                        'alasan' => $request->notes,
                    ]
                );
            }
        });

        ActivityLogger::action('transferred', 'Enrollment',
            "Peserta {$enrollment->user?->name} dialihkan dari {$pelatihanAsal->nama} ke {$pelatihanTujuan->nama}. Status: {$statusSaatIni}. Alasan: {$request->notes}",
            $enrollment->id,
            $enrollment->user?->name
        );

        $this->broadcastDashboardUpdate();

        return redirect()->route('admin.enrollments.show', $enrollment->fresh())
            ->with('success', "Peserta {$enrollment->user?->name} berhasil dialihkan dari {$pelatihanAsal->nama} ke {$pelatihanTujuan->nama}.");
    }

    /**
     * Otomatis promosikan waitlist jika ada slot kosong.
     */
    private function promoteFromWaitlist($pelatihanId, $excludeId = null)
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
                ->when($excludeId, function ($query, $excludeId) {
                    $query->where('id', '!=', $excludeId);
                })
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
        $enrollment->load(['user.pesertaProfile', 'user.kecamatan', 'user.kelurahan', 'pelatihan.dinas']);

        $enrollmentIds = Enrollment::where('pelatihan_id', $enrollment->pelatihan_id)
            ->orderBy('created_at')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($enrollment->id, $enrollmentIds);

        $previousEnrollmentId = $currentIndex > 0 ? $enrollmentIds[$currentIndex - 1] : null;
        $nextEnrollmentId = $currentIndex < count($enrollmentIds) - 1 ? $enrollmentIds[$currentIndex + 1] : null;

        $previousEnrollment = $previousEnrollmentId
            ? Enrollment::with('user:id,name')->find($previousEnrollmentId)
            : null;
        $nextEnrollment = $nextEnrollmentId
            ? Enrollment::with('user:id,name')->find($nextEnrollmentId)
            : null;

        $usia = '-';
        $profile = $enrollment->user->pesertaProfile;
        if ($profile && $profile->tanggal_lahir && $profile->bulan_lahir && $profile->tahun_lahir) {
            try {
                $bulanMap = [
                    'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
                    'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
                    'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
                ];
                $bulan = $bulanMap[$profile->bulan_lahir] ?? (int) $profile->bulan_lahir;
                $tanggalLahir = \Carbon\Carbon::createFromDate((int) $profile->tahun_lahir, $bulan, (int) $profile->tanggal_lahir);
                $diff = $tanggalLahir->diff(\Carbon\Carbon::now());
                $parts = [];
                if ($diff->y > 0) $parts[] = $diff->y . ' Tahun';
                if ($diff->m > 0) $parts[] = $diff->m . ' Bulan';
                $usia = !empty($parts) ? implode(' ', $parts) : '0 Bulan';
            } catch (\Exception $e) {
                $usia = '-';
            }
        }

        // Hitung kapasitas pelatihan
        $pelatihan = $enrollment->pelatihan;
        $approvedCount = Enrollment::where('pelatihan_id', $pelatihan->id)->where('status', 'approved')->count();
        $waitlistCount = Enrollment::where('pelatihan_id', $pelatihan->id)->where('status', 'waitlist')->count();
        $totalPendaftar = Enrollment::where('pelatihan_id', $pelatihan->id)->count();
        $sisaBelumTercek = $totalPendaftar - $approvedCount - $waitlistCount;

        return view('content.admin.enrollments.show', compact('enrollment', 'previousEnrollment', 'nextEnrollment', 'usia', 'approvedCount', 'waitlistCount', 'totalPendaftar', 'sisaBelumTercek'));
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

    /**
     * Auto-manajemen timestamp berdasarkan status (FR-009).
     */
    private function updateTimestamps(Enrollment $enrollment, string $newStatus): void
    {
        $oldStatus = $enrollment->getOriginal('status');

        if ($oldStatus === $newStatus) {
            return;
        }

        $data = [
            'approved_at' => null,
            'rejected_at' => null,
            'waitlist_promoted_at' => null,
        ];

        if ($newStatus === 'approved') {
            $data['approved_at'] = now();
            if ($oldStatus === 'waitlist') {
                $data['waitlist_promoted_at'] = now();
            }
        } elseif ($newStatus === 'rejected') {
            $data['rejected_at'] = now();
        }

        $enrollment->update($data);
    }

    /**
     * Ambil daftar pelatihan yang tersedia untuk transfer.
     */
    public function getAvailablePelatihans(Enrollment $enrollment)
    {
        $pelatihans = Pelatihan::where('is_active', true)
            ->where('id', '!=', $enrollment->pelatihan_id)
            ->whereNotIn('id', function ($query) use ($enrollment) {
                $query->select('pelatihan_id')
                    ->from('enrollments')
                    ->where('user_id', $enrollment->user_id);
            })
            ->orderBy('nama')
            ->get(['id', 'nama', 'batch', 'kuota']);

        return response()->json($pelatihans);
    }
}

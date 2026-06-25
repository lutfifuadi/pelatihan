<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Pelatihan;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use App\Services\VerificationCodeService;
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

        // Filter search by user name or verification code
        $query->when($search, function ($q, $search) {
            $q->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('verification_code', 'like', '%' . $search . '%');
        });

        // Filter status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'waiting_wa') {
                $query->where('status', 'approved')
                      ->whereNotNull('verification_code')
                      ->whereNull('wa_confirmed_at');
            } elseif ($status === 'waiting_newbimma') {
                $query->where('status', 'approved')
                      ->whereNotNull('wa_confirmed_at')
                      ->whereNull('newbimma_checked_at');
            } elseif ($status === 'confirmed') {
                $query->where('status', 'approved')
                      ->whereNotNull('newbimma_checked_at')
                      ->where('newbimma_result', 'valid');
            } else {
                $query->where('status', $status);
            }
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
            ->selectRaw("
                CASE 
                    WHEN status = 'approved' AND verification_code IS NOT NULL AND wa_confirmed_at IS NULL THEN 'waiting_wa'
                    WHEN status = 'approved' AND wa_confirmed_at IS NOT NULL AND newbimma_checked_at IS NULL THEN 'waiting_newbimma'
                    WHEN status = 'approved' AND newbimma_checked_at IS NOT NULL AND newbimma_result = 'valid' THEN 'confirmed'
                    ELSE status 
                END as status_group,
                COUNT(*) as total
            ")
            ->groupBy('status_group')
            ->pluck('total', 'status_group');

        $counts = [
            'pending' => $statusCounts['pending'] ?? 0,
            'approved' => $statusCounts['approved'] ?? 0,
            'waiting_wa' => $statusCounts['waiting_wa'] ?? 0,
            'waiting_newbimma' => $statusCounts['waiting_newbimma'] ?? 0,
            'confirmed' => $statusCounts['confirmed'] ?? 0,
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
                'verification_code' => VerificationCodeService::generate($enrollment),
                'verification_code_expires_at' => now()->addHours(24),
            ]);
        });

        // Dispatch notifikasi WA — di luar transaction agar kegagalan notif tidak rollback status
        try {
            \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notifikasi approved gagal dikirim: ' . $e->getMessage());
        }

        ActivityLogger::action('approved', 'Enrollment', "Pendaftaran {$enrollment->user?->name} untuk pelatihan {$enrollment->pelatihan?->nama} disetujui, kode verifikasi dibuat", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Pendaftaran berhasil di-approve. Kode verifikasi WA telah dibuat.');
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
        try {
            \App\Events\PendaftaranRejected::dispatch(
                $enrollment->user,
                $enrollment->pelatihan,
                $request->notes
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notifikasi rejected gagal dikirim: ' . $e->getMessage());
        }

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
            try {
                $this->notificationService->sendByTemplate(
                    $enrollment->user,
                    'masuk_cadangan',
                    [
                        'nama' => $enrollment->user->name,
                        'pelatihan' => $enrollment->pelatihan->nama,
                    ]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notifikasi waitlist gagal dikirim: ' . $e->getMessage());
            }
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

        $approvedList = [];
        DB::transaction(function () use ($enrollmentsToApprove, &$approvedList) {
            foreach ($enrollmentsToApprove as $enrollment) {
                $enrollment->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                $approvedList[] = $enrollment;
            }
        });

        // Dispatch notifikasi di luar transaction
        foreach ($approvedList as $enrollment) {
            try {
                \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notifikasi approve-all gagal dikirim: ' . $e->getMessage());
            }
        }

        $count = count($approvedList);

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

            if ($oldStatus === 'approved' && $newStatus !== 'approved') {
                $this->promoteFromWaitlist($enrollment->pelatihan_id, $enrollment->id);
            }
        });

        // Event/notifikasi di luar transaction agar tidak rollback status jika gagal
        try {
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
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notifikasi change-status gagal dikirim: ' . $e->getMessage());
        }

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
        });

        // Notifikasi di luar transaction
        if ($enrollment->user && $pelatihanTujuan) {
            try {
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
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notifikasi transfer gagal dikirim: ' . $e->getMessage());
            }
        }

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
     * Konfirmasi bahwa peserta sudah di-chat via WA.
     */
    public function confirmWaChat(Enrollment $enrollment)
    {
        if ($enrollment->status !== 'approved') {
            return redirect()->back()->with('error', 'Status enrollment harus approved.');
        }

        if (!VerificationCodeService::isValid($enrollment)) {
            return redirect()->back()->with('error', 'Kode verifikasi sudah expired. Silakan generate ulang.');
        }

        $enrollment->update([
            'wa_confirmed_at' => now(),
            'wa_confirmed_by' => auth()->id(),
        ]);

        ActivityLogger::action('updated', 'Enrollment', "Konfirmasi WA chat untuk {$enrollment->user?->name} pada pelatihan {$enrollment->pelatihan?->nama}", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'WA chat berhasil dikonfirmasi.');
    }

    /**
     * Konfirmasi pengecekan Newbimma valid.
     */
    public function confirmNewbimmaValid(Enrollment $enrollment)
    {
        if ($enrollment->status !== 'approved') {
            return redirect()->back()->with('error', 'Status enrollment harus approved.');
        }

        $enrollment->update([
            'newbimma_result' => 'valid',
            'newbimma_checked_at' => now(),
            'newbimma_checked_by' => auth()->id(),
        ]);

        ActivityLogger::action('approved', 'Enrollment', "Pengecekan Newbimma untuk {$enrollment->user?->name} pada pelatihan {$enrollment->pelatihan?->nama}: valid", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', 'Peserta dinyatakan valid.');
    }

    /**
     * Tolak karena pengecekan Newbimma invalid.
     */
    public function rejectNewbimmaInvalid(Enrollment $enrollment)
    {
        if ($enrollment->status !== 'approved') {
            return redirect()->back()->with('error', 'Status enrollment harus approved.');
        }

        $enrollment->update([
            'newbimma_result' => 'invalid',
            'newbimma_checked_at' => now(),
            'newbimma_checked_by' => auth()->id(),
            'status' => 'rejected',
            'notes' => 'Pernah mengikuti pelatihan yang sama di Newbimma',
        ]);

        ActivityLogger::action('rejected', 'Enrollment', "Pengecekan Newbimma untuk {$enrollment->user?->name} pada pelatihan {$enrollment->pelatihan?->nama}: invalid — ditolak", $enrollment->id, $enrollment->user?->name);

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('error', 'Peserta ditolak karena pernah mengikuti pelatihan yang sama di Newbimma.');
    }

    /**
     * Generate verification code untuk approved enrollment yang belum punya kode.
     */
    public function generateVerificationCode(Enrollment $enrollment)
    {
        if ($enrollment->status !== 'approved') {
            return redirect()->back()->with('error', 'Kode verifikasi hanya bisa digenerate untuk enrollment dengan status Approved.');
        }

        if ($enrollment->verification_code) {
            return redirect()->back()->with('error', 'Enrollment ini sudah memiliki kode verifikasi: ' . $enrollment->verification_code);
        }

        $code = app(\App\Services\VerificationCodeService::class)->generate($enrollment);
        $enrollment->update([
            'verification_code' => $code,
            'verification_code_expires_at' => now()->addHours(24),
        ]);

        return redirect()->back()->with('success', 'Kode verifikasi berhasil digenerate: ' . $code);
    }

    /**
     * Generate verification code untuk semua approved enrollment yang belum punya kode.
     */
    public function generateAllVerificationCodes()
    {
        $enrollments = Enrollment::where('status', 'approved')
            ->whereNull('verification_code')
            ->get();

        if ($enrollments->isEmpty()) {
            return redirect()->back()->with('info', 'Semua enrollment approved sudah memiliki kode verifikasi.');
        }

        $count = 0;
        foreach ($enrollments as $enrollment) {
            $code = app(\App\Services\VerificationCodeService::class)->generate($enrollment);
            $enrollment->update([
                'verification_code' => $code,
                'verification_code_expires_at' => now()->addHours(24),
            ]);
            $count++;
        }

        return redirect()->back()->with('success', "Berhasil generate {$count} kode verifikasi.");
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

<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EnrollmentStatus;
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
use Illuminate\Validation\Rule;

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
                $query->where('status', EnrollmentStatus::WaitingWaConfirmation);
            } elseif ($status === 'waiting_newbimma') {
                $query->where('status', EnrollmentStatus::WaitingNewbimmaCheck);
            } elseif ($status === 'confirmed') {
                $query->where('status', EnrollmentStatus::Confirmed);
            } else {
                $query->where('status', EnrollmentStatus::fromValue($status)?->value ?? $status);
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
                    WHEN status = ? THEN 'waiting_wa'
                    WHEN status = ? THEN 'waiting_newbimma'
                    WHEN status = ? THEN 'confirmed'
                    ELSE status
                END as status_group,
                COUNT(*) as total
            ", [
                EnrollmentStatus::WaitingWaConfirmation->value,
                EnrollmentStatus::WaitingNewbimmaCheck->value,
                EnrollmentStatus::Confirmed->value,
            ])
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

        return view('content.admin.enrollments.index', compact('enrollments', 'pelatihans', 'pelatihan', 'counts', 'search') + ['enrollmentStatuses' => EnrollmentStatus::values()]);
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
                'status' => EnrollmentStatus::Approved,
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
            'status' => EnrollmentStatus::Rejected,
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
            'status' => EnrollmentStatus::Waitlist,
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
            'status' => EnrollmentStatus::Approved,
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
            ->where('status', EnrollmentStatus::Pending)
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
                    'status' => EnrollmentStatus::Approved,
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
     * Tolak semua pending enrollment untuk pelatihan tertentu.
     */
    public function rejectAll(Request $request, Pelatihan $pelatihan)
    {
        $pendingEnrollments = Enrollment::where('pelatihan_id', $pelatihan->id)
            ->where('status', EnrollmentStatus::Pending)
            ->get();

        if ($pendingEnrollments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pendaftaran pending untuk pelatihan ini.');
        }

        DB::transaction(function () use ($pendingEnrollments) {
            foreach ($pendingEnrollments as $enrollment) {
                $enrollment->update([
                    'status' => EnrollmentStatus::Rejected,
                    'rejected_at' => now(),
                    'notes' => '[Reject All: ' . now()->format('d/m/Y H:i') . '] Penolakan massal oleh admin',
                ]);
            }
        });

        // Dispatch notifikasi WA di luar transaction
        foreach ($pendingEnrollments as $enrollment) {
            try {
                \App\Events\PendaftaranRejected::dispatch(
                    $enrollment->user,
                    $enrollment->pelatihan,
                    'Pendaftaran ditolak oleh admin.'
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notifikasi reject-all gagal dikirim: ' . $e->getMessage());
            }
        }

        // Promosi dari waitlist jika ada slot
        $this->promoteFromWaitlist($pelatihan->id);

        $count = $pendingEnrollments->count();

        ActivityLogger::action('rejected', 'Enrollment',
            "{$count} pendaftaran untuk pelatihan {$pelatihan->nama} ({$pelatihan->batch}) ditolak massal oleh admin",
            $pelatihan->id,
            $pelatihan->nama
        );

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "{$count} pendaftaran berhasil ditolak untuk pelatihan {$pelatihan->nama}.");
    }

    /**
     * Cadangkan semua pending enrollment untuk pelatihan tertentu secara massal.
     */
    public function waitlistAll(Request $request, Pelatihan $pelatihan)
    {
        $pendingEnrollments = Enrollment::where('pelatihan_id', $pelatihan->id)
            ->where('status', EnrollmentStatus::Pending)
            ->get();

        if ($pendingEnrollments->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pendaftaran pending untuk pelatihan ini.');
        }

        $count = $pendingEnrollments->count();

        DB::transaction(function () use ($pendingEnrollments) {
            foreach ($pendingEnrollments as $enrollment) {
                $enrollment->update([
                    'status' => EnrollmentStatus::Waitlist,
                    'notes' => '[Waitlist All: ' . now()->format('d/m/Y H:i') . '] Dimasukkan ke daftar cadangan massal oleh admin',
                ]);
            }
        });

        // Kirim notifikasi WA di luar transaction block menggunakan loop
        foreach ($pendingEnrollments as $enrollment) {
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
                    \Illuminate\Support\Facades\Log::warning('Notifikasi waitlist-all gagal dikirim: ' . $e->getMessage());
                }
            }
        }

        ActivityLogger::action(
            'updated',
            'Enrollment',
            "{$count} pendaftaran untuk pelatihan {$pelatihan->nama} ({$pelatihan->batch}) dipindahkan ke daftar cadangan massal oleh admin",
            $pelatihan->id,
            $pelatihan->nama
        );

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "{$count} pendaftaran berhasil dipindahkan ke daftar cadangan.");
    }

    /**
     * Lakukan bulk action (approve/reject/waitlist) secara massal.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:enrollments,id',
            'action' => 'required|in:approve,reject,waitlist',
        ]);

        $enrollments = Enrollment::whereIn('id', $request->ids)->get();

        if ($enrollments->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada pendaftaran yang terpilih.'], 400);
        }

        // Validasi Kuota Pelatihan (Khusus jika $request->action === 'approve')
        if ($request->action === 'approve') {
            $grouped = $enrollments->groupBy('pelatihan_id');
            foreach ($grouped as $pelatihanId => $groupEnrollments) {
                $pelatihan = Pelatihan::findOrFail($pelatihanId);
                $sisaKuota = $pelatihan->sisaKuota();
                $countSelected = $groupEnrollments->count();

                if ($countSelected > $sisaKuota) {
                    return response()->json([
                        'success' => false,
                        'message' => "Kuota untuk pelatihan {$pelatihan->nama} (Batch {$pelatihan->batch}) tidak mencukupi. Sisa kuota: {$sisaKuota}, yang diajukan untuk disetujui: {$countSelected}."
                    ], 422);
                }
            }
        }

        $processedIds = [];
        $waitlistPromotions = []; // Simpan pelatihan_id yang butuh dipromosikan (jika melepas status approved)

        DB::transaction(function () use ($enrollments, $request, &$processedIds, &$waitlistPromotions) {
            foreach ($enrollments as $enrollment) {
                $oldStatus = $enrollment->status;
                $oldStatusEnum = $oldStatus instanceof EnrollmentStatus ? $oldStatus : EnrollmentStatus::fromValue($oldStatus);

                $action = $request->action;
                if ($action === 'approve') {
                    $newStatus = EnrollmentStatus::Approved;
                    $note = 'Disetujui secara massal oleh admin';
                } elseif ($action === 'reject') {
                    $newStatus = EnrollmentStatus::Rejected;
                    $note = 'Ditolak secara massal oleh admin';
                } else {
                    $newStatus = EnrollmentStatus::Waitlist;
                    $note = 'Dimasukkan ke daftar cadangan massal oleh admin';
                }

                if ($oldStatusEnum !== $newStatus) {
                    $this->updateTimestamps($enrollment, $newStatus);
                }

                $enrollment->update([
                    'status' => $newStatus,
                    'notes' => '[Bulk Action: ' . ucfirst($request->action) . '] ' . $note
                ]);

                if ($oldStatusEnum === EnrollmentStatus::Approved && $newStatus !== EnrollmentStatus::Approved) {
                    $waitlistPromotions[] = [
                        'pelatihan_id' => $enrollment->pelatihan_id,
                        'exclude_id' => $enrollment->id,
                    ];
                }

                $processedIds[] = $enrollment->id;
            }

            // Jalankan promote dari waitlist dalam transaction
            foreach ($waitlistPromotions as $promo) {
                $this->promoteFromWaitlist($promo['pelatihan_id'], $promo['exclude_id']);
            }
        });

        // Looping luar transaction untuk notification & ActivityLogger
        foreach ($enrollments as $enrollment) {
            try {
                $status = $enrollment->status;
                $statusEnum = $status instanceof EnrollmentStatus ? $status : EnrollmentStatus::fromValue($status);

                switch ($statusEnum) {
                    case EnrollmentStatus::Approved:
                        \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
                        break;
                    case EnrollmentStatus::Rejected:
                        \App\Events\PendaftaranRejected::dispatch(
                            $enrollment->user,
                            $enrollment->pelatihan,
                            'Disetujui/Ditolak secara massal oleh admin'
                        );
                        break;
                    case EnrollmentStatus::Waitlist:
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
                \Illuminate\Support\Facades\Log::warning('Notifikasi bulkAction gagal dikirim untuk enrollment ID ' . $enrollment->id . ': ' . $e->getMessage());
            }
        }

        $count = $enrollments->count();
        ActivityLogger::action(
            'status_changed',
            'Enrollment',
            "Aksi massal {$request->action} dilakukan pada {$count} pendaftaran",
            $enrollments->first()?->pelatihan_id,
            $enrollments->first()?->pelatihan?->nama
        );

        $this->broadcastDashboardUpdate();

        return response()->json([
            'success' => true,
            'message' => "Berhasil memproses {$count} pendaftaran."
        ]);
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
            'status' => ['required', Rule::in(EnrollmentStatus::values())],
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $enrollment->status;
        $newStatusValue = $request->status;
        $newStatus = EnrollmentStatus::fromValue($newStatusValue);

        if ($newStatus === EnrollmentStatus::Approved && $enrollment->pelatihan->isKuotaPenuh()) {
            return redirect()->back()->with('error', 'Tidak dapat mengubah status ke approved. Kuota pelatihan "' . $enrollment->pelatihan->nama . '" sudah penuh.');
        }

        DB::transaction(function () use ($request, $enrollment, $oldStatus, $newStatus, $newStatusValue) {
            if ($oldStatus !== $newStatus) {
                $this->updateTimestamps($enrollment, $newStatus);
            }

            $enrollment->update(['status' => $newStatus]);

            $enrollment->update(['notes' => '[Ubah Status: ' . now()->format('d/m/Y H:i') . '] ' . $request->notes]);

            if ($oldStatus === EnrollmentStatus::Approved && $newStatus !== EnrollmentStatus::Approved) {
                $this->promoteFromWaitlist($enrollment->pelatihan_id, $enrollment->id);
            }
        });

        // Event/notifikasi di luar transaction agar tidak rollback status jika gagal
        try {
            switch ($newStatus) {
                case EnrollmentStatus::Approved:
                    \App\Events\PendaftaranApproved::dispatch($enrollment->user, $enrollment->pelatihan);
                    break;
                case EnrollmentStatus::Rejected:
                    \App\Events\PendaftaranRejected::dispatch(
                        $enrollment->user,
                        $enrollment->pelatihan,
                        $request->notes
                    );
                    break;
                case EnrollmentStatus::Waitlist:
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
            "Status {$enrollment->user?->name} untuk {$enrollment->pelatihan?->nama}: {$oldStatus->value} \u{2192} {$newStatus->value}. Alasan: {$request->notes}",
            $enrollment->id,
            $enrollment->user?->name
        );

        $this->broadcastDashboardUpdate();

        return redirect()->back()->with('success', "Status pendaftaran {$enrollment->user?->name} berhasil diubah menjadi {$newStatus?->label()}.");
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
            ->where('status', EnrollmentStatus::Approved)->count();

        $kuotaPenuh = $pelatihanTujuan->kuota && $approvedCount >= $pelatihanTujuan->kuota;

        $pelatihanAsal = $enrollment->pelatihan;
        $statusSaatIni = $enrollment->status;

        DB::transaction(function () use ($request, $enrollment, $pelatihanTujuan, $pelatihanAsal, $statusSaatIni, $kuotaPenuh) {
            $enrollment->update(['pelatihan_id' => $request->pelatihan_id]);

            $statusSaatIniEnum = $statusSaatIni instanceof EnrollmentStatus ? $statusSaatIni : EnrollmentStatus::fromValue($statusSaatIni);
            $statusesToCheck = [
                EnrollmentStatus::Pending,
                EnrollmentStatus::Approved,
                EnrollmentStatus::WaitingWaConfirmation,
                EnrollmentStatus::WaitingNewbimmaCheck,
                EnrollmentStatus::Confirmed,
            ];

            if (in_array($statusSaatIniEnum, $statusesToCheck, true) && $kuotaPenuh) {
                $enrollment->update(['status' => EnrollmentStatus::Waitlist, 'waitlist_promoted_at' => null]);
            }

            $enrollment->update(['notes' => '[Alihkan: ' . now()->format('d/m/Y H:i') . '] ' . $request->notes]);

            $enrollment->attendances()->delete();
            $enrollment->certificate()->delete();

            if (in_array($statusSaatIniEnum, $statusesToCheck, true)) {
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
            "Peserta {$enrollment->user?->name} dialihkan dari {$pelatihanAsal->nama} ke {$pelatihanTujuan->nama}. Status: {$statusSaatIni->value}. Alasan: {$request->notes}",
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
            ->where('status', EnrollmentStatus::Approved)
            ->count();

        $availableSlots = $pelatihan->kuota - $approvedCount;

        if ($availableSlots > 0) {
            // Ambil data enrollment yang akan dipromosikan (dengan relasi)
            $enrollmentsToPromote = Enrollment::with(['user', 'pelatihan'])
                ->where('pelatihan_id', $pelatihanId)
                ->where('status', EnrollmentStatus::Waitlist)
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
                        'status' => EnrollmentStatus::Approved,
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
        $approvedCount = Enrollment::where('pelatihan_id', $pelatihan->id)->where('status', EnrollmentStatus::Approved)->count();
        $waitlistCount = Enrollment::where('pelatihan_id', $pelatihan->id)->where('status', EnrollmentStatus::Waitlist)->count();
        $totalPendaftar = Enrollment::where('pelatihan_id', $pelatihan->id)->count();
        $sisaBelumTercek = $totalPendaftar - $approvedCount - $waitlistCount;

        return view('content.admin.enrollments.show', compact('enrollment', 'previousEnrollment', 'nextEnrollment', 'usia', 'approvedCount', 'waitlistCount', 'totalPendaftar', 'sisaBelumTercek'));
    }

    /**
     * Konfirmasi bahwa peserta sudah di-chat via WA.
     */
    public function confirmWaChat(Enrollment $enrollment)
    {
        if ($enrollment->status !== EnrollmentStatus::Approved) {
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
        if ($enrollment->status !== EnrollmentStatus::Approved) {
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
        if ($enrollment->status !== EnrollmentStatus::Approved) {
            return redirect()->back()->with('error', 'Status enrollment harus approved.');
        }

        $enrollment->update([
            'newbimma_result' => 'invalid',
            'newbimma_checked_at' => now(),
            'newbimma_checked_by' => auth()->id(),
            'status' => EnrollmentStatus::Rejected,
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
        if ($enrollment->status !== EnrollmentStatus::Approved) {
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
        $enrollments = Enrollment::where('status', EnrollmentStatus::Approved)
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
    private function updateTimestamps(Enrollment $enrollment, EnrollmentStatus $newStatus): void
    {
        $oldStatus = $enrollment->getOriginal('status');
        $oldStatusEnum = $oldStatus instanceof EnrollmentStatus ? $oldStatus : EnrollmentStatus::fromValue($oldStatus);

        if ($oldStatusEnum === $newStatus) {
            return;
        }

        $data = [
            'approved_at' => null,
            'rejected_at' => null,
            'waitlist_promoted_at' => null,
        ];

        if ($newStatus === EnrollmentStatus::Approved) {
            $data['approved_at'] = now();
            if ($oldStatusEnum === EnrollmentStatus::Waitlist) {
                $data['waitlist_promoted_at'] = now();
            }
        } elseif ($newStatus === EnrollmentStatus::Rejected) {
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

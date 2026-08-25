<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Pelatihan;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Dashboard Admin — Real-time (no cache).
     */
    public function admin()
    {
        // --- Statistik dengan single query menggunakan GROUP BY ---
        $userCounts = User::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN role = 'peserta' THEN 1 ELSE 0 END) as total_peserta,
                SUM(CASE WHEN role = 'instruktur' THEN 1 ELSE 0 END) as total_instruktur
            ")->first();

        // --- Notifikasi stats dalam 2 query ---
        $waSentToday = Notification::where('channel', 'whatsapp')
            ->where('status', 'sent')
            ->whereDate('sent_at', today())
            ->count();

        $waFailed = Notification::where('channel', 'whatsapp')
            ->where('status', 'failed')
            ->count();

        $notifPending = Notification::where('status', 'pending')->count();

        $activeTemplates = NotificationTemplate::where('is_active', true)->count();

        // --- Pelatihan (via DashboardService) ---
        $totalPelatihan = Pelatihan::count();
        $activePelatihanCount = Pelatihan::where('is_active', true)->count();

        $pelatihanList = $this->dashboardService->getPelatihanList();
        $trendPendaftaran = $this->dashboardService->getRegistrationTrend();
        $topInstruktur = $this->dashboardService->getTopInstruktur();

        // --- Presensi Hari Ini ---
        $today = now()->toDateString();
        $pelatihanHariIni = Pelatihan::where('is_active', true)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->with(['dinas'])
            ->get();

        $pelatihanHariIniCount = $pelatihanHariIni->count();
        $activePelatihanIds = $pelatihanHariIni->pluck('id');

        // Total Enrollment dengan status confirmed pada pelatihan-pelatihan aktif hari ini
        $totalConfirmedHariIni = \App\Models\Enrollment::whereIn('pelatihan_id', $activePelatihanIds)
            ->where('status', \App\Enums\EnrollmentStatus::Confirmed)
            ->count();

        // Total Attendance dengan status hadir pada tanggal hari ini untuk pelatihan-pelatihan tersebut
        $totalHadirHariIni = \App\Models\Attendance::whereDate('date', $today)
            ->where('status', 'hadir')
            ->whereIn('enrollment_id', function ($query) use ($activePelatihanIds) {
                $query->select('id')
                    ->from('enrollments')
                    ->whereIn('pelatihan_id', $activePelatihanIds);
            })
            ->count();

        // Total kuota dari pelatihan-pelatihan aktif hari ini
        $totalKuotaHariIni = $pelatihanHariIni->sum('kuota');

        // Rata-rata kehadiran hari ini (persentase)
        $persentaseKehadiranHariIni = $totalConfirmedHariIni > 0
            ? round(($totalHadirHariIni / $totalConfirmedHariIni) * 100)
            : 0;

        // --- Corong Verifikasi Pendaftaran (Registration Funnel) ---
        $funnelCounts = \App\Models\Enrollment::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
            SUM(CASE WHEN status = 'waiting_wa_confirmation' THEN 1 ELSE 0 END) as waiting_wa,
            SUM(CASE WHEN status = 'waiting_newbimma_check' THEN 1 ELSE 0 END) as waiting_newbimma,
            SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
            SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN status = 'waitlist' THEN 1 ELSE 0 END) as waitlist
        ")->first();

        // --- Live Monitoring Pelatihan Hari Ini ---
        $livePelatihans = $pelatihanHariIni->map(function ($pelatihan) use ($today) {
            $confirmedCount = \App\Models\Enrollment::where('pelatihan_id', $pelatihan->id)
                ->where('status', \App\Enums\EnrollmentStatus::Confirmed)
                ->count();

            $hadirCount = \App\Models\Attendance::whereDate('date', $today)
                ->where('status', 'hadir')
                ->whereIn('enrollment_id', function ($query) use ($pelatihan) {
                    $query->select('id')
                        ->from('enrollments')
                        ->where('pelatihan_id', $pelatihan->id);
                })
                ->count();

            // instuktur default value / fallback
            $instrukturName = "Belum Ditugaskan";

            return [
                'id' => $pelatihan->id,
                'nama' => $pelatihan->nama,
                'batch' => $pelatihan->batch,
                'instruktur' => $instrukturName,
                'dinas' => $pelatihan->dinas,
                'hadir' => $hadirCount,
                'total' => $confirmedCount,
                'persentase' => $confirmedCount > 0 ? round(($hadirCount / $confirmedCount) * 100) : 0
            ];
        });

        // --- Log Audit Presensi Terbaru ---
        $latestAuditLogs = \App\Models\AuditLog::with('actor:id,name')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Kecamatan ---
        $totalKecamatan = Kecamatan::count();

        // --- Sebaran Pendaftar per Kecamatan (Leaflet) ---
        $sebaranKecamatan = Kecamatan::select('kecamatans.id', 'kecamatans.name', 'kecamatans.latitude', 'kecamatans.longitude')
            ->selectRaw('COUNT(users.id) as total_pendaftar')
            ->leftJoin('users', function ($join) {
                $join->on('kecamatans.id', '=', 'users.kecamatan_id')
                    ->where('users.role', '=', 'peserta');
            })
            ->whereNotNull('kecamatans.latitude')
            ->whereNotNull('kecamatans.longitude')
            ->groupBy('kecamatans.id', 'kecamatans.name', 'kecamatans.latitude', 'kecamatans.longitude')
            ->get();

        // --- Peserta terbaru ---
        $pesertaCount = $userCounts->total_peserta;

        $latestPeserta = User::select('id', 'name', 'nik', 'created_at')
            ->where('role', 'peserta')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        // --- Log Aktivitas Terbaru ---
        $latestActivities = \App\Models\ActivityLog::with('user:id,name,role')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('content.dashboard.admin', compact(
            'userCounts',
            'waSentToday',
            'waFailed',
            'notifPending',
            'activeTemplates',
            'totalPelatihan',
            'activePelatihanCount',
            'pelatihanList',
            'trendPendaftaran',
            'topInstruktur',
            'totalKecamatan',
            'sebaranKecamatan',
            'pesertaCount',
            'latestPeserta',
            'latestActivities',
            'pelatihanHariIni',
            'pelatihanHariIniCount',
            'totalConfirmedHariIni',
            'totalHadirHariIni',
            'totalKuotaHariIni',
            'persentaseKehadiranHariIni',
            'funnelCounts',
            'livePelatihans',
            'latestAuditLogs',
        ));
    }

    /**
     * Dashboard Instruktur
     */
    public function instruktur()
    {
        return view('content.dashboard.instruktur');
    }

    /**
     * Dashboard Koordinator
     */
    public function koordinator()
    {
        return view('content.dashboard.koordinator');
    }

    /**
     * Dashboard Peserta
     */
    public function peserta()
    {
        $user = auth()->user();
        $profile = \App\Models\PesertaProfile::where('user_id', $user->id)
            ->with(['pelatihan' => function($query) {
                $query->with('dinas');
            }])
            ->first();

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->with(['pelatihan.dinas', 'attendances', 'certificate'])
            ->latest('id')
            ->first();

        // Hitung persentase kehadiran
        $attendanceRate = 0;
        if ($enrollment && $enrollment->attendances->count() > 0) {
            $hadir = $enrollment->attendances->where('status', 'hadir')->count();
            $attendanceRate = round(($hadir / $enrollment->attendances->count()) * 100);
        }

        // Persentase kelengkapan profil (hitung manual untuk progress bar)
        $profileCompletion = 0;
        if ($profile) {
            $fields = [
                'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'bulan_lahir', 'tahun_lahir', 'nik',
                'alamat_ktp', 'rt', 'rw', 'kelurahan_id', 'kecamatan_id', 'kodepos', 'whatsapp', 'email',
                'pendidikan_terakhir', 'nama_institusi', 'tahun_lulus', 'status_pekerjaan', 'pelatihan_id'
            ];
            $filled = 0;
            foreach ($fields as $f) {
                if ($f === 'kecamatan_id') {
                    if (!empty($user->kecamatan_id)) {
                        $filled++;
                    }
                } else {
                    if (!empty($profile->$f)) {
                        $filled++;
                    }
                }
            }
            $profileCompletion = (int) round(($filled / count($fields)) * 100);
        }

        $allEnrollments = \App\Models\Enrollment::where('user_id', $user->id)
            ->with(['pelatihan.dinas', 'attendances', 'certificate'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Kumpulkan data statistik dashboard
        $whatsappSender = \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
        $data = [
            'isProfileCompleted' => $profile ? $profile->is_completed : false,
            'profileCompletion' => $profileCompletion,
            'hasPelatihan' => ($profile && $profile->pelatihan_id) || ($enrollment && $enrollment->pelatihan_id),
            'pelatihan' => $profile?->pelatihan ?? $enrollment?->pelatihan,
            'enrollment' => $enrollment,
            'allEnrollments' => $allEnrollments,
            'attendanceRate' => $attendanceRate,
            'hasCertificate' => $enrollment && $enrollment->certificate()->exists(),
            'certificate' => $enrollment ? $enrollment->certificate : null,
            'whatsapp_sender' => $whatsappSender,
        ];

        // Hitung elapsed time sejak newbimma_checked_at (FR-011, FR-012)
        $elapsedTime = null;
        if ($enrollment && $enrollment->newbimma_checked_at) {
            $elapsedTime = $enrollment->newbimma_checked_at->diffForHumans(now(), [
                'parts' => 2,
                'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
            ]);
        }

        // Passing data tambahan untuk State 4 (Cek Newbimma) dan State 3
        $data['elapsedTime'] = $elapsedTime;
        $data['newbimmaCheckedAt'] = $enrollment?->newbimma_checked_at;
        $data['waConfirmedAt'] = $enrollment?->wa_confirmed_at;
        $data['approvedAt'] = $enrollment?->approved_at;

        $registeredPelatihanId = $profile?->pelatihan_id ?? $enrollment?->pelatihan_id;

        if ($registeredPelatihanId) {
            $announcements = \App\Models\PengumumanPelatihan::with('user')
                ->where(function($query) use ($registeredPelatihanId) {
                    $query->where('pelatihan_id', $registeredPelatihanId)
                          ->orWhereNull('pelatihan_id');
                })
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $announcements = \App\Models\PengumumanPelatihan::with('user')
                ->whereNull('pelatihan_id')
                ->where('is_private', false)
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('content.dashboard.peserta', compact('profile', 'data', 'announcements'));
    }

    /**
     * Halaman Operasional Panitia — Pelatihan Aktif Hari Ini
     */
    public function panitiaOperasional()
    {
        $today = now()->toDateString();
        $pelatihans = Pelatihan::where('is_active', true)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->get();

        return view('content.panitia.operasional', compact('pelatihans'));
    }
}

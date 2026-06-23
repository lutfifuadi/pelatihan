<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin — Data di-cache untuk performa optimal.
     */
    public function admin()
    {
        $data = Cache::remember('dashboard.admin.stats', 3600, function () {
            // --- Statistik dengan single query menggunakan GROUP BY ---
            $userCounts = User::selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN role = 'peserta' THEN 1 ELSE 0 END) as total_peserta,
                    SUM(CASE WHEN role = 'instruktur' THEN 1 ELSE 0 END) as total_instruktur,
                    SUM(CASE WHEN role = 'koordinator' THEN 1 ELSE 0 END) as total_koordinator
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

            // --- Koordinator pending (aktif & tidak aktif) ---
            $pendingKoordinators = User::where('role', 'koordinator')
                ->where('is_active', false)
                ->with('kecamatan:id,name')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $pendingKoordinatorCount = User::where('role', 'koordinator')
                ->where('is_active', false)
                ->count();

            // --- Koordinator aktif ---
            $activeKoors = User::where('role', 'koordinator')
                ->where('is_active', true)
                ->with('kecamatan:id,name')
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            $koorActiveCount = User::where('role', 'koordinator')
                ->where('is_active', true)
                ->count();

            // --- Pelatihan ---
            $totalPelatihan = Pelatihan::count();
            $activePelatihanCount = Pelatihan::where('is_active', true)->count();

            $latestPelatihan = Pelatihan::select('id', 'nama', 'batch', 'kuota', 'is_active', 'created_at')
                ->orderBy('created_at', 'desc')
                ->take(4)
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
                ->take(4)
                ->get();

            // --- Log Aktivitas Terbaru ---
            $latestActivities = \App\Models\ActivityLog::with('user:id,name,role')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            return compact(
                'userCounts',
                'waSentToday',
                'waFailed',
                'notifPending',
                'activeTemplates',
                'pendingKoordinators',
                'pendingKoordinatorCount',
                'activeKoors',
                'koorActiveCount',
                'totalPelatihan',
                'activePelatihanCount',
                'latestPelatihan',
                'totalKecamatan',
                'sebaranKecamatan',
                'pesertaCount',
                'latestPeserta',
                'latestActivities',
            );
        });

        return view('content.dashboard.admin', $data);
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
            ->with(['pelatihan.dinas'])
            ->first();

        $enrollment = \App\Models\Enrollment::where('user_id', $user->id)
            ->with(['pelatihan.dinas', 'attendances', 'certificate'])
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

        // Kumpulkan data statistik dashboard
        $whatsappSender = \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
        $data = [
            'isProfileCompleted' => $profile ? $profile->is_completed : false,
            'profileCompletion' => $profileCompletion,
            'hasPelatihan' => $profile && $profile->pelatihan_id,
            'pelatihan' => $profile ? $profile->pelatihan : null,
            'enrollment' => $enrollment,
            'attendanceRate' => $attendanceRate,
            'hasCertificate' => $enrollment && $enrollment->certificate()->exists(),
            'certificate' => $enrollment ? $enrollment->certificate : null,
            'whatsapp_sender' => $whatsappSender,
        ];

        return view('content.dashboard.peserta', compact('profile', 'data'));
    }
}

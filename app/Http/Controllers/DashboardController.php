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

            // --- Peserta terbaru ---
            $pesertaCount = $userCounts->total_peserta;

            $latestPeserta = User::select('id', 'name', 'nik', 'created_at')
                ->where('role', 'peserta')
                ->orderBy('created_at', 'desc')
                ->take(4)
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
                'pesertaCount',
                'latestPeserta',
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
        $data = [
            'totalPelatihan' => 3,
            'tugasSelesai' => 12,
            'sertifikat' => 1,
            'jamBelajar' => 47,
            'nilaiRata' => '85.5',
        ];

        return view('content.dashboard.peserta', compact('data'));
    }
}

<?php

namespace App\Events;

use App\Models\Kecamatan;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class DashboardUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $stats;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        // Clear cached dashboard stats
        Cache::forget('dashboard.admin.stats');

        // Fetch fresh stats to broadcast
        $userCounts = User::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN role = 'peserta' THEN 1 ELSE 0 END) as total_peserta,
                SUM(CASE WHEN role = 'instruktur' THEN 1 ELSE 0 END) as total_instruktur,
                SUM(CASE WHEN role = 'koordinator' THEN 1 ELSE 0 END) as total_koordinator
            ")->first();

        $waSentToday = Notification::where('channel', 'whatsapp')
            ->where('status', 'sent')
            ->whereDate('sent_at', today())
            ->count();

        $waFailed = Notification::where('channel', 'whatsapp')
            ->where('status', 'failed')
            ->count();

        $notifPending = Notification::where('status', 'pending')->count();

        $activeTemplates = NotificationTemplate::where('is_active', true)->count();

        $pendingKoordinatorCount = User::where('role', 'koordinator')
            ->where('is_active', false)
            ->count();

        $koorActiveCount = User::where('role', 'koordinator')
            ->where('is_active', true)
            ->count();

        $totalPelatihan = Pelatihan::count();
        $activePelatihanCount = Pelatihan::where('is_active', true)->count();
        $totalKecamatan = Kecamatan::count();
        $pesertaCount = $userCounts->total_peserta;

        // Pending Koordinators (latest 5)
        $pendingKoordinators = User::where('role', 'koordinator')
            ->where('is_active', false)
            ->with('kecamatan:id,name')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($koor) {
                return [
                    'id' => $koor->id,
                    'name' => $koor->name,
                    'nik' => $koor->nik,
                    'kecamatan_name' => $koor->kecamatan->name ?? '-',
                    'whatsapp' => $koor->whatsapp,
                    'approve_route' => route('admin.koordinator.approve', $koor->id),
                    'reject_route' => route('admin.koordinator.reject', $koor->id),
                ];
            });

        // Latest Pelatihan (latest 4)
        $latestPelatihan = Pelatihan::select('id', 'nama', 'batch', 'kuota', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Latest Peserta (latest 4)
        $latestPeserta = User::select('id', 'name', 'nik', 'created_at')
            ->where('role', 'peserta')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'nik' => $p->nik ?? 'NIK Tidak Tersedia',
                    'diff_time' => $p->created_at->diffForHumans()
                ];
            });

        // Active Koors (latest 4)
        $activeKoors = User::where('role', 'koordinator')
            ->where('is_active', true)
            ->with('kecamatan:id,name')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get()
            ->map(function ($k) {
                return [
                    'name' => $k->name,
                    'kecamatan_name' => $k->kecamatan->name ?? '-',
                    'whatsapp' => $k->whatsapp
                ];
            });

        $this->stats = [
            'totalPelatihan' => $totalPelatihan,
            'totalPeserta' => $pesertaCount,
            'totalInstruktur' => $userCounts->total_instruktur,
            'totalKoordinator' => $userCounts->total_koordinator,
            'totalKecamatan' => $totalKecamatan,
            'waSentToday' => $waSentToday,
            'waFailed' => $waFailed,
            'activeTemplates' => $activeTemplates,
            'notifPending' => $notifPending,
            'pendingKoordinatorCount' => $pendingKoordinatorCount,
            'koorActiveCount' => $koorActiveCount,
            'activePelatihanCount' => $activePelatihanCount,
            'pendingKoordinators' => $pendingKoordinators->toArray(),
            'latestPelatihan' => $latestPelatihan->toArray(),
            'latestPeserta' => $latestPeserta->toArray(),
            'activeKoors' => $activeKoors->toArray(),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * Determine if this event should broadcast.
     */
    public function broadcastWhen(): bool
    {
        $enabled = \App\Models\Setting::where('key', 'broadcast_enabled')->value('value') ?? '1';
        return $enabled === '1';
    }

    /**
     * Broadcast's event name.
     */
    public function broadcastAs(): string
    {
        return 'dashboard.updated';
    }
}

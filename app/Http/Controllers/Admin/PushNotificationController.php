<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePushNotificationRequest;
use App\Jobs\SendPushNotificationJob;
use App\Models\Kelurahan;
use App\Models\Pelatihan;
use App\Models\PushNotification;
use App\Services\WebPushService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PushNotificationController extends Controller
{
    public function __construct(
        private WebPushService $webPushService
    ) {}

    /**
     * Halaman daftar riwayat notifikasi push.
     */
    public function index(Request $request): View
    {
        $query = PushNotification::with('admin')
            ->withCount([
                'recipients as sent_count' => fn ($q) => $q->where('status', 'sent'),
                'recipients as failed_count' => fn ($q) => $q->where('status', 'failed'),
                'recipients as expired_count' => fn ($q) => $q->where('status', 'expired'),
            ])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();

        return view('content.admin.push-notifications.index', compact('notifications'));
    }

    /**
     * Halaman form buat notifikasi baru.
     */
    public function create(): View
    {
        $pelatihans = Pelatihan::where('is_active', true)->orderBy('nama')->get(['id', 'nama']);
        $kelurahans = Kelurahan::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('content.admin.push-notifications.create', compact('pelatihans', 'kelurahans'));
    }

    /**
     * Simpan notifikasi baru.
     */
    public function store(StorePushNotificationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $notification = PushNotification::create([
                'admin_id' => auth()->id(),
                'title' => $validated['title'],
                'body' => $validated['body'],
                'link_url' => $validated['link_url'] ?? null,
                'target_type' => $validated['target_type'],
                'target_filters' => $validated['target_filters'] ?? null,
                'total_target' => 0,
            ]);

            return redirect()
                ->route('admin.push-notifications.show', $notification)
                ->with('success', 'Notifikasi berhasil dibuat. Silakan review sebelum dikirim.');
        } catch (\Exception $e) {
            Log::error('Failed to create push notification', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat notifikasi: '.$e->getMessage());
        }
    }

    public function destroy(PushNotification $push_notification): RedirectResponse
    {
        $notification = $push_notification;
        $notification->recipients()->delete();
        $notification->delete();

        return redirect()->route('admin.push-notifications.index')->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Halaman detail notifikasi / hasil pengiriman.
     */
    public function show(PushNotification $push_notification): View
    {
        $notification = $push_notification;
        $notification->load(['admin', 'recipients.subscription']);

        $recipients = $notification->recipients()
            ->with('subscription')
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('content.admin.push-notifications.show', compact('notification', 'recipients'));
    }

    /**
     * Hitung estimasi jumlah target berdasarkan filter.
     */
    public function estimateCount(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', 'in:all,filtered'],
            'target_filters' => ['nullable', 'array'],
            'target_filters.status' => ['nullable', 'array'],
            'target_filters.daerah' => ['nullable', 'array'],
            'target_filters.pelatihan' => ['nullable', 'array'],
        ]);

        try {
            $notification = new PushNotification([
                'target_type' => $validated['target_type'],
                'target_filters' => $validated['target_filters'] ?? null,
            ]);

            $count = $this->webPushService->countTargets($notification);

            return response()->json([
                'success' => true,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to estimate push notification targets', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung target: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Kirim notifikasi push ke target (diproses di background via queue).
     */
    public function send(PushNotification $notification): RedirectResponse
    {
        if ($notification->sent_at !== null) {
            return back()->with('error', 'Notifikasi sudah pernah dikirim.');
        }

        try {
            // Hitung estimasi jumlah target
            $totalTarget = $this->webPushService->countTargets($notification);

            // Simpan total_target hasil estimasi
            $notification->update(['total_target' => $totalTarget]);

            // Dispatch job ke queue untuk diproses background
            SendPushNotificationJob::dispatch($notification);

            return redirect()
                ->route('admin.push-notifications.show', $notification)
                ->with('success', 'Notifikasi sedang dikirim di background. Pantau riwayat untuk hasil.');
        } catch (\Exception $e) {
            Log::error('Failed to dispatch push notification job', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->with('error', 'Gagal mengirim notifikasi: '.$e->getMessage());
        }
    }
}

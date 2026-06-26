<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Pelatihan;
use App\Models\User;
use App\Services\NotificationService;
use App\Jobs\SendWhatsAppNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationAdminController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function index(Request $request)
    {
        $query = Notification::with(['user', 'template'])
            ->latest();

        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(15)->withQueryString();

        return view('content.admin.notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        $notification->load(['user', 'template']);
        return response()->json($notification);
    }

    public function resend(Notification $notification)
    {
        if ($notification->status !== 'failed') {
            return back()->with('error', 'Only failed notifications can be resent.');
        }

        try {
            if ($notification->channel === 'whatsapp' && $notification->recipient) {
                SendWhatsAppNotification::dispatch(
                    $notification->recipient,
                    $notification->body,
                    $notification->id
                )->onConnection('database');

                $notification->update([
                    'status' => 'pending',
                    'failed_reason' => null,
                    'sent_at' => null,
                ]);
            } elseif ($notification->channel === 'in_app') {
                $notification->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'failed_reason' => null,
                ]);
            }

            return back()->with('success', 'Notification queued for resend.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resend: ' . $e->getMessage());
        }
    }

    public function templates()
    {
        $templates = NotificationTemplate::latest()->paginate(15);
        return view('content.admin.notifications.templates.index', compact('templates'));
    }

    public function createTemplate()
    {
        return view('content.admin.notifications.templates.create');
    }

    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:notification_templates,key',
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'channel' => 'required|in:whatsapp,email,in_app',
            'is_active' => 'boolean',
        ]);

        preg_match_all('/\{(\w+)\}/', $validated['body'], $matches);
        $validated['variables'] = array_values(array_unique($matches[1]));
        $validated['is_active'] = $request->boolean('is_active');

        NotificationTemplate::create($validated);

        return redirect()->route('admin.notification-templates.index')
            ->with('success', 'Template notifikasi berhasil dibuat.');
    }

    public function editTemplate(NotificationTemplate $template)
    {
        return view('content.admin.notifications.templates.edit', compact('template'));
    }

    public function updateTemplate(Request $request, NotificationTemplate $template)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:notification_templates,key,' . $template->id,
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'channel' => 'required|in:whatsapp,email,in_app',
            'is_active' => 'boolean',
        ]);

        preg_match_all('/\{(\w+)\}/', $validated['body'], $matches);
        $validated['variables'] = array_values(array_unique($matches[1]));
        $validated['is_active'] = $request->boolean('is_active');

        $template->update($validated);

        return redirect()->route('admin.notification-templates.index')
            ->with('success', 'Template notifikasi berhasil diperbarui.');
    }

    public function destroyTemplate(NotificationTemplate $template)
    {
        $template->delete();
        return back()->with('success', 'Template notifikasi berhasil dihapus.');
    }

    public function testTemplate(Request $request, NotificationTemplate $template)
    {
        $admin = auth()->user();

        if (!$admin->whatsapp) {
            return back()->with('error', 'Your account does not have a WhatsApp number configured.');
        }

        $sampleData = [
            'nama' => $admin->name,
            'pelatihan' => 'Pelatihan Ekonomi Kreatif',
            'tanggal' => now()->format('d/m/Y'),
            'tugas' => 'Tugas Modul 1',
            'link' => url('/'),
        ];

        $rendered = $this->notificationService->renderTemplate($template, $sampleData);

        $notification = Notification::create([
            'user_id' => $admin->id,
            'notification_template_id' => $template->id,
            'channel' => 'whatsapp',
            'recipient' => $admin->whatsapp,
            'title' => $rendered['title'],
            'body' => $rendered['body'],
            'data' => $sampleData,
            'status' => 'pending',
        ]);

        SendWhatsAppNotification::dispatch($admin->whatsapp, $rendered['body'], $notification->id)
            ->onConnection('database');

        return back()->with('success', 'Test message sent to your WhatsApp number.');
    }

    public function broadcast()
    {
        $pelatihans = Pelatihan::where('is_active', true)->orderBy('nama')->get();
        $templates = NotificationTemplate::where('is_active', true)
            ->where('channel', 'whatsapp')
            ->orderBy('name')
            ->get();

        $recentBroadcasts = Notification::where('channel', 'whatsapp')
            ->whereIn('id', function ($q) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('notifications')
                    ->where('channel', 'whatsapp')
                    ->groupBy('user_id');
            })
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('content.admin.notifications.broadcast', compact('pelatihans', 'templates', 'recentBroadcasts'));
    }

    public function estimateCount(Request $request)
    {
        $validated = $request->validate([
            'target' => 'required|in:all_peserta,by_pelatihan,all_koordinator,by_enrollment_status,custom',
            'pelatihan_id' => 'required_if:target,by_pelatihan|exists:pelatihan,id',
            'enrollment_status' => 'required_if:target,by_enrollment_status|in:pending,approved,waiting_wa_confirmation,waiting_newbimma_check,confirmed,rejected,waitlist',
        ]);

        $count = 0;

        switch ($validated['target']) {
            case 'all_peserta':
                $count = User::where('role', 'peserta')->where('is_active', true)->whereNotNull('whatsapp')->count();
                break;

            case 'by_pelatihan':
                $pesertaIds = \App\Models\PesertaProfile::where('pelatihan_id', $validated['pelatihan_id'])->pluck('user_id');
                $count = User::whereIn('id', $pesertaIds)->where('is_active', true)->whereNotNull('whatsapp')->count();
                break;

            case 'all_koordinator':
                $count = User::where('role', 'koordinator')->where('is_active', true)->whereNotNull('whatsapp')->count();
                break;

            case 'by_enrollment_status':
                $userIds = \App\Models\Enrollment::where('status', $validated['enrollment_status'])
                    ->pluck('user_id')
                    ->unique()
                    ->values();
                $count = User::whereIn('id', $userIds)->where('is_active', true)->whereNotNull('whatsapp')->count();
                break;

            case 'custom':
                $count = 0;
                if ($request->hasFile('csv_file')) {
                    $file = $request->file('csv_file');
                    $count = collect(file($file->getRealPath()))
                        ->map(fn($line) => trim($line))
                        ->filter(fn($line) => !empty($line))
                        ->unique()
                        ->count();
                }
                break;
        }

        return response()->json(['count' => $count]);
    }

    public function sendBroadcast(Request $request)
    {
        $validated = $request->validate([
            'target' => 'required|in:all_peserta,by_pelatihan,all_koordinator,by_enrollment_status,custom',
            'pelatihan_id' => 'required_if:target,by_pelatihan|exists:pelatihan,id',
            'enrollment_status' => 'required_if:target,by_enrollment_status|in:pending,approved,waiting_wa_confirmation,waiting_newbimma_check,confirmed,rejected,waitlist',
            'template_id' => 'nullable|exists:notification_templates,id',
            'custom_message' => 'required_without:template_id|nullable|string',
            'csv_file' => 'nullable|file|mimes:csv,txt',
        ]);

        $recipients = collect();

        switch ($validated['target']) {
            case 'all_peserta':
                $recipients = User::where('role', 'peserta')
                    ->where('is_active', true)
                    ->whereNotNull('whatsapp')
                    ->get();
                break;

            case 'by_pelatihan':
                $pesertaIds = \App\Models\PesertaProfile::where('pelatihan_id', $validated['pelatihan_id'])
                    ->pluck('user_id');
                $recipients = User::whereIn('id', $pesertaIds)
                    ->where('is_active', true)
                    ->whereNotNull('whatsapp')
                    ->get();
                break;

            case 'all_koordinator':
                $recipients = User::where('role', 'koordinator')
                    ->where('is_active', true)
                    ->whereNotNull('whatsapp')
                    ->get();
                break;

            case 'by_enrollment_status':
                $userIds = \App\Models\Enrollment::where('status', $validated['enrollment_status'])
                    ->pluck('user_id')
                    ->unique()
                    ->values();
                $recipients = User::whereIn('id', $userIds)
                    ->where('is_active', true)
                    ->whereNotNull('whatsapp')
                    ->get();
                break;

            case 'custom':
                if ($request->hasFile('csv_file')) {
                    $file = $request->file('csv_file');
                    $numbers = collect(file($file->getRealPath()))
                        ->map(fn($line) => trim($line))
                        ->filter(fn($line) => !empty($line));
                    $recipients = $numbers->map(function ($number) {
                        $user = new \stdClass();
                        $user->whatsapp = $number;
                        $user->name = $number;
                        return $user;
                    });
                }
                break;
        }

        if ($recipients->isEmpty()) {
            return back()->with('error', 'No recipients found for the selected target.');
        }

        $template = null;
        $message = $validated['custom_message'] ?? '';

        if ($validated['template_id']) {
            $template = NotificationTemplate::find($validated['template_id']);
        }

        $queued = 0;
        foreach ($recipients as $recipient) {
            $body = $message;
            $title = null;

            if ($template) {
                $renderData = [
                    'nama' => $recipient->name ?? 'Peserta',
                    'pelatihan' => 'Pelatihan',
                    'tanggal' => now()->format('d/m/Y'),
                ];
                $rendered = $this->notificationService->renderTemplate($template, $renderData);
                $title = $rendered['title'];
                $body = $rendered['body'];
            }

            $notification = Notification::create([
                'user_id' => $recipient->id ?? null,
                'notification_template_id' => $template?->id,
                'channel' => 'whatsapp',
                'recipient' => $recipient->whatsapp,
                'title' => $title,
                'body' => $body,
                'data' => ['broadcast' => true, 'target' => $validated['target']],
                'status' => 'pending',
            ]);

            SendWhatsAppNotification::dispatch($recipient->whatsapp, $body, $notification->id)
                ->onConnection('database');

            $queued++;
        }

        return back()->with('success', "Broadcast queued successfully for {$queued} recipients.");
    }
}

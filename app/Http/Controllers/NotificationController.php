<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\UserNotificationPreference;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unread(Request $request)
    {
        $user = $request->user();

        $items = Notification::where('user_id', $user->id)
            ->where('channel', 'in_app')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'body' => $n->body,
                    'channel' => $n->channel,
                    'read_at' => $n->read_at,
                    'time_ago' => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->toISOString(),
                ];
            });

        $count = Notification::where('user_id', $user->id)
            ->where('channel', 'in_app')
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'count' => $count,
            'items' => $items,
        ]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('channel', 'in_app')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Notification::where('user_id', $user->id)
            ->where('channel', 'in_app');

        if ($request->filled('channel') && $request->channel !== 'all') {
            $query->where('channel', $request->channel);
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('content.notifications.index', compact('notifications'));
    }

    public function preferences(Request $request)
    {
        $preferences = UserNotificationPreference::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'whatsapp_enabled' => true,
                'email_enabled' => true,
                'in_app_enabled' => true,
            ]
        );

        return view('content.notifications.preferences', compact('preferences'));
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'whatsapp_enabled' => 'boolean',
            'email_enabled' => 'boolean',
            'in_app_enabled' => 'boolean',
            'quiet_hours_start' => 'nullable|date_format:H:i',
            'quiet_hours_end' => 'nullable|date_format:H:i',
        ]);

        $data['whatsapp_enabled'] = $request->boolean('whatsapp_enabled');
        $data['email_enabled'] = $request->boolean('email_enabled');
        $data['in_app_enabled'] = $request->boolean('in_app_enabled');

        UserNotificationPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui.');
    }
}

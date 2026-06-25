<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheController extends Controller
{
    public function index()
    {
        $lastCleared = session('last_cleared', 'Belum pernah');
        return view('content.admin.cache.index', compact('lastCleared'));
    }

    public function clear()
    {
        $start = microtime(true);
        $results = [];

        try {
            Cache::flush();
            $results[] = 'Cache aplikasi';

            Artisan::call('view:clear');
            $results[] = 'Compiled views';

            Artisan::call('config:clear');
            $results[] = 'Config cache';

            Artisan::call('route:clear');
            $results[] = 'Route cache';

            Artisan::call('optimize:clear');
            $results[] = 'Compiled services & manifest';

            $duration = round(microtime(true) - $start, 2);

            $now = now()->translatedFormat('d M Y H:i');
            session(['last_cleared' => $now]);

            Log::info('Cache berhasil dibersihkan oleh admin', [
                'admin' => auth()->user()?->name,
                'duration' => $duration . ' detik'
            ]);

            return redirect()->route('admin.cache.index')->with('success', [
                'message' => 'Cache berhasil dibersihkan!',
                'details' => $results,
                'duration' => $duration
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal membersihkan cache: ' . $e->getMessage());
            return redirect()->route('admin.cache.index')->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }
}

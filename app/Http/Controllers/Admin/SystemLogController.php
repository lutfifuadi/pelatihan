<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SystemLogController extends Controller
{
    private const LOG_FILE = 'logs/laravel.log';

    /**
     * Tampilkan halaman log viewer.
     */
    public function index()
    {
        return view('content.admin.system-logs.index');
    }

    /**
     * Ambil data log dalam format JSON.
     */
    public function getLogs(Request $request): JsonResponse
    {
        $level = $request->input('level');
        $search = $request->input('search');
        $limit = min((int) $request->input('limit', 100), 1000);

        $path = storage_path(self::LOG_FILE);

        if (!File::exists($path)) {
            return response()->json(['logs' => [], 'total' => 0]);
        }

        $lines = collect(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        // Parse setiap baris menjadi entry log
        $logs = $lines->map(function ($line) {
            return $this->parseLogLine($line);
        });

        // Filter berdasarkan level
        if ($level) {
            $logs = $logs->filter(function ($log) use ($level) {
                return isset($log['level']) && strtolower($log['level']) === strtolower($level);
            });
        }

        // Filter berdasarkan search keyword
        if ($search) {
            $searchLower = strtolower($search);
            $logs = $logs->filter(function ($log) use ($searchLower) {
                return str_contains(strtolower($log['message'] ?? ''), $searchLower)
                    || str_contains(strtolower($log['raw'] ?? ''), $searchLower);
            });
        }

        // Ambil N baris terakhir
        $logs = $logs->reverse()->take($limit)->values();

        return response()->json([
            'logs' => $logs,
            'total' => $logs->count(),
        ]);
    }

    /**
     * Parse satu baris log Laravel.
     */
    private function parseLogLine(string $line): array
    {
        // Pattern Laravel log: [2024-01-15 10:20:30] local.ERROR: pesan {context}
        $pattern = '/^\[(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s*(.*)$/';

        if (preg_match($pattern, $line, $matches)) {
            return [
                'datetime' => $matches[1],
                'environment' => $matches[2],
                'level' => strtoupper($matches[3]),
                'message' => $matches[4],
                'raw' => $line,
            ];
        }

        // Kalau tidak match pattern, anggap sebagai continuation baris sebelumnya
        return [
            'datetime' => '-',
            'environment' => '-',
            'level' => 'INFO',
            'message' => $line,
            'raw' => $line,
        ];
    }

    /**
     * Download file log.
     */
    public function download()
    {
        $path = storage_path(self::LOG_FILE);

        if (!File::exists($path)) {
            return back()->with('error', 'File log tidak ditemukan.');
        }

        return response()->download($path, 'laravel-' . now()->format('Ymd-His') . '.log');
    }

    /**
     * Hapus isi file log.
     */
    public function clear()
    {
        $path = storage_path(self::LOG_FILE);

        if (File::exists($path)) {
            File::put($path, '');
        }

        return back()->with('success', 'File log berhasil dikosongkan.');
    }
}

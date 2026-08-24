<?php

namespace App\Http\Controllers\Admin;

use App\Facades\Feature;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use App\Services\SettingsManager;
use App\Support\FeatureDefaults;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeatureToggleController extends Controller
{
    protected SettingsManager $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Tampilkan halaman khusus Aktivasi Fitur.
     */
    public function index()
    {
        $features = Feature::list();
        $featuresGrouped = Feature::listGrouped();
        $categories = FeatureDefaults::categories();

        $totalCount = count($features);
        $activeCount = count(array_filter($features, fn ($f) => !empty($f['is_on'])));
        $inactiveCount = $totalCount - $activeCount;
        $activePercentage = $totalCount > 0 ? round(($activeCount / $totalCount) * 100) : 0;

        // Ambil log aktivitas perubahan fitur terbaru
        $recentLogs = ActivityLog::with('user:id,name,role')
            ->where('subject_type', 'Pengaturan Fitur')
            ->recent()
            ->limit(10)
            ->get();

        return view('content.admin.fitur.index', compact(
            'features',
            'featuresGrouped',
            'categories',
            'totalCount',
            'activeCount',
            'inactiveCount',
            'activePercentage',
            'recentLogs'
        ));
    }

    /**
     * AJAX toggle switch handler.
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'key'   => 'required|string',
            'value' => 'required',
        ]);

        $key = $request->input('key');
        if (!FeatureDefaults::has($key)) {
            return response()->json([
                'success' => false,
                'message' => 'Kunci fitur tidak dikenali oleh sistem.',
            ], 422);
        }

        $meta = FeatureDefaults::get($key);
        $rawVal = $request->input('value');
        $newVal = filter_var($rawVal, FILTER_VALIDATE_BOOLEAN);
        $oldVal = Feature::isOn($key);

        $this->settings->setBool($key, $newVal, 'fitur', $meta['label'] ?? null);

        // Catat ke ActivityLog
        $statusText = $newVal ? 'Aktif (ON)' : 'Non-Aktif (OFF)';
        ActivityLogger::log(
            action: 'updated',
            subjectType: 'Pengaturan Fitur',
            subjectId: null,
            subjectName: $meta['label'] ?? $key,
            description: "Mengubah status fitur \"{$meta['label']}\" menjadi {$statusText}",
            oldValues: ['key' => $key, 'is_on' => $oldVal],
            newValues: ['key' => $key, 'is_on' => $newVal]
        );

        return response()->json([
            'success' => true,
            'message' => "Fitur \"{$meta['label']}\" berhasil diubah menjadi {$statusText}.",
            'key'     => $key,
            'is_on'   => $newVal,
        ]);
    }

    /**
     * Bulk toggle semua fitur atau berdasarkan kategori.
     */
    public function bulkToggle(Request $request): JsonResponse
    {
        $request->validate([
            'state'    => 'required|in:1,0,true,false',
            'category' => 'nullable|string',
        ]);

        $state = filter_var($request->input('state'), FILTER_VALIDATE_BOOLEAN);
        $category = $request->input('category');
        $definitions = FeatureDefaults::definitions();

        $updatedCount = 0;
        foreach ($definitions as $key => $meta) {
            if ($category && ($meta['category'] ?? '') !== $category) {
                continue;
            }
            $this->settings->setBool($key, $state, 'fitur', $meta['label'] ?? null);
            $updatedCount++;
        }

        $statusText = $state ? 'diaktifkan' : 'dinonaktifkan';
        $scopeText = $category ? "kategori \"{$category}\"" : 'seluruh sistem';

        ActivityLogger::log(
            action: 'updated',
            subjectType: 'Pengaturan Fitur',
            subjectId: null,
            subjectName: 'Bulk Toggle',
            description: "Semua fitur pada {$scopeText} ({$updatedCount} fitur) berhasil {$statusText}",
            oldValues: null,
            newValues: ['category' => $category, 'state' => $state]
        );

        return response()->json([
            'success' => true,
            'message' => "Sebanyak {$updatedCount} fitur {$statusText}.",
            'count'   => $updatedCount,
            'state'   => $state,
        ]);
    }

    /**
     * Reset semua fitur ke nilai bawaan (default SOT).
     */
    public function resetDefaults(): JsonResponse
    {
        $defaults = FeatureDefaults::defaults();
        foreach ($defaults as $key => $val) {
            $meta = FeatureDefaults::get($key);
            $this->settings->setBool($key, $val === '1', 'fitur', $meta['label'] ?? null);
        }

        ActivityLogger::log(
            action: 'updated',
            subjectType: 'Pengaturan Fitur',
            subjectId: null,
            subjectName: 'Reset Default',
            description: 'Seluruh konfigurasi fitur toggle dikembalikan ke nilai default pabrik.',
            oldValues: null,
            newValues: $defaults
        );

        return response()->json([
            'success' => true,
            'message' => 'Seluruh fitur telah berhasil direset ke konfigurasi awal bawaan.',
        ]);
    }
}

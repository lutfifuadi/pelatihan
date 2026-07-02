<?php

require __DIR__ . '/api_pengumuman.php';


use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/panitia/check-in', [App\Http\Controllers\Api\AttendanceApiController::class, 'panitiaCheckIn']);
    Route::post('/panitia/bypass-attendance', [App\Http\Controllers\Api\AttendanceApiController::class, 'panitiaBypass']);
    Route::get('/pelatihan/{pelatihan_id}/realtime-attendance', [App\Http\Controllers\Api\AttendanceApiController::class, 'getRealtimeAttendance']);
    Route::get('/panitia/pelatihan/{pelatihan_id}/search-peserta', [App\Http\Controllers\Api\AttendanceApiController::class, 'searchPeserta']);
});

// Public Web Push API
Route::prefix('push')->group(function () {
    Route::post('/subscribe', [PushSubscriptionController::class, 'store'])
        ->name('api.push.subscribe');

    Route::get('/vapid-public-key', [PushSubscriptionController::class, 'vapidPublicKey'])
        ->name('api.push.vapid-public-key');
});

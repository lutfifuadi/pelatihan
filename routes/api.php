<?php

use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Web Push API
Route::prefix('push')->group(function () {
    Route::post('/subscribe', [PushSubscriptionController::class, 'store'])
        ->name('api.push.subscribe');

    Route::get('/vapid-public-key', [PushSubscriptionController::class, 'vapidPublicKey'])
        ->name('api.push.vapid-public-key');
});

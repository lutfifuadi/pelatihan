<?php

namespace App\Services;

use App\Models\GoogleDriveSetting;

class GoogleDriveConfigService
{
    public static function getConfig(): array
    {
        $setting = GoogleDriveSetting::first();

        return [
            'client_id' => $setting?->google_client_id ?? config('services.google.client_id'),
            'client_secret' => $setting?->google_client_secret ?? config('services.google.client_secret'),
            'redirect_uri' => $setting?->google_redirect_uri ?? config('services.google.redirect'),
            'root_folder_id' => $setting?->google_root_folder_id ?? '1Zm_y2iQmRMvfeZ9wD1LVyXaLyFCGV0bK',
            'access_token' => $setting?->google_access_token,
            'refresh_token' => $setting?->google_refresh_token,
            'token_expires_at' => $setting?->google_token_expires_at,
            'is_connected' => $setting?->is_connected ?? false,
        ];
    }

    /**
     * No-op: cache sudah dihapus dari service ini.
     * Method dipertahankan untuk kompatibilitas dengan pemanggil existing.
     */
    public static function clearCache(): void
    {
        // Cache tidak lagi digunakan — semua data real-time.
    }
}

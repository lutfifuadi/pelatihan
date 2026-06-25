<?php

namespace App\Http\Controllers;

use Google\Client;
use App\Models\GoogleDriveSetting;
use App\Services\GoogleDriveConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Google\Service\Drive;

class GoogleAuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $config = GoogleDriveConfigService::getConfig();

        $this->client = new Client();
        $this->client->setClientId($config['client_id']);
        $this->client->setClientSecret($config['client_secret']);
        $this->client->setRedirectUri($config['redirect_uri']);
        $this->client->addScope(\Google\Service\Drive::DRIVE);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    public function redirectToGoogle()
    {
        $authUrl = $this->client->createAuthUrl();
        return Redirect::away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $error = $request->query('error');

        if ($error) {
            return redirect()->route('admin.google-drive.status')
                ->with('error', 'Akses Google Drive ditolak. Silakan coba lagi.');
        }

        $code = $request->query('code');

        if (!$code) {
            return redirect()->route('admin.google-drive.status')
                ->with('error', 'Kode otorisasi tidak ditemukan.');
        }

        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                return redirect()->route('admin.google-drive.status')
                    ->with('error', 'Gagal mendapatkan token: ' . ($token['error_description'] ?? $token['error']));
            }

            $setting = GoogleDriveSetting::firstOrNew();
            $setting->google_access_token = json_encode($token);
            $setting->is_connected = true;

            if (isset($token['refresh_token'])) {
                $setting->google_refresh_token = $token['refresh_token'];
            }

            if (isset($token['expires_in'])) {
                $setting->google_token_expires_at = now()->addSeconds($token['expires_in']);
            }

            $setting->save();

            GoogleDriveConfigService::clearCache();

            return redirect()->route('admin.google-drive.status')
                ->with('success', 'Berhasil terhubung ke Google Drive!');
        } catch (\Exception $e) {
            return redirect()->route('admin.google-drive.status')
                ->with('error', 'Gagal mendapatkan token: ' . $e->getMessage());
        }
    }

    public function checkGoogleStatus()
    {
        $setting = GoogleDriveSetting::first();

        if (!$setting || !$setting->google_access_token) {
            return response()->json([
                'connected' => false,
                'message' => 'Belum terhubung ke Google Drive.',
            ]);
        }

        try {
            $token = json_decode($setting->google_access_token, true);
            $this->client->setAccessToken($token);

            if ($this->client->isAccessTokenExpired()) {
                if ($setting->google_refresh_token) {
                    $this->client->fetchAccessTokenWithRefreshToken($setting->google_refresh_token);
                    $newToken = $this->client->getAccessToken();

                    $setting->update([
                        'google_access_token' => json_encode($newToken),
                    ]);

                    GoogleDriveConfigService::clearCache();

                    return response()->json([
                        'connected' => true,
                        'message' => 'Token berhasil diperbarui.',
                    ]);
                }

                return response()->json([
                    'connected' => false,
                    'message' => 'Token sudah kedaluwarsa dan tidak dapat diperbarui.',
                ]);
            }

            $drive = new \Google\Service\Drive($this->client);
            $about = $drive->about->get(['fields' => 'storageQuota,user']);

            return response()->json([
                'connected' => true,
                'message' => 'Terhubung ke Google Drive.',
                'user' => $about->getUser()->emailAddress ?? null,
                'storage' => [
                    'limit' => $about->getStorageQuota()->getLimit(),
                    'usage' => $about->getStorageQuota()->getUsage(),
                    'usageInDrive' => $about->getStorageQuota()->getUsageInDrive(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'message' => 'Gagal memeriksa status: ' . $e->getMessage(),
            ]);
        }
    }

    public function revokeGoogleAccess()
    {
        $setting = GoogleDriveSetting::first();

        if (!$setting || !$setting->google_access_token) {
            return redirect()->route('admin.google-drive.status')
                ->with('error', 'Tidak ada token yang dicabut.');
        }

        try {
            $token = json_decode($setting->google_access_token, true);
            $this->client->setAccessToken($token);
            $this->client->revokeToken();
        } catch (\Exception $e) {
            // Lanjutkan hapus dari DB meski revoke gagal
        }

        $setting->update([
            'google_access_token' => null,
            'google_refresh_token' => null,
            'google_token_expires_at' => null,
            'is_connected' => false,
        ]);

        GoogleDriveConfigService::clearCache();

        return redirect()->route('admin.google-drive.status')
            ->with('success', 'Akses Google Drive berhasil dicabut.');
    }

    public function statusPage()
    {
        $setting = GoogleDriveSetting::first();
        $connected = false;
        $storageInfo = null;
        $userEmail = null;
        $tokenExpired = false;
        $tokenData = $setting?->google_access_token;

        if ($tokenData) {
            $connected = true;
            try {
                $token = json_decode($tokenData, true);
                $this->client->setAccessToken($token);

                if ($this->client->isAccessTokenExpired()) {
                    if ($setting->google_refresh_token) {
                        $this->client->fetchAccessTokenWithRefreshToken($setting->google_refresh_token);
                        $newToken = $this->client->getAccessToken();
                        $setting->update([
                            'google_access_token' => json_encode($newToken),
                        ]);
                        GoogleDriveConfigService::clearCache();
                    } else {
                        $tokenExpired = true;
                    }
                }

                if (!$tokenExpired) {
                    $drive = new Drive($this->client);
                    $about = $drive->about->get(['fields' => 'storageQuota,user']);
                    $userEmail = $about->getUser()->emailAddress ?? null;
                    $storageQuota = $about->getStorageQuota();
                    $storageInfo = [
                        'limit' => $storageQuota->getLimit(),
                        'usage' => $storageQuota->getUsage(),
                        'usageInDrive' => $storageQuota->getUsageInDrive(),
                    ];
                }
            } catch (\Exception $e) {
                $connected = false;
            }
        }

        return view('content.admin.google-drive.index', compact(
            'connected', 'storageInfo', 'userEmail', 'tokenExpired'
        ));
    }

    public function edit()
    {
        $setting = GoogleDriveSetting::firstOrNew();
        return view('content.admin.google-drive.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'google_redirect_uri' => 'nullable|url|max:255',
            'google_root_folder_id' => 'nullable|string|max:100',
        ]);

        $setting = GoogleDriveSetting::firstOrNew();
        $setting->fill($validated);

        if (!$request->filled('google_client_secret') || $request->input('google_client_secret') === '********') {
            unset($setting->google_client_secret);
        }

        $setting->save();

        GoogleDriveConfigService::clearCache();

        return redirect()->back()->with('success', 'Pengaturan Google Drive berhasil disimpan.');
    }
}

<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;

class FolderUploadService
{
    protected ImageService $imageService;
    protected GoogleDriveService $driveService;
    protected string $mainFolderId;

    public function __construct()
    {
        $config = GoogleDriveConfigService::getConfig();
        $this->mainFolderId = $config['root_folder_id'];
        $this->imageService = new ImageService();
        $this->driveService = new GoogleDriveService();
    }

    /**
     * Upload foto peserta ke Google Drive.
     *
     * @param User $user
     * @param string|null $fotoDiriBase64 Base64 foto diri (nullable)
     * @param string|null $fotoKtpBase64 Base64 foto KTP (nullable)
     * @return array ['success' => bool, 'foto_diri_url' => ?, 'foto_ktp_url' => ?, 'folder_id' => ?, 'message' => ?]
     */
    public function uploadFotoPeserta(User $user, ?string $fotoDiriBase64 = null, ?string $fotoKtpBase64 = null): array
    {
        $result = [
            'success' => false,
            'foto_diri_url' => null,
            'foto_ktp_url' => null,
            'folder_id' => null,
            'message' => null,
        ];

        $namaFolder = strtoupper($user->nik . '-' . $user->name);

        try {
            $folderPesertaId = $this->driveService->ensureFolderExists($namaFolder, $this->mainFolderId);
            $result['folder_id'] = $folderPesertaId;

            if ($fotoDiriBase64) {
                $binary = $this->imageService->resizeBase64ToBinary($fotoDiriBase64, 500, 500);
                $fileName = $user->nik . '.jpg';
                $uploadedFile = $this->driveService->uploadFile($binary, $fileName, $folderPesertaId);
                $result['foto_diri_url'] = $uploadedFile['url'] ?? null;
            }

            if ($fotoKtpBase64) {
                $binary = $this->imageService->resizeBase64ToBinary($fotoKtpBase64, 500, 500);
                $fileName = $user->nik . '_ktp.jpg';
                $uploadedFile = $this->driveService->uploadFile($binary, $fileName, $folderPesertaId);
                $result['foto_ktp_url'] = $uploadedFile['url'] ?? null;
            }

            // Validasi hanya berhasil jika semua hasil upload tidak null
            $folderOk = $result['folder_id'] !== null;
            $fotoDiriOk = !$fotoDiriBase64 || $result['foto_diri_url'] !== null;
            $fotoKtpOk = !$fotoKtpBase64 || $result['foto_ktp_url'] !== null;

            if ($folderOk && $fotoDiriOk && $fotoKtpOk) {
                $result['success'] = true;
            } else {
                $failed = [];
                if (!$folderOk) {
                    $failed[] = 'folder peserta';
                }
                if (!$fotoDiriOk) {
                    $failed[] = 'foto diri';
                }
                if (!$fotoKtpOk) {
                    $failed[] = 'foto KTP';
                }
                $result['message'] = 'Gagal upload ' . implode(', ', $failed) . '. Silakan coba lagi.';
            }
        } catch (\Exception $e) {
            report($e);
            $result['message'] = 'Terjadi kesalahan saat upload: ' . $e->getMessage();
        }

        return $result;
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\FolderUploadService;

class UploadFoto extends Component
{
    public ?string $fotoDiriBase64 = null;
    public ?string $fotoKtpBase64 = null;
    public string $step = 'awal';
    public bool $uploading = false;
    public ?string $errorMessage = null;
    public string $captureMode = 'foto';

    public function mount()
    {
        $user = auth()->user();

        if ($user->google_drive_photo_url && $user->google_drive_ktp_url) {
            $this->step = 'selesai';
        }
    }

    public function setStep(string $step): void
    {
        $this->step = $step;
        $this->errorMessage = null;
    }

    public function mulaiCapture(string $mode): void
    {
        $this->captureMode = $mode;
        $this->step = 'capture';
        $this->errorMessage = null;
    }

    public function handleFotoCaptured(string $mode, string $image, string $mimeType): void
    {
        if ($mode === 'foto') {
            $this->fotoDiriBase64 = $image;
        } elseif ($mode === 'ktp') {
            $this->fotoKtpBase64 = $image;
        }

        $this->step = 'awal';
    }

    public function uploadToDrive()
    {
        if (!$this->fotoDiriBase64 && !$this->fotoKtpBase64) {
            $this->errorMessage = 'Belum ada foto yang di-capture.';
            return;
        }

        $this->uploading = true;
        $this->errorMessage = null;

        try {
            $service = new FolderUploadService();
            $result = $service->uploadFotoPeserta(
                auth()->user(),
                $this->fotoDiriBase64,
                $this->fotoKtpBase64
            );

            if ($result['success']) {
                if (($this->fotoDiriBase64 && empty($result['foto_diri_url'])) ||
                    ($this->fotoKtpBase64 && empty($result['foto_ktp_url']))) {
                    $this->errorMessage = 'Upload sebagian gagal. URL foto tidak ditemukan.';
                } else {
                    $user = auth()->user();
                    $user->google_drive_photo_url = $result['foto_diri_url'];
                    $user->google_drive_ktp_url = $result['foto_ktp_url'];
                    $user->google_drive_folder_id = $result['folder_id'];
                    $user->save();

                    $this->step = 'selesai';
                }
            } else {
                $this->errorMessage = $result['message'] ?? 'Gagal upload ke Google Drive. Silakan coba lagi.';
            }
        } catch (\Exception $e) {
            report($e);
            $this->errorMessage = 'Terjadi kesalahan saat upload. Silakan coba lagi.';
        }

        $this->uploading = false;
    }

    protected function getListeners()
    {
        return ['foto-captured' => 'handleFotoCaptured'];
    }

    public function render()
    {
        return view('livewire.upload-foto');
    }
}

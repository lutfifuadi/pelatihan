<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FotoCapture extends Component
{
    use WithFileUploads;

    public string $mode = 'foto';
    public ?string $capturedImage = null;
    public ?string $capturedMimeType = null;
    public string $step = 'camera';
    public $manualFile = null;
    public ?string $manualPreview = null;
    public bool $isProcessing = false;
    public ?string $errorMessage = null;

    public function mount($mode = 'foto')
    {
        $this->setMode($mode);
    }

    protected function rules()
    {
        return [
            'manualFile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function setMode(string $mode): void
    {
        if (in_array($mode, ['foto', 'ktp'])) {
            $this->mode = $mode;
            $this->resetCapture();
        }
    }

    public function resetCapture(): void
    {
        $this->capturedImage = null;
        $this->capturedMimeType = null;
        $this->step = 'camera';
        $this->manualFile = null;
        $this->manualPreview = null;
        $this->isProcessing = false;
        $this->errorMessage = null;
    }

    public function confirmCapture(string $base64Data, string $mimeType = 'image/jpeg'): void
    {
        $this->capturedImage = $base64Data;
        $this->capturedMimeType = $mimeType;
        $this->isProcessing = true;

        $this->dispatch('foto-captured', mode: $this->mode, image: $base64Data, mimeType: $mimeType);

        $this->isProcessing = false;
    }

    public function retake(): void
    {
        $this->resetCapture();
    }

    public function useCamera(): void
    {
        $this->step = 'camera';
        $this->errorMessage = null;
    }

    public function useManualUpload(): void
    {
        $this->step = 'upload';
        $this->errorMessage = null;
    }

    public function updatedManualFile(): void
    {
        $this->validate();

        $this->manualPreview = null;

        if ($this->manualFile) {
            $this->manualPreview = $this->manualFile->temporaryUrl();
        }
    }

    public function confirmManualUpload(): void
    {
        if (!$this->manualFile) {
            $this->errorMessage = 'Silakan pilih file terlebih dahulu.';
            return;
        }

        $this->validate();

        $this->isProcessing = true;

        $path = $this->manualFile->store('temp/captures', 'public');
        $fullPath = Storage::disk('public')->path($path);
        $mimeType = $this->manualFile->getMimeType() ?? 'image/jpeg';
        $base64 = base64_encode(file_get_contents($fullPath));

        Storage::disk('public')->delete($path);

        $this->capturedImage = $base64;
        $this->capturedMimeType = $mimeType;

        $this->dispatch('foto-captured', mode: $this->mode, image: $base64, mimeType: $mimeType);

        $this->isProcessing = false;
    }

    public function render()
    {
        return view('livewire.foto-capture');
    }
}

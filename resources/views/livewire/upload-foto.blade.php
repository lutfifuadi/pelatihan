<div>
    {{-- ============================================================ --}}
    {{-- STATE: Awal (Belum Upload / Status) --}}
    {{-- ============================================================ --}}
    @if($step === 'awal')
        <div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                    <i class="icon-base ti tabler-camera text-primary"></i>
                    Status Foto
                </h5>
            </div>

            <p class="text-body-premium mb-4" style="font-size: 0.95rem;">
                Silakan ambil foto diri dan foto KTP Anda untuk melengkapi data peserta.
            </p>

            {{-- Status Foto Diri --}}
            <div class="d-flex align-items-center justify-content-between p-3 rounded mb-3"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.06);">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-box {{ $fotoDiriBase64 ? 'stat-icon-success' : 'stat-icon-secondary' }}"
                        style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="icon-base ti {{ $fotoDiriBase64 ? 'tabler-check' : 'tabler-user' }}"></i>
                    </div>
                    <div>
                        <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Foto Diri</h6>
                        <small class="text-body-premium">
                            {{ $fotoDiriBase64 ? 'Sudah di-capture' : 'Belum upload' }}
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-glass fw-semibold py-2 px-3"
                    style="font-size: 12px;"
                    wire:click="mulaiCapture('foto')">
                    <i class="icon-base ti tabler-camera me-1"></i>
                    {{ $fotoDiriBase64 ? 'Ambil Ulang' : 'Ambil Foto Diri' }}
                </button>
            </div>

            {{-- Status Foto KTP --}}
            <div class="d-flex align-items-center justify-content-between p-3 rounded mb-4"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.06);">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-box {{ $fotoKtpBase64 ? 'stat-icon-success' : 'stat-icon-secondary' }}"
                        style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="icon-base ti {{ $fotoKtpBase64 ? 'tabler-check' : 'tabler-id' }}"></i>
                    </div>
                    <div>
                        <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Foto KTP</h6>
                        <small class="text-body-premium">
                            {{ $fotoKtpBase64 ? 'Sudah di-capture' : 'Belum upload' }}
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-glass fw-semibold py-2 px-3"
                    style="font-size: 12px;"
                    wire:click="mulaiCapture('ktp')">
                    <i class="icon-base ti tabler-camera me-1"></i>
                    {{ $fotoKtpBase64 ? 'Ambil Ulang' : 'Ambil Foto KTP' }}
                </button>
            </div>

            {{-- Tombol Upload --}}
            @if($fotoDiriBase64 || $fotoKtpBase64)
                <div class="text-center">
                    <button type="button" class="btn btn-glow-premium px-5 py-2 fw-bold text-uppercase"
                        style="letter-spacing: 0.05em;"
                        wire:click="uploadToDrive"
                        wire:loading.attr="disabled"
                        wire:target="uploadToDrive">
                        <span wire:loading.remove wire:target="uploadToDrive">
                            <i class="icon-base ti tabler-cloud-upload me-1"></i> Upload Sekarang
                        </span>
                        <span wire:loading wire:target="uploadToDrive">
                            <span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span>
                            Mengupload...
                        </span>
                    </button>
                </div>
            @endif

            {{-- Error Message --}}
            @if($errorMessage)
                <div class="mt-3 p-3 rounded"
                    style="background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.2);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="icon-base ti tabler-alert-circle text-danger fs-5"></i>
                        <span class="text-danger small fw-semibold">{{ $errorMessage }}</span>
                    </div>
                </div>
            @endif
        </div>

    {{-- ============================================================ --}}
    {{-- STATE: Capture (Embed FotoCapture) --}}
    {{-- ============================================================ --}}
    @elseif($step === 'capture')
        <div>
            <div class="mb-3">
                <button type="button" class="btn btn-outline-glass fw-semibold py-1 px-2"
                    style="font-size: 11px;"
                    wire:click="setStep('awal')">
                    <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali
                </button>
            </div>

            <livewire:foto-capture wire:key="foto-capture-{{ $captureMode }}" :mode="$captureMode" />
        </div>

    {{-- ============================================================ --}}
    {{-- STATE: Selesai --}}
    {{-- ============================================================ --}}
    @elseif($step === 'selesai')
        <div class="text-center">
            <div class="stat-icon-box stat-icon-success mx-auto mb-3"
                style="width: 64px; height: 64px; font-size: 2rem; border-radius: 50% !important;">
                <i class="icon-base ti tabler-cloud-check fs-1"></i>
            </div>

            <h4 class="fw-bold text-white mb-2">Upload Berhasil!</h4>
            <p class="text-body-premium mx-auto mb-4" style="max-width: 500px; font-size: 0.95rem;">
                Foto diri dan KTP Anda berhasil diupload ke Google Drive dan tersimpan di akun Anda.
            </p>

            @php
                $user = auth()->user();
            @endphp

            <div class="mt-4 p-3 rounded text-start"
                style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.06); max-width: 500px; margin: 0 auto;">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="icon-base ti tabler-check-circle text-success fs-5"></i>
                    <div>
                        <span class="text-white fw-semibold small d-block">Foto Diri</span>
                        <small class="text-body-premium">
                            {{ $user->google_drive_photo_url ? 'Tersimpan' : '-' }}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="icon-base ti tabler-check-circle text-success fs-5"></i>
                    <div>
                        <span class="text-white fw-semibold small d-block">Foto KTP</span>
                        <small class="text-body-premium">
                            {{ $user->google_drive_ktp_url ? 'Tersimpan' : '-' }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('dashboard.peserta') }}" class="btn btn-glow-premium px-5 py-2 fw-bold">
                    <i class="icon-base ti tabler-home me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    @endif
</div>

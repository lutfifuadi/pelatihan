<div x-data="fotoCapture()" x-init="init()" class="foto-capture-container">
    {{-- Hidden inputs for Livewire --}}
    <input type="hidden" wire:model="mode" x-bind:value="mode" />

    {{-- Loading/Processing overlay --}}
    <div x-show="processing" class="foto-capture-loading">
        <div class="foto-capture-loading-content">
            <div class="spinner-border text-primary mb-2" style="width: 2.5rem; height: 2.5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-white mb-0 fw-semibold small">Memproses...</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CAMERA VIEWFINDER STATE --}}
    {{-- ============================================================ --}}
    <div x-show="state === 'viewfinder'" x-cloak class="foto-capture-viewfinder">
        {{-- Viewfinder wrapper --}}
        <div class="viewfinder-wrapper" x-ref="viewfinderWrapper">
            {{-- Video element --}}
            <video x-ref="video"
                class="viewfinder-video"
                autoplay playsinline muted
                x-init="$el.addEventListener('loadedmetadata', () => handleVideoReady())">
            </video>

            {{-- Hidden canvas for face-api drawing --}}
            <canvas x-ref="overlayCanvas" class="viewfinder-canvas"></canvas>

            {{-- Overlay frame --}}
            <div class="viewfinder-frame"
                :class="mode === 'foto' ? 'frame-oval' : 'frame-ktp'">
            </div>

            {{-- Corner markers --}}
            <template x-if="mode === 'ktp'">
                <div class="ktp-corners">
                    <div class="corner corner-tl"></div>
                    <div class="corner corner-tr"></div>
                    <div class="corner corner-bl"></div>
                    <div class="corner corner-br"></div>
                </div>
            </template>

            {{-- Switch camera button --}}
            <button x-show="deviceCount >= 2"
                @click="switchCamera()"
                type="button"
                class="btn-switch-camera"
                title="Ganti Kamera">
                <i class="icon-base ti tabler-refresh fs-5"></i>
            </button>

            {{-- Close / Back button --}}
            <button @click="closeCamera()"
                type="button"
                class="btn-close-camera"
                title="Tutup Kamera">
                <i class="icon-base ti tabler-x fs-5"></i>
            </button>

            {{-- Guide text (inside viewfinder) --}}
            <div class="guide-container">
                <div class="guide-text"
                    :class="'guide-' + guideType"
                    x-text="guideText"
                    x-show="guideText">
                </div>
            </div>

            {{-- Action buttons (inside viewfinder) --}}
            <div class="viewfinder-actions">
                <button @click="useManualUpload()" type="button" class="btn btn-outline-glass fw-semibold" style="border-radius: 5px;">
                    <i class="icon-base ti tabler-upload me-1"></i> Upload Manual
                </button>
            </div>

            {{-- Progress bar (inside viewfinder, at bottom) --}}
            <div class="progress-container" x-show="progress > 0 && progress < 1" x-cloak>
                <div class="progress-bar-track">
                    <div class="progress-bar-fill" :style="'width: ' + (progress * 100) + '%'"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CAMERA SELECT MODAL --}}
    {{-- ============================================================ --}}
    <div x-show="state === 'select-camera'" x-cloak
        x-transition:enter="fade-in"
        x-transition:leave="fade-out"
        class="camera-select-modal">
        <div class="camera-select-content">
            <div class="text-center mb-3">
                <div class="camera-select-icon">
                    <i class="icon-base ti tabler-camera fs-1"></i>
                </div>
                <h6 class="text-white fw-bold mt-2 mb-1">Pilih Kamera</h6>
                <p class="text-white-50-custom small mb-0">Pilih kamera yang akan digunakan</p>
            </div>
            <div class="d-flex flex-column gap-2">
                <template x-for="(device, index) in availableDevices" :key="device.deviceId">
                    <button type="button" class="btn btn-camera-select"
                        @click="selectCamera(device.deviceId)"
                        :class="{ 'btn-camera-select-active': device.deviceId === selectedDeviceId }">
                        <i class="icon-base ti fs-5 me-2"
                            :class="isBackCamera(device) ? 'tabler-camera' : 'tabler-camera-selfie'"></i>
                        <span class="fw-semibold" x-text="getCameraLabel(device, index)"></span>
                    </button>
                </template>
            </div>
            <button @click="useManualUpload()" type="button" class="btn btn-outline-glass fw-semibold py-2 px-3 mt-3 w-100" style="border-radius: 5px; font-size: 12px;">
                <i class="icon-base ti tabler-upload me-1"></i> Upload Manual Saja
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PREVIEW STATE --}}
    {{-- ============================================================ --}}
    <div x-show="state === 'preview'" x-cloak
        x-transition:enter="fade-slide-in"
        class="foto-capture-preview">
        <div class="preview-image-wrapper">
            <img x-bind:src="capturedDataUrl" alt="Preview" class="preview-image" />
        </div>
        <div class="preview-actions">
            <button @click="retake()" type="button" class="btn btn-outline-glass fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
                <i class="icon-base ti tabler-refresh me-1"></i> Ambil Ulang
            </button>
            <button @click="confirm()" type="button"
                class="btn btn-glow-premium fw-semibold py-2 px-4"
                style="border-radius: 5px; font-size: 13px;"
                :disabled="confirming">
                <template x-if="!confirming">
                    <span><i class="icon-base ti tabler-check me-1"></i> Lanjut</span>
                </template>
                <template x-if="confirming">
                    <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Menyimpan...</span>
                </template>
            </button>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MANUAL UPLOAD STATE --}}
    {{-- ============================================================ --}}
    <div x-show="state === 'upload'" x-cloak
        x-transition:enter="fade-slide-in"
        class="foto-capture-upload">
        <div class="upload-area"
            :class="{ 'has-file': manualFile }"
            @click="if(!manualFile) document.getElementById('manual_upload_input_' + mode).click()"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop($event)"
            x-data="{ isDragging: false }"
            :class="{ 'drag-over': isDragging }">

            <template x-if="!manualPreview">
                <div class="upload-placeholder">
                    <i class="icon-base ti tabler-photo-plus fs-1 mb-2 d-block" style="color: rgba(255,255,255,0.3);"></i>
                    <span class="text-white-50-custom">Klik atau tarik file ke sini</span>
                    <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: JPG/PNG. Maks 2MB</p>
                </div>
            </template>
            <template x-if="manualPreview">
                <div class="upload-preview-wrapper">
                    <img x-bind:src="manualPreview" alt="Preview upload" class="upload-preview-img" />
                    <button type="button" class="btn-remove-upload"
                        @click.stop="removeManualFile()">
                        <i class="icon-base ti tabler-trash"></i>
                    </button>
                </div>
            </template>
        </div>

        <input type="file" x-bind:id="'manual_upload_input_' + mode"
            accept="image/jpeg,image/png,image/jpg"
            class="d-none"
            @change="handleManualFileInput($event)" />

        <div class="upload-actions mt-3">
            <button @click="openCamera()" type="button" class="btn btn-outline-glass fw-semibold py-2 px-3" style="border-radius: 5px; font-size: 12px;">
                <i class="icon-base ti tabler-camera me-1"></i> Gunakan Kamera
            </button>
            <button @click="confirmManualUpload()" type="button"
                class="btn btn-glow-premium fw-semibold py-2 px-4"
                style="border-radius: 5px; font-size: 13px;"
                :disabled="!manualFile || uploading">
                <template x-if="!uploading">
                    <span><i class="icon-base ti tabler-check me-1"></i> Gunakan Foto Ini</span>
                </template>
                <template x-if="uploading">
                    <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Mengupload...</span>
                </template>
            </button>
        </div>

        <div x-show="uploadError" x-cloak class="mt-2 text-center">
            <small class="text-danger" x-text="uploadError"></small>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ERROR STATE (no camera) --}}
    {{-- ============================================================ --}}
    <div x-show="state === 'error'" x-cloak class="foto-capture-error">
        <div class="text-center py-4">
            <div class="mb-3">
                <i class="icon-base ti tabler-camera-off fs-1" style="color: rgba(255,255,255,0.3);"></i>
            </div>
            <h6 class="text-white fw-semibold mb-1">Kamera Tidak Tersedia</h6>
            <p class="text-white-50-custom small mb-0 px-3">
                Kami tidak dapat mengakses kamera Anda. Silakan upload foto secara manual.
            </p>
        </div>

        {{-- langsung tampilkan upload manual --}}
        <div class="upload-area mt-2"
            :class="{ 'has-file': manualFile }"
            @click="if(!manualFile) document.getElementById('manual_upload_error_input').click()"
            @dragover.prevent="isDragging = true"
            @dragleave.prevent="isDragging = false"
            @drop.prevent="handleDrop($event)"
            x-data="{ isDragging: false }"
            :class="{ 'drag-over': isDragging }">

            <template x-if="!manualPreview">
                <div class="upload-placeholder">
                    <i class="icon-base ti tabler-photo-plus fs-1 mb-2 d-block" style="color: rgba(255,255,255,0.3);"></i>
                    <span class="text-white-50-custom">Klik untuk upload foto</span>
                    <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: JPG/PNG. Maks 2MB</p>
                </div>
            </template>
            <template x-if="manualPreview">
                <div class="upload-preview-wrapper">
                    <img x-bind:src="manualPreview" alt="Preview" class="upload-preview-img" />
                    <button type="button" class="btn-remove-upload"
                        @click.stop="removeManualFile()">
                        <i class="icon-base ti tabler-trash"></i>
                    </button>
                </div>
            </template>
        </div>

        <input type="file" id="manual_upload_error_input"
            accept="image/jpeg,image/png,image/jpg"
            class="d-none"
            @change="handleManualFileInput($event)" />

        <div class="text-center mt-3">
            <button @click="confirmManualUpload()" type="button"
                class="btn btn-glow-premium fw-semibold py-2 px-4"
                style="border-radius: 5px; font-size: 13px;"
                :disabled="!manualFile || uploading">
                <template x-if="!uploading">
                    <span><i class="icon-base ti tabler-check me-1"></i> Gunakan Foto Ini</span>
                </template>
                <template x-if="uploading">
                    <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Mengupload...</span>
                </template>
            </button>
        </div>
    </div>

    <style>
        .foto-capture-container {
            position: relative;
            width: 100%;
        }
        .foto-capture-loading {
            position: absolute;
            inset: 0;
            background: rgba(11, 15, 25, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            border-radius: 5px;
        }
        .foto-capture-loading-content {
            text-align: center;
        }

        /* ================================================================
           VIEWFINDER — Portrait Design
        ================================================================ */
        .foto-capture-viewfinder {
            position: relative;
        }
        .viewfinder-wrapper {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            background: #000;
            aspect-ratio: 3 / 4;
            box-shadow:
                0 0 0 1px rgba(99,102,241,0.15),
                0 20px 60px rgba(0,0,0,0.6),
                0 0 40px rgba(99,102,241,0.08);
        }
        .viewfinder-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scaleX(-1); /* mirror selfie */
        }
        .viewfinder-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 5;
        }

        /* --- Oval frame (foto diri) --- */
        .viewfinder-frame {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            pointer-events: none;
        }
        .frame-oval {
            width: 68%;
            aspect-ratio: 3 / 4;
            border-radius: 50%;
            border: 2.5px solid rgba(99, 102, 241, 0.0);
            box-shadow:
                0 0 0 9999px rgba(0,0,0,0.52),
                inset 0 0 0 2.5px rgba(99,102,241,0.85),
                0 0 28px rgba(99,102,241,0.35),
                inset 0 0 28px rgba(99,102,241,0.08);
            animation: frameGlow 2.5s ease-in-out infinite;
        }
        @keyframes frameGlow {
            0%,100% {
                box-shadow:
                    0 0 0 9999px rgba(0,0,0,0.52),
                    inset 0 0 0 2.5px rgba(99,102,241,0.85),
                    0 0 20px rgba(99,102,241,0.25),
                    inset 0 0 20px rgba(99,102,241,0.06);
            }
            50% {
                box-shadow:
                    0 0 0 9999px rgba(0,0,0,0.52),
                    inset 0 0 0 2.5px rgba(168,85,247,0.9),
                    0 0 36px rgba(168,85,247,0.45),
                    inset 0 0 36px rgba(168,85,247,0.12);
            }
        }

        /* Scanning line inside oval */
        .frame-oval::after {
            content: '';
            position: absolute;
            left: 5%;
            width: 90%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(99,102,241,0.8), rgba(217,70,239,0.8), transparent);
            border-radius: 2px;
            animation: scanLine 2.8s ease-in-out infinite;
            top: 10%;
        }
        @keyframes scanLine {
            0%   { top: 10%; opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { top: 88%; opacity: 0; }
        }

        /* --- KTP frame --- */
        .frame-ktp {
            width: 78%;
            aspect-ratio: 1.585 / 1;
            border-radius: 6px;
            border: 2px solid rgba(255,255,255,0.6);
            box-shadow:
                0 0 0 9999px rgba(0,0,0,0.52),
                0 0 20px rgba(99,102,241,0.3);
        }

        /* KTP guide text */
        .frame-ktp::after {
            content: 'Posisikan KTP sejajar dengan bingkai';
            position: absolute;
            bottom: -44px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 13px;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.6);
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(99, 102, 241, 0.5);
            padding: 8px 18px;
            border-radius: 24px;
            backdrop-filter: blur(8px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            z-index: 12;
        }

        /* --- KTP corner markers (premium) --- */
        .ktp-corners {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 88%;
            aspect-ratio: 1.585 / 1;
            z-index: 11;
            pointer-events: none;
        }
        .corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border-style: solid;
            border-color: #ffffff;
            filter: drop-shadow(0 0 6px rgba(99,102,241,0.9));
            animation: cornerPulse 2s ease-in-out infinite;
            background: rgba(0,0,0,0.3);
        }
        @keyframes cornerPulse {
            0%,100% { border-color: #ffffff; filter: drop-shadow(0 0 4px rgba(99,102,241,0.8)); }
            50%      { border-color: #6366f1; filter: drop-shadow(0 0 12px rgba(99,102,241,1)); }
        }
        .corner-tl { top: -2px; left: -2px; border-width: 3px 0 0 3px; border-radius: 6px 0 0 0; }
        .corner-tr { top: -2px; right: -2px; border-width: 3px 3px 0 0; border-radius: 0 6px 0 0; }
        .corner-bl { bottom: -2px; left: -2px; border-width: 0 0 3px 3px; border-radius: 0 0 0 6px; }
        .corner-br { bottom: -2px; right: -2px; border-width: 0 3px 3px 0; border-radius: 0 0 6px 0; }

        /* --- Overlay buttons --- */
        .btn-switch-camera {
            position: absolute;
            bottom: 16px;
            right: 16px;
            z-index: 20;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(8px);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }
        .btn-switch-camera:hover {
            background: rgba(99,102,241,0.35);
            border-color: rgba(99,102,241,0.8);
            box-shadow: 0 4px 20px rgba(99,102,241,0.4);
        }
        .btn-close-camera {
            position: absolute;
            top: 16px;
            right: 16px;
            z-index: 20;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(239,68,68,0.6);
            backdrop-filter: blur(8px);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s;
            box-shadow: 0 4px 12px rgba(239,68,68,0.3);
        }
        .btn-close-camera:hover {
            background: rgba(239,68,68,0.85);
            box-shadow: 0 4px 20px rgba(239,68,68,0.5);
        }

        /* --- Guide text (floated inside viewfinder) --- */
        .guide-container {
            position: absolute;
            bottom: 52px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            width: 86%;
            text-align: center;
            pointer-events: none;
        }
        .guide-text {
            display: inline-block;
            padding: 7px 18px;
            border-radius: 22px;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.02em;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.35s ease;
            white-space: nowrap;
        }
        .guide-success {
            background: rgba(16,185,129,0.22);
            color: #6ee7b7;
            border: 1px solid rgba(16,185,129,0.45);
            box-shadow: 0 4px 20px rgba(16,185,129,0.2);
        }
        .guide-error {
            background: rgba(248,113,113,0.18);
            color: #fca5a5;
            border: 1px solid rgba(248,113,113,0.4);
            box-shadow: 0 4px 20px rgba(248,113,113,0.2);
        }
        .guide-warning {
            background: rgba(251,191,36,0.18);
            color: #fde68a;
            border: 1px solid rgba(251,191,36,0.4);
            box-shadow: 0 4px 20px rgba(251,191,36,0.15);
        }
        .guide-neutral {
            background: rgba(15,23,42,0.65);
            color: rgba(255,255,255,0.82);
            border: 1px solid rgba(255,255,255,0.15);
        }

        /* --- Progress bar (inside viewfinder bottom) --- */
        .progress-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 21;
            padding: 0;
        }
        .progress-bar-track {
            width: 100%;
            height: 3px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #a855f7, #d946ef);
            transition: width 0.1s linear;
            box-shadow: 0 0 8px rgba(168,85,247,0.7);
        }

        /* --- Viewfinder actions (inside viewfinder) --- */
        .viewfinder-actions {
            position: absolute;
            bottom: 12px;
            left: 12px;
            z-index: 20;
        }
        .viewfinder-actions .btn {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            background: rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.22);
            color: rgba(255,255,255,0.88);
            font-size: 11px;
            padding: 5px 11px;
            border-radius: 5px;
        }
        .viewfinder-actions .btn:hover {
            background: rgba(99,102,241,0.3);
            border-color: rgba(99,102,241,0.7);
        }

        /* --- Camera select modal --- */
        .camera-select-modal {
            padding: 20px 0;
        }
        .camera-select-content {
            max-width: 360px;
            margin: 0 auto;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 24px 20px;
        }
        .camera-select-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #6366f1;
        }
        .btn-camera-select {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.8);
            padding: 12px 16px;
            border-radius: 10px;
            text-align: left;
            transition: all 0.2s;
        }
        .btn-camera-select:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(99,102,241,0.3);
        }
        .btn-camera-select-active {
            background: rgba(99, 102, 241, 0.15);
            border-color: #6366f1;
        }

        /* --- Preview --- */
        .foto-capture-preview {
            padding: 8px 0;
        }
        .preview-image-wrapper {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .preview-image {
            width: 100%;
            height: auto;
            display: block;
        }
        .preview-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            padding: 14px 12px 0;
        }

        /* --- Upload area --- */
        .upload-area {
            background: rgba(255,255,255,0.03);
            border: 1.5px dashed rgba(255,255,255,0.14);
            border-radius: 14px;
            padding: 32px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .upload-area:hover {
            background: rgba(255,255,255,0.06);
            border-color: rgba(99,102,241,0.45);
        }
        .upload-area.has-file {
            border-color: #10b981;
            background: rgba(16,185,129,0.05);
        }
        .upload-area.drag-over {
            border-color: #6366f1;
            background: rgba(99,102,241,0.08);
        }
        .upload-placeholder { padding: 10px 0; }
        .upload-preview-wrapper {
            position: relative;
            max-width: 300px;
            margin: 0 auto;
        }
        .upload-preview-img {
            width: 100%;
            max-height: 280px;
            object-fit: contain;
            border-radius: 8px;
        }
        .btn-remove-upload {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: none;
            background: rgba(248,113,113,0.85);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-remove-upload:hover { background: #ef4444; }
        .upload-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .foto-capture-upload,
        .foto-capture-error { padding: 8px 0; }

        /* --- Transitions --- */
        .fade-in  { animation: fadeIn 0.25s ease; }
        .fade-out { animation: fadeOut 0.2s ease; }
        .fade-slide-in { animation: fadeSlideIn 0.3s ease forwards; }
        @keyframes fadeIn      { from{opacity:0} to{opacity:1} }
        @keyframes fadeOut     { from{opacity:1} to{opacity:0} }
        @keyframes fadeSlideIn { 0%{opacity:0;transform:translateY(10px)} 100%{opacity:1;transform:translateY(0)} }

        /* ================================================================
           MOBILE — Full Frame
        ================================================================ */
        @media (max-width: 576px) {
            .viewfinder-wrapper {
                max-width: 100%;
                border-radius: 0;
                aspect-ratio: unset;
                height: calc(100svh - 200px);
                min-height: 420px;
                box-shadow: none;
            }
            .frame-oval  { width: 72%; }
            .frame-ktp   { width: 88%; }
            .ktp-corners { width: 88%; }
            .guide-text  { font-size: 12px; padding: 6px 14px; }
        }
    </style>

</div>

const FOTO_CAPTURE = {
    modelsLoaded: false,
    stream: null,
    currentDeviceId: null,
    deviceList: [],
    detectTimer: null,
    captureTimer: null,
    stableStartTime: null,
    isDetecting: false,
    faceapiReady: false,
    modelLoadAttempted: false,

    FACE_API_URL: 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js',
    FACE_MODEL_URL: 'https://justadudewhohacks.github.io/face-api.js/models',

    async ensureFaceApiLoaded() {
        if (typeof faceapi !== 'undefined') return true;

        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = this.FACE_API_URL;
            script.async = false;
            script.onload = () => resolve(true);
            script.onerror = () => reject(new Error('Gagal memuat face-api.js'));
            document.head.appendChild(script);
        });
    },

    async loadModels() {
        if (this.modelsLoaded) return;
        if (this.modelLoadAttempted) {
            while (!this.modelsLoaded) {
                await new Promise(r => setTimeout(r, 100));
            }
            return;
        }
        this.modelLoadAttempted = true;

        try {
            await this.ensureFaceApiLoaded();
            await faceapi.nets.tinyFaceDetector.loadFromUri(this.FACE_MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(this.FACE_MODEL_URL);
            this.modelsLoaded = true;
            this.faceapiReady = true;
        } catch (e) {
            this.modelsLoaded = false;
            this.faceapiReady = false;
            throw new Error('Gagal memuat model face-api.js: ' + e.message);
        }
    },

    async enumerateCameras() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            return devices.filter(d => d.kind === 'videoinput');
        } catch {
            return [];
        }
    },

    async requestCameraPermission() {
        try {
            const s = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            s.getTracks().forEach(t => t.stop());
            return true;
        } catch {
            return false;
        }
    },

    async startCamera(videoEl, deviceId = null) {
        if (this.stream) {
            this.stream.getTracks().forEach(t => t.stop());
            this.stream = null;
        }

        const constraints = {
            video: {
                width: { ideal: 1280 },
                height: { ideal: 720 },
                facingMode: 'user',
            },
            audio: false,
        };

        if (deviceId) {
            constraints.video = { deviceId: { exact: deviceId } };
        } else {
            constraints.video.facingMode = 'user';
        }

        try {
            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
            videoEl.srcObject = this.stream;

            const track = this.stream.getVideoTracks()[0];
            const settings = track.getSettings();
            this.currentDeviceId = settings.deviceId;

            await videoEl.play();

            return true;
        } catch (e) {
            throw new Error('Gagal mengakses kamera: ' + e.message);
        }
    },

    async switchCamera(videoEl) {
        const devices = await this.enumerateCameras();
        if (devices.length < 2) return false;

        const currentIndex = devices.findIndex(d => d.deviceId === this.currentDeviceId);
        const nextIndex = (currentIndex + 1) % devices.length;

        await this.startCamera(videoEl, devices[nextIndex].deviceId);
        return true;
    },

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(t => t.stop());
            this.stream = null;
        }
        if (this.detectTimer) {
            cancelAnimationFrame(this.detectTimer);
            this.detectTimer = null;
        }
        if (this.captureTimer) {
            clearTimeout(this.captureTimer);
            this.captureTimer = null;
        }
        this.isDetecting = false;
        this.stableStartTime = null;
    },

    startDetectionLoop(videoEl, canvasEl, callbacks) {
        this.isDetecting = true;
        this.stableStartTime = null;

        const inputSize = 320;
        const scoreThreshold = 0.5;

        const detect = async () => {
            if (!this.isDetecting || !videoEl.videoWidth) {
                this.detectTimer = requestAnimationFrame(detect);
                return;
            }

            try {
                const result = await faceapi
                    .detectSingleFace(videoEl, new faceapi.TinyFaceDetectorOptions({
                        inputSize,
                        scoreThreshold,
                    }))
                    .withFaceLandmarks();

                if (result) {
                    this.processFaceDetection(videoEl, canvasEl, result, callbacks);
                } else {
                    if (callbacks.onNoFace) callbacks.onNoFace();
                    this.stableStartTime = null;
                }
            } catch {
                if (callbacks.onError) callbacks.onError();
            }

            if (this.isDetecting) {
                this.detectTimer = requestAnimationFrame(detect);
            }
        };

        detect();
    },

    processFaceDetection(videoEl, canvasEl, result, callbacks) {
        const box = result.detection.box;
        const landmarks = result.landmarks;
        const videoW = videoEl.videoWidth;
        const videoH = videoEl.videoHeight;

        const displayW = videoEl.clientWidth || 320;
        const displayH = videoEl.clientHeight || 240;

        const scaleX = displayW / videoW;
        const scaleY = displayH / videoH;

        const faceX = box.x * scaleX + box.width * scaleX / 2;
        const faceY = box.y * scaleY + box.height * scaleY / 2;
        const faceW = box.width * scaleX;
        const faceH = box.height * scaleY;

        const centerX = displayW / 2;
        const centerY = displayH / 2;

        const frameW = displayW * 0.55;
        const frameH = displayH * 0.65;

        const isCenteredX = Math.abs(faceX - centerX) < frameW * 0.18;
        const isCenteredY = Math.abs(faceY - centerY) < frameH * 0.22;
        const isCentered = isCenteredX && isCenteredY;

        const faceArea = faceW * faceH;
        const frameArea = frameW * frameH;
        const areaRatio = faceArea / frameArea;

        const isCorrectSize = areaRatio > 0.15 && areaRatio < 0.45;

        const luminance = this.calculateLuminance(videoEl);
        const isBrightEnough = luminance > 60;

        const midIndex = Math.floor(landmarks.positions.length / 2);
        const jawY = landmarks.positions[8]?.y * scaleY || 0;
        const noseY = landmarks.positions[33]?.y * scaleY || 0;
        const eyeY = landmarks.positions[37]?.y * scaleY || 0;
        const faceIsForward = (jawY - noseY) > (noseY - eyeY) * 0.3;

        let guideText = '';
        let guideType = 'neutral';
        let isStable = false;

        if (!isBrightEnough) {
            guideText = 'Cari tempat yang lebih terang';
            guideType = 'warning';
        } else if (!faceIsForward) {
            guideText = 'Pastikan wajah terlihat jelas';
            guideType = 'neutral';
        } else if (!isCentered) {
            guideText = 'Posisikan wajah di tengah bingkai';
            guideType = 'neutral';
        } else if (!isCorrectSize) {
            if (areaRatio >= 0.45) {
                guideText = 'Mundur sedikit, wajah terlalu besar';
                guideType = 'error';
            } else {
                guideText = 'Maju sedikit, wajah terlalu kecil';
                guideType = 'error';
            }
        } else {
            guideText = 'Posisi sempurna, tahan...';
            guideType = 'success';
            isStable = true;
        }

        if (callbacks.onGuide) {
            callbacks.onGuide({ text: guideText, type: guideType });
        }

        if (isStable) {
            if (!this.stableStartTime) {
                this.stableStartTime = Date.now();
            }
            const elapsed = Date.now() - this.stableStartTime;
            const progress = Math.min(elapsed / 3000, 1);

            if (callbacks.onProgress) callbacks.onProgress(progress);

            if (progress >= 1) {
                if (callbacks.onCapture) callbacks.onCapture();
                this.isDetecting = false;
                this.stableStartTime = null;
            }
        } else {
            this.stableStartTime = null;
            if (callbacks.onProgress) callbacks.onProgress(0);
        }
    },

    calculateLuminance(videoEl) {
        try {
            const tempCanvas = document.createElement('canvas');
            tempCanvas.width = 50;
            tempCanvas.height = 50;
            const ctx = tempCanvas.getContext('2d');
            ctx.drawImage(videoEl, 0, 0, 50, 50);
            const imageData = ctx.getImageData(0, 0, 50, 50);
            const pixels = imageData.data;
            let sum = 0;
            const count = pixels.length / 4;
            for (let i = 0; i < pixels.length; i += 4) {
                sum += 0.299 * pixels[i] + 0.587 * pixels[i + 1] + 0.114 * pixels[i + 2];
            }
            return sum / count;
        } catch {
            return 100;
        }
    },

    captureFrame(videoEl, mimeType = 'image/jpeg') {
        const canvas = document.createElement('canvas');
        canvas.width = videoEl.videoWidth;
        canvas.height = videoEl.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(videoEl, 0, 0);
        const dataUrl = canvas.toDataURL(mimeType, 0.92);
        const base64 = dataUrl.split(',')[1];
        return { base64, mimeType, dataUrl };
    },
};

window.FOTO_CAPTURE = FOTO_CAPTURE;

// =====================================================
// Register Alpine component for FotoCapture
// =====================================================
document.addEventListener('alpine:init', () => {
    Alpine.data('fotoCapture', () => ({
        // State
        state: 'viewfinder',
        mode: 'foto',
        capturedImage: null,
        step: 'camera',
        capturedDataUrl: null,
        capturedMimeType: null,

        // Camera
        availableDevices: [],
        selectedDeviceId: null,
        deviceCount: 0,
        cameraPermission: false,

        // Detection
        guideText: 'Mengakses kamera...',
        guideType: 'neutral',
        progress: 0,
        detecting: false,

        // Manual upload
        manualFile: null,
        manualPreview: null,
        uploadError: null,
        uploading: false,

        // Processing
        processing: false,
        confirming: false,
        faceapiReady: false,
        initAttempted: false,

        // ================================================================
        // INIT
        // ================================================================
        async init() {
            if (this.$wire) {
                this.mode = this.$wire.mode;
            }

            if (this.initAttempted) return;
            this.initAttempted = true;

            const hasPermission = await window.FOTO_CAPTURE.requestCameraPermission();
            if (!hasPermission) {
                this.state = 'error';
                return;
            }

            await this.enumerateDevices();

            if (this.deviceCount === 0) {
                this.state = 'error';
                return;
            }

            if (this.deviceCount === 1) {
                await this.startViewfinder(this.availableDevices[0].deviceId);
                return;
            }

            this.state = 'select-camera';
        },

        handleVideoReady() {},

        // ================================================================
        // CAMERA METHODS
        // ================================================================
        async enumerateDevices() {
            this.availableDevices = await window.FOTO_CAPTURE.enumerateCameras();
            this.deviceCount = this.availableDevices.length;
        },

        isBackCamera(device) {
            const label = (device.label || '').toLowerCase();
            return label.includes('back') || label.includes('belakang') || label.includes('rear');
        },

        getCameraLabel(device, index) {
            const label = device.label || device.groupId;
            if (label && label !== '') {
                return this.isBackCamera(device) ? 'Kamera Belakang' : 'Kamera Depan';
            }
            return 'Kamera ' + (index + 1);
        },

        async selectCamera(deviceId) {
            this.selectedDeviceId = deviceId;
            this.state = 'viewfinder';
            await this.startViewfinder(deviceId);
        },

        async startViewfinder(deviceId) {
            const videoEl = this.$refs.video;
            if (!videoEl) return;

            this.guideText = 'Mengakses kamera...';
            this.guideType = 'neutral';

            try {
                await window.FOTO_CAPTURE.startCamera(videoEl, deviceId);
                this.cameraPermission = true;

                try {
                    await window.FOTO_CAPTURE.loadModels();
                    this.faceapiReady = true;
                    this.startDetection();
                } catch (e) {
                    this.guideText = 'Deteksi wajah tidak tersedia, capture manual';
                    this.guideType = 'warning';
                }
            } catch (e) {
                this.state = 'error';
            }
        },

        async switchCamera() {
            const videoEl = this.$refs.video;
            if (!videoEl) return;

            this.guideText = 'Mengganti kamera...';
            this.guideType = 'neutral';
            this.progress = 0;

            try {
                await window.FOTO_CAPTURE.switchCamera(videoEl);
                if (this.faceapiReady) {
                    this.startDetection();
                }
            } catch (e) {
                this.guideText = 'Gagal mengganti kamera';
                this.guideType = 'error';
            }
        },

        closeCamera() {
            window.FOTO_CAPTURE.stopCamera();
            this.state = 'upload';
            this.guideText = '';
            this.progress = 0;
        },

        // ================================================================
        // DETECTION
        // ================================================================
        startDetection() {
            const videoEl = this.$refs.video;
            const canvasEl = this.$refs.overlayCanvas;
            if (!videoEl || !canvasEl) return;

            window.FOTO_CAPTURE.startDetectionLoop(videoEl, canvasEl, {
                onGuide: ({ text, type }) => {
                    this.guideText = text;
                    this.guideType = type;
                },
                onProgress: (val) => {
                    this.progress = val;
                },
                onCapture: () => {
                    this.doCapture();
                },
                onNoFace: () => {
                    if (!this.guideText || this.guideText === 'Posisi sempurna, tahan...') {
                        this.guideText = 'Pastikan wajah terlihat jelas';
                        this.guideType = 'neutral';
                    }
                    this.progress = 0;
                },
                onError: () => {},
            });
        },

        // ================================================================
        // CAPTURE
        // ================================================================
        doCapture() {
            const videoEl = this.$refs.video;
            if (!videoEl) return;

            // ✅ Capture frame DULU selagi video masih hidup & streaming
            const result = window.FOTO_CAPTURE.captureFrame(videoEl);

            // ✅ Baru stop kameranya
            window.FOTO_CAPTURE.stopCamera();

            this.capturedDataUrl = result.dataUrl;
            this.capturedImage = result.base64;
            this.capturedMimeType = result.mimeType;
            this.state = 'preview';
            this.progress = 0;
        },

        // ================================================================
        // PREVIEW / CONFIRM / RETAKE
        // ================================================================
        async retake() {
            this.state = 'viewfinder';
            this.progress = 0;
            this.guideText = 'Mengakses kamera...';
            this.guideType = 'neutral';

            const videoEl = this.$refs.video;
            if (!videoEl) return;

            try {
                await window.FOTO_CAPTURE.startCamera(videoEl, this.selectedDeviceId);
                if (this.faceapiReady) {
                    this.startDetection();
                }
            } catch (e) {
                this.state = 'error';
            }
        },

        async confirm() {
            this.confirming = true;
            const base64 = this.capturedImage;
            const mimeType = this.capturedMimeType || 'image/jpeg';

            if (this.$wire) {
                await this.$wire.confirmCapture(base64, mimeType);
            }
            this.confirming = false;
        },

        // ================================================================
        // MANUAL UPLOAD
        // ================================================================
        useManualUpload() {
            window.FOTO_CAPTURE.stopCamera();
            this.state = 'upload';
        },

        openCamera() {
            this.state = 'viewfinder';
            this.retake();
        },

        handleManualFileInput(event) {
            const file = event.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                this.uploadError = 'Format file harus JPG atau PNG.';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                this.uploadError = 'Ukuran file maksimal 2MB.';
                return;
            }

            this.uploadError = null;
            this.manualFile = file;
            this.manualPreview = URL.createObjectURL(file);
        },

        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                this.uploadError = 'Format file harus JPG atau PNG.';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                this.uploadError = 'Ukuran file maksimal 2MB.';
                return;
            }

            this.uploadError = null;
            this.manualFile = file;
            this.manualPreview = URL.createObjectURL(file);
        },

        removeManualFile() {
            if (this.manualPreview) {
                URL.revokeObjectURL(this.manualPreview);
            }
            this.manualFile = null;
            this.manualPreview = null;
            this.uploadError = null;
        },

        async confirmManualUpload() {
            if (!this.manualFile) return;
            this.uploading = true;
            this.uploadError = null;

            try {
                await this.$wire.set('manualFile', this.manualFile);
                await this.$wire.confirmManualUpload();
            } catch (e) {
                this.uploadError = 'Gagal mengupload file. Silakan coba lagi.';
            }

            this.uploading = false;
        },
    }));
});

export default FOTO_CAPTURE;

<div>
    <div x-data="confirmationPopup()" x-init="init()"
         x-show="show"
         x-cloak
         @keydown.escape.window="close()"
         class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
         style="z-index: 1050; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);"
         x-on:click.self="close()">

        <div class="glass-card-premium position-relative" role="dialog" aria-modal="true" aria-label="Konfirmasi Pendaftaran"
             style="max-width: 520px; width: 90%; padding: 2rem;">

            {{-- Close button --}}
            <button type="button"
                    x-on:click.stop.prevent="close()"
                    class="btn btn-sm position-absolute d-inline-flex align-items-center gap-1"
                    style="top: 12px; right: 12px; z-index: 10; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); color: rgba(255,255,255,0.7); border-radius: 5px; padding: 4px 10px;">
                <i class="icon-base ti tabler-x"></i>
                <span style="font-size: 0.75rem;">Tutup</span>
            </button>

            {{-- Confetti canvas --}}
            <canvas id="confetti-canvas" class="position-absolute top-0 start-0 w-100 h-100 pointer-events-none" style="z-index: -1;"></canvas>

            {{-- Success icon --}}
            <div class="text-center mb-3 pt-2">
                <div class="d-inline-flex align-items-center justify-content-center icon-success-circle">
                    <i class="icon-base ti tabler-circle-check text-white fs-1"></i>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-center fw-bold text-white mb-1">Selamat! 🎉</h2>
            <p class="text-center text-body-premium mb-3">Pendaftaran Anda Disetujui!</p>

            {{-- Info Pelatihan --}}
            <div class="p-3 rounded-3 mb-3 bg-dark-glass">
                <small class="text-body-premium d-block mb-1">
                    <i class="icon-base ti tabler-school me-1"></i><span class="text-white fw-semibold">{{ $enrollment->pelatihan->nama }}</span>
                </small>
                <small class="text-body-premium d-block mb-1">
                    <i class="icon-base ti tabler-calendar me-1"></i>{{ $enrollment->pelatihan->tanggal_mulai->format('j F Y') }} s.d. {{ $enrollment->pelatihan->tanggal_selesai->format('j F Y') }}
                </small>
                <small class="text-body-premium d-block">
                    <i class="icon-base ti tabler-building me-1"></i>{{ $enrollment->pelatihan->dinas?->nama_dinas ?? '-' }}
                </small>
            </div>

            {{-- Verification Code --}}
            <div class="text-center mb-3">
                <small class="text-body-premium d-block mb-1">Kode Verifikasi Anda</small>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 bg-code-box">
                    <span class="fw-bold text-white font-monospace" style="font-size: 1.3rem; letter-spacing: 2px;">
                        {{ $enrollment->verification_code }}
                    </span>
                    <button x-on:click.stop="copyCode()" type="button" class="btn btn-sm p-1 text-indigo"
                            title="Salin kode">
                        <i class="icon-base ti tabler-copy"></i>
                    </button>
                </div>
                <small class="text-body-premium d-block mt-1">
                    <i class="icon-base ti tabler-clock me-1"></i>Berlaku hingga: <span x-text="expiryText"></span>
                </small>
            </div>

            {{-- WA Button --}}
            <div class="d-grid">
                <a href="{{ $this->wa_link }}" target="_blank" rel="noopener noreferrer"
                   class="btn d-flex align-items-center justify-content-center gap-2 py-2 fw-bold btn-whatsapp">
                    <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
                    Chat Admin via WhatsApp
                </a>
            </div>

            {{-- Close text button --}}
            <div class="text-center mt-3">
                <button type="button"
                        x-on:click.stop.prevent="close()"
                        class="btn btn-link text-body-premium text-decoration-none p-0"
                        style="font-size: 0.8rem;">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

<style>
    .icon-success-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }
    .bg-dark-glass {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.06);
    }
    .bg-code-box {
        background: rgba(99,102,241,0.15);
        border: 1px solid rgba(99,102,241,0.3);
    }
    .btn-whatsapp {
        background: #25D366;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(37,211,102,0.4);
    }
    .btn-whatsapp:hover {
        background: #20bd5a;
        color: white;
    }
    .text-indigo {
        color: #818cf8;
    }
    .pointer-events-none {
        pointer-events: none;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('confirmationPopup', () => ({
        show: true,
        expiryText: '',
        expiryInterval: null,
        storageKey: 'popup_closed_enrollment_{{ $enrollment->id }}',

        init() {
            // Cek apakah popup pernah ditutup sebelumnya
            if (localStorage.getItem(this.storageKey)) {
                this.show = false;
                return;
            }
            this.startCountdown();
            this.fireConfetti();
        },

        startCountdown() {
            const expiry = new Date('{{ $enrollment->verification_code_expires_at }}').getTime();
            this.updateExpiryText(expiry);
            this.expiryInterval = setInterval(() => {
                this.updateExpiryText(expiry);
            }, 1000);
        },

        updateExpiryText(expiry) {
            const now = new Date().getTime();
            const diff = expiry - now;
            if (diff <= 0) {
                this.expiryText = 'Kode sudah expired';
                clearInterval(this.expiryInterval);
                return;
            }
            const h = Math.floor(diff / (1000 * 60 * 60));
            const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((diff % (1000 * 60)) / 1000);
            this.expiryText = `${h} jam ${m} menit ${s} detik`;
        },

        copyCode() {
            const code = '{{ $enrollment->verification_code }}';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(() => {
                    // Opsional: tampilkan toast sederhana
                });
            } else {
                // Fallback untuk browser lama
                const textarea = document.createElement('textarea');
                textarea.value = code;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            }
        },

        fireConfetti() {
            if (typeof confetti !== 'undefined') {
                confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } });
            }
        },

        close() {
            this.show = false;
            if (this.expiryInterval) clearInterval(this.expiryInterval);
            // Simpan ke localStorage supaya tidak muncul lagi saat reload
            localStorage.setItem(this.storageKey, 'true');
        }
    }));
});
</script>
</div>

@props([
    'namaPelatihan' => '',
    'batch' => '',
    'tanggalDitutup' => '',
])

<div
    x-show="show"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-labelledby="popup-ditutup-title"
    class="popup-ditutup-overlay"
    style="display: none;"
>
    <div
        class="popup-ditutup-backdrop"
        @click="close()"
        x-show="show"
        x-transition:enter="popup-fade-enter"
        x-transition:enter-start="popup-fade-enter-start"
        x-transition:enter-end="popup-fade-enter-end"
        x-transition:leave="popup-fade-leave"
        x-transition:leave-start="popup-fade-leave-start"
        x-transition:leave-end="popup-fade-leave-end"
    ></div>

    <div
        x-ref="popupDialog"
        class="popup-ditutup-card"
        x-show="show"
        x-transition:enter="popup-scale-enter"
        x-transition:enter-start="popup-scale-enter-start"
        x-transition:enter-end="popup-scale-enter-end"
        x-transition:leave="popup-scale-leave"
        x-transition:leave-start="popup-scale-leave-start"
        x-transition:leave-end="popup-scale-leave-end"
        @click.outside="close()"
    >
        <button
            type="button"
            class="popup-ditutup-close"
            @click="close()"
            aria-label="Tutup popup"
        >
            <i class="icon-base ti tabler-x"></i>
        </button>

        <div class="popup-ditutup-icon">
            <i class="icon-base ti tabler-calendar-off"></i>
        </div>

        <h3 id="popup-ditutup-title" class="popup-ditutup-title">
            Pendaftaran Ditutup
        </h3>

        <p class="popup-ditutup-message">
            Pendaftaran untuk <strong x-text="popupNama"></strong> — <strong x-text="'Batch ' + popupBatch"></strong> telah ditutup pada <strong x-text="popupTanggal"></strong>.
        </p>

        <p class="popup-ditutup-submessage">
            Silakan pilih pelatihan lain yang masih tersedia.
        </p>

        <div class="popup-ditutup-actions">
            <a href="{{ route('pelatihan.index') }}" class="popup-btn popup-btn-primary">
                <i class="icon-base ti tabler-search me-1"></i>
                Lihat Pelatihan Lain
            </a>
            <button type="button" class="popup-btn popup-btn-secondary" @click="close()">
                Tutup
            </button>
        </div>
    </div>
</div>

@php
$configData = Helper::appClasses();

// Config lookup helpers
$fLabels = $fields->pluck('label', 'field_key');
$fPlaceholders = $fields->pluck('placeholder', 'field_key');
$fActive = $fields->where('is_active', true)->pluck('field_key')->toArray();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Minat Pelatihan')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  .content-wrapper {
    font-family: 'Outfit', sans-serif;
    color: #f8fafc;
    position: relative !important;
    overflow: hidden !important;
  }
  .content-wrapper h1,
  .content-wrapper h2,
  .content-wrapper h3,
  .content-wrapper h4,
  .content-wrapper h5,
  .content-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-dashboard {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    z-index: 1;
    padding: 28px 24px;
  }
  @media (max-width: 660px) {
    .glass-card-dashboard { padding: 20px 16px; }
  }

  .form-control-custom {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control-custom:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
  .form-control-custom.is-invalid { border-color: #f87171 !important; box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important; }
  .form-control-custom:disabled, .form-control-custom[readonly] { background: rgba(255, 255, 255, 0.02) !important; opacity: 0.6; }
  .form-control-uppercase { text-transform: uppercase !important; }

  textarea.form-control-custom { resize: vertical; min-height: 90px; }
  select.form-control-custom option { background: #1a1f2e; color: #f8fafc; }

  .form-label-custom {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 4px;
  }

  .btn-glow {
    position: relative; overflow: hidden; transition: all 0.3s ease; border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }
  .btn-glow-outline {
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.8) !important;
    transition: all 0.3s ease;
  }
  .btn-glow-outline:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.3);
    color: #ffffff !important;
  }

  .form-check-input-custom {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
  }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .form-check-input-custom[type="radio"]:checked {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e") !important;
  }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }
  .text-white-70-custom { color: rgba(255, 255, 255, 0.7) !important; }

  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 12px; }
  }
  .field-full { grid-column: 1 / -1; }

  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  .checkbox-group label { font-size: 13px; }

  /* Grid Cards Visual */
  .grid-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
  }
  @media (max-width: 660px) {
    .grid-cards-container {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  .visual-card {
    background: rgba(255, 255, 255, 0.02) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 12px !important;
    padding: 20px !important;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
  }

  .visual-card:hover:not(.disabled) {
    transform: translateY(-4px) !important;
    background: rgba(255, 255, 255, 0.04) !important;
    border-color: rgba(99, 102, 241, 0.4) !important;
    box-shadow: 0 12px 20px -10px rgba(99, 102, 241, 0.25) !important;
  }

  .visual-card.active {
    background: rgba(99, 102, 241, 0.1) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.25), inset 0 0 12px rgba(99, 102, 241, 0.1) !important;
  }

  .visual-card.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: rgba(239, 68, 68, 0.02) !important;
    border-color: rgba(239, 68, 68, 0.15) !important;
  }

  .card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
  }

  .badge-batch {
    background: rgba(99, 102, 241, 0.15) !important;
    color: #a5b4fc !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    padding: 4px 10px !important;
    border-radius: 6px !important;
    letter-spacing: 0.05em !important;
    text-transform: uppercase;
  }
  .visual-card.disabled .badge-batch {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #fca5a5 !important;
  }

  .custom-radio-indicator {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
  }

  .visual-card.active .custom-radio-indicator {
    border-color: #6366f1 !important;
    background: #6366f1 !important;
  }

  .radio-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #ffffff;
  }

  .pelatihan-title {
    font-family: 'Sora', sans-serif !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    color: #ffffff !important;
    margin-bottom: 12px !important;
    line-height: 1.4 !important;
  }

  .card-info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px !important;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 8px;
  }
  .card-info-item i {
    font-size: 16px !important;
    color: rgba(255, 255, 255, 0.4) !important;
  }

  .visual-card.active .card-info-item i {
    color: #a5b4fc !important;
  }

  .restricted-warning-box {
    margin-top: 12px;
    padding: 10px 12px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    font-size: 11px;
    color: #f87171;
    display: flex;
    align-items: flex-start;
    gap: 6px;
    line-height: 1.4;
  }
  .restricted-warning-box i {
    margin-top: 2px;
    font-size: 14px;
    flex-shrink: 0;
  }

  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  @if(session('error'))
  <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
    <div class="d-flex align-items-center">
      <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
      <span>{{ session('error') }}</span>
    </div>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-heart text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Minat Pelatihan</h4>
        <p class="text-white-50-custom mb-0 small">Pilih bidang minat dan preferensi pelatihan Anda</p>
      </div>
    </div>
  </div>

  <div class="glass-card-dashboard" x-data="minatForm()" x-cloak>
    <form id="formMinat" action="{{ route('dashboard.peserta.form-minat.store') }}" method="POST">
      @csrf

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-heart me-2" style="color: #6366f1;"></i>{{ $fLabels['section_title'] ?? 'MINAT PELATIHAN' }}
        </h5>

        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom">
              {{ $fLabels['batch_pelatihan'] ?? 'PILIH PELATIHAN (BATCH) YANG ANDA MINATI' }}
              @if($fields->where('field_key', 'batch_pelatihan')->first()?->is_required) <span class="text-danger">*</span> @endif
            </label>
            <p class="text-white-50-custom small mb-3" style="font-size: 12px; font-style: italic;">
              {{ $fPlaceholders['batch_pelatihan'] ?? 'PELAKSANAAN BATCH SELANJUTNYA AKAN DI INFORMASIKAN LEWAT EMAIL ATAU WA TERDAFTAR' }}
            </p>
            <div class="grid-cards-container mt-1">
              <template x-for="(batch, index) in batchList" :key="index">
                <div 
                  class="visual-card"
                  :class="{ 
                    'active': form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value, 
                    'disabled': batch.restricted 
                  }"
                  @click="if (!batch.restricted) { form.batch_pelatihan = batch.value.toString() }"
                >
                  <div>
                    <!-- Card Header -->
                    <div class="card-header-custom">
                      <span class="badge-batch" x-text="'Batch ' + (batch.value.toUpperCase().startsWith('BATCH ') ? batch.value.substring(6) : batch.value)"></span>
                      <div class="custom-radio-indicator">
                        <div class="radio-dot" x-show="form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value"></div>
                      </div>
                    </div>

                    <!-- Hidden Input for accessibility & standard form submit fallback -->
                    <input 
                      type="radio" 
                      name="batch_pelatihan" 
                      :value="batch.value" 
                      :checked="form.batch_pelatihan === batch.value.toString() || form.batch_pelatihan === batch.value" 
                      :disabled="batch.restricted" 
                      class="sr-only"
                    />

                    <!-- Pelatihan Title/Name -->
                    <h6 class="pelatihan-title" x-text="batch.label.split(' : ')[1] ? batch.label.split(' : ')[1].split(' (')[0] : batch.label"></h6>
                  </div>

                  <!-- Details & Meta Info -->
                  <div>
                    <!-- Dinas Badge -->
                    <div class="card-info-item">
                      <i class="icon-base ti tabler-building"></i>
                      <span x-text="batch.dinas_name" class="font-medium"></span>
                    </div>

                    <!-- Date/Schedule -->
                    <div class="card-info-item">
                      <i class="icon-base ti tabler-calendar"></i>
                      <span x-text="batch.label.includes('(') ? batch.label.substring(batch.label.indexOf('(') + 1, batch.label.lastIndexOf(')')) : 'COMING SOON'"></span>
                    </div>

                    <!-- Kecamatan Specific Info -->
                    <div class="card-info-item">
                      <i class="icon-base ti tabler-map-pin"></i>
                      <span x-text="batch.kecamatans && batch.kecamatans.length > 0 ? 'Khusus: ' + batch.kecamatans.join(', ') : 'Untuk semua kecamatan'"></span>
                    </div>

                    <!-- Restriction Warnings if applicable -->
                    <template x-if="batch.restricted">
                      <div class="restricted-warning-box">
                        <i class="icon-base ti tabler-alert-triangle"></i>
                        <span>
                          Sudah pernah mengikuti pelatihan di <strong x-text="batch.restricted_dinas"></strong>. Tersedia setelah <strong x-text="batch.restricted_until"></strong>
                        </span>
                      </div>
                    </template>
                  </div>
                </div>
              </template>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.batch_pelatihan }" x-text="errors.batch_pelatihan"></div>
          </div>
        </div>


      </div>

      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('dashboard.peserta.form-pendidikan') }}" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="submitForm()">
          Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
        </button>
      </div>

    </form>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script>
  document.addEventListener('alpine:init', function() {
    Alpine.data('minatForm', function() {
      var profile = @json($profile);
      return {
        batchList: @json($batchList ?? []),
        form: {
          batch_pelatihan: profile ? (profile.batch_pelatihan || '') : '',
        },
        errors: {},

        clearErrors() { this.errors = {}; },

        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.batch_pelatihan) { errs.batch_pelatihan = 'PILIH MINIMAL 1 BATCH PELATIHAN'; valid = false; }

          this.errors = errs;
          return valid;
        },

        submitForm() {
          if (!this.validate()) return;
          document.getElementById('formMinat').submit();
        },
      };
    });
  });
</script>
@endsection

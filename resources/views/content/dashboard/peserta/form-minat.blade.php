@php
$configData = Helper::appClasses();
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
          <i class="icon-base ti tabler-heart me-2" style="color: #6366f1;"></i>MINAT PELATIHAN
        </h5>

        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom">PILIH PELATIHAN (BATCH) YANG ANDA MINATI *</label>
            <p class="text-white-50-custom small mb-3" style="font-size: 12px; font-style: italic;">
              PELAKSANAAN BATCH SELANJUTNYA AKAN DI INFORMASIKAN LEWAT EMAIL ATAU WA TERDAFTAR
            </p>
            <div class="mt-1" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
              <template x-for="(batch, index) in batchList" :key="index">
                <div class="form-check" :class="{ 'opacity-50': batch.restricted }">
                  <input class="form-check-input form-check-input-custom" type="radio" name="batch_pelatihan"
                    :id="'batch_' + index" :value="batch.value" x-model="form.batch_pelatihan" :disabled="batch.restricted" />
                  <label class="form-check-label text-white-70-custom small" :for="'batch_' + index" x-text="batch.label" style="line-height: 1.4;"></label>
                  <template x-if="batch.restricted">
                    <span class="d-block mt-1" style="font-size: 0.65rem;">
                      <span class="badge bg-danger bg-opacity-25 text-danger fw-normal">
                        <i class="icon-base ti tabler-alert-triangle me-1"></i>⛔ Sudah pernah mengikuti pelatihan di <span x-text="batch.restricted_dinas"></span>. Tersedia setelah <span x-text="batch.restricted_until"></span>
                      </span>
                    </span>
                  </template>
                  <template x-if="!batch.restricted">
                    <span class="d-block mt-1" style="font-size: 0.65rem;">
                      <span class="badge bg-info bg-opacity-25 text-info fw-normal">
                        <i class="icon-base ti tabler-building me-1"></i><span x-text="batch.dinas_name"></span>
                      </span>
                    </span>
                  </template>
                  <template x-if="!batch.restricted && batch.kecamatans && batch.kecamatans.length > 0">
                    <span class="d-block mt-1" style="font-size: 0.65rem;">
                      <span class="badge bg-info bg-opacity-25 text-info fw-normal">
                        <i class="icon-base ti tabler-map-pin me-1"></i>Khusus: <span x-text="batch.kecamatans.join(', ')"></span>
                      </span>
                    </span>
                  </template>
                  <template x-if="!batch.restricted && (!batch.kecamatans || batch.kecamatans.length === 0)">
                    <span class="d-block mt-1" style="font-size: 0.65rem;">
                      <span class="badge bg-secondary bg-opacity-25 text-white-50 fw-normal">
                        <i class="icon-base ti tabler-world me-1"></i>Untuk semua kecamatan
                      </span>
                    </span>
                  </template>
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

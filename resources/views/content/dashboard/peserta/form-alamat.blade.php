@php
$configData = Helper::appClasses();

// Config lookup helpers untuk alamat & kontak
$akLabels = $fieldsAlamatKontak->pluck('label', 'field_key');
$akPlaceholders = $fieldsAlamatKontak->pluck('placeholder', 'field_key');
$akRequired = $fieldsAlamatKontak->where('is_required', true)->pluck('field_key')->toArray();
$akActive = $fieldsAlamatKontak->where('is_active', true)->pluck('field_key')->toArray();

$platList = $platformOptions->toArray();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Alamat & Kontak')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>


  /* Select2 Custom Styling for Glassmorphic Dark Theme */
  .select2-container--default .select2-selection--single {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 5px !important;
    height: 42px !important;
    display: flex;
    align-items: center;
    transition: all 0.3s ease !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #ffffff !important;
    padding-left: 14px !important;
    padding-right: 28px !important;
    font-size: 14px !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px !important;
    right: 10px !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: rgba(255, 255, 255, 0.5) transparent transparent transparent !important;
    border-width: 5px 4px 0 4px !important;
  }
  .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
    border-color: transparent transparent rgba(255, 255, 255, 0.5) transparent !important;
    border-width: 0 4px 5px 4px !important;
  }
  .select2-container--default.select2-container--focus .select2-selection--single,
  .select2-container--default.select2-container--open .select2-selection--single {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
  }

  /* Select2 Dropdown */
  .select2-dropdown {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 5px !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
    z-index: 99999 !important;
  }
  .select2-container--default .select2-results__option {
    color: rgba(255, 255, 255, 0.8) !important;
    padding: 8px 14px !important;
    font-size: 14px !important;
    background-color: transparent !important;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected],
  .select2-container--default .select2-results__option[aria-selected="true"] {
    background: linear-gradient(135deg, #6366f1, #7c3aed) !important;
    color: #ffffff !important;
  }
  .select2-container--default .select2-results__option[aria-disabled="true"] {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .select2-search--dropdown {
    background: transparent !important;
    padding: 8px !important;
  }
  .select2-container--default .select2-search--dropdown .select2-search__field {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    border-radius: 4px !important;
    padding: 6px 10px !important;
    font-size: 13px !important;
  }

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
  .field-group-triple {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 16px;
  }
  .field-group-quad {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 760px) {
    .field-group-quad { grid-template-columns: 1fr 1fr; gap: 12px; }
  }
  @media (max-width: 660px) {
    .field-group-triple { grid-template-columns: 1fr; gap: 12px; }
    .field-group-quad { grid-template-columns: 1fr; gap: 12px; }
  }

  /* Toast Notification */
  .toast-notif {
    position: fixed; top: 20px; right: 20px; z-index: 99999;
    padding: 14px 20px; border-radius: 5px; font-size: 14px;
    font-family: 'Outfit', sans-serif;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    max-width: 380px; display: none; align-items: center; gap: 10px;
    opacity: 0; transition: opacity 0.3s ease;
  }
  .toast-notif.show { display: flex; opacity: 1; }
  .toast-notif.success { background: #065f46; border: 1px solid #10b981; color: #d1fae5; }
  .toast-notif.error { background: #7f1d1d; border: 1px solid #f87171; color: #fecaca; }
  .toast-notif .toast-icon { font-size: 20px; flex-shrink: 0; }


  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  /* Override container-p-y padding top khusus halaman ini */
  body .content-wrapper > .container-p-y {
    padding-top: 0.75rem !important;
  }
</style>
@endsection

{{-- ========== 404 CHECK: Pastikan user sudah isi data pribadi ========== --}}
@php
// Pengecekan data pribadi sudah dilakukan di level Controller (formAlamat).
// Di view kita asumsikan data pribadi selalu ada karena jika tidak, controller sudah me-redirect.
$hasDataPribadi = true;
@endphp

@section('content')
@if(!$hasDataPribadi)
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>
<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  <div class="glass-card-dashboard text-center py-5" style="max-width: 520px; margin: 40px auto;">
    <div style="width: 72px; height: 72px; border-radius: 5px; background: linear-gradient(135deg, #f87171, #ef4444); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 0 20px rgba(248,113,113,0.3);">
      <i class="icon-base ti tabler-alert-triangle text-white fs-2"></i>
    </div>
    <h4 class="fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif;">Data Pribadi Belum Diisi</h4>
    <p class="text-white-50-custom mb-4" style="font-size: 14px; max-width: 360px; margin: 0 auto 20px;">
      Silakan isi data pribadi terlebih dahulu sebelum melanjutkan ke alamat &amp; kontak.
    </p>
    <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
      <i class="icon-base ti tabler-arrow-left me-1"></i> Ke Form Data Pribadi
    </a>
  </div>
</div>
@else
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1; margin-top: -1.5rem;">
  <!-- Header -->
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-map-pin text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Alamat &amp; Kontak</h4>
        <p class="text-white-50-custom mb-0 small">Lengkapi data alamat dan kontak Anda</p>
      </div>
    </div>
  </div>

  @php
    $profile = \App\Models\PesertaProfile::where('user_id', auth()->id())->first();
    $step1Done = $profile && !empty($profile->nama_lengkap) && !empty($profile->nik);
    $step2Done = $profile && !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
    $step3Done = $profile && !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
    $step4Done = $profile && !empty($profile->pelatihan_id);
    $step5Done = $profile && !empty($profile->jawaban_pertanyaan);
    $step6Done = $profile && $profile->is_completed;
  @endphp

  <!-- Step Indicator: 6 Steps -->
  <div class="step-indicator mb-4">
    <div class="step-progress-line" style="transform: scaleX(0.2); transform-origin: left;"></div>
    
    <!-- Step 1: Data Diri -->
    <div class="step-item {{ $step1Done ? 'completed' : '' }}" @if($step1Done) onclick="window.location.href='{{ route('dashboard.peserta.form-pendaftaran') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step1Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          1
        @endif
      </div>
      <div class="step-label">Data Diri</div>
    </div>
    
    <!-- Step 2: Alamat (active) -->
    <div class="step-item active">
      <div class="step-circle">2</div>
      <div class="step-label">Alamat</div>
    </div>
    
    <!-- Step 3: Pendidikan -->
    <div class="step-item {{ $step3Done ? 'completed' : '' }}" @if($step3Done) onclick="window.location.href='{{ route('dashboard.peserta.form-pendidikan') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step3Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          3
        @endif
      </div>
      <div class="step-label">Pendidikan</div>
    </div>
    
    <!-- Step 4: Pelatihan -->
    <div class="step-item {{ $step4Done ? 'completed' : '' }}" @if($step4Done) onclick="window.location.href='{{ route('dashboard.peserta.form-minat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step4Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          4
        @endif
      </div>
      <div class="step-label">Pilihan Pelatihan</div>
    </div>
    
    <!-- Step 5: Dokumen -->
    <div class="step-item {{ $step5Done ? 'completed' : '' }}" @if($step5Done) onclick="window.location.href='{{ route('dashboard.peserta.form-dokumen') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step5Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          5
        @endif
      </div>
      <div class="step-label">Dokumen</div>
    </div>
    
    <!-- Step 6: Review -->
    <div class="step-item {{ $step6Done ? 'completed' : '' }}">
      <div class="step-circle">
        @if($step6Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          6
        @endif
      </div>
      <div class="step-label">Review</div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="glass-card-dashboard" x-data="alamatForm()" x-cloak>
    <!-- Toast Notification -->
    <div class="toast-notif" :class="{ 'show': toast.show, 'success': toast.type === 'success', 'error': toast.type === 'error' }">
      <i class="icon-base ti toast-icon" :class="toast.type === 'success' ? 'tabler-circle-check' : 'tabler-alert-circle'"></i>
      <span x-text="toast.message"></span>
    </div>

    <!-- Error Summary Banner -->
    <template x-if="Object.keys(errors).length > 0">
      <div style="background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); border-radius: 5px; color: #fca5a5; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px;">
        <i class="icon-base ti tabler-alert-circle me-1 fs-5 mt-1 flex-shrink-0"></i>
        <div>
          <div class="fw-semibold mb-1" style="font-size: 13px;">
            <span x-text="Object.keys(errors).length === 1 ? 'Ada 1 field yang harus diisi:' : 'Ada ' + Object.keys(errors).length + ' field yang harus diisi:'"></span>
          </div>
          <ul style="margin: 0; padding-left: 16px; font-size: 12px; color: rgba(255,255,255,0.7); list-style: disc;">
            <template x-for="(msg, field) in errors" :key="field">
              <li x-text="msg"></li>
            </template>
          </ul>
        </div>
      </div>
    </template>

    <form id="formAlamat" action="{{ route('dashboard.peserta.form-alamat.store') }}" method="POST"
      @submit.prevent="if(validateForm()) { saving = true; $el.submit(); }">
      @csrf

      <!-- Baris 1: Provinsi + Kota + Kecamatan (3 kolom sejajar) -->
      <div class="field-group-triple">
        @if(in_array('provinsi', $akActive))
        <div>
          <label class="form-label form-label-custom" for="provinsi">
            {{ $akLabels['provinsi'] ?? 'Provinsi' }}
          </label>
          <input type="text" id="provinsi" name="provinsi" class="form-control form-control-custom form-control-uppercase"
            x-model="form.provinsi" value="Jawa Barat" readonly
            style="opacity: 0.8; cursor: not-allowed;" />
          <small style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">
            <i class="icon-base ti tabler-lock me-1"></i>Jawa Barat
          </small>
        </div>
        @endif
        @if(in_array('kota', $akActive))
        <div>
          <label class="form-label form-label-custom" for="kota">
            {{ $akLabels['kota'] ?? 'Kota/Kabupaten' }}
          </label>
          <input type="text" id="kota" name="kota" class="form-control form-control-custom form-control-uppercase"
            x-model="form.kota" value="BANDUNG" readonly
            style="opacity: 0.8; cursor: not-allowed;" />
          <small style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">
            <i class="icon-base ti tabler-lock me-1"></i>Bandung
          </small>
        </div>
        @endif
        @if(in_array('kecamatan_id', $akActive))
        <div>
          <label class="form-label form-label-custom" for="kecamatan_id">
            {{ $akLabels['kecamatan_id'] ?? 'Kecamatan' }}
            @if(in_array('kecamatan_id', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <select id="kecamatan_id" name="kecamatan_id" class="form-control form-control-custom"
            x-model="form.kecamatan_id"
            :class="{ 'is-invalid': errors.kecamatan_id }">
            <option value="">{{ $akPlaceholders['kecamatan_id'] ?? 'PILIH KECAMATAN' }}</option>
            @foreach($kecamatans as $kec)
              <option value="{{ $kec->id }}">{{ strtoupper($kec->name) }}</option>
            @endforeach
          </select>
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kecamatan_id }" x-text="errors.kecamatan_id"></div>
        </div>
        @endif
      </div>

      <!-- Baris 2: Kelurahan + RT + RW + Kode Pos (4 kolom sejajar) -->
      <div class="field-group-quad mt-3">
        @if(in_array('kelurahan_id', $akActive))
        <div>
          <label class="form-label form-label-custom" for="kelurahan_id">
            {{ $akLabels['kelurahan_id'] ?? 'Kelurahan' }}
            @if(in_array('kelurahan_id', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <select id="kelurahan_id" name="kelurahan_id" class="form-control form-control-custom"
            x-model="form.kelurahan_id" :disabled="!form.kecamatan_id"
            @change="updateKodepos()"
            :class="{ 'is-invalid': errors.kelurahan_id }">
            <option value="">-- Pilih Kelurahan --</option>
            <template x-for="k in kelurahans" :key="k.id">
              <option :value="k.id" x-text="k.name" :selected="k.id == form.kelurahan_id"></option>
            </template>
          </select>
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kelurahan_id }" x-text="errors.kelurahan_id"></div>
        </div>
        @endif
        @if(in_array('rt', $akActive))
        <div>
          <label class="form-label form-label-custom" for="rt">
            {{ $akLabels['rt'] ?? 'RT' }}
            @if(in_array('rt', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="text" id="rt" name="rt" class="form-control form-control-custom"
            x-model="form.rt" placeholder="{{ $akPlaceholders['rt'] ?? 'RT' }}" maxlength="3"
            @input="form.rt = form.rt.replace(/\D/g, '')"
            :class="{ 'is-invalid': errors.rt }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.rt }" x-text="errors.rt"></div>
        </div>
        @endif
        @if(in_array('rw', $akActive))
        <div>
          <label class="form-label form-label-custom" for="rw">
            {{ $akLabels['rw'] ?? 'RW' }}
            @if(in_array('rw', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="text" id="rw" name="rw" class="form-control form-control-custom"
            x-model="form.rw" placeholder="{{ $akPlaceholders['rw'] ?? 'RW' }}" maxlength="3"
            @input="form.rw = form.rw.replace(/\D/g, '')"
            :class="{ 'is-invalid': errors.rw }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.rw }" x-text="errors.rw"></div>
        </div>
        @endif
        @if(in_array('kodepos', $akActive))
        <div>
          <label class="form-label form-label-custom" for="kodepos">
            {{ $akLabels['kodepos'] ?? 'Kode Pos' }}
            @if(in_array('kodepos', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="text" id="kodepos" name="kodepos" class="form-control form-control-custom"
            x-model="form.kodepos" placeholder="{{ $akPlaceholders['kodepos'] ?? 'KODE POS' }}"
            @input="form.kodepos = form.kodepos.replace(/\D/g, '')" maxlength="5"
            :class="{ 'is-invalid': errors.kodepos }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kodepos }" x-text="errors.kodepos"></div>
        </div>
        @endif
      </div>

      <!-- Baris 3: Nama Jalan/Gang (full width) -->
      @if(in_array('alamat_ktp', $akActive))
      <div class="field-group mt-3">
        <div class="field-full">
          <label class="form-label form-label-custom" for="alamat_ktp">
            {{ $akLabels['alamat_ktp'] ?? 'Nama Jalan/Gang' }}
            @if(in_array('alamat_ktp', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control form-control-custom form-control-uppercase"
            x-model="form.alamat_ktp" placeholder="{{ $akPlaceholders['alamat_ktp'] ?? 'NAMA JALAN/GANG' }}"
            @input="form.alamat_ktp = form.alamat_ktp.toUpperCase()"
            :class="{ 'is-invalid': errors.alamat_ktp }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.alamat_ktp }" x-text="errors.alamat_ktp"></div>
        </div>
      </div>
      @endif

      <!-- Baris 4: WhatsApp + Email (2 kolom) -->
      <div class="field-group mt-3">
        @if(in_array('whatsapp', $akActive))
        <div>
          <label class="form-label form-label-custom" for="whatsapp">
            {{ $akLabels['whatsapp'] ?? 'Nomor WhatsApp' }}
            @if(in_array('whatsapp', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="tel" id="whatsapp" name="whatsapp" class="form-control form-control-custom"
            x-model="form.whatsapp" placeholder="{{ $akPlaceholders['whatsapp'] ?? '08XXXXXXXXXX' }}"
            @input="form.whatsapp = form.whatsapp.replace(/\D/g, ''); checkWa()"
            :class="{ 'is-invalid': errors.whatsapp }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.whatsapp }" x-text="errors.whatsapp"></div>
          <div id="wa-feedback" class="small mt-1" :class="waFeedbackClass" x-show="waFeedbackShow" x-text="waFeedbackText"></div>
        </div>
        @endif
        @if(in_array('email', $akActive))
        <div>
          <label class="form-label form-label-custom" for="email">
            {{ $akLabels['email'] ?? 'Email' }}
            @if(in_array('email', $akRequired)) <span class="text-danger">*</span> @endif
          </label>
          <input type="email" id="email" name="email" class="form-control form-control-custom"
            x-model="form.email" placeholder="{{ $akPlaceholders['email'] ?? 'CONTOH@EMAIL.COM' }}"
            :class="{ 'is-invalid': errors.email }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.email }" x-text="errors.email"></div>
        </div>
        @endif
      </div>

      <!-- Baris 5: Link Media Sosial (full width) -->
      @if(in_array('link_medsos', $akActive))
      <div class="field-group mt-3">
        <div class="field-full">
          <label class="form-label form-label-custom">
            {{ $akLabels['link_medsos'] ?? 'Link Media Sosial' }}
          </label>

          <template x-for="(medsos, index) in form.medsos_list" :key="index">
            <div class="d-flex align-items-center gap-2 mb-2">
              <select x-model="medsos.platform" class="form-control form-control-custom" style="width: 140px; flex-shrink: 0;">
                @foreach($platList as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
              </select>
              <input type="url" x-model="medsos.url" class="form-control form-control-custom"
                placeholder="HTTPS://..."
                @input="medsos.url = medsos.url.toLowerCase()" />
              <button type="button" class="btn btn-sm" style="background: none; border: none; color: #f87171; padding: 4px 8px;"
                @click="form.medsos_list.splice(index, 1)" x-show="form.medsos_list.length > 1">
                <i class="icon-base ti tabler-trash"></i>
              </button>
            </div>
          </template>

          <button type="button" class="btn btn-sm mt-1" style="background: rgba(99,102,241,0.1); border: 1px dashed rgba(99,102,241,0.3); color: #818cf8; border-radius: 5px; padding: 6px 14px; font-size: 12px;"
            @click="form.medsos_list.push({platform: '{{ collect($platList)->keys()->first() ?? 'Instagram' }}', url: ''})">
            <i class="icon-base ti tabler-plus me-1"></i> Tambah Media Sosial
          </button>

          <input type="hidden" name="link_medsos" x-bind:value="JSON.stringify(form.medsos_list)" />
        </div>
      </div>
      @endif

      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px; text-decoration: none;">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
        </a>
        <button type="submit" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;"
          :disabled="saving">
          <span x-show="!saving">Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i></span>
          <span x-show="saving"><i class="icon-base ti tabler-loader animate-spin me-1"></i> Menyimpan...</span>
        </button>
      </div>
    </form>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
@endif
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script>
$(function () {
  // === Select2 Init (Vuexy Pattern) ===
  var select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      var $this = $(this);
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Pilih',
        dropdownParent: $this.parent()
      });
    });
  }

  // === Sinkronkan Select2 ke Alpine.js ===
  $(document).on('select2:select', '.select2', function () {
    $(this)[0].dispatchEvent(new Event('input', { bubbles: true }));
  });
});

// === Re-init Select2 ===
window.reinitSelect2 = function() {
  if (typeof $ === 'undefined' || !$.fn.select2) return;
  $('.select2').each(function() {
    try {
      $(this).select2('destroy');
    } catch(e) {}
  });
  $('.select2-container').remove();
  $('.select2:visible').each(function () {
    var $this = $(this);
    if ($this.hasClass('select2-hidden-accessible')) return;
    try {
      $this.wrap('<div class="position-relative"></div>');
      $this.select2({
        placeholder: 'Pilih',
        dropdownParent: $this.parent()
      });
    } catch(e) {}
  });
}
</script>

<script>
  // Data dari server untuk diisi otomatis ke form (Pola PMBM)
  window._formData = {!! json_encode($data) !!};

  document.addEventListener('alpine:init', function() {
    Alpine.data('alamatForm', function() {
      var fd = window._formData || {};
      return {
        saving: false,
        toast: { show: false, message: '', type: 'success' },
        errors: {},
        waFeedbackShow: false,
        waFeedbackText: '',
        waFeedbackClass: '',
        waTimeout: null,
        kelurahans: [],
        form: {
          alamat_ktp: fd.alamat_ktp || '',
          rt: fd.rt || '',
          rw: fd.rw || '',
          kelurahan_id: fd.kelurahan_id || '',
          kecamatan_id: fd.kecamatan_id || '',
          kota: fd.kota || 'BANDUNG',
          provinsi: fd.provinsi || 'Jawa Barat',
          kodepos: fd.kodepos || '',
          whatsapp: fd.whatsapp || '',
          email: fd.email || '',
          medsos_list: (function() {
            var saved = fd.link_medsos;
            if (saved && Array.isArray(saved) && saved.length > 0) {
              return saved;
            }
            return [{platform: 'Instagram', url: ''}];
          })(),
        },

        init() {
          // Fetch kelurahans on load if kecamatan_id is set
          if (this.form.kecamatan_id) {
            this.fetchKelurahans(this.form.kecamatan_id, this.form.kelurahan_id);
          }
          
          // Watch kecamatan_id for changes to load kelurahans
          this.$watch('form.kecamatan_id', (value) => {
            if (value) {
              this.fetchKelurahans(value);
            } else {
              this.kelurahans = [];
              this.form.kelurahan_id = '';
              this.form.kodepos = '';
            }
          });
        },

        fetchKelurahans(kecamatanId, preselectedId = null) {
          fetch('/api/kelurahan?kecamatan_id=' + kecamatanId)
            .then(res => res.json())
            .then(data => {
              this.kelurahans = data;
              if (preselectedId) {
                this.form.kelurahan_id = preselectedId;
                const selected = this.kelurahans.find(k => k.id == preselectedId);
                if (selected && !this.form.kodepos) {
                  this.form.kodepos = selected.kodepos || '';
                }
              } else {
                this.form.kelurahan_id = '';
                this.form.kodepos = '';
              }
            })
            .catch(() => {
              this.kelurahans = [];
            });
        },

        updateKodepos() {
          const selected = this.kelurahans.find(k => k.id == this.form.kelurahan_id);
          if (selected) {
            this.form.kodepos = selected.kodepos || '';
          } else {
            this.form.kodepos = '';
          }
        },

        clearErrors() { this.errors = {}; },

        showToast(type, message) {
          this.toast = { show: true, message: message, type: type };
          clearTimeout(this._toastTimer);
          this._toastTimer = setTimeout(() => {
            this.toast.show = false;
          }, 3000);
        },

        convertWaNumber(num) {
          num = num.replace(/\D/g, '');
          if (num.startsWith('0')) return '62' + num.substring(1);
          if (num.startsWith('62') && num.length >= 10) return num;
          return '62' + num;
        },

        checkWa() {
          var raw = this.form.whatsapp.replace(/\D/g, '');
          if (raw.length < 8) {
            this.waFeedbackShow = false;
            return;
          }
          var self = this;
          clearTimeout(this.waTimeout);
          this.waFeedbackShow = true;
          this.waFeedbackText = 'Memeriksa nomor WhatsApp...';
          this.waFeedbackClass = 'd-flex align-items-center text-info';
          this.waTimeout = setTimeout(function() {
            var finalNumber = self.convertWaNumber(raw);
            fetch('{{ route('landing.check-wa') }}', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
              body: JSON.stringify({ number: finalNumber })
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
              if (data.exists) {
                self.waFeedbackText = '✓ Nomor WhatsApp terdaftar';
                self.waFeedbackClass = 'd-flex align-items-center text-success';
              } else {
                self.waFeedbackText = '⚠ Nomor tidak terdaftar di WA';
                self.waFeedbackClass = 'd-flex align-items-center text-danger';
              }
            })
            .catch(function() {
              self.waFeedbackText = '⚠ Gagal verifikasi WA';
              self.waFeedbackClass = 'd-flex align-items-center text-warning';
            });
          }, 600);
        },

        validateForm() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.alamat_ktp.trim()) { errs.alamat_ktp = 'Nama Jalan/Gang wajib diisi'; valid = false; }
          if (!this.form.rt.trim()) { errs.rt = 'RT wajib diisi'; valid = false; }
          if (!this.form.rw.trim()) { errs.rw = 'RW wajib diisi'; valid = false; }
          if (!this.form.kelurahan_id) { errs.kelurahan_id = 'Pilih kelurahan'; valid = false; }
          if (!this.form.kecamatan_id) { errs.kecamatan_id = 'Pilih kecamatan'; valid = false; }
          if (!this.form.kodepos.trim()) { errs.kodepos = 'Kode pos wajib diisi'; valid = false; }
          if (!this.form.whatsapp.trim()) { errs.whatsapp = 'Nomor WA wajib diisi'; valid = false; }
          if (!this.form.email.trim()) { errs.email = 'Email wajib diisi'; valid = false; }

          this.errors = errs;

          if (!valid) {
            this.$nextTick(() => {
              var firstInvalid = document.querySelector('.is-invalid');
              if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
              }
            });
          }

          return valid;
        },
      };
    });
  });
</script>

@endsection

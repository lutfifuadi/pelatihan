@php
$configData = Helper::appClasses();

// Config lookup helpers untuk data pribadi
$dpLabels = $fieldsDataPribadi->pluck('label', 'field_key');
$dpPlaceholders = $fieldsDataPribadi->pluck('placeholder', 'field_key');
$dpRequired = $fieldsDataPribadi->where('is_required', true)->pluck('field_key')->toArray();
$dpActive = $fieldsDataPribadi->where('is_active', true)->pluck('field_key')->toArray();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Pendaftaran Peserta')

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
    height: 42px !important; /* matches form-control-custom height */
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
    background: #0f172a !important; /* slate-900 / dark theme background */
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




  .tab-pane-step { animation: fadeSlideIn 0.35s ease forwards; }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  .file-upload-area {
    background: rgba(255, 255, 255, 0.03);
    border: 1px dashed rgba(255, 255, 255, 0.15);
    border-radius: 5px;
    padding: 24px 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .file-upload-area:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(99, 102, 241, 0.4);
  }
  .file-upload-area.has-file { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }

  .review-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 16px;
    margin-bottom: 12px;
  }
  .review-section-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.8rem; font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .review-item {
    display: flex; justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    font-size: 13px;
  }
  .review-item:last-child { border-bottom: none; }
  .review-item-label { color: rgba(255, 255, 255, 0.5); }
  .review-item-value { color: rgba(255, 255, 255, 0.9); font-weight: 500; text-align: right; max-width: 60%; }

  .invalid-feedback-custom { color: #f87171; font-size: 11px; margin-top: 3px; display: none; }
  .invalid-feedback-custom.d-block { display: block; }

  .checkbox-group label { font-size: 13px; }

  /* Override container-p-y padding top khusus halaman ini */
  body .content-wrapper > .container-p-y {
    padding-top: 0.75rem !important;
  }
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1; margin-top: -1.5rem;">
  <!-- Header -->
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-file-text text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Pendaftaran Peserta</h4>
        <p class="text-white-50-custom mb-0 small">Lengkapi data pribadi Anda terlebih dahulu</p>
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
  @endphp

  <!-- Step Indicator: 6 Steps -->
  <div class="step-indicator mb-4">
    <div class="step-progress-line" style="transform: scaleX(0); transform-origin: left;"></div>
    
    <!-- Step 1: Data Diri (active) -->
    <div class="step-item active">
      <div class="step-circle">1</div>
      <div class="step-label">Data Diri</div>
    </div>
    
    <!-- Step 2: Alamat -->
    <div class="step-item {{ $step2Done ? 'completed' : '' }}" @if($step2Done) onclick="window.location.href='{{ route('dashboard.peserta.form-alamat') }}'" style="cursor: pointer;" @endif>
      <div class="step-circle">
        @if($step2Done)
          <i class="icon-base ti tabler-check" style="font-size: 16px;"></i>
        @else
          2
        @endif
      </div>
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
    <div class="step-item">
      <div class="step-circle">6</div>
      <div class="step-label">Review</div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="glass-card-dashboard" x-data="dataPribadiForm()" x-cloak>



    <!-- FORM -->
    <form id="formPeserta" @submit.prevent="submitForm()">
      @csrf

      <div class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-user me-2" style="color: #6366f1;"></i>Data Pribadi
        </h5>

        <!-- Nama Lengkap + NIK -->
        <div class="field-group">
          @if(in_array('nama_lengkap', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="nama_lengkap">
              {{ $dpLabels['nama_lengkap'] ?? 'Nama Lengkap Sesuai KTP' }}
              @if(in_array('nama_lengkap', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control form-control-custom form-control-uppercase"
              x-model="form.nama_lengkap" placeholder="{{ $dpPlaceholders['nama_lengkap'] ?? 'NAMA LENGKAP SESUAI KTP' }}"
              @input="form.nama_lengkap = form.nama_lengkap.toUpperCase()"
              :class="{ 'is-invalid': errors.nama_lengkap }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nama_lengkap }" x-text="errors.nama_lengkap"></div>
          </div>
          @endif
          @if(in_array('nik', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="nik">
              {{ $dpLabels['nik'] ?? 'NIK KTP' }}
              @if(in_array('nik', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="nik" name="nik" class="form-control form-control-custom"
              x-model="form.nik" placeholder="{{ $dpPlaceholders['nik'] ?? '15-16 DIGIT NIK' }}" maxlength="16"
              @input="form.nik = form.nik.replace(/\D/g, '')"
              :class="{ 'is-invalid': errors.nik }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nik }" x-text="errors.nik"></div>
          </div>
          @endif
        </div>

        <!-- Jenis Kelamin -->
        @if(in_array('jenis_kelamin', $dpActive))
        <div class="field-group mt-3">
          <div class="field-full">
            <label class="form-label form-label-custom">
              {{ $dpLabels['jenis_kelamin'] ?? 'Jenis Kelamin' }}
              @if(in_array('jenis_kelamin', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <div class="d-flex gap-4 mt-1">
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_l" name="jenis_kelamin" value="L" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_l">LAKI-LAKI</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_p" name="jenis_kelamin" value="P" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_p">PEREMPUAN</label>
              </div>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.jenis_kelamin }" x-text="errors.jenis_kelamin"></div>
          </div>
        </div>
        @endif

        <!-- Tempat, Tanggal, Bulan, Tahun Lahir -->
        <div class="field-group mt-3">
          @if(in_array('tempat_lahir', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="tempat_lahir">
              {{ $dpLabels['tempat_lahir'] ?? 'Tempat Lahir' }}
              @if(in_array('tempat_lahir', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control form-control-custom form-control-uppercase"
              x-model="form.tempat_lahir" placeholder="{{ $dpPlaceholders['tempat_lahir'] ?? 'JAKARTA' }}"
              @input="form.tempat_lahir = form.tempat_lahir.toUpperCase()"
              :class="{ 'is-invalid': errors.tempat_lahir }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tempat_lahir }" x-text="errors.tempat_lahir"></div>
          </div>
          @endif
          @if(in_array('tanggal_lahir', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="tanggal_lahir">
              {{ $dpLabels['tanggal_lahir'] ?? 'Tanggal Lahir' }}
              @if(in_array('tanggal_lahir', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-custom" x-model="form.tanggal_lahir"
              :class="{ 'is-invalid': errors.tanggal_lahir }">
              <option value="" disabled>{{ $dpPlaceholders['tanggal_lahir'] ?? 'PILIH TANGGAL' }}</option>
              <template x-for="tgl in 31" :key="tgl">
                <option :value="tgl" x-text="tgl"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tanggal_lahir }" x-text="errors.tanggal_lahir"></div>
          </div>
          @endif
          @if(in_array('bulan_lahir', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="bulan_lahir">
              {{ $dpLabels['bulan_lahir'] ?? 'Bulan Lahir' }}
              @if(in_array('bulan_lahir', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="bulan_lahir" name="bulan_lahir" class="select2 form-select form-control form-control-custom form-control-uppercase" x-model="form.bulan_lahir"
              :class="{ 'is-invalid': errors.bulan_lahir }">
              <option value="" disabled>{{ $dpPlaceholders['bulan_lahir'] ?? 'PILIH BULAN' }}</option>
              <option value="Januari">Januari</option>
              <option value="Februari">Februari</option>
              <option value="Maret">Maret</option>
              <option value="April">April</option>
              <option value="Mei">Mei</option>
              <option value="Juni">Juni</option>
              <option value="Juli">Juli</option>
              <option value="Agustus">Agustus</option>
              <option value="September">September</option>
              <option value="Oktober">Oktober</option>
              <option value="November">November</option>
              <option value="Desember">Desember</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.bulan_lahir }" x-text="errors.bulan_lahir"></div>
          </div>
          @endif
          @if(in_array('tahun_lahir', $dpActive))
          <div>
            <label class="form-label form-label-custom" for="tahun_lahir">
              {{ $dpLabels['tahun_lahir'] ?? 'Tahun Lahir' }}
              @if(in_array('tahun_lahir', $dpRequired)) <span class="text-danger">*</span> @endif
            </label>
            <select id="tahun_lahir" name="tahun_lahir" class="select2 form-select form-control form-control-custom" x-model="form.tahun_lahir"
              :class="{ 'is-invalid': errors.tahun_lahir }">
              <option value="" disabled>{{ $dpPlaceholders['tahun_lahir'] ?? 'PILIH TAHUN' }}</option>
              <template x-for="thn in tahunLahirOptions" :key="thn">
                <option :value="thn" x-text="thn"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tahun_lahir }" x-text="errors.tahun_lahir"></div>
          </div>
          @endif
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4"
            style="border-radius: 5px; font-size: 13px;"
            @click="submitForm()"
            :disabled="saving">
            <span x-show="!saving">Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i></span>
            <span x-show="saving"><i class="icon-base ti tabler-loader animate-spin me-1"></i> Menyimpan...</span>
          </button>
        </div>
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

// === Re-init Select2 saat pindah tab Alpine ===
window.reinitSelect2 = function() {
  if (typeof $ === 'undefined' || !$.fn.select2) return;
  // Destroy Select2 yang ada
  $('.select2').each(function() {
    try {
      $(this).select2('destroy');
    } catch(e) {}
  });
  $('.select2-container').remove();
  // Init ulang untuk yang visible
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
    Alpine.data('dataPribadiForm', function() {
      var fd = window._formData || {};
      return {
        saving: false,
        init() {
          this.$nextTick(() => {
            if (typeof window.reinitSelect2 === 'function') {
              setTimeout(window.reinitSelect2, 150);
            }
          });
        },
        errors: {},
        tahunLahirOptions: (function() {
          var years = [];
          for (var y = new Date().getFullYear(); y >= 1940; y--) { years.push(y); }
          return years;
        })(),
        form: {
          nama_lengkap: fd.nama_lengkap || '',
          jenis_kelamin: fd.jenis_kelamin || '',
          tempat_lahir: fd.tempat_lahir || '',
          tanggal_lahir: fd.tanggal_lahir || '',
          bulan_lahir: fd.bulan_lahir || '',
          tahun_lahir: fd.tahun_lahir || '',
          nik: fd.nik || '',
        },

        clearErrors() { this.errors = {}; },



        validate() {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (!this.form.nama_lengkap.trim()) { errs.nama_lengkap = 'Nama lengkap wajib diisi'; valid = false; }
          if (!this.form.jenis_kelamin) { errs.jenis_kelamin = 'Pilih jenis kelamin'; valid = false; }
          if (!this.form.tempat_lahir.trim()) { errs.tempat_lahir = 'Tempat lahir wajib diisi'; valid = false; }
          if (!this.form.tanggal_lahir) { errs.tanggal_lahir = 'Pilih tanggal lahir'; valid = false; }
          if (!this.form.bulan_lahir) { errs.bulan_lahir = 'Pilih bulan lahir'; valid = false; }
          if (!this.form.tahun_lahir) { errs.tahun_lahir = 'Pilih tahun lahir'; valid = false; }
          if (!this.form.nik.trim() || this.form.nik.length < 15 || this.form.nik.length > 16) { errs.nik = 'NIK harus 15-16 digit angka'; valid = false; }

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

        submitForm() {
          if (!this.validate()) return;

          this.saving = true;
          var self = this;
          var formData = new FormData();
          formData.append('nama_lengkap', this.form.nama_lengkap);
          formData.append('jenis_kelamin', this.form.jenis_kelamin);
          formData.append('tempat_lahir', this.form.tempat_lahir);
          formData.append('tanggal_lahir', this.form.tanggal_lahir);
          formData.append('bulan_lahir', this.form.bulan_lahir);
          formData.append('tahun_lahir', this.form.tahun_lahir);
          formData.append('nik', this.form.nik);
          formData.append('_token', '{{ csrf_token() }}');

          fetch('{{ route('dashboard.peserta.save-tab1') }}', {
            method: 'POST',
            body: formData
          })
          .then(function(res) {
            return res.json().then(function(data) {
              if (!res.ok) {
                var msg = 'Gagal menyimpan';
                if (data && data.message) msg = data.message;
                if (data && data.errors) {
                  var errList = Object.values(data.errors).flat();
                  if (errList.length) msg = errList.join(', ');
                }
                throw new Error(msg);
              }
              return data;
            }).catch(function(parseErr) {
              if (parseErr instanceof SyntaxError) {
                throw new Error('Server error (HTTP ' + res.status + ')');
              }
              throw parseErr;
            });
          })
            .then(function(data) {
              if (data.success) {
                self.saving = false;
                window.location.href = "{{ route('dashboard.peserta.form-alamat') }}";
              } else {
                throw new Error(data.message || 'Gagal menyimpan');
              }
            })
            .catch(function(e) {
              self.saving = false;
              console.error('Save error:', e);
              alert('Gagal menyimpan: ' + e.message);
            });
        },
      };
    });
  });
</script>
@endsection

@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Form Pendaftaran Peserta')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

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
  @media (max-width: 660px) {
    .field-group-triple { grid-template-columns: 1fr; gap: 12px; }
  }

  /* Step Indicator */
  .step-indicator {
    display: flex;
    justify-content: space-between;
    margin-bottom: 28px;
    position: relative;
  }
  .step-indicator::before {
    content: '';
    position: absolute;
    top: 18px;
    left: 40px;
    right: 40px;
    height: 2px;
    background: rgba(255, 255, 255, 0.08);
    z-index: 1;
  }
  .step-indicator .step-progress-line {
    position: absolute;
    top: 18px;
    left: 40px;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
    z-index: 2;
    transition: width 0.5s ease;
  }
  .step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    z-index: 3;
    cursor: pointer;
    position: relative;
  }
  .step-circle {
    width: 36px; height: 36px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; font-family: 'Sora', sans-serif;
    background: rgba(255, 255, 255, 0.06);
    border: 2px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.4);
    transition: all 0.4s ease;
    margin-bottom: 6px;
  }
  .step-item.active .step-circle {
    background: linear-gradient(135deg, #6366f1, #7c3aed);
    border-color: #6366f1; color: #fff;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
  }
  .step-item.completed .step-circle {
    background: #10b981; border-color: #10b981; color: #fff;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.3);
  }
  .step-label {
    font-size: 10px; text-transform: uppercase; letter-spacing: 0.06em;
    color: rgba(255, 255, 255, 0.35); font-weight: 600;
    transition: color 0.3s ease; text-align: center; white-space: nowrap;
  }
  .step-item.active .step-label { color: rgba(255, 255, 255, 0.85); }
  .step-item.completed .step-label { color: rgba(16, 185, 129, 0.8); }
  @media (max-width: 660px) {
    .step-label { display: none; }
    .step-indicator::before { left: 20px; right: 20px; }
    .step-indicator .step-progress-line { left: 20px; }
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
</style>
@endsection

@section('content')
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
  <!-- Header -->
  <div class="glass-card-dashboard mb-4">
    <div class="d-flex align-items-center gap-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-file-text text-white fs-4"></i>
      </div>
      <div>
        <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Form Pendaftaran Peserta</h4>
        <p class="text-white-50-custom mb-0 small">Lengkapi data diri Anda di 2 tahapan berikut</p>
      </div>
    </div>
  </div>

  <!-- Form Card -->
  <div class="glass-card-dashboard" x-data="multiStepForm()" x-cloak>

    <!-- FORM -->
    <form id="formPeserta" action="{{ route('dashboard.peserta.form-pendaftaran.store') }}" method="POST">
      @csrf

      <!-- TAB 1: DATA PRIBADI -->
      <div x-show="currentTab === 1" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-user me-2" style="color: #6366f1;"></i>Data Pribadi
        </h5>

        <!-- Nama Lengkap + NIK -->
        <div class="field-group">
          <div>
            <label class="form-label form-label-custom" for="nama_lengkap">Nama Lengkap Sesuai KTP</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control form-control-custom form-control-uppercase"
              x-model="form.nama_lengkap" placeholder="NAMA LENGKAP SESUAI KTP"
              @input="form.nama_lengkap = form.nama_lengkap.toUpperCase()"
              :class="{ 'is-invalid': errors.nama_lengkap }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nama_lengkap }" x-text="errors.nama_lengkap"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="nik">NIK KTP</label>
            <input type="text" id="nik" name="nik" class="form-control form-control-custom"
              x-model="form.nik" placeholder="15-16 DIGIT NIK" maxlength="16"
              @input="form.nik = form.nik.replace(/\D/g, '')"
              :class="{ 'is-invalid': errors.nik }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nik }" x-text="errors.nik"></div>
          </div>
        </div>

        <!-- Jenis Kelamin -->
        <div class="field-group mt-3">
          <div class="field-full">
            <label class="form-label form-label-custom">Jenis Kelamin</label>
            <div class="d-flex gap-4 mt-1">
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_l" name="jenis_kelamin" value="LAKI-LAKI" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_l">LAKI-LAKI</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_p" name="jenis_kelamin" value="PEREMPUAN" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_p">PEREMPUAN</label>
              </div>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.jenis_kelamin }" x-text="errors.jenis_kelamin"></div>
          </div>
        </div>

        <!-- Tempat, Tanggal, Bulan, Tahun Lahir -->
        <div class="field-group mt-3">
          <div>
            <label class="form-label form-label-custom" for="tempat_lahir">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control form-control-custom form-control-uppercase"
              x-model="form.tempat_lahir" placeholder="JAKARTA"
              @input="form.tempat_lahir = form.tempat_lahir.toUpperCase()"
              :class="{ 'is-invalid': errors.tempat_lahir }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tempat_lahir }" x-text="errors.tempat_lahir"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="tanggal_lahir">Tanggal Lahir</label>
            <select id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-custom" x-model="form.tanggal_lahir"
              :class="{ 'is-invalid': errors.tanggal_lahir }">
              <option value="" disabled>PILIH TANGGAL</option>
              <template x-for="tgl in 31" :key="tgl">
                <option :value="tgl" x-text="tgl"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tanggal_lahir }" x-text="errors.tanggal_lahir"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="bulan_lahir">Bulan Lahir</label>
            <select id="bulan_lahir" name="bulan_lahir" class="select2 form-select form-control form-control-custom form-control-uppercase" x-model="form.bulan_lahir"
              :class="{ 'is-invalid': errors.bulan_lahir }">
              <option value="" disabled>PILIH BULAN</option>
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
          <div>
            <label class="form-label form-label-custom" for="tahun_lahir">Tahun Lahir</label>
            <select id="tahun_lahir" name="tahun_lahir" class="select2 form-select form-control form-control-custom" x-model="form.tahun_lahir"
              :class="{ 'is-invalid': errors.tahun_lahir }">
              <option value="" disabled>PILIH TAHUN</option>
              <template x-for="thn in tahunLahirOptions" :key="thn">
                <option :value="thn" x-text="thn"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tahun_lahir }" x-text="errors.tahun_lahir"></div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="nextTab()">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- TAB 2: ALAMAT KTP & KONTAK -->
      <div x-show="currentTab === 2" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-map-pin me-2" style="color: #6366f1;"></i>Alamat KTP &amp; Kontak
        </h5>

        <!-- Alamat (text input) -->
        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom" for="alamat_ktp">Alamat Lengkap</label>
            <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control form-control-custom form-control-uppercase"
              x-model="form.alamat_ktp" placeholder="ALAMAT LENGKAP SESUAI KTP"
              @input="form.alamat_ktp = form.alamat_ktp.toUpperCase()"
              :class="{ 'is-invalid': errors.alamat_ktp }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.alamat_ktp }" x-text="errors.alamat_ktp"></div>
          </div>
        </div>

        <!-- RT, RW, Kecamatan (3 kolom) -->
        <div class="field-group-triple mt-3">
          <div>
            <label class="form-label form-label-custom" for="rt">RT</label>
            <input type="text" id="rt" name="rt" class="form-control form-control-custom"
              x-model="form.rt" placeholder="RT" maxlength="3"
              @input="form.rt = form.rt.replace(/\D/g, '')"
              :class="{ 'is-invalid': errors.rt }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.rt }" x-text="errors.rt"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="rw">RW</label>
            <input type="text" id="rw" name="rw" class="form-control form-control-custom"
              x-model="form.rw" placeholder="RW" maxlength="3"
              @input="form.rw = form.rw.replace(/\D/g, '')"
              :class="{ 'is-invalid': errors.rw }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.rw }" x-text="errors.rw"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="kecamatan_id">Kecamatan</label>
            <select id="kecamatan_id" name="kecamatan_id" class="form-control form-control-custom"
              x-model="form.kecamatan_id"
              :class="{ 'is-invalid': errors.kecamatan_id }">
              <option value="">PILIH KECAMATAN</option>
              @foreach($kecamatans as $kec)
                <option value="{{ $kec->id }}">{{ strtoupper($kec->name) }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kecamatan_id }" x-text="errors.kecamatan_id"></div>
          </div>
        </div>

        <!-- Kelurahan, Kota, Provinsi (3 kolom) -->
        <div class="field-group-triple mt-3">
          <div>
            <label class="form-label form-label-custom" for="kelurahan_id">Kelurahan</label>
            <select id="kelurahan_id" name="kelurahan_id" class="form-control form-control-custom"
              x-model="form.kelurahan_id" disabled
              :class="{ 'is-invalid': errors.kelurahan_id }">
              <option value="">-- Pilih Kecamatan Dahulu --</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kelurahan_id }" x-text="errors.kelurahan_id"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="kota">Kota/Kabupaten</label>
            <input type="text" id="kota" name="kota" class="form-control form-control-custom form-control-uppercase"
              x-model="form.kota" value="BANDUNG" readonly disabled
              style="opacity: 0.8; cursor: not-allowed;" />
            <small style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">
              <i class="icon-base ti tabler-lock me-1"></i>Wilayah Kota Bandung
            </small>
          </div>
          <div>
            <label class="form-label form-label-custom" for="provinsi">Provinsi</label>
            <input type="text" id="provinsi" name="provinsi" class="form-control form-control-custom form-control-uppercase"
              x-model="form.provinsi" value="Jawa Barat" readonly disabled
              style="opacity: 0.8; cursor: not-allowed;" />
            <small style="color: rgba(255,255,255,0.4); font-size: 0.7rem;">
              <i class="icon-base ti tabler-lock me-1"></i>Provinsi Jawa Barat
            </small>
          </div>
        </div>

        <!-- Kode Pos, WhatsApp, Email (3 kolom) -->
        <div class="field-group-triple mt-3">
          <div>
            <label class="form-label form-label-custom" for="kodepos">Kode Pos</label>
            <input type="text" id="kodepos" name="kodepos" class="form-control form-control-custom"
              x-model="form.kodepos" placeholder="KODE POS"
              @input="form.kodepos = form.kodepos.replace(/\D/g, '')" maxlength="5"
              :class="{ 'is-invalid': errors.kodepos }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kodepos }" x-text="errors.kodepos"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="whatsapp">Nomor WhatsApp</label>
            <input type="tel" id="whatsapp" name="whatsapp" class="form-control form-control-custom"
              x-model="form.whatsapp" placeholder="08XXXXXXXXXX"
              @input="form.whatsapp = form.whatsapp.replace(/\D/g, ''); checkWa()"
              :class="{ 'is-invalid': errors.whatsapp }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.whatsapp }" x-text="errors.whatsapp"></div>
            <div id="wa-feedback" class="small mt-1" :class="waFeedbackClass" x-show="waFeedbackShow" x-text="waFeedbackText"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control form-control-custom"
              x-model="form.email" placeholder="CONTOH@EMAIL.COM"
              :class="{ 'is-invalid': errors.email }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.email }" x-text="errors.email"></div>
          </div>
        </div>

        <!-- Link Media Sosial (Dinamis) -->
        <div class="field-group mt-3">
          <div class="field-full">
            <label class="form-label form-label-custom">Link Media Sosial</label>

            <template x-for="(medsos, index) in form.medsos_list" :key="index">
              <div class="d-flex align-items-center gap-2 mb-2">
                <select x-model="medsos.platform" class="form-control form-control-custom" style="width: 140px; flex-shrink: 0;">
                  <option value="Instagram">Instagram</option>
                  <option value="Facebook">Facebook</option>
                  <option value="LinkedIn">LinkedIn</option>
                  <option value="Twitter">Twitter / X</option>
                  <option value="TikTok">TikTok</option>
                  <option value="YouTube">YouTube</option>
                  <option value="Website">Website</option>
                  <option value="Lainnya">Lainnya</option>
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
              @click="form.medsos_list.push({platform: 'Instagram', url: ''})">
              <i class="icon-base ti tabler-plus me-1"></i> Tambah Media Sosial
            </button>

            <input type="hidden" name="link_medsos" x-bind:value="JSON.stringify(form.medsos_list)" />
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="prevTab()">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
          </button>
          <button type="submit" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
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
  // Data user dari server untuk diisi otomatis ke form
  window._userData = {!! json_encode($user) !!};

  document.addEventListener('alpine:init', function() {
    Alpine.data('multiStepForm', function() {
      var user = window._userData || {};
      return {
        currentTab: 1,
        init() {
          this.$nextTick(() => {
            if (typeof window.reinitSelect2 === 'function') {
              setTimeout(window.reinitSelect2, 150);
            }
          });
          this.$watch('currentTab', () => {
            if (typeof window.reinitSelect2 === 'function') {
              setTimeout(window.reinitSelect2, 150);
            }
          });
        },
        steps: [
          { id: 1, label: 'Data Pribadi' },
          { id: 2, label: 'Alamat & Kontak' },
        ],
        errors: {},
        waFeedbackShow: false,
        waFeedbackText: '',
        waFeedbackClass: '',
        waTimeout: null,
        tahunLahirOptions: (function() {
          var years = [];
          for (var y = new Date().getFullYear(); y >= 1940; y--) { years.push(y); }
          return years;
        })(),
        form: {
          nama_lengkap: user.name || '',
          jenis_kelamin: user.jenis_kelamin || '',
          tempat_lahir: user.tempat_lahir || '',
          tanggal_lahir: user.tanggal_lahir || '',
          bulan_lahir: user.bulan_lahir || '',
          tahun_lahir: user.tahun_lahir || '',
          nik: user.nik || '',
          alamat_ktp: user.alamat_ktp || '',
          rt: user.rt || '',
          rw: user.rw || '',
          kelurahan_id: user.kelurahan_id || '',
          kecamatan_id: user.kecamatan_id || '',
          kota: user.kota || 'BANDUNG',
          provinsi: user.provinsi || 'Jawa Barat',
          kodepos: user.kodepos || '',
          whatsapp: user.whatsapp || '',
          email: user.email || '',
          medsos_list: (function() {
            var saved = user.link_medsos;
            if (saved && Array.isArray(saved) && saved.length > 0) {
              return saved;
            }
            return [{platform: 'Instagram', url: ''}];
          })(),
        },

        clearErrors() { this.errors = {}; },

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

        validateTab(tabNumber) {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (tabNumber === 1) {
            if (!this.form.nama_lengkap.trim()) { errs.nama_lengkap = 'Nama lengkap wajib diisi'; valid = false; }
            if (!this.form.jenis_kelamin) { errs.jenis_kelamin = 'Pilih jenis kelamin'; valid = false; }
            if (!this.form.tempat_lahir.trim()) { errs.tempat_lahir = 'Tempat lahir wajib diisi'; valid = false; }
            if (!this.form.tanggal_lahir) { errs.tanggal_lahir = 'Pilih tanggal lahir'; valid = false; }
            if (!this.form.bulan_lahir) { errs.bulan_lahir = 'Pilih bulan lahir'; valid = false; }
            if (!this.form.tahun_lahir) { errs.tahun_lahir = 'Pilih tahun lahir'; valid = false; }
            if (!this.form.nik.trim() || this.form.nik.length < 15 || this.form.nik.length > 16) { errs.nik = 'NIK harus 15-16 digit angka'; valid = false; }
          }

          if (tabNumber === 2) {
            if (!this.form.alamat_ktp.trim()) { errs.alamat_ktp = 'Alamat KTP wajib diisi'; valid = false; }
            if (!this.form.rt.trim()) { errs.rt = 'RT wajib diisi'; valid = false; }
            if (!this.form.rw.trim()) { errs.rw = 'RW wajib diisi'; valid = false; }
            if (!this.form.kelurahan_id) { errs.kelurahan_id = 'Pilih kelurahan'; valid = false; }
            if (!this.form.kecamatan_id) { errs.kecamatan_id = 'Pilih kecamatan'; valid = false; }
            if (!this.form.kodepos.trim()) { errs.kodepos = 'Kode pos wajib diisi'; valid = false; }
            if (!this.form.whatsapp.trim()) { errs.whatsapp = 'Nomor WA wajib diisi'; valid = false; }
            if (!this.form.email.trim()) { errs.email = 'Email wajib diisi'; valid = false; }
          }

          this.errors = errs;
          return valid;
        },

        nextTab() {
          if (this.currentTab === 1 && this.validateTab(1)) {
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
            }).catch(function(e) { console.warn('Auto-save error:', e); });
          }

          if (this.currentTab >= 2) return;
          if (this.validateTab(this.currentTab)) {
            this.currentTab++;
            this.clearErrors();
            setTimeout(function() {
              if (typeof window.reinitSelect2 === 'function') window.reinitSelect2();
            }, 150);
          }
        },

        prevTab() {
          if (this.currentTab <= 1) return;
          this.currentTab--;
          this.clearErrors();
          setTimeout(function() {
            if (typeof window.reinitSelect2 === 'function') window.reinitSelect2();
          }, 150);
        },
      };
    });
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const kecamatanSelect = document.getElementById('kecamatan_id');
  const kelurahanSelect = document.getElementById('kelurahan_id');

  if (kecamatanSelect && kelurahanSelect) {
    kecamatanSelect.addEventListener('change', function() {
      const kecamatanId = this.value;
      kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
      kelurahanSelect.disabled = true;

      if (!kecamatanId) {
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kecamatan Dahulu --</option>';
        return;
      }

      fetch('/api/kelurahan?kecamatan_id=' + kecamatanId)
        .then(function(res) {
          if (!res.ok) throw new Error('Gagal');
          return res.json();
        })
        .then(function(data) {
          data.forEach(function(k) {
            const opt = document.createElement('option');
            opt.value = k.id;
            opt.textContent = k.name;
            kelurahanSelect.appendChild(opt);
          });
          kelurahanSelect.disabled = false;
        })
        .catch(function() {
          kelurahanSelect.innerHTML = '<option value="">— Gagal memuat data —</option>';
          kelurahanSelect.disabled = false;
        });
    });

    if (kecamatanSelect.value) {
      kecamatanSelect.dispatchEvent(new Event('change'));
    }
  }
});
</script>
@endsection

@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Form Data Lengkap Peserta')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  #multistep-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow: hidden;
    min-height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    padding: 20px 0;
  }
  #multistep-page-wrapper h1, #multistep-page-wrapper h2, #multistep-page-wrapper h3,
  #multistep-page-wrapper h4, #multistep-page-wrapper h5, #multistep-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }
  .hero-gradient-animated {
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
  }
  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 2;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-wide {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    width: 100%;
    max-width: 780px;
    z-index: 10;
    padding: 32px 30px;
  }
  @media (max-width: 820px) {
    .glass-card-wide { max-width: 100%; margin: 0 12px; padding: 24px 18px; }
  }

  .logo-icon-glow {
    width: 38px; height: 38px; border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
  }
  .logo-text-glow {
    font-family: 'Sora', sans-serif; font-size: 1.25rem;
    font-weight: 800; color: #ffffff; letter-spacing: -0.5px;
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
  .form-control-custom:-webkit-autofill,
  .form-control-custom:-webkit-autofill:hover,
  .form-control-custom:-webkit-autofill:focus,
  .form-control-custom:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
  }
  .form-control-custom:disabled, .form-control-custom[readonly] {
    background: rgba(255, 255, 255, 0.02) !important;
    opacity: 0.6;
  }
  textarea.form-control-custom { resize: vertical; min-height: 90px; }
  select.form-control-custom option {
    background: #1a1f2e;
    color: #f8fafc;
  }

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
  .form-check-input-custom:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
  }
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

  /* --- Step Indicator --- */
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
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    background: rgba(255, 255, 255, 0.06);
    border: 2px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.4);
    transition: all 0.4s ease;
    margin-bottom: 6px;
  }
  .step-item.active .step-circle {
    background: linear-gradient(135deg, #6366f1, #7c3aed);
    border-color: #6366f1;
    color: #fff;
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
  }
  .step-item.completed .step-circle {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.3);
  }
  .step-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: rgba(255, 255, 255, 0.35);
    font-weight: 600;
    transition: color 0.3s ease;
    text-align: center;
    white-space: nowrap;
  }
  .step-item.active .step-label { color: rgba(255, 255, 255, 0.85); }
  .step-item.completed .step-label { color: rgba(16, 185, 129, 0.8); }

  @media (max-width: 660px) {
    .step-label { display: none; }
    .step-indicator::before { left: 20px; right: 20px; }
    .step-indicator .step-progress-line { left: 20px; }
  }

  /* --- Tab Content Animation --- */
  .tab-pane-step {
    animation: fadeSlideIn 0.35s ease forwards;
  }
  @keyframes fadeSlideIn {
    0% { opacity: 0; transform: translateY(12px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  /* --- File Upload --- */
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
  .file-upload-area.has-file {
    border-color: #10b981;
    background: rgba(16, 185, 129, 0.05);
  }

  /* --- Review Card --- */
  .review-section {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 16px;
    margin-bottom: 12px;
  }
  .review-section-title {
    font-family: 'Sora', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .review-item {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    font-size: 13px;
  }
  .review-item:last-child { border-bottom: none; }
  .review-item-label { color: rgba(255, 255, 255, 0.5); }
  .review-item-value { color: rgba(255, 255, 255, 0.9); font-weight: 500; text-align: right; max-width: 60%; }

  .invalid-feedback-custom {
    color: #f87171;
    font-size: 11px;
    margin-top: 3px;
    display: none;
  }
  .invalid-feedback-custom.d-block { display: block; }

  .checkbox-group label { font-size: 13px; }
</style>
@endsection

@section('content')
<div id="multistep-page-wrapper">
  <div class="hero-gradient-animated"></div>
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="glass-card-wide" x-data="multiStepForm()" x-cloak>
    <!-- Logo -->
    <div class="d-flex justify-content-center mb-2">
      <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <div class="logo-icon-glow"><i class="icon-base ti tabler-bulb text-white fs-4"></i></div>
        <x-brand-logo size="sm" />
      </a>
    </div>

    <!-- Title -->
    <div class="text-center mb-3">
      <h4 class="mb-0 text-white fw-bold" style="font-size: 1.15rem;">Form Data Lengkap Peserta</h4>
      <p class="text-white-50-custom small mt-1">Lengkapi data diri Anda di 5 tahapan berikut</p>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator">
      <div class="step-progress-line" :style="'width: ' + ((currentTab - 1) / 4 * 100) + '%'"></div>
      <template x-for="(step, index) in steps" :key="index">
        <div class="step-item"
          :class="{ 'active': currentTab === step.id, 'completed': currentTab > step.id }"
          @click="goToTab(step.id)">
          <div class="step-circle" x-text="step.id"></div>
          <div class="step-label" x-text="step.label"></div>
        </div>
      </template>
    </div>

    <!-- FORM -->
    <form id="formPeserta" action="{{ route('peserta.form-pendaftaran.store') }}" method="POST" @submit.prevent="submitForm" enctype="multipart/form-data">
      @csrf

      <!-- ============================================================ -->
      <!-- TAB 1: DATA DIRI -->
      <!-- ============================================================ -->
      <div x-show="currentTab === 1" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-user me-2" style="color: #6366f1;"></i>Data Diri
        </h5>
        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom" for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control form-control-custom"
              x-model="form.nama_lengkap" placeholder="Nama lengkap sesuai KTP"
              :class="{ 'is-invalid': errors.nama_lengkap }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nama_lengkap }" x-text="errors.nama_lengkap"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="tempat_lahir">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control form-control-custom"
              x-model="form.tempat_lahir" placeholder="Jakarta"
              :class="{ 'is-invalid': errors.tempat_lahir }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tempat_lahir }" x-text="errors.tempat_lahir"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control form-control-custom"
              x-model="form.tanggal_lahir"
              :class="{ 'is-invalid': errors.tanggal_lahir }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tanggal_lahir }" x-text="errors.tanggal_lahir"></div>
          </div>
        </div>

        <div class="field-group mt-3">
          <div>
            <label class="form-label form-label-custom">Jenis Kelamin</label>
            <div class="d-flex gap-4 mt-1">
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_l" name="jenis_kelamin" value="Laki-laki" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_l">Laki-laki</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jk_p" name="jenis_kelamin" value="Perempuan" x-model="form.jenis_kelamin" />
                <label class="form-check-label text-white-50-custom small" for="jk_p">Perempuan</label>
              </div>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.jenis_kelamin }" x-text="errors.jenis_kelamin"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="agama">Agama</label>
            <select id="agama" name="agama" class="form-control form-control-custom" x-model="form.agama"
              :class="{ 'is-invalid': errors.agama }">
              <option value="" disabled>Pilih agama</option>
              <option value="Islam">Islam</option>
              <option value="Kristen">Kristen</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Konghucu">Konghucu</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.agama }" x-text="errors.agama"></div>
          </div>
        </div>

        <div class="field-group mt-3">
          <div class="field-full">
            <label class="form-label form-label-custom" for="nik">NIK</label>
            <input type="text" id="nik" name="nik" class="form-control form-control-custom"
              x-model="form.nik" placeholder="15-16 digit NIK" maxlength="16"
              @input="form.nik = form.nik.replace(/\D/g, '')"
              :class="{ 'is-invalid': errors.nik }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nik }" x-text="errors.nik"></div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="nextTab()">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 2: ALAMAT & KONTAK -->
      <!-- ============================================================ -->
      <div x-show="currentTab === 2" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-map-pin me-2" style="color: #6366f1;"></i>Alamat &amp; Kontak
        </h5>
        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom" for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat" class="form-control form-control-custom" rows="3"
              x-model="form.alamat" placeholder="RT/RW, Dusun, Desa/Kelurahan..."
              :class="{ 'is-invalid': errors.alamat }"></textarea>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.alamat }" x-text="errors.alamat"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="provinsi">Provinsi</label>
            <select id="provinsi" name="provinsi" class="form-control form-control-custom" x-model="form.provinsi"
              :class="{ 'is-invalid': errors.provinsi }">
              <option value="" disabled>Pilih provinsi</option>
              <option value="Jawa Barat">Jawa Barat</option>
              <option value="Jawa Timur">Jawa Timur</option>
              <option value="Jawa Tengah">Jawa Tengah</option>
              <option value="DKI Jakarta">DKI Jakarta</option>
              <option value="Banten">Banten</option>
              <option value="DI Yogyakarta">DI Yogyakarta</option>
              <option value="Sumatera Utara">Sumatera Utara</option>
              <option value="Sumatera Selatan">Sumatera Selatan</option>
              <option value="Sumatera Barat">Sumatera Barat</option>
              <option value="Kalimantan Timur">Kalimantan Timur</option>
              <option value="Sulawesi Selatan">Sulawesi Selatan</option>
              <option value="Bali">Bali</option>
              <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.provinsi }" x-text="errors.provinsi"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="kota">Kota/Kabupaten</label>
            <input type="text" id="kota" name="kota" class="form-control form-control-custom"
              x-model="form.kota" placeholder="Kota atau kabupaten"
              :class="{ 'is-invalid': errors.kota }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kota }" x-text="errors.kota"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="kecamatan">Kecamatan</label>
            <input type="text" id="kecamatan" name="kecamatan" class="form-control form-control-custom"
              x-model="form.kecamatan" placeholder="Kecamatan"
              :class="{ 'is-invalid': errors.kecamatan }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kecamatan }" x-text="errors.kecamatan"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="kode_pos">Kode Pos</label>
            <input type="text" id="kode_pos" name="kode_pos" class="form-control form-control-custom"
              x-model="form.kode_pos" placeholder="Kode pos"
              @input="form.kode_pos = form.kode_pos.replace(/\D/g, '')" maxlength="5"
              :class="{ 'is-invalid': errors.kode_pos }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.kode_pos }" x-text="errors.kode_pos"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="no_hp_alternatif">No. HP Alternatif</label>
            <input type="tel" id="no_hp_alternatif" name="no_hp_alternatif" class="form-control form-control-custom"
              x-model="form.no_hp_alternatif" placeholder="08xx"
              @input="form.no_hp_alternatif = form.no_hp_alternatif.replace(/\D/g, '')" />
          </div>
        </div>
        <div class="field-group mt-3">
          <div class="field-full">
            <label class="form-label form-label-custom" for="email_alternatif">Email Alternatif</label>
            <input type="email" id="email_alternatif" name="email_alternatif" class="form-control form-control-custom"
              x-model="form.email_alternatif" placeholder="email.alternatif@contoh.com" />
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="prevTab()">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
          </button>
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="nextTab()">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 3: PENDIDIKAN & PEKERJAAN -->
      <!-- ============================================================ -->
      <div x-show="currentTab === 3" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-school me-2" style="color: #6366f1;"></i>Pendidikan &amp; Pekerjaan
        </h5>
        <div class="field-group">
          <div>
            <label class="form-label form-label-custom" for="pendidikan_terakhir">Pendidikan Terakhir</label>
            <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control form-control-custom" x-model="form.pendidikan_terakhir"
              :class="{ 'is-invalid': errors.pendidikan_terakhir }">
              <option value="" disabled>Pilih pendidikan</option>
              <option value="SD">SD</option>
              <option value="SMP">SMP</option>
              <option value="SMA">SMA</option>
              <option value="D1">D1</option>
              <option value="D2">D2</option>
              <option value="D3">D3</option>
              <option value="S1">S1</option>
              <option value="S2">S2</option>
              <option value="S3">S3</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.pendidikan_terakhir }" x-text="errors.pendidikan_terakhir"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="tahun_lulus">Tahun Lulus</label>
            <select id="tahun_lulus" name="tahun_lulus" class="form-control form-control-custom" x-model="form.tahun_lulus"
              :class="{ 'is-invalid': errors.tahun_lulus }">
              <option value="" disabled>Pilih tahun</option>
              <template x-for="tahun in tahunOptions" :key="tahun">
                <option :value="tahun" x-text="tahun"></option>
              </template>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tahun_lulus }" x-text="errors.tahun_lulus"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="nama_institusi">Nama Institusi</label>
            <input type="text" id="nama_institusi" name="nama_institusi" class="form-control form-control-custom"
              x-model="form.nama_institusi" placeholder="Nama sekolah/universitas"
              :class="{ 'is-invalid': errors.nama_institusi }" />
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.nama_institusi }" x-text="errors.nama_institusi"></div>
          </div>
          <div>
            <label class="form-label form-label-custom" for="jurusan">Jurusan</label>
            <input type="text" id="jurusan" name="jurusan" class="form-control form-control-custom"
              x-model="form.jurusan" placeholder="Jurusan (jika ada)" />
          </div>
        </div>

        <div class="field-group mt-3">
          <div>
            <label class="form-label form-label-custom" for="status_pekerjaan">Status Pekerjaan</label>
            <select id="status_pekerjaan" name="status_pekerjaan" class="form-control form-control-custom" x-model="form.status_pekerjaan"
              :class="{ 'is-invalid': errors.status_pekerjaan }">
              <option value="" disabled>Pilih status</option>
              <option value="Bekerja">Bekerja</option>
              <option value="Belum Bekerja">Belum Bekerja</option>
              <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
              <option value="Wirausaha">Wirausaha</option>
            </select>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.status_pekerjaan }" x-text="errors.status_pekerjaan"></div>
          </div>
          <div x-show="form.status_pekerjaan === 'Bekerja'">
            <label class="form-label form-label-custom" for="nama_perusahaan">Nama Perusahaan</label>
            <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control form-control-custom"
              x-model="form.nama_perusahaan" placeholder="Nama perusahaan / instansi" />
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="prevTab()">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
          </button>
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="nextTab()">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 4: MINAT PELATIHAN -->
      <!-- ============================================================ -->
      <div x-show="currentTab === 4" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-heart me-2" style="color: #6366f1;"></i>Minat Pelatihan
        </h5>
        <div class="field-group">
          <div class="field-full">
            <label class="form-label form-label-custom">Bidang Minat</label>
            <div class="row g-2 mt-1">
              <template x-for="(bidang, index) in bidangMinatList" :key="index">
                <div class="col-6 col-md-4">
                  <div class="form-check">
                    <input class="form-check-input form-check-input-custom" type="checkbox" name="bidang_minat[]"
                      :id="'bidang_' + index" :value="bidang" x-model="form.bidang_minat" />
                    <label class="form-check-label text-white-50-custom small" :for="'bidang_' + index" x-text="bidang"></label>
                  </div>
                </div>
              </template>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.bidang_minat }" x-text="errors.bidang_minat"></div>
          </div>
          <div class="field-full">
            <label class="form-label form-label-custom" for="tujuan_pelatihan">Tujuan Pelatihan</label>
            <textarea id="tujuan_pelatihan" name="tujuan_pelatihan" class="form-control form-control-custom" rows="3"
              x-model="form.tujuan_pelatihan" placeholder="Apa yang ingin Anda capai dari pelatihan ini?"
              :class="{ 'is-invalid': errors.tujuan_pelatihan }"></textarea>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.tujuan_pelatihan }" x-text="errors.tujuan_pelatihan"></div>
          </div>
        </div>

        <div class="field-group mt-3">
          <div>
            <label class="form-label form-label-custom">Preferensi Jadwal</label>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jadwal_pagi" name="preferensi_jadwal" value="Pagi" x-model="form.preferensi_jadwal" />
                <label class="form-check-label text-white-50-custom small" for="jadwal_pagi">Pagi</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jadwal_siang" name="preferensi_jadwal" value="Siang" x-model="form.preferensi_jadwal" />
                <label class="form-check-label text-white-50-custom small" for="jadwal_siang">Siang</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jadwal_sore" name="preferensi_jadwal" value="Sore" x-model="form.preferensi_jadwal" />
                <label class="form-check-label text-white-50-custom small" for="jadwal_sore">Sore</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="jadwal_weekend" name="preferensi_jadwal" value="Sabtu-Minggu" x-model="form.preferensi_jadwal" />
                <label class="form-check-label text-white-50-custom small" for="jadwal_weekend">Sabtu-Minggu</label>
              </div>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.preferensi_jadwal }" x-text="errors.preferensi_jadwal"></div>
          </div>
          <div>
            <label class="form-label form-label-custom">Preferensi Mode</label>
            <div class="d-flex flex-wrap gap-3 mt-1">
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="mode_online" name="preferensi_mode" value="Online" x-model="form.preferensi_mode" />
                <label class="form-check-label text-white-50-custom small" for="mode_online">Online</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="mode_offline" name="preferensi_mode" value="Offline" x-model="form.preferensi_mode" />
                <label class="form-check-label text-white-50-custom small" for="mode_offline">Offline</label>
              </div>
              <div class="form-check">
                <input class="form-check-input form-check-input-custom" type="radio" id="mode_hybrid" name="preferensi_mode" value="Hybrid" x-model="form.preferensi_mode" />
                <label class="form-check-label text-white-50-custom small" for="mode_hybrid">Hybrid</label>
              </div>
            </div>
            <div class="invalid-feedback-custom" :class="{ 'd-block': errors.preferensi_mode }" x-text="errors.preferensi_mode"></div>
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="prevTab()">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
          </button>
          <button type="button" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="nextTab()">
            Selanjutnya <i class="icon-base ti tabler-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- ============================================================ -->
      <!-- TAB 5: DOKUMEN & KONFIRMASI -->
      <!-- ============================================================ -->
      <div x-show="currentTab === 5" class="tab-pane-step">
        <h5 class="text-white-70-custom fw-semibold mb-3" style="font-size: 0.95rem;">
          <i class="icon-base ti tabler-file-check me-2" style="color: #6366f1;"></i>Dokumen &amp; Konfirmasi
        </h5>

        <!-- Upload Foto Profil -->
        <div class="mb-3">
          <label class="form-label form-label-custom">Upload Foto Profil</label>
          <div class="file-upload-area" :class="{ 'has-file': form.foto_profil }"
            @click="document.getElementById('foto_profil_input').click()">
            <template x-if="!form.foto_profil">
              <div>
                <i class="icon-base ti tabler-photo-plus fs-2 mb-1 d-block" style="color: rgba(255,255,255,0.3);"></i>
                <span class="text-white-50-custom small">Klik untuk upload foto profil</span>
                <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: JPG, PNG. Maks 2MB</p>
              </div>
            </template>
            <template x-if="form.foto_profil">
              <div>
                <i class="icon-base ti tabler-file-check fs-2 mb-1 d-block" style="color: #10b981;"></i>
                <span class="text-white-70-custom small fw-semibold" x-text="form.foto_profil.name"></span>
                <button type="button" class="d-block mx-auto mt-1 btn btn-sm fw-semibold"
                  style="background: none; border: none; color: #f87171; font-size: 11px;"
                  @click.stop="form.foto_profil = null; $el.closest('.file-upload-area').querySelector('input[type=file]').value = ''">
                  <i class="icon-base ti tabler-trash me-1"></i>Hapus
                </button>
              </div>
            </template>
          </div>
          <input type="file" id="foto_profil_input" name="foto_profil" accept="image/*" class="d-none"
            @change="handleFileUpload('foto_profil', $event)"
            :class="{ 'is-invalid': errors.foto_profil }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.foto_profil }" x-text="errors.foto_profil"></div>
        </div>

        <!-- Upload Scan KTP -->
        <div class="mb-3">
          <label class="form-label form-label-custom">Upload Scan KTP</label>
          <div class="file-upload-area" :class="{ 'has-file': form.scan_ktp }"
            @click="document.getElementById('scan_ktp_input').click()">
            <template x-if="!form.scan_ktp">
              <div>
                <i class="icon-base ti tabler-id fs-2 mb-1 d-block" style="color: rgba(255,255,255,0.3);"></i>
                <span class="text-white-50-custom small">Klik untuk upload scan KTP</span>
                <p class="text-white-50-custom mt-1 mb-0" style="font-size: 11px;">Format: PDF, JPG, PNG. Maks 2MB</p>
              </div>
            </template>
            <template x-if="form.scan_ktp">
              <div>
                <i class="icon-base ti tabler-file-check fs-2 mb-1 d-block" style="color: #10b981;"></i>
                <span class="text-white-70-custom small fw-semibold" x-text="form.scan_ktp.name"></span>
                <button type="button" class="d-block mx-auto mt-1 btn btn-sm fw-semibold"
                  style="background: none; border: none; color: #f87171; font-size: 11px;"
                  @click.stop="form.scan_ktp = null; $el.closest('.file-upload-area').querySelector('input[type=file]').value = ''">
                  <i class="icon-base ti tabler-trash me-1"></i>Hapus
                </button>
              </div>
            </template>
          </div>
          <input type="file" id="scan_ktp_input" name="scan_ktp" accept=".pdf,image/*" class="d-none"
            @change="handleFileUpload('scan_ktp', $event)"
            :class="{ 'is-invalid': errors.scan_ktp }" />
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.scan_ktp }" x-text="errors.scan_ktp"></div>
        </div>

        <!-- Review Ringkasan Data -->
        <div class="mt-4">
          <h6 class="text-white-70-custom fw-semibold mb-2" style="font-size: 0.85rem;">
            <i class="icon-base ti tabler-eye me-1"></i>Review Data Anda
          </h6>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-user" style="font-size: 0.9rem; color: #6366f1;"></i> Data Diri
            </div>
            <div class="review-item">
              <span class="review-item-label">Nama Lengkap</span>
              <span class="review-item-value" x-text="form.nama_lengkap || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tempat, Tgl Lahir</span>
              <span class="review-item-value" x-text="(form.tempat_lahir || '-') + ', ' + (form.tanggal_lahir || '-')"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jenis Kelamin</span>
              <span class="review-item-value" x-text="form.jenis_kelamin || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Agama</span>
              <span class="review-item-value" x-text="form.agama || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">NIK</span>
              <span class="review-item-value" x-text="form.nik || '-'"></span>
            </div>
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-map-pin" style="font-size: 0.9rem; color: #6366f1;"></i> Alamat &amp; Kontak
            </div>
            <div class="review-item">
              <span class="review-item-label">Alamat</span>
              <span class="review-item-value" x-text="form.alamat || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Provinsi</span>
              <span class="review-item-value" x-text="form.provinsi || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Kota/Kec/Pos</span>
              <span class="review-item-value" x-text="(form.kota || '-') + ', ' + (form.kecamatan || '-') + ', ' + (form.kode_pos || '-')"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">HP Alternatif</span>
              <span class="review-item-value" x-text="form.no_hp_alternatif || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Email Alternatif</span>
              <span class="review-item-value" x-text="form.email_alternatif || '-'"></span>
            </div>
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-school" style="font-size: 0.9rem; color: #6366f1;"></i> Pendidikan &amp; Pekerjaan
            </div>
            <div class="review-item">
              <span class="review-item-label">Pendidikan Terakhir</span>
              <span class="review-item-value" x-text="form.pendidikan_terakhir || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Institusi</span>
              <span class="review-item-value" x-text="form.nama_institusi || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jurusan</span>
              <span class="review-item-value" x-text="form.jurusan || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tahun Lulus</span>
              <span class="review-item-value" x-text="form.tahun_lulus || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Status Pekerjaan</span>
              <span class="review-item-value" x-text="form.status_pekerjaan || '-'"></span>
            </div>
            <div class="review-item" x-show="form.status_pekerjaan === 'Bekerja'">
              <span class="review-item-label">Perusahaan</span>
              <span class="review-item-value" x-text="form.nama_perusahaan || '-'"></span>
            </div>
          </div>

          <div class="review-section">
            <div class="review-section-title">
              <i class="icon-base ti tabler-heart" style="font-size: 0.9rem; color: #6366f1;"></i> Minat Pelatihan
            </div>
            <div class="review-item">
              <span class="review-item-label">Bidang Minat</span>
              <span class="review-item-value" x-text="form.bidang_minat.length ? form.bidang_minat.join(', ') : '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Tujuan</span>
              <span class="review-item-value" x-text="form.tujuan_pelatihan || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Jadwal</span>
              <span class="review-item-value" x-text="form.preferensi_jadwal || '-'"></span>
            </div>
            <div class="review-item">
              <span class="review-item-label">Mode</span>
              <span class="review-item-value" x-text="form.preferensi_mode || '-'"></span>
            </div>
          </div>
        </div>

        <!-- Checklist Konfirmasi -->
        <div class="mt-3 mb-3">
          <div class="form-check">
            <input class="form-check-input form-check-input-custom" type="checkbox" id="konfirmasi_data" name="konfirmasi" value="1" x-model="form.konfirmasi" />
            <label class="form-check-label text-white-50-custom small" for="konfirmasi_data">
              Saya menyatakan bahwa data yang diisi adalah benar
            </label>
          </div>
          <div class="invalid-feedback-custom" :class="{ 'd-block': errors.konfirmasi }" x-text="errors.konfirmasi"></div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-glow-outline fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" @click="prevTab()">
            <i class="icon-base ti tabler-arrow-left me-1"></i> Sebelumnya
          </button>
          <button type="submit" class="btn btn-glow fw-semibold py-2 px-4" style="border-radius: 5px; font-size: 13px;" :disabled="submitting">
            <template x-if="!submitting">
              <span><i class="icon-base ti tabler-send me-1"></i> Submit</span>
            </template>
            <template x-if="submitting">
              <span><span class="spinner-border spinner-border-sm me-1" style="width:14px;height:14px;border-width:2px;"></span> Mengirim...</span>
            </template>
          </button>
        </div>
      </div>

    </form>

    <!-- Divider -->
    <div class="d-flex align-items-center gap-3 my-3">
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
      <x-brand-logo size="sm" class="text-white-50-custom" />
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
    </div>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection

@section('page-script')
<script>
  document.addEventListener('alpine:init', function() {
    Alpine.data('multiStepForm', function() {
      return {
        currentTab: 1,
        steps: [
          { id: 1, label: 'Data Diri' },
          { id: 2, label: 'Alamat & Kontak' },
          { id: 3, label: 'Pendidikan' },
          { id: 4, label: 'Minat' },
          { id: 5, label: 'Dokumen' },
        ],
        submitting: false,
        errors: {},
        bidangMinatList: [
          'Teknologi Informasi',
          'Desain Grafis',
          'Bisnis & Marketing',
          'Bahasa',
          'Kesehatan',
          'Pertanian',
          'Otomotif',
          'Lainnya'
        ],
        tahunOptions: (function() {
          var years = [];
          for (var y = 2026; y >= 2010; y--) { years.push(y); }
          return years;
        })(),
        form: {
          nama_lengkap: '',
          tempat_lahir: '',
          tanggal_lahir: '',
          jenis_kelamin: '',
          agama: '',
          nik: '',
          alamat: '',
          provinsi: '',
          kota: '',
          kecamatan: '',
          kode_pos: '',
          no_hp_alternatif: '',
          email_alternatif: '',
          pendidikan_terakhir: '',
          nama_institusi: '',
          jurusan: '',
          tahun_lulus: '',
          status_pekerjaan: '',
          nama_perusahaan: '',
          bidang_minat: [],
          tujuan_pelatihan: '',
          preferensi_jadwal: '',
          preferensi_mode: '',
          foto_profil: null,
          scan_ktp: null,
          konfirmasi: false,
        },

        clearErrors() {
          this.errors = {};
        },

        goToTab(tabId) {
          if (tabId === this.currentTab) return;
          if (tabId > this.currentTab) {
            for (var i = this.currentTab; i < tabId; i++) {
              if (!this.validateTab(i)) return;
            }
          }
          this.currentTab = tabId;
          this.clearErrors();
        },

        validateTab(tabNumber) {
          this.clearErrors();
          var errs = {};
          var valid = true;

          if (tabNumber === 1) {
            if (!this.form.nama_lengkap.trim()) { errs.nama_lengkap = 'Nama lengkap wajib diisi'; valid = false; }
            if (!this.form.tempat_lahir.trim()) { errs.tempat_lahir = 'Tempat lahir wajib diisi'; valid = false; }
            if (!this.form.tanggal_lahir) { errs.tanggal_lahir = 'Tanggal lahir wajib diisi'; valid = false; }
            if (!this.form.jenis_kelamin) { errs.jenis_kelamin = 'Pilih jenis kelamin'; valid = false; }
            if (!this.form.agama) { errs.agama = 'Pilih agama'; valid = false; }
            if (!this.form.nik.trim() || this.form.nik.length < 15 || this.form.nik.length > 16) {
              errs.nik = 'NIK harus 15-16 digit angka'; valid = false;
            }
          }

          if (tabNumber === 2) {
            if (!this.form.alamat.trim()) { errs.alamat = 'Alamat wajib diisi'; valid = false; }
            if (!this.form.provinsi) { errs.provinsi = 'Pilih provinsi'; valid = false; }
            if (!this.form.kota.trim()) { errs.kota = 'Kota wajib diisi'; valid = false; }
            if (!this.form.kecamatan.trim()) { errs.kecamatan = 'Kecamatan wajib diisi'; valid = false; }
            if (!this.form.kode_pos.trim()) { errs.kode_pos = 'Kode pos wajib diisi'; valid = false; }
          }

          if (tabNumber === 3) {
            if (!this.form.pendidikan_terakhir) { errs.pendidikan_terakhir = 'Pilih pendidikan terakhir'; valid = false; }
            if (!this.form.nama_institusi.trim()) { errs.nama_institusi = 'Nama institusi wajib diisi'; valid = false; }
            if (!this.form.tahun_lulus) { errs.tahun_lulus = 'Pilih tahun lulus'; valid = false; }
            if (!this.form.status_pekerjaan) { errs.status_pekerjaan = 'Pilih status pekerjaan'; valid = false; }
          }

          if (tabNumber === 4) {
            if (!this.form.bidang_minat.length) { errs.bidang_minat = 'Pilih minimal 1 bidang minat'; valid = false; }
            if (!this.form.tujuan_pelatihan.trim()) { errs.tujuan_pelatihan = 'Tujuan pelatihan wajib diisi'; valid = false; }
            if (!this.form.preferensi_jadwal) { errs.preferensi_jadwal = 'Pilih preferensi jadwal'; valid = false; }
            if (!this.form.preferensi_mode) { errs.preferensi_mode = 'Pilih preferensi mode'; valid = false; }
          }

          if (tabNumber === 5) {
            if (!this.form.foto_profil) { errs.foto_profil = 'Upload foto profil'; valid = false; }
            if (!this.form.scan_ktp) { errs.scan_ktp = 'Upload scan KTP'; valid = false; }
            if (!this.form.konfirmasi) { errs.konfirmasi = 'Centang pernyataan data benar'; valid = false; }
          }

          this.errors = errs;
          return valid;
        },

        nextTab() {
          if (this.currentTab >= 5) return;
          if (this.validateTab(this.currentTab)) {
            this.currentTab++;
            this.clearErrors();
          }
        },

        prevTab() {
          if (this.currentTab <= 1) return;
          this.currentTab--;
          this.clearErrors();
        },

        handleFileUpload(field, event) {
          var file = event.target.files[0];
          if (file) {
            this.form[field] = file;
          }
          var errs = this.errors;
          delete errs[field];
          this.errors = errs;
        },

        submitForm() {
          if (!this.validateTab(5)) return;
          this.submitting = true;
          document.getElementById('formPeserta').submit();
        },
      };
    });
  });
</script>
@endsection

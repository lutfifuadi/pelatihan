@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Tambah Pelatihan')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

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

  /* --- LAYOUT OVERRIDES FOR LANDING PAGE ALIGNMENT --- */
  html,
  body,
  .layout-page,
  .content-wrapper,
  .layout-wrapper,
  .layout-container {
    background-color: #0b0f19 !important;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  .content-wrapper > .container-xxl {
    max-width: 100% !important;
    padding: 0 !important;
  }

  /* Sidebar styling */
  .layout-menu,
  #layout-menu {
    background-color: #0b0f19 !important;
    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
  }
  .layout-menu .app-brand {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-inner {
    background-color: #0b0f19 !important;
  }
  .layout-menu .menu-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  .layout-menu .menu-item.active > .menu-link {
    color: #ffffff !important;
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
  }
  .layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }
  .layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.4) !important;
  }
  .layout-menu .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  .layout-menu .menu-inner-shadow {
    background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important;
  }

  /* Top Navbar styling */
  .layout-navbar,
  #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached {
    background: rgba(15, 23, 42, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    margin-top: 12px !important;
  }
  #layout-navbar .nav-link {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .nav-link:hover {
    color: #ffffff !important;
  }

  /* Dynamic Floating Orbs */
  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out;
    z-index: 0;
  }
  .orb-1 {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
    top: -10%;
    left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 5%;
    right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
    top: 35%;
    left: 25%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    z-index: 1;
  }

  .stat-icon-box {
    width: 52px;
    height: 52px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    flex-shrink: 0;
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }

  /* Form Overrides */
  .form-control, .form-select, textarea {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control:focus, .form-select:focus, textarea:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control::placeholder, textarea::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .form-control.is-invalid, .form-select.is-invalid, textarea.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important;
  }
  .form-label {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 6px;
  }
  .form-select option {
    background-color: #0f172a !important;
    color: #ffffff !important;
  }

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

  /* Select2 Multiple Custom Styling */
  .select2-container--default .select2-selection--multiple {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    border-radius: 5px !important;
    min-height: 42px !important;
    display: flex;
    align-items: center;
    padding: 2px 8px !important;
    transition: all 0.3s ease !important;
  }
  .select2-container--default.select2-container--focus .select2-selection--multiple,
  .select2-container--default.select2-container--open .select2-selection--multiple {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    color: #ffffff !important;
    padding: 0 !important;
    font-size: 14px !important;
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    width: 100%;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: linear-gradient(135deg, #6366f1, #7c3aed) !important;
    border: none !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 4px 10px !important;
    margin: 4px 2px !important;
    font-size: 13px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    position: static !important;
    float: none !important;
    color: rgba(255, 255, 255, 0.7) !important;
    border: none !important;
    background: transparent !important;
    padding: 0 4px !important;
    margin: 0 !important;
    font-weight: bold;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: inline-block !important;
  }
  .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffffff !important;
    background-color: transparent !important;
    transform: scale(1.15);
  }
  .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
    color: #ffffff !important;
    margin: 0 !important;
    height: 32px !important;
    font-size: 14px !important;
    background: transparent !important;
    border: none !important;
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

  .form-check-input {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-radius: 3px !important;
  }
  .form-check-input:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
  }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  .btn-secondary-custom {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-secondary-custom:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }

  /* Autofill Overrides */
  .form-control:-webkit-autofill,
  .form-control:-webkit-autofill:hover,
  .form-control:-webkit-autofill:focus,
  .form-control:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
  }
</style>
@endsection

@section('content')
  <!-- Floating Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Content Wrapper -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    <!-- Title Section -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary">
          <i class="icon-base ti tabler-plus fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Tambah Pelatihan</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Buat program pelatihan baru untuk peserta
          </p>
        </div>
      </div>
    </div>

    <!-- Form Card -->
    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.pelatihan.store') }}" method="POST">
          @csrf

          <!-- Nama Pelatihan -->
          <div class="mb-4">
            <label for="nama" class="form-label">Nama Pelatihan <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror"
              id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama pelatihan" required>
            @error('nama')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Asal Dinas -->
          <div class="mb-4">
            <label for="dinas_id" class="form-label">Asal Dinas <span class="text-danger">*</span></label>
            <select class="select2 form-select @error('dinas_id') is-invalid @enderror"
              id="dinas_id" name="dinas_id" data-placeholder="-- Pilih Dinas --" required>
              <option value=""></option>
              @foreach($dinas as $d)
                <option value="{{ $d->id }}" {{ old('dinas_id') == $d->id ? 'selected' : '' }}>
                  {{ $d->nama_dinas }}
                </option>
              @endforeach
            </select>
            @error('dinas_id')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Kecamatan Sasaran -->
          <div class="mb-4">
            <label for="kecamatan_ids" class="form-label">Kecamatan Sasaran</label>
            <select class="select2 form-select @error('kecamatan_ids') is-invalid @enderror"
              id="kecamatan_ids" name="kecamatan_ids[]" data-placeholder="-- Pilih Kecamatan --" multiple>
              @foreach($kecamatans as $kec)
                <option value="{{ $kec->id }}" {{ in_array($kec->id, old('kecamatan_ids', [])) ? 'selected' : '' }}>
                  {{ $kec->name }}
                </option>
              @endforeach
            </select>
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
              <i class="icon-base ti tabler-info-circle me-1"></i>Kosongkan jika pelatihan berlaku untuk semua kecamatan
            </small>
            @error('kecamatan_ids')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Batch -->
          <div class="mb-4">
            <label for="batch" class="form-label">Batch Pelatihan <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('batch') is-invalid @enderror"
              id="batch" name="batch" value="{{ old('batch') }}" placeholder="Contoh: BATCH 1" required>
            @error('batch')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Deskripsi -->
          <div class="mb-4">
            <label for="deskripsi" class="form-label">Deskripsi Program</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
              id="deskripsi" name="deskripsi" rows="3" placeholder="Masukkan deskripsi pelatihan">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <!-- Batas Pendaftaran & Kuota -->
          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="batas_pendaftaran" class="form-label">Batas Pendaftaran</label>
              <input type="date" class="form-control @error('batas_pendaftaran') is-invalid @enderror"
                id="batas_pendaftaran" name="batas_pendaftaran" value="{{ old('batas_pendaftaran') }}">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Peserta tidak bisa mendaftar setelah tanggal ini
              </small>
              @error('batas_pendaftaran')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="kuota" class="form-label">Kuota Peserta</label>
              <input type="number" class="form-control @error('kuota') is-invalid @enderror"
                id="kuota" name="kuota" value="{{ old('kuota') }}" placeholder="Masukkan kuota peserta" min="0">
              @error('kuota')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Tanggal Mulai & Selesai -->
          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
              <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
              @error('tanggal_mulai')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
              <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
              @error('tanggal_selesai')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Status Aktif -->
          <div class="mb-4">
            <div class="form-check d-flex align-items-center gap-2">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror"
                id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
              <label class="form-check-label text-white-50 ms-1 mb-0" for="is_active" style="cursor: pointer; font-size: 13px;">Status Pelatihan Aktif</label>
              @error('is_active')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Buttons -->
          <div class="d-flex justify-content-end gap-3 mt-5">
            <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-secondary-custom px-4 py-2">
              Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2">
              <i class="icon-base ti tabler-device-floppy me-1"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

@section('vendor-script')
@vite(['resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
  function initSelect2() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
      jQuery('.select2').each(function () {
        var $this = jQuery(this);
        if (!$this.parent().hasClass('position-relative')) {
          $this.wrap('<div class="position-relative"></div>');
        }
        $this.select2({
          dropdownParent: $this.parent(),
          allowClear: true
        });
      });
    } else {
      setTimeout(initSelect2, 50);
    }
  }
  initSelect2();
});
</script>
@endsection

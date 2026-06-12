@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Branding Aplikasi')

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
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }

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
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
  }
  .glass-card-premium:hover {
    transform: translateY(-2px) !important;
    border-color: rgba(99, 102, 241, 0.2) !important;
  }

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
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box" style="background: rgba(255, 193, 7, 0.12); color: #ffc107;">
            <i class="icon-base ti tabler-brand-gravatar fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Branding Aplikasi</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Kelola tampilan brand dan identitas aplikasi
            </p>
          </div>
        </div>
      </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.settings.branding.update') }}" method="POST">
          @csrf

          <div class="mb-4">
            <label for="brand_name" class="form-label">Nama Brand <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('brand_name') is-invalid @enderror"
              id="brand_name" name="brand_name"
              value="{{ old('brand_name', $settings['brand_name']->value ?? 'SABA Kreatif') }}"
              placeholder="SABA Kreatif" required>
            @error('brand_name')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
              Nama brand akan ditampilkan di logo aplikasi (contoh: SABA Kreatif)
            </small>
          </div>

          {{-- Ukuran Logo --}}
          <div class="mb-3">
            <label for="brand_logo_size" class="form-label">Ukuran Logo <span class="text-danger">*</span></label>
            <select class="form-select @error('brand_logo_size') is-invalid @enderror"
              id="brand_logo_size" name="brand_logo_size" required>
              @php
                $currentSize = old('brand_logo_size', $settings['brand_logo_size']->value ?? 'md');
                $sizeOptions = ['sm' => 'Kecil (sm)', 'md' => 'Sedang (md)', 'lg' => 'Besar (lg)', 'xl' => 'Extra Besar (xl)'];
              @endphp
              @foreach($sizeOptions as $val => $label)
                <option value="{{ $val }}" {{ $currentSize == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            @error('brand_logo_size')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Pilih ukuran tampilan logo brand di halaman publik</small>
          </div>

          <hr class="my-4">
          <h6 class="mb-3">Identitas Institusi</h6>

          <div class="row">
              <div class="col-md-6 mb-3">
                  <label for="institution_name" class="form-label">Nama Institusi <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('institution_name') is-invalid @enderror"
                      id="institution_name" name="institution_name"
                      value="{{ old('institution_name', $settings['institution_name']->value ?? 'MAN SABA') }}" required>
                  @error('institution_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              <div class="col-md-6 mb-3">
                  <label for="institution_phone" class="form-label">Nomor Telepon</label>
                  <input type="text" class="form-control @error('institution_phone') is-invalid @enderror"
                      id="institution_phone" name="institution_phone"
                      value="{{ old('institution_phone', $settings['institution_phone']->value ?? '+62 812-3456-7890') }}">
                  @error('institution_phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
          </div>

          <div class="mb-3">
              <label for="institution_email" class="form-label">Email Institusi</label>
              <input type="email" class="form-control @error('institution_email') is-invalid @enderror"
                  id="institution_email" name="institution_email"
                  value="{{ old('institution_email', $settings['institution_email']->value ?? 'admin@sabakreatif.com') }}">
              @error('institution_email')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>

          <div class="mb-3">
              <label for="institution_address" class="form-label">Alamat Institusi</label>
              <textarea class="form-control @error('institution_address') is-invalid @enderror"
                  id="institution_address" name="institution_address" rows="2">{{ old('institution_address', $settings['institution_address']->value ?? 'MAN SABA, Gedung Pusat Pembelajaran Kreatif') }}</textarea>
              @error('institution_address')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>

          <div class="mb-3">
              <label for="institution_description" class="form-label">Deskripsi Institusi</label>
              <textarea class="form-control @error('institution_description') is-invalid @enderror"
                  id="institution_description" name="institution_description" rows="3">{{ old('institution_description', $settings['institution_description']->value ?? '') }}</textarea>
              <small class="text-muted">Deskripsi singkat yang tampil di footer halaman beranda</small>
              @error('institution_description')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>

          {{-- Preview Logo --}}
          <div class="mb-4 p-3 rounded" style="max-width: 300px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08);">
            <p class="text-body-premium small mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">
              <i class="icon-base ti tabler-eye me-1"></i>Pratinjau Logo
            </p>
            <div class="d-flex align-items-center gap-2">
              @php
                $previewSize = old('brand_logo_size', $settings['brand_logo_size']->value ?? 'md');
              @endphp
              <x-brand-logo size="{{ $previewSize }}" />
            </div>
            <small class="text-white-50 mt-2 d-block">Ukuran: {{ $previewSize }}</small>
          </div>

          <div class="mb-4">
            <label for="footer_copyright" class="form-label">Footer Copyright</label>
            <input type="text" class="form-control @error('footer_copyright') is-invalid @enderror"
              id="footer_copyright" name="footer_copyright"
              value="{{ old('footer_copyright', $settings['footer_copyright']->value ?? 'Pelatihan Ekonomi Kreatif — MAN SABA') }}"
              placeholder="Pelatihan Ekonomi Kreatif — MAN SABA">
            @error('footer_copyright')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-lock me-2"></i>Penguncian Wilayah
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Pengaturan ini akan mengunci kota dan provinsi pada form pendaftaran peserta.
          </p>

          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="lock_kota" class="form-label">Kota/Kabupaten</label>
              <input type="text" class="form-control @error('lock_kota') is-invalid @enderror"
                id="lock_kota" name="lock_kota"
                value="{{ old('lock_kota', $settings['lock_kota']->value ?? 'BANDUNG') }}"
                placeholder="BANDUNG">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-lock me-1"></i>Pendaftar akan terkunci ke kota ini
              </small>
              @error('lock_kota')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6">
              <label for="lock_provinsi" class="form-label">Provinsi</label>
              <input type="text" class="form-control @error('lock_provinsi') is-invalid @enderror"
                id="lock_provinsi" name="lock_provinsi"
                value="{{ old('lock_provinsi', $settings['lock_provinsi']->value ?? 'Jawa Barat') }}"
                placeholder="Jawa Barat">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-lock me-1"></i>Pendaftar akan terkunci ke provinsi ini
              </small>
              @error('lock_provinsi')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Pengaturan
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

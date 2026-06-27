@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pengaturan Mode Maintenance')

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
    background: linear-gradient(135deg, #6366f1, #818cf8) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
    background: linear-gradient(135deg, #818cf8, #a5b4fc) !important;
    color: #ffffff !important;
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

  .maintenance-toggle-wrapper {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
  }
  .maintenance-toggle-wrapper .toggle-label {
    font-family: 'Outfit', sans-serif;
    font-weight: 600;
    font-size: 0.95rem;
    color: #ffffff;
  }
  .maintenance-toggle-wrapper .toggle-desc {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
  }

  /* Custom Switch Styling — konsisten dengan halaman users */
  .form-switch .form-check-input {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    height: 1.5em;
    width: 2.75em;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='rgba%28255, 255, 255, 0.6%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 5l5 5-5 5'/%3e%3c/svg%3e") !important;
    transition: background-position 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
  }
  .form-switch .form-check-input:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='%23fff' d='M13.293 6.293a1 1 0 011.414 0l.001.001a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L7 12.586l6.293-6.293z'/%3e%3c/svg%3e") !important;
  }
  .form-switch .form-check-input:focus {
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
  }

  .warning-badge-maintenance {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.12), rgba(255, 152, 0, 0.12));
    border: 1px solid rgba(255, 193, 7, 0.25);
    border-radius: 5px;
    color: #fbbf24;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 0.85rem;
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
          <div class="stat-icon-box" style="background: rgba(99, 102, 241, 0.12); color: #818cf8;">
            <i class="icon-base ti tabler-tool fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Pengaturan Mode Maintenance</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Aktifkan atau nonaktifkan mode maintenance sistem
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

    @php
      $maintenanceActive = ($settings['maintenance_mode']->value ?? '0') === '1';
    @endphp

    @if($maintenanceActive)
    <div class="warning-badge-maintenance mb-4">
      <i class="icon-base ti tabler-alert-triangle"></i>
      <span>⚠️ Maintenance sedang AKTIF — semua pengunjung non-admin melihat halaman maintenance</span>
    </div>
    @endif

      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.settings.maintenance.update') }}" method="POST">
          @csrf

          <div class="maintenance-toggle-wrapper mb-4">
            <div class="form-check form-switch mb-0" style="padding-left: 3.5em;">
              <input type="hidden" name="maintenance_mode" value="0">
              <input class="form-check-input maintenance-toggle" type="checkbox" role="switch"
                id="maintenance_mode" name="maintenance_mode" value="1"
                {{ $maintenanceActive ? 'checked' : '' }}>
            </div>
            <div>
              <div class="toggle-label">
                <i class="icon-base ti tabler-tool me-2"></i>Aktifkan Mode Maintenance
              </div>
              <div class="toggle-desc">
                Jika aktif, semua pengunjung non-admin akan melihat halaman maintenance
              </div>
            </div>
          </div>

          <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

          <h6 class="mb-4">Konten Halaman Maintenance</h6>

          <div class="mb-4">
            <label for="maintenance_title" class="form-label">Judul Halaman <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('maintenance_title') is-invalid @enderror"
              id="maintenance_title" name="maintenance_title"
              value="{{ old('maintenance_title', $settings['maintenance_title']->value ?? 'Sistem Sedang Dalam Pemeliharaan') }}"
              placeholder="Sistem Sedang Dalam Pemeliharaan" required>
            @error('maintenance_title')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
              Judul besar yang tampil di halaman maintenance
            </small>
          </div>

          <div class="mb-4">
            <label for="maintenance_message" class="form-label">Pesan Maintenance <span class="text-danger">*</span></label>
            <textarea class="form-control @error('maintenance_message') is-invalid @enderror"
              id="maintenance_message" name="maintenance_message" rows="3" required
              placeholder="Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan. Silakan kembali lagi nanti.">{{ old('maintenance_message', $settings['maintenance_message']->value ?? 'Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan. Silakan kembali lagi nanti.') }}</textarea>
            @error('maintenance_message')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
              Pesan yang menjelaskan situasi kepada pengunjung
            </small>
          </div>

          <div class="mb-4">
            <label for="maintenance_estimated_time" class="form-label">Estimasi Waktu Selesai</label>
            <input type="text" class="form-control @error('maintenance_estimated_time') is-invalid @enderror"
              id="maintenance_estimated_time" name="maintenance_estimated_time"
              value="{{ old('maintenance_estimated_time', $settings['maintenance_estimated_time']->value ?? '') }}"
              placeholder="Contoh: Pukul 16.00 WIB atau 2 Jam lagi">
            @error('maintenance_estimated_time')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
              Opsional. Jika diisi, akan ditampilkan di halaman maintenance. Biarkan kosong jika tidak ada estimasi.
            </small>
          </div>

          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Pengaturan
            </button>
          </div>
        </form>
      </div>

  </div>
@endsection

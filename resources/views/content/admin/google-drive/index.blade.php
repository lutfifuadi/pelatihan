@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Google Drive')

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
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
  }
  .stat-icon-danger {
    background: rgba(239, 68, 68, 0.12);
    color: #f87171;
  }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
  }
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
    color: #f87171;
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

  .btn-google {
    background: linear-gradient(135deg, #4285f4, #34a853) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
    transition: all 0.3s ease;
  }
  .btn-google:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(66, 133, 244, 0.5);
    background: linear-gradient(135deg, #5a95f5, #3bcc70) !important;
    color: #ffffff !important;
  }

  .btn-danger-premium {
    background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
    transition: all 0.3s ease;
  }
  .btn-danger-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
    background: linear-gradient(135deg, #f87171, #dc2626) !important;
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

  .drive-info-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 16px;
  }

  .storage-bar {
    height: 8px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    overflow: hidden;
  }
  .storage-bar-fill {
    height: 100%;
    border-radius: 20px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
    transition: width 1s ease;
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
          <div class="stat-icon-box {{ $connected ? 'stat-icon-success' : 'stat-icon-primary' }}">
            <i class="icon-base ti tabler-brand-google-drive fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Google Drive</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Integrasi penyimpanan foto peserta
            </p>
          </div>
        </div>
        <div>
          @if($connected && !$tokenExpired)
            <span class="badge-premium badge-premium-success d-flex align-items-center gap-2 px-3 py-2">
              <i class="icon-base ti tabler-check-circle fs-6"></i> Terhubung
            </span>
          @else
            <span class="badge-premium badge-premium-danger d-flex align-items-center gap-2 px-3 py-2">
              <i class="icon-base ti tabler-alert-circle fs-6"></i> Belum Terhubung
            </span>
          @endif
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($tokenExpired)
      <div class="alert alert-warning alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-alert-triangle fs-5 me-2"></i>
          <span>Token sudah kedaluwarsa. Silakan hubungkan ulang akun Google Drive Anda.</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($connected && !$tokenExpired)
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-box stat-icon-success">
                <i class="icon-base ti tabler-mail fs-4"></i>
              </div>
              <div>
                <small class="text-body-premium text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 600;">Akun Terhubung</small>
                <h5 class="fw-bold text-white mb-0">{{ $userEmail ?? 'Tidak diketahui' }}</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-box stat-icon-primary">
                <i class="icon-base ti tabler-database fs-4"></i>
              </div>
              <div>
                <small class="text-body-premium text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 600;">Penyimpanan</small>
                <h5 class="fw-bold text-white mb-0">
                  @if($storageInfo && $storageInfo['limit'] > 0)
                    {{ number_format($storageInfo['usage'] / 1073741824, 2) }} GB / {{ number_format($storageInfo['limit'] / 1073741824, 2) }} GB
                  @else
                    {{ number_format($storageInfo['usage'] / 1073741824, 2) }} GB / Tak terbatas
                  @endif
                </h5>
              </div>
            </div>
            @if($storageInfo && $storageInfo['limit'] > 0)
              <div class="storage-bar mt-2">
                <div class="storage-bar-fill" style="width: {{ min(($storageInfo['usage'] / $storageInfo['limit']) * 100, 100) }}%;"></div>
              </div>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                {{ number_format(($storageInfo['usage'] / $storageInfo['limit']) * 100, 1) }}% terpakai
              </small>
            @endif
          </div>
        </div>
      </div>

      <div class="glass-card-premium px-4 px-xl-5 py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-white mb-1">Putuskan Koneksi</h5>
            <p class="text-body-premium mb-0" style="font-size: 0.9rem;">
              Dengan memutuskan koneksi, upload foto peserta ke Google Drive akan berhenti.
            </p>
          </div>
          <form action="{{ route('admin.google-drive.revoke') }}" method="POST" onsubmit="return confirm('Yakin ingin memutuskan koneksi Google Drive?')">
            @csrf
            <button type="submit" class="btn btn-danger-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-plug-connected-x"></i> Putuskan Koneksi
            </button>
          </form>
        </div>
      </div>
    @else
      <div class="glass-card-premium px-4 px-xl-5 py-5 text-center">
        <div class="mb-4">
          <div class="stat-icon-box stat-icon-primary mx-auto" style="width: 72px; height: 72px; font-size: 2.5rem;">
            <i class="icon-base ti tabler-brand-google-drive"></i>
          </div>
        </div>
        <h4 class="fw-bold text-white mb-2">Hubungkan Google Drive</h4>
        <p class="text-body-premium mb-4" style="font-size: 0.95rem; max-width: 480px; margin-left: auto; margin-right: auto;">
          Klik tombol di bawah untuk menghubungkan akun Google Drive Anda. Kami akan mengirim foto peserta ke folder Pelatihanku.
        </p>
        <a href="{{ route('google.redirect') }}" class="btn btn-google px-5 py-2 d-inline-flex align-items-center gap-2">
          <i class="icon-base ti tabler-brand-google fs-5"></i> Hubungkan Google Drive
        </a>
      </div>
    @endif

  </div>
@endsection

@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Operasional Panitia')

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
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
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

  /* Big Buttons for Mobile Screen */
  .btn-operasional-big {
    padding: 18px 24px;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: 1.05rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  }

  .btn-scanner {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #ffffff !important;
  }
  .btn-scanner:hover {
    background: linear-gradient(135deg, #818cf8, #6366f1);
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(99, 102, 241, 0.4);
  }

  .btn-proyektor {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff !important;
  }
  .btn-proyektor:hover {
    background: linear-gradient(135deg, #34d399, #10b981);
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4);
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
          <i class="icon-base ti tabler-device-imac-cog fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">Operasional Panitia & Instruktur</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Daftar kelas pelatihan aktif hari ini untuk Scanner Presensi & Layar Proyektor
          </p>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Pelatihan Cards List -->
    <div class="row g-4">
      @forelse($pelatihans as $pelatihan)
        <div class="col-12 col-md-6 col-lg-6">
          <div class="glass-card-premium p-4">
            <h5 class="fw-bold text-white mb-2">{{ $pelatihan->nama }}</h5>
            <div class="d-flex flex-wrap gap-2 mb-4">
              <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size: 0.75rem; border-radius: 4px;">
                Dinas: {{ $pelatihan->dinas->nama_dinas ?? '-' }}
              </span>
              <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.75rem; border-radius: 4px;">
                {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d/m/Y') }}
              </span>
            </div>

            <div class="d-flex flex-column gap-3">
              <a href="{{ route('panitia.scanner', $pelatihan->id) }}" class="btn-operasional-big btn-scanner">
                <i class="icon-base ti tabler-qrcode fs-4"></i>
                Buka Scanner Panitia
              </a>
              <a href="{{ route('instruktur.monitoring', $pelatihan->id) }}" class="btn-operasional-big btn-proyektor">
                <i class="icon-base ti tabler-device-laptop fs-4"></i>
                Buka Layar Proyektor
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="glass-card-premium text-center py-5">
            <i class="icon-base ti tabler-alert-circle fs-1 mb-2 d-block text-warning"></i>
            <h5 class="text-white mb-1">Tidak ada kelas pelatihan aktif hari ini.</h5>
            <p class="text-body-premium mb-0">Silakan kembali lagi saat jadwal pelatihan sedang berjalan.</p>
          </div>
        </div>
      @endforelse
    </div>

  </div>
@endsection

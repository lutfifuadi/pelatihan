@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Admin')

{{-- DATA SUDAH DI-PASS DARI DashboardController DENGAN CACHE --}}

@section('page-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  /* Custom Leaflet Dark Mode styling */
  #map-sebaran-peserta {
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.6);
    background: #0b0f19 !important;
  }
  .leaflet-bar {
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
  }
  .leaflet-bar a {
    background-color: rgba(15, 23, 42, 0.6) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: #f8fafc !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
    transition: all 0.3s ease;
  }
  .leaflet-bar a:hover {
    background-color: rgba(99, 102, 241, 0.3) !important;
  }
  .custom-leaflet-popup .leaflet-popup-content-wrapper {
    background: rgba(15, 23, 42, 0.85) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #f8fafc !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
    border-radius: 8px !important;
    padding: 4px;
  }
  .custom-leaflet-popup .leaflet-popup-tip {
    background: rgba(15, 23, 42, 0.85) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
  }
  .custom-leaflet-popup .leaflet-container a.leaflet-popup-close-button {
    color: rgba(255, 255, 255, 0.6) !important;
    padding: 8px !important;
  }
  .custom-leaflet-popup .leaflet-container a.leaflet-popup-close-button:hover {
    color: #f87171 !important;
  }
  .custom-leaflet-tooltip {
    background: rgba(15, 23, 42, 0.9) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
    color: #fff !important;
    border-radius: 4px !important;
    font-family: 'Outfit', sans-serif;
    padding: 4px 8px !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
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

  /* --- LAYOUT OVERRIDES FOR LANDING PAGE ALIGNMENT --- */
  /* Main layouts backgrounds */
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

  /* Remove top layout blur/gradient bar that clashes with the dark premium theme */
  .layout-navbar-fixed .layout-page::before {
    display: none !important;
  }

  /* Override outer container-xxl to span edge-to-edge and remove default padding */
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
  .layout-menu .layout-menu-toggle i {
    color: rgba(255, 255, 255, 0.6) !important;
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
  #layout-navbar .dropdown-menu {
    background-color: #0b0f19 !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
  }
  #layout-navbar .dropdown-item {
    color: rgba(255, 255, 255, 0.7) !important;
  }
  #layout-navbar .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.04) !important;
    color: #ffffff !important;
  }
  #layout-navbar .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.08) !important;
  }
  #layout-navbar .text-body-secondary {
    color: rgba(255, 255, 255, 0.5) !important;
  }
  #layout-navbar h6 {
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

  .bg-dark-premium {
    background-color: #0b0f19 !important;
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
    transform: translateY(-4px) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 30px rgba(99, 102, 241, 0.15) !important;
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
    transition: all 0.3s ease;
  }
  .glass-card-premium:hover .stat-icon-box {
    transform: scale(1.08);
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #10b981;
  }
  .stat-icon-info {
    background: rgba(6, 182, 212, 0.12);
    color: #06b6d4;
  }
  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.12);
    color: #f59e0b;
  }
  .stat-icon-danger {
    background: rgba(248, 113, 113, 0.12);
    color: #f87171;
  }
  .stat-icon-secondary {
    background: rgba(148, 163, 184, 0.12);
    color: #94a3b8;
  }

  .progress-dark-premium {
    background: rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    height: 8px;
  }
  .progress-dark-premium .progress-bar {
    border-radius: 5px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
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
  .badge-premium-primary {
    background: rgba(99, 102, 241, 0.15);
    border-color: rgba(99, 102, 241, 0.3);
    color: #818cf8;
  }
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #22d3ee;
  }

  .instructor-avatar {
    width: 42px;
    height: 42px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    flex-shrink: 0;
  }

  .btn-glow-premium {
    background: linear-gradient(135deg, #ffc107, #ff9800) !important;
    border: none;
    color: #0b0f19 !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.4);
    background: linear-gradient(135deg, #ffca28, #ffa726) !important;
    color: #0b0f19 !important;
  }

  /* --- Pagination styling --- */
  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
  }
  .pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .pagination .page-item .page-link:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }

  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  .text-gradient {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
</style>
@endsection

@section('content')
  <!-- Floating Gradient Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Main Content container with z-index to sit on top of orbs -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    <!-- Welcome Card -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="row align-items-center">
        <div class="col-12">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-shield-lock fs-4"></i>
            </div>
            <div>
              <h4 class="fw-bold text-white mb-0">Dashboard Administrator</h4>
              <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
                Selamat datang kembali, <span class="text-gradient fw-extrabold">{{ auth()->user()->name }}</span> 👋
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================
         QUICK ACTIONS — Shortcut Grid
         ============================================================ -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="stat-icon-box stat-icon-primary" style="width: 40px; height: 40px; font-size: 1.2rem;">
          <i class="icon-base ti tabler-rocket"></i>
        </div>
        <h5 class="fw-bold text-white mb-0">Quick Actions</h5>
      </div>
      <div class="row g-3">
        <a href="{{ route('admin.pelatihan.create') }}" class="col-6 col-md-3 text-decoration-none">
          <div class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded text-center" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s ease; height: 100%;" onmouseover="this.style.borderColor='rgba(99,102,241,0.4)'; this.style.background='rgba(99,102,241,0.06)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)';">
            <div class="stat-icon-box stat-icon-primary" style="width: 48px; height: 48px; font-size: 1.5rem; border-radius: 5px !important;">
              <i class="icon-base ti tabler-plus"></i>
            </div>
            <div class="w-100">
              <p class="text-white fw-semibold mb-0" style="font-size: 0.82rem;">Buat Pelatihan Baru</p>
              <small class="text-body-premium d-flex align-items-center justify-content-center gap-1" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-arrow-right"></i>
              </small>
            </div>
          </div>
        </a>
        <a href="{{ route('panitia.operasional') }}" class="col-6 col-md-3 text-decoration-none">
          <div class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded text-center" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s ease; height: 100%;" onmouseover="this.style.borderColor='rgba(245,158,11,0.4)'; this.style.background='rgba(245,158,11,0.06)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)';">
            <div class="stat-icon-box stat-icon-warning" style="width: 48px; height: 48px; font-size: 1.5rem; border-radius: 5px !important;">
              <i class="icon-base ti tabler-clipboard-list"></i>
            </div>
            <div class="w-100">
              <p class="text-white fw-semibold mb-0" style="font-size: 0.82rem;">Presensi Hari Ini</p>
              <small class="text-body-premium d-flex align-items-center justify-content-center gap-1" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-arrow-right"></i>
              </small>
            </div>
          </div>
        </a>
        <a href="{{ route('admin.exports.index') }}" class="col-6 col-md-3 text-decoration-none">
          <div class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded text-center" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s ease; height: 100%;" onmouseover="this.style.borderColor='rgba(16,185,129,0.4)'; this.style.background='rgba(16,185,129,0.06)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)';">
            <div class="stat-icon-box stat-icon-success" style="width: 48px; height: 48px; font-size: 1.5rem; border-radius: 5px !important;">
              <i class="icon-base ti tabler-file-export"></i>
            </div>
            <div class="w-100">
              <p class="text-white fw-semibold mb-0" style="font-size: 0.82rem;">Export Data</p>
              <small class="text-body-premium d-flex align-items-center justify-content-center gap-1" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-arrow-right"></i>
              </small>
            </div>
          </div>
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="col-6 col-md-3 text-decoration-none">
          <div class="d-flex flex-column align-items-center justify-content-center gap-2 p-3 rounded text-center" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s ease; height: 100%;" onmouseover="this.style.borderColor='rgba(139,92,246,0.4)'; this.style.background='rgba(139,92,246,0.06)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.06)'; this.style.background='rgba(255,255,255,0.02)';">
            <div class="stat-icon-box stat-icon-primary" style="width: 48px; height: 48px; font-size: 1.5rem; border-radius: 5px !important; background: rgba(139,92,246,0.12); color: #a78bfa;">
              <i class="icon-base ti tabler-chart-bar"></i>
            </div>
            <div class="w-100">
              <p class="text-white fw-semibold mb-0" style="font-size: 0.82rem;">Laporan Bulanan</p>
              <small class="text-body-premium d-flex align-items-center justify-content-center gap-1" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-arrow-right"></i>
              </small>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- ============================================================
         STATISTICS CARDS
         ============================================================ -->
    <div class="row g-4 mb-4">
      <!-- Card 1: Total Pengguna -->
      <div class="col-lg-3 col-md-6">
        <div class="glass-card-premium px-4 py-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="stat-icon-box stat-icon-success">
              <i class="icon-base ti tabler-users"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Total Pengguna</p>
              <h3 class="fw-bold text-white mb-0" id="stat-total-pengguna">{{ $userCounts->total }}</h3>
            </div>
          </div>
          <div class="d-flex flex-column gap-1 border-top border-white border-opacity-10 pt-2" style="font-size: 0.82rem;">
            <div class="d-flex justify-content-between text-body-premium">
              <span>Peserta:</span>
              <strong class="text-white" id="stat-total-peserta">{{ $userCounts->total_peserta }}</strong>
            </div>
            <div class="d-flex justify-content-between text-body-premium">
              <span>Instruktur:</span>
              <strong class="text-white" id="stat-total-instruktur">{{ $userCounts->total_instruktur }}</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: WhatsApp Gateway Status & Konektivitas Real-time -->
      <div class="col-lg-3 col-md-6">
        <div class="glass-card-premium px-4 py-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-brand-whatsapp"></i>
            </div>
            <div class="flex-grow-1">
              <p class="text-body-premium small mb-0">WA Gateway Status</p>
              <div id="wa-status-container" class="d-flex align-items-center gap-2 mt-1">
                <div class="d-flex flex-column gap-1 flex-grow-1">
                  <div id="wa-sender-status-container" class="d-inline-flex align-items-center gap-1">
                    <span class="badge bg-secondary text-white-50 px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem;">
                      <i class="icon-base ti tabler-refresh spin-icon me-1" style="font-size: 0.75rem;"></i> Kirim: ...
                    </span>
                  </div>
                  <div id="wa-check-sender-status-container" class="d-inline-flex align-items-center gap-1">
                    <span class="badge bg-secondary text-white-50 px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem;">
                      <i class="icon-base ti tabler-refresh spin-icon me-1" style="font-size: 0.75rem;"></i> Cek: ...
                    </span>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-icon text-white p-1 border-0 bg-transparent" onclick="checkWaConnectionStatus(false)" title="Refresh Status" style="width: 26px; height: 26px; flex-shrink: 0;">
                  <i class="icon-base ti tabler-refresh" style="font-size: 1rem;"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="d-flex flex-column gap-1 border-top border-white border-opacity-10 pt-2" style="font-size: 0.82rem;">
            <div class="d-flex justify-content-between text-body-premium">
              <span>Terkirim (Hari Ini):</span>
              <strong class="text-success" id="stat-wa-sent-today">{{ $waSentToday }}</strong>
            </div>
            <div class="d-flex justify-content-between text-body-premium">
              <span>Total Gagal:</span>
              <strong class="text-danger" id="stat-wa-failed">{{ $waFailed }}</strong>
            </div>
            <div class="d-flex justify-content-between text-body-premium">
              <span>Total Pending:</span>
              <strong class="text-warning" id="stat-notif-pending">{{ $notifPending }}</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Presensi Hari Ini -->
      <div class="col-lg-3 col-md-6">
        <div class="glass-card-premium px-4 py-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon-box stat-icon-warning">
              <i class="icon-base ti tabler-calendar-stats"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Presensi Hari Ini</p>
              <h3 class="fw-bold text-white mb-0">{{ $pelatihanHariIniCount }} <span style="font-size: 0.9rem; font-weight: normal;" class="text-body-premium">Kelas</span></h3>
            </div>
          </div>
          <div class="d-flex flex-column gap-2 border-top border-white border-opacity-10 pt-2" style="font-size: 0.82rem;">
            <div class="d-flex justify-content-between text-body-premium">
              <span>Hadir / Confirmed:</span>
              <strong class="text-white">{{ $totalHadirHariIni }} / {{ $totalConfirmedHariIni }}</strong>
            </div>
            <div>
              <div class="d-flex justify-content-between text-body-premium mb-1" style="font-size: 0.75rem;">
                <span>Persentase Kehadiran</span>
                <span class="fw-bold text-warning">{{ $persentaseKehadiranHariIni }}%</span>
              </div>
              <div class="progress progress-dark-premium" style="height: 6px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persentaseKehadiranHariIni }}%" aria-valuenow="{{ $persentaseKehadiranHariIni }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4: Status Program -->
      <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.pelatihan.index') }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="stat-icon-box stat-icon-info">
                <i class="icon-base ti tabler-books"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Status Program</p>
                <h3 class="fw-bold text-white mb-0" id="stat-total-pelatihan">{{ $totalPelatihan }}</h3>
              </div>
            </div>
            <div class="d-flex flex-column gap-1 border-top border-white border-opacity-10 pt-2" style="font-size: 0.82rem;">
              <div class="d-flex justify-content-between text-body-premium">
                <span>Program Aktif:</span>
                <strong class="text-info" id="badge-active-pelatihan-text">{{ $activePelatihanCount }} Aktif</strong>
              </div>
              <div class="d-flex justify-content-between text-body-premium">
                <span>Kecamatan Terdaftar:</span>
                <strong class="text-white" id="stat-total-kecamatan">{{ $totalKecamatan }}</strong>
              </div>
              <div class="d-flex justify-content-between text-body-premium">
                <span>Template WA Aktif:</span>
                <strong class="text-white" id="stat-active-templates">{{ $activeTemplates }}</strong>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- ============================================================
         SECTION: MINI CHART TREN PENDAFTARAN + REGISTRATION FUNNEL
         ============================================================ -->
    @php
      // Siapkan data JS dari $trendPendaftaran
      $trendLabels = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));
      $trendData = $trendLabels->map(fn($date) => $trendPendaftaran[$date]->total ?? 0);
      $trendMax = max($trendData->max(), 1);
      $trendChange = $trendData->count() >= 2
        ? $trendData[count($trendData)-1] - $trendData[count($trendData)-2]
        : 0;
    @endphp

    <div class="glass-card-premium px-4 py-3 mb-3">
      <div class="d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-trending-up text-gradient"></i>
          Pendaftaran 7 Hari Terakhir
        </h6>
        <span class="badge-premium {{ $trendChange >= 0 ? 'badge-premium-success' : 'badge-premium-danger' }}" style="background: {{ $trendChange >= 0 ? 'rgba(16,185,129,0.15)' : 'rgba(248,113,113,0.15)' }}; border-color: {{ $trendChange >= 0 ? 'rgba(16,185,129,0.3)' : 'rgba(248,113,113,0.3)' }}; color: {{ $trendChange >= 0 ? '#34d399' : '#f87171' }};">
          {{ $trendChange >= 0 ? '↑' : '↓' }} {{ abs($trendChange) }} pendaftar
        </span>
      </div>
      <!-- Bar chart CSS-only -->
      <div class="d-flex align-items-end gap-1 mt-3" style="height: 60px;">
        @foreach($trendLabels as $i => $label)
          @php
            $value = $trendData[$i] ?? 0;
            $height = max(($value / $trendMax) * 100, 5);
            $dateDisplay = \Carbon\Carbon::parse($label)->format('d/m');
          @endphp
          <div class="flex-grow-1 d-flex flex-column align-items-center">
            <div class="w-100 rounded" style="height: {{ $height }}%; background: linear-gradient(180deg, #6366f1, #d946ef); transition: height 0.3s; min-height: 4px;"
                 title="{{ $dateDisplay }}: {{ $value }} pendaftar"></div>
            <small class="text-body-premium mt-1" style="font-size: 0.55rem;">{{ $dateDisplay }}</small>
          </div>
        @endforeach
      </div>
    </div>

    <!-- ============================================================
         SECTION: Registration Funnel
         ============================================================ -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-filter text-gradient"></i>
          Corong Verifikasi Pendaftaran (Registration Funnel)
        </h5>
        <span class="badge-premium badge-premium-primary">Total: {{ $funnelCounts->total }} Pendaftar</span>
      </div>

      @php
        $totalVal = $funnelCounts->total ?: 1;
        $pendingPct = round(($funnelCounts->pending / $totalVal) * 100);
        $approvedPct = round(($funnelCounts->approved / $totalVal) * 100);
        $waitingWaPct = round(($funnelCounts->waiting_wa / $totalVal) * 100);
        $waitingNewbimmaPct = round(($funnelCounts->waiting_newbimma / $totalVal) * 100);
        $confirmedPct = round(($funnelCounts->confirmed / $totalVal) * 100);
      @endphp

      <div class="row g-4 align-items-center">
        <!-- 5 Steps Horizontal Funnel using Neon Glowing Bars -->
        <div class="col-12 col-md-6 col-lg-2-4">
          <div class="d-flex flex-column gap-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(148, 163, 184, 0.4)'; this.style.boxShadow='0 0 15px rgba(148, 163, 184, 0.15)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.04)'; this.style.boxShadow='none'">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-premium small fw-semibold">1. Pending</span>
              <span class="badge bg-secondary bg-opacity-20 text-white-50">{{ $funnelCounts->pending }} ({{ $pendingPct }}%)</span>
            </div>
            <div class="progress progress-dark-premium" style="height: 8px;">
              <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $pendingPct }}%" aria-valuenow="{{ $pendingPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-2-4">
          <div class="d-flex flex-column gap-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(99, 102, 241, 0.4)'; this.style.boxShadow='0 0 15px rgba(99, 102, 241, 0.15)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.04)'; this.style.boxShadow='none'">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-premium small fw-semibold">2. Approved</span>
              <span class="badge bg-indigo bg-opacity-20 text-indigo" style="color: #818cf8 !important;">{{ $funnelCounts->approved }} ({{ $approvedPct }}%)</span>
            </div>
            <div class="progress progress-dark-premium" style="height: 8px;">
              <div class="progress-bar" role="progressbar" style="width: {{ $approvedPct }}%; background: #6366f1;" aria-valuenow="{{ $approvedPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-2-4">
          <div class="d-flex flex-column gap-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(245, 158, 11, 0.4)'; this.style.boxShadow='0 0 15px rgba(245, 158, 11, 0.15)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.04)'; this.style.boxShadow='none'">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-premium small fw-semibold">3. Menunggu WA</span>
              <span class="badge bg-warning bg-opacity-20 text-warning">{{ $funnelCounts->waiting_wa }} ({{ $waitingWaPct }}%)</span>
            </div>
            <div class="progress progress-dark-premium" style="height: 8px;">
              <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $waitingWaPct }}%" aria-valuenow="{{ $waitingWaPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-2-4">
          <div class="d-flex flex-column gap-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(6, 182, 212, 0.4)'; this.style.boxShadow='0 0 15px rgba(6, 182, 212, 0.15)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.04)'; this.style.boxShadow='none'">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-premium small fw-semibold">4. Cek NewBimma</span>
              <span class="badge bg-info bg-opacity-20 text-info">{{ $funnelCounts->waiting_newbimma }} ({{ $waitingNewbimmaPct }}%)</span>
            </div>
            <div class="progress progress-dark-premium" style="height: 8px;">
              <div class="progress-bar bg-info" role="progressbar" style="width: {{ $waitingNewbimmaPct }}%" aria-valuenow="{{ $waitingNewbimmaPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-2-4">
          <div class="d-flex flex-column gap-2 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); transition: all 0.3s;" onmouseover="this.style.borderColor='rgba(16, 185, 129, 0.4)'; this.style.boxShadow='0 0 15px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.04)'; this.style.boxShadow='none'">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-body-premium small fw-semibold">5. Confirmed</span>
              <span class="badge bg-success bg-opacity-20 text-success" style="box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);">{{ $funnelCounts->confirmed }} ({{ $confirmedPct }}%)</span>
            </div>
            <div class="progress progress-dark-premium" style="height: 8px;">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $confirmedPct }}%; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);" aria-valuenow="{{ $confirmedPct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Custom CSS to support col-lg-2-4 -->
      <style>
        @media (min-width: 992px) {
          .col-lg-2-4 {
            flex: 0 0 auto;
            width: 20%;
          }
        }
      </style>
    </div>

    <!-- ============================================================
         SECTION: DAFTAR PELATIHAN — Data Real dari DashboardService
         ============================================================ -->
    <div id="daftar-pelatihan-section" class="mb-4"
         x-data="{
           filterStatus: '',
           filterDinas: '',
           searchQuery: '',
           get filteredPelatihan() {
             return pelatihanData.filter(p => {
               const matchStatus = !this.filterStatus || p.status_label === this.filterStatus;
               const matchDinas = !this.filterDinas || p.dinas_singkatan === this.filterDinas;
               const q = this.searchQuery.toLowerCase();
               const matchSearch = !q || p.nama.toLowerCase().includes(q) || p.batch.toLowerCase().includes(q);
               return matchStatus && matchDinas && matchSearch;
             });
           },
            expandedPelatihan: null
          }">
      <div class="glass-card-premium px-4 px-xl-5 py-4">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
          <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-books text-gradient"></i>
            Daftar Pelatihan
          </h5>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Filter Status -->
            <select x-model="filterStatus" class="form-select form-select-sm" style="width:auto; min-width:140px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #fff;">
              <option value="">Semua Status</option>
              <option value="Aktif">🟢 Aktif</option>
              <option value="Akan Datang">🟡 Akan Datang</option>
              <option value="Selesai">🔵 Selesai</option>
              <option value="Nonaktif">⚪ Nonaktif</option>
            </select>
            <!-- Filter Dinas -->
            <select x-model="filterDinas" class="form-select form-select-sm" style="width:auto; min-width:140px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #fff;">
              <option value="">Semua Dinas</option>
              @php $uniqueDinas = $pelatihanList->pluck('dinas.singkatan')->unique()->filter(); @endphp
              @foreach($uniqueDinas as $singkatan)
                <option value="{{ $singkatan }}">{{ $singkatan }}</option>
              @endforeach
            </select>
            <!-- Pencarian -->
            <input type="text" x-model="searchQuery" class="form-control form-control-sm" placeholder="🔍 Cari pelatihan..." style="width:200px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: #fff;">
          </div>
        </div>

        @if($pelatihanList->isEmpty())
          <!-- Empty State -->
          <div class="text-center py-5">
            <div class="stat-icon-box stat-icon-info mx-auto mb-3" style="width: 64px; height: 64px;">
              <i class="icon-base ti tabler-books-off" style="font-size: 2rem;"></i>
            </div>
            <h5 class="text-white fw-semibold mb-2">Belum Ada Pelatihan</h5>
            <p class="text-body-premium mb-3" style="font-size: 0.9rem;">Mulai dengan membuat pelatihan baru untuk peserta.</p>
            <a href="{{ route('admin.pelatihan.create') }}" class="btn btn-glow-premium px-4 py-2">
              <i class="icon-base ti tabler-plus me-1"></i> Buat Pelatihan Baru
            </a>
          </div>
        @else
          <!-- Table -->
          <div class="table-responsive">
            <table class="table table-borderless text-white align-middle mb-0" style="font-size: 0.85rem;">
              <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                  <th class="text-body-premium small fw-semibold ps-0" style="width: 30%;">Program</th>
                  <th class="text-body-premium small fw-semibold">Dinas</th>
                  <th class="text-body-premium small fw-semibold">Status</th>
                  <th class="text-body-premium small fw-semibold">Pendaftar</th>
                  <th class="text-body-premium small fw-semibold">Progress</th>
                  <th class="text-body-premium small fw-semibold">Pertemuan</th>
                  <th class="text-body-premium small fw-semibold text-end pe-0">Aksi</th>
                </tr>
              </thead>
                @foreach($pelatihanList as $pelatihan)
                <tbody x-show="filteredPelatihan.some(p => p.id === {{ $pelatihan->id }})">
                <tr class="clickable-row" style="border-bottom: 1px solid rgba(255,255,255,0.04); cursor: pointer; transition: all 0.2s;"
                    @click="expandedPelatihan = expandedPelatihan === {{ $pelatihan->id }} ? null : {{ $pelatihan->id }}"
                    onmouseover="this.style.background='rgba(255,255,255,0.02)'"
                    onmouseout="this.style.background='transparent'">
                  <td class="ps-0 py-3">
                    <div class="fw-semibold text-white" style="font-size: 0.9rem;">{{ $pelatihan->nama }}</div>
                    <small class="text-body-premium">Batch {{ $pelatihan->batch }}</small>
                  </td>
                  <td class="py-3">
                    @if($pelatihan->dinas)
                      <span class="badge-premium badge-premium-primary px-2 py-1" style="font-size: 0.7rem;">{{ $pelatihan->dinas->singkatan }}</span>
                    @else
                      <span class="text-body-premium">—</span>
                    @endif
                  </td>
                  <td class="py-3">
                    @php
                      $dotColor = match($pelatihan->status_color) {
                        'success' => '#34d399',
                        'info' => '#22d3ee',
                        'warning' => '#fbbf24',
                        default => '#94a3b8',
                      };
                    @endphp
                    <span class="d-flex align-items-center gap-1">
                      <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:{{ $dotColor }}; box-shadow: 0 0 6px {{ $dotColor }};"></span>
                      <span>{{ $pelatihan->status_label }}</span>
                    </span>
                  </td>
                  <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                      <div class="progress progress-dark-premium flex-grow-1" style="height: 6px; max-width: 80px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $pelatihan->progress_pendaftar }}%; background: linear-gradient(90deg, #6366f1, #d946ef);" aria-valuenow="{{ $pelatihan->progress_pendaftar }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small class="text-body-premium" style="white-space:nowrap;">{{ $pelatihan->confirmed_count }}/{{ $pelatihan->kuota }}</small>
                    </div>
                  </td>
                  <td class="py-3">
                    <div class="d-flex align-items-center gap-2">
                      <div class="progress progress-dark-premium flex-grow-1" style="height: 6px; max-width: 80px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $pelatihan->progress_waktu }}%; background: linear-gradient(90deg, #06b6d4, #6366f1);" aria-valuenow="{{ $pelatihan->progress_waktu }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <small class="text-body-premium" style="white-space:nowrap;">{{ $pelatihan->progress_waktu }}%</small>
                    </div>
                  </td>
                  <td class="py-3">
                    <span class="text-body-premium">{{ $pelatihan->schedules_done_count }}/{{ $pelatihan->schedules_count }}</span>
                  </td>
                  <td class="text-end pe-0 py-3">
                    <div class="d-flex gap-1 justify-content-end">
                      <a href="{{ route('admin.pelatihan.edit', $pelatihan->id) }}" class="btn btn-sm btn-icon text-white p-1 border-0 bg-transparent" title="Detail/Edit" style="width:30px; height:30px;">
                        <i class="icon-base ti tabler-eye"></i>
                      </a>
                      <a href="{{ route('admin.pelatihan.edit', $pelatihan->id) }}?settings=1" class="btn btn-sm btn-icon text-white p-1 border-0 bg-transparent" title="Pengaturan" style="width:30px; height:30px;">
                        <i class="icon-base ti tabler-settings"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <!-- Expandable Detail Row -->
                <tr class="expandable-detail" x-show="expandedPelatihan === {{ $pelatihan->id }}" x-cloak>
                  <td colspan="7" class="px-0 py-0" style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                    <div class="px-4 py-3" style="background: rgba(99,102,241,0.03);">
                      <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                          <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="text-body-premium small fw-semibold">Distribusi Pendaftar:</span>
                          </div>
                          @php
                            $kuota = $pelatihan->kuota ?: 1;
                            $confirmedBar = round(($pelatihan->confirmed_count / $kuota) * 100);
                            $approvedBar = round(($pelatihan->approved_count / $kuota) * 100);
                            $pendingBar = round(($pelatihan->pending_count / $kuota) * 100);
                            $sisaKuota = max(0, $kuota - $pelatihan->confirmed_count - $pelatihan->approved_count - $pelatihan->pending_count);
                            $sisaBar = round(($sisaKuota / $kuota) * 100);
                            $totalBar = $confirmedBar + $approvedBar + $pendingBar + $sisaBar;
                            // Normalize to 100%
                            if ($totalBar > 0) {
                              $confirmedBar = round(($confirmedBar / $totalBar) * 100);
                              $approvedBar = round(($approvedBar / $totalBar) * 100);
                              $pendingBar = round(($pendingBar / $totalBar) * 100);
                              $sisaBar = max(0, 100 - $confirmedBar - $approvedBar - $pendingBar);
                            }
                          @endphp
                          <div class="d-flex w-100 rounded overflow-hidden mb-2" style="height: 10px;">
                            @if($confirmedBar > 0) <div style="width:{{ $confirmedBar }}%; background:#10b981;"></div> @endif
                            @if($approvedBar > 0) <div style="width:{{ $approvedBar }}%; background:#6366f1;"></div> @endif
                            @if($pendingBar > 0) <div style="width:{{ $pendingBar }}%; background:#f59e0b;"></div> @endif
                            @if($sisaBar > 0) <div style="width:{{ $sisaBar }}%; background:rgba(255,255,255,0.1);"></div> @endif
                          </div>
                          <div class="d-flex flex-wrap gap-3 text-body-premium" style="font-size:0.75rem;">
                            <span><span style="color:#10b981;">█</span> Confirmed: {{ $pelatihan->confirmed_count }}</span>
                            <span><span style="color:#6366f1;">█</span> Approved: {{ $pelatihan->approved_count }}</span>
                            <span><span style="color:#f59e0b;">█</span> Pending: {{ $pelatihan->pending_count }}</span>
                            <span><span style="color:rgba(255,255,255,0.2);">█</span> Sisa Kuota: {{ $sisaKuota }}</span>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="text-body-premium small">
                            <div class="mb-1">
                              <i class="icon-base ti tabler-calendar me-1"></i>
                              Periode: {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('j F Y') }} - {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('j F Y') }}
                            </div>
                            <div>
                              <i class="icon-base ti tabler-clock me-1"></i>
                              Sisa Hari: <strong class="{{ $pelatihan->sisa_hari !== null && $pelatihan->sisa_hari > 0 ? 'text-warning' : 'text-danger' }}">{{ $pelatihan->sisa_hari !== null ? abs($pelatihan->sisa_hari) . ' ' . ($pelatihan->sisa_hari >= 0 ? 'hari lagi' : 'hari lalu') : '—' }}</strong>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                </tbody>
                @endforeach
            </table>
          </div>
          <!-- No results message -->
          <div x-show="filteredPelatihan.length === 0 && pelatihanData.length > 0"
               x-cloak
               class="text-center py-4">
            <i class="icon-base ti tabler-search-off fs-2 text-body-premium mb-2 d-block"></i>
            <p class="text-body-premium mb-0">Tidak ada pelatihan yang sesuai filter</p>
          </div>
        @endif
      </div>
    </div>

    <!-- Pass data to Alpine -->
    @php
      $pelatihanDataJson = $pelatihanList->map(function($p) {
          return [
              'id' => $p->id,
              'nama' => $p->nama,
              'batch' => $p->batch,
              'dinas_singkatan' => $p->dinas?->singkatan ?? '',
              'status_label' => $p->status_label,
          ];
      })->values()->toJson();
    @endphp
    <script>
      const pelatihanData = {!! $pelatihanDataJson !!};
    </script>

    <style>
      [x-cloak] { display: none !important; }
      .clickable-row:hover { background: rgba(255,255,255,0.02) !important; }
    </style>

    <!-- ============================================================
         SECTION: LIVE CLASS + MAP (dipindah ke bawah Daftar Pelatihan)
         ============================================================ -->
    <div class="row g-4 mb-4">
      <!-- Kolom Kiri: Live Class Monitoring Hari Ini -->
      <div class="col-12 col-xl-6">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-desktop-analytics text-warning"></i>
              Live Class Monitoring Hari Ini
            </h5>
            <span class="badge bg-warning bg-opacity-20 text-warning px-2 py-1" style="font-size: 10px; border-radius: 4px;">Live</span>
          </div>

          <div class="flex-grow-1">
            @if($livePelatihans->isEmpty())
              <div class="text-center py-5">
                <div class="stat-icon-box stat-icon-warning mx-auto mb-3" style="width: 56px; height: 56px; border-radius: 50% !important;">
                  <i class="icon-base ti tabler-alert-circle" style="font-size: 1.8rem;"></i>
                </div>
                <h6 class="text-white fw-semibold mb-1">Tidak Ada Pelatihan Berjalan Hari Ini</h6>
                <p class="text-body-premium small mb-0">Semua kelas sedang nonaktif atau libur hari ini.</p>
              </div>
            @else
              <div class="d-flex flex-column gap-4">
                @foreach($livePelatihans as $live)
                  <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                    <div class="flex-grow-1 me-3">
                      <div class="d-flex align-items-center gap-2 mb-1">
                        <h6 class="text-white fw-bold mb-0" style="font-size: 0.95rem;">{{ $live['nama'] }}</h6>
                        <span class="badge bg-primary bg-opacity-20 text-primary small px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">{{ $live['batch'] }}</span>
                      </div>
                      <small class="text-body-premium d-block mb-2">Instruktur: <strong class="text-white">{{ $live['instruktur'] }}</strong> • Dinas: {{ $live['dinas']->singkatan ?? '-' }}</small>
                      
                      <div class="d-flex align-items-center gap-2">
                        <div class="progress progress-dark-premium flex-grow-1" style="height: 6px;">
                          <div class="progress-bar bg-success" role="progressbar" style="width: {{ $live['persentase'] }}%" aria-valuenow="{{ $live['persentase'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="text-success fw-bold small" style="font-size: 0.8rem; white-space: nowrap;">{{ $live['hadir'] }} / {{ $live['total'] }} Hadir ({{ $live['persentase'] }}%)</span>
                      </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                      <a href="{{ route('panitia.scanner', $live['id']) }}" class="btn btn-glow-premium d-flex align-items-center justify-content-center p-2" title="Scanner Presensi" style="width: 40px; height: 40px; background: linear-gradient(135deg, #10b981, #059669) !important; color: white !important; box-shadow: 0 0 10px rgba(16,185,129,0.3);">
                        <i class="icon-base ti tabler-scan fs-5"></i>
                      </a>
                      <a href="{{ route('instruktur.monitoring', $live['id']) }}" class="btn btn-glow-premium d-flex align-items-center justify-content-center p-2" title="Proyektor Monitoring" style="width: 40px; height: 40px; background: linear-gradient(135deg, #6366f1, #d946ef) !important; color: white !important; box-shadow: 0 0 10px rgba(99,102,241,0.3);">
                        <i class="icon-base ti tabler-device-desktop-analytics fs-5"></i>
                      </a>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Leaflet Map -->
      <div class="col-12 col-xl-6">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-map text-info"></i>
              Sebaran Pendaftar per Kecamatan
            </h5>
            <a href="{{ route('admin.kecamatan.index') }}" class="btn btn-glow-premium btn-sm py-1 px-3">
              Detail Kecamatan
            </a>
          </div>

          <div class="flex-grow-1" style="min-height: 300px; position: relative; border-radius: 5px; overflow: hidden; z-index: 1;">
            <div id="map-sebaran-peserta" style="height: 300px; width: 100%; border-radius: 5px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================
         SECTION: PESERTA TERDAFTAR BARU + TOP INSTRUKTUR
         ============================================================ -->
    <div class="row g-4 mb-4">
      
      <!-- LEFT: Peserta Terdaftar Baru -->
      <div class="col-lg-6">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-users text-success"></i>
              Peserta Terdaftar Baru
            </h5>
            <span class="badge-premium badge-premium-success" id="badge-peserta-count">{{ $pesertaCount }} Peserta</span>
          </div>

          <div id="container-latest-peserta">
            @if($latestPeserta->isEmpty())
              <div class="text-center py-4">
                <i class="icon-base ti tabler-user-off fs-2 text-muted mb-2"></i>
                <p class="text-body-premium small mb-0">Belum ada peserta yang mendaftar.</p>
              </div>
            @else
              <div class="d-flex flex-column gap-3">
                @foreach($latestPeserta as $p)
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                      <div class="instructor-avatar text-white" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                        {{ strtoupper(substr($p->name, 0, 2)) }}
                      </div>
                      <div>
                        <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">{{ $p->name }}</h6>
                        <small class="text-body-premium">{{ $p->nik ?? 'NIK Tidak Tersedia' }}</small>
                      </div>
                    </div>
                    <span class="text-body-premium small">{{ $p->created_at->diffForHumans() }}</span>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- RIGHT: Top Instruktur -->
      @if($topInstruktur->isNotEmpty())
      <div class="col-lg-6">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <h6 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-crown text-gradient"></i>
            Instruktur Paling Aktif
          </h6>
          <div class="d-flex flex-column gap-3">
            @foreach($topInstruktur as $i => $instruktur)
              <div class="d-flex align-items-center gap-3">
                <span class="fw-bold" style="color: {{ $i == 0 ? '#fbbf24' : ($i == 1 ? '#94a3b8' : ($i == 2 ? '#d97706' : 'rgba(255,255,255,0.3)')) }};">
                  #{{ $i + 1 }}
                </span>
                <div class="instructor-avatar" style="background: rgba(99,102,241,0.15); color: #818cf8;">
                  {{ strtoupper(substr($instruktur->name, 0, 2)) }}
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">{{ $instruktur->name }}</h6>
                  <small class="text-body-premium">{{ $instruktur->total_sessions }} sesi pelatihan</small>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      @endif

    </div>

    </div>

    <!-- ============================================================
         FOURTH ROW: Tabbed Log System (Bawah)
         ============================================================ -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-history text-gradient"></i>
          Log & Audit System
        </h5>
        <span class="badge bg-danger bg-opacity-20 text-danger px-2 py-1" style="font-size: 10px; border-radius: 4px;">Real-time</span>
      </div>

      <!-- Navigation Tabs -->
      <ul class="nav nav-tabs border-bottom border-white border-opacity-10 mb-4" id="logSystemTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active text-white bg-transparent border-0 px-4 py-2" id="general-log-tab" data-bs-toggle="tab" data-bs-target="#general-log-content" type="button" role="tab" aria-controls="general-log-content" aria-selected="true" style="transition: all 0.3s; font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-activity me-2"></i>Log Aktivitas Umum
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link text-white-50 bg-transparent border-0 px-4 py-2" id="audit-log-tab" data-bs-toggle="tab" data-bs-target="#audit-log-content" type="button" role="tab" aria-controls="audit-log-content" aria-selected="false" style="transition: all 0.3s; font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-shield-alert me-2"></i>Log Audit Presensi
          </button>
        </li>
      </ul>

      <!-- Tab Contents -->
      <div class="tab-content p-0" id="logSystemTabContent">
        <!-- Tab 1: Log Aktivitas Umum -->
        <div class="tab-pane fade show active" id="general-log-content" role="tabpanel" aria-labelledby="general-log-tab">
          <div id="container-latest-activities">
            @if($latestActivities->isEmpty())
              <div class="text-center py-5">
                <i class="icon-base ti tabler-activity-heartbeat fs-1 text-muted mb-2"></i>
                <p class="text-body-premium small mb-0">Belum ada aktivitas tercatat.</p>
              </div>
            @else
              <div class="d-flex flex-column gap-3">
                @foreach($latestActivities as $log)
                  @php
                    $badgeColor = 'secondary';
                    if ($log->action === 'created' || $log->action === 'approved') $badgeColor = 'success';
                    elseif ($log->action === 'updated') $badgeColor = 'warning';
                    elseif ($log->action === 'deleted' || $log->action === 'rejected') $badgeColor = 'danger';
                    elseif ($log->action === 'login') $badgeColor = 'info';
                  @endphp
                  <div class="d-flex align-items-start gap-3 p-2 rounded" style="background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.02);">
                    <span class="badge bg-{{ $badgeColor }} bg-opacity-20 text-{{ $badgeColor }} text-uppercase px-2 py-1 mt-1" style="font-size: 8px; border-radius: 4px; min-width: 65px; text-align: center;">
                      {{ $log->action }}
                    </span>
                    <div class="flex-grow-1">
                      <p class="text-white mb-0" style="font-size: 0.85rem; line-height: 1.4;">{{ $log->description }}</p>
                      <small class="text-body-premium" style="font-size: 0.75rem;">
                        Oleh: {{ $log->user->name ?? 'Sistem' }} • {{ $log->created_at->diffForHumans() }}
                      </small>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        <!-- Tab 2: Log Audit Presensi -->
        <div class="tab-pane fade" id="audit-log-content" role="tabpanel" aria-labelledby="audit-log-tab">
          <div id="container-latest-audit-logs">
            @if($latestAuditLogs->isEmpty())
              <div class="text-center py-5">
                <i class="icon-base ti tabler-shield-check fs-1 text-success mb-3"></i>
                <h6 class="text-white">Log audit aman & bersih</h6>
                <p class="text-body-premium small mb-0">Tidak ada log koreksi atau bypass presensi terdeteksi.</p>
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-borderless text-white align-middle" style="font-size: 0.85rem;">
                  <thead>
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                      <th class="text-body-premium small fw-semibold px-0">Pelaku</th>
                      <th class="text-body-premium small fw-semibold">Aksi</th>
                      <th class="text-body-premium small fw-semibold">Target</th>
                      <th class="text-body-premium small fw-semibold">Deskripsi</th>
                      <th class="text-body-premium small fw-semibold">IP Address</th>
                      <th class="text-body-premium small fw-semibold text-end px-0">Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($latestAuditLogs as $audit)
                      @php
                        $badgeColor = 'secondary';
                        if ($audit->action_type === 'bypass') $badgeColor = 'warning';
                        elseif ($audit->action_type === 'correct' || $audit->action_type === 'update') $badgeColor = 'info';
                        elseif ($audit->action_type === 'delete') $badgeColor = 'danger';
                      @endphp
                      <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                        <td class="px-0 py-3">
                          <div class="fw-semibold text-white">{{ $audit->actor->name ?? 'Sistem' }}</div>
                          <span class="badge bg-{{ $badgeColor }} bg-opacity-20 text-{{ $badgeColor }} text-uppercase" style="font-size: 8px; border-radius: 4px; padding: 2px 6px;">{{ $audit->actor_role }}</span>
                        </td>
                        <td class="py-3">
                          <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} border-opacity-30 text-uppercase" style="font-size: 8px; border-radius: 4px; padding: 2px 6px;">{{ $audit->action_type }}</span>
                        </td>
                        <td class="py-3 text-body-premium">
                          {{ $audit->target_entity }} #{{ $audit->target_id }}
                        </td>
                        <td class="py-3 text-white" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                          {{ $audit->description }}
                        </td>
                        <td class="py-3 text-body-premium">
                          {{ $audit->ip_address ?? '-' }}
                        </td>
                        <td class="text-end px-0 py-3 text-body-premium">
                          {{ $audit->created_at->diffForHumans() }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Active State Styling -->
    <style>
      #logSystemTab .nav-link.active {
        color: #fff !important;
        border-bottom: 2px solid #6366f1 !important;
        background: rgba(99, 102, 241, 0.05) !important;
      }
      #logSystemTab .nav-link:not(.active):hover {
        color: #fff !important;
        background: rgba(255,255,255,0.02) !important;
      }
    </style>

  </div>
@endsection

@section('page-script')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
      // Setup WA gateway status checker
      checkWaConnectionStatus();

      // Setup real-time polling every 10 seconds (silent check)
      setInterval(function() {
        checkWaConnectionStatus(true);
      }, 10000);

      // Inisialisasi peta Bandung (-6.914744, 107.609810, zoom 12)
      var map = L.map('map-sebaran-peserta', {
        center: [-6.914744, 107.609810],
        zoom: 12,
        zoomControl: true,
        attributionControl: false
      });

      // CartoDB Dark Matter
      L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 20
      }).addTo(map);

      // Data sebaran kecamatan dari backend
      var sebaranKecamatan = @json($sebaranKecamatan);

      sebaranKecamatan.forEach(function(kec) {
        if (kec.latitude && kec.longitude) {
          var count = parseInt(kec.total_pendaftar) || 0;
          if (count > 0) {
            // Hitung radius proporsional: Math.sqrt(total_pendaftar) * 150 (dengan minimal radius 150 meter)
            var radius = Math.max(Math.sqrt(count) * 150, 150);
            
            // Render lingkaran L.circle
            var circle = L.circle([kec.latitude, kec.longitude], {
              color: '#6366f1',
              fillColor: '#818cf8',
              fillOpacity: 0.45,
              weight: 2,
              radius: radius
            }).addTo(map);

            // Bind popup & tooltip cantik
            var popupContent = `
              <div style="font-family: 'Outfit', sans-serif; color: #fff; padding: 4px;">
                <h6 style="font-family: 'Sora', sans-serif; font-weight: 700; margin-bottom: 8px; color: #818cf8;">Kecamatan ${kec.name}</h6>
                <div style="display: flex; justify-content: space-between; gap: 24px; font-size: 13px;">
                  <span style="color: rgba(255,255,255,0.7);">Total Pendaftar:</span>
                  <span style="font-weight: 700; color: #fbbf24;">${count} Peserta</span>
                </div>
              </div>
            `;

            circle.bindPopup(popupContent, {
              className: 'custom-leaflet-popup',
              closeButton: true
            });

            circle.bindTooltip(`Kec. ${kec.name} (${count} Pendaftar)`, {
              className: 'custom-leaflet-tooltip',
              direction: 'top',
              sticky: true
            });
          }
        }
      });
    });

    function checkWaConnectionStatus(isSilent = false) {
      const senderContainer = document.getElementById('wa-sender-status-container');
      const checkSenderContainer = document.getElementById('wa-check-sender-status-container');

      if (!isSilent) {
        if (senderContainer) {
          senderContainer.innerHTML = `
            <span class="badge bg-secondary text-white-50 px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem;">
              <i class="icon-base ti tabler-refresh spin-icon me-1" style="font-size: 0.75rem;"></i> Kirim: ...
            </span>
          `;
          const spinIcon = senderContainer.querySelector('.spin-icon');
          if (spinIcon) spinIcon.style.animation = 'spin 1.5s linear infinite';
        }

        if (checkSenderContainer) {
          checkSenderContainer.innerHTML = `
            <span class="badge bg-secondary text-white-50 px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem;">
              <i class="icon-base ti tabler-refresh spin-icon me-1" style="font-size: 0.75rem;"></i> Cek: ...
            </span>
          `;
          const spinIcon = checkSenderContainer.querySelector('.spin-icon');
          if (spinIcon) spinIcon.style.animation = 'spin 1.5s linear infinite';
        }
      }

      fetch('{{ route("admin.whatsapp-gateway.status") }}')
        .then(response => response.json())
        .then(data => {
          // 1. Update status Kirim (sender)
          if (senderContainer) {
            if (data.sender && data.sender.connected === true) {
              senderContainer.innerHTML = `
                <span class="badge bg-success text-white px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem; box-shadow: 0 0 10px rgba(16,185,129,0.4); border: 1px solid rgba(16,185,129,0.6);">
                  <span class="pulse-green" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #34d399; box-shadow: 0 0 6px #34d399; margin-right: 2px;"></span> Kirim: Connected
                </span>
              `;
            } else {
              const displayStatus = (data.sender && data.sender.status) || 'Disconnected';
              senderContainer.innerHTML = `
                <span class="badge bg-warning text-white px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem; box-shadow: 0 0 10px rgba(245,158,11,0.4); border: 1px solid rgba(245,158,11,0.6);">
                  <span class="pulse-red" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #f87171; box-shadow: 0 0 6px #f87171; margin-right: 2px;"></span> Kirim: ${displayStatus}
                </span>
              `;
            }
          }

          // 2. Update status Cek WA (check_sender)
          if (checkSenderContainer) {
            if (data.check_sender && data.check_sender.connected === true) {
              checkSenderContainer.innerHTML = `
                <span class="badge bg-success text-white px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem; box-shadow: 0 0 10px rgba(16,185,129,0.4); border: 1px solid rgba(16,185,129,0.6);">
                  <span class="pulse-green" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #34d399; box-shadow: 0 0 6px #34d399; margin-right: 2px;"></span> Cek: Connected
                </span>
              `;
            } else {
              const displayStatus = (data.check_sender && data.check_sender.status) || 'Disconnected';
              checkSenderContainer.innerHTML = `
                <span class="badge bg-warning text-white px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem; box-shadow: 0 0 10px rgba(245,158,11,0.4); border: 1px solid rgba(245,158,11,0.6);">
                  <span class="pulse-red" style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #f87171; box-shadow: 0 0 6px #f87171; margin-right: 2px;"></span> Cek: ${displayStatus}
                </span>
              `;
            }
          }
        })
        .catch(error => {
          console.error('Error fetching WA gateway status:', error);
          const fallbackHtml = `
            <span class="badge bg-secondary text-white-50 px-2 py-1 d-flex align-items-center gap-1" style="border-radius: 4px; font-size: 0.7rem;">
              <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background-color: #a1a1aa; margin-right: 2px;"></span> Offline / Error
            </span>
          `;
          if (senderContainer) senderContainer.innerHTML = fallbackHtml;
          if (checkSenderContainer) checkSenderContainer.innerHTML = fallbackHtml;
        });
    }
  </script>
  @vite(['resources/assets/js/dashboard-admin.js'])
@endsection


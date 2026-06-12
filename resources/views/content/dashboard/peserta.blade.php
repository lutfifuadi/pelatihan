@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Peserta')

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

  .schedule-item {
    border-left: 2px solid rgba(255, 255, 255, 0.06);
    padding-left: 16px;
    position: relative;
  }
  .schedule-item::before {
    content: '';
    position: absolute;
    left: -5px;
    top: 6px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #6366f1;
  }
  .schedule-item.completed::before {
    background: #10b981;
  }
  .schedule-item.upcoming::before {
    background: #f59e0b;
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

  hr.dark-premium {
    border-color: rgba(255, 255, 255, 0.06);
    opacity: 1;
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
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-lg-8">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-user-star fs-4"></i>
            </div>
            <div>
              <h4 class="fw-bold text-white mb-0">Selamat datang, <span class="text-gradient fw-extrabold">{{ auth()->user()->name }}</span> 👋</h4>
              <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
                Terus semangat belajar dan tingkatkan skill kreatifmu!
              </p>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
          <div class="d-flex align-items-center gap-4 justify-content-lg-end">
            <div class="text-center">
              <p class="text-body-premium small mb-0">Jam Belajar</p>
              <h5 class="text-white fw-bold mb-0">12<span class="text-body-premium fs-sm">j</span> 30<span class="text-body-premium fs-sm">m</span></h5>
            </div>
            <div class="text-center">
              <p class="text-body-premium small mb-0">Nilai Rata-rata</p>
              <h5 class="text-white fw-bold mb-0">85<span class="text-body-premium fs-sm">/100</span></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================
         STATISTICS CARDS
         ============================================================ -->
    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-books"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Total Pelatihan</p>
              <h3 class="fw-bold text-white mb-0">6</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-success">
              <i class="icon-base ti tabler-clipboard-check"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Tugas Selesai</p>
              <h3 class="fw-bold text-white mb-0">24</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-info">
              <i class="icon-base ti tabler-certificate"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Sertifikat</p>
              <h3 class="fw-bold text-white mb-0">3</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-warning">
              <i class="icon-base ti tabler-clock-hour-4"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Total Jam Belajar</p>
              <h3 class="fw-bold text-white mb-0">12.5j</h3>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ============================================================
         MAIN CONTENT ROW
         ============================================================ -->
    <div class="row g-4 mb-4">

      <!-- LEFT: Progress Pelatihan -->
      <div class="col-12 col-xl-8">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-trending-up text-primary"></i>
              Progress Pelatihan
            </h5>
            <span class="badge-premium badge-premium-primary">4 Aktif</span>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Kuliner Kreatif — Dasar Memasak Modern</h6>
                <small class="text-body-premium">12 dari 20 Modul</small>
              </div>
              <span class="text-white fw-bold small">60%</span>
            </div>
            <div class="progress progress-dark-premium">
              <div class="progress-bar" style="width: 60%;"></div>
            </div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Konten Kreator — Video & Fotografi</h6>
                <small class="text-body-premium">8 dari 15 Modul</small>
              </div>
              <span class="text-white fw-bold small">53%</span>
            </div>
            <div class="progress progress-dark-premium">
              <div class="progress-bar" style="width: 53%;"></div>
            </div>
          </div>

          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Desain Grafis — Canva & Adobe Express</h6>
                <small class="text-body-premium">5 dari 12 Modul</small>
              </div>
              <span class="text-white fw-bold small">42%</span>
            </div>
            <div class="progress progress-dark-premium">
              <div class="progress-bar" style="width: 42%;"></div>
            </div>
          </div>

          <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Kriya & Seni Tradisional</h6>
                <small class="text-body-premium">18 dari 18 Modul — Selesai</small>
              </div>
              <span class="text-success fw-bold small">
                <i class="icon-base ti tabler-check-circle me-1"></i>100%
              </span>
            </div>
            <div class="progress progress-dark-premium">
              <div class="progress-bar" style="width: 100%; background: linear-gradient(90deg, #10b981, #34d399);"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Jadwal Mendatang -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-calendar-event text-info"></i>
              Jadwal Mendatang
            </h5>
            <span class="badge-premium badge-premium-info">3 Event</span>
          </div>

          <div class="schedule-item mb-4">
            <h6 class="text-white fw-semibold mb-1" style="font-size: 0.9rem;">Webinar: Tips Konten Viral</h6>
            <div class="d-flex align-items-center gap-3">
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-calendar"></i> 15 Jun 2026
              </small>
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-clock"></i> 09:00 WIB
              </small>
            </div>
          </div>

          <div class="schedule-item mb-4">
            <h6 class="text-white fw-semibold mb-1" style="font-size: 0.9rem;">Workshop: Desain Grafis Lanjutan</h6>
            <div class="d-flex align-items-center gap-3">
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-calendar"></i> 20 Jun 2026
              </small>
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-clock"></i> 13:00 WIB
              </small>
            </div>
          </div>

          <div class="schedule-item">
            <h6 class="text-white fw-semibold mb-1" style="font-size: 0.9rem;">Ujian Akhir Pelatihan</h6>
            <div class="d-flex align-items-center gap-3">
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-calendar"></i> 28 Jun 2026
              </small>
              <small class="text-body-premium d-flex align-items-center gap-1">
                <i class="icon-base ti tabler-clock"></i> 08:00 WIB
              </small>
            </div>
          </div>

          <hr class="dark-premium my-4">

          <div class="text-center">
            <a href="javascript:void(0);" class="btn btn-glow-premium w-100 py-2">
              <i class="icon-base ti tabler-plus me-1"></i>Lihat Semua Jadwal
            </a>
          </div>
        </div>
      </div>

    </div>

    <!-- ============================================================
         BOTTOM ROW: Instruktur & Aktivitas Terakhir
         ============================================================ -->
    <div class="row g-4">

      <!-- Instruktur Saya -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-users text-success"></i>
              Instruktur Saya
            </h5>
            <span class="badge-premium badge-premium-success">4 Aktif</span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="instructor-avatar" style="background: rgba(99, 102, 241, 0.15); color: #818cf8;">AS</div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Ahmad Syarif</h6>
              <small class="text-body-premium">Kuliner Kreatif</small>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="instructor-avatar" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">DN</div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Dewi Nuraini</h6>
              <small class="text-body-premium">Konten Kreator</small>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="instructor-avatar" style="background: rgba(6, 182, 212, 0.15); color: #22d3ee;">RF</div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Rizky Firmansyah</h6>
              <small class="text-body-premium">Desain Grafis</small>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="instructor-avatar" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">SW</div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.9rem;">Siti Wulandari</h6>
              <small class="text-body-premium">Kriya & Seni</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Aktivitas Terakhir -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-activity text-warning"></i>
              Aktivitas Terakhir
            </h5>
            <span class="badge-premium badge-premium-warning">Hari Ini</span>
          </div>

          <div class="d-flex align-items-start gap-3 mb-3">
            <div class="stat-icon-box stat-icon-success" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-check"></i>
            </div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Tugas Modul 5 selesai</h6>
              <small class="text-body-premium">Kuliner Kreatif — 2 jam lalu</small>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-3">
            <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-video"></i>
            </div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Menonton video tutorial</h6>
              <small class="text-body-premium">Konten Kreator — 5 jam lalu</small>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-3">
            <div class="stat-icon-box stat-icon-info" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-file-text"></i>
            </div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Mengunduh materi PDF</h6>
              <small class="text-body-premium">Desain Grafis — 1 hari lalu</small>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3">
            <div class="stat-icon-box stat-icon-warning" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-message"></i>
            </div>
            <div>
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Diskusi grup: Tanya jawab</h6>
              <small class="text-body-premium">Kriya & Seni — 2 hari lalu</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Pelatihan Populer -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-star text-danger"></i>
              Pelatihan Terpopuler
            </h5>
            <span class="badge-premium badge-premium-primary">Top</span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="stat-icon-box stat-icon-primary" style="width: 42px; height: 42px; font-size: 1.2rem;">
              <i class="icon-base ti tabler-chef-hat"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Kuliner Kreatif</h6>
              <small class="text-body-premium">120 peserta terdaftar</small>
            </div>
            <span class="badge-premium badge-premium-success">#1</span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="stat-icon-box stat-icon-success" style="width: 42px; height: 42px; font-size: 1.2rem;">
              <i class="icon-base ti tabler-camera"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Konten Kreator</h6>
              <small class="text-body-premium">98 peserta terdaftar</small>
            </div>
            <span class="badge-premium badge-premium-info">#2</span>
          </div>

          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="stat-icon-box stat-icon-warning" style="width: 42px; height: 42px; font-size: 1.2rem;">
              <i class="icon-base ti tabler-palette"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Desain Grafis</h6>
              <small class="text-body-premium">85 peserta terdaftar</small>
            </div>
            <span class="badge-premium badge-premium-warning">#3</span>
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-danger" style="width: 42px; height: 42px; font-size: 1.2rem;">
              <i class="icon-base ti tabler-scissors"></i>
            </div>
            <div class="flex-grow-1">
              <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Kriya & Seni Tradisional</h6>
              <small class="text-body-premium">62 peserta terdaftar</small>
            </div>
            <span class="badge-premium">#4</span>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection

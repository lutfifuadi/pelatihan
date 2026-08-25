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
  .stat-icon-secondary {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.4);
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
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border-radius: 5px !important;
    padding: 5px 14px;
    font-weight: 700;
    font-size: 0.78rem;
    letter-spacing: 0.03em;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .badge-premium-primary {
    background: rgba(99, 102, 241, 0.22) !important;
    border-color: rgba(129, 140, 248, 0.5) !important;
    color: #c7d2fe !important;
    text-shadow: 0 0 12px rgba(129, 140, 248, 0.5);
  }
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.22) !important;
    border-color: rgba(52, 211, 153, 0.5) !important;
    color: #6ee7b7 !important;
    text-shadow: 0 0 12px rgba(52, 211, 153, 0.5);
  }
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.22) !important;
    border-color: rgba(251, 191, 36, 0.5) !important;
    color: #fef08a !important;
    text-shadow: 0 0 12px rgba(251, 191, 36, 0.5);
  }
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.22) !important;
    border-color: rgba(34, 211, 238, 0.5) !important;
    color: #a5f3fc !important;
    text-shadow: 0 0 12px rgba(34, 211, 238, 0.5);
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.22) !important;
    border-color: rgba(248, 113, 113, 0.5) !important;
    color: #fca5a5 !important;
    text-shadow: 0 0 12px rgba(248, 113, 113, 0.5);
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

  .btn-outline-glass {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-outline-glass:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    transform: translateY(-2px);
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
    background: linear-gradient(135deg, #ffffff 0%, #c7d2fe 45%, #f472b6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 800;
    letter-spacing: -0.02em;
    filter: drop-shadow(0 2px 10px rgba(199, 210, 254, 0.25));
  }

  .hover-text-primary:hover {
    color: #818cf8 !important;
  }

  .info-label {
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #94a3b8 !important;
    font-weight: 700;
    margin-bottom: 3px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .info-value {
    font-size: 0.98rem;
    color: #ffffff !important;
    font-weight: 600;
    line-height: 1.4;
    letter-spacing: -0.01em;
  }
  .info-value.fw-mono {
    font-family: 'Fira Code', 'Courier New', monospace;
    letter-spacing: 0.04em;
    color: #fde047 !important;
    font-size: 0.92rem;
  }

  /* === VERTICAL TIMELINE === */
  .timeline-vert {
    position: relative;
    padding-left: 42px;
    list-style: none;
    margin-bottom: 0;
  }
  .timeline-vert::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: rgba(255, 255, 255, 0.12);
  }
  .timeline-item {
    position: relative;
    margin-bottom: 24px;
  }
  .timeline-item:last-child {
    margin-bottom: 0;
  }
  .timeline-icon {
    position: absolute;
    left: -42px;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
    z-index: 2;
    flex-shrink: 0;
    border: 1px solid transparent;
    transition: all 0.3s ease;
  }
  .timeline-icon.done {
    background: rgba(16, 185, 129, 0.22);
    border-color: rgba(52, 211, 153, 0.5);
    color: #6ee7b7;
    box-shadow: 0 0 12px rgba(16, 185, 129, 0.3);
  }
  .timeline-icon.current,
  .timeline-icon.warning,
  .timeline-icon.active {
    background: rgba(245, 158, 11, 0.22);
    border-color: rgba(251, 191, 36, 0.5);
    color: #fef08a;
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.35);
  }
  .timeline-icon.pending,
  .timeline-icon.waiting {
    background: rgba(255, 255, 255, 0.04);
    border-color: rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.35);
  }
  @keyframes timelinePulse {
    0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
    70% { box-shadow: 0 0 0 12px rgba(99, 102, 241, 0); }
    100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
  }
  .timeline-content {
    padding-left: 4px;
  }
  .timeline-content h6 {
    font-size: 0.88rem;
    font-weight: 700;
    margin-bottom: 3px;
    line-height: 1.3;
  }
  .timeline-content p {
    font-size: 0.78rem;
    line-height: 1.45;
    color: #94a3b8 !important;
    margin-bottom: 0;
  }
  /* === END VERTICAL TIMELINE === */

  /* Override container-p-y padding top khusus halaman ini */
  body .content-wrapper > .container-p-y {
    padding-top: 1.5rem !important; /* Disamakan persis dengan admin dashboard (1.5rem) */
  }

  /* Custom styles for announcements */
  .badge-pinned {
    background: linear-gradient(135deg, #ffd700, #b8860b);
    color: #0b0f19 !important;
    font-weight: 700;
  }
  .glass-card-pinned {
    border: 1px solid rgba(212, 175, 55, 0.4) !important;
    background: rgba(212, 175, 55, 0.05) !important;
    box-shadow: 0 20px 60px rgba(212, 175, 55, 0.1) !important;
  }
  .badge-privat {
    background: rgba(139, 92, 246, 0.15);
    border: 1px solid rgba(139, 92, 246, 0.3);
    color: #a78bfa !important;
  }
  .badge-publik {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399 !important;
  }

  /* === HERO CARD BADGES (HIGH CONTRAST & PREMIUM) === */
  .badge-hero-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 5px !important;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.04em;
    background: rgba(16, 185, 129, 0.18) !important;
    border: 1px solid rgba(52, 211, 153, 0.45) !important;
    color: #34d399 !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.15);
  }
  .badge-hero-batch {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 5px !important;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    background: rgba(99, 102, 241, 0.18) !important;
    border: 1px solid rgba(129, 140, 248, 0.45) !important;
    color: #c7d2fe !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.15);
  }
</style>
@endsection

@section('content')
  {{-- Popup Congratulations Wajib Chat WA (hanya untuk status waiting_wa_confirmation) --}}
  @if(auth()->user()->enrollments()->where('status', 'waiting_wa_confirmation')->whereNotNull('verification_code')->whereNull('wa_confirmed_at')->exists())
    <livewire:peserta.waiting-confirmation />
  @endif

  <!-- Floating Gradient Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Main Content container with z-index to sit on top of orbs -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    {{-- Welcoming Header --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="row align-items-center">
        <div class="col-12 col-lg-8">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="stat-icon-box stat-icon-primary">
              <i class="icon-base ti tabler-user-star fs-4"></i>
            </div>
            <div>
              <h4 class="fw-bold text-white mb-0">Selamat datang, <span class="text-gradient fw-extrabold">{{ optional($profile)->nama_lengkap ?? auth()->user()->name }}</span> 👋</h4>
              <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
                Terus semangat belajar dan tingkatkan skill kreatifmu!
              </p>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
          <div class="d-flex align-items-center gap-4 justify-content-lg-end">
            @if($data['enrollment'] && $data['enrollment']->status?->value === 'rejected')
              <div class="text-end">
                <span class="badge-premium badge-premium-danger">
                  <i class="icon-base ti tabler-circle-x"></i> Pendaftaran Ditolak
                </span>
              </div>
            @elseif($data['enrollment'] && $data['enrollment']->status?->value === 'waiting_newbimma_check')
              <div class="text-end">
                <span class="badge-premium badge-premium-info">
                  <span class="spinner-grow spinner-grow-sm" style="width: 8px; height: 8px;"></span> 🔄 Cek NewBimma
                </span>
              </div>
            @elseif($data['isProfileCompleted'] && $data['hasPelatihan'] && $data['enrollment'] && in_array($data['enrollment']->status?->value, ['approved', 'confirmed']))
              <div class="text-center">
                <p class="text-body-premium small mb-0">Kehadiran</p>
                <h5 class="text-white fw-bold mb-0">{{ $data['attendanceRate'] }}%</h5>
              </div>
              <div class="text-center">
                <p class="text-body-premium small mb-0">Sertifikat</p>
                <h5 class="text-white fw-bold mb-0">
                  @if($data['hasCertificate'])
                    <span class="text-success"><i class="icon-base ti tabler-certificate me-1"></i>Ada</span>
                  @else
                    <span class="text-muted"><i class="icon-base ti tabler-certificate-off me-1"></i>Belum</span>
                  @endif
                </h5>
              </div>
            @else
              <div class="text-center">
                <p class="text-body-premium small mb-0">Kelengkapan Profil</p>
                <h5 class="text-white fw-bold mb-0">{{ $data['profileCompletion'] }}%</h5>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Section Pengumuman Dashboard Peserta --}}
    @if(isset($announcements) && $announcements->count() > 0)
      <div class="mb-4">
        <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-bell text-primary"></i>
          Pengumuman Terbaru
        </h5>
        <div class="row g-3">
          @foreach($announcements as $announcement)
            <div class="col-12">
              <div class="glass-card-premium p-4 {{ $announcement->is_pinned ? 'glass-card-pinned' : '' }}">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                  <div class="d-flex align-items-center gap-2">
                    @if($announcement->is_pinned)
                      <span class="badge badge-pinned px-2.5 py-1 rounded fs-xs">
                        <i class="icon-base ti tabler-pin me-1"></i>PINNED
                      </span>
                    @endif
                    @if($announcement->is_private)
                      <span class="badge badge-privat px-2.5 py-1 rounded fs-xs">
                        <i class="icon-base ti tabler-lock me-1"></i>Khusus Peserta
                      </span>
                    @else
                      <span class="badge badge-publik px-2.5 py-1 rounded fs-xs">
                        <i class="icon-base ti tabler-world me-1"></i>Umum / Global
                      </span>
                    @endif
                  </div>
                  <div class="text-muted fs-xs">
                    <i class="icon-base ti tabler-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                  </div>
                </div>
                <h6 class="fw-bold mb-2 text-white" style="font-size: 1.1rem;">{{ $announcement->judul }}</h6>
                <div class="announcement-content text-white-50" style="font-size: 0.92rem; line-height: 1.6;">
                  {!! nl2br(e($announcement->konten)) !!}
                </div>
                @if($announcement->user)
                  <div class="mt-3 pt-3 border-top border-white-10 d-flex align-items-center gap-2 text-muted fs-xs" style="border-top: 1px solid rgba(255, 255, 255, 0.08) !important;">
                    <i class="icon-base ti tabler-user fs-sm"></i>
                    <span>Diposting oleh: <strong>{{ $announcement->user->name }}</strong></span>
                  </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- ============================================================
         STATE 1: Pendaftaran Belum Lengkap
         ============================================================ -->
    @if(!$data['isProfileCompleted'])
      <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-user text-primary"></i>
                Kelengkapan Profil &amp; Pendaftaran
              </h5>
              <span class="badge-premium badge-premium-warning">{{ $data['profileCompletion'] }}% Selesai</span>
            </div>

            <div class="mb-4">
              <p class="text-body-premium" style="font-size: 0.95rem;">
                Profil Anda belum lengkap. Silakan lengkapi data profil Anda terlebih dahulu melalui tahapan formulir pendaftaran untuk dapat memilih dan mengikuti pelatihan yang tersedia.
              </p>
            </div>

            <div class="mb-4">
              <div class="progress progress-dark-premium" style="height: 12px;">
                <div class="progress-bar" style="width: {{ $data['profileCompletion'] }}%;"></div>
              </div>
            </div>

            <hr class="dark-premium my-4">

            <h6 class="text-white fw-semibold mb-3">Tahapan Pendaftaran:</h6>
            <div class="row g-3 mb-4">
              {{-- Tahap 1: Data Pribadi & Alamat --}}
              @php
                $step1Done = !empty($profile->nama_lengkap) && !empty($profile->nik);
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step1Done ? 'success' : 'secondary' }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step1Done ? 'check' : 'user' }}"></i>
                  </div>
                  <div>
                    <a href="{{ route('dashboard.peserta.form-pendaftaran') }}" class="text-white fw-semibold text-decoration-none hover-text-primary" style="font-size: 0.9rem;">1. Data Pribadi</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step1Done ? 'Sudah Diisi' : 'Belum Lengkap' }}
                    </p>
                  </div>
                </div>
              </div>

              {{-- Tahap 2: Alamat & Kontak --}}
              @php
                $step2Done = !empty($profile->alamat_ktp) && !empty($profile->whatsapp);
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step2Done ? 'success' : 'secondary' }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step2Done ? 'check' : 'map-pin' }}"></i>
                  </div>
                  <div>
                    <a href="{{ $step1Done ? route('dashboard.peserta.form-alamat') : 'javascript:void(0);' }}" 
                       class="text-white fw-semibold text-decoration-none {{ !$step1Done ? 'text-muted' : 'hover-text-primary' }}" 
                       style="font-size: 0.9rem; @if(!$step1Done) cursor: not-allowed; opacity: 0.5; @endif">2. Alamat &amp; Kontak</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step2Done ? 'Sudah Diisi' : 'Belum Lengkap' }}
                    </p>
                  </div>
                </div>
              </div>

              {{-- Tahap 3: Pendidikan --}}
              @php
                $step3Done = !empty($profile->pendidikan_terakhir) && !empty($profile->nama_institusi);
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step3Done ? 'success' : 'secondary' }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step3Done ? 'check' : 'school' }}"></i>
                  </div>
                  <div>
                    <a href="{{ $step2Done ? route('dashboard.peserta.form-pendidikan') : 'javascript:void(0);' }}" 
                       class="text-white fw-semibold text-decoration-none {{ !$step2Done ? 'text-muted' : 'hover-text-primary' }}" 
                       style="font-size: 0.9rem; @if(!$step2Done) cursor: not-allowed; opacity: 0.5; @endif">3. Riwayat Pendidikan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step3Done ? 'Sudah Diisi' : 'Belum Lengkap' }}
                    </p>
                  </div>
                </div>
              </div>

              {{-- Tahap 4: Minat Pelatihan --}}
              @php
                $step4Done = !empty($profile->pelatihan_id);
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step4Done ? 'success' : 'secondary' }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step4Done ? 'check' : 'heart' }}"></i>
                  </div>
                  <div>
                    <a href="{{ $step3Done ? route('dashboard.peserta.form-minat') : 'javascript:void(0);' }}" 
                       class="text-white fw-semibold text-decoration-none {{ !$step3Done ? 'text-muted' : 'hover-text-primary' }}" 
                       style="font-size: 0.9rem; @if(!$step3Done) cursor: not-allowed; opacity: 0.5; @endif">4. Pilihan Pelatihan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step4Done ? 'Sudah Diisi' : 'Belum Lengkap' }}
                    </p>
                  </div>
                </div>
              </div>

              {{-- Tahap 5: Dokumen & Pertanyaan --}}
              @php
                $step5Done = !empty($profile->jawaban_pertanyaan);
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step5Done ? 'success' : 'secondary' }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step5Done ? 'check' : 'file-check' }}"></i>
                  </div>
                  <div>
                    <a href="{{ $step4Done ? route('dashboard.peserta.form-dokumen') : 'javascript:void(0);' }}" 
                       class="text-white fw-semibold text-decoration-none {{ !$step4Done ? 'text-muted' : 'hover-text-primary' }}" 
                       style="font-size: 0.9rem; @if(!$step4Done) cursor: not-allowed; opacity: 0.5; @endif">5. Dokumen &amp; Pertanyaan</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step5Done ? 'Sudah Diisi' : 'Belum Lengkap' }}
                    </p>
                  </div>
                </div>
              </div>

              {{-- Tahap 6: Review & Kirim --}}
              @php
                $step6Done = $profile && $profile->is_completed;
              @endphp
              <div class="col-12 col-md-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255,255,255,0.04);">
                  <div class="stat-icon-box stat-icon-{{ $step6Done ? 'success' : ($step5Done ? 'warning' : 'secondary') }}" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="icon-base ti tabler-{{ $step6Done ? 'check' : 'send' }}"></i>
                  </div>
                  <div>
                    <a href="{{ $step5Done ? route('dashboard.peserta.form-review') : 'javascript:void(0);' }}" 
                       class="text-white fw-semibold text-decoration-none {{ !$step5Done ? 'text-muted' : 'hover-text-primary' }}" 
                       style="font-size: 0.9rem; @if(!$step5Done) cursor: not-allowed; opacity: 0.5; @endif">6. Review &amp; Kirim</a>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.75rem;">
                      {{ $step6Done ? 'Sudah Dikirim' : ($step5Done ? 'Siap Dikirim' : 'Belum Lengkap') }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              @php
                $nextRoute = route('dashboard.peserta.form-pendaftaran');
                if ($step5Done) {
                    $nextRoute = route('dashboard.peserta.form-review');
                } elseif ($step4Done) {
                    $nextRoute = route('dashboard.peserta.form-dokumen');
                } elseif ($step3Done) {
                    $nextRoute = route('dashboard.peserta.form-minat');
                } elseif ($step2Done) {
                    $nextRoute = route('dashboard.peserta.form-pendidikan');
                } elseif ($step1Done) {
                    $nextRoute = route('dashboard.peserta.form-alamat');
                }
              @endphp
              <a href="{{ $nextRoute }}" class="btn btn-glow-premium px-5 py-2 fw-bold text-uppercase" style="letter-spacing: 0.05em;">
                <i class="icon-base ti tabler-player-play me-1"></i> Mulai / Lanjutkan Pengisian
              </a>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-help-circle text-info"></i>
              Butuh Bantuan?
            </h5>
            <p class="text-body-premium mb-4" style="font-size: 0.9rem; line-height: 1.5;">
              Jika Anda mengalami kesulitan saat mengisi formulir pendaftaran atau membutuhkan informasi lebih lanjut mengenai pelatihan kerja, silakan hubungi kami.
            </p>
            
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="stat-icon-box stat-icon-success" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-brand-whatsapp"></i>
              </div>
              <div>
                <span class="info-label d-block">WhatsApp Service</span>
                <a href="https://wa.me/{{ $data['whatsapp_sender'] }}" target="_blank" class="text-white fw-bold text-decoration-none hover-text-primary">Hubungi Admin</a>
              </div>
            </div>
            
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-primary" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-info-circle"></i>
              </div>
              <div>
                <span class="info-label d-block">FAQ &amp; Panduan</span>
                <a href="{{ url('/#faq') }}" target="_blank" class="text-white fw-bold text-decoration-none hover-text-primary">Lihat Tanya Jawab</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- ============================================================
         STATE REJECTED: Pendaftaran Ditolak
         ============================================================ -->
    @elseif($data['enrollment'] && $data['enrollment']->status?->value === 'rejected')
      <div class="row g-4 mb-4">
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100" style="border-color: rgba(248, 113, 113, 0.3) !important; background: radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.08) 0px, transparent 60%), rgba(15, 23, 42, 0.35) !important;">
            <div class="text-center py-4">
              <div class="stat-icon-box stat-icon-danger mx-auto mb-3" style="width: 68px; height: 68px; font-size: 2.2rem; border-radius: 50% !important; background: rgba(248, 113, 113, 0.15) !important; color: #f87171 !important; box-shadow: 0 0 30px rgba(248, 113, 113, 0.25);">
                <i class="icon-base ti tabler-circle-x fs-1"></i>
              </div>
              <h4 class="fw-bold text-white mb-2">Pendaftaran Belum Dapat Disetujui</h4>
              <p class="text-body-premium mx-auto mb-4" style="max-width: 550px; font-size: 0.95rem; line-height: 1.6;">
                Mohon maaf, permohonan pendaftaran Anda untuk pelatihan ini tidak lolos tahap verifikasi berkas atau kuota penerimaan telah terpenuhi.
              </p>

              <div class="p-4 mx-auto text-start mb-4" style="max-width: 580px; background: rgba(248, 113, 113, 0.08); border: 1px solid rgba(248, 113, 113, 0.25); border-radius: 5px !important;">
                <div class="d-flex align-items-start gap-3">
                  <i class="icon-base ti tabler-alert-circle fs-3 text-danger mt-1 flex-shrink-0"></i>
                  <div>
                    <span class="text-danger fw-bold d-block mb-1" style="font-size: 0.9rem;">Catatan &amp; Alasan Penolakan:</span>
                    <p class="text-white mb-0" style="font-size: 0.9rem; line-height: 1.5;">
                      {{ $data['enrollment']->notes ?? $data['enrollment']->newbimma_result ?? 'Data peserta belum memenuhi kriteria kualifikasi atau pernah terdaftar pada pelatihan sejenis di sistem NewBimma.' }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('dashboard.peserta.form-minat') }}" class="btn btn-glow-premium px-4 py-2" style="background: linear-gradient(135deg, #ef4444, #f97316) !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3) !important;">
                  <i class="icon-base ti tabler-refresh me-2"></i>Pilih Pelatihan Lain
                </a>
                <a href="{{ route('dashboard.peserta.status') }}" class="btn btn-outline-glass px-4 py-2">
                  <i class="icon-base ti tabler-timeline me-2"></i>Lihat Riwayat Lengkap
                </a>
              </div>
            </div>

            @if($data['pelatihan'])
              <hr class="dark-premium my-4">
              <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-book text-muted"></i>
                Detail Pelatihan Terkait
              </h5>
              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <span class="info-label d-block">Nama Pelatihan</span>
                    <span class="info-value fw-bold text-white">{{ $data['pelatihan']->nama }}</span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Batch</span>
                    <span class="info-value text-white">{{ $data['pelatihan']->batch }}</span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Dinas Penyelenggara</span>
                    <span class="info-value text-white">{{ $data['pelatihan']->dinas->nama_dinas ?? '-' }}</span>
                  </div>
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-help-circle text-info"></i>
              Bantuan &amp; Solusi
            </h5>
            <div class="d-flex flex-column gap-3">
              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <span class="text-white fw-semibold small d-block mb-1">
                  <i class="icon-base ti tabler-bulb text-warning me-1"></i> Jangan Berkecil Hati
                </span>
                <small class="text-body-premium d-block" style="line-height: 1.5;">
                  Anda masih berkesempatan mendaftar pada kejuruan lain atau batch selanjutnya yang masih membuka kuota penerimaan.
                </small>
              </div>

              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <span class="text-white fw-semibold small d-block mb-1">
                  <i class="icon-base ti tabler-brand-whatsapp text-success me-1"></i> Klarifikasi Data NIK / Berkas
                </span>
                <small class="text-body-premium d-block mb-2" style="line-height: 1.5;">
                  Jika Anda merasa ada kekeliruan pencocokan data di sistem NewBimma, silakan hubungi tim Admin.
                </small>
                @php
                  $waTolakMsg = "Halo Admin, saya ingin menanyakan terkait penolakan pendaftaran saya pada pelatihan " . ($data['pelatihan']->nama ?? '-') . ". NIK: " . ($profile->nik ?? auth()->user()->nik ?? '-');
                @endphp
                <a href="https://wa.me/{{ $data['whatsapp_sender'] }}?text={{ urlencode($waTolakMsg) }}" target="_blank" class="btn btn-sm btn-outline-success w-100 py-2">
                  <i class="icon-base ti tabler-brand-whatsapp me-1"></i> Chat Helpdesk Admin
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

    <!-- ============================================================
         STATE 2: Pendaftaran Selesai, Menunggu Verifikasi / Cadangan / Konfirmasi WA
         ============================================================ -->
    @elseif(!$data['enrollment'] || in_array($data['enrollment']->status?->value, ['pending', 'waitlist', 'waiting_wa_confirmation']))
      @php
        $enrollment = $data['enrollment'];
        $pelatihan = $data['pelatihan'];
        $isWaitlist = $enrollment && $enrollment->status?->value === 'waitlist';
        $isWaitingWa = $enrollment && $enrollment->status?->value === 'waiting_wa_confirmation';
        
        $statusTitle = 'MENUNGGU VERIFIKASI BERKAS';
        if ($isWaitlist) {
            $statusTitle = 'STATUS: CADANGAN (WAITLIST)';
        } elseif ($isWaitingWa) {
            $statusTitle = 'MENUNGGU KONFIRMASI WHATSAPP';
        }
        
        $tglDaftar = $enrollment?->created_at ?? ($profile?->created_at ?? now());
        $whatsappSender = $data['whatsapp_sender'] ?? '62888888888';
        $waNama = $profile->nama_lengkap ?? auth()->user()->name ?? '-';
        $waPelatihan = $pelatihan->nama ?? '-';
        $waMsg = "Halo Admin, saya telah mendaftar pelatihan {$waPelatihan}.\nNama: {$waNama}\nNIK: " . ($profile->nik ?? auth()->user()->nik ?? '-') . "\nMohon info terkait verifikasi pendaftaran saya.";
      @endphp

      {{-- HERO CARD: Status Pending / Menunggu Verifikasi --}}
      <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4 position-relative overflow-hidden" 
           style="background: radial-gradient(at 0% 0%, rgba(245, 158, 11, 0.12) 0px, transparent 65%), rgba(15, 23, 42, 0.5) !important; border: 1px solid rgba(251, 191, 36, 0.3) !important; border-radius: 5px !important;">
        
        <div class="row align-items-center g-4">
          <div class="col-12 col-lg-8">
            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
              {{-- Badge Status Utama --}}
              <span class="badge-hero-status" style="background: rgba(245, 158, 11, 0.22) !important; border-color: rgba(251, 191, 36, 0.5) !important; color: #fef08a !important; text-shadow: 0 0 12px rgba(251, 191, 36, 0.4);">
                <span class="spinner-grow spinner-grow-sm me-1" role="status" style="width: 8px; height: 8px;"></span>
                {{ $statusTitle }}
              </span>

              {{-- Badge Batch --}}
              @if($pelatihan && $pelatihan->batch)
                <span class="badge-hero-batch">
                  <i class="icon-base ti tabler-tag me-1"></i> BATCH {{ $pelatihan->batch }}
                </span>
              @endif

              {{-- Badge Dinas --}}
              @if($pelatihan && $pelatihan->dinas)
                <span class="badge-hero-batch" style="background: rgba(99, 102, 241, 0.2) !important; border-color: rgba(129, 140, 248, 0.4) !important; color: #c7d2fe !important;">
                  <i class="icon-base ti tabler-building me-1"></i> {{ $pelatihan->dinas->nama_dinas }}
                </span>
              @endif
            </div>

            <h3 class="fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif; letter-spacing: -0.02em;">
              {{ $pelatihan->nama ?? 'Pendaftaran Pelatihan Kerja' }}
            </h3>

            <p class="text-body-premium mb-3" style="font-size: 0.95rem; line-height: 1.6; max-width: 680px;">
              Data formulir Anda telah berhasil kami terima. Saat ini berkas administrasi dan kualifikasi Anda sedang dalam tahap peninjauan oleh tim verifikator dinas.
            </p>

            <div class="d-flex align-items-center gap-4 flex-wrap text-white-50" style="font-size: 0.85rem;">
              <div>
                <span class="info-label d-inline me-1">Pendaftar:</span>
                <strong class="text-white">{{ $profile->nama_lengkap ?? auth()->user()->name }}</strong>
              </div>
              <div>
                <span class="info-label d-inline me-1">NIK:</span>
                <span class="text-white" style="font-family: 'Fira Code', monospace; color: #fde047 !important;">{{ $profile->nik ?? auth()->user()->nik ?? '-' }}</span>
              </div>
              <div>
                <span class="info-label d-inline me-1">Diajukan:</span>
                <span class="text-white">{{ $tglDaftar->format('d M Y, H:i') }} WIB</span>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 text-lg-end">
            <div class="d-flex flex-column gap-2 justify-content-lg-end">
              <a href="{{ route('dashboard.peserta.status') }}" class="btn btn-glow-premium py-2 px-4">
                <i class="icon-base ti tabler-file-text me-1"></i> Bukti &amp; Riwayat Pendaftaran
              </a>
              <a href="https://wa.me/{{ $whatsappSender }}?text={{ urlencode($waMsg) }}" target="_blank" class="btn btn-outline-glass py-2 px-4 text-white">
                <i class="icon-base ti tabler-brand-whatsapp text-success me-1"></i> Konfirmasi ke Admin
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- ROW KONTEN DETAIL: Kiri (Detail & Estimasi SLA) | Kanan (Tahapan Seleksi Tracker) --}}
      <div class="row g-4 mb-4">
        {{-- Kiri: Detail & Estimasi Waktu Verifikasi --}}
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium p-4 p-xl-4 h-100">
            
            {{-- Box Estimasi Waktu / SLA --}}
            <div class="p-3.5 mb-4" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.03) 100%); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 5px !important;">
              <div class="d-flex align-items-start gap-3">
                <div class="stat-icon-box stat-icon-warning flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.3rem; border-radius: 5px !important; background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(251, 191, 36, 0.4); color: #fde047;">
                  <i class="icon-base ti tabler-info-circle"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="text-white fw-bold mb-1" style="font-size: 0.94rem; letter-spacing: -0.01em;">
                    Estimasi Waktu Verifikasi Berkas (1–3 Hari Kerja)
                  </h6>
                  <p class="text-body-premium mb-0" style="font-size: 0.85rem; line-height: 1.6; color: rgba(255, 255, 255, 0.8) !important;">
                    Tim panitia sedang mencocokkan kelengkapan foto KTP, kesesuaian domisili, dan riwayat pelatihan di NewBimma. Status terbaru akan selalu diperbarui di dashboard ini dan notifikasi akan dikirimkan langsung ke nomor WhatsApp Anda.
                  </p>
                </div>
              </div>
            </div>

            @if($isWaitlist && $enrollment->notes)
              <div class="p-3 mb-4" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 5px !important;">
                <span class="text-warning fw-bold d-block mb-1 small"><i class="icon-base ti tabler-note me-1"></i>Catatan Tambahan Admin:</span>
                <p class="text-white mb-0 small">{{ $enrollment->notes }}</p>
              </div>
            @endif

            <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-list-details text-primary"></i>
              Rincian Pendaftaran yang Diajukan
            </h5>

            @if($pelatihan)
              <div class="p-3 mb-3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 5px !important;">
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <span class="info-label d-block">Nama Kejuruan / Program</span>
                    <span class="info-value text-white fw-bold">{{ $pelatihan->nama }}</span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Batch Pelatihan</span>
                    <span class="info-value text-white">{{ $pelatihan->batch }}</span>
                  </div>
                  <div class="col-6 col-md-3">
                    <span class="info-label d-block">Dinas Penyelenggara</span>
                    <span class="info-value text-white">{{ $pelatihan->dinas->nama_dinas ?? '-' }}</span>
                  </div>
                  <div class="col-12 col-md-6">
                    <span class="info-label d-block">Rencana Pelaksanaan</span>
                    <span class="info-value text-white">
                      @if($pelatihan->tanggal_mulai)
                        {{ $pelatihan->tanggal_mulai->format('d M Y') }} s/d {{ $pelatihan->tanggal_selesai ? $pelatihan->tanggal_selesai->format('d M Y') : '-' }}
                      @else
                        Akan segera diumumkan panitia
                      @endif
                    </span>
                  </div>
                  <div class="col-12 col-md-6">
                    <span class="info-label d-block">Nomor WhatsApp Terdaftar</span>
                    <span class="info-value text-white" style="font-family: 'Fira Code', monospace;">{{ $profile->whatsapp ?? auth()->user()->whatsapp ?? '-' }}</span>
                  </div>
                </div>
              </div>
            @endif

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2">
              <span class="text-body-premium small">
                Ingin mengecek atau memperbaiki dokumen yang diunggah?
              </span>
              <a href="{{ route('dashboard.peserta.status') }}" class="text-white fw-semibold small text-decoration-none hover-text-primary">
                Buka Halaman Biodata &amp; Dokumen <i class="icon-base ti tabler-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>

        {{-- Kanan: Alur Seleksi 4 Tahap --}}
        <div class="col-12 col-xl-4">
          <div class="glass-card-premium p-4 p-xl-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-route text-info"></i>
              Tahapan Seleksi Anda
            </h5>
            
            <ul class="timeline-vert mb-0">
              {{-- Tahap 1: Pengisian Form & Kirim --}}
              <li class="timeline-item">
                <div class="timeline-icon done">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white mb-1">1. Form &amp; Berkas Terkirim</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.78rem;">
                    Seluruh biodata &amp; berkas telah masuk ke sistem.
                  </p>
                </div>
              </li>

              {{-- Tahap 2: Verifikasi Berkas (Active) --}}
              <li class="timeline-item">
                <div class="timeline-icon current">
                  <span class="spinner-border spinner-border-sm" role="status" style="width: 12px; height: 12px; color: #fef08a;"></span>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-warning mb-1">2. Verifikasi Berkas &amp; Kuota</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.78rem;">
                    Admin memeriksa keabsahan KTP/KK &amp; kuota kelas.
                  </p>
                </div>
              </li>

              {{-- Tahap 3: Pengecekan NewBimma --}}
              <li class="timeline-item">
                <div class="timeline-icon pending">
                  <i class="icon-base ti tabler-search"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white-50 mb-1">3. Pengecekan NewBimma</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.78rem;">
                    Sinkronisasi data ke database pusat pelatihan.
                  </p>
                </div>
              </li>

              {{-- Tahap 4: Penetapan Peserta & Kelas --}}
              <li class="timeline-item">
                <div class="timeline-icon pending">
                  <i class="icon-base ti tabler-award"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white-50 mb-1">4. Penetapan Peserta Resmi</h6>
                  <p class="text-body-premium mb-0 small" style="font-size: 0.78rem;">
                    Penerbitan QR Presensi &amp; undangan kelas pelatihan.
                  </p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

    <!-- ============================================================
         STATE 4: Cek Newbimma — Menunggu pengecekan admin
         ============================================================ -->
    @elseif($data['enrollment'] && $data['enrollment']->status?->value === 'waiting_newbimma_check')
      <div class="row g-4 mb-4">
        {{-- Kiri: Status & Timeline --}}
        <div class="col-12 col-xl-8">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            {{-- Header Status --}}
            <div class="text-center py-4">
              <div class="stat-icon-box stat-icon-info mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem; border-radius: 50% !important; background: rgba(59,130,246,0.12) !important; color: #60a5fa !important;">
                <i class="icon-base ti tabler-search fs-1"></i>
              </div>
              <h4 class="fw-bold text-white mb-2">Status: 🔄 Cek Newbimma</h4>
              <p class="text-body-premium mx-auto" style="max-width: 550px; font-size: 0.95rem; line-height: 1.6;">
                Pendaftaran Anda telah disetujui dan terkonfirmasi. Saat ini data Anda sedang dalam proses pengecekan Newbimma oleh Admin/Dinas penyelenggara.
              </p>
              <div class="d-inline-flex align-items-center gap-2 px-3 py-2 mt-3" style="border-radius: 5px !important; border: 1px solid rgba(59,130,246,0.3); background: rgba(59,130,246,0.15); color: #60a5fa;">
                <span class="spinner-grow spinner-grow-sm" role="status" style="width: 10px; height: 10px; color: #60a5fa;"></span>
                <span class="fw-semibold small text-uppercase" style="letter-spacing: 0.05em; color: #60a5fa;">🔄 Cek Newbimma</span>
              </div>
              @if($data['elapsedTime'] ?? null)
                <p class="text-body-premium mt-3 mb-0" style="font-size: 0.85rem;">
                  <i class="icon-base ti tabler-clock me-1"></i> Menunggu pengecekan sejak {{ $data['elapsedTime'] }}
                </p>
              @else
                <p class="text-body-premium mt-3 mb-0" style="font-size: 0.85rem;">
                  <i class="icon-base ti tabler-clock me-1"></i> Segera diperiksa
                </p>
              @endif
            </div>

            <hr class="dark-premium my-4">

            {{-- Timeline Vertikal --}}
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-timeline text-primary"></i>
              Alur Seleksi
            </h5>

            <ul class="timeline-vert">
              {{-- Langkah 1: Pendaftaran Disetujui --}}
              <li class="timeline-item">
                <div class="timeline-icon done">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">✅ Pendaftaran Disetujui</h6>
                  <p class="text-body-premium">
                    Pendaftaran Anda telah diverifikasi dan disetujui oleh Admin.
                    @if($data['approvedAt'] ?? null)
                      <br><span class="text-white-50" style="font-size: 0.75rem;">
                        <i class="icon-base ti tabler-clock me-1"></i>{{ $data['approvedAt']->format('d M Y H:i') }}
                      </span>
                    @endif
                  </p>
                </div>
              </li>

              {{-- Langkah 2: Konfirmasi WA --}}
              <li class="timeline-item">
                <div class="timeline-icon done">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">✅ Konfirmasi WA</h6>
                  <p class="text-body-premium">
                    Anda telah mengkonfirmasi pendaftaran melalui WhatsApp.
                    @if($data['waConfirmedAt'] ?? null)
                      <br><span class="text-white-50" style="font-size: 0.75rem;">
                        <i class="icon-base ti tabler-clock me-1"></i>{{ $data['waConfirmedAt']->format('d M Y H:i') }}
                      </span>
                    @endif
                  </p>
                </div>
              </li>

              {{-- Langkah 3: Cek Newbimma (active) --}}
              <li class="timeline-item">
                <div class="timeline-icon active">
                  <i class="icon-base ti tabler-search"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="fw-bold text-white">🔄 Cek Newbimma</h6>
                  <p class="text-body-premium">
                    Data Anda sedang diperiksa oleh Admin melalui sistem Newbimma.
                    <br><span class="fw-semibold" style="font-size: 0.78rem; color: #818cf8;">SEDANG DIPROSES</span>
                  </p>
                </div>
              </li>

              {{-- Langkah 4: Hasil Seleksi (waiting) --}}
              <li class="timeline-item">
                <div class="timeline-icon waiting">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div class="timeline-content">
                  <h6 class="text-white-50 fw-bold">⏳ Hasil Seleksi</h6>
                  <p class="text-body-premium">
                    Menunggu hasil pengecekan Newbimma dari Admin.
                  </p>
                </div>
              </li>
            </ul>

            {{-- Link ke halaman Status Pendaftaran --}}
            <hr class="dark-premium my-4">
            <div class="text-center">
              <a href="{{ route('dashboard.peserta.status') }}" class="btn btn-outline-glass px-4 py-2 fw-semibold" style="border-radius: 5px; font-size: 0.85rem;">
                <i class="icon-base ti tabler-external-link me-1"></i> Lihat Status Lengkap <span aria-hidden="true">&rarr;</span>
              </a>
            </div>
          </div>
        </div>

        {{-- Kanan: Info Pelatihan + Butuh Bantuan + Profil --}}
        <div class="col-12 col-xl-4 d-flex flex-column gap-4">

          {{-- Card Info Pelatihan --}}
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-book text-success"></i>
              Info Pelatihan
            </h5>

            @if($data['pelatihan'])
              <div class="p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
                <div class="row g-3">
                  <div class="col-12">
                    <span class="info-label d-block">Nama Pelatihan</span>
                    <span class="info-value fw-bold text-white">{{ $data['pelatihan']->nama }}</span>
                  </div>
                  <div class="col-6">
                    <span class="info-label d-block">Batch</span>
                    <span class="info-value text-white">{{ $data['pelatihan']->batch }}</span>
                  </div>
                  <div class="col-6">
                    <span class="info-label d-block">Dinas Penyelenggara</span>
                    <span class="info-value text-white">{{ $data['pelatihan']->dinas->nama_dinas ?? '-' }}</span>
                  </div>
                  <div class="col-12">
                    <span class="info-label d-block">Jadwal Pelaksanaan</span>
                    <span class="info-value text-white">
                      @if($data['pelatihan']->tanggal_mulai)
                        {{ $data['pelatihan']->tanggal_mulai->format('d M Y') }}
                        @if($data['pelatihan']->tanggal_selesai)
                          — {{ $data['pelatihan']->tanggal_selesai->format('d M Y') }}
                        @endif
                      @else
                        Akan segera diumumkan
                      @endif
                    </span>
                  </div>
                </div>
              </div>
            @else
              <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
                <i class="icon-base ti tabler-book-off fs-2 text-muted mb-2 d-block"></i>
                <span class="text-body-premium small">Belum ada data pelatihan.</span>
              </div>
            @endif
          </div>

          {{-- Card Butuh Bantuan / Hubungi Admin --}}
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-headset text-info"></i>
              💬 Butuh Bantuan?
            </h5>
            <p class="text-body-premium mb-4" style="font-size: 0.9rem; line-height: 1.5;">
              Jika Anda memiliki pertanyaan seputar proses pengecekan Newbimma, silakan hubungi Admin melalui WhatsApp.
            </p>

            @php
              $waNamaPeserta = optional($profile)->nama_lengkap ?? auth()->user()->name ?? '-';
              $waNikPeserta = optional($profile)->nik ?? '-';
              $waMessage = "Halo Admin, saya ingin menanyakan status pengecekan Newbimma saya. Nama: {$waNamaPeserta}, NIK: {$waNikPeserta}";
              $waNumber = $data['whatsapp_sender'] ?? \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
            @endphp
            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($waMessage) }}"
               target="_blank"
               class="btn btn-glow-premium w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
              <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
              Hubungi Admin via WhatsApp
            </a>
          </div>

          {{-- Card Profil Peserta --}}
          <div class="glass-card-premium px-4 px-xl-5 py-4">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-user-circle text-info"></i>
              Profil Peserta
            </h5>
            <div class="row g-3">
              <div class="col-12">
                <span class="info-label d-block">Nama Lengkap</span>
                <span class="info-value text-white">{{ optional($profile)->nama_lengkap ?? auth()->user()->name ?? '-' }}</span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">NIK</span>
                <span class="info-value text-white" style="font-family: monospace;">{{ optional($profile)->nik ?? '-' }}</span>
              </div>
              <div class="col-6">
                <span class="info-label d-block">WhatsApp</span>
                <span class="info-value text-white">{{ optional($profile)->whatsapp ?? '-' }}</span>
              </div>
            </div>
          </div>

        </div>
      </div>

    <!-- ============================================================
         STATE 3: Pelatihan Aktif / Diterima (Confirmed & Approved)
         ============================================================ -->
    @else
      {{-- HERO CARD: Status Penerimaan & Info Kelas --}}
      <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4" style="background: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.12) 0px, transparent 60%), rgba(15, 23, 42, 0.35) !important; border-color: rgba(16, 185, 129, 0.3) !important;">
        <div class="row align-items-center g-3">
          <div class="col-12 col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
              <span class="badge-hero-status">
                <i class="icon-base ti tabler-circle-check"></i> RESMI TERDAFTAR SEBAGAI PESERTA
              </span>
              @if($data['pelatihan'] && $data['pelatihan']->batch)
                <span class="badge-hero-batch">
                  <i class="icon-base ti tabler-tag"></i> {{ $data['pelatihan']->batch }}
                </span>
              @endif
            </div>
            <h3 class="fw-bold text-white mb-2">{{ $data['pelatihan']->nama ?? 'Pelatihan Unggulan' }}</h3>
            <p class="text-body-premium mb-0" style="font-size: 0.92rem;">
              Penyelenggara: <strong class="text-white">{{ $data['pelatihan']->dinas->nama_dinas ?? '-' }}</strong>
              @if($data['pelatihan'] && $data['pelatihan']->tanggal_mulai)
                &bull; Pelaksanaan: <span class="text-white fw-semibold">{{ $data['pelatihan']->tanggal_mulai->format('d M Y') }} s/d {{ $data['pelatihan']->tanggal_selesai ? $data['pelatihan']->tanggal_selesai->format('d M Y') : '-' }}</span>
              @endif
            </p>
          </div>
          <div class="col-12 col-lg-4 text-lg-end">
            <div class="d-flex gap-2 justify-content-lg-end flex-wrap">
              @php
                $waGrupMsg = "Halo Admin, saya peserta terdaftar pelatihan " . ($data['pelatihan']->nama ?? '-') . " (NIK: " . (optional($profile)->nik ?? auth()->user()->nik ?? '-') . "). Mohon info grup WhatsApp kelas / koordinasi pelatihan.";
              @endphp
              <a href="https://wa.me/{{ $data['whatsapp_sender'] }}?text={{ urlencode($waGrupMsg) }}" target="_blank" class="btn btn-glow-premium px-3 py-2" style="font-size: 0.85rem;">
                <i class="icon-base ti tabler-brand-whatsapp me-1.5"></i> Hubungi Panitia / Grup WA
              </a>
              <a href="{{ route('dashboard.peserta.status') }}" class="btn btn-outline-glass px-3 py-2" style="font-size: 0.85rem;">
                <i class="icon-base ti tabler-file-text me-1.5"></i> Bukti Pendaftaran
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        {{-- Left: Progress & Absensi --}}
        <div class="col-12 col-xl-8">
          {{-- KARTU QR CODE PRESENSI DINAMIS --}}
          <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-qrcode text-primary"></i>
                Presensi QR Code
              </h5>
              @if($data['enrollment']->status?->value === 'confirmed')
                <span class="badge-premium badge-premium-success">Terverifikasi</span>
              @else
                <span class="badge-premium badge-premium-warning">Menunggu</span>
              @endif
            </div>

            @if($data['enrollment']->status?->value === 'confirmed')
              <div class="text-center py-3">
                <p class="text-body-premium mb-4" style="font-size: 0.9rem; line-height: 1.5;">
                  Tunjukkan QR Code dinamis ini kepada Panitia di lokasi pelatihan untuk melakukan check-in kehadiran.
                </p>
                <div id="qr-display-section" class="d-none">
                  <div class="d-inline-block p-3 rounded mb-3 bg-white" style="box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <div id="qrcode-canvas" style="width: 200px; height: 200px; margin: 0 auto;"></div>
                  </div>
                  <div class="mx-auto" style="max-width: 250px;">
                    <div class="progress progress-dark-premium mb-2" style="height: 6px;">
                      <div id="qr-countdown-bar" class="progress-bar" style="width: 100%; transition: width 0.1s linear;"></div>
                    </div>
                    <small class="text-body-premium d-block mb-3" style="font-size: 0.75rem;">
                      QR Code diperbarui dalam <span id="qr-countdown-text" class="text-white fw-semibold">20</span> detik
                    </small>
                  </div>
                </div>
                <button type="button" id="btn-toggle-qr" class="btn btn-glow-premium px-4 py-2">
                  <i class="icon-base ti tabler-qrcode me-2"></i>Tampilkan QR Presensi
                </button>
              </div>
            @else
              <div class="text-center py-4 rounded border border-white border-opacity-5" style="background: rgba(255, 255, 255, 0.05);">
                <i class="icon-base ti tabler-alert-triangle fs-2 text-warning mb-2 d-block"></i>
                <span class="text-white fw-semibold d-block mb-1">Pendaftaran Sedang Diverifikasi</span>
                <p class="text-body-premium mb-0 small px-3" style="font-size: 0.8rem; line-height: 1.4;">
                  Status pendaftaran Anda saat ini adalah <strong class="text-white">{{ $data['enrollment']->status?->value }}</strong>. QR Code presensi hanya tersedia jika pendaftaran telah dikonfirmasi dan diverifikasi WhatsApp/NewBimma.
                </p>
              </div>
            @endif
          </div>

          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-trending-up text-primary"></i>
                Progress &amp; Kehadiran Pelatihan
              </h5>
              <span class="badge-premium {{ ($data['pelatihan'] && $data['pelatihan']->tanggal_selesai && now()->gt($data['pelatihan']->tanggal_selesai)) ? 'badge-premium-info' : 'badge-premium-success' }}">
                {{ ($data['pelatihan'] && $data['pelatihan']->tanggal_selesai && now()->gt($data['pelatihan']->tanggal_selesai)) ? 'Selesai' : 'Aktif' }}
              </span>
            </div>

            <div class="mb-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.95rem;">{{ $data['pelatihan'] ? $data['pelatihan']->nama : 'Data pelatihan tidak tersedia' }}</h6>
                  <small class="text-body-premium">Penyelenggara: {{ $data['pelatihan'] ? ($data['pelatihan']->dinas->nama_dinas ?? '-') : '-' }}</small>
                </div>
                <span class="text-white fw-bold small">{{ $data['attendanceRate'] }}% Kehadiran</span>
              </div>
              <div class="progress progress-dark-premium" style="height: 10px;">
                <div class="progress-bar" style="width: {{ $data['attendanceRate'] }}%;"></div>
              </div>
            </div>

            <hr class="dark-premium my-4">

            <h6 class="text-white fw-semibold mb-3">Daftar Pertemuan &amp; Absensi:</h6>
            @if($data['enrollment']->attendances && $data['enrollment']->attendances->count() > 0)
              <div class="row g-3">
                @foreach($data['enrollment']->attendances->sortBy('pertemuan_ke') as $att)
                  <div class="col-12 col-sm-6">
                    <div class="d-flex align-items-center justify-content-between p-3" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255,255,255,0.06); border-radius: 5px;">
                      <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 0.95rem; border-radius: 5px !important;">
                          {{ $att->pertemuan_ke }}
                        </div>
                        <div>
                          <span class="text-white fw-semibold small d-block">Pertemuan {{ $att->pertemuan_ke }}</span>
                          <small class="text-body-premium" style="font-size: 0.75rem;">{{ $att->created_at ? $att->created_at->format('d/m/Y') : '-' }}</small>
                        </div>
                      </div>
                      <div>
                        @switch($att->status)
                          @case('hadir')
                            <span class="badge-premium badge-premium-success px-2.5 py-1" style="font-size: 0.75rem;">Hadir</span>
                            @break
                          @case('izin')
                            <span class="badge-premium badge-premium-warning px-2.5 py-1" style="font-size: 0.75rem;">Izin</span>
                            @break
                          @case('sakit')
                            <span class="badge-premium badge-premium-info px-2.5 py-1" style="font-size: 0.75rem;">Sakit</span>
                            @break
                          @case('alpa')
                            <span class="badge-premium badge-premium-danger px-2.5 py-1" style="font-size: 0.75rem;">Alpa</span>
                            @break
                          @default
                            <span class="badge-premium px-2.5 py-1" style="font-size: 0.75rem;">-</span>
                        @endswitch
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              {{-- ONBOARDING / PANDUAN PRESENSI KELAS --}}
              <div class="p-3.5 p-xl-4" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 5px;">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge-premium badge-premium-primary">
                      <i class="icon-base ti tabler-calendar-event"></i> Jadwal &amp; Presensi
                    </span>
                    <span class="text-white fw-semibold small">Sesi Kelas Siap Dimulai</span>
                  </div>
                  <span class="text-body-premium" style="font-size: 0.78rem;">
                    Check-in via QR Code di Lokasi
                  </span>
                </div>

                <div class="row g-2.5 mb-3">
                  <div class="col-12 col-md-4">
                    <div class="p-3 h-100" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 5px;">
                      <div class="d-flex align-items-center gap-2 mb-1.5">
                        <span class="badge-premium badge-premium-primary px-2 py-0.5" style="font-size: 0.72rem;">1</span>
                        <strong class="text-white small">Hadir di Kelas</strong>
                      </div>
                      <p class="text-body-premium mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                        Datang ke lokasi pelatihan sesuai jadwal pelaksanaan.
                      </p>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="p-3 h-100" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 5px;">
                      <div class="d-flex align-items-center gap-2 mb-1.5">
                        <span class="badge-premium badge-premium-primary px-2 py-0.5" style="font-size: 0.72rem;">2</span>
                        <strong class="text-white small">Buka QR Code</strong>
                      </div>
                      <p class="text-body-premium mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                        Klik tombol <em>Tampilkan QR</em> di atas saat berada di kelas.
                      </p>
                    </div>
                  </div>
                  <div class="col-12 col-md-4">
                    <div class="p-3 h-100" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 5px;">
                      <div class="d-flex align-items-center gap-2 mb-1.5">
                        <span class="badge-premium badge-premium-success px-2 py-0.5" style="font-size: 0.72rem;">3</span>
                        <strong class="text-white small">Scan Panitia</strong>
                      </div>
                      <p class="text-body-premium mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                        Tunjukkan QR ke panitia, status kehadiran akan otomatis tercatat.
                      </p>
                    </div>
                  </div>
                </div>

                <div class="d-flex align-items-center gap-2 px-3 py-2" style="background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 5px;">
                  <i class="icon-base ti tabler-info-circle text-info flex-shrink-0"></i>
                  <small class="text-body-premium mb-0" style="font-size: 0.78rem; line-height: 1.35;">
                    Riwayat check-in dan persentase kehadiran Anda akan otomatis terisi di bagian ini setiap kali selesai presensi.
                  </small>
                </div>
              </div>
            @endif
          </div>
        </div>

        {{-- Right: Status & Sertifikat --}}
        <div class="col-12 col-xl-4">
          <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
            <h5 class="fw-bold text-white mb-4 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-certificate text-warning"></i>
              Sertifikat &amp; Kelulusan
            </h5>

            @if($data['hasCertificate'])
              <div class="text-center py-3">
                <div class="stat-icon-box stat-icon-warning mx-auto mb-3" style="width: 58px; height: 58px; font-size: 1.8rem; border-radius: 50% !important; background: rgba(251,191,36,0.15); color: #fbbf24;">
                  <i class="icon-base ti tabler-award fs-1"></i>
                </div>
                <h5 class="fw-bold text-white mb-2">Selamat, Anda Lulus!</h5>
                <p class="text-body-premium small mb-3" style="line-height: 1.4;">
                  Anda dinyatakan lulus dari pelatihan <strong>{{ $data['pelatihan']->nama ?? 'Anda' }}</strong>. Sertifikat resmi Anda telah diterbitkan.
                </p>

                <div class="p-3 mb-4 rounded border border-white border-opacity-5 text-start" style="background: rgba(255, 255, 255, 0.05);">
                  <span class="info-label d-block">Nomor Sertifikat</span>
                  <span class="info-value fw-mono text-warning" style="font-size: 0.85rem; font-family: monospace;">{{ $data['certificate']->certificate_number }}</span>
                </div>

                <div class="d-flex flex-column gap-2">
                  <a href="{{ route('admin.certificates.download', $data['certificate']->id) }}" class="btn btn-glow-premium w-100 py-2">
                    <i class="icon-base ti tabler-download me-1"></i> Unduh Sertifikat PDF
                  </a>
                  <a href="{{ route('certificates.verify', ['nomor' => $data['certificate']->certificate_number]) }}" target="_blank" class="btn btn-outline-secondary w-100 py-2" style="border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.7);">
                    <i class="icon-base ti tabler-qrcode me-1"></i> Verifikasi Online
                  </a>
                </div>
              </div>
            @elseif($data['pelatihan'] && $data['pelatihan']->tanggal_selesai && now()->gt($data['pelatihan']->tanggal_selesai))
              {{-- STATE: Pelatihan Selesai - Tidak Memenuhi Syarat Kelulusan --}}
              <div class="p-3.5 mb-3" style="background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(248, 113, 113, 0.25); border-radius: 5px !important;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-box stat-icon-danger" style="width: 38px; height: 38px; font-size: 1.2rem; border-radius: 5px !important; background: rgba(239, 68, 68, 0.15); color: #f87171;">
                      <i class="icon-base ti tabler-alert-circle"></i>
                    </div>
                    <div>
                      <h6 class="text-white fw-bold mb-0" style="font-size: 0.9rem;">Evaluasi Kelulusan</h6>
                      <small class="text-danger" style="font-size: 0.72rem;">Pelatihan Telah Selesai</small>
                    </div>
                  </div>
                  <span class="badge-premium badge-premium-danger">
                    <i class="icon-base ti tabler-x"></i> Tidak Lulus
                  </span>
                </div>

                <p class="text-body-premium mb-3" style="font-size: 0.8rem; line-height: 1.45;">
                  Rangkaian pelatihan telah berakhir. Berdasarkan evaluasi kehadiran, tingkat partisipasi Anda belum mencapai batas minimal kelulusan resmi:
                </p>

                {{-- Checklist Rincian Ketidaklulusan --}}
                <div class="d-flex flex-column gap-2 pt-2 border-top border-white border-opacity-10 mb-3">
                  <div class="d-flex align-items-center justify-content-between p-2" style="background: rgba(255,255,255,0.02); border-radius: 5px;">
                    <span class="text-white small d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                      <i class="icon-base ti tabler-{{ $data['attendanceRate'] >= 80 ? 'circle-check text-success' : 'circle-x text-danger' }}"></i> Kehadiran (Target: 80%)
                    </span>
                    <span class="fw-bold small {{ $data['attendanceRate'] >= 80 ? 'text-success' : 'text-danger' }}">{{ $data['attendanceRate'] }}%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between p-2" style="background: rgba(255,255,255,0.02); border-radius: 5px;">
                    <span class="text-white small d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                      <i class="icon-base ti tabler-certificate-off text-danger"></i> Penerbitan Sertifikat
                    </span>
                    <span class="badge-premium badge-premium-danger px-2 py-0.5" style="font-size: 0.68rem;">Tidak Terbit</span>
                  </div>
                </div>

                <a href="{{ route('pelatihan.index') }}" class="btn btn-glow-premium w-100 py-2" style="font-size: 0.82rem;">
                  <i class="icon-base ti tabler-arrow-right me-1"></i> Daftar Batch Berikutnya
                </a>
              </div>
            @else
              <div class="p-3.5 mb-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 5px;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <div class="d-flex align-items-center gap-2">
                    <div class="stat-icon-box stat-icon-warning" style="width: 38px; height: 38px; font-size: 1.2rem; border-radius: 5px !important;">
                      <i class="icon-base ti tabler-award"></i>
                    </div>
                    <div>
                      <h6 class="text-white fw-bold mb-0" style="font-size: 0.9rem;">Target Kelulusan</h6>
                      <small class="text-body-premium" style="font-size: 0.72rem;">Pelatihan Sedang Berlangsung</small>
                    </div>
                  </div>
                  <span class="badge-premium badge-premium-warning">
                    <i class="icon-base ti tabler-clock"></i> Proses
                  </span>
                </div>

                <p class="text-body-premium mb-3" style="font-size: 0.8rem; line-height: 1.45;">
                  Sertifikat resmi ber-QR Code &amp; SK Penyelenggara akan diterbitkan otomatis setelah memenuhi syarat:
                </p>

                {{-- Checklist Syarat Kelulusan --}}
                <div class="d-flex flex-column gap-2 pt-2 border-top border-white border-opacity-10">
                  <div class="d-flex align-items-center justify-content-between p-2" style="background: rgba(255,255,255,0.02); border-radius: 5px;">
                    <span class="text-white small d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                      <i class="icon-base ti tabler-circle-check text-success"></i> Kehadiran Minimal (80%)
                    </span>
                    <span class="text-white fw-bold small">{{ $data['attendanceRate'] }}%</span>
                  </div>
                  <div class="d-flex align-items-center justify-content-between p-2" style="background: rgba(255,255,255,0.02); border-radius: 5px;">
                    <span class="text-white small d-flex align-items-center gap-2" style="font-size: 0.78rem;">
                      <i class="icon-base ti tabler-hourglass-low text-warning"></i> Selesaikan Seluruh Sesi
                    </span>
                    <span class="badge-premium px-2 py-0.5" style="font-size: 0.68rem;">Menunggu</span>
                  </div>
                </div>
              </div>
            @endif

            <hr class="dark-premium my-4">

            @if($data['pelatihan'])
            <h6 class="text-white fw-semibold mb-3">Info Kelas Offline:</h6>
            <ul class="list-unstyled mb-0">
              <li class="d-flex justify-content-between mb-2">
                <span class="text-body-premium small">Batch</span>
                <span class="text-white small fw-semibold">{{ $data['pelatihan']->batch ?? '-' }}</span>
              </li>
              <li class="d-flex justify-content-between mb-2">
                <span class="text-body-premium small">Mulai</span>
                <span class="text-white small fw-semibold">{{ $data['pelatihan']->tanggal_mulai ? $data['pelatihan']->tanggal_mulai->format('d M Y') : '-' }}</span>
              </li>
              <li class="d-flex justify-content-between">
                <span class="text-body-premium small">Selesai</span>
                <span class="text-white small fw-semibold">{{ $data['pelatihan']->tanggal_selesai ? $data['pelatihan']->tanggal_selesai->format('d M Y') : '-' }}</span>
              </li>
            </ul>
            @endif
          </div>
        </div>
      </div>
    @endif

    <!-- ============================================================
         BOTTOM ROW: Hanya tampil di State 3 (Approved)
         ============================================================ -->
    @if($data['isProfileCompleted'] && $data['enrollment'] && in_array($data['enrollment']->status?->value, ['approved', 'confirmed']))
    <div class="row g-4">

      <!-- Instruktur Saya (Placeholder) -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-users text-success"></i>
              Instruktur Saya
            </h5>
            <span class="badge-premium badge-premium-success">Info</span>
          </div>

          <div class="text-center py-4">
            <div class="stat-icon-box stat-icon-success mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.6rem; border-radius: 50% !important;">
              <i class="icon-base ti tabler-users"></i>
            </div>
            <h6 class="text-white fw-semibold mb-2" style="font-size: 0.95rem;">Data Instruktur Segera Hadir</h6>
            <p class="text-body-premium mb-0" style="font-size: 0.85rem; line-height: 1.5;">
              Informasi instruktur akan ditampilkan setelah pelatihan <strong class="text-white">{{ $data['pelatihan']->nama ?? 'Anda' }}</strong> resmi dimulai dan jadwal pertemuan telah diterbitkan oleh penyelenggara.
            </p>
          </div>

          <hr class="dark-premium my-4">

          <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
            <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-bell"></i>
            </div>
            <div>
              <span class="text-white fw-semibold small d-block">Notifikasi</span>
              <small class="text-body-premium" style="font-size: 0.75rem;">Kami akan memberitahu Anda saat data instruktur tersedia.</small>
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
            <span class="badge-premium badge-premium-warning">Update</span>
          </div>

          @if($data['enrollment'])
            {{-- Baris 1: Pendaftaran dikirim --}}
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon-box stat-icon-success" style="width: 36px; height: 36px; font-size: 1rem;">
                <i class="icon-base ti tabler-send"></i>
              </div>
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Pendaftaran dikirim</h6>
                <small class="text-body-premium">
                  @if($data['enrollment']->created_at)
                    {{ $data['enrollment']->created_at->format('d M Y H:i') }}
                  @else
                    Semua data pribadi dan dokumen lengkap
                  @endif
                </small>
              </div>
            </div>

            {{-- Baris 2: Status berdasarkan enrollment --}}
            @if($data['enrollment']->status?->value === 'approved' && $data['enrollment']->approved_at)
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-success" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Disetujui</h6>
                  <small class="text-body-premium">
                    {{ $data['enrollment']->approved_at->format('d M Y H:i') }}
                  </small>
                </div>
              </div>
            @elseif($data['enrollment']->status?->value === 'waitlist')
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-warning" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Masuk antrean cadangan</h6>
                  <small class="text-body-premium">Pelatihan: {{ $data['pelatihan']->nama ?? '-' }}</small>
                </div>
              </div>
            @elseif($data['enrollment']->status?->value === 'rejected')
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-danger" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-circle-x"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Pendaftaran ditolak Admin</h6>
                  <small class="text-body-premium">Silakan pilih pelatihan lain</small>
                </div>
              </div>
            @else
              <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon-box stat-icon-info" style="width: 36px; height: 36px; font-size: 1rem;">
                  <i class="icon-base ti tabler-clock"></i>
                </div>
                <div>
                  <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Menunggu Verifikasi</h6>
                  <small class="text-body-premium">Data sedang diperiksa oleh Admin</small>
                </div>
              </div>
            @endif
          @else
            {{-- Fallback jika enrollment tidak ada --}}
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="stat-icon-box stat-icon-secondary" style="width: 36px; height: 36px; font-size: 1rem;">
                <i class="icon-base ti tabler-minus"></i>
              </div>
              <div>
                <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">Belum ada aktivitas</h6>
                <small class="text-body-premium">Lengkapi pendaftaran Anda untuk memulai</small>
              </div>
            </div>
          @endif
        </div>
      </div>

      <!-- Rekomendasi Pelatihan Lainnya -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-star text-danger"></i>
              Rekomendasi Pelatihan Lainnya
            </h5>
            <span class="badge-premium badge-premium-primary">Baru</span>
          </div>

          <div class="text-center py-3 flex-grow-1 d-flex flex-column align-items-center justify-content-center">
            <div class="stat-icon-box stat-icon-danger mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.6rem; border-radius: 50% !important;">
              <i class="icon-base ti tabler-library"></i>
            </div>
            <h6 class="text-white fw-semibold mb-2" style="font-size: 0.95rem;">Jelajahi Pelatihan Lainnya</h6>
            <p class="text-body-premium mb-3" style="font-size: 0.85rem; line-height: 1.5; max-width: 280px;">
              Temukan berbagai pelatihan kreatif dan kejuruan lainnya yang tersedia untuk Anda ikuti.
            </p>

            <a href="{{ route('pelatihan.index') }}" class="btn btn-glow-premium px-4 py-2 fw-semibold">
              <i class="icon-base ti tabler-arrow-right me-1"></i> Lihat Semua Pelatihan
            </a>
          </div>

          <hr class="dark-premium my-4">

          <div class="d-flex align-items-center gap-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);">
            <div class="stat-icon-box stat-icon-primary" style="width: 36px; height: 36px; font-size: 1rem;">
              <i class="icon-base ti tabler-info-circle"></i>
            </div>
            <div>
              <span class="text-white fw-semibold small d-block">Sedang Aktif</span>
              <small class="text-body-premium" style="font-size: 0.75rem;">
                Anda saat ini mengikuti <strong class="text-white">{{ $data['pelatihan']->nama ?? 'pelatihan' }}</strong>
              </small>
            </div>
          </div>
        </div>
      </div>

    </div>
    @endif
  </div>
@endsection

@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  // ===== REALTIME NOTIFICATION via Echo/Reverb =====
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.Echo !== 'undefined') {
      const userId = {{ auth()->id() }};

      window.Echo.private('App.Models.User.' + userId)
        .listen('.NotificationReceived', (e) => {
          // Refresh bell badge
          if (window.Alpine && window.Alpine.$data) {
            const bellEl = document.querySelector('[x-data="notificationBell()"]');
            if (bellEl) {
              const bellData = window.Alpine.$data(bellEl);
              if (bellData && bellData.refresh) {
                bellData.refresh();
              }
            }
          }

          // Show toast
          const notification = e.notification || e;
          showNotificationToast(notification.title, notification.body, notification.wa_data || notification.data?.wa_data);
        });
    }

    // ===== QR CODE ATTENDANCE LOGIC =====
    const btnToggleQr = document.getElementById('btn-toggle-qr');
    const qrDisplaySection = document.getElementById('qr-display-section');
    const qrcodeCanvas = document.getElementById('qrcode-canvas');
    const countdownBar = document.getElementById('qr-countdown-bar');
    const countdownText = document.getElementById('qr-countdown-text');

    if (btnToggleQr && qrDisplaySection) {
      let qrInterval = null;
      let countdownInterval = null;
      let isQrVisible = false;
      const duration = 20; // 20 seconds
      let timeLeft = duration;

      btnToggleQr.addEventListener('click', function() {
        if (!isQrVisible) {
          // Show QR Code display
          qrDisplaySection.classList.remove('d-none');
          btnToggleQr.innerHTML = '<i class="icon-base ti tabler-eye-off me-2"></i>Sembunyikan QR Code';
          isQrVisible = true;

          // Try to request screen wake lock and maximize screen brightness if possible
          if (navigator.wakeLock) {
            navigator.wakeLock.request('screen').catch(err => console.log('Wake Lock request failed:', err));
          }

          // Start generation
          fetchAndRenderQr();
          qrInterval = setInterval(fetchAndRenderQr, duration * 1000);
          startCountdown();
        } else {
          // Hide QR Code display
          qrDisplaySection.classList.add('d-none');
          btnToggleQr.innerHTML = '<i class="icon-base ti tabler-qrcode me-2"></i>Tampilkan QR Presensi';
          isQrVisible = false;

          clearInterval(qrInterval);
          clearInterval(countdownInterval);
        }
      });

      function startCountdown() {
        clearInterval(countdownInterval);
        timeLeft = duration;
        countdownText.textContent = timeLeft;
        countdownBar.style.width = '100%';

        countdownInterval = setInterval(() => {
          timeLeft--;
          if (timeLeft < 0) {
            timeLeft = duration;
          }
          countdownText.textContent = timeLeft;
          const percentage = (timeLeft / duration) * 100;
          countdownBar.style.width = percentage + '%';
        }, 1000);
      }

      function fetchAndRenderQr() {
        const pelatihanId = "{{ $data['pelatihan']->id ?? '' }}";
        if (!pelatihanId) return;

        fetch(`/peserta/attendance-token/${pelatihanId}`, {
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => {
          if (!response.ok) throw new Error('Failed to fetch token');
          return response.json();
        })
        .then(res => {
          if (res.qr_token) {
            // Render QR Code
            qrcodeCanvas.innerHTML = '';
            new QRCode(qrcodeCanvas, {
              text: res.qr_token,
              width: 200,
              height: 200,
              colorDark: "#0b0f19",
              colorLight: "#ffffff",
              correctLevel: QRCode.CorrectLevel.H
            });
            startCountdown();
          }
        })
        .catch(err => {
          console.error('Error generating QR Token:', err);
          showNotificationToast('Gagal memuat QR Presensi', 'Terjadi kesalahan sistem atau sesi Anda telah berakhir. Silakan refresh halaman.');
        });
      }
    }
  });

  function showNotificationToast(title, body, waData) {
    const toast = document.createElement('div');
    toast.style.cssText = `
      position: fixed; top: 20px; right: 20px; z-index: 99999;
      background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 5px; padding: 16px; max-width: 380px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
      animation: slideInRight 0.3s ease-out;
      font-family: 'Outfit', sans-serif;
    `;

    // Build WA URL helper
    let waHtml = '';
    if (waData) {
      const adminWa = waData.admin_wa || waData.admin_phone || '62888888888';
      let waMessage = waData.message || `Halo Admin, saya telah melakukan pendaftaran pelatihan.\n\nNama Lengkap Sesuai KTP : ${waData.nama_lengkap || '-'}\nJenis Pelatihan : ${waData.pelatihan || '-'}\nKelurahan : ${waData.kelurahan || '-'}\nKecamatan : ${waData.kecamatan || '-'}\nNo. HP Peserta Terdaftar : ${waData.no_hp || '-'}\n\n#pelatihanku2026`;
      const waUrl = `https://wa.me/${adminWa}?text=${encodeURIComponent(waMessage)}`;
      waHtml = `<a href="${waUrl}" target="_blank" style="display: inline-block; background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 5px; padding: 4px 10px; font-size: 0.75rem; text-decoration: none; margin-top: 8px;"><i class="icon-base ti tabler-brand-whatsapp me-1"></i> Hubungi Admin</a>`;
    }

    toast.innerHTML = `
      <div class="d-flex gap-3">
        <div style="font-size: 1.5rem; flex-shrink: 0;">🎉</div>
        <div style="flex: 1; min-width: 0;">
          <h6 style="color: #f8fafc; font-weight: 700; margin: 0 0 4px; font-size: 0.9rem; font-family: 'Sora', sans-serif;">${escapeHtml(title)}</h6>
          <p style="color: rgba(255,255,255,0.65); margin: 0; font-size: 0.8rem; line-height: 1.4;">${escapeHtml(body)}</p>
          ${waHtml}
        </div>
        <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: rgba(255,255,255,0.3); cursor: pointer; font-size: 1.2rem; padding: 0; line-height: 1; flex-shrink: 0;">&times;</button>
      </div>
    `;

    document.body.appendChild(toast);

    // Auto dismiss 5 detik
    setTimeout(() => {
      toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }

  function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  // CSS untuk animasi toast
  (function() {
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
      @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    `;
    document.head.appendChild(style);
  })();
</script>
@endsection

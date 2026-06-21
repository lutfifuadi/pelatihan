@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard Admin')

{{-- DATA SUDAH DI-PASS DARI DashboardController DENGAN CACHE --}}

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
         STATISTICS CARDS
         ============================================================ -->
    <div class="row g-4 mb-4">
      <!-- Card 1: Pelatihan -->
      <div class="col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('admin.pelatihan.index') }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-primary">
                <i class="icon-base ti tabler-books"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Total Pelatihan</p>
                <h3 class="fw-bold text-white mb-0" id="stat-total-pelatihan">{{ $totalPelatihan }}</h3>
              </div>
            </div>
          </div>
        </a>
      </div>
      <!-- Card 2: Peserta -->
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-success">
              <i class="icon-base ti tabler-users"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Total Peserta</p>
              <h3 class="fw-bold text-white mb-0" id="stat-total-peserta">{{ $userCounts->total_peserta }}</h3>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 3: Instruktur -->
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="glass-card-premium px-4 py-4 h-100">
          <div class="d-flex align-items-center gap-3">
            <div class="stat-icon-box stat-icon-warning">
              <i class="icon-base ti tabler-school"></i>
            </div>
            <div>
              <p class="text-body-premium small mb-0">Total Instruktur</p>
              <h3 class="fw-bold text-white mb-0" id="stat-total-instruktur">{{ $userCounts->total_instruktur }}</h3>
            </div>
          </div>
        </div>
      </div>
      <!-- Card 4: Koordinator -->
      <div class="col-lg-6 col-md-6">
        <a href="{{ route('admin.koordinator.index') }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-info">
                <i class="icon-base ti tabler-map-pin"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Koordinator Wilayah</p>
                <h3 class="fw-bold text-white mb-0" id="stat-total-koordinator">{{ $userCounts->total_koordinator }}</h3>
              </div>
            </div>
          </div>
        </a>
      </div>
      <!-- Card 5: Kecamatan -->
      <div class="col-lg-6 col-md-12">
        <a href="{{ route('admin.kecamatan.index') }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-danger">
                <i class="icon-base ti tabler-building-community"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Kecamatan Terdaftar</p>
                <h3 class="fw-bold text-white mb-0" id="stat-total-kecamatan">{{ $totalKecamatan }}</h3>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- ============================================================
         NOTIFICATION STATISTICS ROW
         ============================================================ -->
    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.notifications.index', ['channel' => 'whatsapp', 'status' => 'sent']) }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-success">
                <i class="icon-base ti tabler-brand-whatsapp"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">WA Terkirim Hari Ini</p>
                <h3 class="fw-bold text-white mb-0" id="stat-wa-sent-today">
                  {{ $waSentToday }}
                </h3>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.notifications.index', ['status' => 'failed']) }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-danger">
                <i class="icon-base ti tabler-alert-circle"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Total WA Gagal</p>
                <h3 class="fw-bold text-white mb-0" id="stat-wa-failed">
                  {{ $waFailed }}
                </h3>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.notification-templates.index', ['is_active' => 1]) }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-primary">
                <i class="icon-base ti tabler-template"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Template Aktif</p>
                <h3 class="fw-bold text-white mb-0" id="stat-active-templates">
                  {{ $activeTemplates }}
                </h3>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-3 col-md-6">
        <a href="{{ route('admin.notifications.index', ['status' => 'pending']) }}" class="text-decoration-none">
          <div class="glass-card-premium px-4 py-4 h-100">
            <div class="d-flex align-items-center gap-3">
              <div class="stat-icon-box stat-icon-warning">
                <i class="icon-base ti tabler-clock"></i>
              </div>
              <div>
                <p class="text-body-premium small mb-0">Notifikasi Pending</p>
                <h3 class="fw-bold text-white mb-0" id="stat-notif-pending">
                  {{ $notifPending }}
                </h3>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>

    <!-- ============================================================
         MAIN CONTENT ROW
         ============================================================ -->
    <div class="row g-4 mb-4">
      
      <!-- LEFT: Approval Koordinator Pending -->
      <div class="col-12 col-xl-8">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-user-check text-warning"></i>
              Approval Koordinator Pending
            </h5>
            <span class="badge-premium badge-premium-warning" id="badge-pending-koordinator">{{ $pendingKoordinatorCount }} Menunggu</span>
          </div>

          <div id="container-pending-koordinator">
            @if($pendingKoordinators->isEmpty())
              <div class="text-center py-5">
                <i class="icon-base ti tabler-discount-check fs-1 text-success mb-3"></i>
                <h6 class="text-white">Semua pendaftaran bersih!</h6>
                <p class="text-body-premium small mb-0">Tidak ada pengajuan koordinator yang tertunda.</p>
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-borderless text-white align-middle">
                  <thead>
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                      <th class="text-body-premium small fw-semibold px-0">Nama / NIK</th>
                      <th class="text-body-premium small fw-semibold">Kecamatan</th>
                      <th class="text-body-premium small fw-semibold">WhatsApp</th>
                      <th class="text-body-premium small fw-semibold text-end px-0">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($pendingKoordinators as $koor)
                      <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                        <td class="px-0 py-3">
                          <div class="fw-semibold text-white">{{ $koor->name }}</div>
                          <small class="text-body-premium">{{ $koor->nik }}</small>
                        </td>
                        <td class="py-3">
                          <span class="badge-premium badge-premium-info">{{ $koor->kecamatan->name ?? '-' }}</span>
                        </td>
                        <td class="py-3">
                          <a href="https://wa.me/{{ $koor->whatsapp }}" target="_blank" class="text-warning text-decoration-none small">
                            <i class="icon-base ti tabler-brand-whatsapp me-1"></i>{{ $koor->whatsapp }}
                          </a>
                        </td>
                        <td class="text-end px-0 py-3">
                          <div class="d-inline-flex gap-2">
                            <form action="{{ route('admin.koordinator.approve', $koor->id) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-success btn-sm px-3" style="border-radius: 5px;">
                                Approve
                              </button>
                            </form>
                            <form action="{{ route('admin.koordinator.reject', $koor->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak koordinator ini?')">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 5px;">
                                Tolak
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="text-center mt-3">
                <a href="{{ route('admin.koordinator.pending') }}" class="btn btn-glow-premium py-2 px-4 w-100">
                  <i class="icon-base ti tabler-list-check me-1"></i>Lihat Semua Pengajuan
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- RIGHT: Pelatihan Terbaru -->
      <div class="col-12 col-xl-4">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100 d-flex flex-column">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-book-2 text-primary"></i>
              Pelatihan Terbaru
            </h5>
            <span class="badge-premium badge-premium-primary" id="badge-active-pelatihan">{{ $activePelatihanCount }} Aktif</span>
          </div>

          <div id="container-latest-pelatihan" class="d-flex flex-column h-100">
            @if($latestPelatihan->isEmpty())
              <div class="text-center py-5">
                <i class="icon-base ti tabler-book-off fs-1 text-warning mb-3"></i>
                <h6 class="text-white">Belum ada pelatihan</h6>
                <p class="text-body-premium small mb-0">Silakan tambahkan pelatihan baru.</p>
              </div>
            @else
              <div class="d-flex flex-column gap-3 mb-4">
                @foreach($latestPelatihan as $pel)
                  <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon-box stat-icon-primary" style="width: 42px; height: 42px; font-size: 1.25rem;">
                      <i class="icon-base ti tabler-chef-hat"></i>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">{{ $pel->nama }}</h6>
                      <small class="text-body-premium">Batch {{ $pel->batch }} • Kuota {{ $pel->kuota }}</small>
                    </div>
                    <div>
                      @if($pel->is_active)
                        <span class="badge bg-success bg-opacity-20 text-success small px-2 py-1" style="border-radius: 4px; font-size: 10px;">Aktif</span>
                      @else
                        <span class="badge bg-secondary bg-opacity-20 text-white-50 small px-2 py-1" style="border-radius: 4px; font-size: 10px;">Nonaktif</span>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="text-center mt-auto">
                <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-glow-premium w-100 py-2">
                  <i class="icon-base ti tabler-settings me-1"></i>Kelola Pelatihan
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>

    <!-- ============================================================
         SECOND ROW: Peserta Terdaftar Baru & Koordinator Wilayah Aktif
         ============================================================ -->
    <div class="row g-4">
      
      <!-- LEFT: Peserta Terdaftar Baru -->
      <div class="col-12 col-xl-6">
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

      <!-- RIGHT: Koordinator Wilayah Aktif -->
      <div class="col-12 col-xl-6">
        <div class="glass-card-premium px-4 px-xl-5 py-4 h-100">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-white mb-0 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-map-pins text-info"></i>
              Koordinator Wilayah Aktif
            </h5>
            <span class="badge-premium badge-premium-info" id="badge-active-koors">{{ $koorActiveCount }} Aktif</span>
          </div>

          <div id="container-active-koors">
            @if($activeKoors->isEmpty())
              <div class="text-center py-4">
                <i class="icon-base ti tabler-user-x fs-2 text-muted mb-2"></i>
                <p class="text-body-premium small mb-0">Belum ada koordinator aktif.</p>
              </div>
            @else
              <div class="d-flex flex-column gap-3">
                @foreach($activeKoors as $k)
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                      <div class="instructor-avatar text-white" style="background: rgba(6, 182, 212, 0.15); color: #22d3ee;">
                        {{ strtoupper(substr($k->name, 0, 2)) }}
                      </div>
                      <div>
                        <h6 class="text-white fw-semibold mb-0" style="font-size: 0.85rem;">{{ $k->name }}</h6>
                        <small class="text-body-premium">Kecamatan: {{ $k->kecamatan->name ?? '-' }}</small>
                      </div>
                    </div>
                    @if($k->whatsapp)
                      <a href="https://wa.me/{{ $k->whatsapp }}" target="_blank" class="btn btn-sm btn-outline-success px-2 py-1" style="border-radius: 4px; font-size: 11px;">
                        <i class="icon-base ti tabler-brand-whatsapp"></i> Chat
                      </a>
                    @endif
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>

    </div>

  </div>
@endsection

@section('page-script')
  @vite(['resources/assets/js/dashboard-admin.js'])
@endsection


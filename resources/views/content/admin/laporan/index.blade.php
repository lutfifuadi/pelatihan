@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Laporan & Statistik')

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

  /* --- LAYOUT OVERRIDES --- */
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

  .stat-icon-warning {
    background: rgba(245, 158, 11, 0.12);
    color: #fbbf24;
  }

  .stat-icon-info {
    background: rgba(6, 182, 212, 0.12);
    color: #22d3ee;
  }

  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
  }

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #818cf8;
  }

  .stat-icon-danger {
    background: rgba(239, 68, 68, 0.12);
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

  .btn-glow-primary {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.5);
    color: #ffffff !important;
  }

  .btn-glow-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.5);
    color: #ffffff !important;
  }

  .btn-glow-info {
    background: linear-gradient(135deg, #06b6d4, #0284c7) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(6, 182, 212, 0.5);
    color: #ffffff !important;
  }

  .laporan-card {
    cursor: pointer;
  }
  .laporan-card .card-icon {
    font-size: 2.2rem;
    margin-bottom: 0.75rem;
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

    <!-- Header -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary">
          <i class="icon-base ti tabler-report-analytics fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0">📊 Laporan & Statistik</h4>
          <p class="text-body-premium mt-1 mb-0">Pilih jenis laporan yang ingin dilihat atau di-export.</p>
        </div>
      </div>
    </div>

    <!-- Grid Opsi Laporan -->
    <div class="row g-4">

      <!-- Card 1: Laporan Kehadiran -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-warning mx-auto mb-3">
            <i class="icon-base ti tabler-calendar-stats fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Laporan Kehadiran</h6>
          <p class="text-body-premium small">Rekap kehadiran peserta per pelatihan, termasuk persentase kehadiran.</p>
          <a href="{{ route('admin.exports.attendance.pdf', ['pelatihan' => 0]) }}" class="btn btn-glow-premium btn-sm mt-2">Lihat Laporan</a>
        </div>
      </div>

      <!-- Card 2: Laporan Pendaftaran -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-info mx-auto mb-3">
            <i class="icon-base ti tabler-users fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Laporan Pendaftaran</h6>
          <p class="text-body-premium small">Statistik pendaftar per status, periode, dan pelatihan.</p>
          <a href="{{ route('admin.exports.enrollments.pdf') }}" class="btn btn-glow-premium btn-sm mt-2">Lihat Laporan</a>
        </div>
      </div>

      <!-- Card 3: Laporan Peserta -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-primary mx-auto mb-3">
            <i class="icon-base ti tabler-id-badge-2 fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Laporan Peserta</h6>
          <p class="text-body-premium small">Data lengkap seluruh peserta terdaftar dalam sistem.</p>
          <a href="{{ route('admin.exports.peserta.pdf') }}" class="btn btn-glow-premium btn-sm mt-2">Lihat Laporan</a>
        </div>
      </div>

      <!-- Card 4: Laporan Sertifikat -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-success mx-auto mb-3">
            <i class="icon-base ti tabler-certificate fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Laporan Sertifikat</h6>
          <p class="text-body-premium small">Rekap sertifikat yang telah diterbitkan untuk peserta.</p>
          <a href="{{ route('admin.exports.certificates.pdf') }}" class="btn btn-glow-premium btn-sm mt-2">Lihat Laporan</a>
        </div>
      </div>

      <!-- Card 5: Export Excel Kehadiran -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-warning mx-auto mb-3">
            <i class="icon-base ti tabler-file-spreadsheet fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Export Excel Kehadiran</h6>
          <p class="text-body-premium small">Download rekap kehadiran dalam format Excel (.xlsx).</p>
          <a href="{{ route('admin.exports.attendance.excel', ['pelatihan' => 0]) }}" class="btn btn-glow-success btn-sm mt-2">Download Excel</a>
        </div>
      </div>

      <!-- Card 6: Export Excel Pendaftaran -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-info mx-auto mb-3">
            <i class="icon-base ti tabler-file-spreadsheet fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Export Excel Pendaftaran</h6>
          <p class="text-body-premium small">Download data pendaftaran dalam format Excel (.xlsx).</p>
          <a href="{{ route('admin.exports.enrollments.excel') }}" class="btn btn-glow-success btn-sm mt-2">Download Excel</a>
        </div>
      </div>

      <!-- Card 7: Export Excel Peserta -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-primary mx-auto mb-3">
            <i class="icon-base ti tabler-file-spreadsheet fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Export Excel Peserta</h6>
          <p class="text-body-premium small">Download data peserta dalam format Excel (.xlsx).</p>
          <a href="{{ route('admin.exports.peserta.excel') }}" class="btn btn-glow-success btn-sm mt-2">Download Excel</a>
        </div>
      </div>

      <!-- Card 8: Export Excel Sertifikat -->
      <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="glass-card-premium px-4 py-4 h-100 text-center laporan-card">
          <div class="stat-icon-box stat-icon-success mx-auto mb-3">
            <i class="icon-base ti tabler-file-spreadsheet fs-4"></i>
          </div>
          <h6 class="fw-bold text-white">Export Excel Sertifikat</h6>
          <p class="text-body-premium small">Download data sertifikat dalam format Excel (.xlsx).</p>
          <a href="{{ route('admin.exports.certificates.excel') }}" class="btn btn-glow-success btn-sm mt-2">Download Excel</a>
        </div>
      </div>

    </div>

    <!-- Informasi Tambahan -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mt-4">
      <div class="d-flex align-items-start gap-3">
        <div class="stat-icon-box stat-icon-danger flex-shrink-0">
          <i class="icon-base ti tabler-info-circle fs-4"></i>
        </div>
        <div>
          <h6 class="fw-bold text-white mb-1">💡 Informasi</h6>
          <p class="text-body-premium small mb-0">
            Pilih jenis laporan di atas untuk melihat atau mengunduh data.
            Untuk laporan kehadiran dan pendaftaran, Anda dapat memfilter berdasarkan pelatihan tertentu
            melalui halaman export masing-masing. Data yang ditampilkan adalah data real-time dari sistem.
          </p>
        </div>
      </div>
    </div>

  </div>
@endsection

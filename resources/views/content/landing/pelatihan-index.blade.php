@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@php
    $institutionName = \App\Models\Setting::where('key', 'institution_name')->value('value') ?? 'Lembaga Pelatihan';
    $institutionAddress = \App\Models\Setting::where('key', 'institution_address')->value('value') ?? 'Gedung Pusat Pembelajaran Kreatif';
    $institutionPhone = \App\Models\Setting::where('key', 'institution_phone')->value('value') ?? '+62 812-3456-7890';
    $institutionEmail = \App\Models\Setting::where('key', 'institution_email')->value('value') ?? 'admin@sabakreatif.com';
    $institutionDesc = \App\Models\Setting::where('key', 'institution_description')->value('value') ?? 'Program pelatihan pengembangan kompetensi dan keterampilan praktis yang mandiri, kreatif, dan berdaya saing.';
    $footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?? 'Pelatihan — Pengembangan Kompetensi';

    // Landing page settings
    $landingSettings = \App\Models\Setting::where('group', 'landing')->get()->keyBy('key');
    if (!function_exists('landingVal')) {
        function landingVal($settings, $key, $default) {
            return $settings[$key]->value ?? $default;
        }
    }
    $pelatihan_badge = landingVal($landingSettings, 'pelatihan_badge', 'Program Unggulan');
    $pelatihan_title = landingVal($landingSettings, 'pelatihan_title', 'Pelatihan yang Tersedia');
    $pelatihan_subtitle = landingVal($landingSettings, 'pelatihan_subtitle', 'Pilih kelas sesuai minat Anda. Kuota terbatas, segera daftar sebelum pendaftaran ditutup.');
    $pelatihan_empty_title = landingVal($landingSettings, 'pelatihan_empty_title', 'Belum Ada Pelatihan Aktif');
    $pelatihan_empty_desc = landingVal($landingSettings, 'pelatihan_empty_desc', 'Silakan kembali beberapa saat lagi untuk melihat program pelatihan terbaru kami.');
@endphp

@extends('layouts/blankLayout')

@section('title', __('Semua Pelatihan') . ' — ' . $institutionName)

@section('page-style')
<style>
  /* ============================================================
     CUSTOM STYLES — Halaman Semua Pelatihan
     ============================================================ */

  /* --- Fonts Import for Premium Look --- */
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  /* --- Typography Base --- */
  #pelatihan-index-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow-x: hidden;
    min-height: 100vh;
  }
  #pelatihan-index-wrapper h1,
  #pelatihan-index-wrapper h2,
  #pelatihan-index-wrapper h3,
  #pelatihan-index-wrapper h4,
  #pelatihan-index-wrapper h5,
  #pelatihan-index-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* ============================================================
     FLOATING NAVIGATION BAR (shared with beranda.blade.php)
     ============================================================ */
  .navbar-glass-floating {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 40px);
    max-width: 1200px;
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    padding: 12px 28px;
    z-index: 1000;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }
  .navbar-glass-floating.scrolled {
    top: 10px;
    background: rgba(15, 23, 42, 0.85);
    border-color: rgba(99, 102, 241, 0.25);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 25px rgba(99, 102, 241, 0.15);
  }
  .logo-icon-glow {
    width: 38px;
    height: 38px;
    border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
    transition: transform 0.3s ease;
  }
  .navbar-logo:hover .logo-icon-glow {
    transform: rotate(15deg) scale(1.05);
  }
  .logo-text-glow {
    font-family: 'Sora', sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
  }
  .nav-link-premium {
    font-weight: 500;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    padding: 6px 0;
  }
  .nav-link-premium::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #6366f1, #d946ef);
    transition: width 0.3s ease;
    border-radius: 2px;
  }
  .nav-link-premium:hover {
    color: #ffffff;
  }
  .nav-link-premium:hover::after {
    width: 100%;
  }
  .btn-login-premium {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 8px 22px;
    border-radius: 5px;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  .btn-login-premium:hover {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
    transform: translateY(-2px);
  }

  /* ============================================================
     MOBILE SLIDE-IN MENU — Premium Drawer dari Kiri
     ============================================================ */
  .mobile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    height: 100dvh;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.35s ease, visibility 0.35s ease;
    cursor: pointer;
  }
  .mobile-overlay.active {
    opacity: 1;
    visibility: visible;
  }
  .mobile-slide-panel {
    position: fixed;
    top: 0;
    left: 0;
    width: 290px;
    max-width: 80vw;
    height: 100vh;
    height: 100dvh;
    background: rgba(12, 16, 28, 0.97);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border-right: 1px solid rgba(255, 255, 255, 0.07);
    z-index: 1050;
    overflow-y: auto;
    transform: translateX(-100%) translateX(-30px);
    transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
  }
  .mobile-slide-panel.active {
    transform: translateX(0);
  }
  .panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 22px 24px 18px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  }
  .panel-title {
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    font-size: 1.15rem;
    color: #ffffff;
    letter-spacing: -0.3px;
  }
  .panel-close-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff;
    width: 36px;
    height: 36px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s ease;
  }
  .panel-close-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.2);
  }
  .panel-close-btn:active {
    transform: scale(0.92);
  }
  .panel-nav {
    flex: 1;
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .panel-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    border-radius: 5px;
    transition: all 0.2s ease;
    font-family: 'Outfit', sans-serif;
    position: relative;
  }
  .panel-link i {
    font-size: 1.25rem;
    width: 22px;
    text-align: center;
    color: rgba(255, 255, 255, 0.35);
    transition: color 0.2s ease;
    flex-shrink: 0;
  }
  .panel-link:hover {
    background: rgba(255, 255, 255, 0.06);
    color: #ffffff;
  }
  .panel-link:hover i {
    color: #818cf8;
  }
  .panel-link:active {
    background: rgba(99, 102, 241, 0.15);
    color: #ffffff;
    transform: scale(0.98);
  }
  .panel-footer {
    padding: 16px 20px 28px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
  }
  .panel-footer .btn-login-premium {
    font-size: 0.95rem;
    padding: 10px 22px;
  }
  .mobile-menu-btn {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px;
    width: 38px;
    height: 38px;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 9px;
    transition: all 0.25s ease;
  }
  .mobile-menu-btn:hover {
    background: rgba(255, 255, 255, 0.1);
  }
  .mobile-menu-btn .bar {
    display: block;
    width: 18px;
    height: 2px;
    background: #ffffff;
    border-radius: 2px;
    transition: all 0.3s ease;
    transform-origin: center;
  }
  .mobile-menu-btn.active .bar:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
  }
  .mobile-menu-btn.active .bar:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
  }
  .mobile-menu-btn.active .bar:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
  }
  body.mobile-menu-open {
    overflow: hidden;
  }
  @media (max-width: 991.98px) {
    .navbar-glass-floating {
      padding: 10px 16px;
    }
    .mobile-menu-btn {
      display: flex;
    }
  }

  /* ============================================================
     FOOTER PREMIUM (shared with beranda.blade.php)
     ============================================================ */
  .footer-premium {
    background: #06080e;
    border-top: 1px solid rgba(255,255,255,0.04);
  }
  .footer-link {
    color: rgba(255, 255, 255, 0.5);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }
  .footer-link:hover {
    color: #ffc107;
    transform: translateX(4px);
    display: inline-block;
  }
  .social-icon-btn {
    width: 38px;
    height: 38px;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    font-size: 1.15rem;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  .social-icon-btn:hover {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border-color: transparent;
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
  }
  .hover-white {
    transition: color 0.2s ease;
  }
  .hover-white:hover {
    color: #ffffff !important;
  }

  html { scroll-behavior: smooth; }

  /* --- Pelatihan Grid Section (same as landing) --- */
  .pelatihan-grid-section {
    background: #0b0f19;
    position: relative;
    overflow: hidden;
  }
  .pelatihan-grid-section::before {
    content: '';
    position: absolute;
    top: -10%;
    right: -5%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
    pointer-events: none;
  }
  .pelatihan-grid-section::after {
    content: '';
    position: absolute;
    bottom: -10%;
    left: -5%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
    pointer-events: none;
  }

  .pelatihan-card {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
  }
  .pelatihan-card:hover {
    transform: translateY(-8px);
    border-color: rgba(255, 193, 7, 0.35);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4), 0 0 30px rgba(255, 193, 7, 0.08);
  }

  .pelatihan-card .card-cover {
    position: relative;
    height: 180px;
    overflow: hidden;
  }
  .pelatihan-card .card-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .pelatihan-card:hover .card-cover img {
    transform: scale(1.08);
  }
  .pelatihan-card .card-cover::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(11, 15, 25, 0.9) 0%, rgba(11, 15, 25, 0.2) 50%, transparent 100%);
  }

  .pelatihan-card .card-badge-category {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    padding: 6px 12px;
    background: rgba(11, 15, 25, 0.75);
    border: 1px solid rgba(255, 193, 7, 0.3);
    border-radius: 20px;
    color: #ffc107;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
  }

  .pelatihan-card .card-badge-status {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 2;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    backdrop-filter: blur(8px);
  }
  .card-status-open {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .card-status-limited {
    background: rgba(245, 158, 11, 0.15);
    border: 1px solid rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .card-status-full {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #f87171;
  }

  .pelatihan-card .card-body {
    padding: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .pelatihan-card .batch-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6366f1;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
  }

  .pelatihan-card .card-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.4;
    margin-bottom: 14px;
  }

  .pelatihan-card .card-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.65);
    margin-bottom: 10px;
  }
  .pelatihan-card .card-meta i {
    color: rgba(255, 255, 255, 0.4);
    font-size: 1rem;
  }

  .pelatihan-card .quota-bar {
    margin-top: auto;
    padding-top: 16px;
  }
  .pelatihan-card .quota-label {
    display: flex;
    justify-content: space-between;
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-bottom: 6px;
  }
  .pelatihan-card .quota-label strong {
    color: #ffc107;
  }
  .pelatihan-card .progress {
    height: 5px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    overflow: hidden;
  }
  .pelatihan-card .progress-bar {
    border-radius: 10px;
    transition: width 0.6s ease;
  }

  .pelatihan-card .card-footer-action {
    padding: 16px 22px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
  }

  .pelatihan-card .price-tag {
    font-family: 'Sora', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #ffc107;
  }
  .pelatihan-card .price-tag small {
    display: block;
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.45);
    font-weight: 500;
    text-decoration: line-through;
  }

  .pelatihan-card .btn-daftar-card {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 700;
    border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #0b0f19;
    transition: all 0.25s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .pelatihan-card .btn-daftar-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 152, 0, 0.35);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }

  .pelatihan-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: rgba(15, 23, 42, 0.4);
    border: 1px dashed rgba(255, 255, 255, 0.12);
    border-radius: 12px;
  }
  .pelatihan-empty-state i {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.2);
    margin-bottom: 16px;
  }
  .pelatihan-empty-state h5 {
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 8px;
  }
  .pelatihan-empty-state p {
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 0;
  }

  /* --- Breadcrumb --- */
  .breadcrumb-premium {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.5);
    margin-bottom: 0;
  }
  .breadcrumb-premium a {
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .breadcrumb-premium a:hover {
    color: #ffc107;
  }
  .breadcrumb-premium .separator {
    color: rgba(255, 255, 255, 0.2);
  }
  .breadcrumb-premium .current {
    color: rgba(255, 255, 255, 0.8);
  }

  /* --- Scroll reveal --- */
  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
  }
  .reveal.revealed {
    opacity: 1;
    transform: translateY(0);
  }

  /* --- Page Header --- */
  .page-header-section {
    padding-top: 140px;
    padding-bottom: 60px;
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%);
    position: relative;
  }
  @media (max-width: 991.98px) {
    .page-header-section {
      padding-top: 100px;
      padding-bottom: 40px;
    }
  }

  /* --- Back to Home Button --- */
  .btn-back-home {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: rgba(255, 255, 255, 0.7);
    font-family: 'Outfit', sans-serif;
    font-weight: 500;
    font-size: 0.85rem;
    padding: 8px 18px;
    border-radius: 5px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-back-home:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.25);
    color: #ffffff;
    transform: translateX(-4px);
  }

  /* --- Custom Scrollbar --- */
  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  @media (max-width: 767.98px) {
    .pelatihan-card .card-cover {
      height: 160px;
    }
    .pelatihan-card .card-title {
      font-size: 1rem;
    }
    .pelatihan-card .btn-daftar-card {
      padding: 8px 14px;
      font-size: 0.75rem;
    }
  }
</style>
@endsection

@section('content')
<div id="pelatihan-index-wrapper">

  @include('partials.floating-navbar')

  <!-- ============================================================
       PAGE HEADER
       ============================================================ -->
  <section class="page-header-section">
    <div class="container position-relative" style="z-index: 10;">
      <div class="row align-items-center">
        <div class="col-lg-8">
          <!-- Breadcrumb -->
          <nav aria-label="Breadcrumb" class="mb-3">
            <ol class="breadcrumb-premium list-unstyled d-flex flex-wrap">
              <li><a href="{{ route('pages-home') }}#beranda"><i class="icon-base ti tabler-home me-1"></i>Beranda</a></li>
              <li class="separator mx-2">/</li>
              <li class="current">Semua Pelatihan</li>
            </ol>
          </nav>

          <!-- Section Header -->
          <span class="badge bg-warning bg-opacity-10 text-warning px-4 py-2 fw-semibold mb-3" style="font-size: 0.8rem; letter-spacing: 0.05em; border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 5px;">
            {{ $pelatihan_badge }}
          </span>
          <h1 class="fw-bold mb-3 display-5" style="color: #ffffff; font-family: 'Sora', sans-serif;">
            {{ __('Semua Pelatihan') }}
          </h1>
          <p class="text-white-50 fs-5 mx-0" style="max-width: 580px;">
            {{ $pelatihan_subtitle }}
          </p>
        </div>
        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
          <a href="{{ route('pages-home') }}#pelatihan" class="btn-back-home">
            <i class="icon-base ti tabler-arrow-left"></i>
            {{ __('Kembali ke Beranda') }}
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================================
       ALL PELATIHAN GRID
       ============================================================ -->
  <section class="py-8 py-lg-10 pelatihan-grid-section">
    <div class="container py-4 position-relative" style="z-index: 1;">

      @php
        \Carbon\Carbon::setLocale('id');

        $coverImages = [
          'kuliner' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?q=80&w=800&auto=format&fit=crop',
          'kriya' => 'https://images.unsplash.com/photo-1452860606245-08befc0ff44b?q=80&w=800&auto=format&fit=crop',
          'desain' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=800&auto=format&fit=crop',
          'film' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?q=80&w=800&auto=format&fit=crop',
          'foto' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop',
          'animasi' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?q=80&w=800&auto=format&fit=crop',
          'marketing' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=800&auto=format&fit=crop',
          'bisnis' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop',
          'teknologi' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=800&auto=format&fit=crop',
          'default' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
        ];

        $keywordMap = [
          'kuliner' => 'kuliner', 'makanan' => 'kuliner', 'pastry' => 'kuliner',
          'kriya' => 'kriya', 'kreasi' => 'kriya', 'diy' => 'kriya',
          'desain' => 'desain', 'design' => 'desain',
          'film' => 'film', 'video' => 'film',
          'animasi' => 'animasi',
          'foto' => 'foto', 'fotografi' => 'foto',
          'marketing' => 'marketing', 'iklan' => 'marketing', 'periklanan' => 'marketing',
          'bisnis' => 'bisnis', 'usaha' => 'bisnis',
          'teknologi' => 'teknologi', 'it' => 'teknologi', 'software' => 'teknologi',
        ];
      @endphp

      @if($pelatihans->count() > 0)
        <div class="row g-4">
          @foreach($pelatihans as $pelatihan)
            @php
              $namaLower = strtolower($pelatihan->nama);
              $imageKey = 'default';
              foreach ($keywordMap as $keyword => $key) {
                if (str_contains($namaLower, $keyword)) {
                  $imageKey = $key;
                  break;
                }
              }
              $coverUrl = $coverImages[$imageKey] ?? $coverImages['default'];

              $approvedCount = $pelatihan->approved_enrollments_count ?? 0;
              $quota = $pelatihan->kuota;
              $isKuotaUnlimited = is_null($quota) || $quota <= 0;
              $percentage = $isKuotaUnlimited ? 0 : min(100, round(($approvedCount / $quota) * 100, 1));

              if ($isKuotaUnlimited) {
                $statusClass = 'card-status-open';
                $statusText = __('Pendaftaran Dibuka');
                $quotaText = __('Kuota Terbuka');
                $barColor = 'bg-success';
              } elseif ($percentage >= 100) {
                $statusClass = 'card-status-full';
                $statusText = __('Kuota Penuh');
                $quotaText = __('Kuota Penuh');
                $barColor = 'bg-danger';
              } elseif ($percentage >= 80) {
                $statusClass = 'card-status-limited';
                $statusText = __('Sisa Sedikit');
                $quotaText = ($quota - $approvedCount) . ' ' . __('kursi tersisa');
                $barColor = 'bg-warning';
              } else {
                $statusClass = 'card-status-open';
                $statusText = __('Pendaftaran Dibuka');
                $quotaText = $approvedCount . ' / ' . $quota . ' ' . __('kursi terisi');
                $barColor = 'bg-success';
              }

              $dateRange = '';
              if ($pelatihan->tanggal_mulai && $pelatihan->tanggal_selesai) {
                $dateRange = $pelatihan->tanggal_mulai->translatedFormat('d M') . ' - ' . $pelatihan->tanggal_selesai->translatedFormat('d M Y');
              } elseif ($pelatihan->tanggal_mulai) {
                $dateRange = $pelatihan->tanggal_mulai->translatedFormat('d M Y');
              } else {
                $dateRange = __('Jadwal Menyusul');
              }

              $kecamatanNames = $pelatihan->kecamatans->pluck('name')->filter()->values();
              if ($kecamatanNames->isNotEmpty()) {
                $displayKecamatan = $kecamatanNames->take(3)->implode(', ');
                $remainingCount = $kecamatanNames->count() - 3;
                if ($remainingCount > 0) {
                  $displayKecamatan .= ' +' . $remainingCount . ' ' . __('lainnya');
                }
                $lokasiText = __('Khusus') . ': ' . $displayKecamatan;
              } else {
                $lokasiText = __('Untuk semua kecamatan');
              }

              $batchDisplay = str_starts_with(strtoupper($pelatihan->batch), 'BATCH ')
                ? substr($pelatihan->batch, 6)
                : $pelatihan->batch;
            @endphp

            <div class="col-md-6 col-lg-4 reveal" style="transition-delay: {{ $loop->iteration * 0.1 }}s;">
              <div class="pelatihan-card h-100">
                <!-- Cover Image -->
                <div class="card-cover">
                  <img src="{{ $coverUrl }}" alt="{{ $pelatihan->nama }}" loading="lazy">
                  <span class="card-badge-category">{{ $imageKey }}</span>
                  <span class="card-badge-status {{ $statusClass }}">{{ $statusText }}</span>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                  <div class="batch-label">{{ __('Batch') }} {{ $batchDisplay }}</div>
                  <h5 class="card-title">{{ $pelatihan->nama }}</h5>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-building"></i>
                    <span>{{ $pelatihan->dinas?->nama_dinas ?? __('Dinas Penyelenggara') }}</span>
                  </div>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-calendar"></i>
                    <span>{{ $dateRange }}</span>
                  </div>

                  <div class="card-meta">
                    <i class="icon-base ti tabler-map-pin"></i>
                    <span>{{ $lokasiText }}</span>
                  </div>

                  @if(!$isKuotaUnlimited)
                    <div class="quota-bar">
                      <div class="quota-label">
                        <span>{{ __('Terisi') }}</span>
                        <strong>{{ $quotaText }}</strong>
                      </div>
                      <div class="progress">
                        <div class="progress-bar {{ $barColor }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer-action">
                  <div class="price-tag">
                    <small>Rp 0</small>
                    {{ __('Gratis') }}
                  </div>
                  <a href="{{ route('pages-home') }}#beranda" class="btn-daftar-card">
                    {{ __('Daftar') }} <i class="icon-base ti tabler-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="pelatihan-empty-state reveal">
          <i class="icon-base ti tabler-calendar-off"></i>
          <h5>{{ $pelatihan_empty_title }}</h5>
          <p>{{ $pelatihan_empty_desc }}</p>
        </div>
      @endif

    </div>
  </section>

  @include('partials.site-footer')

</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  'use strict';

  // ============================================================
  // 1. DYNAMIC NAVIGATION ON SCROLL
  // ============================================================
  const navbar = document.querySelector('.navbar-glass-floating');
  if (navbar) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  }

  // ============================================================
  // 2. REVEAL ON SCROLL (Intersection Observer)
  // ============================================================
  const revealElements = document.querySelectorAll('.reveal');
  if (revealElements.length > 0) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    revealElements.forEach(el => revealObserver.observe(el));
  }

  // ============================================================
  // 3. SMOOTH SCROLL FOR ALL ANCHOR LINKS
  // ============================================================
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        const headerOffset = 90;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  // ============================================================
  // 4. MOBILE MENU TOGGLE (Hamburger → X animation)
  // ============================================================
  const menuToggle = document.getElementById('mobileMenuToggle');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobilePanel = document.getElementById('mobileSlidePanel');
  const menuClose = document.getElementById('mobileMenuClose');

  function openMenu() {
    menuToggle.classList.add('active');
    mobileOverlay.classList.add('active');
    mobilePanel.classList.add('active');
    document.body.classList.add('mobile-menu-open');
  }

  function closeMenu() {
    menuToggle.classList.remove('active');
    mobileOverlay.classList.remove('active');
    mobilePanel.classList.remove('active');
    document.body.classList.remove('mobile-menu-open');
  }

  if (menuToggle) {
    menuToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      if (mobilePanel.classList.contains('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }

  if (mobileOverlay) {
    mobileOverlay.addEventListener('click', closeMenu);
  }

  if (menuClose) {
    menuClose.addEventListener('click', closeMenu);
  }

  // Tutup menu saat klik link di dalam panel
  if (mobilePanel) {
    mobilePanel.querySelectorAll('.panel-link').forEach(link => {
      link.addEventListener('click', closeMenu);
    });
  }

  // ============================================================
  // 5. SMART NAVIGATION: Intercept internal anchor links (full URL)
  // ============================================================
  document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (!href || href.indexOf('#') === -1 || href.startsWith('#')) return;
      const url = new URL(href, window.location.origin);
      const currentPath = window.location.pathname;
      const targetPath = url.pathname;

      if (targetPath === currentPath || (targetPath === '/' && (currentPath === '' || currentPath === '/'))) {
        const targetId = url.hash;
        if (targetId) {
          e.preventDefault();
          const target = document.querySelector(targetId);
          if (target) {
            const headerOffset = 90;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            window.scrollTo({
              top: offsetPosition,
              behavior: 'smooth'
            });
          }
        }
      }
    });
  });
});
</script>
@endsection

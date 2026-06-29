@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@php
  $configData = Helper::appClasses();
  $isFront = true;

  /* Display elements */
  $customizerHidden = $customizerHidden ?? '';
@endphp

@extends('layouts/commonMaster')

@push('styles')
<style>
  /* ============================================================
     CUSTOM GLOBAL STYLES — Public Layout
     ============================================================ */

  /* --- Fonts Import for Premium Look --- */
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  /* --- Typography Base --- */
  #beranda-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow-x: hidden;
  }
  #beranda-page-wrapper h1,
  #beranda-page-wrapper h2,
  #beranda-page-wrapper h3,
  #beranda-page-wrapper h4,
  #beranda-page-wrapper h5,
  #beranda-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* --- Floating Navigation Bar --- */
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

  /* --- Premium Footer --- */
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

  /* ============================================================
     MOBILE SLIDE-IN MENU — Premium Drawer dari Kiri
     ============================================================ */

  /* Overlay blur di belakang panel */
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

  /* Panel slide-in dari kiri */
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

  /* Header panel: judul + tombol close */
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

  /* Navigasi panel */
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

  /* Footer panel: login button */
  .panel-footer {
    padding: 16px 20px 28px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
  }
  .panel-footer .btn-login-premium {
    font-size: 0.95rem;
    padding: 10px 22px;
  }

  /* Animated Hamburger: 3 bar jadi X */
  .mobile-menu-btn {
    display: none; /* override dengan d-lg-none (Bootstrap) */
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

  /* Body scroll lock saat menu terbuka */
  body.mobile-menu-open {
    overflow: hidden;
  }

  /* Fix navbar padding di mobile */
  @media (max-width: 991.98px) {
    .navbar-glass-floating {
      padding: 10px 16px;
    }
    .mobile-menu-btn {
      display: flex; /* override d-lg-none: tampil di mobile */
    }
  }

  /* --- Custom Scrollbar --- */
  ::-webkit-scrollbar { width: 8px; }
  ::-webkit-scrollbar-track { background: #0b0f19; }
  ::-webkit-scrollbar-thumb { background: #6366f1; border-radius: 4px; }
  ::-webkit-scrollbar-thumb:hover { background: #d946ef; }

  html { scroll-behavior: smooth; }
</style>
@endpush

@section('layoutContent')
  <!-- Content -->
  @yield('content')
  <!--/ Content -->

  {{-- Floating WhatsApp Support (hanya untuk halaman publik) --}}
  @includeWhen(isset($whatsappNumbers) && $whatsappNumbers->isNotEmpty(), 'components.floating-whatsapp')

@endsection

@push('scripts')
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
  // 2. MOBILE SLIDE-IN MENU — Toggle, Overlay, Auto-close
  // ============================================================
  const mobileToggle = document.getElementById('mobileMenuToggle');
  const mobileClose = document.getElementById('mobileMenuClose');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobilePanel = document.getElementById('mobileSlidePanel');
  const bodyEl = document.body;

  if (mobileToggle && mobilePanel && mobileOverlay) {
    // Buka menu
    function openMobileMenu() {
      mobileToggle.classList.add('active');
      mobilePanel.classList.add('active');
      mobileOverlay.classList.add('active');
      bodyEl.classList.add('mobile-menu-open');
      mobileToggle.setAttribute('aria-label', 'Tutup menu');
      // Prevent background scroll on touch devices
      mobileOverlay.style.touchAction = 'none';
    }

    // Tutup menu
    function closeMobileMenu() {
      mobileToggle.classList.remove('active');
      mobilePanel.classList.remove('active');
      mobileOverlay.classList.remove('active');
      bodyEl.classList.remove('mobile-menu-open');
      mobileToggle.setAttribute('aria-label', 'Buka menu');
      mobileOverlay.style.touchAction = '';
    }

    // Toggle via hamburger
    mobileToggle.addEventListener('click', function() {
      if (mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    // Tutup via overlay klik
    mobileOverlay.addEventListener('click', closeMobileMenu);

    // Tutup via tombol close (X)
    if (mobileClose) {
      mobileClose.addEventListener('click', closeMobileMenu);
    }

    // Tutup via tombol Escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      }
    });

    // Tutup saat link di panel diklik
    mobilePanel.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        // Beri jeda kecil agar scroll mulai dulu sebelum panel nutup
        setTimeout(closeMobileMenu, 120);
      });
    });

    // Tutup otomatis saat resize ke layar desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 992 && mobilePanel.classList.contains('active')) {
        closeMobileMenu();
      }
    });
  }
});
</script>
@endpush

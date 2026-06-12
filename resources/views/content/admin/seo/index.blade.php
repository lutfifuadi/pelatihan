@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pengaturan SEO')

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
    background: radial-gradient(circle, #10b981 0%, rgba(16, 185, 129, 0) 70%);
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
    background: linear-gradient(135deg, #10b981, #059669) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    background: linear-gradient(135deg, #34d399, #10b981) !important;
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

  .badge-seo {
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
    font-size: 0.7rem;
    padding: 4px 10px;
    border-radius: 20px;
    font-weight: 600;
  }

  .section-divider {
    border: none;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    margin: 28px 0;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- Header --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
            <i class="icon-base ti tabler-seo fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Pengaturan SEO</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Optimasi mesin pencari — atur meta tags, social media, dan schema markup
            </p>
          </div>
        </div>
        <span class="badge-seo">
          <i class="icon-base ti tabler-search me-1"></i>SEO
        </span>
      </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{-- Main Form --}}
    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.settings.seo.update') }}" method="POST">
          @csrf

          {{-- Section 1: Meta Tags Default --}}
          <div class="d-flex align-items-center gap-2 mb-4">
            <i class="icon-base ti tabler-tags fs-5" style="color: #6366f1;"></i>
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Meta Tags Default</h5>
          </div>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Nilai default untuk meta tags di seluruh halaman. Akan dipakai jika halaman tidak memiliki data SEO spesifik.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="seo_default_title" class="form-label">Default Title <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('seo_default_title') is-invalid @enderror"
                id="seo_default_title" name="seo_default_title"
                value="{{ old('seo_default_title', $settings['seo_default_title']->value ?? config('seo.defaults.title')) }}"
                placeholder="Aplikasi Pelatihan" required>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Maks 60 karakter. Contoh: "Aplikasi Pelatihan — Platform Pelatihan Online"
              </small>
              @error('seo_default_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="seo_default_description" class="form-label">Default Description <span class="text-danger">*</span></label>
              <textarea class="form-control @error('seo_default_description') is-invalid @enderror"
                id="seo_default_description" name="seo_default_description" rows="2"
                placeholder="Platform manajemen pelatihan online..." required>{{ old('seo_default_description', $settings['seo_default_description']->value ?? config('seo.defaults.description')) }}</textarea>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Maks 160 karakter. Muncul di hasil pencarian Google.
              </small>
              @error('seo_default_description')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="seo_default_keywords" class="form-label">Default Keywords</label>
            <input type="text" class="form-control @error('seo_default_keywords') is-invalid @enderror"
              id="seo_default_keywords" name="seo_default_keywords"
              value="{{ old('seo_default_keywords', $settings['seo_default_keywords']->value ?? config('seo.defaults.keywords')) }}"
              placeholder="pelatihan, kursus online, sertifikat">
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
              <i class="icon-base ti tabler-info-circle me-1"></i>Pisahkan dengan koma. Contoh: "pelatihan, kursus online, sertifikat"
            </small>
            @error('seo_default_keywords')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <hr class="section-divider">

          {{-- Section 2: Open Graph & Social Media --}}
          <div class="d-flex align-items-center gap-2 mb-4">
            <i class="icon-base ti tabler-share fs-5" style="color: #ec4899;"></i>
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Open Graph & Social Media</h5>
          </div>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Tampilan link website saat dibagikan di media sosial (Facebook, Twitter/X, Instagram, LinkedIn).
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="seo_og_image" class="form-label">OG Image (URL Path)</label>
              <input type="text" class="form-control @error('seo_og_image') is-invalid @enderror"
                id="seo_og_image" name="seo_og_image"
                value="{{ old('seo_og_image', $settings['seo_og_image']->value ?? config('seo.defaults.og_image')) }}"
                placeholder="/assets/img/og-default.jpg">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-image me-1"></i>Gambar default untuk preview link (1200x630px)
              </small>
              @error('seo_og_image')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="seo_twitter_handle" class="form-label">Twitter / X Handle</label>
              <input type="text" class="form-control @error('seo_twitter_handle') is-invalid @enderror"
                id="seo_twitter_handle" name="seo_twitter_handle"
                value="{{ old('seo_twitter_handle', $settings['seo_twitter_handle']->value ?? config('seo.social.twitter_handle')) }}"
                placeholder="@username">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-brand-x me-1"></i>Contoh: @aplikasipelatihan
              </small>
              @error('seo_twitter_handle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="seo_facebook_page" class="form-label">Facebook Page URL</label>
              <input type="text" class="form-control @error('seo_facebook_page') is-invalid @enderror"
                id="seo_facebook_page" name="seo_facebook_page"
                value="{{ old('seo_facebook_page', $settings['seo_facebook_page']->value ?? config('seo.social.facebook_page')) }}"
                placeholder="https://facebook.com/aplikasipelatihan">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-brand-facebook me-1"></i>URL lengkap halaman Facebook
              </small>
              @error('seo_facebook_page')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="seo_instagram_handle" class="form-label">Instagram Handle</label>
              <input type="text" class="form-control @error('seo_instagram_handle') is-invalid @enderror"
                id="seo_instagram_handle" name="seo_instagram_handle"
                value="{{ old('seo_instagram_handle', $settings['seo_instagram_handle']->value ?? config('seo.social.instagram_handle')) }}"
                placeholder="@aplikasipelatihan">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-brand-instagram me-1"></i>Contoh: @aplikasipelatihan
              </small>
              @error('seo_instagram_handle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="section-divider">

          {{-- Section 3: Schema.org Structured Data --}}
          <div class="d-flex align-items-center gap-2 mb-4">
            <i class="icon-base ti tabler-code fs-5" style="color: #f59e0b;"></i>
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Schema.org (Structured Data)</h5>
          </div>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Data terstruktur untuk membantu mesin pencari memahami website. Digunakan di seluruh halaman.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="seo_org_name" class="form-label">Nama Organisasi <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('seo_org_name') is-invalid @enderror"
                id="seo_org_name" name="seo_org_name"
                value="{{ old('seo_org_name', $settings['seo_org_name']->value ?? config('seo.schema.organization.name')) }}"
                placeholder="Aplikasi Pelatihan" required>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-building me-1"></i>Nama perusahaan/lembaga untuk schema Organization
              </small>
              @error('seo_org_name')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6 mb-3">
              <label for="seo_org_logo" class="form-label">Logo Organisasi (URL Path)</label>
              <input type="text" class="form-control @error('seo_org_logo') is-invalid @enderror"
                id="seo_org_logo" name="seo_org_logo"
                value="{{ old('seo_org_logo', $settings['seo_org_logo']->value ?? config('seo.schema.organization.logo')) }}"
                placeholder="/assets/img/logo.png">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-photo me-1"></i>Path logo untuk schema Organization
              </small>
              @error('seo_org_logo')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="section-divider">

          {{-- Informasi Tambahan --}}
          <div class="d-flex align-items-start gap-3 p-3 rounded" style="background: rgba(99, 102, 241, 0.08); border: 1px solid rgba(99, 102, 241, 0.15);">
            <i class="icon-base ti tabler-info-circle fs-5 mt-1" style="color: #6366f1;"></i>
            <div>
              <p class="text-white fw-semibold mb-1" style="font-size: 0.85rem;">Informasi</p>
              <p class="text-body-premium mb-0" style="font-size: 0.8rem;">
                Pengaturan ini akan menyimpan nilai ke database dan menimpa konfigurasi default di <code class="text-white">config/seo.php</code>.
                Untuk pengaturan lanjutan (robots.txt, sitemap, canonical), silakan edit langsung file konfigurasi.
              </p>
            </div>
          </div>

          {{-- Actions --}}
          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Pengaturan SEO
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

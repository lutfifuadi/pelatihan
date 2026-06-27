@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Konten Halaman Publik')

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

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .layout-menu { background-color: #0b0f19 !important; border-right: 1px solid rgba(255, 255, 255, 0.08) !important; }
  .layout-menu .app-brand { background-color: #0b0f19 !important; }
  .layout-menu .menu-inner { background-color: #0b0f19 !important; }
  .layout-menu .menu-link { color: rgba(255, 255, 255, 0.7) !important; }
  .layout-menu .menu-item.active > .menu-link { color: #ffffff !important; background: linear-gradient(135deg, #6366f1, #d946ef) !important; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important; }
  .layout-menu .menu-item.active > .menu-link i { color: #ffffff !important; }
  .layout-menu .menu-header-text { color: rgba(255, 255, 255, 0.4) !important; }
  .layout-menu .menu-link:hover { background-color: rgba(255, 255, 255, 0.04) !important; color: #ffffff !important; }
  .layout-menu .menu-inner-shadow { background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important; }
  .layout-menu .app-brand .app-brand-text { color: #ffffff !important; }

  .layout-navbar, #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached { background: rgba(15, 23, 42, 0.45) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; margin-top: 12px !important; }
  #layout-navbar .nav-link { color: rgba(255, 255, 255, 0.7) !important; }
  #layout-navbar .nav-link:hover { color: #ffffff !important; }

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
  .glass-card-premium:hover { transform: translateY(-2px) !important; border-color: rgba(99, 102, 241, 0.2) !important; }

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
  .form-control::placeholder, textarea::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
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

  .btn-secondary-custom {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s ease;
  }
  .btn-secondary-custom:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }

  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }

  .invalid-feedback {
    color: #f87171 !important;
    font-size: 0.75rem !important;
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
          <div class="stat-icon-box" style="background: rgba(255, 193, 7, 0.12); color: #ffc107;">
            <i class="icon-base ti tabler-article fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Konten Halaman Publik</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Kelola semua teks dan redaksi yang tampil di halaman beranda publik
            </p>
          </div>
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

      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.settings.landing.update') }}" method="POST">
          @csrf

          {{-- ======================== SECTION 1: HERO / HEADER UTAMA ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-home me-2 text-warning"></i>Hero / Header Utama
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Kelola teks utama, tag kategori, dan statistik yang tampil di hero section halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_title" class="form-label">Judul Utama (kiri)</label>
              <input type="text" class="form-control @error('hero_title') is-invalid @enderror" id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title']->value ?? 'Pendaftaran') }}">
              @error('hero_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_subtitle" class="form-label">Subjudul (kanan, warna kuning)</label>
              <input type="text" class="form-control @error('hero_subtitle') is-invalid @enderror" id="hero_subtitle" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle']->value ?? 'Pelatihan Ekonomi Kreatif') }}">
              @error('hero_subtitle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-3">
              <label for="hero_description" class="form-label">Deskripsi Hero</label>
              <textarea class="form-control @error('hero_description') is-invalid @enderror" id="hero_description" name="hero_description" rows="3">{{ old('hero_description', $settings['hero_description']->value ?? '') }}</textarea>
              @error('hero_description')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          <h6 class="fw-semibold text-white mb-3">Tag Kategori</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_icon" class="form-label">Tag 1 - Icon</label>
              <input type="text" class="form-control @error('hero_tag_1_icon') is-invalid @enderror" id="hero_tag_1_icon" name="hero_tag_1_icon" value="{{ old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat') }}" placeholder="chef-hat">
              @error('hero_tag_1_icon')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Nama icon Tabler (<a href="https://tabler-icons.io" target="_blank" class="text-warning">lihat daftar</a>)</small>
            </div>
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_text" class="form-label">Tag 1 - Teks</label>
              <input type="text" class="form-control @error('hero_tag_1_text') is-invalid @enderror" id="hero_tag_1_text" name="hero_tag_1_text" value="{{ old('hero_tag_1_text', $settings['hero_tag_1_text']->value ?? 'Kuliner Kreatif') }}">
              @error('hero_tag_1_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_preview" class="form-label">Icon Tabler (full class)</label>
              <input type="text" class="form-control" id="hero_tag_1_preview" value="tabler-{{ old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat') }}" disabled>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Pratinjau otomatis dari field icon di atas</small>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_tag_2_icon" class="form-label">Tag 2 - Icon</label>
              <input type="text" class="form-control @error('hero_tag_2_icon') is-invalid @enderror" id="hero_tag_2_icon" name="hero_tag_2_icon" value="{{ old('hero_tag_2_icon', $settings['hero_tag_2_icon']->value ?? 'camera') }}">
              @error('hero_tag_2_icon')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_tag_2_text" class="form-label">Tag 2 - Teks</label>
              <input type="text" class="form-control @error('hero_tag_2_text') is-invalid @enderror" id="hero_tag_2_text" name="hero_tag_2_text" value="{{ old('hero_tag_2_text', $settings['hero_tag_2_text']->value ?? 'Konten Kreator') }}">
              @error('hero_tag_2_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_tag_3_icon" class="form-label">Tag 3 - Icon</label>
              <input type="text" class="form-control @error('hero_tag_3_icon') is-invalid @enderror" id="hero_tag_3_icon" name="hero_tag_3_icon" value="{{ old('hero_tag_3_icon', $settings['hero_tag_3_icon']->value ?? 'palette') }}">
              @error('hero_tag_3_icon')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_tag_3_text" class="form-label">Tag 3 - Teks</label>
              <input type="text" class="form-control @error('hero_tag_3_text') is-invalid @enderror" id="hero_tag_3_text" name="hero_tag_3_text" value="{{ old('hero_tag_3_text', $settings['hero_tag_3_text']->value ?? 'Desain Grafis') }}">
              @error('hero_tag_3_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          <h6 class="fw-semibold text-white mb-3">Statistik Hero</h6>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_stat_1_value" class="form-label">Stat 1 - Angka</label>
              <input type="text" class="form-control @error('hero_stat_1_value') is-invalid @enderror" id="hero_stat_1_value" name="hero_stat_1_value" value="{{ old('hero_stat_1_value', $settings['hero_stat_1_value']->value ?? '4+') }}">
              @error('hero_stat_1_value')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_stat_1_label" class="form-label">Stat 1 - Label</label>
              <input type="text" class="form-control @error('hero_stat_1_label') is-invalid @enderror" id="hero_stat_1_label" name="hero_stat_1_label" value="{{ old('hero_stat_1_label', $settings['hero_stat_1_label']->value ?? 'Bidang Kreatif') }}">
              @error('hero_stat_1_label')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_stat_2_value" class="form-label">Stat 2 - Angka</label>
              <input type="text" class="form-control @error('hero_stat_2_value') is-invalid @enderror" id="hero_stat_2_value" name="hero_stat_2_value" value="{{ old('hero_stat_2_value', $settings['hero_stat_2_value']->value ?? 'Gratis') }}">
              @error('hero_stat_2_value')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_stat_2_label" class="form-label">Stat 2 - Label</label>
              <input type="text" class="form-control @error('hero_stat_2_label') is-invalid @enderror" id="hero_stat_2_label" name="hero_stat_2_label" value="{{ old('hero_stat_2_label', $settings['hero_stat_2_label']->value ?? 'Tanpa Biaya') }}">
              @error('hero_stat_2_label')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_stat_3_value" class="form-label">Stat 3 - Angka</label>
              <input type="text" class="form-control @error('hero_stat_3_value') is-invalid @enderror" id="hero_stat_3_value" name="hero_stat_3_value" value="{{ old('hero_stat_3_value', $settings['hero_stat_3_value']->value ?? '2026') }}">
              @error('hero_stat_3_value')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="hero_stat_3_label" class="form-label">Stat 3 - Label</label>
              <input type="text" class="form-control @error('hero_stat_3_label') is-invalid @enderror" id="hero_stat_3_label" name="hero_stat_3_label" value="{{ old('hero_stat_3_label', $settings['hero_stat_3_label']->value ?? 'Tahun Akademik') }}">
              @error('hero_stat_3_label')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="col-12 mb-3">
              <label for="hero_scroll_text" class="form-label">Teks Scroll (bawah)</label>
              <input type="text" class="form-control @error('hero_scroll_text') is-invalid @enderror" id="hero_scroll_text" name="hero_scroll_text" value="{{ old('hero_scroll_text', $settings['hero_scroll_text']->value ?? 'Scroll ke bawah untuk informasi lanjut') }}">
              @error('hero_scroll_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks yang muncul di bagian bawah hero sebagai ajakan scroll</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== SECTION 2: FORM PENDAFTARAN ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-file-text me-2 text-success"></i>Form Pendaftaran
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada kartu form pendaftaran di halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_title" class="form-label">Judul Kartu</label>
              <input type="text" class="form-control @error('form_title') is-invalid @enderror" id="form_title" name="form_title" value="{{ old('form_title', $settings['form_title']->value ?? 'Daftar Sekarang') }}">
              @error('form_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="form_password_info" class="form-label">Info Password</label>
              <input type="text" class="form-control @error('form_password_info') is-invalid @enderror" id="form_password_info" name="form_password_info" value="{{ old('form_password_info', $settings['form_password_info']->value ?? 'Password akun akan diisi otomatis') }}">
              @error('form_password_info')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Informasi yang ditampilkan di samping field password</small>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_password_value" class="form-label">Default Password</label>
              <input type="text" class="form-control @error('form_password_value') is-invalid @enderror" id="form_password_value" name="form_password_value" value="{{ old('form_password_value', $settings['form_password_value']->value ?? 'pelatihanku2026') }}">
              @error('form_password_value')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Password default yang akan diisi otomatis untuk pendaftar</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="form_button_text" class="form-label">Tombol Daftar</label>
              <input type="text" class="form-control @error('form_button_text') is-invalid @enderror" id="form_button_text" name="form_button_text" value="{{ old('form_button_text', $settings['form_button_text']->value ?? 'Daftar Sekarang') }}">
              @error('form_button_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_button_loading" class="form-label">Loading State</label>
              <input type="text" class="form-control @error('form_button_loading') is-invalid @enderror" id="form_button_loading" name="form_button_loading" value="{{ old('form_button_loading', $settings['form_button_loading']->value ?? 'Memproses Pendaftaran...') }}">
              @error('form_button_loading')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks yang muncul saat tombol ditekan dan sedang memproses</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="form_login_text" class="form-label">Teks "Sudah memiliki akun?"</label>
              <input type="text" class="form-control @error('form_login_text') is-invalid @enderror" id="form_login_text" name="form_login_text" value="{{ old('form_login_text', $settings['form_login_text']->value ?? 'Sudah memiliki akun?') }}">
              @error('form_login_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_login_link" class="form-label">Teks Link Login</label>
              <input type="text" class="form-control @error('form_login_link') is-invalid @enderror" id="form_login_link" name="form_login_link" value="{{ old('form_login_link', $settings['form_login_link']->value ?? 'Login di sini') }}">
              @error('form_login_link')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks link yang dapat diklik untuk menuju halaman login</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== SECTION 3: ALUR PENDAFTARAN (3 LANGKAH) ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-steps me-2 text-info"></i>Alur Pendaftaran (3 Langkah)
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur judul dan deskripsi untuk setiap langkah dalam alur pendaftaran.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_badge" class="form-label">Badge</label>
              <input type="text" class="form-control @error('steps_badge') is-invalid @enderror" id="steps_badge" name="steps_badge" value="{{ old('steps_badge', $settings['steps_badge']->value ?? 'Alur Pendaftaran') }}">
              @error('steps_badge')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="steps_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('steps_title') is-invalid @enderror" id="steps_title" name="steps_title" value="{{ old('steps_title', $settings['steps_title']->value ?? 'Ikuti 3 Langkah Mudah') }}">
              @error('steps_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="steps_subtitle" class="form-label">Subjudul</label>
              <input type="text" class="form-control @error('steps_subtitle') is-invalid @enderror" id="steps_subtitle" name="steps_subtitle" value="{{ old('steps_subtitle', $settings['steps_subtitle']->value ?? '') }}">
              @error('steps_subtitle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <h6 class="fw-semibold text-white mt-4 mb-3">Langkah 1</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_1_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('steps_1_title') is-invalid @enderror" id="steps_1_title" name="steps_1_title" value="{{ old('steps_1_title', $settings['steps_1_title']->value ?? 'Daftarkan Akun') }}">
              @error('steps_1_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-8 mb-3">
              <label for="steps_1_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control @error('steps_1_desc') is-invalid @enderror" id="steps_1_desc" name="steps_1_desc" rows="2">{{ old('steps_1_desc', $settings['steps_1_desc']->value ?? '') }}</textarea>
              @error('steps_1_desc')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 2</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_2_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('steps_2_title') is-invalid @enderror" id="steps_2_title" name="steps_2_title" value="{{ old('steps_2_title', $settings['steps_2_title']->value ?? 'Ikuti Kelas Pelatihan') }}">
              @error('steps_2_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-8 mb-3">
              <label for="steps_2_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control @error('steps_2_desc') is-invalid @enderror" id="steps_2_desc" name="steps_2_desc" rows="2">{{ old('steps_2_desc', $settings['steps_2_desc']->value ?? '') }}</textarea>
              @error('steps_2_desc')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 3</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_3_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('steps_3_title') is-invalid @enderror" id="steps_3_title" name="steps_3_title" value="{{ old('steps_3_title', $settings['steps_3_title']->value ?? 'Raih Hasil & Sertifikat') }}">
              @error('steps_3_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-8 mb-3">
              <label for="steps_3_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control @error('steps_3_desc') is-invalid @enderror" id="steps_3_desc" name="steps_3_desc" rows="2">{{ old('steps_3_desc', $settings['steps_3_desc']->value ?? '') }}</textarea>
              @error('steps_3_desc')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== SECTION 4: PELATIHAN ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-backpack me-2 text-warning"></i>Section Pelatihan
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section daftar pelatihan yang tersedia.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="pelatihan_badge" class="form-label">Badge</label>
              <input type="text" class="form-control @error('pelatihan_badge') is-invalid @enderror" id="pelatihan_badge" name="pelatihan_badge" value="{{ old('pelatihan_badge', $settings['pelatihan_badge']->value ?? 'Program Unggulan') }}">
              @error('pelatihan_badge')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="pelatihan_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('pelatihan_title') is-invalid @enderror" id="pelatihan_title" name="pelatihan_title" value="{{ old('pelatihan_title', $settings['pelatihan_title']->value ?? 'Pelatihan yang Tersedia') }}">
              @error('pelatihan_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="pelatihan_subtitle" class="form-label">Subjudul</label>
              <input type="text" class="form-control @error('pelatihan_subtitle') is-invalid @enderror" id="pelatihan_subtitle" name="pelatihan_subtitle" value="{{ old('pelatihan_subtitle', $settings['pelatihan_subtitle']->value ?? '') }}">
              @error('pelatihan_subtitle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="pelatihan_empty_title" class="form-label">Kosong - Judul</label>
              <input type="text" class="form-control @error('pelatihan_empty_title') is-invalid @enderror" id="pelatihan_empty_title" name="pelatihan_empty_title" value="{{ old('pelatihan_empty_title', $settings['pelatihan_empty_title']->value ?? 'Belum Ada Pelatihan Aktif') }}">
              @error('pelatihan_empty_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Judul yang tampil ketika belum ada pelatihan aktif</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="pelatihan_empty_desc" class="form-label">Kosong - Deskripsi</label>
              <input type="text" class="form-control @error('pelatihan_empty_desc') is-invalid @enderror" id="pelatihan_empty_desc" name="pelatihan_empty_desc" value="{{ old('pelatihan_empty_desc', $settings['pelatihan_empty_desc']->value ?? '') }}">
              @error('pelatihan_empty_desc')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Deskripsi yang tampil ketika belum ada pelatihan aktif</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== SECTION 5: "MENGAPA" ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-question-mark me-2 text-primary"></i>Section "Mengapa"
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section alasan memilih pelatihan.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="why_badge" class="form-label">Badge</label>
              <input type="text" class="form-control @error('why_badge') is-invalid @enderror" id="why_badge" name="why_badge" value="{{ old('why_badge', $settings['why_badge']->value ?? 'Mengapa Memilih') }}">
              @error('why_badge')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="why_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('why_title') is-invalid @enderror" id="why_title" name="why_title" value="{{ old('why_title', $settings['why_title']->value ?? 'Mengapa Memilih Pelatihan Kami?') }}">
              @error('why_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="why_subtitle" class="form-label">Subjudul</label>
              <textarea class="form-control @error('why_subtitle') is-invalid @enderror" id="why_subtitle" name="why_subtitle" rows="2">{{ old('why_subtitle', $settings['why_subtitle']->value ?? '') }}</textarea>
              @error('why_subtitle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== SECTION 6: CTA / CALL-TO-ACTION ======================== --}}
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-bolt me-2 text-danger"></i>CTA / Call-to-Action
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section ajakan bertindak di bagian bawah halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="cta_badge" class="form-label">Badge</label>
              <input type="text" class="form-control @error('cta_badge') is-invalid @enderror" id="cta_badge" name="cta_badge" value="{{ old('cta_badge', $settings['cta_badge']->value ?? 'SEGERA BERGABUNG') }}">
              @error('cta_badge')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="cta_title" class="form-label">Judul</label>
              <input type="text" class="form-control @error('cta_title') is-invalid @enderror" id="cta_title" name="cta_title" value="{{ old('cta_title', $settings['cta_title']->value ?? 'Siap Memulai Perjalanan Anda?') }}">
              @error('cta_title')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-4 mb-3">
              <label for="cta_subtitle" class="form-label">Subjudul</label>
              <textarea class="form-control @error('cta_subtitle') is-invalid @enderror" id="cta_subtitle" name="cta_subtitle" rows="2">{{ old('cta_subtitle', $settings['cta_subtitle']->value ?? '') }}</textarea>
              @error('cta_subtitle')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="cta_button_text" class="form-label">Tombol Daftar</label>
              <input type="text" class="form-control @error('cta_button_text') is-invalid @enderror" id="cta_button_text" name="cta_button_text" value="{{ old('cta_button_text', $settings['cta_button_text']->value ?? 'Daftar Sekarang — Gratis!') }}">
              @error('cta_button_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label for="cta_login_text" class="form-label">Tombol Login</label>
              <input type="text" class="form-control @error('cta_login_text') is-invalid @enderror" id="cta_login_text" name="cta_login_text" value="{{ old('cta_login_text', $settings['cta_login_text']->value ?? 'Sudah Punya Akun? Login') }}">
              @error('cta_login_text')
                <div class="invalid-feedback mt-1">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          {{-- ======================== TOMBOL AKSI ======================== --}}
          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Semua Perubahan
            </button>
          </div>
        </form>
    </div>

  </div>
@endsection


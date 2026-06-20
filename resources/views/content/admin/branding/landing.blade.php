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

  .section-accordion {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    margin-bottom: 16px;
    overflow: hidden;
  }
  .section-accordion-header {
    padding: 16px 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.3s ease;
    user-select: none;
  }
  .section-accordion-header:hover { background: rgba(255, 255, 255, 0.04); }
  .section-accordion-body { padding: 0 20px 20px; display: none; }
  .section-accordion.open .section-accordion-body { display: block; }
  .section-accordion.open .chevron-icon { transform: rotate(180deg); }
  .chevron-icon { transition: transform 0.3s ease; font-size: 1.2rem; color: rgba(255, 255, 255, 0.5); }
</style>
@endsection

@section('content')
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

  <div class="col-12">
    <div class="glass-card-premium px-4 px-xl-5 py-5">
      <form action="{{ route('admin.settings.landing.update') }}" method="POST">
        @csrf

        {{-- HERO SECTION --}}
        <div class="section-accordion open">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-home me-2 text-warning"></i>Hero / Header Utama
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Judul Utama (kiri)</label>
                <input type="text" class="form-control" name="hero_title" value="{{ old('hero_title', $settings['hero_title']->value ?? 'Pendaftaran') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Subjudul (kanan, warna kuning)</label>
                <input type="text" class="form-control" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle']->value ?? 'Pelatihan Ekonomi Kreatif') }}">
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi Hero</label>
              <textarea class="form-control" name="hero_description" rows="3">{{ old('hero_description', $settings['hero_description']->value ?? '') }}</textarea>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

            <h6 class="fw-semibold text-white mb-3">Tag Kategori</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 1 - Icon</label>
                <input type="text" class="form-control" name="hero_tag_1_icon" value="{{ old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat') }}" placeholder="chef-hat">
                <small class="text-body-premium">Nama icon Tabler (<a href="https://tabler-icons.io" target="_blank" class="text-warning">lihat daftar</a>)</small>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 1 - Teks</label>
                <input type="text" class="form-control" name="hero_tag_1_text" value="{{ old('hero_tag_1_text', $settings['hero_tag_1_text']->value ?? 'Kuliner Kreatif') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Icon Tabler (full class)</label>
                <input type="text" class="form-control" value="tabler-{{ old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat') }}" disabled>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 2 - Icon</label>
                <input type="text" class="form-control" name="hero_tag_2_icon" value="{{ old('hero_tag_2_icon', $settings['hero_tag_2_icon']->value ?? 'camera') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 2 - Teks</label>
                <input type="text" class="form-control" name="hero_tag_2_text" value="{{ old('hero_tag_2_text', $settings['hero_tag_2_text']->value ?? 'Konten Kreator') }}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 3 - Icon</label>
                <input type="text" class="form-control" name="hero_tag_3_icon" value="{{ old('hero_tag_3_icon', $settings['hero_tag_3_icon']->value ?? 'palette') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Tag 3 - Teks</label>
                <input type="text" class="form-control" name="hero_tag_3_text" value="{{ old('hero_tag_3_text', $settings['hero_tag_3_text']->value ?? 'Desain Grafis') }}">
              </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

            <h6 class="fw-semibold text-white mb-3">Statistik Hero</h6>
            <div class="row">
              <div class="col-md-2 mb-3">
                <label class="form-label">Stat 1 - Angka</label>
                <input type="text" class="form-control" name="hero_stat_1_value" value="{{ old('hero_stat_1_value', $settings['hero_stat_1_value']->value ?? '4+') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Stat 1 - Label</label>
                <input type="text" class="form-control" name="hero_stat_1_label" value="{{ old('hero_stat_1_label', $settings['hero_stat_1_label']->value ?? 'Bidang Kreatif') }}">
              </div>
              <div class="col-md-2 mb-3">
                <label class="form-label">Stat 2 - Angka</label>
                <input type="text" class="form-control" name="hero_stat_2_value" value="{{ old('hero_stat_2_value', $settings['hero_stat_2_value']->value ?? 'Gratis') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Stat 2 - Label</label>
                <input type="text" class="form-control" name="hero_stat_2_label" value="{{ old('hero_stat_2_label', $settings['hero_stat_2_label']->value ?? 'Tanpa Biaya') }}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 mb-3">
                <label class="form-label">Stat 3 - Angka</label>
                <input type="text" class="form-control" name="hero_stat_3_value" value="{{ old('hero_stat_3_value', $settings['hero_stat_3_value']->value ?? '2026') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Stat 3 - Label</label>
                <input type="text" class="form-control" name="hero_stat_3_label" value="{{ old('hero_stat_3_label', $settings['hero_stat_3_label']->value ?? 'Tahun Akademik') }}">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Teks Scroll (bawah)</label>
              <input type="text" class="form-control" name="hero_scroll_text" value="{{ old('hero_scroll_text', $settings['hero_scroll_text']->value ?? 'Scroll ke bawah untuk informasi lanjut') }}">
            </div>
          </div>
        </div>

        {{-- FORM SECTION --}}
        <div class="section-accordion">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-file-text me-2 text-success"></i>Form Pendaftaran
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul Kartu</label>
                <input type="text" class="form-control" name="form_title" value="{{ old('form_title', $settings['form_title']->value ?? 'Daftar Sekarang') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Info Password</label>
                <input type="text" class="form-control" name="form_password_info" value="{{ old('form_password_info', $settings['form_password_info']->value ?? 'Password akun akan diisi otomatis') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Default Password</label>
                <input type="text" class="form-control" name="form_password_value" value="{{ old('form_password_value', $settings['form_password_value']->value ?? 'pelatihanku2026') }}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Tombol Daftar</label>
                <input type="text" class="form-control" name="form_button_text" value="{{ old('form_button_text', $settings['form_button_text']->value ?? 'Daftar Sekarang') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Loading State</label>
                <input type="text" class="form-control" name="form_button_loading" value="{{ old('form_button_loading', $settings['form_button_loading']->value ?? 'Memproses Pendaftaran...') }}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Teks "Sudah memiliki akun?"</label>
                <input type="text" class="form-control" name="form_login_text" value="{{ old('form_login_text', $settings['form_login_text']->value ?? 'Sudah memiliki akun?') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Teks Link Login</label>
                <input type="text" class="form-control" name="form_login_link" value="{{ old('form_login_link', $settings['form_login_link']->value ?? 'Login di sini') }}">
              </div>
            </div>
          </div>
        </div>

        {{-- STEPS SECTION --}}
        <div class="section-accordion">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-steps me-2 text-info"></i>Alur Pendaftaran (3 Langkah)
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Badge</label>
                <input type="text" class="form-control" name="steps_badge" value="{{ old('steps_badge', $settings['steps_badge']->value ?? 'Alur Pendaftaran') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="steps_title" value="{{ old('steps_title', $settings['steps_title']->value ?? 'Ikuti 3 Langkah Mudah') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Subjudul</label>
                <input type="text" class="form-control" name="steps_subtitle" value="{{ old('steps_subtitle', $settings['steps_subtitle']->value ?? '') }}">
              </div>
            </div>

            <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 1</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="steps_1_title" value="{{ old('steps_1_title', $settings['steps_1_title']->value ?? 'Daftarkan Akun') }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="steps_1_desc" rows="2">{{ old('steps_1_desc', $settings['steps_1_desc']->value ?? '') }}</textarea>
              </div>
            </div>

            <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 2</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="steps_2_title" value="{{ old('steps_2_title', $settings['steps_2_title']->value ?? 'Ikuti Kelas Pelatihan') }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="steps_2_desc" rows="2">{{ old('steps_2_desc', $settings['steps_2_desc']->value ?? '') }}</textarea>
              </div>
            </div>

            <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 3</h6>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="steps_3_title" value="{{ old('steps_3_title', $settings['steps_3_title']->value ?? 'Raih Hasil & Sertifikat') }}">
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control" name="steps_3_desc" rows="2">{{ old('steps_3_desc', $settings['steps_3_desc']->value ?? '') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        {{-- PELATIHAN SECTION --}}
        <div class="section-accordion">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-backpack me-2 text-warning"></i>Section Pelatihan
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Badge</label>
                <input type="text" class="form-control" name="pelatihan_badge" value="{{ old('pelatihan_badge', $settings['pelatihan_badge']->value ?? 'Program Unggulan') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="pelatihan_title" value="{{ old('pelatihan_title', $settings['pelatihan_title']->value ?? 'Pelatihan yang Tersedia') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Subjudul</label>
                <input type="text" class="form-control" name="pelatihan_subtitle" value="{{ old('pelatihan_subtitle', $settings['pelatihan_subtitle']->value ?? '') }}">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Kosong - Judul</label>
                <input type="text" class="form-control" name="pelatihan_empty_title" value="{{ old('pelatihan_empty_title', $settings['pelatihan_empty_title']->value ?? 'Belum Ada Pelatihan Aktif') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Kosong - Deskripsi</label>
                <input type="text" class="form-control" name="pelatihan_empty_desc" value="{{ old('pelatihan_empty_desc', $settings['pelatihan_empty_desc']->value ?? '') }}">
              </div>
            </div>
          </div>
        </div>

        {{-- WHY SECTION --}}
        <div class="section-accordion">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-question-mark me-2 text-primary"></i>Section "Mengapa"
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Badge</label>
                <input type="text" class="form-control" name="why_badge" value="{{ old('why_badge', $settings['why_badge']->value ?? 'Mengapa Memilih') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="why_title" value="{{ old('why_title', $settings['why_title']->value ?? 'Mengapa Memilih Pelatihan Kami?') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Subjudul</label>
                <textarea class="form-control" name="why_subtitle" rows="2">{{ old('why_subtitle', $settings['why_subtitle']->value ?? '') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        {{-- CTA SECTION --}}
        <div class="section-accordion">
          <div class="section-accordion-header" onclick="this.parentElement.classList.toggle('open')">
            <h5 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-bolt me-2 text-danger"></i>CTA / Call-to-Action
            </h5>
            <i class="icon-base ti tabler-chevron-down chevron-icon"></i>
          </div>
          <div class="section-accordion-body">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Badge</label>
                <input type="text" class="form-control" name="cta_badge" value="{{ old('cta_badge', $settings['cta_badge']->value ?? 'SEGERA BERGABUNG') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="cta_title" value="{{ old('cta_title', $settings['cta_title']->value ?? 'Siap Memulai Perjalanan Anda?') }}">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Subjudul</label>
                <textarea class="form-control" name="cta_subtitle" rows="2">{{ old('cta_subtitle', $settings['cta_subtitle']->value ?? '') }}</textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Tombol Daftar</label>
                <input type="text" class="form-control" name="cta_button_text" value="{{ old('cta_button_text', $settings['cta_button_text']->value ?? 'Daftar Sekarang — Gratis!') }}">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Tombol Login</label>
                <input type="text" class="form-control" name="cta_login_text" value="{{ old('cta_login_text', $settings['cta_login_text']->value ?? 'Sudah Punya Akun? Login') }}">
              </div>
            </div>
          </div>
        </div>

        <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

        <div class="d-flex justify-content-between align-items-center gap-3">
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

</div>
@endsection

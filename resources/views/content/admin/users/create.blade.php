@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Tambah User Baru')

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
    z-index: 1;
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

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }

  /* Form Overrides */
  .form-control,
  .form-select {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control:focus,
  .form-select:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .form-control.is-invalid,
  .form-select.is-invalid {
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
  .form-select option {
    background-color: #0f172a !important;
    color: #ffffff !important;
  }

  /* Form Hint Text */
  .form-hint {
    font-size: 0.78rem;
    color: rgba(255, 255, 255, 0.45);
    margin-top: 5px;
    display: flex;
    align-items: flex-start;
    gap: 5px;
    line-height: 1.4;
  }
  .form-hint i {
    font-size: 0.8rem;
    margin-top: 1px;
    flex-shrink: 0;
  }
  .form-hint.hint-warning {
    color: rgba(251, 191, 36, 0.8);
  }

  /* Inline error messages */
  .field-error {
    font-size: 0.78rem;
    color: #f87171;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .field-error i {
    font-size: 0.8rem;
    flex-shrink: 0;
  }

  /* Buttons */
  .btn-glow-premium {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border: none;
    color: #ffffff !important;
    font-family: 'Sora', sans-serif;
    font-weight: 700;
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    transition: all 0.3s ease;
  }
  .btn-glow-premium:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.5);
    background: linear-gradient(135deg, #4f46e5, #c026d3) !important;
    color: #ffffff !important;
  }
  .btn-glow-premium:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
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

  /* Divider in form */
  .form-section-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    margin: 1.75rem 0;
  }

  /* Autofill Overrides */
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

  /* Spinner on submit */
  .btn-spinner {
    display: none;
  }
  [data-submitting="true"] .btn-spinner {
    display: inline-block;
  }
  [data-submitting="true"] .btn-icon-save {
    display: none;
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

    <!-- Title Section -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-user-plus fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Tambah User Baru</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Buat akun pengguna baru untuk mengakses sistem
            </p>
          </div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary-custom px-3 py-2">
          <i class="icon-base ti tabler-arrow-left me-1"></i> Kembali ke Daftar
        </a>
      </div>
    </div>

    <!-- Form Card -->
    <div class="col-12 mb-5">
      <div class="glass-card-premium px-4 px-xl-5 py-5">

        <form
          action="{{ route('admin.users.store') }}"
          method="POST"
          x-data="{
            submitting: false,
            submitForm(e) {
              if (this.submitting) {
                e.preventDefault();
                return;
              }
              this.submitting = true;
            }
          }"
          @submit="submitForm($event)"
        >
          @csrf

          <!-- Nama Lengkap -->
          <div class="mb-4">
            <label for="name" class="form-label">
              Nama Lengkap <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              class="form-control @error('name') is-invalid @enderror"
              id="name"
              name="name"
              value="{{ old('name') }}"
              placeholder="Masukkan nama lengkap"
              autocomplete="off"
            >
            <div class="form-hint">
              <i class="icon-base ti tabler-info-circle"></i>
              Nama akan otomatis disimpan dalam huruf kapital.
            </div>
            @error('name')
              <div class="field-error">
                <i class="icon-base ti tabler-alert-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="form-label">
              Email <span class="text-danger">*</span>
            </label>
            <input
              type="email"
              class="form-control @error('email') is-invalid @enderror"
              id="email"
              name="email"
              value="{{ old('email') }}"
              placeholder="Masukkan alamat email"
              autocomplete="off"
            >
            @error('email')
              <div class="field-error">
                <i class="icon-base ti tabler-alert-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <!-- No. WhatsApp / HP -->
          <div class="mb-4">
            <label for="whatsapp" class="form-label">
              No. WhatsApp / HP <span class="text-danger">*</span>
            </label>
            <input
              type="text"
              class="form-control @error('whatsapp') is-invalid @enderror"
              id="whatsapp"
              name="whatsapp"
              value="{{ old('whatsapp') }}"
              placeholder="Contoh: 081234567890"
              autocomplete="off"
            >
            <div class="form-hint hint-warning">
              <i class="icon-base ti tabler-key"></i>
              Password awal akan diset: <strong>Plh@[No_WhatsApp]</strong>&nbsp;(contoh: <code style="background:rgba(255,255,255,0.08); padding:1px 6px; border-radius:3px; color: #fbbf24;">Plh@081234567890</code>)
            </div>
            @error('whatsapp')
              <div class="field-error">
                <i class="icon-base ti tabler-alert-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form-section-divider"></div>

          <!-- NIK (Opsional) -->
          <div class="mb-4">
            <label for="nik" class="form-label">
              NIK <span class="text-body-premium" style="font-size:0.7rem; text-transform:none; letter-spacing:0; font-weight:400;">(Opsional)</span>
            </label>
            <input
              type="text"
              class="form-control @error('nik') is-invalid @enderror"
              id="nik"
              name="nik"
              value="{{ old('nik') }}"
              placeholder="Masukkan 16 digit NIK"
              maxlength="16"
              autocomplete="off"
            >
            <div class="form-hint">
              <i class="icon-base ti tabler-id"></i>
              Maksimal 16 digit. Biarkan kosong jika belum tersedia.
            </div>
            @error('nik')
              <div class="field-error">
                <i class="icon-base ti tabler-alert-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="form-section-divider"></div>

          <!-- Role & Status — 2 column row -->
          <div class="row">
            <!-- Role -->
            <div class="col-md-6 mb-4">
              <label for="role" class="form-label">
                Role <span class="text-danger">*</span>
              </label>
              <select
                class="form-select @error('role') is-invalid @enderror"
                id="role"
                name="role"
              >
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Role --</option>
                <option value="peserta"     {{ old('role') === 'peserta'     ? 'selected' : '' }}>Peserta</option>
                <option value="instruktur"  {{ old('role') === 'instruktur'  ? 'selected' : '' }}>Instruktur</option>
                <option value="koordinator" {{ old('role') === 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                <option value="admin"       {{ old('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
              </select>
              @error('role')
                <div class="field-error">
                  <i class="icon-base ti tabler-alert-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>

            <!-- Status Aktif -->
            <div class="col-md-6 mb-4">
              <label for="is_active" class="form-label">
                Status Aktif <span class="text-danger">*</span>
              </label>
              <select
                class="form-select @error('is_active') is-invalid @enderror"
                id="is_active"
                name="is_active"
              >
                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active') == '0'      ? 'selected' : '' }}>Non-Aktif</option>
              </select>
              @error('is_active')
                <div class="field-error">
                  <i class="icon-base ti tabler-alert-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="d-flex justify-content-end align-items-center gap-3 mt-4" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary-custom px-4 py-2">
              <i class="icon-base ti tabler-x me-1"></i> Batal
            </a>
            <button
              type="submit"
              class="btn btn-glow-premium px-4 py-2"
              :disabled="submitting"
            >
              <span class="btn-spinner spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" x-show="submitting"></span>
              <i class="icon-base ti tabler-device-floppy me-1 btn-icon-save" x-show="!submitting"></i>
              <span x-text="submitting ? 'Menyimpan...' : 'Simpan User'">Simpan User</span>
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
@endsection

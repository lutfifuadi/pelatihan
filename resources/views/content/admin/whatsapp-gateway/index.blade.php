@php
$configData = Helper::appClasses();

$allFilled = !empty($settings['whatsapp_send_url']->value ?? '') &&
             !empty($settings['whatsapp_api_url']->value ?? '') &&
             !empty($settings['whatsapp_api_key']->value ?? '') &&
             !empty($settings['whatsapp_sender']->value ?? '');
@endphp

@extends('layouts/layoutMaster')

@section('title', 'WhatsApp Gateway')

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

  .stat-icon-primary {
    background: rgba(99, 102, 241, 0.12);
    color: #6366f1;
  }
  .stat-icon-success {
    background: rgba(16, 185, 129, 0.12);
    color: #34d399;
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
  .badge-premium-success {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.3);
    color: #34d399;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
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

  .input-group-custom {
    position: relative;
  }
  .input-group-custom .form-control {
    padding-right: 44px !important;
  }
  .input-group-custom .toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    padding: 6px;
    z-index: 2;
    transition: color 0.2s;
  }
  .input-group-custom .toggle-password:hover {
    color: rgba(255, 255, 255, 0.8);
  }

  .info-banner {
    background: rgba(6, 182, 212, 0.08);
    border: 1px solid rgba(6, 182, 212, 0.2);
    border-radius: 5px;
    color: #22d3ee;
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
          <div class="stat-icon-box {{ $allFilled ? 'stat-icon-success' : 'stat-icon-primary' }}">
            <i class="icon-base ti tabler-brand-whatsapp fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">WhatsApp Gateway</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Konfigurasi API WhatsApp untuk notifikasi
            </p>
          </div>
        </div>
        <div>
          @if($allFilled)
            <span class="badge-premium badge-premium-success d-flex align-items-center gap-2 px-3 py-2">
              <i class="icon-base ti tabler-check-circle fs-6"></i> Terkonfigurasi
            </span>
          @else
            <span class="badge-premium badge-premium-danger d-flex align-items-center gap-2 px-3 py-2">
              <i class="icon-base ti tabler-alert-circle fs-6"></i> Belum dikonfigurasi
            </span>
          @endif
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
          <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="info-banner px-4 py-3 mb-4 d-flex align-items-center gap-3">
      <i class="icon-base ti tabler-info-circle fs-5 flex-shrink-0"></i>
      <span style="font-size: 0.9rem;">
        Pengaturan ini digunakan untuk mengirim notifikasi password ke koordinator via WhatsApp. Pastikan API Key dan URL sudah benar sebelum menyimpan.
      </span>
    </div>

    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="{{ route('admin.whatsapp-gateway.update') }}" method="POST">
          @csrf

          <div class="mb-4">
            <label for="whatsapp_send_url" class="form-label">URL Kirim Pesan <span class="text-danger">*</span></label>
            <input type="url" class="form-control @error('whatsapp_send_url') is-invalid @enderror"
              id="whatsapp_send_url" name="whatsapp_send_url"
              value="{{ $settings['whatsapp_send_url']->value ?? '' }}"
              placeholder="https://example.com/send-message" required>
            @error('whatsapp_send_url')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="whatsapp_api_url" class="form-label">URL Cek Nomor</label>
            <input type="url" class="form-control @error('whatsapp_api_url') is-invalid @enderror"
              id="whatsapp_api_url" name="whatsapp_api_url"
              value="{{ $settings['whatsapp_api_url']->value ?? '' }}"
              placeholder="https://example.com/check-number">
            @error('whatsapp_api_url')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="whatsapp_api_key" class="form-label">API Key <span class="text-danger">*</span></label>
            <div class="input-group-custom">
              <input type="password" class="form-control @error('whatsapp_api_key') is-invalid @enderror"
                id="whatsapp_api_key" name="whatsapp_api_key"
                value="{{ $settings['whatsapp_api_key']->value ?? '' }}"
                placeholder="Masukkan API Key" required>
              <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1">
                <i id="password-icon" class="icon-base ti tabler-eye fs-5"></i>
              </button>
            </div>
            @error('whatsapp_api_key')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="whatsapp_sender" class="form-label">Nomor Pengirim</label>
            <input type="text" class="form-control @error('whatsapp_sender') is-invalid @enderror"
              id="whatsapp_sender" name="whatsapp_sender"
              value="{{ $settings['whatsapp_sender']->value ?? '' }}"
              placeholder="62812xxxxxxx">
            <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
              Format internasional tanpa +, contoh: 6281234567890
            </small>
            @error('whatsapp_sender')
              <div class="invalid-feedback mt-1">{{ $message }}</div>
            @enderror
          </div>


          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <div class="d-flex gap-3">
              <form action="{{ route('admin.whatsapp-gateway.test') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
                  <i class="icon-base ti tabler-send"></i> Kirim Test WA
                </button>
              </form>
              <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
                <i class="icon-base ti tabler-device-floppy"></i> Simpan Pengaturan
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

@section('page-script')
<script>
function togglePassword() {
  const input = document.getElementById('whatsapp_api_key');
  const icon = document.getElementById('password-icon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.className = 'icon-base ti tabler-eye-off fs-5';
  } else {
    input.type = 'password';
    icon.className = 'icon-base ti tabler-eye fs-5';
  }
}
</script>
@endsection

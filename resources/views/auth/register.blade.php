@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Daftar Akun Baru')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  #auth-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow: hidden;
    height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }
  @media (max-height: 750px) {
    #auth-page-wrapper { height: auto; min-height: 100vh; overflow-y: auto; padding: 20px 0; }
  }
  #auth-page-wrapper h1, #auth-page-wrapper h2, #auth-page-wrapper h3,
  #auth-page-wrapper h4, #auth-page-wrapper h5, #auth-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }
  .hero-gradient-animated {
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
  }
  .glow-orb {
    position: absolute; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 2;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  /* --- Glass Card Lebar --- */
  .glass-card-wide {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    width: 100%;
    max-width: 620px;
    z-index: 10;
    padding: 32px 30px;
  }
  @media (max-width: 660px) {
    .glass-card-wide { max-width: 420px; padding: 24px 20px; }
  }

  .logo-icon-glow {
    width: 38px; height: 38px; border-radius: 5px;
    background: linear-gradient(135deg, #6366f1, #d946ef);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
  }
  .logo-text-glow {
    font-family: 'Sora', sans-serif; font-size: 1.25rem;
    font-weight: 800; color: #ffffff; letter-spacing: -0.5px;
  }

  /* --- Input Fields --- */
  .form-control-custom {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control-custom:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
  .form-control-custom.is-invalid { border-color: #f87171 !important; box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important; }
  .form-control-custom:-webkit-autofill,
  .form-control-custom:-webkit-autofill:hover,
  .form-control-custom:-webkit-autofill:focus,
  .form-control-custom:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
  }
  .form-label-custom {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 4px;
  }
  .input-group-text {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-weight: 600;
    border-radius: 5px !important;
    border-left: none !important;
    padding: 10px 14px !important;
    transition: all 0.3s ease !important;
  }
  .input-group-merge .form-control-custom { border-right: none !important; border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
  .input-group-merge .input-group-text { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; }
  .input-group-merge:focus-within .input-group-text { border-color: #6366f1 !important; }

  .btn-glow {
    position: relative; overflow: hidden; transition: all 0.3s ease; border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }
  .form-check-input-custom { background-color: rgba(255, 255, 255, 0.05) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; }
  .form-check-input-custom:checked { background-color: #6366f1 !important; border-color: #6366f1 !important; }
  .text-white-50-custom { color: rgba(255, 255, 255, 0.5) !important; }

  /* --- Grid 2 Kolom --- */
  .field-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  @media (max-width: 660px) {
    .field-group { grid-template-columns: 1fr; gap: 0; }
  }
  .field-full { grid-column: 1 / -1; }
</style>
@endsection

@section('content')
<div id="auth-page-wrapper">
  <div class="hero-gradient-animated"></div>
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="glass-card-wide">
    <!-- Logo -->
    <div class="d-flex justify-content-center mb-3">
      <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <div class="logo-icon-glow"><i class="icon-base ti tabler-bulb text-white fs-4"></i></div>
        <x-brand-logo size="lg" />
      </a>
    </div>

    <!-- Title -->
    <div class="text-center mb-4">
      <h4 class="mb-0 text-white fw-bold">Daftar Akun Baru 🚀</h4>
      <p class="text-white-50-custom small mt-1">Lengkapi data diri Anda untuk memulai</p>
    </div>

    <form id="formRegister" action="{{ route('register') }}" method="POST">
      @csrf

      <!-- Grid 2 Kolom -->
      <div class="field-group">

        <!-- Nama Lengkap -->
        <div class="mb-0">
          <label for="name" class="form-label form-label-custom">Nama Lengkap Sesuai KTP</label>
          <input type="text" id="name" name="name"
            class="form-control form-control-custom @error('name') is-invalid @enderror"
            placeholder="Andi Pratama" value="{{ old('name') }}" required />
          @error('name') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- NIK -->
        <div class="mb-0">
          <label for="nik" class="form-label form-label-custom">NIK (Username Login)</label>
          <input type="text" id="nik" name="nik"
            class="form-control form-control-custom @error('nik') is-invalid @enderror"
            placeholder="15-16 digit NIK" maxlength="16" inputmode="numeric" value="{{ old('nik') }}" required />
          <div id="nik-feedback" class="small mt-1 d-none"></div>
          @error('nik') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- WhatsApp -->
        <div class="mb-0">
          <label for="whatsapp" class="form-label form-label-custom">Nomor WhatsApp Aktif</label>
          <input type="tel" id="whatsapp" name="whatsapp"
            class="form-control form-control-custom @error('whatsapp') is-invalid @enderror"
            placeholder="0821xxxxxxxx" value="{{ old('whatsapp') }}" required />
          <div id="wa-feedback" class="small mt-1 d-none"></div>
          <small id="wa-format-hint" class="text-white-50-custom d-block mt-1" style="font-size: 11px;">Format: 08xx, otomatis jadi 628xx</small>
          @error('whatsapp') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Email -->
        <div class="mb-0">
          <label for="email" class="form-label form-label-custom">Email</label>
          <input type="email" id="email" name="email"
            class="form-control form-control-custom @error('email') is-invalid @enderror"
            placeholder="contoh@email.com" value="{{ old('email') }}" required />
          @error('email') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
        </div>

        <!-- Password Info -->
        <div class="mb-0 field-full">
          <div class="d-flex align-items-start gap-2 p-3 rounded-3" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.15);">
            <i class="icon-base ti tabler-info-circle text-warning mt-1 flex-shrink-0"></i>
            <div>
              <p class="text-white-50-custom small mb-0" style="line-height: 1.4;">
                Password akun Anda akan diisi secara otomatis. 
                <span class="text-warning fw-semibold">pelatihanku2026</span>
              </p>
            </div>
          </div>
        </div>

      </div>

      <!-- Full Width: Terms -->
      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
      <div class="mt-4 mb-4 field-full">
        <div class="form-check ms-1 @error('terms') is-invalid @enderror">
          <input class="form-check-input form-check-input-custom @error('terms') is-invalid @enderror" type="checkbox" id="terms" name="terms" required />
          <label class="form-check-label text-white-50-custom small" for="terms">
            Saya menyetujui <a href="{{ route('policy.show') }}" target="_blank" class="text-warning text-decoration-none fw-semibold">kebijakan privasi</a> &amp; <a href="{{ route('terms.show') }}" target="_blank" class="text-warning text-decoration-none fw-semibold">ketentuan layanan</a>
          </label>
        </div>
        @error('terms') <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div> @enderror
      </div>
      @else
      <div class="mt-4 mb-4 field-full"></div>
      @endif

      <!-- Full Width: Submit -->
      <button class="btn btn-warning w-100 fw-semibold btn-glow py-2 field-full" type="submit" style="border-radius: 5px; font-size: 14px;">
        Daftar Sekarang <i class="icon-base ti tabler-arrow-right ms-2"></i>
      </button>
    </form>

    <!-- Divider -->
    <div class="d-flex align-items-center gap-3 my-4">
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
      <span class="text-white-50-custom" style="font-size: 12px; letter-spacing: 0.05em;">atau</span>
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
    </div>

    <!-- Login Link -->
    <p class="text-center mb-0">
      <span class="text-white-50-custom small">Sudah punya akun? </span>
      @if (Route::has('login'))
      <a href="{{ route('login') }}" class="small fw-semibold text-warning text-decoration-none hover-white">Login di sini</a>
      @endif
    </p>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // ============================================================
  // 1. NIK INPUT — Auto filter & Check via AJAX
  // ============================================================
  const nikInput = document.getElementById('nik');
  const nikFeedback = document.getElementById('nik-feedback');

  if (nikInput) {
    let nikTimeout = null;
    nikInput.addEventListener('input', function() {
      clearTimeout(nikTimeout);
      const nik = this.value.replace(/\D/g, '');
      this.value = nik;
      if (nik.length < 15 || nik.length > 16) {
        nikFeedback.classList.add('d-none');
        nikFeedback.className = 'small mt-1 d-none';
        nikFeedback.textContent = '';
        return;
      }
      nikFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      nikFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa NIK...';
      nikFeedback.classList.remove('d-none');
      nikTimeout = setTimeout(function() {
        fetch('{{ route('landing.check-nik') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ nik: nik })
        })
        .then(res => res.json())
        .then(data => {
          nikFeedback.classList.remove('d-none');
          if (data.exists) {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-alert-circle me-1"></i> ' + data.message;
          } else {
            nikFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            nikFeedback.innerHTML = '<i class="icon-base ti tabler-check-circle me-1"></i> NIK tersedia';
            setTimeout(function() { nikFeedback.classList.add('d-none'); }, 3000);
          }
        })
        .catch(function() {
          nikFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
          nikFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal memeriksa NIK';
        });
      }, 500);
    });
  }

  // ============================================================
  // 2. WHATSAPP INPUT — Auto-convert & Check WA Registration
  // ============================================================
  const waInput = document.getElementById('whatsapp');
  const waFeedback = document.getElementById('wa-feedback');
  const waHint = document.getElementById('wa-format-hint');

  function convertWaNumber(num) {
    num = num.replace(/\D/g, '');
    if (num.startsWith('0')) return '62' + num.substring(1);
    if (num.startsWith('62') && num.length >= 10) return num;
    return '62' + num;
  }

  if (waInput) {
    let waTimeout = null;
    waInput.addEventListener('input', function() {
      clearTimeout(waTimeout);
      const raw = this.value.replace(/\D/g, '');
      this.value = raw;
      if (raw.length >= 4) {
        const converted = convertWaNumber(raw);
        if (waHint) waHint.textContent = 'Format: 08xx → ' + converted;
      } else {
        if (waHint) waHint.textContent = 'Format: 08xx, otomatis jadi 628xx';
      }
      if (raw.length < 8) {
        waFeedback.classList.add('d-none');
        waFeedback.className = 'small mt-1 d-none';
        waFeedback.textContent = '';
        return;
      }
      waFeedback.className = 'small mt-1 d-flex align-items-center text-info';
      waFeedback.innerHTML = '<div class="spinner-border spinner-border-xs me-1" style="width:12px;height:12px;border-width:2px;"></div> Memeriksa nomor WhatsApp...';
      waFeedback.classList.remove('d-none');
      waTimeout = setTimeout(function() {
        const finalNumber = convertWaNumber(raw);
        fetch('{{ route('landing.check-wa') }}', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ number: finalNumber })
        })
        .then(res => res.json())
        .then(data => {
          waFeedback.classList.remove('d-none');
          if (data.exists) {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-success';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-brand-whatsapp me-1"></i> Nomor WhatsApp terdaftar';
          } else {
            waFeedback.className = 'small mt-1 d-flex align-items-center text-danger';
            waFeedback.innerHTML = '<i class="icon-base ti tabler-alert-triangle me-1"></i> Nomor tidak terdaftar di WA';
          }
        })
        .catch(function() {
          waFeedback.className = 'small mt-1 d-flex align-items-center text-warning';
          waFeedback.innerHTML = '<i class="icon-base ti tabler-cloud-off me-1"></i> Gagal verifikasi WA';
        });
      }, 600);
    });
  }
});
</script>
@endsection

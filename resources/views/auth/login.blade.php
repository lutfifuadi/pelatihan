@php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
<style>
  /* --- Fonts Import for Premium Look --- */
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  #auth-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow: hidden;
    height: 100dvh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  @media (max-height: 600px) {
    #auth-page-wrapper {
      overflow-y: auto;
    }
    .glass-card-premium {
      padding: 20px 16px;
    }
  }
  #auth-page-wrapper::-webkit-scrollbar { display: none; }
  #auth-page-wrapper h1, 
  #auth-page-wrapper h2, 
  #auth-page-wrapper h3, 
  #auth-page-wrapper h4, 
  #auth-page-wrapper h5, 
  #auth-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* --- Premium Mesh Gradient Backdrop --- */
  .hero-gradient-animated {
    background: #0b0f19;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
  }

  /* --- Dynamic Floating Orbs --- */
  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.4;
    mix-blend-mode: screen;
    pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out;
    z-index: 2;
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

  /* --- Glass Card Redesign --- */
  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    width: 100%;
    max-width: 420px;
    z-index: 10;
    padding: 32px 28px;
  }
  .glass-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5), 0 0 30px rgba(99, 102, 241, 0.08);
  }

  /* --- Logo branding --- */
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
  .logo-text-glow {
    font-family: 'Sora', sans-serif;
    font-size: 1.25rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
  }

  /* --- Input Fields Redesign --- */
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
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25), 0 0 20px rgba(99, 102, 241, 0.15) !important;
    color: #ffffff !important;
  }
  .form-control-custom::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .form-control-custom.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important;
  }
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
    font-size: 0.75rem !important;
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
  .input-group-merge .form-control-custom {
    border-right: none !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .input-group-merge .input-group-text {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
  }
  .input-group-merge:focus-within .input-group-text {
    border-color: #6366f1 !important;
  }
  
  /* --- Buttons with Pulse Glow --- */
  .btn-glow {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    border: none;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    color: #0b0f19 !important;
  }
  .btn-glow:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 30px rgba(255, 152, 0, 0.5);
    background: linear-gradient(135deg, #ffca28, #ffa726);
  }

  /* --- Checkbox Styling --- */
  .form-check-input-custom {
    background-color: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
  }
  .form-check-input-custom:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
  }

  /* --- Social Icons --- */
  .social-icon-btn-custom {
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
  .social-icon-btn-custom:hover {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border-color: transparent;
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3);
  }
  
  .divider-text-custom {
    color: rgba(255, 255, 255, 0.4) !important;
    background: transparent !important;
  }
  .divider-custom::before,
  .divider-custom::after {
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
  }
  
  .text-white-50-custom {
    color: rgba(255, 255, 255, 0.5) !important;
  }
</style>
@endsection

@section('content')
<div id="auth-page-wrapper">
  <!-- Mesh Gradient Background -->
  <div class="hero-gradient-animated"></div>

  <!-- Glow Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Glass Card Premium Form -->
  <div class="glass-card-premium">
    <!-- Logo -->
    <div class="d-flex justify-content-center mb-4">
      <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <div class="logo-icon-glow">
          <i class="icon-base ti tabler-bulb text-white fs-4"></i>
        </div>
        <x-brand-logo size="lg" />
      </a>
    </div>
    
    <!-- Title / Welcome -->
    <div class="text-center mb-4">
      <h4 class="mb-0 text-white fw-bold">Selamat Datang! 👋</h4>
      <p class="text-white-50-custom small mt-1">Silakan login ke akun Anda</p>
    </div>

    @if (session('status'))
    <div class="alert alert-success mb-4 border-0" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
      <div class="d-flex align-items-center">
        <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
        <span class="small fw-semibold">{{ session('status') }}</span>
      </div>
    </div>
    @endif

    <form id="formAuthentication" action="{{ route('login') }}" method="POST">
      @csrf
      
      <!-- Input NIK (Username) -->
      <div class="mb-3">
        <label for="login-nik" class="form-label form-label-custom">NIK (Username)</label>
        <input type="text" class="form-control form-control-custom @error('nik') is-invalid @enderror" 
          id="login-nik" name="nik" placeholder="Masukkan NIK" maxlength="16" inputmode="numeric"
          autofocus value="{{ old('nik') }}" required />
        @error('nik')
        <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Input Password -->
      <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between mb-1">
          <label class="form-label form-label-custom" for="login-password">Password</label>
        </div>
        <div class="input-group input-group-merge @error('password') is-invalid @enderror">
          <input type="password" id="login-password" class="form-control form-control-custom @error('password') is-invalid @enderror"
            name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password" required />
          <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
        </div>
        @error('password')
        <div class="invalid-feedback small mt-1 text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Remember Me & Forgot Password -->
      <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check mb-0">
            <input class="form-check-input form-check-input-custom" type="checkbox" id="remember-me" name="remember"
              {{ old('remember') ? 'checked' : '' }} />
            <label class="form-check-label text-white-50-custom small" for="remember-me"> Ingat Saya </label>
          </div>
          @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="small fw-semibold text-warning text-decoration-none hover-white">
            Lupa Password?
          </a>
          @endif
        </div>
      </div>

      <!-- Button Submit -->
      <button class="btn btn-warning w-100 fw-semibold btn-glow py-2" type="submit" style="border-radius: 5px; font-size: 14px;">
        Masuk <i class="icon-base ti tabler-login ms-2"></i>
      </button>
    </form>

    <!-- Divider -->
    <div class="d-flex align-items-center gap-3 my-4">
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
      <span class="text-white-50-custom" style="font-size: 12px; letter-spacing: 0.05em;">atau</span>
      <hr class="flex-grow-1" style="border-color: rgba(255,255,255,0.08); margin: 0;">
    </div>

    <!-- Sign Up Link -->
    <p class="text-center mb-0">
      <span class="text-white-50-custom small">Belum punya akun? </span>
      @if (Route::has('register'))
      <a href="{{ route('register') }}" class="small fw-semibold text-warning text-decoration-none hover-white">
        Daftar di sini
      </a>
      @endif
    </p>
  </div>
</div>
@endsection
@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', __('Pendaftaran Berhasil!'))

@section('page-style')
<style>
  /* --- Fonts Import for Premium Look --- */
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

  /* --- Typography Base --- */
  #confirm-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
    overflow: hidden;
  }
  #confirm-page-wrapper h1, 
  #confirm-page-wrapper h2, 
  #confirm-page-wrapper h3, 
  #confirm-page-wrapper h4, 
  #confirm-page-wrapper h5, 
  #confirm-page-wrapper h6 {
    font-family: 'Sora', sans-serif;
  }

  /* --- Premium Mesh Gradient Backdrop --- */
  .confirm-gradient {
    background: #0b0f19;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
    position: relative;
    overflow: hidden;
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
  }
  .orb-1 {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, #6366f1 0%, rgba(99, 102, 241, 0) 70%);
    top: -10%;
    left: -10%;
    animation-duration: 20s;
  }
  .orb-2 {
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, #ec4899 0%, rgba(236, 72, 153, 0) 70%);
    bottom: 5%;
    right: -10%;
    animation-duration: 28s;
  }
  .orb-3 {
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, #06b6d4 0%, rgba(6, 182, 212, 0) 70%);
    top: 35%;
    left: 20%;
    animation-duration: 24s;
  }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(50px, 30px) scale(1.05) rotate(180deg); }
    100% { transform: translate(-25px, -40px) scale(0.95) rotate(360deg); }
  }

  /* --- Brand Logo --- */
  .logo-icon-glow {
    width: 36px;
    height: 36px;
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
    font-size: 1.15rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.5px;
  }

  /* --- Success Glow Icon --- */
  .success-glow {
    width: 58px;
    height: 58px;
    border-radius: 5px;
    background: linear-gradient(135deg, #10b981, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 30px rgba(16, 185, 129, 0.35), 0 5px 15px rgba(16, 185, 129, 0.15);
    animation: successPulse 2.5s ease-in-out infinite;
  }
  @keyframes successPulse {
    0%, 100% { box-shadow: 0 0 30px rgba(16, 185, 129, 0.35), 0 5px 15px rgba(16, 185, 129, 0.15); }
    50% { box-shadow: 0 0 45px rgba(16, 185, 129, 0.5), 0 5px 15px rgba(16, 185, 129, 0.25); }
  }
  .check-animate {
    animation: checkScale 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    transform: scale(0);
  }
  @keyframes checkScale {
    0% { transform: scale(0) rotate(-30deg); opacity: 0; }
    100% { transform: scale(1) rotate(0deg); opacity: 1; }
  }

  /* --- Fade In Stagger Animations --- */
  .fade-up {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
  }
  .fade-up:nth-child(1) { animation-delay: 0.1s; }
  .fade-up:nth-child(2) { animation-delay: 0.2s; }
  .fade-up:nth-child(3) { animation-delay: 0.3s; }
  .fade-up:nth-child(4) { animation-delay: 0.4s; }
  @keyframes fadeUp {
    to { opacity: 1; transform: translateY(0); }
  }

  /* --- Premium Translucent Info Cards --- */
  .info-card {
    background: rgba(15, 23, 42, 0.45);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .info-card:hover {
    background: rgba(15, 23, 42, 0.65);
    border-color: rgba(99, 102, 241, 0.3);
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.15);
    transform: translateY(-2px);
  }

  /* --- Premium Action Button --- */
  .btn-next {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    color: #ffffff !important;
    border: none;
    border-radius: 5px;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(99, 102, 241, 0.35);
  }
  .btn-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.55);
    filter: brightness(1.1);
  }
</style>
@endsection

@section('content')
<div id="confirm-page-wrapper">
  <div class="confirm-gradient min-vh-100 d-flex align-items-center justify-content-center px-3">

    <!-- Floating Background Orbs -->
    <div class="glow-orb orb-1"></div>
    <div class="glow-orb orb-2"></div>
    <div class="glow-orb orb-3"></div>

    <!-- Top Left Floating Brand Header -->
    <div style="position: absolute; top: 30px; left: 30px; z-index: 10;">
      <a href="{{ route('pages-home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
        <div class="logo-icon-glow">
          <i class="icon-base ti tabler-bulb text-white fs-5"></i>
        </div>
        <x-brand-logo size="md" />
      </a>
    </div>

    <!-- Center Content -->
    <div class="text-center position-relative py-5" style="z-index: 5; max-width: 520px; width: 100%;">

      <!-- Success Glow Icon Box -->
      <div class="d-flex justify-content-center mb-3 fade-up">
        <div class="success-glow">
          <i class="icon-base ti tabler-check text-white check-animate" style="font-size: 26px; stroke-width: 3;"></i>
        </div>
      </div>

      <!-- Main Title -->
      <h3 class="fw-bold text-white mb-2 fade-up" style="letter-spacing: -0.5px; font-size: 1.8rem;">
        {{ __('Pendaftaran Berhasil!') }} 🎉
      </h3>

      <!-- Informative Subtitle -->
      <p class="text-white-50 mb-4 fade-up" style="max-width: 440px; margin-left: auto; margin-right: auto; line-height: 1.5; font-size: 0.98rem;">
        {{ __('Terima kasih telah mendaftar') }}, <strong class="text-white fw-semibold">{{ $user->name }}</strong>!<br>
        {{ __('Data Anda telah berhasil direkam dalam sistem') }}.
      </p>

      <!-- Glass Information Cards -->
      <div class="d-flex flex-column gap-3 mb-5 text-start fade-up">
        
        <!-- NIK Card -->
        <div class="info-card d-flex align-items-center gap-3 px-4 py-3">
          <div style="width: 42px; height: 42px; border-radius: 5px; background: rgba(99, 102, 241, 0.08); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="icon-base ti tabler-id"></i>
          </div>
          <div>
            <small class="text-white-50 d-block" style="font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600;">{{ __('NIK Anda (Username Login)') }}</small>
            <span class="text-white fw-medium fs-5" style="font-family: 'Sora', sans-serif;">{{ $user->nik }}</span>
          </div>
        </div>

        <!-- Email Card -->
        <div class="info-card d-flex align-items-center gap-3 px-4 py-3">
          <div style="width: 42px; height: 42px; border-radius: 5px; background: rgba(217, 70, 239, 0.08); color: #d946ef; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="icon-base ti tabler-mail"></i>
          </div>
          <div>
            <small class="text-white-50 d-block" style="font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600;">{{ __('Alamat Email') }}</small>
            <span class="text-white fw-medium fs-6">{{ $user->email }}</span>
          </div>
        </div>

        <!-- WhatsApp Card -->
        <div class="info-card d-flex align-items-center gap-3 px-4 py-3">
          <div style="width: 42px; height: 42px; border-radius: 5px; background: rgba(16, 185, 129, 0.08); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
            <i class="icon-base ti tabler-brand-whatsapp"></i>
          </div>
          <div>
            <small class="text-white-50 d-block" style="font-size: 10px; letter-spacing: 0.08em; text-transform: uppercase; font-weight: 600;">{{ __('Nomor WhatsApp Aktif') }}</small>
            <span class="text-white fw-medium fs-6">+62{{ $user->whatsapp }}</span>
          </div>
        </div>

      </div>

      <!-- Action Button Area -->
      <div class="fade-up">
        <a href="{{ route('dashboard.peserta') }}" class="btn btn-primary btn-lg px-5 py-3 fw-semibold btn-next d-inline-flex align-items-center gap-2">
          {{ __('Masuk ke Dashboard Peserta') }} <i class="icon-base ti tabler-arrow-right fs-5"></i>
        </a>
        <p class="text-white-50 small mt-4 mb-0">
          {{ __('Selamat belajar & berkarya!') }} 🚀
        </p>
      </div>

    </div>
  </div>
</div>
@endsection

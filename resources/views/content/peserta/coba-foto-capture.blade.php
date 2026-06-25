@php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Capture Foto')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

  body, #multistep-page-wrapper {
    font-family: 'Outfit', sans-serif;
    background-color: #0b0f19;
    color: #f8fafc;
  }

  .hero-gradient-capture {
    background: #0b0f19;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%);
    position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;
  }

  .glass-card-capture {
    background: rgba(15, 23, 42, 0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.45);
    border-radius: 5px;
    position: relative;
    width: 100%;
    max-width: 560px;
    z-index: 10;
    padding: 28px 24px;
  }
  @media (max-width: 660px) {
    .glass-card-capture { max-width: 100%; margin: 0 12px; padding: 20px 16px; }
  }
</style>
@endsection

@section('content')
<div id="multistep-page-wrapper" class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px 0;">
  <div class="hero-gradient-capture"></div>

  <div class="glass-card-capture">
    {{-- Header --}}
    <div class="text-center mb-3">
      <div style="width: 48px; height: 48px; border-radius: 5px; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
        <i class="icon-base ti tabler-camera text-white fs-4"></i>
      </div>
      <h4 class="fw-bold text-white mt-2 mb-0" style="font-family: 'Sora', sans-serif; font-size: 1.1rem;">Capture Foto</h4>
      <p class="text-white-50-custom small mt-1 mb-0">Ambil foto diri atau foto KTP Anda</p>
    </div>



    {{-- The Livewire Component --}}
    <livewire:foto-capture wire:key="foto-capture-main" />

    {{-- Hasil capture --}}
    <div x-data="{ captured: false }"
         x-on:foto-captured.window="captured = true">
      <template x-if="captured">
        <div class="mt-3 p-2" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 5px;">
          <div class="d-flex align-items-center gap-2">
            <i class="icon-base ti tabler-check-circle text-success fs-5"></i>
            <span class="text-white-70-custom small fw-semibold">Foto berhasil diambil!</span>
          </div>
          <small class="text-white-50-custom mt-1 d-block" style="font-size: 11px;">
            Data base64 siap dikirim ke backend.
          </small>
        </div>
      </template>
    </div>
  </div>
</div>

<style>
  [x-cloak] { display: none !important; }
</style>
@endsection

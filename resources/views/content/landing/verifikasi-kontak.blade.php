@php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/publicLayout')

@section('title', 'Verifikasi Kontak Resmi Admin')

@section('content')
<div id="beranda-page-wrapper">
@include('partials.floating-navbar')

<section class="section-py first-section-pt help-center-header position-relative overflow-hidden" style="background: linear-gradient(135deg, #0b0f19 0%, #1e1b4b 100%) !important; color: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Outfit', sans-serif; padding-top: 140px !important;">
  <!-- Glow Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Background illustration/glow effect -->
  <div class="position-absolute w-100 h-100 top-0 start-0 z-0 opacity-25" style="background-image: radial-gradient(circle at 80% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%), radial-gradient(circle at 20% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 50%); pointer-events: none;"></div>

  <div class="container py-5 z-1">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10">
        <div class="card border-0 shadow-lg text-white" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-radius: 16px; border: 1px solid rgba(255, 255, 255, 0.08);">
          <div class="card-body p-4 p-md-5">
            <!-- Header Brand / Logo -->
            <div class="text-center mb-4">
              <!-- Logos Officially Displayed Side-by-Side -->
              <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                <!-- Logo Pemkot Bandung -->
                <div class="bg-white px-3 py-1.5 rounded shadow-sm d-flex align-items-center gap-2" style="border: 1px solid rgba(255,255,255,0.15);">
                  <img src="{{ asset('assets/img/logo-pemkot.png') }}" alt="Logo Pemkot Bandung" style="height: 38px; width: auto; object-fit: contain;">
                  <div class="text-start">
                    <span class="text-dark fw-bold d-block" style="font-size: 10px; line-height: 1.1; letter-spacing: 0.5px;">PEMKOT</span>
                    <span class="text-muted fw-semibold" style="font-size: 8px; line-height: 1;">BANDUNG</span>
                  </div>
                </div>
              </div>
              
              <h2 class="h4 fw-bold text-white mb-2" style="font-family: 'Sora', sans-serif;">Verifikasi Kontak Resmi</h2>
              <p class="text-white-50 small mb-0 px-md-3">Masukkan nomor WhatsApp atau telepon untuk memverifikasi apakah nomor tersebut adalah admin resmi dinas penyelenggara terkait di bawah naungan Pemerintah Kota Bandung.</p>
            </div>

            <!-- Alert Badges (Notifikasi Hasil) -->
            @if (session('success'))
              <div class="alert border-0 d-flex align-items-start gap-3 mb-4 animate-fade-in" style="background: rgba(16, 185, 129, 0.2); border: 2px solid #10b981 !important; border-radius: 12px; box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);" role="alert">
                <i class="icon-base ti tabler-circle-check-filled text-success fs-2 mt-1"></i>
                <div>
                  <h6 class="text-success fw-bold mb-1" style="font-family: 'Sora', sans-serif; letter-spacing: 0.5px;">✓ TERVERIFIKASI RESMI</h6>
                  <p class="text-white fw-medium small mb-0" style="line-height: 1.45;">{{ session('success') }}</p>
                </div>
              </div>
            @endif

            @if (session('error'))
              <div class="alert border-0 d-flex align-items-start gap-3 mb-4 animate-fade-in" style="background: rgba(239, 68, 68, 0.2); border: 2px solid #ef4444 !important; border-radius: 12px; box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);" role="alert">
                <i class="icon-base ti tabler-circle-x-filled text-danger fs-2 mt-1"></i>
                <div>
                  <h6 class="text-danger fw-bold mb-1" style="font-family: 'Sora', sans-serif; letter-spacing: 0.5px;">⚠️ TIDAK TERDAFTAR (WASPADA)</h6>
                  <p class="text-white fw-medium small mb-0" style="line-height: 1.45;">{{ session('error') }}</p>
                </div>
              </div>
            @endif

            <!-- Form Verifikasi -->
            <form action="{{ route('verifikasi-kontak.check') }}" method="POST" autocomplete="off">
              @csrf
              <div class="mb-4">
                <label for="phone" class="form-label text-white-50 small fw-medium mb-2">Nomor WhatsApp / Telepon</label>
                <div class="input-group">
                  <span class="input-group-text bg-dark border-0 text-white-50" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;">
                    <i class="icon-base ti tabler-phone fs-5"></i>
                  </span>
                  <input type="text" 
                    class="form-control bg-dark border-0 text-white @error('phone') is-invalid @enderror" 
                    id="phone" 
                    name="phone" 
                    placeholder="Contoh: 0889894706xxx" 
                    value="{{ old('phone') }}" 
                    style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; padding: 12px;" 
                    required>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-2">
                  <small class="text-white-50" style="font-size: 11px;">Format: 08xx atau 628xx</small>
                  @error('phone')
                    <div class="invalid-feedback text-danger d-block small m-0">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="row g-2">
                <div class="col-8">
                  <button type="submit" class="btn btn-warning w-100 fw-semibold py-3" style="border-radius: 8px; font-family: 'Sora', sans-serif;">
                    <i class="icon-base ti tabler-search me-1 fs-5 align-middle"></i> Verifikasi Sekarang
                  </button>
                </div>
                <div class="col-4">
                  <a href="{{ url('/') }}" class="btn btn-outline-light w-100 py-3" style="border-radius: 8px;">
                    Kembali
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('partials.site-footer')
</div>
@endsection

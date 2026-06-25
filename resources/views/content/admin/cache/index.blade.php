@extends('layouts.layoutMaster')

@section('title', 'Clear Cache')

@section('page-style')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap');

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

  html, body, .layout-page, .content-wrapper,
  .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
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

  .text-body-premium {
    color: rgba(255, 255, 255, 0.65) !important;
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

  .stat-icon-primary { background: rgba(99, 102, 241, 0.12); color: #6366f1; }

  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-weight: 500;
    font-size: 0.75rem;
    white-space: nowrap;
  }

  .cache-inline-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 5px;
    padding: 14px 16px;
    transition: all 0.3s ease;
    cursor: default;
    height: 100%;
  }
  .cache-inline-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.12);
    transform: translateY(-1px);
  }

  .cache-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
  }

  .btn-gradient {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    color: #fff;
    border-radius: 5px;
    font-weight: 600;
    padding: 14px 32px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  .btn-gradient::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 5px;
  }
  .btn-gradient:hover:not(:disabled)::before {
    opacity: 1;
  }
  .btn-gradient:hover:not(:disabled) {
    box-shadow: 0 0 30px rgba(99, 102, 241, 0.4), 0 0 60px rgba(139, 92, 246, 0.2);
    transform: translateY(-2px);
    color: #fff;
  }
  .btn-gradient:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  .btn-gradient > * {
    position: relative;
    z-index: 1;
  }

  @keyframes spin-slow {
    to { transform: rotate(360deg); }
  }
  .animate-spin-slow {
    animation: spin-slow 1.2s linear infinite;
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .fade-in-up {
    animation: fadeInUp 0.4s ease forwards;
  }

  @keyframes checkPop {
    0%   { opacity: 0; transform: scale(0); }
    50%  { transform: scale(1.2); }
    100% { opacity: 1; transform: scale(1); }
  }
  .check-pop {
    animation: checkPop 0.35s ease forwards;
  }

  .result-item {
    opacity: 0;
    animation: fadeInUp 0.35s ease forwards;
  }
  .result-item:nth-child(1) { animation-delay: 0.05s; }
  .result-item:nth-child(2) { animation-delay: 0.10s; }
  .result-item:nth-child(3) { animation-delay: 0.15s; }
  .result-item:nth-child(4) { animation-delay: 0.20s; }
  .result-item:nth-child(5) { animation-delay: 0.25s; }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  {{-- Header --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary">
          <i class="icon-base ti tabler-broom fs-4"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-1">Clear Cache</h4>
          <p class="text-body-premium mb-0" style="font-size: 0.95rem;">
            Bersihkan semua cache aplikasi agar data selalu real-time dan performa optimal
          </p>
        </div>
      </div>
      <span class="badge-premium" style="background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #f87171;">
        <i class="icon-base ti tabler-shield-lock me-1"></i> Admin Only
      </span>
    </div>
  </div>

  {{-- Cache Cards --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <h6 class="fw-semibold text-white mb-3">
      <i class="icon-base ti tabler-server me-1"></i> Cache yang akan dibersihkan
    </h6>
    <div class="row g-2">
      <div class="col-12 col-md-6 col-xl mb-3 mb-xl-0">
        <div class="cache-inline-card d-flex align-items-center gap-3">
          <div class="cache-icon-box" style="background: rgba(99,102,241,0.12); color: #6366f1;">
            <i class="icon-base ti tabler-database"></i>
          </div>
          <div>
            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Cache Aplikasi</div>
            <small class="text-body-premium">Dashboard, settings & data</small>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl mb-3 mb-xl-0">
        <div class="cache-inline-card d-flex align-items-center gap-3">
          <div class="cache-icon-box" style="background: rgba(6,182,212,0.12); color: #22d3ee;">
            <i class="icon-base ti tabler-code"></i>
          </div>
          <div>
            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Compiled Views</div>
            <small class="text-body-premium">Blade template cache</small>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl mb-3 mb-xl-0">
        <div class="cache-inline-card d-flex align-items-center gap-3">
          <div class="cache-icon-box" style="background: rgba(249,115,22,0.12); color: #fb923c;">
            <i class="icon-base ti tabler-settings"></i>
          </div>
          <div>
            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Config Cache</div>
            <small class="text-body-premium">Konfigurasi aplikasi</small>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl mb-3 mb-xl-0">
        <div class="cache-inline-card d-flex align-items-center gap-3">
          <div class="cache-icon-box" style="background: rgba(16,185,129,0.12); color: #34d399;">
            <i class="icon-base ti tabler-signal-lte"></i>
          </div>
          <div>
            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Route Cache</div>
            <small class="text-body-premium">Rute aplikasi</small>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-xl">
        <div class="cache-inline-card d-flex align-items-center gap-3">
          <div class="cache-icon-box" style="background: rgba(168,85,247,0.12); color: #c084fc;">
            <i class="icon-base ti tabler-package"></i>
          </div>
          <div>
            <div class="fw-semibold text-white" style="font-size: 0.85rem;">Services & Manifest</div>
            <small class="text-body-premium">Optimasi & service</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Clear Button + Last Cleared --}}
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
      <div>
        <button type="submit" form="clearCacheForm" class="btn btn-gradient d-inline-flex align-items-center gap-2" id="clearCacheBtn">
          <i class="icon-base ti tabler-trash fs-5" id="btnIcon"></i>
          <span id="btnText">Clear All Cache</span>
          <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width: 16px; height: 16px;"></div>
        </button>
      </div>
      <div class="text-body-premium d-flex align-items-center gap-2" style="font-size: 0.8rem;">
        <i class="icon-base ti tabler-clock"></i>
        <span>Terakhir dibersihkan: <strong id="lastCleared">{{ $lastCleared ?? 'Belum pernah' }}</strong></span>
      </div>
    </div>
  </div>

  {{-- Success Result --}}
  @if(session('success'))
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4 fade-in-up" style="border-left: 4px solid #34d399;">
    <div class="d-flex align-items-center gap-3 mb-3">
      <div class="check-pop" style="background: rgba(16, 185, 129, 0.1); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
        <i class="icon-base ti tabler-check" style="color: #34d399; font-size: 1.4rem;"></i>
      </div>
      <div>
        <h6 class="text-white fw-semibold mb-0">{{ session('success')['message'] ?? 'Cache berhasil dibersihkan!' }}</h6>
        <small class="text-body-premium d-flex align-items-center gap-1 mt-1">
          <i class="icon-base ti tabler-clock-play"></i>
          Selesai dalam {{ session('success')['duration'] ?? '-' }} detik
        </small>
      </div>
    </div>
    @if(isset(session('success')['details']))
    <div class="mt-2">
      @foreach(session('success')['details'] as $detail)
      <div class="result-item d-flex align-items-center gap-2 mb-1" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">
        <i class="icon-base ti tabler-circle-check-filled" style="color: #34d399; font-size: 1rem;"></i>
        <span>{{ $detail }}</span>
      </div>
      @endforeach
    </div>
    @endif
    <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
      <a href="{{ route('admin.cache.index') }}" class="btn btn-gradient d-inline-flex align-items-center gap-2" style="padding: 8px 20px; font-size: 0.85rem;">
        <i class="icon-base ti tabler-refresh"></i>
        Clear Lagi
      </a>
    </div>
  </div>
  @endif

  {{-- Error Result --}}
  @if(session('error'))
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4 fade-in-up" style="border-left: 4px solid #f87171;">
    <div class="d-flex align-items-center gap-3">
      <div style="background: rgba(239, 68, 68, 0.1); width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
        <i class="icon-base ti tabler-x" style="color: #f87171; font-size: 1.4rem;"></i>
      </div>
      <div>
        <h6 class="text-white fw-semibold mb-0">Gagal!</h6>
        <small class="text-body-premium">{{ session('error') }}</small>
      </div>
    </div>
  </div>
  @endif

  <form action="{{ route('admin.cache.clear') }}" method="POST" id="clearCacheForm" class="d-none">
    @csrf
  </form>

</div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('clearCacheForm');
  const btn = document.getElementById('clearCacheBtn');
  const btnText = document.getElementById('btnText');
  const spinner = document.getElementById('btnSpinner');
  const btnIcon = document.getElementById('btnIcon');

  btn?.addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
      title: 'Bersihkan Semua Cache?',
      text: 'Cache aplikasi, view, config, route, dan service akan dibersihkan.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, bersihkan!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#6366f1',
      cancelButtonColor: '#6b7280',
      background: '#0b0f19',
      color: '#f8fafc',
      iconColor: '#fbbf24',
      reverseButtons: true,
      customClass: {
        popup: 'glass-card-premium',
        confirmButton: 'btn-gradient border-0',
        cancelButton: 'btn d-inline-flex align-items-center gap-2',
      },
      buttonsStyling: false,
      padding: '28px',
    }).then((result) => {
      if (result.isConfirmed) {
        btn.disabled = true;
        btnIcon.className = 'icon-base ti tabler-loader animate-spin-slow fs-5';
        spinner.classList.remove('d-none');
        btnText.textContent = 'Membersihkan...';
        form.submit();
      }
    });
  });
});
</script>
@endsection

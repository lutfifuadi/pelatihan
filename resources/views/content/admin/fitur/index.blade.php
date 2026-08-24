@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Aktivasi Fitur Sistem — Manajemen Modul')

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
    background-color: #080c16 !important;
    background-image: 
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.18) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.18) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.09) 0px, transparent 50%) !important;
    color: #f8fafc !important;
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
    background: radial-gradient(circle, #8b5cf6 0%, rgba(139, 92, 246, 0) 70%);
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
    0% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(40px, 30px) scale(1.08); }
    100% { transform: translate(-30px, 50px) scale(0.95); }
  }

  /* ═══════════════════════════════════════════════════
     PREMIUM GLASS & HIGH-CONTRAST SURFACES
  ═══════════════════════════════════════════════════ */
  .glass-card-premium {
    background: linear-gradient(145deg, rgba(20, 29, 50, 0.8) 0%, rgba(12, 19, 35, 0.9) 100%);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    border-radius: 5px !important;
  }

  .stat-card-feature {
    background: linear-gradient(145deg, rgba(26, 38, 66, 0.75) 0%, rgba(15, 23, 42, 0.9) 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    border-radius: 5px !important;
    padding: 18px 22px;
    transition: all 0.3s ease;
  }
  .stat-card-feature:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.22);
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.6);
  }

  .feature-item-card {
    background: linear-gradient(145deg, rgba(22, 32, 56, 0.8) 0%, rgba(13, 20, 36, 0.92) 100%);
    border: 1px solid rgba(255, 255, 255, 0.11);
    border-radius: 5px !important;
    padding: 20px 22px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
  }
  .feature-item-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--card-accent, #6366f1);
    opacity: 0.4;
    transition: all 0.3s ease;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
  }
  .feature-item-card.is-active::before {
    opacity: 1;
    box-shadow: 0 0 14px var(--card-accent, #6366f1);
  }
  .feature-item-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.22);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
  }

  .feature-icon-box {
    width: 44px;
    height: 44px;
    border-radius: 5px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    transition: all 0.3s ease;
  }

  /* ═══════════════════════════════════════════════════
     SQUARE CHISELED SWITCH TOGGLE (Max radius 4px)
  ═══════════════════════════════════════════════════ */
  .switch-toggle-feature {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 26px;
    margin: 0;
  }
  .switch-toggle-feature input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  .switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(51, 65, 85, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.18);
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 4px !important;
  }
  .switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: #ffffff;
    transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 3px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.4);
  }
  .switch-toggle-feature input:checked + .switch-slider {
    background-color: #10b981;
    border-color: #10b981;
    box-shadow: 0 0 16px rgba(16, 185, 129, 0.5);
  }
  .switch-toggle-feature input:checked + .switch-slider:before {
    transform: translateX(22px);
  }
  .switch-toggle-feature input:disabled + .switch-slider {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* ═══════════════════════════════════════════════════
     FILTER BUTTONS & SEARCH BAR
  ═══════════════════════════════════════════════════ */
  .category-filter-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    border-radius: 5px !important;
    padding: 6px 12px;
    font-size: 0.82rem;
    font-weight: 500;
    transition: all 0.2s ease;
  }
  .category-filter-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.25);
    color: #ffffff;
  }
  .category-filter-btn.active {
    background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
    border-color: #818cf8 !important;
    color: #ffffff !important;
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.45) !important;
  }

  .search-input-feature {
    background: rgba(9, 14, 28, 0.85) !important;
    border: 1px solid rgba(255, 255, 255, 0.16) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
  }
  .search-input-feature:focus {
    border-color: #6366f1 !important;
    background: rgba(12, 18, 36, 0.95) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25) !important;
  }

  /* ═══════════════════════════════════════════════════
     HIGH-CONTRAST DROPDOWN & UTILITIES
  ═══════════════════════════════════════════════════ */
  .dropdown-menu-dark {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.18) !important;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.8), 0 0 20px rgba(99, 102, 241, 0.15) !important;
    padding: 6px !important;
    border-radius: 5px !important;
  }
  .dropdown-menu-dark .dropdown-item {
    color: #e2e8f0 !important;
    font-size: 0.84rem;
    font-weight: 500;
    padding: 9px 14px;
    border-radius: 4px !important;
    transition: all 0.18s ease;
  }
  .dropdown-menu-dark .dropdown-item:hover {
    background: rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }

  .badge,
  .btn,
  .progress,
  .progress-bar,
  code,
  .timeline-badge {
    border-radius: 4px !important;
  }

  .offcanvas-dark {
    background-color: #0d1424 !important;
    color: #f8fafc !important;
    border-left: 1px solid rgba(255, 255, 255, 0.12) !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-5 position-relative" style="z-index: 1;">

    {{-- ═══════════════════════════════════════════════════
         HERO BANNER
    ═══════════════════════════════════════════════════ --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4" style="border-radius: 5px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="feature-icon-box" style="background: rgba(99, 102, 241, 0.25); border: 1px solid rgba(99, 102, 241, 0.45); color: #a5b4fc; width: 50px; height: 50px; font-size: 1.5rem; border-radius: 5px; box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);">
            <i class="icon-base ti tabler-toggle-right"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <h4 class="fw-bold text-white mb-0">Aktivasi & Manajemen Fitur</h4>
              <span class="badge bg-label-primary text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; border-radius: 4px; border: 1px solid rgba(99, 102, 241, 0.3);">Single Source of Truth</span>
            </div>
            <p class="text-white-50 mb-0 mt-1" style="font-size: 0.95rem;">
              Kendalikan modul aplikasi, kanal pendaftaran, integrasi eksternal, dan fungsionalitas sistem secara real-time via AJAX toggle.
            </p>
          </div>
        </div>

        <div class="d-flex align-items-center gap-2">
          <button type="button" class="btn d-flex align-items-center gap-2 text-white" data-bs-toggle="offcanvas" data-bs-target="#activityLogsDrawer" aria-controls="activityLogsDrawer" style="border-radius: 5px; background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255, 255, 255, 0.15); padding: 8px 16px; font-size: 0.88rem;">
            <i class="icon-base ti tabler-history text-primary"></i>
            <span>Riwayat Perubahan</span>
          </button>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         SUMMARY METRIC CARDS (LUMINOUS CONTRAST)
    ═══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Fitur</span>
            <h3 class="fw-bold text-white mb-0 mt-1" id="stat-total">{{ $totalCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(99, 102, 241, 0.22); border: 1px solid rgba(99, 102, 241, 0.45); color: #a5b4fc; box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);">
            <i class="icon-base ti tabler-apps"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Fitur Aktif (ON)</span>
            <h3 class="fw-bold mb-0 mt-1" style="color: #34d399;" id="stat-active">{{ $activeCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(16, 185, 129, 0.22); border: 1px solid rgba(16, 185, 129, 0.45); color: #34d399; box-shadow: 0 0 15px rgba(16, 185, 129, 0.25);">
            <i class="icon-base ti tabler-circle-check"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Fitur Non-Aktif (OFF)</span>
            <h3 class="fw-bold mb-0 mt-1" style="color: #fbbf24;" id="stat-inactive">{{ $inactiveCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(245, 158, 11, 0.22); border: 1px solid rgba(245, 158, 11, 0.45); color: #fbbf24; box-shadow: 0 0 15px rgba(245, 158, 11, 0.25);">
            <i class="icon-base ti tabler-circle-x"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card-feature d-flex flex-column justify-content-between h-100" style="border-radius: 5px;">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kesiapan Sistem</span>
            <span class="fw-bold" style="color: #38bdf8;" id="stat-percentage">{{ $activePercentage }}%</span>
          </div>
          <div class="progress" style="height: 8px; background: rgba(255,255,255,0.08); border-radius: 4px;">
            <div class="progress-bar" id="stat-progress-bar" role="progressbar" style="width: {{ $activePercentage }}%; border-radius: 4px; background: linear-gradient(90deg, #6366f1, #38bdf8);" aria-valuenow="{{ $activePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         FILTER & ACTION BAR (CLEAN & BALANCED)
    ═══════════════════════════════════════════════════ --}}
    <div class="glass-card-premium px-3 px-xl-4 py-3 mb-4" style="border-radius: 5px;">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 gap-xl-3">
        {{-- Search Input --}}
        <div class="d-flex align-items-center flex-grow-1" style="min-width: 240px; max-width: 320px;">
          <div class="input-group w-100">
            <span class="input-group-text bg-transparent border-0 text-white-50 pe-0">
              <i class="icon-base ti tabler-search"></i>
            </span>
            <input type="text" id="feature-search" class="form-control search-input-feature ps-2" placeholder="Cari nama fitur, deskripsi, atau key..." style="border-radius: 5px; font-size: 0.88rem; height: 38px;">
          </div>
        </div>

        {{-- Categories & Quick Actions (Grouped Together) --}}
        <div class="d-flex flex-wrap align-items-center justify-content-start justify-content-lg-end gap-2 flex-grow-1">
          <div class="d-flex flex-wrap gap-1" id="category-filter-group">
            <button type="button" class="category-filter-btn active" data-category="all">Semua</button>
            @foreach($categories as $cat)
              <button type="button" class="category-filter-btn" data-category="{{ Str::slug($cat) }}">{{ $cat }}</button>
            @endforeach
          </div>

          <div class="vr bg-secondary d-none d-md-block mx-1" style="height: 24px; opacity: 0.3;"></div>

          <div class="dropdown">
            <button class="btn dropdown-toggle text-white d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 5px; padding: 6px 14px; font-size: 0.84rem; background: linear-gradient(145deg, rgba(30, 41, 59, 0.85) 0%, rgba(15, 23, 42, 0.95) 100%); border: 1px solid rgba(255, 255, 255, 0.16); height: 36px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);">
              <i class="icon-base ti tabler-adjustments-horizontal text-primary"></i>
              <span>Aksi Cepat</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="border-radius: 5px; min-width: 220px;">
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" onclick="bulkToggle(true)">
                  <i class="icon-base ti tabler-check text-success fs-5"></i> Aktifkan Semua Fitur
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" onclick="bulkToggle(false)">
                  <i class="icon-base ti tabler-x text-danger fs-5"></i> Nonaktifkan Semua Fitur
                </a>
              </li>
              <li><hr class="dropdown-divider border-secondary opacity-25"></li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" onclick="resetToDefaults()">
                  <i class="icon-base ti tabler-rotate-clockwise text-warning fs-5"></i> Reset ke Nilai Default
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         FEATURE CARDS GRID (GROUPED BY CATEGORY)
    ═══════════════════════════════════════════════════ --}}
    <div id="features-container">
      @foreach($featuresGrouped as $categoryName => $groupFeatures)
        <div class="category-section mb-5" data-category-group="{{ Str::slug($categoryName) }}">
          <div class="d-flex align-items-center gap-2 mb-3">
            <h5 class="fw-bold text-white mb-0" style="letter-spacing: 0.3px;">{{ $categoryName }}</h5>
            <span class="badge" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); font-size: 0.75rem; border-radius: 4px; color: #cbd5e1;">{{ count($groupFeatures) }} Fitur</span>
          </div>

          <div class="row g-3">
            @foreach($groupFeatures as $featureKey => $feature)
              @php
                $accentColor = $feature['badge_color'] ?? '#6366f1';
                $isOn = !empty($feature['is_on']);
              @endphp
              <div class="col-12 col-md-6 col-xl-4 feature-card-wrapper" 
                   data-feature-key="{{ $featureKey }}"
                   data-feature-name="{{ strtolower($feature['label']) }}"
                   data-feature-desc="{{ strtolower($feature['description']) }}"
                   data-category="{{ Str::slug($categoryName) }}">
                
                <div class="feature-item-card {{ $isOn ? 'is-active' : '' }}" style="--card-accent: {{ $accentColor }}; border-radius: 5px;">
                  <div>
                    {{-- Header Kartu --}}
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                      <div class="d-flex align-items-center gap-3">
                        <div class="feature-icon-box" style="background: {{ $accentColor }}25; border: 1px solid {{ $accentColor }}50; color: {{ $accentColor }}; border-radius: 5px; box-shadow: 0 0 12px {{ $accentColor }}20;">
                          <i class="icon-base ti {{ $feature['icon'] ?? 'tabler-app-window' }}"></i>
                        </div>
                        <div>
                          <h6 class="fw-bold text-white mb-1" style="font-size: 0.98rem;">{{ $feature['label'] }}</h6>
                          <code style="font-size: 0.72rem; background: rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); color: #94a3b8; padding: 2px 6px; border-radius: 4px;">{{ $featureKey }}</code>
                        </div>
                      </div>

                      {{-- Switch Toggle --}}
                      <label class="switch-toggle-feature" title="Klik untuk On/Off">
                        <input type="checkbox" 
                               class="feature-checkbox" 
                               data-key="{{ $featureKey }}" 
                               {{ $isOn ? 'checked' : '' }} 
                               onchange="handleToggleChange(this)">
                        <span class="switch-slider" style="border-radius: 4px;"></span>
                      </label>
                    </div>

                    {{-- Deskripsi Fitur --}}
                    <p class="mb-0" style="color: #94a3b8; font-size: 0.86rem; line-height: 1.5;">
                      {{ $feature['description'] }}
                    </p>
                  </div>

                  {{-- Footer Kartu / Status Badge --}}
                  <div class="d-flex justify-content-between align-items-center pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                    <span class="badge" style="background: {{ $accentColor }}20; border: 1px solid {{ $accentColor }}40; color: {{ $accentColor }}; font-size: 0.75rem; font-weight: 600; border-radius: 4px;">
                      {{ $categoryName }}
                    </span>
                    <span class="badge feature-status-badge {{ $isOn ? 'bg-label-success text-success' : 'bg-label-secondary text-white-50' }}" style="font-size: 0.75rem; border-radius: 4px; border: 1px solid rgba(255,255,255,0.08);">
                      {{ $isOn ? '● AKTIF (ON)' : '○ NON-AKTIF' }}
                    </span>
                  </div>

                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>

    {{-- Empty State untuk Pencarian --}}
    <div id="no-features-found" class="text-center py-5 d-none">
      <div class="feature-icon-box mx-auto mb-3" style="width: 60px; height: 60px; background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.4); font-size: 1.8rem; border-radius: 5px;">
        <i class="icon-base ti tabler-search-off"></i>
      </div>
      <h5 class="text-white fw-bold">Tidak ada fitur yang cocok</h5>
      <p class="text-white-50 mb-0">Coba gunakan kata kunci pencarian lain atau pilih kategori Semua.</p>
    </div>

  </div>

  {{-- ═══════════════════════════════════════════════════
       OFFCANVAS DRAWER: RIWAYAT PERUBAHAN FITUR
  ═══════════════════════════════════════════════════ --}}
  <div class="offcanvas offcanvas-end offcanvas-dark" tabindex="-1" id="activityLogsDrawer" aria-labelledby="activityLogsDrawerLabel" style="width: 420px;">
    <div class="offcanvas-header border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
      <h5 class="offcanvas-title text-white fw-bold d-flex align-items-center gap-2" id="activityLogsDrawerLabel">
        <i class="icon-base ti tabler-history text-primary"></i>
        <span>Riwayat Aktivitas Fitur</span>
      </h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      @if($recentLogs->isNotEmpty())
        <div class="timeline ps-3">
          @foreach($recentLogs as $log)
            <div class="mb-4 position-relative">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-semibold text-white" style="font-size: 0.88rem;">{{ $log->subject_name }}</span>
                <span class="text-white-50" style="font-size: 0.72rem;">{{ $log->created_at->diffForHumans() }}</span>
              </div>
              <p class="text-white-50 mb-1" style="font-size: 0.82rem; line-height: 1.4;">{{ $log->description }}</p>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-label-info" style="font-size: 0.68rem; border-radius: 4px;">{{ $log->user?->name ?? 'Sistem' }}</span>
                <span class="text-white-50" style="font-size: 0.7rem;">IP: {{ $log->ip_address }}</span>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-5">
          <i class="icon-base ti tabler-history-off fs-1 text-white-50 mb-2"></i>
          <p class="text-white-50 mb-0">Belum ada riwayat perubahan fitur.</p>
        </div>
      @endif
    </div>
  </div>
@endsection

@section('page-script')
<script>
  // Setup CSRF Token untuk seluruh request AJAX
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

  /**
   * Handle AJAX Toggle Change
   */
  function handleToggleChange(checkbox) {
    const key = checkbox.dataset.key;
    const isChecked = checkbox.checked;
    const card = checkbox.closest('.feature-item-card');
    const badge = card.querySelector('.feature-status-badge');

    checkbox.disabled = true;

    fetch("{{ route('admin.settings.fitur.toggle') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        key: key,
        value: isChecked ? 1 : 0
      })
    })
    .then(response => response.json())
    .then(data => {
      checkbox.disabled = false;
      if (data.success) {
        if (isChecked) {
          card.classList.add('is-active');
          badge.className = 'badge feature-status-badge bg-label-success text-success';
          badge.textContent = '● AKTIF (ON)';
        } else {
          card.classList.remove('is-active');
          badge.className = 'badge feature-status-badge bg-label-secondary text-white-50';
          badge.textContent = '○ NON-AKTIF';
        }

        updateStatCounters();
        showToast(data.message, 'success');
      } else {
        checkbox.checked = !isChecked;
        showToast(data.message || 'Gagal mengubah status fitur.', 'error');
      }
    })
    .catch(error => {
      checkbox.disabled = false;
      checkbox.checked = !isChecked;
      console.error('Error toggle feature:', error);
      showToast('Terjadi kesalahan jaringan saat memperbarui fitur.', 'error');
    });
  }

  /**
   * Update Summary Stat Counters di header
   */
  function updateStatCounters() {
    const checkboxes = document.querySelectorAll('.feature-checkbox');
    const total = checkboxes.length;
    let active = 0;

    checkboxes.forEach(cb => {
      if (cb.checked) active++;
    });

    const inactive = total - active;
    const percentage = total > 0 ? Math.round((active / total) * 100) : 0;

    const statActive = document.getElementById('stat-active');
    const statInactive = document.getElementById('stat-inactive');
    const statPercentage = document.getElementById('stat-percentage');
    const statProgressBar = document.getElementById('stat-progress-bar');

    if (statActive) statActive.textContent = active;
    if (statInactive) statInactive.textContent = inactive;
    if (statPercentage) statPercentage.textContent = `${percentage}%`;
    if (statProgressBar) {
      statProgressBar.style.width = `${percentage}%`;
      statProgressBar.setAttribute('aria-valuenow', percentage);
    }
  }

  /**
   * Bulk Toggle All Features
   */
  function bulkToggle(state) {
    const actionText = state ? 'mengaktifkan' : 'menonaktifkan';
    if (!confirm(`Apakah Anda yakin ingin ${actionText} seluruh fitur sistem?`)) {
      return;
    }

    fetch("{{ route('admin.settings.fitur.bulk') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        state: state ? 1 : 0
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        document.querySelectorAll('.feature-checkbox').forEach(cb => {
          cb.checked = state;
          const card = cb.closest('.feature-item-card');
          const badge = card.querySelector('.feature-status-badge');
          if (state) {
            card.classList.add('is-active');
            badge.className = 'badge feature-status-badge bg-label-success text-success';
            badge.textContent = '● AKTIF (ON)';
          } else {
            card.classList.remove('is-active');
            badge.className = 'badge feature-status-badge bg-label-secondary text-white-50';
            badge.textContent = '○ NON-AKTIF';
          }
        });

        updateStatCounters();
        showToast(data.message, 'success');
      } else {
        showToast(data.message || 'Gagal memproses aksi cepat.', 'error');
      }
    })
    .catch(error => {
      console.error('Error bulk toggle:', error);
      showToast('Terjadi kesalahan jaringan.', 'error');
    });
  }

  /**
   * Reset All Features to Default SOT
   */
  function resetToDefaults() {
    if (!confirm('Apakah Anda yakin ingin mengembalikan seluruh fitur ke pengaturan default pabrik?')) {
      return;
    }

    fetch("{{ route('admin.settings.fitur.reset') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast(data.message, 'success');
        setTimeout(() => location.reload(), 800);
      } else {
        showToast(data.message || 'Gagal mereset fitur.', 'error');
      }
    })
    .catch(error => {
      console.error('Error reset features:', error);
      showToast('Terjadi kesalahan jaringan.', 'error');
    });
  }

  /**
   * Toast Notification Helper
   */
  function showToast(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        background: '#0f172a',
        color: '#f8fafc'
      });
      Toast.fire({
        icon: type === 'success' ? 'success' : 'error',
        title: message
      });
    } else {
      console.log(`[${type.toUpperCase()}] ${message}`);
    }
  }

  /**
   * Live Search & Filter Kategori
   */
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('feature-search');
    const categoryButtons = document.querySelectorAll('.category-filter-btn');
    const featureWrappers = document.querySelectorAll('.feature-card-wrapper');
    const categorySections = document.querySelectorAll('.category-section');
    const noResultsEl = document.getElementById('no-features-found');

    let currentCategory = 'all';
    let currentSearch = '';

    function filterFeatures() {
      let visibleCount = 0;

      categorySections.forEach(section => {
        const sectionCategory = section.dataset.categoryGroup;
        let sectionHasVisible = false;

        const cardsInSection = section.querySelectorAll('.feature-card-wrapper');
        cardsInSection.forEach(wrapper => {
          const matchesCategory = (currentCategory === 'all' || wrapper.dataset.category === currentCategory);
          const name = wrapper.dataset.featureName;
          const desc = wrapper.dataset.featureDesc;
          const key = wrapper.dataset.featureKey;
          const matchesSearch = !currentSearch || name.includes(currentSearch) || desc.includes(currentSearch) || key.includes(currentSearch);

          if (matchesCategory && matchesSearch) {
            wrapper.classList.remove('d-none');
            sectionHasVisible = true;
            visibleCount++;
          } else {
            wrapper.classList.add('d-none');
          }
        });

        if (sectionHasVisible) {
          section.classList.remove('d-none');
        } else {
          section.classList.add('d-none');
        }
      });

      if (visibleCount === 0) {
        noResultsEl.classList.remove('d-none');
      } else {
        noResultsEl.classList.add('d-none');
      }
    }

    if (searchInput) {
      searchInput.addEventListener('input', function(e) {
        currentSearch = e.target.value.toLowerCase().trim();
        filterFeatures();
      });
    }

    categoryButtons.forEach(btn => {
      btn.addEventListener('click', function() {
        categoryButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        currentCategory = this.dataset.category;
        filterFeatures();
      });
    });
  });
</script>
@endsection

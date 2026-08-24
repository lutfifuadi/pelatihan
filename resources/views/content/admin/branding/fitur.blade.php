@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Aktivasi Fitur — Pengaturan Sistem')

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

  .glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    opacity: 0.35;
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

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
    border-radius: 5px !important;
  }

  .stat-card-feature {
    background: rgba(30, 41, 59, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 5px !important;
    padding: 18px 22px;
    transition: all 0.3s ease;
  }
  .stat-card-feature:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.15);
  }

  .feature-item-card {
    background: rgba(15, 23, 42, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 5px !important;
    padding: 20px 22px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
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
    box-shadow: 0 0 12px var(--card-accent, #6366f1);
  }
  .feature-item-card:hover {
    transform: translateY(-3px);
    border-color: rgba(255, 255, 255, 0.18);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
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

  /* Custom Modern Switch */
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
    background-color: rgba(71, 85, 105, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.15);
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
    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
  }
  .switch-toggle-feature input:checked + .switch-slider {
    background-color: #10b981;
    border-color: #10b981;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
  }
  .switch-toggle-feature input:checked + .switch-slider:before {
    transform: translateX(22px);
  }
  .switch-toggle-feature input:disabled + .switch-slider {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Quick Navigation Nav Pills */
  .nav-pills-settings .nav-link {
    color: rgba(255, 255, 255, 0.7);
    padding: 10px 18px;
    border-radius: 5px !important;
    font-weight: 500;
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }
  .nav-pills-settings .nav-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.06);
  }
  .nav-pills-settings .nav-link.active {
    color: #ffffff;
    background: rgba(99, 102, 241, 0.2);
    border: 1px solid rgba(99, 102, 241, 0.4);
    box-shadow: 0 0 15px rgba(99, 102, 241, 0.25);
  }

  .category-filter-btn {
    background: rgba(30, 41, 59, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.75);
    border-radius: 5px !important;
    padding: 7px 14px;
    font-size: 0.88rem;
    transition: all 0.2s ease;
  }
  .category-filter-btn:hover,
  .category-filter-btn.active {
    background: rgba(99, 102, 241, 0.25);
    border-color: rgba(99, 102, 241, 0.5);
    color: #ffffff;
  }

  .search-input-feature {
    background: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
  }
  .search-input-feature:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2) !important;
  }

  .badge,
  .btn,
  .dropdown-menu,
  .dropdown-item,
  code {
    border-radius: 4px !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- ═══════════════════════════════════════════════════
         HEADER & NAVIGATION
    ═══════════════════════════════════════════════════ --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4" style="border-radius: 5px;">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="feature-icon-box" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border-radius: 5px;">
            <i class="icon-base ti tabler-toggle-right fs-3"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Aktivasi Fitur Sistem</h4>
            <p class="text-white-50 mb-0 mt-1" style="font-size: 0.95rem;">
              Aktifkan atau nonaktifkan modul, menu, dan fungsionalitas sistem secara real-time via AJAX toggle.
            </p>
          </div>
        </div>

        {{-- Navigasi Tab Pengaturan --}}
        <ul class="nav nav-pills nav-pills-settings gap-1">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.branding') }}" style="border-radius: 5px;">
              <i class="icon-base ti tabler-palette me-1"></i> Branding
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.landing') }}" style="border-radius: 5px;">
              <i class="icon-base ti tabler-browser me-1"></i> Landing Page
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.seo') }}" style="border-radius: 5px;">
              <i class="icon-base ti tabler-seo me-1"></i> SEO
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.settings.maintenance') }}" style="border-radius: 5px;">
              <i class="icon-base ti tabler-tool me-1"></i> Maintenance
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.fitur.index') }}" style="border-radius: 5px;">
              <i class="icon-base ti tabler-toggle-right me-1"></i> Aktivasi Fitur
            </a>
          </li>
        </ul>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         SUMMARY STAT CARDS
    ═══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
      <div class="col-12 col-md-4">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.78rem; letter-spacing: 0.5px;">Total Fitur Tersedia</span>
            <h3 class="fw-bold text-white mb-0 mt-1" id="stat-total">{{ $totalCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border-radius: 5px;">
            <i class="icon-base ti tabler-apps"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.78rem; letter-spacing: 0.5px;">Fitur Aktif (ON)</span>
            <h3 class="fw-bold text-success mb-0 mt-1" id="stat-active">{{ $activeCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border-radius: 5px;">
            <i class="icon-base ti tabler-circle-check"></i>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="stat-card-feature d-flex align-items-center justify-content-between" style="border-radius: 5px;">
          <div>
            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 0.78rem; letter-spacing: 0.5px;">Fitur Non-Aktif (OFF)</span>
            <h3 class="fw-bold text-warning mb-0 mt-1" id="stat-inactive">{{ $inactiveCount }}</h3>
          </div>
          <div class="feature-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-radius: 5px;">
            <i class="icon-base ti tabler-circle-x"></i>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         FILTER & CONTROLS BAR
    ═══════════════════════════════════════════════════ --}}
    <div class="glass-card-premium px-4 py-3 mb-4" style="border-radius: 5px;">
      <div class="row g-3 align-items-center justify-content-between">
        <div class="col-12 col-lg-5">
          <div class="input-group">
            <span class="input-group-text bg-transparent border-0 text-white-50 pe-0">
              <i class="icon-base ti tabler-search"></i>
            </span>
            <input type="text" id="feature-search" class="form-control search-input-feature ps-2" placeholder="Cari nama fitur atau deskripsi..." style="border-radius: 5px;">
          </div>
        </div>
        <div class="col-12 col-lg-7 d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
          <div class="d-flex flex-wrap gap-1" id="category-filter-group">
            <button type="button" class="category-filter-btn active" data-category="all" style="border-radius: 5px;">Semua</button>
            @foreach($categories as $cat)
              <button type="button" class="category-filter-btn" data-category="{{ Str::slug($cat) }}" style="border-radius: 5px;">{{ $cat }}</button>
            @endforeach
          </div>
          <div class="vr bg-secondary d-none d-sm-block mx-1" style="height: 24px; opacity: 0.3;"></div>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle text-white" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 5px;">
              <i class="icon-base ti tabler-adjustments-horizontal me-1"></i> Aksi Cepat
            </button>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow" style="border-radius: 5px;">
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" onclick="bulkToggle(true)">
                  <i class="icon-base ti tabler-check text-success"></i> Aktifkan Semua Fitur
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0);" onclick="bulkToggle(false)">
                  <i class="icon-base ti tabler-x text-danger"></i> Nonaktifkan Semua Fitur
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
            <span class="badge" style="background: rgba(255,255,255,0.08); font-size: 0.75rem; border-radius: 4px;">{{ count($groupFeatures) }} Fitur</span>
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
                        <div class="feature-icon-box" style="background: {{ $accentColor }}20; color: {{ $accentColor }}; border-radius: 5px;">
                          <i class="icon-base ti {{ $feature['icon'] ?? 'tabler-app-window' }}"></i>
                        </div>
                        <div>
                          <h6 class="fw-bold text-white mb-1" style="font-size: 1rem;">{{ $feature['label'] }}</h6>
                          <code class="text-white-50" style="font-size: 0.72rem; background: rgba(0,0,0,0.25); padding: 2px 6px; border-radius: 4px;">{{ $featureKey }}</code>
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
                    <p class="text-white-50 mb-0" style="font-size: 0.88rem; line-height: 1.45;">
                      {{ $feature['description'] }}
                    </p>
                  </div>

                  {{-- Footer Kartu / Status Badge --}}
                  <div class="d-flex justify-content-between align-items-center pt-3 mt-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
                    <span class="badge" style="background: {{ $accentColor }}18; color: {{ $accentColor }}; font-size: 0.75rem; font-weight: 500; border-radius: 4px;">
                      {{ $categoryName }}
                    </span>
                    <span class="badge feature-status-badge {{ $isOn ? 'bg-label-success text-success' : 'bg-label-secondary text-white-50' }}" style="font-size: 0.75rem; border-radius: 4px;">
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
@endsection

@section('page-script')
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

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

  function updateStatCounters() {
    const checkboxes = document.querySelectorAll('.feature-checkbox');
    const total = checkboxes.length;
    let active = 0;

    checkboxes.forEach(cb => {
      if (cb.checked) active++;
    });

    const inactive = total - active;

    const statActive = document.getElementById('stat-active');
    const statInactive = document.getElementById('stat-inactive');
    if (statActive) statActive.textContent = active;
    if (statInactive) statInactive.textContent = inactive;
  }

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

  function showToast(message, type = 'success') {
    if (typeof Swal !== 'undefined') {
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        background: '#1e293b',
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

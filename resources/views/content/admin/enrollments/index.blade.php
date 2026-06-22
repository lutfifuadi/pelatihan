@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Pendaftaran Pelatihan')

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

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
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
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .badge-premium-danger {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.3);
    color: #f87171;
  }
  .badge-premium-info {
    background: rgba(96, 165, 250, 0.15);
    border-color: rgba(96, 165, 250, 0.3);
    color: #93c5fd;
  }

  .pagination .page-item .page-link {
    background: rgba(255, 255, 255, 0.04) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
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
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  @keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1) rotate(0deg); }
    50% { transform: translate(60px, 40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px, -50px) scale(0.92) rotate(360deg); }
  }

  .btn-action {
    border-radius: 5px !important;
    padding: 4px 12px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
  }
  .btn-action:hover {
    transform: translateY(-1px);
  }
  select.form-select {
    background-color: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    color: rgba(255,255,255,0.8) !important;
    border-radius: 5px !important;
    padding: 6px 12px !important;
    font-size: 0.85rem !important;
  }
  select.form-select option {
    background-color: #0b0f19 !important;
    color: #f8fafc !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    {{-- Title --}}
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="fw-bold text-white mb-0">Pendaftaran Pelatihan</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
            Kelola approve, reject, dan daftar cadangan peserta pelatihan
          </p>
        </div>
      </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
      <div class="col-sm-6 col-md-3">
        <div class="glass-card-premium px-3 py-3 text-center">
          <div class="stat-icon-box mx-auto mb-2" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
            <i class="icon-base ti tabler-clock"></i>
          </div>
          <h3 class="fw-bold text-white mb-0">{{ $counts['pending'] }}</h3>
          <small class="text-body-premium">Pending</small>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="glass-card-premium px-3 py-3 text-center">
          <div class="stat-icon-box mx-auto mb-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
            <i class="icon-base ti tabler-check"></i>
          </div>
          <h3 class="fw-bold text-white mb-0">{{ $counts['approved'] }}</h3>
          <small class="text-body-premium">Approved</small>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="glass-card-premium px-3 py-3 text-center">
          <div class="stat-icon-box mx-auto mb-2" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
            <i class="icon-base ti tabler-x"></i>
          </div>
          <h3 class="fw-bold text-white mb-0">{{ $counts['rejected'] }}</h3>
          <small class="text-body-premium">Ditolak</small>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="glass-card-premium px-3 py-3 text-center">
          <div class="stat-icon-box mx-auto mb-2" style="background: rgba(96, 165, 250, 0.15); color: #93c5fd;">
            <i class="icon-base ti tabler-users"></i>
          </div>
          <h3 class="fw-bold text-white mb-0">{{ $counts['waitlist'] }}</h3>
          <small class="text-body-premium">Cadangan</small>
        </div>
      </div>
    </div>

    {{-- Search --}}
    <div class="col-12 mb-4">
      <div class="glass-card-premium px-4 py-3">
        <div class="row align-items-center g-3">
          <div class="col-12 col-md-6">
            <div class="d-flex gap-2">
              <div class="position-relative flex-grow-1">
                <i class="icon-base ti tabler-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-premium" style="font-size: 1rem; z-index: 2;"></i>
                <input type="text" id="search-input" class="form-control" placeholder="Cari nama peserta..." value="{{ $search ?? '' }}" style="padding-left: 2.5rem !important;">
              </div>
              <button type="button" id="search-btn" class="btn btn-glow-premium px-3 py-2" style="white-space: nowrap; border-radius: 5px;" title="Cari">
                <i class="icon-base ti tabler-search"></i>
              </button>
              <a href="{{ route('admin.enrollments.index') }}" id="reset-btn" class="btn btn-secondary-custom px-3 py-2 {{ ($search ?? '') ? '' : 'd-none' }}" title="Reset pencarian">
                <i class="icon-base ti tabler-x"></i>
              </a>
              <div id="loading-spinner" class="d-none">
                <div class="spinner-border spinner-border-sm text-warning" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filter --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
          <label class="text-body-premium small fw-semibold mb-1">Filter Pelatihan</label>
          <select name="pelatihan_id" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Pelatihan</option>
            @foreach($pelatihans as $p)
              <option value="{{ $p->id }}" {{ request('pelatihan_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nama }} ({{ $p->batch }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="text-body-premium small fw-semibold mb-1">Filter Status</label>
          <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            <option value="waitlist" {{ request('status') == 'waitlist' ? 'selected' : '' }}>Cadangan</option>
          </select>
        </div>
        <div class="col-md-2">
          <a href="{{ route('admin.enrollments.index') }}" class="btn btn-outline-secondary btn-action w-100" style="color: rgba(255,255,255,0.6); border-color: rgba(255,255,255,0.1);">
            <i class="icon-base ti tabler-refresh me-1"></i> Reset
          </a>
        </div>
      </form>
    </div>

    {{-- Export Buttons --}}
    <div class="glass-card-premium px-4 py-3 mb-4">
      <div class="d-flex justify-content-end align-items-center gap-2">
        <a href="{{ request('pelatihan_id') ? route('admin.exports.enrollments.pdf', ['pelatihan' => request('pelatihan_id')]) : route('admin.exports.enrollments.pdf') }}" class="btn btn-outline-info btn-action" style="border-color: rgba(96,165,250,0.3); color: #93c5fd;">
          <i class="icon-base ti tabler-file-export me-1"></i> 📄 Export PDF
        </a>
        <a href="{{ request('pelatihan_id') ? route('admin.exports.enrollments.excel', ['pelatihan' => request('pelatihan_id')]) : route('admin.exports.enrollments.excel') }}" class="btn btn-outline-success btn-action" style="border-color: rgba(16,185,129,0.3); color: #34d399;">
          <i class="icon-base ti tabler-file-spreadsheet me-1"></i> 📊 Export Excel
        </a>
      </div>
    </div>

    {{-- Table --}}
    <div class="glass-card-premium px-4 py-4">
      <div class="table-responsive">
        <table class="table table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small fw-semibold px-0" style="width: 50px;">No</th>
              <th class="text-body-premium small fw-semibold">Nama Peserta</th>
              <th class="text-body-premium small fw-semibold">Pelatihan</th>
              <th class="text-body-premium small fw-semibold">WhatsApp</th>
              <th class="text-body-premium small fw-semibold">Tgl Daftar</th>
              <th class="text-body-premium small fw-semibold">Status</th>
              <th class="text-body-premium small fw-semibold text-end px-0" style="width: 260px;">Aksi</th>
            </tr>
          </thead>
          <tbody id="table-content">
            @include('content.admin.enrollments._table_rows')
          </tbody>
        </table>
      </div>
      <div id="table-pagination">
        @if($enrollments->hasPages())
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            {{ $enrollments->links() }}
          </div>
        @endif
      </div>
    </div>

  </div>
@endsection

@section('vendor-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
<script>
(function() {
    let search = {!! json_encode($search ?? '') !!};
    let loading = false;
    let debounceTimer = null;
    let abortController = null;

    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const tableContent = document.getElementById('table-content');
    const paginationContainer = document.getElementById('table-pagination');

    async function fetchData(targetPage = null) {
      // Abort previous request if still in-flight
      if (abortController) abortController.abort();
      abortController = new AbortController();

      loading = true;
      loadingSpinner.classList.remove('d-none');

      const params = new URLSearchParams({ search: search || '' });
      if (targetPage) params.set('page', targetPage);

      try {
        const res = await fetch(`/admin/enrollments?${params.toString()}`, {
          method: 'GET',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          signal: abortController.signal
        });
        if (!res.ok) throw new Error('Response error');
        const data = await res.json();
        tableContent.innerHTML = data.rows;
        if (data.pagination) {
          paginationContainer.innerHTML = data.pagination;
        } else {
          paginationContainer.innerHTML = '';
        }

        // Sync URL
        const url = new URL(window.location);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        if (targetPage) url.searchParams.set('page', targetPage);
        else url.searchParams.delete('page');
        window.history.replaceState({}, '', url);

        // Reset button visibility
        if (search) resetBtn.classList.remove('d-none');
        else resetBtn.classList.add('d-none');

        // Bind pagination clicks
        const links = document.querySelectorAll('#table-pagination .pagination a');
        links.forEach(link => {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            if (href) {
              const urlObj = new URL(href, window.location.origin);
              const targetPage = urlObj.searchParams.get('page') || 1;
              fetchData(targetPage);
            }
          });
        });
      } catch (e) {
        if (e.name === 'AbortError') return;
        console.error('Gagal memuat data:', e);
      } finally {
        loading = false;
        loadingSpinner.classList.add('d-none');
      }
    }

    // Auto-search on input
    searchInput.addEventListener('input', function() {
      search = this.value;
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchData(), 300);
    });

    // Enter key
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        search = this.value;
        clearTimeout(debounceTimer);
        fetchData();
      }
    });

    // Search button click
    searchBtn.addEventListener('click', function() {
      search = searchInput.value;
      clearTimeout(debounceTimer);
      fetchData();
    });

    // Reset button
    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      search = '';
      searchInput.value = '';
      fetchData();
    });
  })();
</script>
@endsection

@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Data Peserta')

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

  .form-control {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }

</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;" id="peserta-page">
    
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-users-group fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Data Peserta</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Daftar seluruh peserta pelatihan
            </p>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('admin.exports.peserta.pdf') }}" class="btn btn-outline-info btn-sm" style="border-radius: 5px; border-color: rgba(96,165,250,0.3); color: #93c5fd; font-weight: 600; padding: 4px 12px; font-size: 0.75rem; transition: all 0.3s ease;">
            <i class="icon-base ti tabler-file-export me-1"></i> 📄 Export PDF
          </a>
          <a href="{{ route('admin.exports.peserta.excel') }}" class="btn btn-outline-success btn-sm" style="border-radius: 5px; border-color: rgba(16,185,129,0.3); color: #34d399; font-weight: 600; padding: 4px 12px; font-size: 0.75rem; transition: all 0.3s ease;">
            <i class="icon-base ti tabler-file-spreadsheet me-1"></i> 📊 Export Excel
          </a>
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

    <div class="col-12 mb-4">
      <div class="glass-card-premium px-4 py-3">
        <div class="d-flex align-items-center gap-3">
          <div class="position-relative flex-grow-1">
            <i class="icon-base ti tabler-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-premium" style="font-size: 1rem; z-index: 2;"></i>
            <input type="text" id="search-input"
              class="form-control" placeholder="Cari nama, NIK, atau WA..." value="{{ $search ?? '' }}" style="padding-left: 2.5rem !important; border-radius: 5px;">
          </div>
          <div style="min-width: 200px;">
            <select id="filter-pelatihan-select" class="form-select text-white" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 5px; height: 42px; padding: 0 14px;">
              <option value="all" {{ ($filterPelatihan ?? 'all') == 'all' ? 'selected' : '' }} style="background-color: #0b0f19;">Semua Status Pelatihan</option>
              <option value="sudah" {{ ($filterPelatihan ?? 'all') == 'sudah' ? 'selected' : '' }} style="background-color: #0b0f19;">Sudah Memilih Pelatihan</option>
              <option value="belum" {{ ($filterPelatihan ?? 'all') == 'belum' ? 'selected' : '' }} style="background-color: #0b0f19;">Belum Memilih Pelatihan</option>
            </select>
          </div>
          <a href="{{ route('admin.peserta.index') }}" id="reset-btn" class="btn btn-secondary-custom px-3 py-2 {{ (request()->has('search') && request('search') != '') || (request()->has('filter_pelatihan') && request('filter_pelatihan') != 'all') ? '' : 'd-none' }}" style="white-space: nowrap;">
            <i class="icon-base ti tabler-x me-1"></i> Reset
          </a>
          <div id="loading-spinner" class="d-none">
            <div class="spinner-border spinner-border-sm text-warning" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 60px;">No</th>
                <th class="text-body-premium small fw-semibold" style="cursor: pointer;" id="sort-name">
                  <span class="d-flex align-items-center gap-1">
                    Nama
                    <i id="sort-icon" class="icon-base ti {{ $sortBy == 'name' ? ($sortDir == 'asc' ? 'tabler-sort-ascending' : 'tabler-sort-descending') : 'tabler-arrows-sort' }}" style="font-size: {{ $sortBy == 'name' ? '1rem' : '0.85rem' }};"></i>
                  </span>
                </th>
                <th class="text-body-premium small fw-semibold">WhatsApp</th>
                <th class="text-body-premium small fw-semibold">Kecamatan</th>
                <th class="text-body-premium small fw-semibold">Pilihan Pelatihan</th>
                <th class="text-body-premium small fw-semibold">Tanggal Daftar</th>
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 120px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="table-content">
              @include('content.admin.peserta._table_rows')
            </tbody>
          </table>
        </div>
        <div id="table-pagination">
          @if($pesertas->hasPages())
            <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
              {{ $pesertas->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
@endsection

@section('page-script')
<script>
  (function() {
    let search = {!! json_encode($search ?? '') !!};
    let sortBy = {!! json_encode($sortBy) !!};
    let sortDir = {!! json_encode($sortDir) !!};
    let filterPelatihan = {!! json_encode($filterPelatihan ?? 'all') !!};
    let loading = false;
    let debounceTimer = null;

    const searchInput = document.getElementById('search-input');
    const filterPelatihanSelect = document.getElementById('filter-pelatihan-select');
    const resetBtn = document.getElementById('reset-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const sortHeader = document.getElementById('sort-name');
    const sortIcon = document.getElementById('sort-icon');
    const tableContainer = document.getElementById('table-content');

    async function fetchData() {
      if (loading) return;
      loading = true;
      loadingSpinner.classList.remove('d-none');

      const params = new URLSearchParams({ search, sort_by: sortBy, sort_dir: sortDir, filter_pelatihan: filterPelatihan });
      try {
        const res = await fetch(`/admin/peserta?${params.toString()}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        tableContainer.innerHTML = data.rows;
        document.getElementById('table-pagination').innerHTML = data.pagination;

        const url = new URL(window.location);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');

        if (filterPelatihan && filterPelatihan !== 'all') url.searchParams.set('filter_pelatihan', filterPelatihan);
        else url.searchParams.delete('filter_pelatihan');

        url.searchParams.set('sort_by', sortBy);
        url.searchParams.set('sort_dir', sortDir);
        window.history.replaceState({}, '', url);

        if (search || (filterPelatihan && filterPelatihan !== 'all')) resetBtn.classList.remove('d-none');
        else resetBtn.classList.add('d-none');

        if (sortBy == 'name') {
          sortIcon.className = 'icon-base ti ' + (sortDir == 'asc' ? 'tabler-sort-ascending' : 'tabler-sort-descending');
          sortIcon.style.fontSize = '1rem';
        } else {
          sortIcon.className = 'icon-base ti tabler-arrows-sort';
          sortIcon.style.fontSize = '0.85rem';
        }
      } catch (e) {
        console.error('Gagal memuat data:', e);
      } finally {
        loading = false;
        loadingSpinner.classList.add('d-none');
      }
    }

    searchInput.addEventListener('input', function() {
      search = this.value;
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(fetchData, 300);
    });

    filterPelatihanSelect.addEventListener('change', function() {
      filterPelatihan = this.value;
      fetchData();
    });

    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      search = '';
      sortBy = 'name';
      sortDir = 'asc';
      filterPelatihan = 'all';
      searchInput.value = '';
      filterPelatihanSelect.value = 'all';
      fetchData();
    });

    sortHeader.addEventListener('click', function() {
      if (sortBy === 'name') {
        sortDir = sortDir === 'asc' ? 'desc' : 'asc';
      } else {
        sortBy = 'name';
        sortDir = 'asc';
      }
      fetchData();
    });
  })();
</script>
@endsection

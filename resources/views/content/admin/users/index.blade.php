@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Manajemen User')

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

  .hover-text-success:hover {
    color: #34d399 !important;
    transition: color 0.2s ease;
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

  .form-select {
    background-color: rgba(15, 23, 42, 0.25) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-select:focus {
    background-color: rgba(15, 23, 42, 0.45) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-select option {
    background-color: #0b0f19 !important;
    color: #ffffff !important;
  }

  /* Custom Switch Styling to fit dark premium theme */
  .form-switch .form-check-input {
    background-color: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
    height: 1.5em;
    width: 2.75em;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='rgba%28255, 255, 255, 0.6%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M8 5l5 5-5 5'/%3e%3c/svg%3e") !important;
    transition: background-position 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
  }
  .form-switch .form-check-input:checked {
    background-color: #6366f1 !important;
    border-color: #6366f1 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='%23fff' d='M13.293 6.293a1 1 0 011.414 0l.001.001a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-3-3a1 1 0 011.414-1.414L7 12.586l6.293-6.293z'/%3e%3c/svg%3e") !important;
  }
  .form-switch .form-check-input:focus {
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
  }
  .form-switch .form-check-input:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  /* Custom Pagination for dark theme */
  .pagination .page-link {
    background-color: rgba(255, 255, 255, 0.03) !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
    color: rgba(255, 255, 255, 0.75) !important;
    margin: 0 2px;
    border-radius: 4px;
    transition: all 0.3s ease;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(99, 102, 241, 0.25);
  }
  .pagination .page-link:hover {
    background-color: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }
  .pagination .page-item.disabled .page-link {
    background-color: rgba(255, 255, 255, 0.01) !important;
    border-color: rgba(255, 255, 255, 0.05) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
</style>
@endsection

@section('content')
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;" id="users-page">
    
    <!-- Title Card -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-users fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Manajemen User</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Daftar dan kelola seluruh akun pengguna sistem
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Messages (Standard fallback) -->
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

    <!-- Filters Section -->
    <div class="col-12 mb-4">
      <div class="glass-card-premium px-4 py-3">
        <div class="row align-items-center g-3">
          <!-- Search input -->
          <div class="col-12 col-md-5">
            <div class="position-relative">
              <i class="icon-base ti tabler-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-premium" style="font-size: 1rem; z-index: 2;"></i>
              <input type="text" id="search-input" class="form-control" placeholder="Cari nama, email, NIK, atau WhatsApp..." value="{{ $search ?? '' }}" style="padding-left: 2.5rem !important;">
            </div>
          </div>

          <!-- Dropdown Role -->
          <div class="col-6 col-md-3">
            <select id="role-filter" class="form-select">
              <option value="">Semua Role</option>
              <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="instruktur" {{ $role === 'instruktur' ? 'selected' : '' }}>Instruktur</option>
              <option value="koordinator" {{ $role === 'koordinator' ? 'selected' : '' }}>Koordinator</option>
              <option value="peserta" {{ $role === 'peserta' ? 'selected' : '' }}>Peserta</option>
            </select>
          </div>

          <!-- Dropdown Status -->
          <div class="col-6 col-md-2">
            <select id="status-filter" class="form-select">
              <option value="">Semua Status</option>
              <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
              <option value="0" {{ $status === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
          </div>

          <!-- Reset button and loader -->
          <div class="col-12 col-md-2 d-flex align-items-center justify-content-md-end gap-2">
            <a href="#" id="reset-btn" class="btn btn-secondary-custom px-3 py-2 w-100 {{ ($search || $role || $status) ? '' : 'd-none' }}">
              <i class="icon-base ti tabler-x me-1"></i> Reset
            </a>
            <div id="loading-spinner" class="d-none ms-2">
              <div class="spinner-border spinner-border-sm text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Container -->
    <div class="col-12">
      <div class="glass-card-premium px-4 py-4 mb-5">
        <div id="table-container">
          @include('content.admin.users._table')
        </div>
      </div>
    </div>

  </div>
@endsection

@section('page-script')
<script>
  (function() {
    let search = {!! json_encode($search ?? '') !!};
    let role = {!! json_encode($role ?? '') !!};
    let status = {!! json_encode($status ?? '') !!};
    let page = 1;
    let loading = false;
    let debounceTimer = null;

    const searchInput = document.getElementById('search-input');
    const roleFilter = document.getElementById('role-filter');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const tableContainer = document.getElementById('table-container');

    // ===== SWEETALERT2 DARK THEME =====
    const swalDark = Swal.mixin({
      background: '#1e293b',
      color: '#f8fafc',
      confirmButtonColor: '#6366f1',
      cancelButtonColor: '#6b7280',
      iconColor: '#a5b4fc',
      customClass: {
        popup: 'rounded-3 shadow-lg',
        title: 'fw-bold text-white',
        confirmButton: 'btn btn-primary px-3 py-2 border-0 me-2',
        cancelButton: 'btn btn-secondary px-3 py-2 border-0',
      },
      buttonsStyling: false,
    });

    // Helper: update Reset button visibility
    function updateResetBtnVisibility() {
      if (search || role || status) {
        resetBtn.classList.remove('d-none');
      } else {
        resetBtn.classList.add('d-none');
      }
    }

    // Main Fetch Function
    async function fetchData(targetPage = null) {
      if (loading) return;
      loading = true;
      loadingSpinner.classList.remove('d-none');

      if (targetPage !== null) {
        page = targetPage;
      }

      const params = new URLSearchParams({
        search: search || '',
        role: role || '',
        status: status || '',
        page: page
      });

      try {
        const res = await fetch(`/admin/users?${params.toString()}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
        
        if (!res.ok) throw new Error('Response error');
        
        const data = await res.json();
        tableContainer.innerHTML = data.html;

        // Sync browser history state
        const url = new URL(window.location);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');

        if (role) url.searchParams.set('role', role);
        else url.searchParams.delete('role');

        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');

        if (page > 1) url.searchParams.set('page', page);
        else url.searchParams.delete('page');

        window.history.replaceState({}, '', url);
        updateResetBtnVisibility();
        bindInteractiveEvents();
      } catch (e) {
        console.error('Gagal memuat data user:', e);
      } finally {
        loading = false;
        loadingSpinner.classList.add('d-none');
      }
    }

    // Bind event handlers for dynamically loaded content
    function bindInteractiveEvents() {
      // 1. Pagination clicks
      const links = document.querySelectorAll('#pagination-links a');
      links.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const href = this.getAttribute('href');
          if (href) {
            const urlObj = new URL(href);
            const targetPage = urlObj.searchParams.get('page') || 1;
            fetchData(targetPage);
          }
        });
      });

      // 2. Status Toggle switch changes
      const switches = document.querySelectorAll('.status-switch');
      switches.forEach(sw => {
        sw.addEventListener('change', async function() {
          const userId = this.getAttribute('data-id');
          const userName = this.getAttribute('data-name');
          const url = this.getAttribute('data-url');
          const originalState = !this.checked;

          try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const res = await fetch(url, {
              method: 'PATCH',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              }
            });

            const responseData = await res.json();

            if (!res.ok) {
              throw new Error(responseData.message || 'Gagal mengubah status');
            }

            swalDark.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: responseData.message || 'Status berhasil diubah.',
              timer: 2000,
              showConfirmButton: false
            });
          } catch (err) {
            // Revert state
            this.checked = originalState;
            swalDark.fire({
              icon: 'error',
              title: 'Gagal!',
              text: err.message || 'Terjadi kesalahan sistem.',
              confirmButtonText: 'OK'
            });
          }
        });
      });

      // 3. Delete Forms interception
      const deleteForms = document.querySelectorAll('.delete-form');
      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const userName = this.getAttribute('data-name');
          
          swalDark.fire({
            title: 'Apakah Anda yakin?',
            text: `Akun user "${userName}" akan dihapus secara permanen dari sistem!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });

      // 4. Impersonate Forms interception
      const impersonateForms = document.querySelectorAll('.impersonate-form');
      impersonateForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          const userName = this.getAttribute('data-name');
          
          swalDark.fire({
            title: 'Login As (Impersonasi)?',
            text: `Anda akan masuk ke dalam sistem sebagai "${userName}". Sesi Anda sebagai administrator akan disimpan sementara.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Masuk!',
            cancelButtonText: 'Batal',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    }

    // Search input event
    searchInput.addEventListener('input', function() {
      search = this.value;
      page = 1; // reset page to 1
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchData(), 300);
    });

    // Role select filter event
    roleFilter.addEventListener('change', function() {
      role = this.value;
      page = 1;
      fetchData();
    });

    // Status select filter event
    statusFilter.addEventListener('change', function() {
      status = this.value;
      page = 1;
      fetchData();
    });

    // Reset button event
    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      search = '';
      role = '';
      status = '';
      page = 1;

      searchInput.value = '';
      roleFilter.value = '';
      statusFilter.value = '';

      fetchData();
    });

    // Initial binding
    bindInteractiveEvents();

  })();
</script>
@endsection

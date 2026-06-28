<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Manajemen User'); ?>

<?php $__env->startSection('page-style'); ?>
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

  /* SweetAlert2 Custom Styling */
  .swal2-popup.swal2-custom-popup {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 8px !important;
    padding: 1.75rem !important;
  }
  .swal2-title.swal2-custom-title {
    font-size: 1.15rem !important;
    font-family: 'Sora', sans-serif !important;
    font-weight: 600 !important;
    color: #ffffff !important;
    margin-top: 1rem !important;
    margin-bottom: 0.75rem !important;
  }
  .swal2-html-container.swal2-custom-text {
    font-size: 0.85rem !important;
    line-height: 1.5 !important;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 1.5rem !important;
  }
  .swal2-custom-popup .swal2-icon {
    transform: scale(0.85) !important;
    margin-top: 0.5rem !important;
  }
  .swal2-actions.swal2-custom-actions {
    margin-top: 1rem !important;
    gap: 1rem !important;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span><?php echo e(session('success')); ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
      <div class="alert alert-danger alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
          <span><?php echo e(session('error')); ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Filters Section -->
    <div class="col-12 mb-4">
      <div class="glass-card-premium px-4 py-3">
        <div class="row align-items-center g-3">
          <!-- Search input -->
          <div class="col-12 col-md-5">
            <div class="d-flex gap-2">
              <div class="position-relative flex-grow-1">
                <i class="icon-base ti tabler-search position-absolute top-50 start-0 translate-middle-y ms-3 text-body-premium" style="font-size: 1rem; z-index: 2;"></i>
                <input type="text" id="search-input" class="form-control" placeholder="Cari nama, email, NIK, atau WhatsApp..." value="<?php echo e($search ?? ''); ?>" style="padding-left: 2.5rem !important;">
              </div>
              <button type="button" id="search-btn" class="btn btn-glow-premium px-3 py-2" style="white-space: nowrap; border-radius: 5px;" title="Cari">
                <i class="icon-base ti tabler-search"></i>
              </button>
            </div>
          </div>

          <!-- Dropdown Role -->
          <div class="col-6 col-md-3">
            <select id="role-filter" class="form-select">
              <option value="">Semua Role</option>
              <option value="admin" <?php echo e($role === 'admin' ? 'selected' : ''); ?>>Admin</option>
              <option value="instruktur" <?php echo e($role === 'instruktur' ? 'selected' : ''); ?>>Instruktur</option>
              <option value="koordinator" <?php echo e($role === 'koordinator' ? 'selected' : ''); ?>>Koordinator</option>
              <option value="peserta" <?php echo e($role === 'peserta' ? 'selected' : ''); ?>>Peserta</option>
            </select>
          </div>

          <!-- Dropdown Status -->
          <div class="col-6 col-md-2">
            <select id="status-filter" class="form-select">
              <option value="">Semua Status</option>
              <option value="1" <?php echo e($status === '1' ? 'selected' : ''); ?>>Aktif</option>
              <option value="0" <?php echo e($status === '0' ? 'selected' : ''); ?>>Nonaktif</option>
            </select>
          </div>

          <!-- Reset button and loader -->
          <div class="col-12 col-md-2 d-flex align-items-center justify-content-md-end gap-2">
            <button type="button" id="reset-all-peserta-btn" class="btn btn-warning px-3 py-2 w-100" style="white-space: nowrap; border-radius: 5px; background: linear-gradient(135deg, #f59e0b, #d97706); border: none;" title="Reset Semua Password Peserta">
              <i class="icon-base ti tabler-key-off me-1"></i> Reset Password Peserta
            </button>
            <a href="#" id="reset-btn" class="btn btn-secondary-custom px-3 py-2 w-100 <?php echo e(($search || $role || $status) ? '' : 'd-none'); ?>">
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
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 60px;">No</th>
                <th class="text-body-premium small fw-semibold">Detail User</th>
                <th class="text-body-premium small fw-semibold">NIK</th>
                <th class="text-body-premium small fw-semibold">No. WhatsApp</th>
                <th class="text-body-premium small fw-semibold">Role</th>
                <th class="text-body-premium small fw-semibold text-center" style="width: 100px;">Status</th>
                <th class="text-body-premium small fw-semibold">Tanggal Terdaftar</th>
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 100px;">Aksi</th>
              </tr>
            </thead>
            <tbody id="table-content">
              <?php echo $__env->make('content.admin.users._table_rows', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </tbody>
          </table>
        </div>
        <div id="table-pagination">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->hasPages()): ?>
            <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
              <?php echo e($users->links()); ?>

            </div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <form id="reset-all-peserta-form" action="<?php echo e(route('admin.users.reset-all-peserta')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
  </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
  (function() {
    let search = <?php echo json_encode($search ?? ''); ?>;
    let role = <?php echo json_encode($role ?? ''); ?>;
    let status = <?php echo json_encode($status ?? ''); ?>;
    let loading = false;
    let debounceTimer = null;
    let abortController = null;

    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const roleFilter = document.getElementById('role-filter');
    const statusFilter = document.getElementById('status-filter');
    const resetBtn = document.getElementById('reset-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    const tableContent = document.getElementById('table-content');
    const paginationContainer = document.getElementById('table-pagination');

    // ===== SWEETALERT2 DARK THEME =====
    const swalDark = Swal.mixin({
      background: '#0f172a',
      color: '#f8fafc',
      confirmButtonColor: '#6366f1',
      cancelButtonColor: '#6b7280',
      iconColor: '#a5b4fc',
      customClass: {
        popup: 'swal2-custom-popup shadow-lg',
        title: 'swal2-custom-title',
        htmlContainer: 'swal2-custom-text',
        actions: 'swal2-custom-actions',
        confirmButton: 'btn btn-glow-premium px-4 py-2 border-0 me-2',
        cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
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

    // Main Fetch Function — with AbortController to cancel stale requests
    async function fetchData(targetPage = null) {
      // Abort previous request if still in-flight
      if (abortController) {
        abortController.abort();
      }
      abortController = new AbortController();

      loading = true;
      loadingSpinner.classList.remove('d-none');

      const params = new URLSearchParams({
        search: search || '',
        role: role || '',
        status: status || '',
      });

      if (targetPage) {
        params.set('page', targetPage);
      }

      try {
        const res = await fetch(`/admin/users?${params.toString()}`, {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
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

        // Sync browser history state
        const url = new URL(window.location);
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');

        if (role) url.searchParams.set('role', role);
        else url.searchParams.delete('role');

        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');

        if (targetPage) url.searchParams.set('page', targetPage);
        else url.searchParams.delete('page');

        window.history.replaceState({}, '', url);
        updateResetBtnVisibility();
        bindInteractiveEvents();
      } catch (e) {
        // Ignore abort errors (intentional cancellation from new search)
        if (e.name === 'AbortError') return;
        console.error('Gagal memuat data user:', e);
      } finally {
        loading = false;
        loadingSpinner.classList.add('d-none');
      }
    }

    // Bind event handlers for dynamically loaded content
    function bindInteractiveEvents() {
      // 1. Pagination clicks — bind to pagination links
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

      // 2. Status Toggle switch changes
      const switches = document.querySelectorAll('.status-switch');
      switches.forEach(sw => {
        sw.addEventListener('change', async function() {
          const userId = this.getAttribute('data-id');
          const userName = this.getAttribute('data-name');
          const url = this.getAttribute('data-url');
          const originalState = !this.checked;

          try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';
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
    }

    // Intercept forms submission globally (Event Delegation)
    document.addEventListener('submit', function(e) {
      // Reset Password Form
      const resetForm = e.target.closest('.reset-password-form');
      if (resetForm) {
        e.preventDefault();
        const userName = resetForm.getAttribute('data-name');
        const userRole = resetForm.getAttribute('data-role');
        const resetText = userRole === 'peserta'
          ? `Password peserta "${userName}" akan direset ke default: "pelatihanku2026".`
          : `Password user "${userName}" akan direset ke nomor HP/WhatsApp user.`;

        swalDark.fire({
          title: 'Yakin ingin mereset password?',
          text: resetText,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Reset!',
          cancelButtonText: 'Batal',
          customClass: {
            popup: 'swal2-custom-popup shadow-lg',
            title: 'swal2-custom-title',
            htmlContainer: 'swal2-custom-text',
            actions: 'swal2-custom-actions',
            confirmButton: 'btn btn-warning px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            resetForm.submit();
          }
        });
        return;
      }

      // Impersonate Form
      const impersonateForm = e.target.closest('.impersonate-form');
      if (impersonateForm) {
        e.preventDefault();
        const userName = impersonateForm.getAttribute('data-name');
        const userAvatar = impersonateForm.getAttribute('data-avatar') || '';
        const userEmail = impersonateForm.getAttribute('data-email') || '';
        const userRole = impersonateForm.getAttribute('data-role') || '';
        const userStatus = impersonateForm.getAttribute('data-status') || '';

        // Generate avatar HTML
        let avatarHtml = '';
        if (userAvatar) {
          avatarHtml = '<img src="' + userAvatar + '" alt="' + userName + '" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(99, 102, 241, 0.3);">';
        } else {
          // Generate inisial
          const initials = (userName.match(/\b\w/g) || []).map(function(char) { return char.toUpperCase(); });
          const initialsStr = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
          avatarHtml = '<div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #d946ef); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: #fff; font-family: \'Sora\', sans-serif; border: 2px solid rgba(99, 102, 241, 0.3);">' + initialsStr + '</div>';
        }

        // Role badge color mapping
        var roleColors = {
          'admin': { bg: 'rgba(139, 92, 246, 0.15)', border: 'rgba(139, 92, 246, 0.3)', color: '#a78bfa' },
          'instruktur': { bg: 'rgba(6, 182, 212, 0.15)', border: 'rgba(6, 182, 212, 0.3)', color: '#22d3ee' },
          'koordinator': { bg: 'rgba(245, 158, 11, 0.15)', border: 'rgba(245, 158, 11, 0.3)', color: '#fbbf24' },
          'peserta': { bg: 'rgba(16, 185, 129, 0.15)', border: 'rgba(16, 185, 129, 0.3)', color: '#34d399' },
        };
        var roleColor = roleColors[userRole] || roleColors['peserta'];

        // Status badge
        var statusColor = userStatus === 'aktif' ? '#34d399' : '#ef4444';
        var statusBg = userStatus === 'aktif' ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)';
        var statusBorder = userStatus === 'aktif' ? 'rgba(16, 185, 129, 0.3)' : 'rgba(239, 68, 68, 0.3)';

        var htmlContent = [
          '<div style="text-align: center; padding: 8px 0;">',
          '  <div style="display: flex; justify-content: center; margin-bottom: 16px;">',
          '    ' + avatarHtml,
          '  </div>',
          '  <h4 style="font-family: \'Sora\', sans-serif; font-weight: 700; font-size: 1.2rem; color: #ffffff; margin: 0 0 4px 0;">',
          '    ' + userName,
          '  </h4>',
          '  <p style="font-family: \'Outfit\', sans-serif; font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); margin: 0 0 12px 0;">',
          '    ' + userEmail,
          '  </p>',
          '  <div style="display: flex; justify-content: center; gap: 8px; margin-bottom: 16px;">',
          '    <span style="font-family: \'Outfit\', sans-serif; font-size: 0.75rem; font-weight: 600; padding: 4px 12px; border-radius: 5px; background: ' + roleColor.bg + '; border: 1px solid ' + roleColor.border + '; color: ' + roleColor.color + '; text-transform: capitalize;">',
          '      ' + userRole,
          '    </span>',
          '    <span style="font-family: \'Outfit\', sans-serif; font-size: 0.75rem; font-weight: 600; padding: 4px 12px; border-radius: 5px; background: ' + statusBg + '; border: 1px solid ' + statusBorder + '; color: ' + statusColor + '; text-transform: capitalize;">',
          '      ' + userStatus,
          '    </span>',
          '  </div>',
          '  <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 5px; padding: 10px 14px; margin: 0 4px;">',
          '    <p style="font-family: \'Outfit\', sans-serif; font-size: 0.8rem; color: #fbbf24; margin: 0; line-height: 1.5;">',
          '      <i class="icon-base ti tabler-shield-alert" style="font-size: 0.9rem; margin-right: 6px;"></i>',
          '      Anda akan masuk sebagai user ini. Sesi admin Anda akan disimpan sementara.',
          '    </p>',
          '  </div>',
          '</div>'
        ].join('\n');

        swalDark.fire({
          title: 'Konfirmasi Impersonasi',
          html: htmlContent,
          icon: null,
          showCancelButton: true,
          confirmButtonText: '<i class="icon-base ti tabler-user-shield me-1"></i> Ya, Masuk!',
          cancelButtonText: 'Batal',
          customClass: {
            popup: 'swal2-custom-popup shadow-lg',
            title: 'swal2-custom-title',
            htmlContainer: 'swal2-custom-text',
            actions: 'swal2-custom-actions',
            confirmButton: 'btn px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          reverseButtons: true,
          didOpen: function() {
            var confirmBtn = document.querySelector('.swal2-confirm');
            if (confirmBtn) {
              confirmBtn.style.background = 'linear-gradient(135deg, #6366f1, #d946ef)';
              confirmBtn.style.color = '#fff';
              confirmBtn.style.borderRadius = '5px';
              confirmBtn.style.fontFamily = "'Sora', sans-serif";
              confirmBtn.style.fontWeight = '600';
            }
          }
        }).then(function(result) {
          if (result.isConfirmed) {
            impersonateForm.submit();
          }
        });
        return;
      }

      // Delete Form
      const deleteForm = e.target.closest('.delete-form');
      if (deleteForm) {
        e.preventDefault();
        const userName = deleteForm.getAttribute('data-name');

        swalDark.fire({
          title: 'Apakah Anda yakin?',
          text: `Akun user "${userName}" akan dihapus secara permanen dari sistem!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          customClass: {
            popup: 'swal2-custom-popup shadow-lg',
            title: 'swal2-custom-title',
            htmlContainer: 'swal2-custom-text',
            actions: 'swal2-custom-actions',
            confirmButton: 'btn btn-danger px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            deleteForm.submit();
          }
        });
        return;
      }
    });

    // Search input event — auto search on input
    searchInput.addEventListener('input', function() {
      search = this.value;
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fetchData(), 300);
    });

    // Prevent enter key from submitting form — trigger instant search instead
    searchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        search = this.value;
        clearTimeout(debounceTimer);
        fetchData();
      }
    });

    // Search button click — trigger search manually
    searchBtn.addEventListener('click', function() {
      search = searchInput.value;
      clearTimeout(debounceTimer);
      fetchData();
    });

    // Role select filter event
    roleFilter.addEventListener('change', function() {
      role = this.value;
      fetchData();
    });

    // Status select filter event
    statusFilter.addEventListener('change', function() {
      status = this.value;
      fetchData();
    });

    // Reset button event
    resetBtn.addEventListener('click', function(e) {
      e.preventDefault();
      search = '';
      role = '';
      status = '';

      searchInput.value = '';
      roleFilter.value = '';
      statusFilter.value = '';

      fetchData();
    });

    // Reset Semua Password Peserta Event
    const resetAllPesertaBtn = document.getElementById('reset-all-peserta-btn');
    if (resetAllPesertaBtn) {
      resetAllPesertaBtn.addEventListener('click', function(e) {
        e.preventDefault();
        swalDark.fire({
          title: 'Apakah Anda yakin?',
          text: "Tindakan ini akan mereset password SELURUH peserta (peserta saja) yang terdaftar di sistem menjadi 'pelatihanku2026'. Tindakan ini tidak dapat dibatalkan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Reset Semua!',
          cancelButtonText: 'Batal',
          customClass: {
            popup: 'swal2-custom-popup shadow-lg',
            title: 'swal2-custom-title',
            htmlContainer: 'swal2-custom-text',
            actions: 'swal2-custom-actions',
            confirmButton: 'btn btn-danger px-4 py-2 border-0 me-2',
            cancelButton: 'btn btn-secondary-custom px-4 py-2 border-0',
          },
          reverseButtons: true
        }).then((result) => {
          if (result.isConfirmed) {
            document.getElementById('reset-all-peserta-form').submit();
          }
        });
      });
    }

    // Initial binding
    bindInteractiveEvents();

  })();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/users/index.blade.php ENDPATH**/ ?>
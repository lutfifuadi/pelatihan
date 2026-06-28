<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Approval Koordinator'); ?>

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

  /* --- LAYOUT OVERRIDES FOR LANDING PAGE ALIGNMENT --- */
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

  /* Sidebar styling */
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

  /* Top Navbar styling */
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

  /* Dynamic Floating Orbs */
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
  .badge-premium-warning {
    background: rgba(245, 158, 11, 0.15);
    border-color: rgba(245, 158, 11, 0.3);
    color: #fbbf24;
  }
  .badge-premium-info {
    background: rgba(6, 182, 212, 0.15);
    border-color: rgba(6, 182, 212, 0.3);
    color: #22d3ee;
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

  /* --- Pagination styling --- */
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
  .pagination .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.02) !important;
    border-color: rgba(255, 255, 255, 0.04) !important;
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .pagination .page-item .page-link:hover:not(.disabled) {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
  }

  /* ===== TOGGLE SWITCH CUSTOM (Dark Theme) ===== */
  .form-switch .form-check-input {
    width: 44px !important;
    height: 22px !important;
    background-color: rgba(255,255,255,0.1) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(255,255,255,0.5)'/%3e%3c/svg%3e") !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    background-size: 16px !important;
    background-position: left center !important;
    padding-left: 0 !important;
    float: none !important;
    margin: 0 !important;
  }
  .form-switch .form-check-input:checked {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e") !important;
    background-position: right center !important;
  }
  .form-switch .form-check-input:focus {
    box-shadow: none !important;
    outline: none !important;
  }
  .toggle-koordinator {
    user-select: none;
  }

  /* ===== MODAL GLASSMORPHISM ===== */
  .modal-glass {
    background: rgba(15,23,42,0.85) !important;
    backdrop-filter: blur(24px) !important;
    -webkit-backdrop-filter: blur(24px) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 5px !important;
    box-shadow: 0 30px 80px rgba(0,0,0,0.6) !important;
  }

  /* ===== BACKGROUND BLUR EFFECT ===== */
  .blur-active {
    filter: blur(4px) !important;
    transition: filter 0.3s ease !important;
    pointer-events: none;
    user-select: none;
  }
  .modal-backdrop-blur {
    backdrop-filter: blur(6px) !important;
    -webkit-backdrop-filter: blur(6px) !important;
    background: rgba(0,0,0,0.4) !important;
  }
  .modal-backdrop-blur.show {
    opacity: 1 !important;
  }

  /* ===== TOAST NOTIFICATION ===== */
  .toast-premium {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 99999;
    min-width: 320px;
    max-width: 420px;
    padding: 16px 20px;
    border-radius: 5px;
    background: rgba(15,23,42,0.92);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    transform: translateX(120%);
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .toast-premium.show {
    transform: translateX(0);
  }
  .toast-premium.toast-success {
    border-color: rgba(16,185,129,0.3);
  }
  .toast-premium.toast-success .toast-icon {
    color: #10b981;
  }
  .toast-premium.toast-error {
    border-color: rgba(239,68,68,0.3);
  }
  .toast-premium.toast-error .toast-icon {
    color: #ef4444;
  }
  .toast-premium .toast-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
  }
  .toast-premium .toast-message {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9rem;
    color: #f8fafc;
    flex: 1;
  }
  .toast-premium .toast-close {
    color: rgba(255,255,255,0.4);
    cursor: pointer;
    font-size: 1.2rem;
    line-height: 1;
    background: none;
    border: none;
    padding: 0;
    transition: color 0.2s;
  }
  .toast-premium .toast-close:hover {
    color: #ffffff;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <!-- Floating Background Orbs -->
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <!-- Content Wrapper -->
  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">
    
    <!-- Title Section -->
    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box stat-icon-primary">
            <i class="icon-base ti tabler-user-check fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Approval Koordinator</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Daftar pengajuan akun koordinator wilayah yang menunggu persetujuan
            </p>
          </div>
        </div>
        <a href="<?php echo e(route('admin.koordinator.index')); ?>" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-checklist"></i> Semua Koordinator
        </a>
      </div>
    </div>

    <!-- Alert Messages -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span><?php echo e(session('success')); ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Data Table Card -->
    <div class="col-12">
      <div class="glass-card-premium px-4 py-4">
        <div class="table-responsive">
          <table class="table table-borderless text-white align-middle">
            <thead>
              <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                <th class="text-body-premium small fw-semibold px-0" style="width: 60px;">No</th>
                <th class="text-body-premium small fw-semibold text-center" style="width: 80px;">Status</th>
                <th class="text-body-premium small fw-semibold">Nama / NIK</th>
                <th class="text-body-premium small fw-semibold">Email</th>
                <th class="text-body-premium small fw-semibold">Wilayah (Kecamatan)</th>
                <th class="text-body-premium small fw-semibold">WhatsApp</th>
                <th class="text-body-premium small fw-semibold">Tanggal Daftar</th>
                <th class="text-body-premium small fw-semibold text-end px-0" style="width: 200px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $koordinators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $koordinator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                  <td class="px-0 py-3 text-body-premium"><?php echo e($koordinators->firstItem() + $index); ?></td>
                  <td class="py-3 text-center">
                    <div class="form-check form-switch mb-0 d-flex justify-content-center">
                      <input class="form-check-input toggle-koordinator" type="checkbox" role="switch"
                        data-id="<?php echo e($koordinator->id); ?>"
                        data-name="<?php echo e($koordinator->name); ?>"
                        data-is-active="<?php echo e($koordinator->is_active ? 'true' : 'false'); ?>"
                        data-toggle-url="<?php echo e(route('admin.koordinator.toggle-status', $koordinator)); ?>"
                        <?php echo e($koordinator->is_active ? 'checked' : ''); ?>>
                    </div>
                  </td>
                  <td class="py-3">
                    <div class="fw-semibold text-white"><?php echo e($koordinator->name); ?></div>
                    <small class="text-body-premium"><?php echo e($koordinator->nik ?? '-'); ?></small>
                  </td>
                  <td class="py-3 text-body-premium"><?php echo e($koordinator->email); ?></td>
                  <td class="py-3">
                    <span class="badge-premium badge-premium-info"><?php echo e($koordinator->kecamatan?->name ?? '-'); ?></span>
                  </td>
                  <td class="py-3">
                    <a href="https://wa.me/<?php echo e($koordinator->whatsapp); ?>" target="_blank" class="text-warning text-decoration-none small">
                      <i class="icon-base ti tabler-brand-whatsapp me-1"></i><?php echo e($koordinator->whatsapp ?? '-'); ?>

                    </a>
                  </td>
                  <td class="py-3 text-body-premium"><?php echo e($koordinator->created_at->format('d M Y H:i')); ?></td>
                  <td class="text-end px-0 py-3">
                    <div class="d-inline-flex gap-2">
                      <form action="<?php echo e(route('admin.koordinator.approve', $koordinator)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-sm px-3" style="border-radius: 5px;"
                          onclick="return confirm('Aktifkan koordinator <?php echo e($koordinator->name); ?>?')">
                          <i class="icon-base ti tabler-circle-check me-1"></i> Setujui
                        </button>
                      </form>
                      <form action="<?php echo e(route('admin.koordinator.reject', $koordinator)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 5px;"
                          onclick="return confirm('Hapus pendaftaran koordinator <?php echo e($koordinator->name); ?>? Data akan dihapus permanen.')">
                          <i class="icon-base ti tabler-circle-x me-1"></i> Tolak
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="8" class="text-center text-body-premium py-5">
                    <i class="icon-base ti tabler-discount-check fs-1 mb-2 d-block text-success"></i>
                    Tidak ada pendaftar koordinator yang menunggu persetujuan.
                  </td>
                </tr>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
          </table>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($koordinators->hasPages()): ?>
          <div class="mt-4 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
            <?php echo e($koordinators->links()); ?>

          </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
    </div>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
  <!-- ===== TOGGLE CONFIRMATION MODAL (Premium Glassmorphism) ===== -->
  <div class="modal fade" id="toggleConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content modal-glass">
        <div class="modal-body text-center p-4">
          <!-- Dynamic Icon -->
          <div id="toggleModalIcon" class="mb-3">
            <i class="icon-base ti tabler-toggle-left fs-1 text-warning"></i>
          </div>
          <!-- Title -->
          <h5 class="text-white fw-bold mb-2" id="toggleModalTitle">Konfirmasi</h5>
          <!-- Message -->
          <p class="text-body-premium mb-4" id="toggleModalMessage">Apakah Anda yakin?</p>
          <!-- Hidden Data -->
          <input type="hidden" id="toggleModalId" value="">
          <input type="hidden" id="toggleModalAction" value="">
          <!-- Buttons -->
          <div class="d-flex gap-2 justify-content-center">
            <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal"
              style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.7); border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600; min-width: 100px;">
              Batal
            </button>
            <button type="button" class="btn px-4 py-2 text-white fw-bold" id="toggleModalConfirmBtn"
              style="background: linear-gradient(135deg, #6366f1, #d946ef); border: none; border-radius: 5px; font-family: 'Sora', sans-serif; font-weight: 600; box-shadow: 0 4px 15px rgba(99,102,241,0.3); min-width: 140px;">
              <span id="toggleModalBtnText">Ya, Aktifkan</span>
              <div class="spinner-border spinner-border-sm d-none ms-2" id="toggleModalSpinner" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== END TOGGLE CONFIRMATION MODAL ===== -->
<?php $__env->stopPush(); ?>

<?php $__env->startSection('page-script'); ?>
<script>
(function() {
    'use strict';

    function initToggleKoordinator() {
        // --- Bootstrap Modal Instance ---
        const modalEl = document.getElementById('toggleConfirmModal');
        if (!modalEl) return; // Halaman mungkin tidak punya modal

        const modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
        let currentCheckbox = null;
        let originalState = false;

        // --- 1. Toggle Switch Click Handler ---
        document.querySelectorAll('.toggle-koordinator').forEach(function(el) {
            el.addEventListener('click', function(e) {
                e.preventDefault();

                currentCheckbox = this;

                // Sumber kebenaran: data attribute (bukan this.checked)
                const isCurrentlyActive = this.getAttribute('data-is-active') === 'true';
                const desiredState = !isCurrentlyActive; // Kebalikan: mau aktifkan atau nonaktifkan

                // Simpan state awal untuk revert jika perlu
                originalState = isCurrentlyActive;

                // Pastikan checkbox TETAP di state semula (jangan biarkan browser berubah)
                this.checked = isCurrentlyActive;

                const id = this.dataset.id;
                const name = this.dataset.name;

                document.getElementById('toggleModalId').value = id;
                document.getElementById('toggleModalAction').value = desiredState ? 'activate' : 'deactivate';

                if (desiredState) {
                    document.getElementById('toggleModalIcon').innerHTML =
                        '<i class="icon-base ti tabler-toggle-right fs-1 text-success"></i>';
                    document.getElementById('toggleModalTitle').textContent = 'Aktifkan Koordinator';
                    document.getElementById('toggleModalMessage').innerHTML =
                        'Aktifkan <strong class="text-white">' + escapeHtml(name) + '</strong> sebagai koordinator?';
                    document.getElementById('toggleModalBtnText').textContent = 'Ya, Aktifkan';
                } else {
                    document.getElementById('toggleModalIcon').innerHTML =
                        '<i class="icon-base ti tabler-toggle-left fs-1 text-warning"></i>';
                    document.getElementById('toggleModalTitle').textContent = 'Nonaktifkan Koordinator';
                    document.getElementById('toggleModalMessage').innerHTML =
                        'Nonaktifkan <strong class="text-white">' + escapeHtml(name) + '</strong>? Akun tidak bisa digunakan.';
                    document.getElementById('toggleModalBtnText').textContent = 'Ya, Nonaktifkan';
                }

                modal.show();
            });
        });

        // --- 2. Confirm Button Handler (AJAX) ---
        document.getElementById('toggleModalConfirmBtn').addEventListener('click', function() {
            const id = document.getElementById('toggleModalId').value;
            const action = document.getElementById('toggleModalAction').value;
            const btnText = document.getElementById('toggleModalBtnText');
            const spinner = document.getElementById('toggleModalSpinner');

            btnText.textContent = 'Memproses...';
            spinner.classList.remove('d-none');
            this.disabled = true;

            const url = currentCheckbox.dataset.toggleUrl;
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(function(res) {
                if (!res.ok) {
                    return res.json().catch(function() {
                        throw new Error('Server error (' + res.status + ')');
                    }).then(function(errData) {
                        throw new Error(errData.message || 'Server error (' + res.status + ')');
                    });
                }
                return res.json();
            })
            .then(function(data) {
                modal.hide();
                if (data.success) {
                    if (currentCheckbox) {
                        // Update sumber kebenaran (data attribute)
                        currentCheckbox.setAttribute('data-is-active', String(!originalState));
                        // Update visual checkbox
                        currentCheckbox.checked = !originalState;
                    }
                    showToast('success', data.message || 'Status koordinator berhasil diubah');
                } else {
                    // Revert checkbox ke state semula
                    if (currentCheckbox) {
                        currentCheckbox.checked = originalState;
                    }
                    showToast('error', data.message || 'Gagal mengubah status koordinator');
                }
            })
            .catch(function(err) {
                modal.hide();
                // Revert checkbox to original state
                if (currentCheckbox) {
                    currentCheckbox.checked = originalState;
                }
                showToast('error', err.message || 'Terjadi kesalahan server. Silakan coba lagi.');
            })
            .finally(function() {
                btnText.textContent = action === 'activate' ? 'Ya, Aktifkan' : 'Ya, Nonaktifkan';
                spinner.classList.add('d-none');
                document.getElementById('toggleModalConfirmBtn').disabled = false;
                currentCheckbox = null;
            });
        });

        // --- 3. Modal Hide → Revert if not confirmed ---
        modalEl.addEventListener('hidden.bs.modal', function() {
            if (currentCheckbox) {
                currentCheckbox.checked = originalState;
                currentCheckbox = null;
            }
            document.querySelector('.content-wrapper')?.classList.remove('blur-active');
        });

        // --- 4. Modal Show → Add blur effect ---
        modalEl.addEventListener('show.bs.modal', function() {
            document.querySelector('.content-wrapper')?.classList.add('blur-active');
            // Also style the backdrop
            setTimeout(function() {
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.classList.add('modal-backdrop-blur');
            }, 10);
        });

        // --- 5. Toast Notification System ---
        function showToast(type, message) {
            var existing = document.querySelector('.toast-premium');
            if (existing) existing.remove();

            var toast = document.createElement('div');
            toast.className = 'toast-premium toast-' + type;

            var icon = type === 'success'
                ? '<i class="icon-base ti tabler-check-circle toast-icon"></i>'
                : '<i class="icon-base ti tabler-alert-circle toast-icon"></i>';

            toast.innerHTML = icon +
                '<span class="toast-message">' + escapeHtml(message) + '</span>' +
                '<button class="toast-close" onclick="this.parentElement.remove()">&times;</button>';

            document.body.appendChild(toast);

            setTimeout(function() { toast.classList.add('show'); }, 50);

            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() { toast.remove(); }, 400);
            }, 4000);
        }

        // --- 6. Utility: Escape HTML ---
        function escapeHtml(str) {
            if (!str) return '';
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }
    }

    // Tunggu Bootstrap tersedia, lalu jalankan
    if (typeof bootstrap !== 'undefined') {
        initToggleKoordinator();
    } else {
        var checkBootstrap = setInterval(function() {
            if (typeof bootstrap !== 'undefined') {
                clearInterval(checkBootstrap);
                initToggleKoordinator();
            }
        }, 50);
    }
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/koordinator/pending.blade.php ENDPATH**/ ?>
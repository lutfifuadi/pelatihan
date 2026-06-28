<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Dashboard Koordinator'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  /* Popup blur overlay for disabled account */
  #disabled-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .disabled-card {
    max-width: 480px;
    width: 90%;
    background: rgba(15,23,42,0.85);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 5px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.6);
  }
  .disabled-card .btn-logout {
    background: linear-gradient(135deg, #6366f1, #d946ef);
    border: none;
    border-radius: 5px;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(99,102,241,0.3);
    transition: all 0.3s ease;
  }
  .disabled-card .btn-logout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99,102,241,0.4);
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
  $isDisabledKoordinator = auth()->check()
      && auth()->user()->role === 'koordinator'
      && !auth()->user()->is_active;
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('account_disabled') || $isDisabledKoordinator): ?>
  <!-- Disabled Account Overlay -->
  <div id="disabled-overlay">
    <div class="disabled-card p-5 text-center">
      <i class="icon-base ti tabler-alert-triangle fs-1 text-warning mb-3 d-inline-block"></i>
      <h4 class="text-white fw-bold mb-2">Akun Dinonaktifkan</h4>
      <p class="text-body-premium mb-4" style="font-size: 0.95rem;">
        Akun koordinator Anda telah dinonaktifkan oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.
      </p>
      <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-logout text-white px-4 py-2">Logout</button>
      </form>
    </div>
  </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="row">
  <div class="col-12 mb-4">
    <h4>Dashboard Koordinator</h4>
    <p>Selamat datang, <strong><?php echo e(auth()->user()->name); ?></strong> 👋</p>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->kecamatan): ?>
      <p class="text-muted">
        <i class="icon-base ti tabler-map-pin me-1"></i> Wilayah: <strong><?php echo e(auth()->user()->kecamatan->name); ?></strong>
      </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Peserta Wilayah</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-users fs-2 text-primary"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Pelatihan Aktif</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-books fs-2 text-success"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <div class="card-info">
            <p class="card-text mb-1">Pendaftar Baru</p>
            <h3 class="fw-bold mb-0">0</h3>
          </div>
          <div class="card-icon">
            <i class="icon-base ti tabler-user-plus fs-2 text-info"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Daftar Peserta Wilayah <?php echo e(auth()->user()->kecamatan?->name ?? '-'); ?></h5>
      </div>
      <div class="card-body">
        <p class="text-muted mb-0">Belum ada peserta terdaftar di wilayah ini.</p>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/dashboard/koordinator.blade.php ENDPATH**/ ?>
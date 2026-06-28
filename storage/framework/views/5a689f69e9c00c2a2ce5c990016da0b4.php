<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Notifikasi'); ?>

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

  html, body, .layout-page, .content-wrapper, .layout-wrapper, .layout-container {
    background-color: #0b0f19 !important;
    background-image:
      radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
      radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
      radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
    color: #f8fafc !important;
  }

  .layout-navbar-fixed .layout-page::before { display: none !important; }
  .content-wrapper > .container-xxl { max-width: 100% !important; padding: 0 !important; }

  .glow-orb {
    position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.4;
    mix-blend-mode: screen; pointer-events: none;
    animation: orbFloat 25s infinite alternate ease-in-out; z-index: 0;
  }
  .orb-1 { width: 450px; height: 450px; background: radial-gradient(circle, #6366f1 0%, transparent 70%); top: -10%; left: -10%; animation-duration: 20s; }
  .orb-2 { width: 550px; height: 550px; background: radial-gradient(circle, #ec4899 0%, transparent 70%); bottom: 5%; right: -10%; animation-duration: 28s; }
  .orb-3 { width: 350px; height: 350px; background: radial-gradient(circle, #06b6d4 0%, transparent 70%); top: 35%; left: 25%; animation-duration: 24s; }
  @keyframes orbFloat {
    0% { transform: translate(0,0) scale(1) rotate(0deg); }
    50% { transform: translate(60px,40px) scale(1.08) rotate(180deg); }
    100% { transform: translate(-30px,-50px) scale(0.92) rotate(360deg); }
  }

  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    border-radius: 5px !important;
    position: relative;
    z-index: 1;
  }

  .notif-item {
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 5px;
    transition: all 0.2s ease;
  }
  .notif-item:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(99,102,241,0.2);
  }
  .notif-item.unread {
    border-left: 3px solid #6366f1;
    background: rgba(99,102,241,0.04);
  }

  .filter-btn {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.6);
    border-radius: 5px;
    padding: 8px 18px;
    font-size: 13px;
    font-family: 'Outfit', sans-serif;
    text-decoration: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .filter-btn:hover,
  .filter-btn.active {
    background: rgba(99,102,241,0.15);
    border-color: #6366f1;
    color: #fff;
  }

  .pagination-container .pagination .page-item .page-link {
    background: rgba(255,255,255,0.04) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    color: rgba(255,255,255,0.7) !important;
    font-size: 13px !important;
    padding: 6px 12px !important;
    transition: all 0.3s ease !important;
    border-radius: 5px !important;
    margin: 0 2px !important;
  }
  .pagination-container .pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #d946ef) !important;
    border-color: transparent !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(99,102,241,0.3) !important;
  }
  .pagination-container .pagination .page-item.disabled .page-link {
    background: rgba(255,255,255,0.02) !important;
    border-color: rgba(255,255,255,0.04) !important;
    color: rgba(255,255,255,0.3) !important;
  }

  .info-label {
    font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(255, 255, 255, 0.4); font-weight: 600; margin-bottom: 2px;
  }

  hr.dark-premium { border-color: rgba(255, 255, 255, 0.06); opacity: 1; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Floating Gradient Background Orbs -->
<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>
<div class="glow-orb orb-3"></div>

<div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

  
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item">
        <a href="<?php echo e(route('dashboard.peserta')); ?>" class="text-decoration-none" style="color: rgba(255,255,255,0.5); font-size: 0.85rem;">
          <i class="icon-base ti tabler-layout-dashboard me-1"></i>Dashboard
        </a>
      </li>
      <li class="breadcrumb-item active" aria-current="page" style="color: rgba(255,255,255,0.8); font-size: 0.85rem;">
        Notifikasi
      </li>
    </ol>
  </nav>

  
  <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="stat-icon-box stat-icon-primary" style="width: 48px; height: 48px; font-size: 1.5rem;">
          <i class="icon-base ti tabler-bell"></i>
        </div>
        <div>
          <h4 class="fw-bold text-white mb-0" style="font-family: 'Sora', sans-serif;">Notifikasi</h4>
          <p class="text-body-premium mb-0 mt-1" style="font-size: 0.9rem;">
            Riwayat notifikasi dan pemberitahuan Anda
          </p>
        </div>
      </div>
      <div class="d-flex gap-2">
        <form action="<?php echo e(route('notifications.read-all')); ?>" method="POST" class="d-inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="filter-btn" style="border: none; cursor: pointer;">
            <i class="icon-base ti tabler-check-all"></i> Tandai Semua Dibaca
          </button>
        </form>
      </div>
    </div>
  </div>

  
  <div class="glass-card-premium px-4 py-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
      <a href="<?php echo e(route('notifications.index', ['channel' => 'all', 'status' => request('status')])); ?>"
        class="filter-btn <?php echo e(request('channel', 'all') === 'all' ? 'active' : ''); ?>">
        <i class="icon-base ti tabler-bell"></i>Semua
      </a>
      <a href="<?php echo e(route('notifications.index', ['channel' => 'in_app', 'status' => request('status')])); ?>"
        class="filter-btn <?php echo e(request('channel') === 'in_app' ? 'active' : ''); ?>">
        <i class="icon-base ti tabler-device-mobile"></i>In-App
      </a>
      <a href="<?php echo e(route('notifications.index', ['channel' => 'whatsapp', 'status' => request('status')])); ?>"
        class="filter-btn <?php echo e(request('channel') === 'whatsapp' ? 'active' : ''); ?>">
        <i class="icon-base ti tabler-brand-whatsapp" style="color:#25D366;"></i>WhatsApp
      </a>
      <a href="<?php echo e(route('notifications.index', ['channel' => 'email', 'status' => request('status')])); ?>"
        class="filter-btn <?php echo e(request('channel') === 'email' ? 'active' : ''); ?>">
        <i class="icon-base ti tabler-mail" style="color:#6366f1;"></i>Email
      </a>

      <span style="width:1px; height:24px; background: rgba(255,255,255,0.1); margin: 4px 8px;"></span>

      <a href="<?php echo e(route('notifications.index', ['status' => 'all', 'channel' => request('channel')])); ?>"
        class="filter-btn <?php echo e(request('status', 'all') === 'all' ? 'active' : ''); ?>">Semua Status</a>
      <a href="<?php echo e(route('notifications.index', ['status' => 'unread', 'channel' => request('channel')])); ?>"
        class="filter-btn <?php echo e(request('status') === 'unread' ? 'active' : ''); ?>">Belum Dibaca</a>
      <a href="<?php echo e(route('notifications.index', ['status' => 'read', 'channel' => request('channel')])); ?>"
        class="filter-btn <?php echo e(request('status') === 'read' ? 'active' : ''); ?>">Sudah Dibaca</a>
    </div>
  </div>

  
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"
      style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25); color: #86efac; border-radius: 5px;">
      <i class="icon-base ti tabler-check-circle me-1"></i><?php echo e(session('success')); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1) brightness(2);"></button>
    </div>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  
  <div class="glass-card-premium overflow-hidden p-0">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="notif-item <?php echo e($notif->read_at ? '' : 'unread'); ?> p-3 p-xl-4" style="border-radius: 0; border-top: none; border-left: none; border-right: none; margin-bottom: 0;">
        <div class="d-flex align-items-start gap-3">
          
          <div class="flex-shrink-0 mt-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notif->channel === 'whatsapp'): ?>
              <div class="stat-icon-box stat-icon-success" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-brand-whatsapp"></i>
              </div>
            <?php elseif($notif->channel === 'email'): ?>
              <div class="stat-icon-box stat-icon-primary" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-mail"></i>
              </div>
            <?php else: ?>
              <div class="stat-icon-box stat-icon-warning" style="width: 40px; height: 40px; font-size: 1.2rem;">
                <i class="icon-base ti tabler-bell"></i>
              </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
          
          <div class="flex-grow-1" style="min-width: 0;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
              <div>
                <h6 class="fw-semibold mb-0" style="color: #f8fafc; font-size: 0.95rem;">
                  <?php echo e($notif->title); ?>

                </h6>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notif->read_at): ?>
                  <span class="badge-premium badge-premium-primary ms-2" style="font-size: 0.6rem; padding: 2px 8px;">NEW</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </div>
              <small style="color: rgba(255,255,255,0.4); font-size: 0.75rem; white-space: nowrap;">
                <?php echo e($notif->created_at->diffForHumans()); ?>

              </small>
            </div>
            <p style="color: rgba(255,255,255,0.6); font-size: 0.85rem; margin: 6px 0 0 0; line-height: 1.5;">
              <?php echo e(Illuminate\Support\Str::limit($notif->body, 200)); ?>

            </p>
            <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
              <span class="badge-premium" style="font-size: 0.7rem;">
                <i class="icon-base ti tabler-<?php echo e($notif->channel === 'whatsapp' ? 'brand-whatsapp' : ($notif->channel === 'email' ? 'mail' : 'bell')); ?> me-1"></i>
                <?php echo e(ucfirst($notif->channel)); ?>

              </span>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notif->read_at): ?>
                <span class="badge-premium badge-premium-success" style="font-size: 0.7rem;">
                  <i class="icon-base ti tabler-check me-1"></i>Sudah Dibaca
                </span>
              <?php else: ?>
                <form action="<?php echo e(route('notifications.read', $notif->id)); ?>" method="POST" class="d-inline">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="btn btn-sm p-0" style="background: none; border: none; color: #818cf8; font-size: 0.8rem;">
                    <i class="icon-base ti tabler-check me-1"></i>Tandai Dibaca
                  </button>
                </form>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

              
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notif->data && isset($notif->data['wa_data'])): ?>
                <?php
                  $waData = $notif->data['wa_data'];
                  $adminWa = $waData['admin_wa'] ?? $waData['admin_phone'] ?? \App\Models\Setting::where('key', 'whatsapp_sender')->value('value') ?? '62888888888';
                  $waMsg = $waData['message'] ?? "Halo Admin, saya telah melakukan pendaftaran pelatihan.\n\nNama Lengkap Sesuai KTP : " . ($waData['nama_lengkap'] ?? '-') . "\nJenis Pelatihan : " . ($waData['pelatihan'] ?? '-') . "\nKelurahan : " . ($waData['kelurahan'] ?? '-') . "\nKecamatan : " . ($waData['kecamatan'] ?? '-') . "\nNo. HP Peserta Terdaftar : " . ($waData['no_hp'] ?? '-') . "\n\n#pelatihanku2026";
                ?>
                <a href="https://wa.me/<?php echo e($adminWa); ?>?text=<?php echo e(urlencode($waMsg)); ?>"
                  target="_blank" class="btn btn-sm"
                  style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 5px; font-size: 0.75rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                  <i class="icon-base ti tabler-brand-whatsapp"></i> Hubungi Admin
                </a>
              <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="text-center py-5 px-4">
        <div class="stat-icon-box stat-icon-secondary mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem; border-radius: 50% !important;">
          <i class="icon-base ti tabler-bell-off"></i>
        </div>
        <h5 class="fw-semibold" style="color: rgba(255,255,255,0.6);">Belum ada notifikasi</h5>
        <p style="color: rgba(255,255,255,0.4); font-size: 0.9rem;">
          Notifikasi akan muncul di sini ketika ada aktivitas terkait akun Anda.
        </p>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>

  
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notifications->hasPages()): ?>
    <div class="mt-4 pagination-container d-flex justify-content-center">
      <?php echo e($notifications->links()); ?>

    </div>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/dashboard/peserta/notifikasi.blade.php ENDPATH**/ ?>
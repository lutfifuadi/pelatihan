<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Detail Push Notification'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  .glass-card-premium {
    background: rgba(15, 23, 42, 0.25) !important;
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    border-radius: 5px !important;
  }
  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }
  .badge-premium {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 4px 12px;
    font-size: 0.75rem;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold mb-4">Detail Push Notification</h4>

  <div class="glass-card-premium mb-4 p-4">
    <h5 class="text-white"><?php echo e($notification->title); ?></h5>
    <p class="text-body-premium"><?php echo e($notification->body); ?></p>

    <div class="row g-2 mt-3">
        <div class="col-md-6">
            <div class="text-body-premium small">Link URL: <?php echo e($notification->link_url ?? '-'); ?></div>
            <div class="text-body-premium small">Target: <?php echo e($notification->target_type === 'all' ? 'Semua' : 'Filter'); ?></div>
        </div>
        <div class="col-md-6">
            <div class="text-body-premium small">Total Target: <?php echo e($notification->total_target); ?></div>
            <div class="text-body-premium small">Waktu Kirim: <?php echo e($notification->sent_at?->format('d M Y H:i') ?? 'Belum'); ?></div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$notification->sent_at): ?>
        <form action="<?php echo e(route('admin.push-notifications.send', $notification)); ?>" method="POST" class="mt-3">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-success btn-sm">Kirim Sekarang</button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <a href="<?php echo e(route('admin.push-notifications.index')); ?>" class="btn btn-secondary btn-sm mt-3">Kembali</a>
  </div>

  <div class="glass-card-premium p-4">
    <h5 class="text-white mb-3">Log Penerima</h5>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recipients->isEmpty()): ?>
      <p class="text-body-premium">Belum ada log.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-hover table-borderless text-white align-middle">
          <thead>
            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
              <th class="text-body-premium small">ID Sub</th>
              <th class="text-body-premium small">Status</th>
              <th class="text-body-premium small">Error</th>
              <th class="text-body-premium small">Waktu</th>
            </tr>
          </thead>
          <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($recipient->subscription?->id ?? '-'); ?></td>
                <td>
                  <span class="badge-premium"><?php echo e($recipient->status); ?></span>
                </td>
                <td class="small text-body-premium"><?php echo e($recipient->error_message ?? '-'); ?></td>
                <td class="small"><?php echo e($recipient->sent_at?->format('d M Y H:i') ?? '-'); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php echo e($recipients->links()); ?>

    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/push-notifications/show.blade.php ENDPATH**/ ?>
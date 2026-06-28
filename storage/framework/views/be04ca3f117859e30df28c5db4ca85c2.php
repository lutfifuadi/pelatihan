<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kecamatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium"><?php echo e($kecamatans->firstItem() + $index); ?></td>
    <td class="py-3 text-white fw-semibold" style="text-transform: uppercase;"><?php echo e($kecamatan->name); ?></td>
    <td class="py-3">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kecamatan->users->count() > 0): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kecamatan->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $koordinator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="d-flex align-items-center gap-2 mb-1">
            <i class="icon-base ti tabler-user text-primary" style="font-size: 0.85rem;"></i>
            <span class="text-white" style="font-size: 0.85rem;"><?php echo e($koordinator->name); ?></span>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      <?php else: ?>
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="py-3">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kecamatan->users->count() > 0): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kecamatan->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $koordinator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="mb-1" style="font-size: 0.85rem;">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($koordinator->whatsapp): ?>
              <span class="text-body-premium">
                <i class="icon-base ti tabler-brand-whatsapp text-success me-1" style="font-size: 0.85rem;"></i>
                <?php echo e($koordinator->whatsapp); ?>

              </span>
            <?php elseif($koordinator->phone): ?>
              <span class="text-body-premium">
                <i class="icon-base ti tabler-phone text-info me-1" style="font-size: 0.85rem;"></i>
                <?php echo e($koordinator->phone); ?>

              </span>
            <?php else: ?>
              <span class="text-body-premium">—</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      <?php else: ?>
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="text-end px-0 py-3">
      <div class="d-inline-flex gap-2">
        <a href="<?php echo e(route('admin.kecamatan.edit', $kecamatan)); ?>" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
          <i class="icon-base ti tabler-edit fs-5 text-dark"></i>
        </a>
        <form action="<?php echo e(route('admin.kecamatan.destroy', $kecamatan)); ?>" method="POST" class="d-inline"
          onsubmit="return confirm('Yakin ingin menghapus kecamatan <?php echo e($kecamatan->name); ?>?')">
          <?php echo csrf_field(); ?>
          <?php echo method_field('DELETE'); ?>
          <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
            <i class="icon-base ti tabler-trash fs-5"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <tr>
    <td colspan="5" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-map-off fs-1 mb-2 d-block text-warning"></i>
      Belum ada data kecamatan.
    </td>
  </tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/kecamatan/_table_content.blade.php ENDPATH**/ ?>
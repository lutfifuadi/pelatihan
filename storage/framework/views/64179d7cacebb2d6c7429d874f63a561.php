<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium"><?php echo e($users->firstItem() + $index); ?></td>
    <td class="py-3">
      <div class="d-flex flex-column">
        <span class="text-white fw-semibold"><?php echo e($u->name); ?></span>
        <span class="text-body-premium" style="font-size: 0.8rem;"><?php echo e($u->email); ?></span>
      </div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;"><?php echo e($u->nik ?? '-'); ?></td>
    <td class="py-3">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->whatsapp): ?>
        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $u->whatsapp)); ?>" target="_blank" class="text-body-premium hover-text-success" style="font-size: 0.85rem; text-decoration: none;">
          <i class="icon-base ti tabler-brand-whatsapp text-success me-1" style="font-size: 0.95rem;"></i>
          <?php echo e($u->whatsapp); ?>

        </a>
      <?php else: ?>
        <span class="text-body-premium" style="font-size: 0.85rem;">—</span>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="py-3">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->role === 'admin'): ?>
        <span class="badge-premium" style="background: rgba(139, 92, 246, 0.15); border-color: rgba(139, 92, 246, 0.3); color: #a78bfa;">Admin</span>
      <?php elseif($u->role === 'instruktur'): ?>
        <span class="badge-premium" style="background: rgba(6, 182, 212, 0.15); border-color: rgba(6, 182, 212, 0.3); color: #22d3ee;">Instruktur</span>
      <?php elseif($u->role === 'koordinator'): ?>
        <span class="badge-premium" style="background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.3); color: #fbbf24;">Koordinator</span>
      <?php else: ?>
        <span class="badge-premium" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #34d399;">Peserta</span>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="py-3 text-center">
      <div class="form-check form-switch d-inline-block">
        <input class="form-check-input status-switch" type="checkbox" role="switch"
          data-id="<?php echo e($u->id); ?>"
          data-name="<?php echo e($u->name); ?>"
          data-url="<?php echo e(route('admin.users.toggle-status', $u)); ?>"
          <?php echo e($u->is_active ? 'checked' : ''); ?>

          <?php echo e($u->id === auth()->id() ? 'disabled' : ''); ?>

          style="cursor: pointer;">
      </div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
      <?php echo e($u->created_at ? $u->created_at->format('d/m/Y H:i') : '-'); ?>

    </td>
    <td class="text-end px-0 py-3">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->id !== auth()->id()): ?>
        <div class="d-inline-flex gap-1 align-items-center">
          
          <form action="<?php echo e(route('admin.users.reset-password', $u)); ?>" method="POST" class="d-inline reset-password-form"
            data-name="<?php echo e($u->name); ?>" data-role="<?php echo e($u->role); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; background: linear-gradient(135deg, #f59e0b, #d97706); border: none; color: #fff;" title="Reset Password">
              <i class="icon-base ti tabler-key fs-5"></i>
            </button>
          </form>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($u->role !== 'admin'): ?>
            <form action="<?php echo e(route('admin.users.impersonate', $u)); ?>" method="POST" class="d-inline impersonate-form"
              data-name="<?php echo e($u->name); ?>"
              data-avatar="<?php echo e($u->avatar ?? ''); ?>"
              data-email="<?php echo e($u->email); ?>"
              data-role="<?php echo e($u->role); ?>"
              data-status="<?php echo e($u->is_active ? 'aktif' : 'nonaktif'); ?>">
              <?php echo csrf_field(); ?>
              <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; background: linear-gradient(135deg, #fbbf24, #d97706); border: none; color: #fff;" title="Login As (Impersonasi)">
                <i class="icon-base ti tabler-user-shield fs-5"></i>
              </button>
            </form>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <form action="<?php echo e(route('admin.users.destroy', $u)); ?>" method="POST" class="d-inline delete-form"
            data-name="<?php echo e($u->name); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center" style="border-radius: 5px; width: 32px; height: 32px; padding: 0;">
              <i class="icon-base ti tabler-trash fs-5"></i>
            </button>
          </form>
        </div>
      <?php else: ?>
        <span class="text-body-premium small" style="font-size: 0.75rem;">(Akun Anda)</span>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
  </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <tr>
    <td colspan="8" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-users-off fs-1 mb-2 d-block text-warning"></i>
      Tidak ada data user yang ditemukan.
    </td>
  </tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/users/_table_rows.blade.php ENDPATH**/ ?>
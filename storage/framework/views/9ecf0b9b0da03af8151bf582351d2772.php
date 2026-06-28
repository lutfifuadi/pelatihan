<?php $__env->startSection('step-chip',    'Langkah 1 — Sistem'); ?>
<?php $__env->startSection('step-num',     '1'); ?>
<?php $__env->startSection('progress',     '33'); ?>
<?php $__env->startSection('step-title',   'Pengecekan Sistem'); ?>
<?php $__env->startSection('step-desc',    'Pastikan server memenuhi semua persyaratan sebelum melanjutkan instalasi.'); ?>
<?php $__env->startSection('form-action',  route('installer.step1')); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $requirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $passed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="req-row" style="margin-bottom: 0;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 28px; height: 28px; border-radius: 6px; background: <?php echo e($passed ? 'var(--success-dim)' : 'var(--danger-dim)'); ?>; display: flex; align-items: center; justify-content: center; color: <?php echo e($passed ? 'var(--success)' : 'var(--danger)'); ?>;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($passed): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php else: ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <span class="req-name" style="font-size: 0.8125rem;"><?php echo e($label); ?></span>
                </div>
                <span class="badge <?php echo e($passed ? 'badge-ok' : 'badge-fail'); ?>">
                    <?php echo e($passed ? 'Aktif' : 'Gagal'); ?>

                </span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$allPassed): ?>
        <div class="notice notice-err mt-16 mb-0">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="13"/><circle cx="12" cy="16" r=".5" fill="currentColor"/>
            </svg>
            <span>Server Anda belum memenuhi syarat. Perbaiki error di atas lalu refresh.</span>
        </div>
    <?php else: ?>
        <div class="notice notice-info mt-16 mb-0">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            </svg>
            <span>Server siap digunakan! Silakan lanjut ke konfigurasi database.</span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('foot-l'); ?>
    <a href="<?php echo e(route('installer.step1')); ?>" class="btn btn-ghost">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="1 4 1 10 7 10"/>
            <path d="M3.51 15a9 9 0 1 0 .49-5"/>
        </svg>
        Refresh
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('foot-r'); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($allPassed): ?>
        <a href="<?php echo e(route('installer.step2')); ?>" class="btn btn-primary">
            Lanjut
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    <?php else: ?>
        <button type="button" class="btn btn-primary" disabled>
            Lanjut
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('installer.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/installer/step1.blade.php ENDPATH**/ ?>
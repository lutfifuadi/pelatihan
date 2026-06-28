<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
    <td class="px-0 py-3 text-body-premium"><?php echo e($enrollments->firstItem() + $index); ?></td>
    <td class="py-3">
      <div class="d-flex align-items-center flex-wrap gap-2">
        <span class="fw-semibold text-white"><?php echo e($enrollment->user?->pesertaProfile?->nama_lengkap ?: $enrollment->user?->name ?? 'User tidak ditemukan'); ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($enrollment->user?->ktaMember?->status_kta ?? null) === 'Aktif' || ($enrollment->is_kta_active ?? false)): ?>
          <span class="badge-premium badge-premium-success d-inline-flex align-items-center gap-1" title="Anggota KTA aktif">
            <i class="icon-base ti tabler-id-badge fs-6"></i> KTA Aktif
          </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->is_kta_priority ?? false): ?>
          <span class="badge-premium badge-premium-warning d-inline-flex align-items-center gap-1" title="Prioritas KTA — didahulukan dalam antrian approval">
            <i class="icon-base ti tabler-star fs-6"></i> Prioritas
          </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </div>
      <div class="text-body-premium" style="font-size: 0.75rem;"><?php echo e($enrollment->user?->whatsapp ?: $enrollment->user?->phone ?? '-'); ?></div>
    </td>
    <td class="py-3">
      <div class="fw-semibold text-white" style="font-size: 0.85rem;"><?php echo e($enrollment->pelatihan->nama); ?></div>
      <div class="text-body-premium" style="font-size: 0.7rem;">Batch: <?php echo e($enrollment->pelatihan->batch); ?></div>
    </td>
    <td class="py-3 text-body-premium" style="font-size: 0.85rem;">
      <?php echo e($enrollment->created_at->format('d/m/Y H:i')); ?>

    </td>
    <td class="py-3">
      <?php $statusEnum = $enrollment->status; ?>
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($statusEnum?->value ?? $enrollment->status):
        case ('pending'): ?>
          <span class="badge-premium badge-premium-warning"><?php echo e($enrollment->statusLabel()); ?></span>
          <?php break; ?>
        <?php case ('approved'): ?>
          <span class="badge-premium badge-premium-success"><?php echo e($enrollment->statusLabel()); ?></span>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->waitlist_promoted_at): ?>
            <div style="font-size: 0.65rem; color: #93c5fd; margin-top: 2px;">Dari cadangan</div>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php break; ?>
        <?php case ('waiting_wa_confirmation'): ?>
          <span class="badge" style="background: rgba(234,179,8,0.15); color: #eab308; border: 1px solid rgba(234,179,8,0.3);">
            <i class="icon-base ti tabler-brand-whatsapp me-1"></i><?php echo e($enrollment->statusLabel()); ?>

          </span>
          <?php break; ?>
        <?php case ('waiting_newbimma_check'): ?>
          <span class="badge" style="background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);">
            <i class="icon-base ti tabler-search me-1"></i><?php echo e($enrollment->statusLabel()); ?>

          </span>
          <?php break; ?>
        <?php case ('confirmed'): ?>
          <span class="badge" style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3);">
            <i class="icon-base ti tabler-circle-check me-1"></i><?php echo e($enrollment->statusLabel()); ?>

          </span>
          <?php break; ?>
        <?php case ('rejected'): ?>
          <span class="badge-premium badge-premium-danger"><?php echo e($enrollment->statusLabel()); ?></span>
          <?php break; ?>
        <?php case ('waitlist'): ?>
          <span class="badge-premium badge-premium-info"><?php echo e($enrollment->statusLabel()); ?></span>
          <?php break; ?>
        <?php default: ?>
          <span class="badge-premium"><?php echo e($enrollment->statusLabel()); ?></span>
      <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td class="text-end px-0 py-3" style="white-space: nowrap;">
      
      <div class="dropdown d-inline">
        <button class="btn btn-sm dropdown-toggle d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" style="border-radius: 5px; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2); color: #93c5fd; padding: 4px 10px; font-size: 0.75rem;" title="Ubah Status">
          <i class="icon-base ti tabler-arrows-exchange me-1"></i> Status
        </button>
        <ul class="dropdown-menu dropdown-menu-dark" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; min-width: 180px;">
          <li><h6 class="dropdown-header" style="color: rgba(255,255,255,0.5); font-size: 0.7rem; text-transform: uppercase;">Ubah Status Ke:</h6></li>
          <?php
            $allStatuses = $enrollmentStatuses ?? \App\Enums\EnrollmentStatus::values();
            $statusColors = [
              'pending' => '#fbbf24',
              'approved' => '#34d399',
              'waiting_wa_confirmation' => '#eab308',
              'waiting_newbimma_check' => '#3b82f6',
              'confirmed' => '#22c55e',
              'rejected' => '#f87171',
              'waitlist' => '#93c5fd',
            ];
          ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $statusEnum = \App\Enums\EnrollmentStatus::fromValue($statusValue); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusEnum): ?>
            <li>
              <form action="<?php echo e(route('admin.enrollments.change-status', $enrollment)); ?>" method="POST" class="change-status-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="status" value="<?php echo e($statusEnum->value); ?>">
                <input type="hidden" name="notes" value="">
                <button type="submit" class="dropdown-item" style="color: <?php echo e($statusColors[$statusEnum->value] ?? '#93c5fd'); ?>; font-size: 0.8rem; padding: 6px 16px;">
                  <?php echo e($statusEnum->label()); ?>

                </button>
              </form>
            </li>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
      </div>

      
      <a href="<?php echo e(route('admin.enrollments.show', $enrollment)); ?>" class="btn btn-outline-info btn-sm d-inline-flex align-items-center justify-content-center ms-1" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border-color: rgba(96,165,250,0.3); color: #93c5fd;" title="Detail">
        <i class="icon-base ti tabler-eye fs-5"></i>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->user?->whatsapp): ?>
        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $enrollment->user->whatsapp)); ?>"
           class="btn btn-outline-success btn-sm d-inline-flex align-items-center justify-content-center ms-1"
           style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border-color: rgba(37,211,102,0.3); color: #25D366;"
           target="_blank" title="Chat WhatsApp">
          <i class="icon-base ti tabler-brand-whatsapp fs-5"></i>
        </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      
      <form action="<?php echo e(route('admin.enrollments.reset', $enrollment)); ?>" method="POST" class="d-inline reset-enrollment-form" data-name="<?php echo e($enrollment->user?->name ?? 'Unknown'); ?>" data-pelatihan="<?php echo e($enrollment->pelatihan->nama); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center ms-1" style="border-radius: 5px; width: 32px; height: 32px; padding: 0; border: none; background: linear-gradient(135deg, #f59e0b, #d97706);" title="Reset pendaftaran">
          <i class="icon-base ti tabler-refresh fs-5"></i>
        </button>
      </form>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->status?->value === 'approved' && !$enrollment->verification_code): ?>
        <form action="<?php echo e(route('admin.enrollments.generate-verification-code', $enrollment)); ?>" method="POST" class="d-inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-sm"
                  style="background: #6366f1; color: white; border: none; border-radius: 5px; padding: 4px 10px;">
            <i class="icon-base ti tabler-key"></i> Generate Kode
          </button>
        </form>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->status?->value === 'approved' && $enrollment->verification_code && !$enrollment->wa_confirmed_at): ?>
        <form action="<?php echo e(route('admin.enrollments.confirm-wa-chat', $enrollment)); ?>" method="POST" class="d-inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-sm"
                  style="background: #25D366; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Konfirmasi bahwa peserta sudah chat WA?')">
            <i class="icon-base ti tabler-brand-whatsapp"></i> Sudah Chat WA
          </button>
        </form>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enrollment->status?->value === 'approved' && $enrollment->wa_confirmed_at && !$enrollment->newbimma_checked_at): ?>
        <a href="#" class="btn btn-sm btn-outline-primary"
           onclick="window.open('https://newbimma.example.com', '_blank')"
           style="font-size: 11px;">
          🔍 Cek Newbimma
        </a>
        <form action="<?php echo e(route('admin.enrollments.confirm-newbimma-valid', $enrollment)); ?>" method="POST" class="d-inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-sm"
                  style="background: #22c55e; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Validasi Newbimma: Pastikan peserta TIDAK TERDAFTAR di pelatihan yang sama. Lanjutkan?')">
            ✅ Valid
          </button>
        </form>
        <form action="<?php echo e(route('admin.enrollments.reject-newbimma-invalid', $enrollment)); ?>" method="POST" class="d-inline">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-sm"
                  style="background: #ef4444; color: white; border: none; border-radius: 5px; padding: 4px 10px;"
                  onclick="return confirm('Yakin ingin menolak? Peserta sudah pernah ikut pelatihan yang sama di Newbimma.')">
            ❌ Tolak
          </button>
        </form>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
  </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <tr>
    <td colspan="6" class="text-center text-body-premium py-5">
      <i class="icon-base ti tabler-inbox fs-1 mb-2 d-block text-warning"></i>
      Belum ada pendaftaran.
    </td>
  </tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/enrollments/_table_rows.blade.php ENDPATH**/ ?>
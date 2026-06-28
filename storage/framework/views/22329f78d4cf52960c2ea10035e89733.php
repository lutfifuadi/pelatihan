<?php
  $institutionDesc = \App\Models\Setting::where('key', 'institution_description')->value('value') ?? 'Program pelatihan pengembangan kompetensi dan keterampilan praktis yang mandiri, kreatif, dan berdaya saing.';
  $institutionAddress = \App\Models\Setting::where('key', 'institution_address')->value('value') ?? '';
  $institutionPhone = \App\Models\Setting::where('key', 'institution_phone')->value('value') ?? '';
  $footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?? 'Pelatihan — Pengembangan Kompetensi';
?>

<footer class="footer-premium py-6 py-lg-7 text-white">
  <div class="container">
    <div class="row g-5 mb-5 text-start">
      <div class="col-lg-5 col-md-12">
        <a href="<?php echo e(url('/')); ?>" class="d-flex align-items-center gap-2 text-decoration-none mb-3">
          <div class="logo-icon-glow" style="width:34px; height:34px; border-radius:5px;">
            <i class="icon-base ti tabler-bulb text-white fs-5"></i>
          </div>
          <?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => ['size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $attributes = $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3)): ?>
<?php $component = $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3; ?>
<?php unset($__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3); ?>
<?php endif; ?>
        </a>
        <p class="text-white-50 mb-4" style="max-width: 400px; font-size: 0.95rem; line-height: 1.65;">
          <?php echo e($institutionDesc); ?>

        </p>
        <div class="d-flex gap-3">
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-instagram"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-facebook"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-brand-youtube"></i></a>
          <a href="#" class="social-icon-btn"><i class="icon-base ti tabler-mail"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-md-4 col-sm-6">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;"><?php echo e(__('Kategori Pelatihan')); ?></h6>
        <ul class="list-unstyled d-flex flex-column gap-2">
          <li><a href="#" class="footer-link">Kuliner Kreatif</a></li>
          <li><a href="#" class="footer-link">Konten Kreator</a></li>
          <li><a href="#" class="footer-link">Desain Grafis</a></li>
          <li><a href="#" class="footer-link">Kriya & Seni Tradisional</a></li>
        </ul>
      </div>

      <div class="col-lg-2 col-md-4 col-sm-6">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;"><?php echo e(__('Tautan Penting')); ?></h6>
          <ul class="list-unstyled d-flex flex-column gap-2">
            <li><a href="<?php echo e(route('pages-home')); ?>#beranda" class="footer-link"><?php echo e(__('Beranda')); ?></a></li>
            <li><a href="<?php echo e(route('pages-home')); ?>#pelatihan" class="footer-link"><?php echo e(__('Pelatihan')); ?></a></li>
            <li><a href="<?php echo e(route('pages-home')); ?>#langkah" class="footer-link"><?php echo e(__('Cara Daftar')); ?></a></li>
            <li><a href="<?php echo e(route('pages-home')); ?>#mengapa" class="footer-link"><?php echo e(__('Keunggulan')); ?></a></li>
            <li><a href="<?php echo e(route('pages-home')); ?>#faq" class="footer-link"><?php echo e(__('FAQ')); ?></a></li>
            <li><a href="<?php echo e(route('koordinator.register')); ?>" class="footer-link"><?php echo e(__('Daftar Koordinator')); ?></a></li>
          </ul>
      </div>

      <div class="col-lg-2 col-md-4 col-sm-12">
        <h6 class="text-white fw-bold mb-4" style="font-family: 'Sora', sans-serif;"><?php echo e(__('Hubungi Penyelenggara')); ?></h6>
        <p class="text-white-50 mb-3 small d-flex align-items-start gap-2" style="line-height: 1.5;">
           <i class="icon-base ti tabler-map-pin text-warning mt-1"></i> <?php echo e($institutionAddress); ?>

        </p>
        <p class="text-white-50 mb-0 small d-flex align-items-center gap-2">
           <i class="icon-base ti tabler-phone text-warning"></i> <?php echo e($institutionPhone); ?>

        </p>
      </div>
    </div>

    <div style="width: 100%; height: 1px; background: rgba(255, 255, 255, 0.07); margin-bottom: 24px;"></div>

    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-center text-md-start">
      <p class="text-white-50 small mb-0">
        &copy; <?php echo e(date('Y')); ?> <span class="text-white fw-medium"><?php echo e($footerCopyright); ?></span>. All rights reserved.
      </p>
      <div class="d-flex gap-4">
        <a href="#" class="text-white-50 small text-decoration-none hover-white"><?php echo e(__('Kebijakan Privasi')); ?></a>
        <a href="#" class="text-white-50 small text-decoration-none hover-white"><?php echo e(__('Syarat & Ketentuan')); ?></a>
      </div>
    </div>
  </div>
</footer>
<?php /**PATH D:\Project\Pelatihanku\resources\views/partials/site-footer.blade.php ENDPATH**/ ?>
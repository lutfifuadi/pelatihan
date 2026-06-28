<header class="navbar-glass-floating">
  <div class="container d-flex align-items-center justify-content-between p-0">
    <a href="<?php echo e(route('pages-home')); ?>#beranda" class="navbar-logo d-flex align-items-center gap-2 text-decoration-none">
      <div class="logo-icon-glow">
        <i class="icon-base ti tabler-bulb text-white fs-4"></i>
      </div>
      <?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => ['size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'lg']); ?>
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
    <!-- Mobile Hamburger: animated 3-bar toggle -->
    <button class="mobile-menu-btn d-lg-none ms-auto me-3" id="mobileMenuToggle" aria-label="Buka menu">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>
    <nav class="d-none d-lg-flex align-items-center gap-4">
      <a href="<?php echo e(route('pages-home')); ?>#beranda" class="nav-link-premium"><?php echo e(__('Beranda')); ?></a>
      <a href="<?php echo e(route('pages-home')); ?>#pelatihan" class="nav-link-premium"><?php echo e(__('Pelatihan')); ?></a>
      <a href="<?php echo e(route('pages-home')); ?>#langkah" class="nav-link-premium"><?php echo e(__('Alur Pendaftaran')); ?></a>
      <a href="<?php echo e(route('pages-home')); ?>#mengapa" class="nav-link-premium"><?php echo e(__('Keunggulan')); ?></a>
      <a href="<?php echo e(route('pages-home')); ?>#faq" class="nav-link-premium"><?php echo e(__('FAQ')); ?></a>
    </nav>
    <div class="d-flex align-items-center gap-2">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('dashboard.admin')); ?>" class="btn btn-login-premium d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-dashboard fs-5"></i>Dashboard
        </a>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-login-premium d-flex align-items-center gap-2">
          <i class="icon-base ti tabler-login fs-5"></i>Login
        </a>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
  </div>
</header>

<!-- Mobile overlay (blur background) -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Mobile slide-in panel from left -->
<div class="mobile-slide-panel" id="mobileSlidePanel">
  <div class="panel-header">
    <span class="panel-title"><?php echo e(__('Menu')); ?></span>
    <button class="panel-close-btn" id="mobileMenuClose" aria-label="<?php echo e(__('Close')); ?>">
      <i class="icon-base ti tabler-x fs-4"></i>
    </button>
  </div>

  <nav class="panel-nav">
    <a href="<?php echo e(route('pages-home')); ?>#beranda" class="panel-link">
      <i class="icon-base ti tabler-smart-home"></i>
      <?php echo e(__('Beranda')); ?>

    </a>
    <a href="<?php echo e(route('pages-home')); ?>#pelatihan" class="panel-link">
      <i class="icon-base ti tabler-school"></i>
      <?php echo e(__('Pelatihan')); ?>

    </a>
    <a href="<?php echo e(route('pages-home')); ?>#langkah" class="panel-link">
      <i class="icon-base ti tabler-list-check"></i>
      <?php echo e(__('Alur Pendaftaran')); ?>

    </a>
    <a href="<?php echo e(route('pages-home')); ?>#mengapa" class="panel-link">
      <i class="icon-base ti tabler-star"></i>
      <?php echo e(__('Keunggulan')); ?>

    </a>
    <a href="<?php echo e(route('pages-home')); ?>#faq" class="panel-link">
      <i class="icon-base ti tabler-question-mark"></i>
      <?php echo e(__('FAQ')); ?>

    </a>
  </nav>

  <div class="panel-footer">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('dashboard.admin')); ?>" class="btn btn-login-premium w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="icon-base ti tabler-dashboard fs-5"></i>Dashboard
      </a>
    <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="btn btn-login-premium w-100 d-flex align-items-center justify-content-center gap-2">
        <i class="icon-base ti tabler-login fs-5"></i>Login
      </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>
<?php /**PATH D:\Project\Pelatihanku\resources\views/partials/floating-navbar.blade.php ENDPATH**/ ?>
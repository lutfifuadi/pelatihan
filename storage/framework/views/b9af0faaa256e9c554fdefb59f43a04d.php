<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Branding Aplikasi'); ?>

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
  .layout-menu .app-brand .app-brand-text {
    color: #ffffff !important;
  }

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

  .form-control, .form-select, textarea {
    background: rgba(255, 255, 255, 0.03) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
    color: #ffffff !important;
    border-radius: 5px !important;
    padding: 10px 14px !important;
    font-size: 14px !important;
    transition: all 0.3s ease !important;
  }
  .form-control:focus, .form-select:focus, textarea:focus {
    background: rgba(255, 255, 255, 0.06) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.25) !important;
    color: #ffffff !important;
  }
  .form-control::placeholder, textarea::placeholder {
    color: rgba(255, 255, 255, 0.35) !important;
  }
  .form-control.is-invalid, .form-select.is-invalid, textarea.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.2) !important;
  }
  .form-label {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7) !important;
    margin-bottom: 6px;
  }

  .form-control:-webkit-autofill,
  .form-control:-webkit-autofill:hover,
  .form-control:-webkit-autofill:focus,
  .form-control:-webkit-autofill:active {
    -webkit-text-fill-color: #ffffff !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #131824 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #131824 inset !important;
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

  .preview-logo-card {
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 5px;
    padding: 16px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>
  <div class="glow-orb orb-3"></div>

  <div class="container-fluid px-4 px-lg-6 position-relative" style="z-index: 1;">

    <div class="glass-card-premium px-4 px-xl-5 py-4 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon-box" style="background: rgba(255, 193, 7, 0.12); color: #ffc107;">
            <i class="icon-base ti tabler-brand-gravatar fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Branding Aplikasi</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Kelola tampilan brand dan identitas aplikasi
            </p>
          </div>
        </div>
      </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
      <div class="alert alert-success alert-dismissible border-0 mb-4" role="alert" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 5px;">
        <div class="d-flex align-items-center">
          <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
          <span><?php echo e(session('success')); ?></span>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="col-12">
      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="<?php echo e(route('admin.settings.branding.update')); ?>" method="POST">
          <?php echo csrf_field(); ?>

          
          <div class="row">
            <div class="col-12 mb-3">
              <label for="brand_name" class="form-label">Nama Brand <span class="text-danger">*</span></label>
              <input type="text" class="form-control <?php $__errorArgs = ['brand_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="brand_name" name="brand_name"
                value="<?php echo e(old('brand_name', $settings['brand_name']->value ?? 'SABA Kreatif')); ?>"
                placeholder="SABA Kreatif" required>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['brand_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
                Nama brand akan ditampilkan di logo aplikasi (contoh: SABA Kreatif)
              </small>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="brand_logo_size" class="form-label">Ukuran Logo <span class="text-danger">*</span></label>
              <select class="form-select <?php $__errorArgs = ['brand_logo_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="brand_logo_size" name="brand_logo_size" required>
                <?php
                  $currentSize = old('brand_logo_size', $settings['brand_logo_size']->value ?? 'md');
                  $sizeOptions = ['sm' => 'Kecil (sm)', 'md' => 'Sedang (md)', 'lg' => 'Besar (lg)', 'xl' => 'Extra Besar (xl)'];
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sizeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($val); ?>" <?php echo e($currentSize == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              </select>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['brand_logo_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-muted">Pilih ukuran tampilan logo brand di halaman publik</small>
            </div>
            <div class="col-md-6 mb-3">
              <div class="preview-logo-card">
                <p class="text-body-premium small mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                  <i class="icon-base ti tabler-eye me-1"></i>Pratinjau Logo
                </p>
                <div class="d-flex align-items-center gap-2">
                  <?php
                    $previewSize = old('brand_logo_size', $settings['brand_logo_size']->value ?? 'md');
                  ?>
                  <?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.brand-logo','data' => ['size' => ''.e($previewSize).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('brand-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => ''.e($previewSize).'']); ?>
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
                </div>
                <small class="text-white-50 mt-2 d-block">Ukuran: <?php echo e($previewSize); ?></small>
              </div>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h6 class="mb-3">Identitas Institusi</h6>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="institution_name" class="form-label">Nama Institusi <span class="text-danger">*</span></label>
              <input type="text" class="form-control <?php $__errorArgs = ['institution_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="institution_name" name="institution_name"
                value="<?php echo e(old('institution_name', $settings['institution_name']->value ?? 'Lembaga Pelatihan')); ?>" required>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['institution_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
              <label for="institution_email" class="form-label">Email Institusi</label>
              <input type="email" class="form-control <?php $__errorArgs = ['institution_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="institution_email" name="institution_email"
                value="<?php echo e(old('institution_email', $settings['institution_email']->value ?? 'admin@sabakreatif.com')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['institution_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="institution_phone" class="form-label">Nomor Telepon</label>
              <input type="text" class="form-control <?php $__errorArgs = ['institution_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="institution_phone" name="institution_phone"
                value="<?php echo e(old('institution_phone', $settings['institution_phone']->value ?? '+62 812-3456-7890')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['institution_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-md-6 mb-3">
              <label for="footer_copyright" class="form-label">Footer Copyright</label>
              <input type="text" class="form-control <?php $__errorArgs = ['footer_copyright'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="footer_copyright" name="footer_copyright"
                value="<?php echo e(old('footer_copyright', $settings['footer_copyright']->value ?? 'Pelatihan — Pengembangan Kompetensi')); ?>"
                placeholder="Pelatihan — Pengembangan Kompetensi">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['footer_copyright'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="institution_address" class="form-label">Alamat Institusi</label>
              <textarea class="form-control <?php $__errorArgs = ['institution_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="institution_address" name="institution_address" rows="2"><?php echo e(old('institution_address', $settings['institution_address']->value ?? 'Gedung Pusat Pembelajaran Kreatif')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['institution_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="institution_description" class="form-label">Deskripsi Institusi</label>
              <textarea class="form-control <?php $__errorArgs = ['institution_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="institution_description" name="institution_description" rows="3"><?php echo e(old('institution_description', $settings['institution_description']->value ?? '')); ?></textarea>
              <small class="text-muted">Deskripsi singkat yang tampil di footer halaman beranda</small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['institution_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-settings me-2"></i>Validasi & Konfigurasi
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Mengatur validasi pendaftaran, broadcast real-time, dan zona waktu aplikasi.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="validate_whatsapp" class="form-label">Validasi Otomatis Nomor WhatsApp <span class="text-danger">*</span></label>
              <select class="form-select <?php $__errorArgs = ['validate_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="validate_whatsapp" name="validate_whatsapp" required>
                <?php
                  $currentValidateWa = old('validate_whatsapp', ($settings['validate_whatsapp'] ?? null)?->value ?? '1');
                ?>
                <option value="1" <?php echo e($currentValidateWa == '1' ? 'selected' : ''); ?>>Aktif (Periksa Nomor Terdaftar via API)</option>
                <option value="0" <?php echo e($currentValidateWa == '0' ? 'selected' : ''); ?>>Nonaktif (Lewati Pemeriksaan, Selalu Izinkan)</option>
              </select>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['validate_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Tentukan apakah sistem harus memverifikasi keaktifan nomor WhatsApp pendaftar melalui API eksternal.
              </small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="broadcast_enabled" class="form-label">Status Broadcast <span class="text-danger">*</span></label>
              <select class="form-select <?php $__errorArgs = ['broadcast_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="broadcast_enabled" name="broadcast_enabled" required>
                <?php
                  $currentBroadcast = old('broadcast_enabled', ($settings['broadcast_enabled'] ?? null)?->value ?? '1');
                ?>
                <option value="1" <?php echo e($currentBroadcast == '1' ? 'selected' : ''); ?>>Aktif (Broadcast real-time menyala)</option>
                <option value="0" <?php echo e($currentBroadcast == '0' ? 'selected' : ''); ?>>Nonaktif (Broadcast dimatikan)</option>
              </select>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['broadcast_enabled'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Nonaktifkan jika server WebSocket (Reverb) tidak berjalan, untuk menghindari error koneksi.
              </small>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="timezone" class="form-label">Zona Waktu <span class="text-danger">*</span></label>
              <select class="form-select <?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="timezone" name="timezone" required>
                <?php
                  $currentTimezone = old('timezone', ($settings['timezone'] ?? null)?->value ?? 'Asia/Jakarta');
                  $indonesianTimezones = [
                    'Asia/Jakarta' => 'Asia/Jakarta (WIB)',
                    'Asia/Makassar' => 'Asia/Makassar (WITA)',
                    'Asia/Jayapura' => 'Asia/Jayapura (WIT)',
                  ];
                  $allTimezones = timezone_identifiers_list();
                ?>
                <optgroup label="Zona Waktu Indonesia">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $indonesianTimezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e($currentTimezone == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </optgroup>
                <optgroup label="Zona Waktu Lainnya">
                  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allTimezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($tz, array_keys($indonesianTimezones))): ?>
                      <option value="<?php echo e($tz); ?>" <?php echo e($currentTimezone == $tz ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </optgroup>
              </select>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['timezone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Pilih zona waktu yang akan digunakan untuk pencatatan dan tampilan tanggal/waktu di seluruh sistem.
              </small>
            </div>
          </div>

          
          <div class="row mt-4">
            <div class="col-12">
              <h6 class="text-white fw-bold mb-3" style="font-size: 0.85rem;">
                <i class="icon-base ti tabler-device-mobile me-2" style="color: #818cf8;"></i>Tampilan Mobile
              </h6>
            </div>
            <div class="col-12 col-md-6">
              <label for="minat_mobile_view_mode" class="form-label">Mode Tampilan Form Minat (Mobile)</label>
              <select class="form-control <?php $__errorArgs = ['minat_mobile_view_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="minat_mobile_view_mode" name="minat_mobile_view_mode">
                <option value="horizontal" <?php echo e((old('minat_mobile_view_mode', $settings['minat_mobile_view_mode']->value ?? 'horizontal') == 'horizontal') ? 'selected' : ''); ?>>Horizontal (Swipe)</option>
                <option value="grid" <?php echo e((old('minat_mobile_view_mode', $settings['minat_mobile_view_mode']->value ?? 'horizontal') == 'grid') ? 'selected' : ''); ?>>Grid (Vertikal)</option>
              </select>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['minat_mobile_view_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.8rem;">
                Pilih bagaimana kartu pelatihan ditampilkan di perangkat mobile: Horizontal (swipe) atau Grid (vertikal).
              </small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-id-badge me-2"></i>Verifikasi KTA Otomatis
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur perilaku sistem saat peserta yang mendaftar memiliki keanggotaan KTA (Kartu Tanda Anggota) yang valid.
          </p>

          <?php $currentKtaMode = old('kta_verification_mode', $settings['kta_verification_mode']->value ?? 'off'); ?>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['kta_verification_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="mb-3 px-3 py-2 d-flex align-items-center gap-2" style="background: rgba(248,113,113,0.1); border: 1px solid rgba(248,113,113,0.25); border-radius: 5px; color: #f87171; font-size: 0.85rem;">
              <i class="icon-base ti tabler-alert-circle"></i> <?php echo e($message); ?>

            </div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

          <div class="row mb-4 g-3">
            
            <div class="col-md-4">
              <label for="kta_mode_off" class="kta-radio-card <?php echo e($currentKtaMode === 'off' ? 'active' : ''); ?>" style="cursor: pointer; display: block; padding: 1rem 1.25rem; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; background: rgba(255,255,255,0.03); transition: all 0.25s ease; position: relative;">
                <input type="radio" id="kta_mode_off" name="kta_verification_mode" value="off"
                  <?php echo e($currentKtaMode === 'off' ? 'checked' : ''); ?>

                  class="d-none" onchange="document.querySelectorAll('.kta-radio-card').forEach(el => el.classList.remove('active')); this.closest('.kta-radio-card').classList.add('active');">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(107,114,128,0.15); color: #9ca3af; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="icon-base ti tabler-power-off"></i>
                  </div>
                  <span class="fw-bold text-white" style="font-size: 0.9rem;">Nonaktif</span>
                </div>
                <p class="text-body-premium mb-0" style="font-size: 0.78rem; line-height: 1.5;">
                  Verifikasi KTA dinonaktifkan. Semua pendaftar mengikuti alur pendaftaran normal.
                </p>
                
                <div class="kta-check-icon" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; border-radius: 50%; background: #10b981; color: white; display: <?php echo e($currentKtaMode === 'off' ? 'flex' : 'none'); ?>; align-items: center; justify-content: center; font-size: 0.7rem;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
              </label>
            </div>

            
            <div class="col-md-4">
              <label for="kta_mode_priority" class="kta-radio-card <?php echo e($currentKtaMode === 'priority' ? 'active' : ''); ?>" style="cursor: pointer; display: block; padding: 1rem 1.25rem; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; background: rgba(255,255,255,0.03); transition: all 0.25s ease; position: relative;">
                <input type="radio" id="kta_mode_priority" name="kta_verification_mode" value="priority"
                  <?php echo e($currentKtaMode === 'priority' ? 'checked' : ''); ?>

                  class="d-none" onchange="document.querySelectorAll('.kta-radio-card').forEach(el => el.classList.remove('active')); this.closest('.kta-radio-card').classList.add('active');">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(245,158,11,0.15); color: #fbbf24; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="icon-base ti tabler-star"></i>
                  </div>
                  <span class="fw-bold text-white" style="font-size: 0.9rem;">Tandai Prioritas</span>
                </div>
                <p class="text-body-premium mb-0" style="font-size: 0.78rem; line-height: 1.5;">
                  Pendaftaran anggota KTA aktif diberi tanda <strong style="color: #fbbf24;">prioritas</strong> agar didahulukan admin saat approval.
                </p>
                <div class="kta-check-icon" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; border-radius: 50%; background: #10b981; color: white; display: <?php echo e($currentKtaMode === 'priority' ? 'flex' : 'none'); ?>; align-items: center; justify-content: center; font-size: 0.7rem;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
              </label>
            </div>

            
            <div class="col-md-4">
              <label for="kta_mode_auto_approve" class="kta-radio-card <?php echo e($currentKtaMode === 'auto_approve' ? 'active' : ''); ?>" style="cursor: pointer; display: block; padding: 1rem 1.25rem; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; background: rgba(255,255,255,0.03); transition: all 0.25s ease; position: relative;">
                <input type="radio" id="kta_mode_auto_approve" name="kta_verification_mode" value="auto_approve"
                  <?php echo e($currentKtaMode === 'auto_approve' ? 'checked' : ''); ?>

                  class="d-none" onchange="document.querySelectorAll('.kta-radio-card').forEach(el => el.classList.remove('active')); this.closest('.kta-radio-card').classList.add('active');">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(16,185,129,0.15); color: #34d399; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                    <i class="icon-base ti tabler-circle-check"></i>
                  </div>
                  <span class="fw-bold text-white" style="font-size: 0.9rem;">Auto-Approve</span>
                </div>
                <p class="text-body-premium mb-0" style="font-size: 0.78rem; line-height: 1.5;">
                  Pendaftaran anggota KTA aktif langsung <strong style="color: #34d399;">disetujui otomatis</strong> tanpa perlu approval admin manual.
                </p>
                <div class="kta-check-icon" style="position: absolute; top: 10px; right: 10px; width: 20px; height: 20px; border-radius: 50%; background: #10b981; color: white; display: <?php echo e($currentKtaMode === 'auto_approve' ? 'flex' : 'none'); ?>; align-items: center; justify-content: center; font-size: 0.7rem;">
                  <i class="icon-base ti tabler-check"></i>
                </div>
              </label>
            </div>
          </div>

          <style>
            .kta-radio-card:hover {
              border-color: rgba(99,102,241,0.35) !important;
              background: rgba(99,102,241,0.05) !important;
            }
            .kta-radio-card.active {
              border-color: rgba(16,185,129,0.4) !important;
              background: rgba(16,185,129,0.06) !important;
            }
          </style>
          <script>
            document.querySelectorAll('.kta-radio-card input[type="radio"]').forEach(function(radio) {
              radio.addEventListener('change', function() {
                document.querySelectorAll('.kta-radio-card .kta-check-icon').forEach(function(icon) {
                  icon.style.display = 'none';
                });
                var checkIcon = this.closest('.kta-radio-card').querySelector('.kta-check-icon');
                if (checkIcon) checkIcon.style.display = 'flex';
              });
            });
          </script>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-clock-pause me-2"></i>Pencegahan Pendaftaran Ganda
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur jeda waktu sebelum peserta dapat mendaftar ulang setelah pendaftaran sebelumnya ditolak atau dibatalkan.
          </p>

          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="cooldown_period_days" class="form-label">Jeda Pendaftaran Ulang (Hari) <span class="text-danger">*</span></label>
              <input type="number" min="0" step="1"
                class="form-control <?php $__errorArgs = ['cooldown_period_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="cooldown_period_days" name="cooldown_period_days"
                value="<?php echo e(old('cooldown_period_days', $settings['cooldown_period_days']->value ?? 30)); ?>"
                placeholder="30" required>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cooldown_period_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Masukkan angka bulat &ge; 0. Nilai 0 berarti peserta dapat langsung mendaftar ulang tanpa jeda.
              </small>
            </div>
            <div class="col-md-6">
              <label for="cooldown_period_passed_days" class="form-label">Jeda Pendaftaran Ulang Setelah Lulus (Hari) <span class="text-danger">*</span></label>
              <input type="number" min="0" step="1"
                class="form-control <?php $__errorArgs = ['cooldown_period_passed_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="cooldown_period_passed_days" name="cooldown_period_passed_days"
                value="<?php echo e(old('cooldown_period_passed_days', ($settings['cooldown_period_passed_days'] ?? null)?->value ?? 0)); ?>"
                placeholder="365" required>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cooldown_period_passed_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-info-circle me-1"></i>Jumlah hari jeda sebelum alumni dapat mendaftar kembali pada pelatihan yang sama di dinas yang sama (contoh: 365 untuk 1 tahun).
              </small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-lock me-2"></i>Penguncian Wilayah
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Pengaturan ini akan mengunci kota dan provinsi pada form pendaftaran peserta.
          </p>

          <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
              <label for="lock_kota" class="form-label">Kota/Kabupaten</label>
              <input type="text" class="form-control <?php $__errorArgs = ['lock_kota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="lock_kota" name="lock_kota"
                value="<?php echo e(old('lock_kota', $settings['lock_kota']->value ?? 'BANDUNG')); ?>"
                placeholder="BANDUNG">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-lock me-1"></i>Pendaftar akan terkunci ke kota ini
              </small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lock_kota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="col-md-6">
              <label for="lock_provinsi" class="form-label">Provinsi</label>
              <input type="text" class="form-control <?php $__errorArgs = ['lock_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="lock_provinsi" name="lock_provinsi"
                value="<?php echo e(old('lock_provinsi', $settings['lock_provinsi']->value ?? 'Jawa Barat')); ?>"
                placeholder="Jawa Barat">
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">
                <i class="icon-base ti tabler-lock me-1"></i>Pendaftar akan terkunci ke provinsi ini
              </small>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['lock_provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <div x-data="waManager()" x-init="init()" class="mb-4">
            <h5 class="fw-bold text-white mb-4" style="font-family: 'Sora', sans-serif;">
              <i class="icon-base ti tabler-brand-whatsapp me-2"></i>WhatsApp Support
            </h5>
            <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
              Kelola nomor WhatsApp yang muncul di floating icon halaman publik.
            </p>

            <button type="button"
                    class="btn btn-sm mb-3 d-inline-flex align-items-center gap-2"
                    style="background: linear-gradient(135deg, #6366f1, #d946ef); border: none; color: white; border-radius: 5px; padding: 8px 20px; font-family: 'Sora', sans-serif; font-weight: 600; box-shadow: 0 4px 12px rgba(99,102,241,0.3);"
                    @click="openModal()">
              <i class="icon-base ti tabler-plus"></i> Tambah Nomor
            </button>

            <div x-show="numbers.length > 0">
              <table class="table table-borderless text-white align-middle">
                <thead>
                  <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                    <th class="text-body-premium small fw-semibold" style="width:40px">#</th>
                    <th class="text-body-premium small fw-semibold">Label</th>
                    <th class="text-body-premium small fw-semibold">Nomor</th>
                    <th class="text-body-premium small fw-semibold" style="width:90px">Status</th>
                    <th class="text-body-premium small fw-semibold text-end" style="width:160px">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <template x-for="(item, index) in numbers" :key="item.id">
                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                      <td class="text-muted" x-text="index + 1"></td>
                      <td x-text="item.label"></td>
                      <td>
                        <span x-text="item.number.substring(0, 4) + '*****' + item.number.slice(-2)"></span>
                        <small class="text-body-premium d-block" style="font-size:0.75rem;">
                          <a :href="'https://wa.me/' + item.number" target="_blank" class="text-info small">
                            wa.me/<span x-text="item.number"></span>
                          </a>
                        </small>
                      </td>
                      <td>
                        <span class="badge-premium" :class="item.is_active ? 'badge-premium-success' : 'badge-premium-warning'"
                              @click="toggleActive(item.id, index)" style="cursor: pointer;">
                          <span x-text="item.is_active ? 'Aktif' : 'Nonaktif'"></span>
                        </span>
                      </td>
                      <td>
                        <div class="d-flex gap-1 justify-content-end">
                          <button class="btn btn-sm btn-action d-flex align-items-center justify-content-center"
                                  style="width: 30px; height: 30px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); border-radius: 5px;"
                                  @click="moveUp(index)" :disabled="index === 0" title="Naik">
                            <i class="icon-base ti tabler-chevron-up fs-6"></i>
                          </button>
                          <button class="btn btn-sm btn-action d-flex align-items-center justify-content-center"
                                  style="width: 30px; height: 30px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); border-radius: 5px;"
                                  @click="moveDown(index)" :disabled="index === numbers.length - 1" title="Turun">
                            <i class="icon-base ti tabler-chevron-down fs-6"></i>
                          </button>
                          <button class="btn btn-sm btn-action d-flex align-items-center justify-content-center"
                                  style="width: 30px; height: 30px; background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); color: #818cf8; border-radius: 5px;"
                                  @click="openModal(item)" title="Edit">
                            <i class="icon-base ti tabler-edit fs-6"></i>
                          </button>
                          <button class="btn btn-sm btn-action d-flex align-items-center justify-content-center"
                                  style="width: 30px; height: 30px; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #f87171; border-radius: 5px;"
                                  @click="deleteNumber(item.id, index)" title="Hapus">
                            <i class="icon-base ti tabler-trash fs-6"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>

            <div x-show="numbers.length === 0" class="text-center py-4 text-body-premium">
              <p>Belum ada nomor WhatsApp. Klik "Tambah Nomor" untuk menambahkan.</p>
            </div>

            <input type="hidden" name="wa_order" x-model="orderData">

            
            <div class="wa-modal" x-show="showModal" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @keydown.escape.window="closeModal()">
              <div class="modal-dialog" style="width: 100%; max-width: 440px; margin: 1rem;" @click.outside="closeModal()">
                <div class="modal-content wa-modal-content">
                  <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 16px 24px;">
                    <h5 class="modal-title fw-bold" style="font-family: 'Sora', sans-serif; font-size: 1.05rem; color: #ffffff;" x-text="editing ? 'Edit Nomor WhatsApp' : 'Tambah Nomor WhatsApp'"></h5>
                    <button type="button" class="btn-close btn-close-white" @click="closeModal()" style="opacity: 0.7;"></button>
                  </div>
                  <div class="modal-body" style="padding: 16px 24px;">
                    <div class="mb-4">
                      <label class="form-label text-body-premium small fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.03em;">Label <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" x-model="form.label"
                             maxlength="100" placeholder="Contoh: Pendaftaran"
                             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #ffffff; border-radius: 5px; padding: 10px 14px; font-size: 0.9rem;">
                    </div>
                    <div class="mb-3">
                      <label class="form-label text-body-premium small fw-semibold mb-1" style="font-size: 0.75rem; letter-spacing: 0.03em;">Nomor WhatsApp <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" x-model="form.number"
                             maxlength="15" placeholder="6281234567890"
                             @input="form.number = form.number.replace(/[^0-9]/g, '')"
                             style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12); color: #ffffff; border-radius: 5px; padding: 10px 14px; font-size: 0.9rem;">
                      <small class="text-body-premium d-block mt-1" style="font-size: 0.7rem; line-height: 1.4;">
                        <i class="icon-base ti tabler-info-circle me-1"></i>Format: kode negara + nomor, tanpa + atau spasi. Contoh: 6281234567890
                      </small>
                      <div class="mt-2" x-show="form.number.length >= 10">
                        <span style="background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.25); color: #a5b4fc; border-radius: 5px; padding: 4px 10px; font-size: 0.75rem; display: inline-block;">
                          <i class="icon-base ti tabler-brand-whatsapp me-1"></i>wa.me/<span x-text="form.number"></span>
                        </span>
                      </div>
                    </div>
                    <div x-show="error" 
                         class="d-flex align-items-center gap-2 px-3 py-2 small" 
                         style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; border-radius: 5px; font-size: 0.8rem;"
                         x-text="error">
                    </div>
                  </div>
                  <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 12px 24px; display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="button" 
                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #ffffff; border-radius: 5px; padding: 8px 20px; font-family: 'Sora', sans-serif; font-weight: 600; font-size: 0.85rem; transition: all 0.3s ease;"
                            @click="closeModal()"
                            onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                      Batal
                    </button>
                    <button type="button"
                            style="background: linear-gradient(135deg, #6366f1, #d946ef); border: none; color: #ffffff; border-radius: 5px; padding: 8px 24px; font-family: 'Sora', sans-serif; font-weight: 600; font-size: 0.85rem; box-shadow: 0 4px 12px rgba(99,102,241,0.3); transition: all 0.3s ease;"
                            @click="saveNumber()" :disabled="saving"
                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(99,102,241,0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(99,102,241,0.3)'">
                      <span x-show="!saving" x-text="editing ? 'Update' : 'Simpan'"></span>
                      <span x-show="saving" style="display: inline-flex; align-items: center; gap: 4px;">
                        <span class="spinner-border spinner-border-sm" style="width: 14px; height: 14px;"></span> Menyimpan...
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Pengaturan
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
<style>
  [x-cloak] { display: none !important; }
  .wa-modal-content {
    background: rgba(15, 23, 42, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 5px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 40px rgba(99, 102, 241, 0.1);
    width: 100%;
  }
  .wa-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1055;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
  }
</style>
<script>
  function waManager() {
    return {
      numbers: <?php echo json_encode($whatsappNumbers ?? [], 15, 512) ?>,
      showModal: false,
      editing: false,
      saving: false,
      error: '',
      form: { label: '', number: '' },
      editId: null,
      orderData: '',

      init() {
        this.updateOrderData();
      },

      openModal(item = null) {
        this.error = '';
        if (item) {
          this.editing = true;
          this.editId = item.id;
          this.form = { label: item.label, number: item.number };
        } else {
          this.editing = false;
          this.editId = null;
          this.form = { label: '', number: '' };
        }
        this.showModal = true;
      },

      closeModal() {
        this.showModal = false;
        this.error = '';
        this.form = { label: '', number: '' };
        this.editId = null;
        this.editing = false;
      },

      async saveNumber() {
        this.error = '';
        if (!this.form.label.trim()) {
          this.error = 'Label wajib diisi';
          return;
        }
        if (!this.form.number.trim()) {
          this.error = 'Nomor WhatsApp wajib diisi';
          return;
        }
        if (!/^[0-9]+$/.test(this.form.number)) {
          this.error = 'Nomor hanya boleh berisi angka';
          return;
        }
        if (this.form.number.length < 10 || this.form.number.length > 15) {
          this.error = 'Nomor minimal 10 digit dan maksimal 15 digit';
          return;
        }
        this.saving = true;
        try {
          let url = '/admin/whatsapp-numbers';
          let method = 'POST';
          if (this.editing) {
            url = `/admin/whatsapp-numbers/${this.editId}`;
            method = 'PUT';
          }
          const response = await fetch(url, {
            method: method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
              'Accept': 'application/json',
            },
            body: JSON.stringify(this.form),
          });
          const data = await response.json();
          if (!response.ok) {
            if (data.errors) {
              this.error = Object.values(data.errors).flat().join('\n');
            } else {
              this.error = data.message || 'Terjadi kesalahan';
            }
            return;
          }
          await this.refreshNumbers();
          this.closeModal();
        } catch (e) {
          this.error = 'Terjadi kesalahan jaringan';
        } finally {
          this.saving = false;
        }
      },

      async deleteNumber(id, index) {
        const result = await Swal.fire({
          title: 'Hapus Nomor WhatsApp?',
          text: `Nomor "${this.numbers[index].label}" akan dihapus permanen.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          confirmButtonColor: '#ef4444',
          cancelButtonColor: '#6b7280',
        });
        if (!result.isConfirmed) return;
        try {
          const response = await fetch(`/admin/whatsapp-numbers/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
              'Accept': 'application/json',
            },
          });
          if (response.ok) {
            this.numbers.splice(index, 1);
            this.updateOrderData();
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menghapus nomor', confirmButtonColor: '#6366f1' });
        }
      },

      async toggleActive(id, index) {
        try {
          const response = await fetch(`/admin/whatsapp-numbers/${id}/toggle-active`, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
              'Accept': 'application/json',
            },
          });
          if (response.ok) {
            const data = await response.json();
            this.numbers[index].is_active = data.is_active;
          }
        } catch (e) {
          Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal mengubah status', confirmButtonColor: '#6366f1' });
        }
      },

      moveUp(index) {
        if (index === 0) return;
        [this.numbers[index], this.numbers[index - 1]] = [this.numbers[index - 1], this.numbers[index]];
        this.updateOrderData();
        this.saveOrder();
      },

      moveDown(index) {
        if (index === this.numbers.length - 1) return;
        [this.numbers[index], this.numbers[index + 1]] = [this.numbers[index + 1], this.numbers[index]];
        this.updateOrderData();
        this.saveOrder();
      },

      updateOrderData() {
        this.orderData = JSON.stringify(this.numbers.map(n => n.id));
      },

      async saveOrder() {
        try {
          await fetch('/admin/whatsapp-numbers/reorder', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
              'Accept': 'application/json',
            },
            body: JSON.stringify({ ids: this.numbers.map(n => n.id) }),
          });
        } catch (e) {
          console.error('Gagal menyimpan urutan');
        }
      },

      async refreshNumbers() {
        try {
          const response = await fetch('/admin/whatsapp-numbers', {
            headers: { 'Accept': 'application/json' }
          });
          if (response.ok) {
            this.numbers = await response.json();
            this.updateOrderData();
          }
        } catch (e) {
          console.error('Gagal refresh data');
        }
      }
    }
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/branding/index.blade.php ENDPATH**/ ?>
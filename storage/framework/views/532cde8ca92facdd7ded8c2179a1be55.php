<?php
$configData = Helper::appClasses();
?>



<?php $__env->startSection('title', 'Konten Halaman Publik'); ?>

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

  .layout-menu { background-color: #0b0f19 !important; border-right: 1px solid rgba(255, 255, 255, 0.08) !important; }
  .layout-menu .app-brand { background-color: #0b0f19 !important; }
  .layout-menu .menu-inner { background-color: #0b0f19 !important; }
  .layout-menu .menu-link { color: rgba(255, 255, 255, 0.7) !important; }
  .layout-menu .menu-item.active > .menu-link { color: #ffffff !important; background: linear-gradient(135deg, #6366f1, #d946ef) !important; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important; }
  .layout-menu .menu-item.active > .menu-link i { color: #ffffff !important; }
  .layout-menu .menu-header-text { color: rgba(255, 255, 255, 0.4) !important; }
  .layout-menu .menu-link:hover { background-color: rgba(255, 255, 255, 0.04) !important; color: #ffffff !important; }
  .layout-menu .menu-inner-shadow { background: linear-gradient(#0b0f19 5%, rgba(11, 15, 25, 0) 95%) !important; }
  .layout-menu .app-brand .app-brand-text { color: #ffffff !important; }

  .layout-navbar, #layout-navbar {
    background: rgba(15, 23, 42, 0.45) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.08) !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
  }
  .navbar-detached { background: rgba(15, 23, 42, 0.45) !important; border: 1px solid rgba(255, 255, 255, 0.08) !important; margin-top: 12px !important; }
  #layout-navbar .nav-link { color: rgba(255, 255, 255, 0.7) !important; }
  #layout-navbar .nav-link:hover { color: #ffffff !important; }

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
  .glass-card-premium:hover { transform: translateY(-2px) !important; border-color: rgba(99, 102, 241, 0.2) !important; }

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
  .form-control::placeholder, textarea::placeholder { color: rgba(255, 255, 255, 0.35) !important; }
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
  .btn-secondary-custom:hover { background: rgba(255, 255, 255, 0.1); color: #ffffff; }

  .text-body-premium { color: rgba(255, 255, 255, 0.65) !important; }

  .invalid-feedback {
    color: #f87171 !important;
    font-size: 0.75rem !important;
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
            <i class="icon-base ti tabler-article fs-4"></i>
          </div>
          <div>
            <h4 class="fw-bold text-white mb-0">Konten Halaman Publik</h4>
            <p class="text-body-premium mb-0 mt-1" style="font-size: 0.95rem;">
              Kelola semua teks dan redaksi yang tampil di halaman beranda publik
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

      <div class="glass-card-premium px-4 px-xl-5 py-5">
        <form action="<?php echo e(route('admin.settings.landing.update')); ?>" method="POST">
          <?php echo csrf_field(); ?>

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-home me-2 text-warning"></i>Hero / Header Utama
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Kelola teks utama, tag kategori, dan statistik yang tampil di hero section halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_title" class="form-label">Judul Utama (kiri)</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_title" name="hero_title" value="<?php echo e(old('hero_title', $settings['hero_title']->value ?? 'Pendaftaran')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_title'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_subtitle" class="form-label">Subjudul (kanan, warna kuning)</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_subtitle" name="hero_subtitle" value="<?php echo e(old('hero_subtitle', $settings['hero_subtitle']->value ?? 'Pelatihan Ekonomi Kreatif')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_subtitle'];
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
              <label for="hero_description" class="form-label">Deskripsi Hero</label>
              <textarea class="form-control <?php $__errorArgs = ['hero_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_description" name="hero_description" rows="3"><?php echo e(old('hero_description', $settings['hero_description']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_description'];
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

          <h6 class="fw-semibold text-white mb-3">Tag Kategori</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_icon" class="form-label">Tag 1 - Icon</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_1_icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_1_icon" name="hero_tag_1_icon" value="<?php echo e(old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat')); ?>" placeholder="chef-hat">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_1_icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Nama icon Tabler (<a href="https://tabler-icons.io" target="_blank" class="text-warning">lihat daftar</a>)</small>
            </div>
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_text" class="form-label">Tag 1 - Teks</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_1_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_1_text" name="hero_tag_1_text" value="<?php echo e(old('hero_tag_1_text', $settings['hero_tag_1_text']->value ?? 'Kuliner Kreatif')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_1_text'];
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
            <div class="col-md-4 mb-3">
              <label for="hero_tag_1_preview" class="form-label">Icon Tabler (full class)</label>
              <input type="text" class="form-control" id="hero_tag_1_preview" value="tabler-<?php echo e(old('hero_tag_1_icon', $settings['hero_tag_1_icon']->value ?? 'chef-hat')); ?>" disabled>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Pratinjau otomatis dari field icon di atas</small>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_tag_2_icon" class="form-label">Tag 2 - Icon</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_2_icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_2_icon" name="hero_tag_2_icon" value="<?php echo e(old('hero_tag_2_icon', $settings['hero_tag_2_icon']->value ?? 'camera')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_2_icon'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_tag_2_text" class="form-label">Tag 2 - Teks</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_2_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_2_text" name="hero_tag_2_text" value="<?php echo e(old('hero_tag_2_text', $settings['hero_tag_2_text']->value ?? 'Konten Kreator')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_2_text'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_tag_3_icon" class="form-label">Tag 3 - Icon</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_3_icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_3_icon" name="hero_tag_3_icon" value="<?php echo e(old('hero_tag_3_icon', $settings['hero_tag_3_icon']->value ?? 'palette')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_3_icon'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_tag_3_text" class="form-label">Tag 3 - Teks</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_tag_3_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_tag_3_text" name="hero_tag_3_text" value="<?php echo e(old('hero_tag_3_text', $settings['hero_tag_3_text']->value ?? 'Desain Grafis')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_tag_3_text'];
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

          <h6 class="fw-semibold text-white mb-3">Statistik Hero</h6>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="hero_stat_1_value" class="form-label">Stat 1 - Angka</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_1_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_1_value" name="hero_stat_1_value" value="<?php echo e(old('hero_stat_1_value', $settings['hero_stat_1_value']->value ?? '4+')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_1_value'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_stat_1_label" class="form-label">Stat 1 - Label</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_1_label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_1_label" name="hero_stat_1_label" value="<?php echo e(old('hero_stat_1_label', $settings['hero_stat_1_label']->value ?? 'Bidang Kreatif')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_1_label'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_stat_2_value" class="form-label">Stat 2 - Angka</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_2_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_2_value" name="hero_stat_2_value" value="<?php echo e(old('hero_stat_2_value', $settings['hero_stat_2_value']->value ?? 'Gratis')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_2_value'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_stat_2_label" class="form-label">Stat 2 - Label</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_2_label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_2_label" name="hero_stat_2_label" value="<?php echo e(old('hero_stat_2_label', $settings['hero_stat_2_label']->value ?? 'Tanpa Biaya')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_2_label'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_stat_3_value" class="form-label">Stat 3 - Angka</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_3_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_3_value" name="hero_stat_3_value" value="<?php echo e(old('hero_stat_3_value', $settings['hero_stat_3_value']->value ?? '2026')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_3_value'];
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
            <div class="col-md-6 mb-3">
              <label for="hero_stat_3_label" class="form-label">Stat 3 - Label</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_stat_3_label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_stat_3_label" name="hero_stat_3_label" value="<?php echo e(old('hero_stat_3_label', $settings['hero_stat_3_label']->value ?? 'Tahun Akademik')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_stat_3_label'];
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
              <label for="hero_scroll_text" class="form-label">Teks Scroll (bawah)</label>
              <input type="text" class="form-control <?php $__errorArgs = ['hero_scroll_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="hero_scroll_text" name="hero_scroll_text" value="<?php echo e(old('hero_scroll_text', $settings['hero_scroll_text']->value ?? 'Scroll ke bawah untuk informasi lanjut')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['hero_scroll_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks yang muncul di bagian bawah hero sebagai ajakan scroll</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-file-text me-2 text-success"></i>Form Pendaftaran
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada kartu form pendaftaran di halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_title" class="form-label">Judul Kartu</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_title" name="form_title" value="<?php echo e(old('form_title', $settings['form_title']->value ?? 'Daftar Sekarang')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_title'];
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
            <div class="col-md-6 mb-3">
              <label for="form_password_info" class="form-label">Info Password</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_password_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_password_info" name="form_password_info" value="<?php echo e(old('form_password_info', $settings['form_password_info']->value ?? 'Password akun akan diisi otomatis')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_password_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Informasi yang ditampilkan di samping field password</small>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="form_password_value" class="form-label">Default Password</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_password_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_password_value" name="form_password_value" value="<?php echo e(old('form_password_value', $settings['form_password_value']->value ?? 'pelatihanku2026')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_password_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Password default yang akan diisi otomatis untuk pendaftar</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="form_button_text" class="form-label">Tombol Daftar</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_button_text" name="form_button_text" value="<?php echo e(old('form_button_text', $settings['form_button_text']->value ?? 'Daftar Sekarang')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_button_text'];
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
            <div class="col-md-6 mb-3">
              <label for="form_button_loading" class="form-label">Loading State</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_button_loading'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_button_loading" name="form_button_loading" value="<?php echo e(old('form_button_loading', $settings['form_button_loading']->value ?? 'Memproses Pendaftaran...')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_button_loading'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks yang muncul saat tombol ditekan dan sedang memproses</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="form_login_text" class="form-label">Teks "Sudah memiliki akun?"</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_login_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_login_text" name="form_login_text" value="<?php echo e(old('form_login_text', $settings['form_login_text']->value ?? 'Sudah memiliki akun?')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_login_text'];
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
            <div class="col-md-6 mb-3">
              <label for="form_login_link" class="form-label">Teks Link Login</label>
              <input type="text" class="form-control <?php $__errorArgs = ['form_login_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="form_login_link" name="form_login_link" value="<?php echo e(old('form_login_link', $settings['form_login_link']->value ?? 'Login di sini')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form_login_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Teks link yang dapat diklik untuk menuju halaman login</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-steps me-2 text-info"></i>Alur Pendaftaran (3 Langkah)
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur judul dan deskripsi untuk setiap langkah dalam alur pendaftaran.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_badge" class="form-label">Badge</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_badge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_badge" name="steps_badge" value="<?php echo e(old('steps_badge', $settings['steps_badge']->value ?? 'Alur Pendaftaran')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_badge'];
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
            <div class="col-md-4 mb-3">
              <label for="steps_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_title" name="steps_title" value="<?php echo e(old('steps_title', $settings['steps_title']->value ?? 'Ikuti 3 Langkah Mudah')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_title'];
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
            <div class="col-md-4 mb-3">
              <label for="steps_subtitle" class="form-label">Subjudul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_subtitle" name="steps_subtitle" value="<?php echo e(old('steps_subtitle', $settings['steps_subtitle']->value ?? '')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_subtitle'];
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

          <h6 class="fw-semibold text-white mt-4 mb-3">Langkah 1</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_1_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_1_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_1_title" name="steps_1_title" value="<?php echo e(old('steps_1_title', $settings['steps_1_title']->value ?? 'Daftarkan Akun')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_1_title'];
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
            <div class="col-md-8 mb-3">
              <label for="steps_1_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control <?php $__errorArgs = ['steps_1_desc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_1_desc" name="steps_1_desc" rows="2"><?php echo e(old('steps_1_desc', $settings['steps_1_desc']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_1_desc'];
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

          <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 2</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_2_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_2_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_2_title" name="steps_2_title" value="<?php echo e(old('steps_2_title', $settings['steps_2_title']->value ?? 'Ikuti Kelas Pelatihan')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_2_title'];
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
            <div class="col-md-8 mb-3">
              <label for="steps_2_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control <?php $__errorArgs = ['steps_2_desc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_2_desc" name="steps_2_desc" rows="2"><?php echo e(old('steps_2_desc', $settings['steps_2_desc']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_2_desc'];
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

          <h6 class="fw-semibold text-white mt-3 mb-3">Langkah 3</h6>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="steps_3_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['steps_3_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_3_title" name="steps_3_title" value="<?php echo e(old('steps_3_title', $settings['steps_3_title']->value ?? 'Raih Hasil & Sertifikat')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_3_title'];
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
            <div class="col-md-8 mb-3">
              <label for="steps_3_desc" class="form-label">Deskripsi</label>
              <textarea class="form-control <?php $__errorArgs = ['steps_3_desc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="steps_3_desc" name="steps_3_desc" rows="2"><?php echo e(old('steps_3_desc', $settings['steps_3_desc']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['steps_3_desc'];
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

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-backpack me-2 text-warning"></i>Section Pelatihan
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section daftar pelatihan yang tersedia.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="pelatihan_badge" class="form-label">Badge</label>
              <input type="text" class="form-control <?php $__errorArgs = ['pelatihan_badge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pelatihan_badge" name="pelatihan_badge" value="<?php echo e(old('pelatihan_badge', $settings['pelatihan_badge']->value ?? 'Program Unggulan')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pelatihan_badge'];
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
            <div class="col-md-4 mb-3">
              <label for="pelatihan_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['pelatihan_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pelatihan_title" name="pelatihan_title" value="<?php echo e(old('pelatihan_title', $settings['pelatihan_title']->value ?? 'Pelatihan yang Tersedia')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pelatihan_title'];
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
            <div class="col-md-4 mb-3">
              <label for="pelatihan_subtitle" class="form-label">Subjudul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['pelatihan_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pelatihan_subtitle" name="pelatihan_subtitle" value="<?php echo e(old('pelatihan_subtitle', $settings['pelatihan_subtitle']->value ?? '')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pelatihan_subtitle'];
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
            <div class="col-md-6 mb-3">
              <label for="pelatihan_empty_title" class="form-label">Kosong - Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['pelatihan_empty_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pelatihan_empty_title" name="pelatihan_empty_title" value="<?php echo e(old('pelatihan_empty_title', $settings['pelatihan_empty_title']->value ?? 'Belum Ada Pelatihan Aktif')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pelatihan_empty_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Judul yang tampil ketika belum ada pelatihan aktif</small>
            </div>
            <div class="col-md-6 mb-3">
              <label for="pelatihan_empty_desc" class="form-label">Kosong - Deskripsi</label>
              <input type="text" class="form-control <?php $__errorArgs = ['pelatihan_empty_desc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="pelatihan_empty_desc" name="pelatihan_empty_desc" value="<?php echo e(old('pelatihan_empty_desc', $settings['pelatihan_empty_desc']->value ?? '')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pelatihan_empty_desc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback mt-1"><?php echo e($message); ?></div>
              <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
              <small class="text-body-premium mt-1 d-block" style="font-size: 0.75rem;">Deskripsi yang tampil ketika belum ada pelatihan aktif</small>
            </div>
          </div>

          <hr class="my-5" style="border-color: rgba(255,255,255,0.08);">

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-question-mark me-2 text-primary"></i>Section "Mengapa"
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section alasan memilih pelatihan.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="why_badge" class="form-label">Badge</label>
              <input type="text" class="form-control <?php $__errorArgs = ['why_badge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="why_badge" name="why_badge" value="<?php echo e(old('why_badge', $settings['why_badge']->value ?? 'Mengapa Memilih')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['why_badge'];
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
            <div class="col-md-4 mb-3">
              <label for="why_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['why_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="why_title" name="why_title" value="<?php echo e(old('why_title', $settings['why_title']->value ?? 'Mengapa Memilih Pelatihan Kami?')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['why_title'];
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
            <div class="col-md-4 mb-3">
              <label for="why_subtitle" class="form-label">Subjudul</label>
              <textarea class="form-control <?php $__errorArgs = ['why_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="why_subtitle" name="why_subtitle" rows="2"><?php echo e(old('why_subtitle', $settings['why_subtitle']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['why_subtitle'];
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

          
          <h5 class="fw-bold text-white mb-3" style="font-family: 'Sora', sans-serif;">
            <i class="icon-base ti tabler-bolt me-2 text-danger"></i>CTA / Call-to-Action
          </h5>
          <p class="text-body-premium mb-4" style="font-size: 0.85rem;">
            Atur teks pada section ajakan bertindak di bagian bawah halaman beranda.
          </p>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label for="cta_badge" class="form-label">Badge</label>
              <input type="text" class="form-control <?php $__errorArgs = ['cta_badge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cta_badge" name="cta_badge" value="<?php echo e(old('cta_badge', $settings['cta_badge']->value ?? 'SEGERA BERGABUNG')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cta_badge'];
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
            <div class="col-md-4 mb-3">
              <label for="cta_title" class="form-label">Judul</label>
              <input type="text" class="form-control <?php $__errorArgs = ['cta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cta_title" name="cta_title" value="<?php echo e(old('cta_title', $settings['cta_title']->value ?? 'Siap Memulai Perjalanan Anda?')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cta_title'];
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
            <div class="col-md-4 mb-3">
              <label for="cta_subtitle" class="form-label">Subjudul</label>
              <textarea class="form-control <?php $__errorArgs = ['cta_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cta_subtitle" name="cta_subtitle" rows="2"><?php echo e(old('cta_subtitle', $settings['cta_subtitle']->value ?? '')); ?></textarea>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cta_subtitle'];
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
            <div class="col-md-6 mb-3">
              <label for="cta_button_text" class="form-label">Tombol Daftar</label>
              <input type="text" class="form-control <?php $__errorArgs = ['cta_button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cta_button_text" name="cta_button_text" value="<?php echo e(old('cta_button_text', $settings['cta_button_text']->value ?? 'Daftar Sekarang — Gratis!')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cta_button_text'];
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
            <div class="col-md-6 mb-3">
              <label for="cta_login_text" class="form-label">Tombol Login</label>
              <input type="text" class="form-control <?php $__errorArgs = ['cta_login_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="cta_login_text" name="cta_login_text" value="<?php echo e(old('cta_login_text', $settings['cta_login_text']->value ?? 'Sudah Punya Akun? Login')); ?>">
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['cta_login_text'];
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

          
          <div class="d-flex justify-content-between align-items-center gap-3 mt-5">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary-custom px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-glow-premium px-4 py-2 d-flex align-items-center gap-2">
              <i class="icon-base ti tabler-device-floppy"></i> Simpan Semua Perubahan
            </button>
          </div>
        </form>
    </div>

  </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/content/admin/branding/landing.blade.php ENDPATH**/ ?>
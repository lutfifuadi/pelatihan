<?php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
?>



<?php $__env->startSection('title', 'Admin Login'); ?>

<?php $__env->startSection('page-style'); ?>
<style>
  /* --- Premium Admin Login --- */
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  #admin-auth-wrapper {
    font-family: 'Inter', sans-serif;
    background-color: #070b14;
    color: #e2e8f0;
    overflow: hidden;
    height: 100dvh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }
  #admin-auth-wrapper h1,
  #admin-auth-wrapper h2,
  #admin-auth-wrapper h3,
  #admin-auth-wrapper h4,
  #admin-auth-wrapper h5,
  #admin-auth-wrapper h6 {
    font-family: 'Plus Jakarta Sans', sans-serif;
  }

  /* --- Premium Mesh Gradient --- */
  .admin-gradient {
    background: #070b14;
    background-image:
      radial-gradient(at 85% 15%, rgba(56, 189, 248, 0.12) 0px, transparent 50%),
      radial-gradient(at 15% 85%, rgba(99, 102, 241, 0.10) 0px, transparent 50%),
      radial-gradient(at 50% 50%, rgba(30, 41, 59, 0.4) 0px, transparent 50%);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
  }

  /* --- Grid Pattern Overlay --- */
  .admin-grid-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    background-image:
      linear-gradient(rgba(56, 189, 248, 0.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
  }

  /* --- Subtle Glow Orbs --- */
  .admin-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(150px);
    opacity: 0.3;
    mix-blend-mode: screen;
    pointer-events: none;
    z-index: 3;
  }
  .admin-orb-1 {
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, #38bdf8, rgba(56, 189, 248, 0) 70%);
    top: -15%;
    right: -10%;
    animation: adminOrbFloat 22s infinite alternate ease-in-out;
  }
  .admin-orb-2 {
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, #6366f1, rgba(99, 102, 241, 0) 70%);
    bottom: -10%;
    left: -10%;
    animation: adminOrbFloat 28s infinite alternate-reverse ease-in-out;
  }
  @keyframes adminOrbFloat {
    0% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(40px, -30px) scale(1.05); }
    100% { transform: translate(-20px, 40px) scale(0.95); }
  }

  /* --- Dark Glass Card --- */
  .admin-card {
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(56, 189, 248, 0.08);
    box-shadow:
      0 20px 60px rgba(0, 0, 0, 0.5),
      inset 0 1px 0 rgba(255, 255, 255, 0.03);
    border-radius: 5px;
    position: relative;
    width: 100%;
    max-width: 420px;
    z-index: 10;
    padding: 36px 32px;
    transition: box-shadow 0.3s ease;
  }
  .admin-card:focus-within {
    box-shadow:
      0 20px 60px rgba(0, 0, 0, 0.5),
      0 0 40px rgba(56, 189, 248, 0.05);
  }
  @media (max-width: 576px) {
    .admin-card {
      padding: 24px 20px;
      max-width: calc(100% - 24px);
      margin: 0 12px;
    }
  }

  /* --- Admin Shield Logo --- */
  .admin-logo {
    width: 52px;
    height: 52px;
    border-radius: 5px;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border: 1px solid rgba(56, 189, 248, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.1);
    margin: 0 auto 12px auto;
  }
  .admin-brand-text {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #f1f5f9;
    letter-spacing: -0.3px;
  }
  .admin-brand-badge {
    display: inline-block;
    font-size: 0.6rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 2px 10px;
    border-radius: 3px;
    background: rgba(56, 189, 248, 0.1);
    border: 1px solid rgba(56, 189, 248, 0.15);
    color: #38bdf8;
    margin-top: 4px;
  }

  /* --- Form Controls --- */
  .admin-input {
    background: rgba(15, 23, 42, 0.5) !important;
    border: 1px solid rgba(148, 163, 184, 0.15) !important;
    color: #f1f5f9 !important;
    border-radius: 5px !important;
    padding: 11px 14px !important;
    font-size: 14px !important;
    transition: all 0.25s ease !important;
  }
  .admin-input:focus {
    background: rgba(15, 23, 42, 0.7) !important;
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.12), 0 0 20px rgba(56, 189, 248, 0.06) !important;
    color: #f1f5f9 !important;
  }
  .admin-input::placeholder {
    color: rgba(148, 163, 184, 0.4) !important;
  }
  .admin-input.is-invalid {
    border-color: #f87171 !important;
    box-shadow: 0 0 0 4px rgba(248, 113, 113, 0.12) !important;
  }
  .admin-input:-webkit-autofill,
  .admin-input:-webkit-autofill:hover,
  .admin-input:-webkit-autofill:focus,
  .admin-input:-webkit-autofill:active {
    -webkit-text-fill-color: #f1f5f9 !important;
    transition: background-color 5000s ease-in-out 0s;
    background-clip: padding-box !important;
    box-shadow: 0 0 0 1000px #111625 inset !important;
    -webkit-box-shadow: 0 0 0 1000px #111625 inset !important;
  }
  .admin-label {
    font-family: 'Inter', sans-serif !important;
    font-weight: 600 !important;
    font-size: 0.7rem !important;
    letter-spacing: 0.06em !important;
    text-transform: uppercase;
    color: rgba(148, 163, 184, 0.8) !important;
    margin-bottom: 6px;
  }
  .admin-input-group-text {
    background: rgba(15, 23, 42, 0.5) !important;
    border: 1px solid rgba(148, 163, 184, 0.15) !important;
    color: rgba(148, 163, 184, 0.7) !important;
    border-radius: 5px !important;
    border-left: none !important;
    padding: 11px 14px !important;
    transition: all 0.25s ease !important;
  }
  .admin-input-group .admin-input {
    border-right: none !important;
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
  }
  .admin-input-group .admin-input-group-text {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
  }
  .admin-input-group:focus-within .admin-input-group-text {
    border-color: #38bdf8 !important;
  }

  /* --- Admin Button --- */
  .btn-admin {
    position: relative;
    overflow: hidden;
    border: none;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    box-shadow: 0 4px 15px rgba(14, 165, 233, 0.25);
    color: #ffffff !important;
    font-weight: 600;
    padding: 11px 20px;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s ease;
  }
  .btn-admin:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14, 165, 233, 0.35);
    background: linear-gradient(135deg, #38bdf8, #818cf8);
  }
  .btn-admin:active {
    transform: translateY(0);
  }

  /* --- Checkbox --- */
  .admin-check {
    background-color: rgba(15, 23, 42, 0.5) !important;
    border: 1px solid rgba(148, 163, 184, 0.2) !important;
  }
  .admin-check:checked {
    background-color: #0ea5e9 !important;
    border-color: #0ea5e9 !important;
  }

  /* --- Links --- */
  .admin-link {
    color: #38bdf8;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }
  .admin-link:hover {
    color: #7dd3fc;
    text-decoration: underline;
  }

  /* --- Divider --- */
  .admin-divider {
    color: rgba(148, 163, 184, 0.4);
    font-size: 0.75rem;
  }
  .admin-divider::before,
  .admin-divider::after {
    border-top: 1px solid rgba(148, 163, 184, 0.08) !important;
  }

  /* --- Alert --- */
  .admin-alert-success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
    border: 1px solid rgba(16, 185, 129, 0.2);
    color: #6ee7b7;
    border-radius: 5px;
  }
  .admin-alert-error {
    background: linear-gradient(135deg, rgba(248, 113, 113, 0.1), rgba(239, 68, 68, 0.05));
    border: 1px solid rgba(248, 113, 113, 0.15);
    color: #fca5a5;
    border-radius: 5px;
  }

  .text-admin-muted {
    color: rgba(148, 163, 184, 0.5) !important;
  }

  @media (max-width: 576px) {
    .admin-orb-1,
    .admin-orb-2 {
      opacity: 0.15;
    }
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="admin-auth-wrapper">
  <!-- Mesh Gradient Background -->
  <div class="admin-gradient"></div>

  <!-- Grid Overlay -->
  <div class="admin-grid-overlay"></div>

  <!-- Glow Orbs -->
  <div class="admin-orb admin-orb-1"></div>
  <div class="admin-orb admin-orb-2"></div>

  <!-- Admin Login Card -->
  <div class="admin-card">
    <!-- Logo / Branding -->
    <div class="text-center mb-4">
      <div class="admin-logo">
        <i class="icon-base ti tabler-shield-lock text-white fs-3"></i>
      </div>
      <div class="admin-brand-text"><?php if (isset($component)) { $__componentOriginal8741a05e11b0c77d19ec61b6b35b26b3 = $component; } ?>
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
<?php endif; ?></div>
      <div class="admin-brand-badge">Admin Panel</div>
    </div>

    <!-- Title -->
    <div class="text-center mb-4">
      <h5 class="mb-1 text-white fw-bold" style="font-size: 1.15rem;">Selamat Datang, Admin</h5>
      <p class="text-admin-muted small mt-1">Masuk ke dashboard administrasi</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
    <div class="alert admin-alert-success mb-4 border-0 d-flex align-items-center" role="alert">
      <i class="icon-base ti tabler-check-circle fs-5 me-2"></i>
      <span class="small fw-semibold"><?php echo e(session('status')); ?></span>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <div class="alert admin-alert-error mb-4 border-0" role="alert">
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="d-flex align-items-center">
        <i class="icon-base ti tabler-alert-circle fs-5 me-2"></i>
        <span class="small"><?php echo e($error); ?></span>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form id="adminLoginForm" action="<?php echo e(route('admin.login')); ?>" method="POST">
      <?php echo csrf_field(); ?>

      <!-- Email -->
      <div class="mb-3">
        <label for="admin-email" class="form-label admin-label">Email Admin</label>
        <input type="email" class="form-control admin-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
          id="admin-email" name="email" placeholder="<?php echo e(\App\Models\Setting::where('key', 'institution_email')->value('value') ?? 'admin@sabakreatif.com'); ?>"
          autofocus autocomplete="email" value="<?php echo e(old('email')); ?>" required />
      </div>

      <!-- Password -->
      <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between mb-1">
          <label class="form-label admin-label" for="admin-password">Password</label>
        </div>
        <div class="input-group admin-input-group">
          <input type="password" id="admin-password" class="form-control admin-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password" required />
          <span class="input-group-text admin-input-group-text cursor-pointer">
            <i class="icon-base ti tabler-eye-off"></i>
          </span>
        </div>
      </div>

      <!-- Remember & Forgot -->
      <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check mb-0">
            <input class="form-check-input admin-check" type="checkbox" id="admin-remember" name="remember"
              <?php echo e(old('remember') ? 'checked' : ''); ?> />
            <label class="form-check-label text-admin-muted small" for="admin-remember">
              Ingat Saya
            </label>
          </div>
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('admin.password.request')): ?>
          <a href="<?php echo e(route('admin.password.request')); ?>" class="admin-link small">
            Lupa Password?
          </a>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>

      <!-- Submit -->
      <button class="btn btn-admin w-100" type="submit">
        <i class="icon-base ti tabler-login me-2"></i>Masuk ke Admin
      </button>
    </form>

    <!-- Divider -->
    <div class="d-flex align-items-center gap-3 my-4">
      <hr class="flex-grow-1" style="border-color: rgba(148,163,184,0.08); margin: 0;">
      <span class="admin-divider">kembali</span>
      <hr class="flex-grow-1" style="border-color: rgba(148,163,184,0.08); margin: 0;">
    </div>

    <!-- Back to main site -->
    <p class="text-center mb-0">
      <span class="text-admin-muted small">Bukan admin? </span>
      <a href="<?php echo e(url('/login')); ?>" class="admin-link small fw-semibold">
        Login untuk Peserta / Instruktur
      </a>
    </p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/blankLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>
<?php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
?>



<?php $__env->startSection('title', 'Forgot Password'); ?>

<?php $__env->startSection('page-style'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/scss/pages/page-auth.scss']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="authentication-wrapper authentication-cover">
  <!-- Logo -->
  <a href="<?php echo e(url('/')); ?>" class="app-brand auth-cover-brand">
    <span class="app-brand-logo demo"><?php echo $__env->make('_partials.macros', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
    <span class="app-brand-text demo text-heading fw-bold"><?php echo e(config('variables.templateName')); ?></span>
  </a>
  <!-- /Logo -->
  <div class="authentication-inner row m-0">
    <!-- /Left Text -->
    <div class="d-none d-xl-flex col-xl-8 p-0">
      <div class="auth-cover-bg d-flex justify-content-center align-items-center">
        <img
          src="<?php echo e(asset('assets/img/illustrations/auth-forgot-password-illustration-' . $configData['theme'] . '.png')); ?>"
          alt="auth-forgot-password-cover" class="my-5 auth-illustration d-lg-block d-none"
          data-app-light-img="illustrations/auth-forgot-password-illustration-light.png"
          data-app-dark-img="illustrations/auth-forgot-password-illustration-dark.png" />
        <img src="<?php echo e(asset('assets/img/illustrations/bg-shape-image-' . $configData['theme'] . '.png')); ?>"
          alt="auth-forgot-password-cover" class="platform-bg"
          data-app-light-img="illustrations/bg-shape-image-light.png"
          data-app-dark-img="illustrations/bg-shape-image-dark.png" />
      </div>
    </div>
    <!-- /Left Text -->

    <!-- Forgot Password -->
    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">
      <div class="w-px-400 mx-auto mt-12 mt-5">
        <h4 class="mb-1">Forgot Password? 🔒</h4>
        <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
        <div class="mb-1 text-success">
          <?php echo e(session('status')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <form id="formAuthentication" class="mb-6" action="<?php echo e(route('password.email')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <div class="mb-6">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email"
              placeholder="john@example.com" autofocus />
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <span class="invalid-feedback" role="alert">
              <span class="fw-medium"><?php echo e($message); ?></span>
            </span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
        </form>
        <div class="text-center">
          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
          <a href="<?php echo e(route('login')); ?>" class="d-flex justify-content-center">
            <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl me-1_5"></i>
            Back to login
          </a>
          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
      </div>
    </div>
    <!-- /Forgot Password -->
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts/blankLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>
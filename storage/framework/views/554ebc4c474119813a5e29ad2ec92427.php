<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
?>

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($navbarFull)): ?>
<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4 ms-0">
  <a href="<?php echo e(url('/')); ?>" class="app-brand-link">
    <span class="app-brand-logo demo"><?php echo $__env->make('_partials.macros', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
    <span class="app-brand-text demo menu-text fw-bold"><?php echo e(config('variables.templateName')); ?></span>
  </a>

  <!-- Display menu close icon only for horizontal-menu with navbar-full -->
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($menuHorizontal)): ?>
  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
    <i class="icon-base ti tabler-x icon-sm d-flex align-items-center justify-content-center"></i>
  </a>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- ! Not required for layout-without-menu -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($navbarHideToggle)): ?>
<div
  class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0<?php echo e(isset($menuHorizontal) ? ' d-xl-none ' : ''); ?> <?php echo e(isset($contentNavbar) ? ' d-xl-none ' : ''); ?>">
  <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
    <i class="icon-base ti tabler-menu-2 icon-md"></i>
  </a>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php
    $maintenanceActive = \Illuminate\Support\Facades\Cache::remember('setting.maintenance_mode', 60, function () {
        return \App\Models\Setting::where('key', 'maintenance_mode')->value('value');
    });
?>

<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navbar-component');

$__key = null;

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3757002100-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key);

echo $__html;

unset($__html);
unset($__key);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($maintenanceActive === '1'): ?>
  <div class="maintenance-indicator d-flex align-items-center gap-2 px-3 py-1 mx-2 rounded-pill" style="background: rgba(255, 193, 7, 0.12); border: 1px solid rgba(255, 193, 7, 0.2);">
    <i class="icon-base ti tabler-tool text-warning" style="font-size: 16px;"></i>
    <span class="text-warning fw-semibold" style="font-size: 0.75rem; font-family: 'Sora', sans-serif;">Mode Maintenance Aktif</span>
  </div>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($configData['hasCustomizer'] == true): ?>
  <!-- Style Switcher -->
  <div class="navbar-nav align-items-center">
    <li class="nav-item dropdown me-2 me-xl-0">
      <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);"
        data-bs-toggle="dropdown">
        <i class="icon-base ti tabler-sun icon-md theme-icon-active"></i>
        <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-start" aria-labelledby="nav-theme-text">
        <li>
          <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light"
            aria-pressed="false">
            <span><i class="icon-base ti tabler-sun icon-22px me-3" data-icon="sun"></i>Light</span>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="true">
            <span><i class="icon-base ti tabler-moon-stars icon-22px me-3" data-icon="moon-stars"></i>Dark</span>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system"
            aria-pressed="false">
            <span><i class="icon-base ti tabler-device-desktop-analytics icon-22px me-3"
                data-icon="device-desktop-analytics"></i>System</span>
          </button>
        </li>
      </ul>
    </li>
  </div>
  <!-- / Style Switcher-->
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Notification Bell -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
    <li class="nav-item dropdown-notification me-2 d-flex align-items-center">
      <?php if (isset($component)) { $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.notification-bell','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('notification-bell'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $attributes = $__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__attributesOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386)): ?>
<?php $component = $__componentOriginal6541145ad4a57bfb6e6f221ba77eb386; ?>
<?php unset($__componentOriginal6541145ad4a57bfb6e6f221ba77eb386); ?>
<?php endif; ?>
    </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <!-- User -->
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="<?php echo e(asset('assets/img/avatars/1.png')); ?>" alt class="rounded-circle" />
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::check()): ?>
        <li>
          <a class="dropdown-item mt-0" href="javascript:void(0);">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online">
                  <img src="<?php echo e(asset('assets/img/avatars/1.png')); ?>" alt class="rounded-circle" />
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0"><?php echo e(Auth::user()->name); ?></h6>
                <small class="text-body-secondary text-capitalize"><?php echo e(Auth::user()->role); ?></small>
              </div>
            </div>
          </a>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
          <a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>">
            <i class="icon-base ti tabler-user me-3 icon-md"></i><span class="align-middle">Profil Saya</span>
          </a>
        </li>
        <li>
          <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
          <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="icon-base ti tabler-power-off me-3 icon-md"></i><span>Logout</span>
          </a>
        </li>
        <form method="POST" id="logout-form" action="<?php echo e(route('logout')); ?>">
          <?php echo csrf_field(); ?>
        </form>
        <?php else: ?>
        <li>
          <div class="d-grid px-2 pt-2 pb-1">
            <a class="btn btn-sm btn-primary d-flex" href="<?php echo e(route('login')); ?>">
              <small class="align-middle">Login</small>
              <i class="icon-base ti tabler-login ms-2 icon-14px"></i>
            </a>
          </div>
        </li>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </ul>
    </li>
    <!--/ User -->
  </ul>
</div>
<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/navbar/navbar-partial.blade.php ENDPATH**/ ?>
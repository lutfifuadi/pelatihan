<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
$configData = Helper::appClasses();
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu" <?php $__currentLoopData = $configData['menuAttributes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attribute=>
  $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php echo e($attribute); ?>="<?php echo e($value); ?>" <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>

  <!-- ! Hide app brand if navbar-full -->
  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!isset($navbarFull)): ?>
  <div class="app-brand demo">
    <a href="<?php echo e(url('/')); ?>" class="app-brand-link">
      <span class="app-brand-logo demo"><?php echo $__env->make('_partials.macros', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
      <span class="app-brand-text demo menu-text fw-bold ms-3"><?php echo e(\App\Models\Setting::where('key', 'app_name')->value('value') ?? config('variables.templateName')); ?></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
      <i class="icon-base ti tabler-x d-block d-xl-none"></i>
    </a>
  </div>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <?php
    $user = Auth::user();
    // Pilih menu berdasarkan role
    $roleMenu = ($user && isset($menuByRole[$user->role])) ? $menuByRole[$user->role] : null;
    $activeMenu = $roleMenu ? $roleMenu->menu : $menuData[0]->menu;
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activeMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($menu->menuHeader)): ?>
    <li class="menu-header small">
      <span class="menu-header-text"><?php echo e(__($menu->menuHeader)); ?></span>
    </li>
    <?php else: ?>
    
    <?php
    $activeClass = null;
    $currentRouteName = Route::currentRouteName();

    if ($currentRouteName === $menu->slug) {
    $activeClass = 'active';
    } elseif (isset($menu->submenu)) {
    if (gettype($menu->slug) === 'array') {
    foreach ($menu->slug as $slug) {
    if (str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) {
    $activeClass = 'active open';
    }
    }
    } else {
    if (
    str_contains($currentRouteName, $menu->slug) and
    strpos($currentRouteName, $menu->slug) === 0
    ) {
    $activeClass = 'active open';
    }
    }
    }
    ?>

    
    <li class="menu-item <?php echo e($activeClass); ?>">
      <a href="<?php echo e(isset($menu->url) ? url($menu->url) : 'javascript:void(0);'); ?>"
        class="<?php echo e(isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link'); ?>" <?php if(isset($menu->target) and
        !empty($menu->target)): ?> target="_blank" <?php endif; ?>>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($menu->icon)): ?>
        <i class="<?php echo e($menu->icon); ?>"></i>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div><?php echo e(isset($menu->name) ? __($menu->name) : ''); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($menu->badge)): ?>
        <div class="badge bg-<?php echo e($menu->badge[0]); ?> rounded-pill ms-auto"><?php echo e($menu->badge[1]); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
      </a>

      
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($menu->submenu)): ?>
      <?php echo $__env->make('layouts.sections.menu.submenu', ['menu' => $menu->submenu], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </li>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </ul>

</aside>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('<?php echo e(route("admin.google-drive.check-status")); ?>')
        .then(res => res.json())
        .then(data => {
            const menuItems = document.querySelectorAll('.menu-inner .menu-item');
            menuItems.forEach(item => {
                const link = item.querySelector('.menu-link');
                if (link && link.textContent.includes('Google Drive')) {
                    const dot = document.createElement('span');
                    dot.className = 'ms-2 badge rounded-pill';
                    dot.style.width = '8px';
                    dot.style.height = '8px';
                    dot.style.padding = '0';
                    dot.style.backgroundColor = data.connected ? '#10b981' : '#ef4444';
                    dot.style.display = 'inline-block';
                    dot.style.borderRadius = '50%';
                    dot.style.boxShadow = data.connected ? '0 0 6px rgba(16,185,129,0.6)' : '0 0 6px rgba(239,68,68,0.6)';
                    link.querySelector('div').appendChild(dot);
                }
            });
        })
        .catch(() => {});
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/menu/verticalMenu.blade.php ENDPATH**/ ?>
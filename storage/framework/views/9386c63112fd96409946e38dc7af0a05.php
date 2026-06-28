<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$containerNav = $configData['contentLayout'] === 'compact' ? 'container-xxl' : 'container-fluid';
$navbarDetached = $navbarDetached ?? '';
?>

<!-- Navbar -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($navbarDetached) && $navbarDetached == 'navbar-detached'): ?>
<nav class="layout-navbar <?php echo e($containerNav); ?> navbar navbar-expand-xl <?php echo e($navbarDetached); ?> align-items-center bg-navbar-theme"
  id="layout-navbar">
  <?php echo $__env->make('layouts/sections/navbar/navbar-partial', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</nav>
<?php else: ?>
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="<?php echo e($containerNav); ?>"><?php echo $__env->make('layouts/sections/navbar/navbar-partial', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></div>
</nav>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<!-- / Navbar -->
<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/navbar/navbar.blade.php ENDPATH**/ ?>
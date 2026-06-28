<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
  rel="stylesheet" />

<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/fonts/iconify/iconify.css']); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($configData['hasCustomizer']): ?>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/pickr/pickr-themes.scss']); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Vendor Styles -->
<?php echo $__env->yieldContent('vendor-style'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/node-waves/node-waves.scss']); ?>

<!-- Core CSS -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/scss/pages/front-page.scss']); ?>

<!-- Page Styles -->
<?php echo $__env->yieldContent('page-style'); ?>


<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>


<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/stylesFront.blade.php ENDPATH**/ ?>
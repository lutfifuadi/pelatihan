<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
  rel="stylesheet" />
<link
  href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700&ampdisplay=swap"
  rel="stylesheet" />
<link
  href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&ampdisplay=swap"
  rel="stylesheet" />

<!-- Fonts Icons -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/fonts/iconify/iconify.css']); ?>

<!-- BEGIN: Vendor CSS-->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/node-waves/node-waves.scss']); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($configData['hasCustomizer']): ?>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/pickr/pickr-themes.scss']); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Core CSS -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/scss/core.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss']); ?>

<!-- Vendor Styles -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss']); ?>
<?php echo $__env->yieldContent('vendor-style'); ?>

<!-- Page Styles -->
<?php echo $__env->yieldContent('page-style'); ?>

<!-- app CSS -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
<!-- END: app CSS-->

<!-- Admin Premium Theme -->
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin-theme.css']); ?>
<!-- END: Admin Premium Theme -->

<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/styles.blade.php ENDPATH**/ ?>
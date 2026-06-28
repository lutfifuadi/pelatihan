<!DOCTYPE html>
<?php
  use Illuminate\Support\Str;
  use App\Helpers\Helpers;

  $menuFixed =
      $configData['layout'] === 'vertical'
          ? $menuFixed ?? ''
          : ($configData['layout'] === 'front'
              ? ''
              : $configData['headerType']);
  $navbarType =
      $configData['layout'] === 'vertical'
          ? $configData['navbarType']
          : ($configData['layout'] === 'front'
              ? 'layout-navbar-fixed'
              : '');
  $isFront = ($isFront ?? '') == true ? 'Front' : '';
  $contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

  // Get skin name from configData - only applies to admin layouts
  $isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
  $skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

  // Get semiDark value from configData - only applies to admin layouts
  $semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

  // Generate primary color CSS if color is set
  $primaryColorCSS = '';
  if (isset($configData['color']) && $configData['color']) {
      $primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
  }

?>

<html lang="id"
  class="<?php echo e($navbarType ?? ''); ?> <?php echo e($contentLayout ?? ''); ?> <?php echo e($menuFixed ?? ''); ?> <?php echo e($menuCollapsed ?? ''); ?> <?php echo e($footerFixed ?? ''); ?> <?php echo e($customizerHidden ?? ''); ?>"
  dir="ltr" data-skin="<?php echo e($skinName); ?>" data-assets-path="<?php echo e(asset('/assets') . '/'); ?>"
  data-base-url="<?php echo e(url('/')); ?>" data-framework="laravel" data-template="<?php echo e($configData['layout']); ?>-menu-template"
  data-bs-theme="<?php echo e($configData['theme']); ?>" <?php if($isAdminLayout && $semiDarkEnabled): ?> data-semidark-menu="true" <?php endif; ?>>

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  
  <?php echo app(\App\Services\SEOManager::class)->render(); ?>

  
  <?php if (! empty(trim($__env->yieldContent('title')))): ?>
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('variables.templateName') ?? config('app.name')); ?></title>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/img/favicon/favicon.ico')); ?>" />

  
  <meta name="application-name" content="Pelatihanku">
  <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
  <meta name="theme-color" content="#7367f0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Pelatihanku">
  <link rel="apple-touch-icon" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">
  <link rel="mask-icon" href="<?php echo e(asset('icons/icon.svg')); ?>" color="#7367f0">
  <meta name="msapplication-TileColor" content="#7367f0">
  <meta name="msapplication-TileImage" content="<?php echo e(asset('icons/icon-192x192.png')); ?>">
  

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  <?php echo $__env->make('layouts/sections/styles' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Component-pushed styles (e.g. floating WhatsApp) -->
  <?php echo $__env->yieldPushContent('styles'); ?>

  <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
      $primaryColorCSS &&
          (config('custom.custom.primaryColor') ||
              isset($_COOKIE['admin-primaryColor']) ||
              isset($_COOKIE['front-primaryColor']))): ?>
    <!-- Primary Color Style -->
    <style id="primary-color-style">
      <?php echo $primaryColorCSS; ?>

    </style>
  <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  <?php echo $__env->make('layouts/sections/scriptsIncludes' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body>
  <?php echo $__env->make('partials.impersonate-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <!-- Layout Content -->
  <?php echo $__env->yieldContent('layoutContent'); ?>
  <!--/ Layout Content -->

  
  

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  <?php echo $__env->make('layouts/sections/scripts' . $isFront, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('<?php echo e(asset("sw.js")); ?>').then(function(registration) {
          console.log('PWA: ServiceWorker registered with scope:', registration.scope);

          // Check for updates
          registration.addEventListener('updatefound', function() {
            const newWorker = registration.installing;
            console.log('PWA: New service worker installing...');
          });
        }).catch(function(err) {
          console.log('PWA: ServiceWorker registration failed: ', err);
        });
      });
    }
  </script>
  

  
  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/pwa-helper.js']); ?>
  

  
  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/push-subscription.js']); ?>
  

  
  <div id="push-notification-overlay" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
       style="background: rgba(0, 0, 0, 0.6);">
    <?php if (isset($component)) { $__componentOriginalf4d1041be06e3dfe02ba84978c726b8b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf4d1041be06e3dfe02ba84978c726b8b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.push-subscription-toggle','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('push-subscription-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf4d1041be06e3dfe02ba84978c726b8b)): ?>
<?php $attributes = $__attributesOriginalf4d1041be06e3dfe02ba84978c726b8b; ?>
<?php unset($__attributesOriginalf4d1041be06e3dfe02ba84978c726b8b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf4d1041be06e3dfe02ba84978c726b8b)): ?>
<?php $component = $__componentOriginalf4d1041be06e3dfe02ba84978c726b8b; ?>
<?php unset($__componentOriginalf4d1041be06e3dfe02ba84978c726b8b); ?>
<?php endif; ?>
  </div>
  

</body>

</html>
<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/commonMaster.blade.php ENDPATH**/ ?>
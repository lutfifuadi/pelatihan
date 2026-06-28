<!-- BEGIN: Vendor JS-->

<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/jquery/jquery.js', 'resources/assets/vendor/libs/popper/popper.js', 'resources/assets/vendor/js/bootstrap.js', 'resources/assets/vendor/libs/node-waves/node-waves.js']); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($configData['hasCustomizer']): ?>
  <?php echo app('Illuminate\Foundation\Vite')('resources/assets/vendor/libs/pickr/pickr.js'); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js', 'resources/assets/vendor/libs/hammer/hammer.js', 'resources/assets/vendor/js/menu.js']); ?>

<?php echo $__env->yieldContent('vendor-script'); ?>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<?php echo app('Illuminate\Foundation\Vite')(['resources/assets/js/main.js']); ?>
<!-- END: Theme JS-->

<!-- Pricing Modal JS-->
<?php echo $__env->yieldPushContent('pricing-script'); ?>
<!-- END: Pricing Modal JS-->

<!-- BEGIN: SweetAlert2 Global Config -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/sweetalert2-global.js']); ?>
<!-- END: SweetAlert2 Global Config -->

<!-- BEGIN: Page JS-->
<?php echo $__env->yieldContent('page-script'); ?>
<!-- END: Page JS-->

<!-- app JS -->
<script>
  window.broadcastEnabled = <?php echo json_encode(\App\Models\Setting::where('key', 'broadcast_enabled')->value('value') ?? '1', 512) ?>;
  window.reverbConfig = {
    key: <?php echo json_encode(config('broadcasting.connections.reverb.key'), 15, 512) ?>,
    host: <?php echo json_encode(config('broadcasting.connections.reverb.options.host'), 15, 512) ?>,
    port: <?php echo json_encode(config('broadcasting.connections.reverb.options.port'), 15, 512) ?>,
    scheme: <?php echo json_encode(config('broadcasting.connections.reverb.options.scheme'), 15, 512) ?>,
  };
</script>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
<!-- END: app JS-->

<?php echo $__env->yieldPushContent('modals'); ?>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/scripts.blade.php ENDPATH**/ ?>
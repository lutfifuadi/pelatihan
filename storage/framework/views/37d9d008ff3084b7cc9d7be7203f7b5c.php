<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pageConfigs)): ?>
  <?php echo Helper::updatePageConfig($pageConfigs); ?>

<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php
  $configData = Helper::appClasses();
  $isFront = true;

  /* Display elements */
  $customizerHidden = $customizerHidden ?? '';
?>



<?php $__env->startSection('layoutContent'); ?>
  <!-- Content -->
  <?php echo $__env->yieldContent('content'); ?>
  <!--/ Content -->

  
  <?php echo $__env->renderWhen(isset($whatsappNumbers) && $whatsappNumbers->isNotEmpty(), 'components.floating-whatsapp', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/commonMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/publicLayout.blade.php ENDPATH**/ ?>
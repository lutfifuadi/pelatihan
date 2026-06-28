<?php
$containerFooter =
isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
? 'container-xxl'
: 'container-fluid';

$footerCopyright = \App\Models\Setting::where('key', 'footer_copyright')->value('value') ?? 'Aplikasi Pelatihan';
?>

<!-- Footer-->
<footer class="content-footer footer bg-footer-theme">
  <div class="<?php echo e($containerFooter); ?>">
    <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
      <div class="text-body">
        &#169;
        <script>
          document.write(new Date().getFullYear());
        </script>
        , <?php echo e($footerCopyright); ?>

      </div>
    </div>
  </div>
</footer>
<!-- / Footer -->
<?php /**PATH D:\Project\Pelatihanku\resources\views/layouts/sections/footer/footer.blade.php ENDPATH**/ ?>
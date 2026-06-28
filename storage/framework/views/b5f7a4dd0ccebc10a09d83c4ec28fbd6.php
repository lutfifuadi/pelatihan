<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class' => '', 'size' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['class' => '', 'size' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $brandName = \App\Models\Setting::where('key', 'brand_name')->value('value') ?? 'SABA Kreatif';
    $parts = explode(' ', $brandName, 2);

    // Ukuran: jika props size dikasih, pakai itu. Kalau tidak, ambil dari setting database
    $selectedSize = $size ?? \App\Models\Setting::where('key', 'brand_logo_size')->value('value') ?? 'md';

    $sizes = [
        'sm' => 'fs-6',
        'md' => 'fs-4',
        'lg' => 'fs-2',
        'xl' => 'fs-1',
    ];
    $fontClass = $sizes[$selectedSize] ?? $sizes['md'];
?>

<span class="logo-text-glow <?php echo e($class); ?> <?php echo e($fontClass); ?>">
    <span class="text-warning fw-bold"><?php echo e($parts[0]); ?></span>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($parts[1])): ?>
        <span class="text-white fw-semibold"><?php echo e($parts[1]); ?></span>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</span>
<?php /**PATH D:\Project\Pelatihanku\resources\views/components/brand-logo.blade.php ENDPATH**/ ?>
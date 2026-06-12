@props(['class' => '', 'size' => null])

@php
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
@endphp

<span class="logo-text-glow {{ $class }} {{ $fontClass }}">
    <span class="text-warning fw-bold">{{ $parts[0] }}</span>
    @if(isset($parts[1]))
        <span class="text-white fw-semibold">{{ $parts[1] }}</span>
    @endif
</span>

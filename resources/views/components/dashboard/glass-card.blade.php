@props([
    'title' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'icon' => null,
    'padding' => 'px-4 py-4',
    'fullHeight' => true,
    'id' => null,
])

@php
$badgeColors = [
    'primary' => 'badge-premium-primary',
    'success' => 'badge-premium-success',
    'warning' => 'badge-premium-warning',
    'info' => 'badge-premium-info',
    'danger' => 'badge-premium-danger',
];
$badgeClass = $badgeColors[$badgeColor] ?? 'badge-premium-primary';
@endphp

<div {{ $attributes->merge(['class' => "glass-card-premium {$padding} " . ($fullHeight ? 'h-100' : '')]) }} @if($id) id="{{ $id }}" @endif>
    @if($title || $badge || $icon)
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            @if($icon)
            <i class="icon-base ti {{ $icon }}"></i>
            @endif
            @if($title)
            <h5 class="fw-bold text-white mb-0">{{ $title }}</h5>
            @endif
        </div>
        @if($badge)
        <span class="badge-premium {{ $badgeClass }}">{{ $badge }}</span>
        @endif
    </div>
    @endif
    {{ $slot }}
</div>

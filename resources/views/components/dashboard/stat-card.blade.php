@props([
    'title',
    'value',
    'icon' => 'tabler-trend-up',
    'color' => 'primary',
    'href' => null,
    'id' => null,
    'trend' => null,
    'trendIcon' => 'tabler-trending-up',
    'trendColor' => 'success',
])

@php
$colorMap = [
    'primary' => ['bg' => 'rgba(99,102,241,0.12)', 'text' => '#6366f1', 'badge' => 'badge-premium-primary'],
    'success' => ['bg' => 'rgba(16,185,129,0.12)', 'text' => '#10b981', 'badge' => 'badge-premium-success'],
    'info'    => ['bg' => 'rgba(6,182,212,0.12)',  'text' => '#06b6d4', 'badge' => 'badge-premium-info'],
    'warning' => ['bg' => 'rgba(245,158,11,0.12)', 'text' => '#f59e0b', 'badge' => 'badge-premium-warning'],
    'danger'  => ['bg' => 'rgba(248,113,113,0.12)','text' => '#f87171', 'badge' => 'badge-premium-danger'],
    'secondary'=>['bg'=> 'rgba(148,163,184,0.12)','text'=> '#94a3b8', 'badge'=> 'badge-premium-secondary'],
];
$c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="glass-card-premium px-3 py-3 h-100 position-relative overflow-hidden">
    {{-- Accent line on left --}}
    <div class="position-absolute top-0 start-0 bottom-0" style="width:3px;background:linear-gradient(180deg,{{ $c['text'] }},transparent);border-radius:3px 0 0 3px;"></div>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <p class="text-body-premium small mb-0">{{ $title }}</p>
            <h3 class="fw-bold text-white mb-0" @if($id) id="{{ $id }}" @endif>{{ $value }}</h3>
        </div>
        <div class="stat-icon-box d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-size:1.2rem;background:{{ $c['bg'] }};color:{{ $c['text'] }};">
            <i class="icon-base ti {{ $icon }}"></i>
        </div>
    </div>
    @if($trend)
    <div class="mt-2">
        <small class="text-{{ $trendColor }}">
            <i class="icon-base ti {{ $trendIcon }} me-1"></i>{{ $trend }}
        </small>
    </div>
    @endif
</div>

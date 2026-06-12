@php
    use Illuminate\Support\Str;
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Notifikasi')

@section('page-style')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .content-wrapper {
        font-family: 'Outfit', sans-serif;
        color: #f8fafc;
        position: relative !important;
        overflow: hidden !important;
    }
    .content-wrapper h1,
    .content-wrapper h2,
    .content-wrapper h3,
    .content-wrapper h4,
    .content-wrapper h5,
    .content-wrapper h6 {
        font-family: 'Sora', sans-serif;
    }

    html,
    body,
    .layout-page,
    .content-wrapper,
    .layout-wrapper,
    .layout-container {
        background-color: #0b0f19 !important;
        background-image: 
            radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 55%),
            radial-gradient(at 100% 0%, rgba(139, 92, 246, 0.15) 0px, transparent 55%),
            radial-gradient(at 50% 50%, rgba(236, 72, 153, 0.08) 0px, transparent 50%) !important;
        color: #f8fafc !important;
    }

    .layout-navbar-fixed .layout-page::before {
        display: none !important;
    }

    .content-wrapper > .container-xxl {
        max-width: 100% !important;
        padding: 0 !important;
    }

    .layout-menu,
    #layout-menu {
        background-color: #0b0f19 !important;
        border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    .notif-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 5px;
        backdrop-filter: blur(12px);
        transition: all 0.2s ease;
    }
    .notif-card:hover {
        background: rgba(255,255,255,0.06);
        border-color: rgba(255,255,255,0.1);
    }
    .notif-card.unread {
        border-left: 3px solid #6366f1;
        background: rgba(99,102,241,0.05);
    }

    .filter-btn {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        color: #b0b4c2;
        border-radius: 5px;
        padding: 8px 18px;
        font-size: 13px;
        font-family: 'Outfit', sans-serif;
        transition: all 0.2s;
    }
    .filter-btn:hover,
    .filter-btn.active {
        background: rgba(99,102,241,0.15);
        border-color: #6366f1;
        color: #fff;
    }

    .pagination-container .pagination {
        --bs-pagination-bg: rgba(255,255,255,0.04);
        --bs-pagination-border-color: rgba(255,255,255,0.08);
        --bs-pagination-color: #b0b4c2;
        --bs-pagination-hover-bg: rgba(99,102,241,0.15);
        --bs-pagination-hover-border-color: #6366f1;
        --bs-pagination-hover-color: #fff;
        --bs-pagination-active-bg: #6366f1;
        --bs-pagination-active-border-color: #6366f1;
        --bs-pagination-active-color: #fff;
        --bs-pagination-disabled-bg: transparent;
        --bs-pagination-disabled-color: #4a4f62;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-1 fw-bold" style="color: #f8fafc; font-family: 'Sora', sans-serif;">Notifikasi</h3>
            <p class="mb-0" style="color: #9ca0b0; font-size: 14px;">Riwayat notifikasi anda</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="filter-btn">
                    <i class="icon-base ti tabler-check-all me-1"></i>Tandai Semua Dibaca
                </button>
            </form>
            <a href="{{ route('notifications.preferences') }}" class="filter-btn ms-2">
                <i class="icon-base ti tabler-settings me-1"></i>Preferensi
            </a>
        </div>
    </div>

    <div class="notif-card p-4 mb-4">
        <div class="d-flex flex-wrap gap-2 mb-1">
            <a href="{{ route('notifications.index', ['channel' => 'all', 'status' => request('status')]) }}"
                class="filter-btn {{ request('channel', 'all') === 'all' ? 'active' : '' }}">
                <i class="icon-base ti tabler-bell me-1"></i>Semua
            </a>
            <a href="{{ route('notifications.index', ['channel' => 'in_app', 'status' => request('status')]) }}"
                class="filter-btn {{ request('channel') === 'in_app' ? 'active' : '' }}">
                <i class="icon-base ti tabler-bell me-1"></i>In-App
            </a>
            <a href="{{ route('notifications.index', ['channel' => 'whatsapp', 'status' => request('status')]) }}"
                class="filter-btn {{ request('channel') === 'whatsapp' ? 'active' : '' }}">
                <i class="icon-base ti tabler-brand-whatsapp me-1" style="color:#25D366;"></i>WhatsApp
            </a>
            <a href="{{ route('notifications.index', ['channel' => 'email', 'status' => request('status')]) }}"
                class="filter-btn {{ request('channel') === 'email' ? 'active' : '' }}">
                <i class="icon-base ti tabler-mail me-1" style="color:#6366f1;"></i>Email
            </a>

            <span style="width:1px; height:24px; background: rgba(255,255,255,0.1); margin: 4px 8px;"></span>

            <a href="{{ route('notifications.index', ['status' => 'all', 'channel' => request('channel')]) }}"
                class="filter-btn {{ request('status', 'all') === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('notifications.index', ['status' => 'unread', 'channel' => request('channel')]) }}"
                class="filter-btn {{ request('status') === 'unread' ? 'active' : '' }}">Unread</a>
            <a href="{{ route('notifications.index', ['status' => 'read', 'channel' => request('channel')]) }}"
                class="filter-btn {{ request('status') === 'read' ? 'active' : '' }}">Read</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
            style="background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25); color: #86efac; border-radius: 5px;">
            <i class="icon-base ti tabler-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="filter: invert(1) brightness(2);"></button>
        </div>
    @endif

    <div class="notif-card overflow-hidden">
        @forelse($notifications as $notif)
            <div class="p-3 {{ $loop->first ? '' : '' }} {{ $notif->read_at ? '' : 'unread' }} notif-card rounded-0"
                style="border-left: none !important; border-top: none !important; border-right: none !important; margin-bottom: 0;">
                <div class="d-flex align-items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        @if($notif->channel === 'whatsapp')
                            <i class="icon-base ti tabler-brand-whatsapp" style="color: #25D366; font-size: 20px;"></i>
                        @elseif($notif->channel === 'email')
                            <i class="icon-base ti tabler-mail" style="color: #6366f1; font-size: 20px;"></i>
                        @else
                            <i class="icon-base ti tabler-bell" style="color: #f59e0b; font-size: 20px;"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong style="color: #e8e9ed; font-size: 14px;">{{ $notif->title }}</strong>
                                @if(!$notif->read_at)
                                    <span class="badge bg-primary ms-2" style="font-size: 9px;">NEW</span>
                                @endif
                            </div>
                            <small style="color: #6b7084; font-size: 12px; white-space: nowrap; margin-left: 12px;">
                                {{ $notif->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <p style="color: #9ca0b0; font-size: 13px; margin: 4px 0 0 0;">
                            {{ Str::limit($notif->body, 150) }}
                        </p>
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="badge" style="background: rgba(255,255,255,0.06); color: #b0b4c2; font-size: 11px;">
                                {{ ucfirst($notif->channel) }}
                            </span>
                            @if($notif->read_at)
                                <span class="badge" style="background: rgba(34,197,94,0.12); color: #86efac; font-size: 11px;">
                                    <i class="icon-base ti tabler-check me-1"></i>Sudah Dibaca
                                </span>
                            @else
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0" style="background: none; border: none; color: #6366f1; font-size: 12px;">
                                        <i class="icon-base ti tabler-check me-1"></i>Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="icon-base ti tabler-bell-off" style="font-size: 48px; color: #4a4f62;"></i>
                <h5 class="mt-3" style="color: #9ca0b0;">Belum ada notifikasi</h5>
                <p style="color: #6b7084; font-size: 14px;">Notifikasi akan muncul disini ketika ada aktivitas terkait akun anda.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-4 pagination-container d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection

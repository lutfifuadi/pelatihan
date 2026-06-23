@php
    $configData = Helper::appClasses();
@endphp

<div x-data="notificationBell()" class="notification-bell-container d-inline-block position-relative me-2">
    <button @click="toggleDropdown"
        class="nav-link position-relative d-flex align-items-center justify-content-center"
        style="width: 40px; height: 40px; border: none; background: transparent; cursor: pointer;"
        aria-label="Notifikasi">
        <i class="icon-base ti tabler-bell icon-md" style="color: #b0b4c2; transition: color 0.2s;"></i>
        <span x-show="unreadCount > 0"
            x-cloak
            x-text="unreadCount"
            class="position-absolute badge rounded-pill bg-danger"
            style="top: 0; right: 0; font-size: 0.6rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; padding: 0 4px; line-height: 1; transform: translate(25%, -25%);">
        </span>
    </button>

    <div x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="position-absolute end-0 mt-2 py-0"
        style="z-index: 9999; width: 380px; max-width: 90vw; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">

        {{-- Header --}}
        <div class="px-3 py-3 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <h6 class="fw-bold mb-0" style="color: #f8fafc; font-family: 'Sora', sans-serif; font-size: 0.9rem;">
                🔔 Notifikasi
            </h6>
            <button x-show="unreadCount > 0"
                @click="markAllAsRead()"
                class="btn btn-sm p-0 border-0 bg-transparent"
                style="color: rgba(255,255,255,0.5); font-size: 0.75rem;">
                ✅ Tandai semua dibaca
            </button>
        </div>

        {{-- Notification List --}}
        <div class="notification-list" style="max-height: 350px; overflow-y: auto;">
            <template x-if="notifications.length === 0">
                <div class="text-center py-4">
                    <i class="icon-base ti tabler-bell-off fs-2 d-block mb-2" style="color: rgba(255,255,255,0.2);"></i>
                    <span style="color: rgba(255,255,255,0.4); font-size: 0.85rem;">Belum ada notifikasi</span>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <div class="px-3 py-3" 
                    style="border-bottom: 1px solid rgba(255,255,255,0.04); cursor: pointer; transition: all 0.2s;"
                    :style="notif.read_at ? '' : 'background: rgba(99,102,241,0.05);'"
                    @click="markAsRead(notif.id)">
                    <div class="d-flex gap-3">
                        {{-- Indicator --}}
                        <div class="mt-1 flex-shrink-0">
                            <div :style="'width: 10px; height: 10px; border-radius: 50%; ' + (notif.read_at ? 'background: rgba(255,255,255,0.2);' : 'background: #818cf8; box-shadow: 0 0 8px rgba(99,102,241,0.5);')"></div>
                        </div>
                        {{-- Content --}}
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="fw-semibold mb-0" style="font-size: 0.85rem; color: #f8fafc;" x-text="notif.title"></h6>
                                <small class="flex-shrink-0 ms-2" style="color: rgba(255,255,255,0.4); font-size: 0.65rem;" x-text="timeAgo(notif.created_at)"></small>
                            </div>
                            <p class="mb-1" style="color: rgba(255,255,255,0.6); font-size: 0.78rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" x-text="notif.body"></p>
                            {{-- WA Hubungi Admin Button --}}
                            <template x-if="notif.data?.wa_data || notif.wa_data">
                                <a :href="waUrl(notif.data?.wa_data || notif.wa_data)" target="_blank" class="btn btn-sm mt-1" 
                                    style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #34d399; border-radius: 5px; font-size: 0.7rem; text-decoration: none; display: inline-flex; align-items: center;"
                                    @click.stop>
                                    <i class="icon-base ti tabler-brand-whatsapp me-1" style="font-size: 0.75rem;"></i> Hubungi Admin
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div style="border-top: 1px solid rgba(255,255,255,0.06);">
            @auth
            @if(auth()->user()->role === 'peserta')
            <a href="{{ route('dashboard.peserta.notifikasi') }}" 
                class="d-block text-center py-2 text-decoration-none"
                style="color: #818cf8; font-size: 0.8rem; font-weight: 600;">
                📨 Lihat Semua Notifikasi
            </a>
            @else
            <a href="{{ route('notifications.index') }}" 
                class="d-block text-center py-2 text-decoration-none"
                style="color: #818cf8; font-size: 0.8rem; font-weight: 600;">
                📨 Lihat Semua Notifikasi
            </a>
            @endif
            @endauth
        </div>
    </div>
</div>

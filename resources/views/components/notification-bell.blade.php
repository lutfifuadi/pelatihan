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
            class="position-absolute badge badge-dot badge-notifications bg-danger rounded-pill"
            style="top: 2px; right: 2px; font-size: 10px; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; padding: 0 4px; line-height: 1;">
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
        class="position-absolute end-0 mt-2 py-2"
        style="z-index: 9999; width: 360px; max-width: 90vw; background: #1a1f2e; border: 1px solid rgba(255,255,255,0.08); border-radius: 5px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">

        <div class="px-3 pb-2 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <h6 class="mb-0 fw-semibold" style="color: #e8e9ed; font-size: 14px;">Notifikasi</h6>
            <span x-text="unreadCount + ' baru'" class="badge bg-primary rounded-pill" style="font-size: 11px;"></span>
        </div>

        <div class="notification-list" style="max-height: 360px; overflow-y: auto;">
            <template x-if="notifications.length === 0">
                <div class="text-center py-4 px-3">
                    <i class="icon-base ti tabler-bell-off" style="font-size: 32px; color: #4a4f62;"></i>
                    <p class="mt-2 mb-0" style="color: #6b7084; font-size: 13px;">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-for="(notif, index) in notifications" :key="notif.id">
                <div :class="notif.read_at ? '' : 'bg-primary bg-opacity-10'"
                    style="padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.2s; cursor: pointer;">
                    <div class="d-flex align-items-start gap-2">
                        <div class="flex-shrink-0 mt-1">
                            <template x-if="notif.channel === 'whatsapp'">
                                <i class="icon-base ti tabler-brand-whatsapp" style="color: #25D366; font-size: 16px;"></i>
                            </template>
                            <template x-if="notif.channel === 'email'">
                                <i class="icon-base ti tabler-mail" style="color: #6366f1; font-size: 16px;"></i>
                            </template>
                            <template x-if="notif.channel === 'in_app' || !notif.channel">
                                <i class="icon-base ti tabler-bell" style="color: #f59e0b; font-size: 16px;"></i>
                            </template>
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong x-text="notif.title" style="color: #e8e9ed; font-size: 13px; line-height: 1.3;"></strong>
                                <small x-text="notif.time_ago" style="color: #6b7084; font-size: 11px; white-space: nowrap; margin-left: 8px;"></small>
                            </div>
                            <p x-text="notif.body" style="color: #9ca0b0; font-size: 12px; margin: 2px 0 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></p>
                        </div>
                        <button x-show="!notif.read_at"
                            @click.stop="markAsRead(notif.id)"
                            class="btn btn-sm p-0 ms-1 flex-shrink-0"
                            style="background: none; border: none; color: #6366f1; font-size: 11px;"
                            title="Tandai sudah dibaca">
                            <i class="icon-base ti tabler-check"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.06); padding: 10px 16px; text-align: center;">
            <a href="{{ route('notifications.index') }}"
                style="color: #6366f1; font-size: 13px; text-decoration: none; font-weight: 500;">
                Lihat Semua Notifikasi
                <i class="icon-base ti tabler-arrow-right ms-1" style="font-size: 12px;"></i>
            </a>
        </div>
    </div>
</div>

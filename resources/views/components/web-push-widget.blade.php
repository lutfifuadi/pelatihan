@php
    $vapidPublicKey = config('services.web_push.vapid_public_key') ?? env('VAPID_PUBLIC_KEY');
    $user = auth()->user();
@endphp

@if($vapidPublicKey && $user)
<div class="glass-card-premium mb-4" id="webPushPanel" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(15, 23, 42, 0.6) 100%) !important; border: 1px solid rgba(99, 102, 241, 0.25) !important; border-radius: 5px !important;">
    <div class="p-3 px-md-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            {{-- Info & Icon --}}
            <div class="d-flex align-items-center gap-3">
                <div style="width: 42px; height: 42px; border-radius: 5px !important; background: rgba(99, 102, 241, 0.18); border: 1px solid rgba(129, 140, 248, 0.4); color: #c7d2fe; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);">
                    <i class="icon-base ti tabler-bell-ringing" id="pushIcon"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h6 class="text-white fw-bold mb-0" style="font-size: 0.92rem; letter-spacing: -0.01em;">Notifikasi Layar &amp; HP (Web Push)</h6>
                        <span class="badge" id="pushBadge" style="background: rgba(255,255,255,0.08); color: #94a3b8; font-size: 0.7rem; border: 1px solid rgba(255,255,255,0.12); border-radius: 4px !important; padding: 3px 8px; font-weight: 600;">
                            <span class="spinner-border spinner-border-sm me-1" style="width: 9px; height: 9px;"></span> Memeriksa...
                        </span>
                    </div>
                    <p class="text-body-premium mb-0 small" style="font-size: 0.78rem; line-height: 1.4;" id="pushSub">
                        Dapatkan notifikasi instan untuk hasil verifikasi seleksi, pengingat kelas pelatihan, dan pengumuman dinas langsung di perangkat ini.
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center gap-2 flex-wrap flex-shrink-0" id="pushActions">
                <button type="button" class="btn d-inline-flex align-items-center gap-1.5" id="btnSubscribePush" onclick="toggleWebPushSubscription()" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #fff; border: 1px solid rgba(99, 102, 241, 0.5); border-radius: 5px !important; box-shadow: 0 2px 10px rgba(99, 102, 241, 0.3); padding: 6px 14px; font-weight: 700; font-size: 0.82rem; transition: all 0.2s ease;">
                    <i class="icon-base ti tabler-bell-plus" id="iconBtnSubscribe" style="font-size: 0.95rem;"></i>
                    <span id="lblBtnSubscribe">Aktifkan Notifikasi</span>
                </button>
                <button type="button" class="btn d-none d-inline-flex align-items-center gap-1.5" id="btnTestPush" onclick="testWebPushNotification()" style="background: rgba(52, 211, 153, 0.12); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); border-radius: 5px !important; padding: 6px 12px; font-weight: 600; font-size: 0.82rem; transition: all 0.2s ease;" title="Kirim notifikasi uji coba ke browser ini">
                    <i class="icon-base ti tabler-send"></i> <span>Test Notif</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const VAPID_PUBLIC_KEY = '{{ $vapidPublicKey }}';
    let isWebPushSubscribed = false;

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
        const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    async function initWebPushState() {
        const badge = document.getElementById('pushBadge');
        const btnSub = document.getElementById('btnSubscribePush');
        const lblBtn = document.getElementById('lblBtnSubscribe');
        const iconBtn = document.getElementById('iconBtnSubscribe');
        const btnTest = document.getElementById('btnTestPush');
        const icon = document.getElementById('pushIcon');

        if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
            if (badge) {
                badge.style.background = 'rgba(255, 255, 255, 0.05)';
                badge.style.color = '#64748b';
                badge.style.border = '1px solid rgba(255, 255, 255, 0.1)';
                badge.textContent = 'Browser Tidak Mendukung';
            }
            if (btnSub) btnSub.disabled = true;
            return;
        }

        try {
            // Register SW if not registered
            if (navigator.serviceWorker.controller === null) {
                await navigator.serviceWorker.register('/sw.js');
            }
            const reg = await navigator.serviceWorker.ready;
            const subscription = await reg.pushManager.getSubscription();

            if (subscription) {
                // Verifikasi status ke backend
                const res = await fetch('/api/web-push/user/status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ endpoint: subscription.endpoint })
                });
                const data = await res.json();

                if (data.active) {
                    isWebPushSubscribed = true;
                    if (badge) {
                        badge.style.background = 'rgba(16, 185, 129, 0.18)';
                        badge.style.color = '#6ee7b7';
                        badge.style.border = '1px solid rgba(52, 211, 153, 0.4)';
                        badge.innerHTML = '<i class="icon-base ti tabler-check me-1"></i> Aktif di Perangkat Ini';
                    }
                    if (lblBtn) lblBtn.textContent = 'Nonaktifkan';
                    if (iconBtn) iconBtn.className = 'icon-base ti tabler-bell-off';
                    if (btnSub) {
                        btnSub.style.background = 'rgba(239, 68, 68, 0.14)';
                        btnSub.style.color = '#f87171';
                        btnSub.style.border = '1px solid rgba(239, 68, 68, 0.35)';
                        btnSub.style.boxShadow = 'none';
                        btnSub.disabled = false;
                    }
                    if (btnTest) btnTest.classList.remove('d-none');
                    if (icon) {
                        icon.className = 'icon-base ti tabler-bell-check text-success';
                    }
                    return;
                }
            }

            // Jika belum subscribe
            isWebPushSubscribed = false;
            if (badge) {
                badge.style.background = 'rgba(245, 158, 11, 0.15)';
                badge.style.color = '#fde047';
                badge.style.border = '1px solid rgba(251, 191, 36, 0.35)';
                badge.textContent = 'Belum Diaktifkan';
            }
            if (lblBtn) lblBtn.textContent = 'Aktifkan Notifikasi';
            if (iconBtn) iconBtn.className = 'icon-base ti tabler-bell-plus';
            if (btnSub) {
                btnSub.style.background = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';
                btnSub.style.color = '#fff';
                btnSub.style.border = '1px solid rgba(99, 102, 241, 0.5)';
                btnSub.style.boxShadow = '0 2px 10px rgba(99, 102, 241, 0.3)';
                btnSub.disabled = false;
            }
            if (btnTest) btnTest.classList.add('d-none');
            if (icon) {
                icon.className = 'icon-base ti tabler-bell-ringing';
            }
        } catch (e) {
            console.error('Push state init error:', e);
        }
    }

    window.toggleWebPushSubscription = async function() {
        const btnSub = document.getElementById('btnSubscribePush');
        if (btnSub) btnSub.disabled = true;

        try {
            const reg = await navigator.serviceWorker.ready;

            if (isWebPushSubscribed) {
                // Unsubscribe
                const sub = await reg.pushManager.getSubscription();
                if (sub) {
                    await fetch('/api/web-push/user/unsubscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ endpoint: sub.endpoint })
                    });
                    await sub.unsubscribe();
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Notifikasi Dinonaktifkan',
                        text: 'Anda tidak akan lagi menerima Web Push di perangkat ini.',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'btn btn-primary' }
                    });
                }
            } else {
                // Request permission & Subscribe
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Izin Notifikasi Ditolak',
                            text: 'Silakan izinkan notifikasi pada ikon gembok di sebelah kiri address bar browser Anda.',
                            confirmButtonText: 'Mengerti',
                            customClass: { confirmButton: 'btn btn-primary' }
                        });
                    }
                    if (btnSub) btnSub.disabled = false;
                    return;
                }

                const convertedVapidKey = urlBase64ToUint8Array(VAPID_PUBLIC_KEY);
                const subscription = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: convertedVapidKey
                });

                const subJson = subscription.toJSON();
                const userAgent = navigator.userAgent;

                await fetch('/api/web-push/user/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        endpoint: subscription.endpoint,
                        keys: subJson.keys,
                        content_encoding: (PushManager.supportedContentEncodings || ['aes128gcm'])[0],
                        device_label: navigator.platform || 'Desktop/Mobile',
                        browser: navigator.userAgentData?.brands?.[0]?.brand || 'Browser'
                    })
                });

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notifikasi Aktif! 🎉',
                        text: 'Perangkat Anda kini siap menerima notifikasi instan seputar seleksi dan pelatihan.',
                        confirmButtonText: 'Siap',
                        customClass: { confirmButton: 'btn btn-success' }
                    });
                }
            }

            await initWebPushState();
        } catch (e) {
            console.error('Push toggle error:', e);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memproses Notifikasi',
                    text: e.message || 'Terjadi kendala saat menghubungkan ke browser push service.',
                    confirmButtonText: 'Tutup',
                    customClass: { confirmButton: 'btn btn-danger' }
                });
            }
            if (btnSub) btnSub.disabled = false;
        }
    };

    window.testWebPushNotification = async function() {
        const btnTest = document.getElementById('btnTestPush');
        if (btnTest) {
            btnTest.disabled = true;
            btnTest.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...';
        }

        try {
            const res = await fetch('/api/web-push/user/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await res.json();

            if (data.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notifikasi Terkirim!',
                        text: data.message || 'Cek sudut layar atau panel notifikasi HP Anda sekarang.',
                        confirmButtonText: 'Bagus',
                        customClass: { confirmButton: 'btn btn-success' }
                    });
                }
            } else {
                throw new Error(data.message || 'Gagal mengirim notifikasi.');
            }
        } catch (e) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Uji Coba Gagal',
                    text: e.message,
                    confirmButtonText: 'Tutup',
                    customClass: { confirmButton: 'btn btn-secondary' }
                });
            }
        } finally {
            if (btnTest) {
                btnTest.disabled = false;
                btnTest.innerHTML = '<i class="icon-base ti tabler-send"></i> <span>Test Notif</span>';
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWebPushState);
    } else {
        initWebPushState();
    }
})();
</script>
@endif

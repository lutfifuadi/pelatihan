/**
 * Push Subscription Manager untuk Pelatihanku
 *
 * Menangani client-side Web Push subscription:
 * - Mendapatkan VAPID public key dari server
 * - Subscribe / unsubscribe ke push notifications
 * - Integrasi dengan Service Worker yang sudah terdaftar
 *
 * Dependencies:
 * - Service Worker sudah diregistrasi di commonMaster.blade.php
 * - Backend API: GET /api/push/vapid-public-key, POST /api/push/subscribe
 */

// ============================================================
// Constants
// ============================================================
const BANNER_LOCALSTORAGE_KEY = 'pelatihanku_push_banner_dismissed';
const BANNER_DISMISS_DAYS = 7;

// ============================================================
// Helper: Convert VAPID base64 key ke Uint8Array
// (standar yang dibutuhkan PushManager.subscribe)
// ============================================================
function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');

  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);

  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }

  return outputArray;
}

// ============================================================
// Helper: Check if banner was dismissed recently
// ============================================================
function isBannerDismissed() {
  try {
    const dismissed = localStorage.getItem(BANNER_LOCALSTORAGE_KEY);
    if (dismissed) {
      const dismissedTime = parseInt(dismissed, 10);
      const now = Date.now();
      const diffDays = (now - dismissedTime) / (1000 * 60 * 60 * 24);
      return diffDays < BANNER_DISMISS_DAYS;
    }
  } catch (e) {
    console.warn('[Push] localStorage tidak tersedia:', e);
  }
  return false;
}

// ============================================================
// Helper: Set localStorage flag dismissed
// ============================================================
function setDismissedLocalStorage() {
  try {
    localStorage.setItem(BANNER_LOCALSTORAGE_KEY, String(Date.now()));
    console.log('[Push] Banner dismissed — localStorage flag diset');
  } catch (e) {
    console.warn('[Push] Gagal set localStorage:', e);
  }
}

// ============================================================
// Helper: Show the push notification overlay
// ============================================================
function showOverlay() {
  // Cek apakah browser support Notification
  if (!('Notification' in window)) {
    return;
  }

  // Cek localStorage — jangan muncul jika sudah di-dismiss
  if (isBannerDismissed()) {
    console.log('[Push] Overlay tidak ditampilkan (masih dalam masa dismiss ' + BANNER_DISMISS_DAYS + ' hari)');
    return;
  }

  // Cek permission — hanya tampilkan jika masih 'default'
  if (Notification.permission !== 'default') {
    return;
  }

  console.log('[Push] Menampilkan overlay push notification');

  // Tampilkan overlay
  const overlay = document.getElementById('push-notification-overlay');
  if (overlay) {
    overlay.classList.remove('hidden');
    // Trigger fade-in animation
    requestAnimationFrame(() => {
      overlay.style.transition = 'opacity 0.4s ease';
      overlay.style.opacity = '1';
    });
  }
}

// ============================================================
// Helper: Hide the push notification overlay
// ============================================================
function hideOverlay() {
  const overlay = document.getElementById('push-notification-overlay');
  if (overlay) {
    overlay.classList.add('hidden');
  }
}

// ============================================================
// Fungsi: getVapidPublicKey()
// Fetch VAPID public key dari backend
// ============================================================
async function getVapidPublicKey() {
  try {
    const response = await fetch('/api/push/vapid-public-key', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}: Gagal mengambil VAPID public key`);
    }

    const data = await response.json();

    if (!data.success || !data.publicKey) {
      throw new Error('Response VAPID public key tidak valid');
    }

    console.log('[Push] VAPID public key berhasil didapatkan');
    return data.publicKey;
  } catch (error) {
    console.error('[Push] Gagal mendapatkan VAPID public key:', error);
    throw error;
  }
}

// ============================================================
// Fungsi: subscribeToPush(registration, vapidPublicKey)
// Subscribe ke push notification dan kirim subscription ke server
// ============================================================
async function subscribeToPush(registration, vapidPublicKey) {
  try {
    // Subscribe ke push manager
    const subscription = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    });

    console.log('[Push] Berhasil subscribe ke push notification');

    // Kirim subscription ke backend
    const subData = subscription.toJSON();
    const payload = {
      endpoint: subData.endpoint,
      keys: {
        p256dh: subData.keys.p256dh,
        auth: subData.keys.auth,
      },
    };

    // Dapatkan CSRF token dari meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const response = await fetch('/api/push/subscribe', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();

    if (!response.ok || !result.success) {
      throw new Error(result.message || 'Gagal menyimpan subscription ke server');
    }

    console.log('[Push] Subscription berhasil disimpan ke server:', result.message);

    // Auto-hide overlay after successful subscription (with delay)
    setTimeout(hideOverlay, 1500);

    return result;
  } catch (error) {
    console.error('[Push] Gagal subscribe:', error);
    throw error;
  }
}

// ============================================================
// Fungsi: unsubscribeFromPush(registration)
// Unsubscribe dari push notification
// ============================================================
async function unsubscribeFromPush(registration) {
  try {
    const subscription = await registration.pushManager.getSubscription();

    if (!subscription) {
      console.log('[Push] Tidak ada subscription yang aktif');
      return false;
    }

    const unsubscribed = await subscription.unsubscribe();

    if (unsubscribed) {
      console.log('[Push] Berhasil unsubscribe dari push notification');
    } else {
      console.warn('[Push] Gagal unsubscribe');
    }

    return unsubscribed;
  } catch (error) {
    console.error('[Push] Gagal unsubscribe:', error);
    throw error;
  }
}

// ============================================================
// Fungsi: initPushSubscription()
// Main function: inisialisasi push subscription
// - Cek support browser
// - Cek permission status
// - Jangan request permission otomatis, butuh user gesture
// ============================================================
async function initPushSubscription() {
  // Cek apakah browser support service worker & push manager
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
    console.log('[Push] Browser tidak mendukung push notification');
    return false;
  }

  const permission = Notification.permission;

  if (permission === 'granted') {
    console.log('[Push] Izin notifikasi sudah diberikan — melanjutkan subscribe');
    try {
      const registration = await navigator.serviceWorker.ready;
      const vapidPublicKey = await getVapidPublicKey();
      await subscribeToPush(registration, vapidPublicKey);
      return true;
    } catch (error) {
      console.error('[Push] Gagal subscribe saat init:', error);
      return false;
    }
  }

  if (permission === 'denied') {
    console.log('[Push] Izin notifikasi diblokir oleh user. Arahkan ke pengaturan browser.');
    return false;
  }

  // permission === 'default' — jangan request otomatis
  // Terserah user untuk klik tombol "Aktifkan Notifikasi"
  console.log('[Push] Izin notifikasi belum ditentukan. Tunggu user gesture.');
  return false;
}

// ============================================================
// Fungsi: showPromptBanner() — Legacy
// Dipertahankan untuk kompatibilitas, tapi alih-alih membuat
// banner, fungsi ini sekarang memunculkan overlay component.
// ============================================================
function showPromptBanner() {
  showOverlay();
}

// ============================================================
// Expose ke window object untuk digunakan oleh Blade / Alpine.js
// ============================================================
window.PushSubscriptionManager = {
  init: initPushSubscription,
  subscribe: subscribeToPush,
  unsubscribe: unsubscribeFromPush,
  getVapidKey: getVapidPublicKey,
  getPermissionStatus: () => Notification.permission,
  isSupported: () => 'serviceWorker' in navigator && 'PushManager' in window,
  showPrompt: showPromptBanner,
  showOverlay: showOverlay,
  hideOverlay: hideOverlay,
};

// Log debug
if (typeof console !== 'undefined') {
  console.log('[Push] PushSubscriptionManager siap digunakan');
}

// ============================================================
// Alpine.js Component: pushSubscription
// Untuk digunakan di Blade component push-subscription-toggle
// ============================================================
function registerPushSubscriptionData() {
  if (!window.Alpine) return;

  // Hindari double register
  if (window.Alpine.__pushSubscriptionRegistered) return;
  window.Alpine.__pushSubscriptionRegistered = true;

  window.Alpine.data('pushSubscription', () => ({
    permission: 'loading',
    isSubscribed: false,
    isSupported: false,
    dismissed: false,

    init() {
      this.isSupported = window.PushSubscriptionManager?.isSupported() || false;

      if (!this.isSupported) {
        this.permission = 'unsupported';
        return;
      }

      this.permission = Notification.permission;

      // Cek apakah user sudah subscribe
      navigator.serviceWorker.ready
        .then((reg) => reg.pushManager.getSubscription())
        .then((subscription) => {
          this.isSubscribed = !!subscription;
        })
        .catch((err) => {
          console.error('[Push] Gagal cek subscription status:', err);
        });

      // Listen for auto-dismiss events
      document.addEventListener('push-overlay-dismiss', () => {
        this.dismissed = true;
      });
    },

    async requestPermission() {
      try {
        const permission = await Notification.requestPermission();
        this.permission = permission;

        if (permission === 'granted') {
          const reg = await navigator.serviceWorker.ready;
          const key = await window.PushSubscriptionManager.getVapidKey();
          await window.PushSubscriptionManager.subscribe(reg, key);
          this.isSubscribed = true;
        }
      } catch (error) {
        console.error('[Push] Gagal request permission:', error);
      }
    },

    async unsubscribe() {
      try {
        const reg = await navigator.serviceWorker.ready;
        await window.PushSubscriptionManager.unsubscribe(reg);
        this.isSubscribed = false;
      } catch (error) {
        console.error('[Push] Gagal unsubscribe:', error);
      }
    },

    dismiss() {
      this.dismissed = true;
      setDismissedLocalStorage();
      // Dispatch event untuk parent overlay
      document.dispatchEvent(new CustomEvent('push-overlay-dismiss'));
    },
  }));
}

// Jika Alpine sudah ada, register langsung
if (window.Alpine) {
  registerPushSubscriptionData();
} else {
  // Jika belum, tunggu event
  document.addEventListener('alpine:init', registerPushSubscriptionData);
}

// ============================================================
// Auto-init overlay after DOM ready + 5 seconds
// ============================================================
function autoInitPushOverlay() {
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

  setTimeout(() => {
    if (!window.PushSubscriptionManager) return;

    window.PushSubscriptionManager.init().then((result) => {
      if (!result) {
        window.PushSubscriptionManager.showOverlay();
      }
    });
  }, 5000);
}

// Run after DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', autoInitPushOverlay);
} else {
  autoInitPushOverlay();
}

// Ekspor untuk digunakan oleh module lain jika perlu
export {
  urlBase64ToUint8Array,
  getVapidPublicKey,
  subscribeToPush,
  unsubscribeFromPush,
  initPushSubscription,
};
export default {
  urlBase64ToUint8Array,
  getVapidPublicKey,
  subscribeToPush,
  unsubscribeFromPush,
  initPushSubscription,
};

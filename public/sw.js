/**
 * PWA Service Worker - Aplikasi Pelatihan
 * Version 1.1.0
 */

const CACHE_NAME = 'pelatihanku-pwa-v3';
const STATIC_ASSETS = [
  '/offline',
  '/manifest.json',
  '/icons/icon.svg',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/icons/badge-72x72.png',
];

// Install event - cache critical offline assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[PWA] Caching static assets...');
      return cache.addAll(STATIC_ASSETS).catch((error) => {
        console.warn('[PWA] Some assets failed to cache:', error);
      });
    })
  );
  // Activate immediately without waiting for page refresh
  self.skipWaiting();
});

// Activate event - cleanup old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((name) => name !== CACHE_NAME)
          .map((name) => {
            console.log('[PWA] Deleting old cache:', name);
            return caches.delete(name);
          })
      );
    }).then(() => {
      console.log('[PWA] Service Worker activated');
      return self.clients.claim();
    })
  );
});

// Helper: check if request is for a navigation (HTML) page
const isNavigation = (request) => {
  return request.mode === 'navigate' ||
    (request.method === 'GET' &&
      request.headers.get('Accept') &&
      request.headers.get('Accept').includes('text/html'));
};

// Helper: check if request is for a static asset
const isStaticAsset = (request) => {
  const url = new URL(request.url);
  const ext = url.pathname.split('.').pop().toLowerCase();
  return ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf', 'eot'].includes(ext);
};

// Fetch event - carefully managed strategies
self.addEventListener('fetch', (event) => {
  const request = event.request;

  // 1. Only handle GET requests
  if (request.method !== 'GET') {
    return;
  }

  // 2. Only handle HTTP/HTTPS requests (skip chrome-extension, blob, data, etc.)
  if (!request.url.startsWith('http://') && !request.url.startsWith('https://')) {
    return;
  }

  const url = new URL(request.url);

  // 3. Skip dev / hot reload / livewire polling requests
  if (
    url.pathname.startsWith('/@vite') ||
    url.pathname.startsWith('/__vite_ping') ||
    url.pathname.includes('/livewire/')
  ) {
    return;
  }

  // 4. Handle API requests with network-first and graceful offline JSON response
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(
      fetch(request).catch(() => {
        return new Response(
          JSON.stringify({
            success: false,
            message: 'Anda sedang offline. Periksa koneksi internet Anda.',
          }),
          {
            status: 503,
            statusText: 'Service Unavailable',
            headers: { 'Content-Type': 'application/json' },
          }
        );
      })
    );
    return;
  }

  // 5. Navigation requests: Network First, fallback to cached offline page
  if (isNavigation(request)) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          return response;
        })
        .catch(async () => {
          // Attempt to return cached offline page
          try {
            const cache = await caches.open(CACHE_NAME);
            const cachedOffline = await cache.match('/offline');
            if (cachedOffline) {
              return cachedOffline;
            }
          } catch (e) {
            console.warn('[PWA] Failed to retrieve cached offline page:', e);
          }

          // Fallback static offline HTML if offline view not available
          return new Response(
            '<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><title>Offline - Pelatihanku</title><meta name="viewport" content="width=device-width, initial-scale=1.0"><style>body{font-family:system-ui,-apple-system,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#1a1d29;color:#fff;text-align:center;padding:20px}.card{background:#232635;border:1px solid #2e3140;padding:2rem;border-radius:1rem;max-width:400px;width:100%}h1{font-size:1.5rem;margin-top:0;color:#7367f0}p{color:#8e92a4;line-height:1.5}button{background:#7367f0;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-weight:600;margin-top:1rem}</style></head><body><div class="card"><h1>Koneksi Terputus</h1><p>Anda sedang offline. Periksa koneksi internet Anda lalu coba lagi.</p><button onclick="window.location.reload()">Coba Lagi</button></div></body></html>',
            {
              status: 503,
              statusText: 'Service Unavailable',
              headers: { 'Content-Type': 'text/html; charset=utf-8' },
            }
          );
        })
    );
    return;
  }

  // 6. Static assets: Cache First, fallback to Network
  if (isStaticAsset(request)) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) {
          return cached;
        }

        return fetch(request)
          .then((response) => {
            // Only cache valid basic or CORS responses with status 200
            if (response && response.status === 200 && (response.type === 'basic' || response.type === 'cors')) {
              const clone = response.clone();
              caches.open(CACHE_NAME).then((cache) => {
                cache.put(request, clone).catch((err) => {
                  console.warn('[PWA] Failed to cache asset:', request.url, err);
                });
              }).catch(() => {});
            }
            return response;
          })
          .catch(() => {
            // Return placeholder for images when offline
            if (request.url.match(/\.(png|jpg|jpeg|gif|svg|webp|ico)$/i)) {
              return new Response(
                '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="#1a1d29" width="200" height="200"/><text fill="#7367f0" font-size="18" text-anchor="middle" x="100" y="105">Offline</text></svg>',
                { headers: { 'Content-Type': 'image/svg+xml' } }
              );
            }
            return new Response('', { status: 404, statusText: 'Not Found' });
          });
      })
    );
    return;
  }

  // 7. All other requests: let the browser handle naturally (no event.respondWith)
});

// Background sync for pending notifications
self.addEventListener('sync', (event) => {
  if (event.tag === 'sync-notifications') {
    event.waitUntil(syncPendingNotifications());
  }
});

// Function to sync pending notifications when back online
async function syncPendingNotifications() {
  try {
    const cache = await caches.open(CACHE_NAME);
    const pendingKey = 'pending-notifications';
    const cachedResponse = await cache.match(pendingKey);

    if (cachedResponse) {
      const pendingNotifications = await cachedResponse.json();
      console.log('[PWA] Syncing', pendingNotifications.length, 'pending notifications');

      for (const notification of pendingNotifications) {
        try {
          const response = await fetch('/notifications/sync', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': notification.csrf_token },
            body: JSON.stringify(notification.data),
          });
          if (response.ok) {
            console.log('[PWA] Notification synced successfully');
          }
        } catch (error) {
          console.warn('[PWA] Failed to sync notification:', error);
        }
      }

      // Clear pending notifications after sync
      await cache.delete(pendingKey);
    }
  } catch (error) {
    console.warn('[PWA] Error syncing notifications:', error);
  }
}

// ============================================================
// Push Notification Handlers (Web Push API)
// ============================================================

self.addEventListener('push', (event) => {
  let payload = {};

  if (event.data) {
    try {
      payload = event.data.json();
    } catch (e) {
      payload = {
        title: 'Pelatihanku',
        body: event.data.text(),
      };
    }
  }

  // Support both direct payload and nested "notification" payload
  const title = payload.title || payload.notification?.title || 'Pelatihanku';
  const body = payload.body || payload.notification?.body || 'Ada notifikasi baru untukmu.';
  const icon = payload.icon || payload.notification?.icon || '/icons/icon-192x192.png';
  const badge = payload.badge || '/icons/badge-72x72.png';
  const image = payload.image || payload.notification?.image || null;
  const tag = payload.tag || 'pelatihanku-push';
  const url = payload.url || payload.link_url || payload.data?.url || '/';

  const notificationOptions = {
    body,
    icon,
    badge,
    tag,
    requireInteraction: false,
    renotify: false,
    data: { url },
  };

  if (image) {
    notificationOptions.image = image;
  }

  event.waitUntil(
    self.registration.showNotification(title, notificationOptions)
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const urlToOpen = event.notification.data?.url || '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
      // If a matching client already exists, focus it
      for (const client of clientList) {
        if (client.url === urlToOpen && 'focus' in client) {
          return client.focus();
        }
      }

      // Otherwise open a new window/tab
      if (clients.openWindow) {
        return clients.openWindow(urlToOpen);
      }
    })
  );
});

// ============================================================
// Message handling from client
// ============================================================

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

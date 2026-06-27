/**
 * PWA Service Worker - Aplikasi Pelatihan
 * Version 1.0.0
 */

const CACHE_NAME = 'pelatihanku-pwa-v2';
const STATIC_ASSETS = [
  '/',
  '/offline',
  '/manifest.json',
  '/icons/icon.svg',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/icons/badge-72x72.png',
];

// Install event - cache static assets
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

// Helper: check if request is for an API endpoint
const isApiRequest = (request) => {
  const url = new URL(request.url);
  return url.pathname.startsWith('/api/');
};

// Fetch event - network first for HTML, cache first for assets
self.addEventListener('fetch', (event) => {
  const request = event.request;

  // Only handle GET requests
  if (request.method !== 'GET') return;

  // Skip non-HTTP(S) requests
  if (!request.url.startsWith('http')) return;

  // Handle API requests with network-only strategy
  if (isApiRequest(request)) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          return response;
        })
        .catch(() => {
          return new Response(
            JSON.stringify({ error: 'Anda sedang offline. Periksa koneksi internet Anda.' }),
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

  // Navigation requests: Network First, fallback to cache, then offline page
  if (isNavigation(request)) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache the latest version of this page
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, clone);
          });
          return response;
        })
        .catch(() => {
          return caches.match(request).then((cached) => {
            if (cached) return cached;
            // Return offline page
            return caches.match('/offline').then((offlinePage) => {
              return offlinePage || new Response(
                '<html><body><h1>Offline</h1><p>Koneksi terputus. Silakan coba lagi.</p></body></html>',
                { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
              );
            });
          });
        })
    );
    return;
  }

  // Static assets: Cache First, then network
  if (isStaticAsset(request)) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) return cached;
        return fetch(request).then((response) => {
          // Cache the new asset
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, clone);
          });
          return response;
        }).catch(() => {
          // Return a placeholder for failed assets
          if (request.url.match(/\.(png|jpg|jpeg|gif|svg|webp|ico)$/)) {
            return new Response(
              '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect fill="#1a1d29" width="200" height="200"/><text fill="#7367f0" font-size="20" text-anchor="middle" x="100" y="110">Offline</text></svg>',
              { headers: { 'Content-Type': 'image/svg+xml' } }
            );
          }
          return new Response('', { status: 404 });
        });
      })
    );
    return;
  }

  // Default: Network only for everything else
  event.respondWith(
    fetch(request).catch(() => {
      return caches.match(request);
    })
  );
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

const CACHE_NAME = 'ga-facility-pwa-v3';
const OFFLINE_URL = '/offline.html';
const APP_SHELL = [
    '/',
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/icons/icon-192.png',
    '/icons/icon-512.png',
    '/icons/icon-maskable-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(APP_SHELL))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_NAME)
                    .map((key) => caches.delete(key))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);
    const isNavigation = event.request.mode === 'navigate';
    const isSameOrigin = requestUrl.origin === self.location.origin;
    const isApiRequest = isSameOrigin && requestUrl.pathname.startsWith('/api/');

    if (isNavigation) {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
                    return response;
                })
                .catch(async () => {
                    const cachedPage = await caches.match(event.request);
                    return cachedPage || caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    if (!isSameOrigin) {
        return;
    }

    // Always fetch dynamic API data from network to avoid stale dashboard counts.
    if (isApiRequest) {
        event.respondWith(
            fetch(event.request, { cache: 'no-store' }).catch(() => {
                return new Response(
                    JSON.stringify({
                        error: 'offline',
                        message: 'Data live tidak tersedia saat offline.',
                    }),
                    {
                        status: 503,
                        headers: { 'Content-Type': 'application/json' },
                    }
                );
            })
        );
        return;
    }

    event.respondWith(
        caches.match(event.request).then((cached) => {
            if (cached) {
                return cached;
            }

            return fetch(event.request).then((response) => {
                const responseCopy = response.clone();
                caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseCopy));
                return response;
            });
        })
    );
});

self.addEventListener('push', (event) => {
    const payload = event.data ? event.data.json() : {};
    const title = payload.title || 'Notifikasi Baru';
    const options = {
        body: payload.body || 'Ada pembaruan baru dari sistem.',
        icon: payload.icon || '/icons/icon-192.png',
        badge: payload.badge || '/icons/icon-192.png',
        tag: payload.tag || 'ga-facility-notification',
        data: {
            url: payload.url || '/dashboard',
            ...payload.data,
        },
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const targetUrl = event.notification.data?.url || '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if ('focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }

            return undefined;
        })
    );
});

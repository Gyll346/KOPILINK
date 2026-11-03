const CACHE_NAME = 'kopilink-pwa-v5';
const PRECACHE_ASSETS = [
    '/',
    '/manifest.json',
    '/favicon.ico',
    '/img/logo.png',
    '/img/icon-192x192.png',
    '/img/icon-512x512.png',
    '/css/style.min.css',
    '/js/offline-sync.js'
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key)))
        ).then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;

    // Navigasi halaman HTML: Network first, fallback ke cache agar tetap di dalam PWA
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then((response) => {
                    if (response && response.status === 200) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    }
                    return response;
                })
                .catch(() => caches.match(event.request).then((cached) => cached || caches.match('/')))
        );
        return;
    }

    // Asset statis lokal: Cache first, network fallback
    if (event.request.url.startsWith(self.location.origin)) {
        event.respondWith(
            caches.match(event.request).then((cached) => {
                if (cached) return cached;
                return fetch(event.request).then((response) => {
                    if (response && response.status === 200) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                    }
                    return response;
                });
            })
        );
    }
});

// Background Sync API: Bangunkan client atau lakukan sinkronisasi antrean formulir offline
self.addEventListener('sync', (event) => {
    if (event.tag === 'kopilink-sync-forms') {
        event.waitUntil(
            self.clients.matchAll({ includeUncontrolled: true, type: 'window' }).then((clients) => {
                if (clients && clients.length > 0) {
                    clients.forEach((client) => client.postMessage({ type: 'SYNC_NOW' }));
                }
            })
        );
    }
});

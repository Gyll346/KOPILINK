const CACHE_NAME = 'kopilink-pwa-v3';
const PRECACHE_ASSETS = [
    '/',
    '/manifest.json',
    '/favicon.ico',
    '/img/logo.png',
    '/img/icon-32x32.png',
    '/img/icon-64x64.png',
    '/img/icon-128x128.png',
    '/img/icon-192x192.png',
    '/img/icon-256x256.png',
    '/img/icon-512x512.png',
    '/img/icon-maskable-192x192.png',
    '/img/icon-maskable-512x512.png',
    '/css/style.min.css'
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
    if (event.request.method !== 'GET' || !event.request.url.startsWith(self.location.origin)) {
        return;
    }
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseClone));
                }
                return response;
            })
            .catch(() => caches.match(event.request).then((cached) => cached || caches.match('/')))
    );
});

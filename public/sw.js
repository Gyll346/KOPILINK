const CACHE_NAME = 'kopilink-pwa-v6';
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
                .catch(async () => {
                    const cached = await caches.match(event.request);
                    if (cached) return cached;

                    const url = event.request.url;

                    // Jika berada di dalam rute dashboard petani, jangan lempar keluar ke homepage publik
                    if (url.includes('/petani/')) {
                        const dashCached = await caches.match('/petani/dashboard');
                        if (dashCached) return dashCached;

                        return new Response(
                            `<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Halaman Offline - KopiLink</title><link rel="stylesheet" href="/css/style.min.css"></head><body style="background:#251714;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center;padding:20px;font-family:sans-serif;"><div><div style="font-size:48px;margin-bottom:12px;">📡</div><h3 style="color:#DA9F5B;font-weight:bold;">Mode Offline</h3><p style="color:#bbb;max-width:380px;margin:12px auto;font-size:14px;">Halaman ini belum tersimpan di memori perangkat. Buka halaman ini sekali saat online agar dapat diakses tanpa sinyal.</p><a href="/petani/dashboard" style="background:#DA9F5B;color:#251714;font-weight:bold;padding:10px 24px;border-radius:6px;text-decoration:none;display:inline-block;margin-top:8px;">Buka Dashboard Petani</a></div></body></html>`,
                            { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                        );
                    }

                    // Halaman publik fallback ke '/'
                    return caches.match('/');
                })
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

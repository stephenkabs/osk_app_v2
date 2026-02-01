const CACHE_NAME = 'OSK-v1';
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll([
                OFFLINE_URL,
                '/icons/icon-192.png',
                '/icons/icon-512.png'
            ]);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(k => k !== CACHE_NAME)
                    .map(k => caches.delete(k))
            )
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    // Only handle navigation requests (pages)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then(response => response)
                .catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // For assets (css/js/images)
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});

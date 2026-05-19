const CACHE_NAME = 'ruang-hening-v1';
const urlsToCache = [
    '/images/logo.png',
    '/manifest.json'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

self.addEventListener('fetch', event => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Network successful - update cache if it's a static asset
                if (response.ok && event.request.url.match(/\.(css|js|png|jpg|jpeg|webp|svg)$/)) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Network failed - try to get from cache
                return caches.match(event.request);
            })
    );
});

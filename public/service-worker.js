var CACHE_NAME = 'buhurt-cache-v1';
var urlsToCache = [
    //'/',
    //'/data/bootstrap-4.0.0-dist/css/bootstrap.min.css',
    //'/data/bootstrap-star-rating/css/star-rating.min.css',
    //'/data/css/main.css',
    //'/data/js/jquery-2.1.1.min.js',
    //'/data/js/jquery-ui-1.12.1.custom/jquery-ui.min.js',
    //'/data/bootstrap-4.0.0-dist/js/bootstrap.min.js',
    //'/data/bootstrap-star-rating/js/star-rating.min.js',
    //'/data/js/main.js'
];

self.addEventListener('install', function(event) {
    // Perform install steps
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                console.log('Opened cache');
                return cache.addAll(urlsToCache);
            })
    );
});

/*
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                    // Cache hit - return response
                    if (response) {
                        return response;
                    }
                    return fetch(event.request);
                }
            )
    );
});
*/
self.addEventListener('fetch', function(event) {
    event.respondWith(
        fetch(event.request).catch(function() {
            return caches.match(event.request);
        })
    );
});

self.addEventListener('activate', function(event) {

    var cacheWhitelist = ['buhurt-cache-v1'];

    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

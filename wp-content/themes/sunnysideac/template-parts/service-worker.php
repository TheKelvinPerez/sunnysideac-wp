<?php
/**
 * Service Worker for Performance Optimization
 * Solves PostHog caching and other mobile performance issues
 */

// Set content type
header('Content-Type: application/javascript');
?>
const CACHE_NAME = 'sunnysideac-v<?php echo time(); ?>';
const STATIC_CACHE = 'sunnysideac-static-v1';

// Critical assets to cache immediately
const CRITICAL_ASSETS = [
    '/',
    '/dist/css/main.css',
    '/dist/js/main.js',
    '<?php echo get_template_directory_uri(); ?>/dist/css/main.css',
    '<?php echo get_template_directory_uri(); ?>/dist/js/main.js'
];

// PostHog assets to cache with longer TTL
const POSTHOG_ASSETS = [
    'https://us-assets.i.posthog.com/static/array.js',
    'https://us-assets.i.posthog.com/static/web-vitals.js',
    'https://us-assets.i.posthog.com/static/surveys.js',
    'https://eu-assets.i.posthog.com/static/array.js',
    'https://eu-assets.i.posthog.com/static/web-vitals.js',
    'https://eu-assets.i.posthog.com/static/surveys.js'
];

// Google Analytics assets
const GA_ASSETS = [
    'https://www.google-analytics.com/analytics.js',
    'https://www.googletagmanager.com/gtag/js'
];

// Install event - cache critical assets
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(function(cache) {
                return cache.addAll(CRITICAL_ASSETS);
            })
            .then(function() {
                // Skip waiting to activate immediately
                return self.skipWaiting();
            })
    );
});

// Fetch event - serve from cache with network fallback
self.addEventListener('fetch', function(event) {
    const url = new URL(event.request.url);

    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip Chrome extension requests
    if (url.protocol === 'chrome-extension:') {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Cache hit - return response
                if (response) {
                    // For PostHog assets, check if we need to refresh
                    if (POSTHOG_ASSETS.includes(url.href)) {
                        // Refresh in background every 24 hours
                        refreshPostHogAsset(event.request);
                    }
                    return response;
                }

                // Network request for non-cached content
                return fetch(event.request).then(function(response) {
                    // Don't cache non-successful responses
                    if (!response || response.status !== 200 || response.type !== 'basic') {
                        return response;
                    }

                    // Clone response for caching
                    const responseToCache = response.clone();

                    // Cache PostHog assets with long TTL
                    if (POSTHOG_ASSETS.includes(url.href) || GA_ASSETS.includes(url.href)) {
                        caches.open(STATIC_CACHE).then(function(cache) {
                            cache.put(event.request, responseToCache);
                        });
                    }

                    return response;
                });
            })
    );
});

// Refresh PostHog assets in background
function refreshPostHogAsset(request) {
    return fetch(request).then(function(response) {
        if (response.ok) {
            return caches.open(STATIC_CACHE).then(function(cache) {
                cache.put(request, response.clone());
            });
        }
    }).catch(function() {
        // Network failed, serve from cache
    });
}

// Activate event - clean up old caches
self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== STATIC_CACHE && cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(function() {
            // Take control of all pages
            return self.clients.claim();
        })
    );
});
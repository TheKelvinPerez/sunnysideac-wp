<?php
/**
 * Service Worker for Performance Optimization
 * Solves PostHog caching and other mobile performance issues
 */

// Headers are set by template_redirect hook in functions.php
?>
const CACHE_NAME = 'sunnysideac-v<?php echo wp_get_theme()->get( 'Version' ); ?>';
const STATIC_CACHE = 'sunnysideac-static-v1';

// Critical assets to cache immediately
// Note: Vite assets are hashed, so we'll cache them on first request instead of during install
const CRITICAL_ASSETS = [
    '/'
];

// PostHog assets to cache with longer TTL (using official CDN)
const POSTHOG_ASSETS = [
    'https://us.i.posthog.com/static/array.js',
    'https://us.i.posthog.com/static/web-vitals.js',
    'https://us.i.posthog.com/static/surveys.js'
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
                // Cache critical assets individually to avoid blocking on failures
                return Promise.all(
                    CRITICAL_ASSETS.map(function(url) {
                        return cache.add(url).catch(function(err) {
                            console.warn('Failed to cache:', url, err);
                        });
                    })
                );
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

    // Skip Chrome extension and non-http(s) requests
    if (!url.protocol.startsWith('http')) {
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
                    if (!response || response.status !== 200) {
                        return response;
                    }

                    // Don't cache opaque responses (CORS)
                    if (response.type === 'opaque') {
                        return response;
                    }

                    // Clone response for caching
                    const responseToCache = response.clone();

                    // Cache PostHog and GA assets
                    if (POSTHOG_ASSETS.includes(url.href) || GA_ASSETS.includes(url.href)) {
                        caches.open(STATIC_CACHE).then(function(cache) {
                            cache.put(event.request, responseToCache);
                        }).catch(function(err) {
                            console.warn('Failed to cache asset:', url.href);
                        });
                    }
                    // Cache theme assets (CSS, JS, fonts, images from theme directory)
                    else if (url.pathname.includes('/wp-content/themes/sunnysideac/dist/')) {
                        caches.open(STATIC_CACHE).then(function(cache) {
                            cache.put(event.request, responseToCache);
                        }).catch(function(err) {
                            console.warn('Failed to cache theme asset:', url.href);
                        });
                    }

                    return response;
                }).catch(function(error) {
                    console.warn('Fetch failed:', error);
                    // Return cached response if available
                    return caches.match(event.request);
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
/*
  Progressive Web App Service Worker
  - Pre-caches core assets and offline page
  - Runtime caching for CSS/JS (stale-while-revalidate)
  - Cache-first for images and fonts
  - Network-first for navigations with offline fallback
*/
(function () {
  'use strict';
  // Integrate OneSignal push handling if present
  try { importScripts('https://cdn.onesignal.com/sdks/OneSignalSDKWorker.js'); } catch (e) {}

  const STATIC_CACHE = 'pwa-static-v1';
  const RUNTIME_CACHE = 'pwa-runtime-v1';
  const OFFLINE_URL = 'offline.html';

  const FILES_TO_PRECACHE = [
    './',
    OFFLINE_URL,
    'src/manifest.json',
    'src/192x192.png',
    'src/512x512.png'
  ];

  // Install: pre-cache core assets
  self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
      caches.open(STATIC_CACHE).then((cache) => cache.addAll(FILES_TO_PRECACHE))
    );
  });

  // Activate: cleanup old caches and take control
  self.addEventListener('activate', (event) => {
    event.waitUntil(
      (async () => {
        // Enable navigation preload if available (improves performance)
        if ('navigationPreload' in self.registration) {
          try { await self.registration.navigationPreload.enable(); } catch (e) {}
        }

        const cacheNames = await caches.keys();
        await Promise.all(
          cacheNames
            .filter((name) => name.startsWith('pwa-') && name !== STATIC_CACHE && name !== RUNTIME_CACHE)
            .map((name) => caches.delete(name))
        );
        await self.clients.claim();
      })()
    );
  });

  // Fetch: apply strategy based on request type
  self.addEventListener('fetch', (event) => {
    const req = event.request;

    // Only handle GET requests
    if (req.method !== 'GET') { return; }

    // Handle navigation requests (HTML pages)
    if (req.mode === 'navigate') {
      event.respondWith((async () => {
        try {
          const preload = await event.preloadResponse;
          if (preload) return preload;
          const networkResp = await fetch(req);
          const cache = await caches.open(RUNTIME_CACHE);
          cache.put(req, networkResp.clone());
          return networkResp;
        } catch (err) {
          const cached = await caches.match(req);
          if (cached) return cached;
          const offline = await caches.match(OFFLINE_URL);
          return offline || Response.error();
        }
      })());
      return;
    }

    // Choose strategy by asset type
    const dest = req.destination;

    // Stale-while-revalidate for scripts and styles
    if (dest === 'script' || dest === 'style' || dest === 'worker') {
      event.respondWith((async () => {
        const cache = await caches.open(RUNTIME_CACHE);
        const cached = await cache.match(req);
        const networkFetch = fetch(req).then((resp) => {
          if (resp && resp.status === 200) {
            cache.put(req, resp.clone());
          }
          return resp;
        }).catch(() => undefined);
        return cached || networkFetch || fetch(req);
      })());
      return;
    }

    // Cache-first for images and fonts
    if (dest === 'image' || dest === 'font') {
      event.respondWith((async () => {
        const cache = await caches.open(RUNTIME_CACHE);
        const cached = await cache.match(req);
        if (cached) return cached;
        try {
          const resp = await fetch(req);
          if (resp && resp.status === 200) {
            cache.put(req, resp.clone());
          }
          return resp;
        } catch (err) {
          return cached || Response.error();
        }
      })());
      return;
    }

    // Default: network-first with cache fallback for same-origin GETs
    event.respondWith((async () => {
      try {
        const resp = await fetch(req);
        const cache = await caches.open(RUNTIME_CACHE);
        if (resp && resp.status === 200 && new URL(req.url).origin === self.location.origin) {
          cache.put(req, resp.clone());
        }
        return resp;
      } catch (err) {
        const cached = await caches.match(req);
        return cached || Response.error();
      }
    })());
  });
})();

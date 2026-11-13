// sw.js
self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open('rotas-store').then((cache) => {
      return cache.addAll([
        '/',
        '/index.php',
        '/clientes.php',
        '/assets/css/style.css',
        '/assets/js/rotas.js'
      ]);
    })
  );
});

self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((response) => {
      return response || fetch(e.request);
    })
  );
});
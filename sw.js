/* =====================================================================
   Service Worker · Fisioterapia BUAP (PWA)
   - Guarda en caché el diseño, imágenes e íconos (funciona sin internet).
   - Páginas: primero intenta la red (datos frescos de la BD); si no hay
     conexión muestra la copia guardada o la página offline.html.
   ===================================================================== */
const VERSION = "fisio-v3";
const ESTATICOS = [
  "offline.html",
  "assets/css/style.css?v=3",
  "assets/js/app.js?v=3",
  "assets/img/logo-web.png",
  "assets/img/fisio1.jpg",
  "assets/img/fisio1-sm.jpg",
  "assets/img/fisio2-sm.jpg",
  "assets/img/colab-sm.jpg",
  "assets/img/poster-video.jpg",
  "icons/icon-192.png",
  "icons/icon-512.png",
  "icons/favicon-64.png",
  "manifest.webmanifest",
];
// Páginas públicas que se pueden guardar para verlas sin conexión
const PUBLICAS = ["", "index.php", "plan.php", "campo.php"];

self.addEventListener("install", (ev) => {
  ev.waitUntil(caches.open(VERSION).then((c) => c.addAll(ESTATICOS)).then(() => self.skipWaiting()));
});

self.addEventListener("activate", (ev) => {
  ev.waitUntil(
    caches.keys()
      .then((claves) => Promise.all(claves.filter((k) => k !== VERSION).map((k) => caches.delete(k))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener("fetch", (ev) => {
  const req = ev.request;
  if (req.method !== "GET") return;                       // formularios POST siempre van a la red
  const url = new URL(req.url);
  if (/\.(mp4|mp3)$/i.test(url.pathname)) return;         // video/audio: directo a la red

  // Navegación entre páginas: red primero
  if (req.mode === "navigate") {
    const pagina = url.pathname.split("/").pop();
    ev.respondWith(
      fetch(req)
        .then((resp) => {
          if (resp.ok && PUBLICAS.includes(pagina) && !url.search) {
            const copia = resp.clone();
            caches.open(VERSION).then((c) => c.put(req, copia));
          }
          return resp;
        })
        .catch(() => caches.match(req).then((r) => r || caches.match("offline.html")))
    );
    return;
  }

  // Recursos (CSS, JS, imágenes, fuentes): caché y actualización en segundo plano
  const mismoOrigen = url.origin === self.location.origin;
  const esFuente = /fonts\.(googleapis|gstatic)\.com$/.test(url.hostname);
  if (!mismoOrigen && !esFuente) return;
  ev.respondWith(
    caches.match(req).then((guardado) => {
      const deRed = fetch(req)
        .then((resp) => {
          if (resp && (resp.ok || resp.type === "opaque")) {
            const copia = resp.clone();
            caches.open(VERSION).then((c) => c.put(req, copia));
          }
          return resp;
        })
        .catch(() => guardado);
      return guardado || deRed;
    })
  );
});

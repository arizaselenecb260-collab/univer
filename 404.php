<?php
http_response_code(404);
$titulo = 'Página no encontrada';
$pagina = '404';
require __DIR__ . '/includes/header.php';
?>
<section class="contenedor offline">
  <h1>404 · Esta página se lesionó 🩹</h1>
  <p>No encontramos lo que buscabas, pero podemos rehabilitar tu visita.</p>
  <div class="hero-btns centro">
    <a class="btn" href="index.php">Ir al inicio</a>
    <a class="btn btn-borde" href="plan.php">Plan de estudios</a>
  </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>

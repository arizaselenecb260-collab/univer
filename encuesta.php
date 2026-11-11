<?php
/* =====================================================================
   FORMULARIO DE SATISFACCIÓN · 10 preguntas con 3 opciones cada una
   Las respuestas se guardan en la tabla `encuestas`.
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';

$PREGUNTAS = [
    ['¿Qué tan clara fue la información sobre la carrera?',            ['😀 Muy clara', '🙂 Regular', '😕 Poco clara']],
    ['¿Cómo calificas el diseño del sitio web?',                        ['🤩 Excelente', '👍 Bueno', '🛠️ Mejorable']],
    ['¿Encontraste fácilmente el plan de estudios?',                    ['⚡ De inmediato', '🔎 Con algo de esfuerzo', '❌ No lo encontré']],
    ['¿Te fue útil la información del campo laboral en Puebla?',       ['💯 Muy útil', '👌 Algo útil', '🤷 Poco útil']],
    ['¿El video y el spot de radio te motivaron a conocer la carrera?', ['🔥 Mucho', '🙂 Un poco', '😐 Nada']],
    ['¿Qué tan fácil fue solicitar tu ficha de inscripción?',           ['✅ Muy fácil', '🆗 Normal', '😓 Difícil']],
    ['¿El sitio funcionó bien en tu celular?',                          ['📱 Perfecto', '👍 Con detalles', '🐞 Tuvo fallas']],
    ['¿La velocidad de carga de las páginas fue…?',                     ['🚀 Rápida', '🚶 Normal', '🐢 Lenta']],
    ['¿Después de visitar el sitio, qué tanto te interesa la carrera?', ['❤️ Mucho', '🤔 Lo pensaré', '👋 Poco']],
    ['¿Recomendarías este sitio a otros aspirantes?',                   ['🙌 Sí, seguro', '🤞 Tal vez', '🙅 No']],
];

$error = '';
$resp = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $nombre = mb_substr(post('nombre'), 0, 100) ?: 'Anónimo';
    $comentario = mb_substr(post('comentario'), 0, 500);
    foreach ($PREGUNTAS as $i => $_) {
        $v = (int)($_POST['p' . ($i + 1)] ?? 0);
        if ($v < 1 || $v > 3) { $error = 'Responde las 10 preguntas para enviar la encuesta.'; }
        $resp[] = $v;
    }
    if (!$error) {
        q('INSERT INTO encuestas (nombre, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, comentario, creado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
          array_merge([$nombre], $resp, [$comentario !== '' ? $comentario : null, ahora()]));
        flash('🎉 ¡Gracias por tu opinión! Mira cómo van los resultados.');
        redirigir('encuesta.php?enviada=1#resultados');
    }
}

// ---------- Resultados (consulta agregada) ----------
$cols = [];
for ($i = 1; $i <= 10; $i++) {
    for ($k = 1; $k <= 3; $k++) $cols[] = "SUM(CASE WHEN p$i = $k THEN 1 ELSE 0 END) AS p{$i}_$k";
}
$res = q('SELECT COUNT(*) AS total, ' . implode(', ', $cols) . ' FROM encuestas')->fetch();
$total = (int)$res['total'];
$positivas = 0;
for ($i = 1; $i <= 10; $i++) $positivas += (int)$res["p{$i}_1"];
$indice = $total ? round($positivas * 100 / ($total * 10)) : 0;
$comentarios = q("SELECT nombre, comentario, creado FROM encuestas WHERE comentario IS NOT NULL AND comentario <> '' ORDER BY id DESC LIMIT 4")->fetchAll();

$titulo = 'Encuesta de satisfacción';
$pagina = 'encuesta';
$banner = ['img' => 'assets/img/galeria/practica-grupal.jpg', 'etiqueta' => 'Tu opinión cuenta', 'titulo' => 'Encuesta de satisfacción', 'sub' => '10 preguntas · 3 opciones cada una · menos de 2 minutos'];
require __DIR__ . '/includes/header.php';
?>

<section class="contenedor encuesta-zona">
  <form method="post" class="encuesta" id="encuesta" novalidate>
    <?= csrf_campo() ?>
    <div class="enc-progreso" aria-hidden="true"><div><i id="encBarra"></i></div><span id="encTexto">0 / 10</span></div>
    <?php if ($error): ?><div class="alerta error" role="alert"><?= e($error) ?></div><?php endif; ?>

    <div class="enc-pasos" id="encPasos">
      <?php foreach ($PREGUNTAS as $i => [$texto, $ops]): $n = $i + 1; ?>
      <fieldset class="pregunta tarjeta" data-paso="<?= $n ?>">
        <legend><span class="nro"><?= $n ?></span><?= e($texto) ?></legend>
        <div class="opciones">
          <?php foreach ($ops as $k => $op): ?>
          <label class="opcion">
            <input type="radio" name="p<?= $n ?>" value="<?= $k + 1 ?>" required<?= (($resp[$i] ?? 0) === $k + 1) ? ' checked' : '' ?>>
            <span><?= e($op) ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </fieldset>
      <?php endforeach; ?>

      <fieldset class="pregunta tarjeta final" data-paso="11">
        <legend><span class="nro">✓</span>¡Último paso! (opcional)</legend>
        <label class="campo">Tu nombre<input name="nombre" placeholder="Anónimo" maxlength="100" autocomplete="given-name"></label>
        <label class="campo">Comentario o sugerencia<textarea name="comentario" rows="3" maxlength="500" placeholder="¿Qué mejorarías del sitio?"></textarea></label>
        <button class="btn btn-bloque" type="submit">Enviar encuesta</button>
      </fieldset>
    </div>

    <div class="enc-nav" id="encNav" hidden>
      <button type="button" class="btn btn-borde" id="encAnterior">← Anterior</button>
      <div class="enc-puntos" id="encPuntos"></div>
      <button type="button" class="btn" id="encSiguiente">Siguiente →</button>
    </div>
  </form>
</section>

<section class="contenedor" id="resultados">
  <div class="cabecera-sec">
    <h2 class="titulo-sec">Resultados en vivo</h2>
  </div>
  <div class="resumen-enc">
    <div class="tarjeta"><b class="contador" data-meta="<?= $total ?>"><?= $total ?></b><span>respuestas guardadas en la base de datos</span></div>
    <div class="tarjeta indice">
      <div class="dona" style="--p:<?= $indice ?>"><b><?= $indice ?>%</b></div>
      <span>índice de satisfacción<br><small>(respuestas con la opción más positiva)</small></span>
    </div>
  </div>

  <div class="grid-2 resultados-lista">
    <?php foreach ($PREGUNTAS as $i => [$texto, $ops]): $n = $i + 1; ?>
    <div class="tarjeta resultado">
      <h4><span class="nro"><?= $n ?></span><?= e($texto) ?></h4>
      <?php foreach ($ops as $k => $op): $c = (int)$res["p{$n}_" . ($k + 1)]; $pct = $total ? round($c * 100 / $total) : 0; ?>
        <div class="barra-res"><span><?= e($op) ?></span><div><i style="--w:<?= $pct ?>%"></i></div><b><?= $pct ?>%</b></div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if ($comentarios): ?>
  <h3 class="titulo-sec chico">Comentarios recientes</h3>
  <div class="grid-2">
    <?php foreach ($comentarios as $c): ?>
      <blockquote class="tarjeta comentario">“<?= e($c['comentario']) ?>”<cite>— <?= e($c['nombre']) ?> · <?= e(date('d/m/Y', strtotime($c['creado']))) ?></cite></blockquote>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

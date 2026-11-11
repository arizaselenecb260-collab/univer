<?php
$titulo = 'Plan de Estudios';
$pagina = 'plan';
$banner = ['img' => 'assets/img/fisio2.jpg', 'etiqueta' => 'Página 1 · Plan de estudios', 'titulo' => 'Plan de Estudios', 'sub' => '8 semestres · 342 créditos · formación básica, formativa, profesional y de integración'];
require __DIR__ . '/includes/header.php';

// CONSULTA (Read) de la base de datos
$materias = q('SELECT semestre, clave, nombre, area, creditos FROM materias ORDER BY semestre, clave')->fetchAll();
$porSem = [];
foreach ($materias as $m) {
    $porSem[(int)$m['semestre']][] = $m;
}
$areas = q('SELECT area, COUNT(*) AS n, SUM(creditos) AS cr FROM materias GROUP BY area ORDER BY cr DESC')->fetchAll();
$totalCr = array_sum(array_column($materias, 'creditos'));
$maxSem = $porSem ? max(array_map(fn($l) => array_sum(array_column($l, 'creditos')), $porSem)) : 1;
$slug = fn($a) => strtolower(strtr($a, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'Á' => 'a']));
?>

<section class="contenedor" id="semestres">
  <div class="cabecera-sec">
    <h2 class="titulo-sec">Materias por semestre</h2>
    <?php if (es_admin()): ?><a href="materias.php" class="btn btn-chico">🛠️ Administrar materias</a><?php endif; ?>
  </div>
  <p class="lead">Datos cargados en vivo desde la base de datos: <b><?= count($materias) ?> materias</b> y <b><?= (int)$totalCr ?> créditos</b>. Usa las pestañas, el buscador o filtra por área.</p>

  <div class="herramientas">
    <label class="buscador"><span aria-hidden="true">🔍</span><input type="search" id="buscaMateria" placeholder="Buscar materia o clave…" aria-label="Buscar materia"></label>
    <div class="filtros" id="filtrosArea" role="group" aria-label="Filtrar por área">
      <button class="chip-filtro activo" data-area="todas">Todas</button>
      <?php foreach ($areas as $a): ?>
        <button class="chip-filtro" data-area="<?= e($slug($a['area'])) ?>"><?= e($a['area']) ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="tabs" id="tabs" role="tablist">
    <?php for ($s = 1; $s <= 8; $s++): ?>
      <button class="tab<?= $s === 1 ? ' activo' : '' ?>" role="tab" data-sem="<?= $s ?>" aria-selected="<?= $s === 1 ? 'true' : 'false' ?>"><?= $s ?>.º</button>
    <?php endfor; ?>
  </div>

  <div id="paneles">
    <?php for ($s = 1; $s <= 8; $s++): $lista = $porSem[$s] ?? []; $suma = array_sum(array_column($lista, 'creditos')); ?>
    <div class="panel-sem<?= $s === 1 ? ' visible' : '' ?>" data-sem="<?= $s ?>" role="tabpanel">
      <h3 class="sem-titulo"><?= $s ?>.º semestre</h3>
      <div class="scroll-x tarjeta-tabla">
        <table class="tabla">
          <thead><tr><th>Clave</th><th>Materia</th><th>Área</th><th class="num">Créditos</th></tr></thead>
          <tbody>
          <?php if (!$lista): ?>
            <tr class="vacio"><td colspan="4">Sin materias registradas.</td></tr>
          <?php else: foreach ($lista as $m): ?>
            <tr data-area="<?= e($slug($m['area'])) ?>">
              <td><code><?= e($m['clave']) ?></code></td>
              <td><?= e($m['nombre']) ?></td>
              <td><span class="chip area-<?= e($slug($m['area'])) ?>"><?= e($m['area']) ?></span></td>
              <td class="num"><?= (int)$m['creditos'] ?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
          <?php if ($lista): ?><tfoot><tr><td colspan="3">Total del semestre</td><td class="num"><?= (int)$suma ?></td></tr></tfoot><?php endif; ?>
        </table>
      </div>
    </div>
    <?php endfor; ?>
  </div>
  <p class="nota">* Materias y claves de referencia para este proyecto escolar. El mapa curricular oficial se consulta en la Facultad de Medicina BUAP.</p>
</section>

<section class="contenedor dos-col">
  <div class="tarjeta revelar">
    <h3>Créditos por semestre</h3>
    <div class="grafica" role="img" aria-label="Gráfica de créditos por semestre">
      <?php for ($s = 1; $s <= 8; $s++): $suma = array_sum(array_column($porSem[$s] ?? [], 'creditos')); ?>
        <div class="g-col"><i style="--h:<?= round($suma / $maxSem * 100) ?>%"><b><?= (int)$suma ?></b></i><span><?= $s ?>.º</span></div>
      <?php endfor; ?>
    </div>
  </div>
  <div class="tarjeta revelar">
    <h3>Áreas de formación</h3>
    <table class="tabla tabla-simple">
      <thead><tr><th>Área</th><th class="num">Materias</th><th class="num">Créditos</th><th class="num">%</th></tr></thead>
      <tbody>
        <?php foreach ($areas as $a): ?>
        <tr><td><span class="chip area-<?= e($slug($a['area'])) ?>"><?= e($a['area']) ?></span></td><td class="num"><?= (int)$a['n'] ?></td><td class="num"><?= (int)$a['cr'] ?></td><td class="num"><?= $totalCr ? round($a['cr'] * 100 / $totalCr) : 0 ?>%</td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<section class="contenedor" id="ingreso">
  <h2 class="titulo-sec">Perfil de ingreso</h2>
  <div class="grid-3">
    <div class="tarjeta revelar">
      <h3>🧠 Conocimientos</h3>
      <ul class="lista-check">
        <li>Biología, química y física de bachillerato.</li>
        <li>Nociones de anatomía y del cuerpo humano.</li>
        <li>Matemáticas básicas y estadística.</li>
        <li>Comprensión lectora (también en inglés).</li>
      </ul>
    </div>
    <div class="tarjeta revelar">
      <h3>🛠️ Habilidades</h3>
      <ul class="lista-check">
        <li>Comunicación clara y escucha activa.</li>
        <li>Coordinación y destreza manual.</li>
        <li>Trabajo en equipo.</li>
        <li>Uso de herramientas digitales.</li>
      </ul>
    </div>
    <div class="tarjeta revelar">
      <h3>💚 Actitudes</h3>
      <ul class="lista-check">
        <li>Vocación de servicio y empatía.</li>
        <li>Paciencia y constancia.</li>
        <li>Respeto a la diversidad y a la discapacidad.</li>
        <li>Gusto por la actividad física.</li>
      </ul>
    </div>
  </div>

  <div class="tarjeta pasos revelar">
    <h3>Ruta para ingresar</h3>
    <ol class="lista-num">
      <li><b>Consulta la convocatoria</b> de admisión en el portal oficial de la BUAP.</li>
      <li><b>Regístrate</b> con tu CURP y elige Fisioterapia (Puebla o Teziutlán).</li>
      <li><b>Paga el derecho a examen</b> y descarga tu formato de asignación.</li>
      <li><b>Presenta el Examen General de Admisión</b> el día asignado.</li>
      <li><b>Consulta resultados</b> e inscríbete en las fechas indicadas.</li>
    </ol>
  </div>
</section>

<section class="contenedor" id="multimedia">
  <h2 class="titulo-sec">Video y spot de radio</h2>
  <div class="dos-col">
    <div class="video-marco revelar">
      <video controls preload="none" poster="assets/img/poster-video.jpg" playsinline>
        <source src="assets/media/promo_fisioterapia.mp4" type="video/mp4">
      </video>
    </div>
    <div class="revelar">
      <ul class="lista-media">
        <li><a href="assets/media/promo_fisioterapia.mp4" target="_blank" rel="noopener"><span>🎬</span><div><b>Video promocional y testimonio</b><small>MP4 · 30 s · 1280×720</small></div></a></li>
        <li><a href="assets/media/spot_fisioterapia.mp3" target="_blank" rel="noopener"><span>🎧</span><div><b>Spot de radio</b><small>MP3 · 24 s</small></div></a></li>
        <li><a href="https://www.youtube.com/@BUAPoficial" target="_blank" rel="noopener"><span>📺</span><div><b>Canal oficial BUAP</b><small>Más videos en YouTube</small></div></a></li>
      </ul>
      <audio controls preload="none" src="assets/media/spot_fisioterapia.mp3" class="audio-ancho"></audio>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

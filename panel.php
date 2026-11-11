<?php
/* =====================================================================
   PANEL DEL ADMINISTRADOR
   Resumen de la base de datos + datos del servidor (útil para las
   capturas del reporte técnico: muestra la conexión a la BD remota).
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';
requiere_login('admin');

$c = [
    'usuarios'   => (int)q('SELECT COUNT(*) FROM usuarios')->fetchColumn(),
    'aspirantes' => (int)q('SELECT COUNT(*) FROM aspirantes')->fetchColumn(),
    'materias'   => (int)q('SELECT COUNT(*) FROM materias')->fetchColumn(),
    'encuestas'  => (int)q('SELECT COUNT(*) FROM encuestas')->fetchColumn(),
];
$estados = q('SELECT estado, COUNT(*) AS n FROM aspirantes GROUP BY estado')->fetchAll(PDO::FETCH_KEY_PAIR);
$sedes   = q('SELECT sede, COUNT(*) AS n FROM aspirantes GROUP BY sede')->fetchAll(PDO::FETCH_KEY_PAIR);
$ultimas = q('SELECT id, nombre, sede, estado, creado FROM aspirantes ORDER BY id DESC LIMIT 6')->fetchAll();

$version = db()->getAttribute(PDO::ATTR_SERVER_VERSION);
$motor = DB_DRIVER === 'sqlite' ? 'SQLite (modo prueba)' : (stripos($version, 'maria') !== false ? 'MariaDB' : 'MySQL');

$titulo = 'Panel de control';
$pagina = 'panel';
require __DIR__ . '/includes/header.php';
?>

<section class="contenedor encabezado-admin">
  <div>
    <span class="etiqueta">Panel del administrador</span>
    <h1>Hola, <?= e(primer_nombre(usuario()['nombre'])) ?> 👋</h1>
    <p>Resumen en tiempo real de la base de datos del sitio.</p>
  </div>
  <div class="hero-btns">
    <a href="exportar.php" class="btn btn-borde">⬇ Exportar fichas (CSV)</a>
    <a href="materias.php" class="btn">🛠️ CRUD de materias</a>
  </div>
</section>

<section class="contenedor">
  <div class="kpis">
    <a href="registro.php#ficha" class="kpi"><span>📝</span><b class="contador" data-meta="<?= $c['aspirantes'] ?>"><?= $c['aspirantes'] ?></b><small>Fichas de aspirantes</small></a>
    <a href="materias.php" class="kpi"><span>📚</span><b class="contador" data-meta="<?= $c['materias'] ?>"><?= $c['materias'] ?></b><small>Materias</small></a>
    <a href="encuesta.php#resultados" class="kpi"><span>⭐</span><b class="contador" data-meta="<?= $c['encuestas'] ?>"><?= $c['encuestas'] ?></b><small>Encuestas</small></a>
    <div class="kpi"><span>👥</span><b class="contador" data-meta="<?= $c['usuarios'] ?>"><?= $c['usuarios'] ?></b><small>Usuarios</small></div>
  </div>
</section>

<section class="contenedor grid-3 panel-grid">
  <div class="tarjeta">
    <h3>Fichas por estado</h3>
    <?php foreach (['Pendiente', 'En revisión', 'Aceptada'] as $s): $n = (int)($estados[$s] ?? 0); $pct = $c['aspirantes'] ? round($n * 100 / $c['aspirantes']) : 0; ?>
      <div class="barra-res"><span><?= e($s) ?></span><div><i style="--w:<?= $pct ?>%"></i></div><b><?= $n ?></b></div>
    <?php endforeach; ?>
    <h3 class="mt">Fichas por sede</h3>
    <?php foreach (['Puebla (CU)', 'Teziutlán'] as $s): $n = (int)($sedes[$s] ?? 0); $pct = $c['aspirantes'] ? round($n * 100 / $c['aspirantes']) : 0; ?>
      <div class="barra-res"><span><?= e($s) ?></span><div><i style="--w:<?= $pct ?>%"></i></div><b><?= $n ?></b></div>
    <?php endforeach; ?>
  </div>

  <div class="tarjeta">
    <h3>Últimas fichas</h3>
    <ul class="lista-ultimas">
      <?php if (!$ultimas): ?><li>Sin fichas todavía.</li><?php endif; ?>
      <?php foreach ($ultimas as $f): ?>
        <li><a href="registro.php?editar=<?= (int)$f['id'] ?>#ficha"><b>#<?= (int)$f['id'] ?> <?= e($f['nombre']) ?></b><small><?= e($f['sede']) ?> · <?= e(date('d/m/Y H:i', strtotime($f['creado']))) ?></small></a><span class="estado estado-<?= e(strtolower(strtr($f['estado'], [' ' => '-', 'ó' => 'o']))) ?>"><?= e($f['estado']) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="tarjeta servidor">
    <h3>🖥️ Servidor y base de datos</h3>
    <dl>
      <dt>Estado</dt><dd><span class="punto-ok"></span> Conectado</dd>
      <dt>Motor</dt><dd><?= e($motor) ?> <?= e($version) ?></dd>
      <dt>Host de BD</dt><dd><code><?= e(DB_DRIVER === 'sqlite' ? 'archivo local' : DB_HOST) ?></code></dd>
      <dt>Base de datos</dt><dd><code><?= e(DB_DRIVER === 'sqlite' ? basename(DB_NAME) : DB_NAME) ?></code></dd>
      <dt>Servidor web</dt><dd><?= e($_SERVER['SERVER_SOFTWARE'] ?? 'desconocido') ?></dd>
      <dt>PHP</dt><dd><?= e(PHP_VERSION) ?></dd>
      <dt>Dominio</dt><dd><code><?= e($_SERVER['HTTP_HOST'] ?? '') ?></code></dd>
      <dt>Protocolo</dt><dd><?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' ? '🔒 HTTPS' : 'HTTP' ?></dd>
      <dt>Hora del servidor</dt><dd><?= e(date('d/m/Y H:i:s')) ?></dd>
    </dl>
    <p class="nota">Toma captura de este recuadro para el reporte técnico.</p>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

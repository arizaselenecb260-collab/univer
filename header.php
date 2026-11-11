<?php
/* Encabezado común. Antes de incluirlo define:
   $titulo (texto de la pestaña), $pagina (para marcar el menú activo),
   y opcionalmente $banner = ['img' => ..., 'titulo' => ..., 'sub' => ...]. */
require_once __DIR__ . '/auth.php';
$titulo      = $titulo ?? SITIO_NOMBRE;
$pagina      = $pagina ?? '';
$descripcion = $descripcion ?? 'Licenciatura en Fisioterapia BUAP: plan de estudios, perfil de egreso, campo laboral en Puebla, fichas de inscripción y encuesta.';
$u           = usuario();
$activo      = fn(string ...$p) => in_array($pagina, $p, true) ? ' activo' : '';
$aviso       = tomar_flash();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($titulo) ?> · Fisioterapia BUAP</title>
<meta name="description" content="<?= e($descripcion) ?>">
<meta name="theme-color" content="#003B5C">
<meta property="og:title" content="<?= e($titulo) ?> · Fisioterapia BUAP">
<meta property="og:description" content="<?= e($descripcion) ?>">
<meta property="og:image" content="assets/img/fisio1.jpg">
<link rel="manifest" href="manifest.webmanifest">
<link rel="icon" type="image/png" href="icons/favicon-64.png">
<link rel="apple-touch-icon" href="icons/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Fisio BUAP">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css?v=3">
<script>document.documentElement.classList.add('js');try{var t=localStorage.getItem('fisio:tema');if(t)document.documentElement.dataset.tema=t;}catch(e){}</script>
</head>
<body data-pagina="<?= e($pagina) ?>">
<a class="saltar" href="#contenido">Saltar al contenido</a>
<div class="progreso" id="progreso" aria-hidden="true"></div>
<div class="sin-red" id="sinRed" hidden>📡 Sin conexión · mostrando la versión guardada</div>

<header class="nav" id="nav">
  <a href="index.php" class="marca" aria-label="Inicio Fisioterapia BUAP">
    <img src="assets/img/logo-web.png" alt="" width="44" height="44">
    <span>Fisioterapia<b>BUAP</b></span>
  </a>

  <button class="hamburguesa" id="hamburguesa" aria-label="Abrir menú" aria-expanded="false" aria-controls="menu">
    <span></span><span></span><span></span>
  </button>

  <nav id="menu" aria-label="Menú principal">
    <ul class="menu">
      <li><a href="index.php" class="enlace<?= $activo('inicio') ?>">Inicio</a></li>

      <li class="dropdown">
        <button class="enlace<?= $activo('plan', 'materias') ?>" aria-expanded="false">Plan de Estudios <i class="flecha"></i></button>
        <ul class="submenu">
          <li><a href="plan.php#semestres"><span>📚</span>Materias por semestre</a></li>
          <li><a href="plan.php#ingreso"><span>🎒</span>Perfil de ingreso</a></li>
          <li><a href="plan.php#multimedia"><span>🎬</span>Video y spot de radio</a></li>
          <?php if (es_admin()): ?><li><a href="materias.php"><span>🛠️</span>Administrar materias (CRUD)</a></li><?php endif; ?>
        </ul>
      </li>

      <li class="dropdown">
        <button class="enlace<?= $activo('campo') ?>" aria-expanded="false">Egreso y Campo Laboral <i class="flecha"></i></button>
        <ul class="submenu">
          <li><a href="campo.php#egreso"><span>🎓</span>Perfil de egreso</a></li>
          <li><a href="campo.php#puebla"><span>🏥</span>Campo laboral en Puebla</a></li>
          <li><a href="campo.php#salarios"><span>💰</span>Ingresos aproximados</a></li>
          <li><a href="campo.php#social"><span>🤝</span>Compromiso social</a></li>
        </ul>
      </li>

      <li class="dropdown">
        <button class="enlace<?= $activo('registro', 'encuesta') ?>" aria-expanded="false">Registro e Inscripciones <i class="flecha"></i></button>
        <ul class="submenu">
          <li><a href="registro.php#requisitos"><span>📋</span>Requisitos y costos</a></li>
          <li><a href="registro.php#calendario"><span>🗓️</span>Calendario de admisión</a></li>
          <li><a href="registro.php#ficha"><span>📝</span>Solicitar ficha</a></li>
          <li><a href="encuesta.php"><span>⭐</span>Encuesta de satisfacción</a></li>
        </ul>
      </li>

      <?php if ($u): ?>
      <li class="dropdown cuenta">
        <button class="enlace btn-nav" aria-expanded="false"><span class="avatar"><?= e(mb_strtoupper(mb_substr($u['nombre'], 0, 1))) ?></span><?= e(primer_nombre($u['nombre'])) ?> <i class="flecha"></i></button>
        <ul class="submenu derecha">
          <li class="sub-info"><small><?= e($u['correo']) ?></small><b><?= $u['rol'] === 'admin' ? 'Administrador' : 'Aspirante' ?></b></li>
          <li><a href="registro.php#ficha"><span>📝</span>Mis fichas</a></li>
          <?php if (es_admin()): ?>
          <li><a href="panel.php"><span>📊</span>Panel de control</a></li>
          <li><a href="materias.php"><span>🛠️</span>CRUD de materias</a></li>
          <?php endif; ?>
          <li><a href="logout.php"><span>🚪</span>Cerrar sesión</a></li>
        </ul>
      </li>
      <?php else: ?>
      <li><a href="login.php" class="enlace btn-nav<?= $activo('login') ?>">Iniciar sesión</a></li>
      <?php endif; ?>
      <li><button class="tema" id="tema" aria-label="Cambiar a modo oscuro" title="Modo claro / oscuro">🌙</button></li>
    </ul>
  </nav>
</header>

<main id="contenido">
<div class="toasts" id="toasts" aria-live="polite">
  <?php if ($aviso): ?><div class="toast <?= e($aviso[1]) ?>" role="status"><?= e($aviso[0]) ?></div><?php endif; ?>
</div>

<?php if (!empty($banner)): ?>
<section class="banner">
  <img class="banner-img" src="<?= e($banner['img']) ?>" alt="" fetchpriority="high">
  <div class="banner-txt">
    <?php if (!empty($banner['etiqueta'])): ?><span class="etiqueta"><?= e($banner['etiqueta']) ?></span><?php endif; ?>
    <h1><?= e($banner['titulo']) ?></h1>
    <?php if (!empty($banner['sub'])): ?><p><?= e($banner['sub']) ?></p><?php endif; ?>
  </div>
</section>
<?php endif; ?>

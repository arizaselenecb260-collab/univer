<?php
/* =====================================================================
   INICIO DE SESIÓN (aspirantes y administrador)
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';

// Solo se permite regresar a páginas internas del sitio
$next = basename((string)($_GET['next'] ?? $_POST['next'] ?? 'registro.php'));
if (!preg_match('/^[a-z_]+\.php$/', $next)) $next = 'registro.php';

if (usuario()) redirigir($next);

$error = '';
$correo = '';
$_SESSION['intentos'] = $_SESSION['intentos'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $correo = strtolower(post('correo'));
    $pass = (string)($_POST['password'] ?? '');

    if ($_SESSION['intentos'] >= 5 && (time() - ($_SESSION['ultimo_intento'] ?? 0)) < 60) {
        $error = 'Demasiados intentos. Espera un minuto e inténtalo de nuevo.';
    } else {
        $fila = q('SELECT * FROM usuarios WHERE correo = ?', [$correo])->fetch();
        if ($fila && password_verify($pass, $fila['password_hash'])) {
            $_SESSION['intentos'] = 0;
            iniciar_sesion($fila);
            flash('👋 ¡Bienvenido(a), ' . primer_nombre($fila['nombre']) . '!');
            redirigir($fila['rol'] === 'admin' && $next === 'registro.php' ? 'panel.php' : $next);
        }
        $_SESSION['intentos']++;
        $_SESSION['ultimo_intento'] = time();
        $error = 'Correo o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
$pagina = 'login';
require __DIR__ . '/includes/header.php';
?>

<section class="acceso">
  <div class="acceso-foto">
    <img src="assets/img/galeria/ejercicio-guiado.jpg" alt="Fisioterapeuta realizando un estiramiento guiado">
    <div class="acceso-cita">
      <b>Portal de aspirantes</b>
      <p>Solicita tu ficha de inscripción, revisa su estado y actualiza tus datos.</p>
    </div>
  </div>

  <form method="post" class="formulario acceso-form" novalidate>
    <?= csrf_campo() ?>
    <input type="hidden" name="next" value="<?= e($next) ?>">
    <img src="assets/img/logo-web.png" alt="" class="acceso-logo" width="72" height="72">
    <h1>Iniciar sesión</h1>
    <p class="sub">Accede para solicitar tu ficha de inscripción.</p>

    <?php if ($error): ?><div class="alerta error" role="alert"><?= e($error) ?></div><?php endif; ?>

    <label class="campo">Correo electrónico
      <input type="email" name="correo" value="<?= e($correo) ?>" required autocomplete="username" autofocus placeholder="tucorreo@ejemplo.com">
    </label>
    <label class="campo">Contraseña
      <span class="con-ojo">
        <input type="password" name="password" required autocomplete="current-password" minlength="6" id="pass">
        <button type="button" class="ojo" data-ojo="pass" aria-label="Mostrar contraseña">👁️</button>
      </span>
    </label>
    <button class="btn btn-bloque">Entrar</button>

    <p class="centro">¿No tienes cuenta? <a href="crear_cuenta.php">Crea una aquí</a></p>

    <div class="demo">
      <b>Cuentas de prueba</b>
      <button type="button" class="demo-btn" data-correo="aspirante@buap.mx" data-pass="fisio2026">🎓 Aspirante<small>aspirante@buap.mx · fisio2026</small></button>
      <button type="button" class="demo-btn" data-correo="admin@buap.mx" data-pass="admin2026">🛠️ Administrador<small>admin@buap.mx · admin2026</small></button>
    </div>
  </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

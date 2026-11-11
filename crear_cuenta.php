<?php
/* =====================================================================
   CREAR CUENTA DE ASPIRANTE (INSERT en la tabla usuarios)
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';
if (usuario()) redirigir('registro.php#ficha');

$errores = [];
$nombre = $correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $nombre = post('nombre');
    $correo = strtolower(post('correo'));
    $pass   = (string)($_POST['password'] ?? '');
    $pass2  = (string)($_POST['password2'] ?? '');

    if (mb_strlen($nombre) < 5) $errores['nombre'] = 'Escribe tu nombre completo.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores['correo'] = 'Correo no válido.';
    elseif (q('SELECT id FROM usuarios WHERE correo = ?', [$correo])->fetch()) $errores['correo'] = 'Ese correo ya está registrado. Inicia sesión.';
    if (strlen($pass) < 8 || !preg_match('/\d/', $pass) || !preg_match('/[A-Za-z]/', $pass)) $errores['password'] = 'Mínimo 8 caracteres, con letras y números.';
    if ($pass !== $pass2) $errores['password2'] = 'Las contraseñas no coinciden.';
    if (empty($_POST['acepto'])) $errores['acepto'] = 'Debes aceptar el aviso de privacidad.';

    if (!$errores) {
        q('INSERT INTO usuarios (nombre, correo, password_hash, rol, creado) VALUES (?, ?, ?, ?, ?)',
          [$nombre, $correo, password_hash($pass, PASSWORD_DEFAULT), 'aspirante', ahora()]);
        $fila = q('SELECT * FROM usuarios WHERE correo = ?', [$correo])->fetch();
        iniciar_sesion($fila);
        flash('🎉 Cuenta creada. ¡Ya puedes solicitar tu ficha!');
        redirigir('registro.php#ficha');
    }
}

$err = fn($c) => isset($errores[$c]) ? '<small class="error-campo">' . e($errores[$c]) . '</small>' : '';
$titulo = 'Crear cuenta';
$pagina = 'login';
require __DIR__ . '/includes/header.php';
?>

<section class="acceso">
  <div class="acceso-foto">
    <img src="assets/img/galeria/balon-terapeutico.jpg" alt="Estudiantes practicando con balón terapéutico">
    <div class="acceso-cita">
      <b>Tu camino empieza aquí</b>
      <p>Con tu cuenta podrás registrar, consultar, editar y eliminar tus fichas.</p>
    </div>
  </div>

  <form method="post" class="formulario acceso-form" novalidate id="formCuenta">
    <?= csrf_campo() ?>
    <img src="assets/img/logo-web.png" alt="" class="acceso-logo" width="72" height="72">
    <h1>Crear cuenta</h1>
    <p class="sub">Regístrate como aspirante en menos de un minuto.</p>

    <label class="campo">Nombre completo<input name="nombre" value="<?= e($nombre) ?>" required autocomplete="name"><?= $err('nombre') ?></label>
    <label class="campo">Correo electrónico<input type="email" name="correo" value="<?= e($correo) ?>" required autocomplete="email"><?= $err('correo') ?></label>
    <label class="campo">Contraseña
      <span class="con-ojo"><input type="password" name="password" id="pass1" required minlength="8" autocomplete="new-password"><button type="button" class="ojo" data-ojo="pass1" aria-label="Mostrar contraseña">👁️</button></span>
      <span class="fuerza" aria-hidden="true"><i id="fuerzaBarra"></i></span>
      <small class="ayuda" id="fuerzaTexto">Mínimo 8 caracteres, con letras y números.</small>
      <?= $err('password') ?>
    </label>
    <label class="campo">Confirmar contraseña<input type="password" name="password2" required autocomplete="new-password"><?= $err('password2') ?></label>
    <label class="check"><input type="checkbox" name="acepto" value="1"> Acepto que mis datos se usen solo para este proyecto escolar.</label>
    <?= $err('acepto') ?>
    <button class="btn btn-bloque">Crear cuenta</button>
    <p class="centro">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
  </form>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

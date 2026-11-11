<?php
/* =====================================================================
   REGISTRO E INSCRIPCIONES
   CRUD completo de fichas de aspirantes:
     Insertar (crear) · Consultar (listar/buscar) · Actualizar · Eliminar
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';

$SEDES   = ['Puebla (CU)', 'Teziutlán'];
$TURNOS  = ['Matutino', 'Vespertino'];
$MEDIOS  = ['Redes sociales', 'Feria vocacional', 'Amigos o familia', 'Página web BUAP', 'Otro'];
$ESTADOS = ['Pendiente', 'En revisión', 'Aceptada'];

$u = usuario();
$errores = [];
$ficha = ['id' => 0, 'nombre' => $u['nombre'] ?? '', 'curp' => '', 'correo' => $u['correo'] ?? '', 'telefono' => '', 'fecha_nac' => '',
          'bachillerato' => '', 'promedio' => '', 'sede' => $SEDES[0], 'turno' => $TURNOS[0], 'medio' => $MEDIOS[0]];

/** Busca una ficha que el usuario actual tenga permiso de modificar. */
function ficha_propia(int $id): ?array
{
    $f = q('SELECT * FROM aspirantes WHERE id = ?', [$id])->fetch();
    if (!$f) return null;
    if (!es_admin() && (int)$f['usuario_id'] !== usuario()['id']) return null;
    return $f;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requiere_login();
    verificar_csrf();
    $accion = post('accion');

    // ---------- ELIMINAR ----------
    if ($accion === 'eliminar') {
        $f = ficha_propia((int)post('id'));
        if ($f) {
            q('DELETE FROM aspirantes WHERE id = ?', [$f['id']]);
            flash('🗑️ Ficha #' . $f['id'] . ' eliminada.', 'aviso');
        } else {
            flash('No puedes eliminar esa ficha.', 'error');
        }
        redirigir('registro.php#ficha');
    }

    // ---------- CAMBIAR ESTADO (solo administrador) ----------
    if ($accion === 'estado' && es_admin()) {
        $estado = post('estado');
        if (in_array($estado, $ESTADOS, true)) {
            q('UPDATE aspirantes SET estado = ?, actualizado = ? WHERE id = ?', [$estado, ahora(), (int)post('id')]);
            flash('Estado de la ficha #' . (int)post('id') . ' cambiado a «' . $estado . '».');
        }
        redirigir('registro.php#ficha');
    }

    // ---------- INSERTAR / ACTUALIZAR ----------
    if ($accion === 'crear' || $accion === 'actualizar') {
        $ficha = [
            'id'           => (int)post('id'),
            'nombre'       => post('nombre'),
            'curp'         => strtoupper(post('curp')),
            'correo'       => strtolower(post('correo')),
            'telefono'     => preg_replace('/\D/', '', post('telefono')),
            'fecha_nac'    => post('fecha_nac'),
            'bachillerato' => post('bachillerato'),
            'promedio'     => post('promedio'),
            'sede'         => post('sede'),
            'turno'        => post('turno'),
            'medio'        => post('medio'),
        ];

        // Validación del lado del servidor
        if (mb_strlen($ficha['nombre']) < 5 || !preg_match('/^[\p{L} .\'-]+$/u', $ficha['nombre'])) $errores['nombre'] = 'Escribe tu nombre completo (solo letras).';
        if (!preg_match('/^[A-Z]{4}\d{6}[HMX][A-Z]{5}[A-Z0-9]\d$/', $ficha['curp'])) $errores['curp'] = 'La CURP debe tener 18 caracteres con el formato oficial.';
        if (!filter_var($ficha['correo'], FILTER_VALIDATE_EMAIL)) $errores['correo'] = 'Correo electrónico no válido.';
        if (strlen($ficha['telefono']) !== 10) $errores['telefono'] = 'El teléfono debe tener 10 dígitos.';
        $fn = DateTime::createFromFormat('Y-m-d', $ficha['fecha_nac']);
        $edad = $fn ? $fn->diff(new DateTime())->y : 0;
        if (!$fn || $edad < 15 || $edad > 60) $errores['fecha_nac'] = 'Fecha de nacimiento no válida (de 15 a 60 años).';
        if (mb_strlen($ficha['bachillerato']) < 3) $errores['bachillerato'] = 'Indica tu bachillerato de procedencia.';
        if (!is_numeric($ficha['promedio']) || $ficha['promedio'] < 6 || $ficha['promedio'] > 10) $errores['promedio'] = 'El promedio debe estar entre 6.0 y 10.0.';
        if (!in_array($ficha['sede'], $SEDES, true))   $errores['sede']  = 'Elige una sede.';
        if (!in_array($ficha['turno'], $TURNOS, true)) $errores['turno'] = 'Elige un turno.';
        if (!in_array($ficha['medio'], $MEDIOS, true)) $errores['medio'] = 'Elige una opción.';

        $datos = [$ficha['nombre'], $ficha['curp'], $ficha['correo'], $ficha['telefono'], $ficha['fecha_nac'], $ficha['bachillerato'],
                  round((float)$ficha['promedio'], 2), $ficha['sede'], $ficha['turno'], $ficha['medio']];

        if (!$errores && $accion === 'crear') {
            q('INSERT INTO aspirantes (usuario_id, nombre, curp, correo, telefono, fecha_nac, bachillerato, promedio, sede, turno, medio, estado, creado)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array_merge([$u['id']], $datos, ['Pendiente', ahora()]));
            $folio = db()->lastInsertId();
            flash('✅ ¡Ficha registrada! Tu folio es #' . $folio . '.');
            redirigir('registro.php?folio=' . $folio . '#ficha');
        }
        if (!$errores && $accion === 'actualizar') {
            if (ficha_propia($ficha['id'])) {
                q('UPDATE aspirantes SET nombre=?, curp=?, correo=?, telefono=?, fecha_nac=?, bachillerato=?, promedio=?, sede=?, turno=?, medio=?, actualizado=? WHERE id=?',
                  array_merge($datos, [ahora(), $ficha['id']]));
                flash('✏️ Ficha #' . $ficha['id'] . ' actualizada.');
            } else {
                flash('No puedes modificar esa ficha.', 'error');
            }
            redirigir('registro.php#ficha');
        }
    }
}

// ---------- Cargar ficha para EDITAR ----------
if ($u && isset($_GET['editar']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $f = ficha_propia((int)$_GET['editar']);
    if ($f) {
        $ficha = $f;
    }
}

// ---------- CONSULTAR (listar con búsqueda) ----------
$fichas = [];
$buscar = trim((string)($_GET['q'] ?? ''));
$filtroEstado = in_array($_GET['estado'] ?? '', $ESTADOS, true) ? $_GET['estado'] : '';
if ($u) {
    $sql = 'SELECT * FROM aspirantes WHERE 1=1';
    $p = [];
    if (!es_admin()) { $sql .= ' AND usuario_id = ?'; $p[] = $u['id']; }
    if ($buscar !== '') { $sql .= ' AND (nombre LIKE ? OR correo LIKE ? OR curp LIKE ?)'; $like = '%' . $buscar . '%'; array_push($p, $like, $like, $like); }
    if ($filtroEstado !== '') { $sql .= ' AND estado = ?'; $p[] = $filtroEstado; }
    $sql .= ' ORDER BY id DESC';
    $fichas = q($sql, $p)->fetchAll();
}

$editando = (int)$ficha['id'] > 0;
$err = fn($c) => isset($errores[$c]) ? '<small class="error-campo">' . e($errores[$c]) . '</small>' : '';
$inv = fn($c) => isset($errores[$c]) ? ' aria-invalid="true"' : '';

$titulo = 'Registro e Inscripciones';
$pagina = 'registro';
$banner = ['img' => 'assets/img/galeria/banda-elastica.jpg', 'etiqueta' => 'Página 3 · Registro e inscripciones', 'titulo' => 'Registro e Inscripciones', 'sub' => 'Requisitos, costos, calendario y tu ficha de pre-registro en línea'];
require __DIR__ . '/includes/header.php';
?>

<section class="contenedor dos-col" id="requisitos">
  <div class="tarjeta revelar">
    <h2>📋 Requisitos de ingreso</h2>
    <ol class="lista-num">
      <li>Certificado de bachillerato o constancia con promedio.</li>
      <li>Acta de nacimiento y CURP actualizada.</li>
      <li>Identificación oficial con fotografía.</li>
      <li>Fotografía reciente de frente (formato digital).</li>
      <li>Comprobante de pago del derecho a examen.</li>
      <li>Certificado médico (se solicita al inscribirte).</li>
    </ol>
  </div>
  <div class="tarjeta revelar">
    <h2>💳 Costos de referencia</h2>
    <table class="tabla tabla-simple">
      <thead><tr><th>Concepto</th><th class="num">Monto (MXN)</th></tr></thead>
      <tbody>
        <tr><td>Derecho a examen de admisión*</td><td class="num">$750</td></tr>
        <tr><td>Inscripción semestral (cuota de referencia)</td><td class="num">Según tabulador BUAP</td></tr>
        <tr><td>Uniforme clínico (filipina y pantalón)</td><td class="num">$900 aprox.</td></tr>
        <tr><td>Ficha de pre-registro en este sitio</td><td class="num"><b>Gratis</b></td></tr>
      </tbody>
    </table>
    <p class="nota">* Monto del proceso de admisión 2025. Las cuotas pueden cambiar cada año; verifica en la convocatoria oficial.</p>
  </div>
</section>

<section class="contenedor" id="calendario">
  <h2 class="titulo-sec">Calendario de admisión</h2>
  <ul class="linea-tiempo">
    <li class="revelar"><b>Febrero – marzo</b><span>Publicación de la convocatoria y registro en línea</span></li>
    <li class="revelar"><b>Abril – mayo</b><span>Pago del derecho a examen y validación de documentos</span></li>
    <li class="revelar"><b>Junio</b><span>Aplicación del Examen General de Admisión</span></li>
    <li class="revelar"><b>Julio</b><span>Publicación de resultados en Autoservicios BUAP</span></li>
    <li class="revelar"><b>Agosto</b><span>Inscripción e inicio de clases (periodo otoño)</span></li>
  </ul>
  <p class="nota">Fechas aproximadas basadas en los procesos de admisión recientes. Consulta siempre la convocatoria oficial.</p>
</section>

<section class="contenedor" id="ficha">
  <h2 class="titulo-sec">Solicitar ficha de pre-registro</h2>

  <?php if (!$u): ?>
    <div class="bloqueo tarjeta revelar">
      <span class="icono">🔒</span>
      <h3>Inicia sesión para solicitar tu ficha</h3>
      <p>Así podrás consultar, actualizar o eliminar tus fichas cuando quieras.</p>
      <div class="hero-btns centro">
        <a href="login.php?next=registro.php" class="btn">Iniciar sesión</a>
        <a href="crear_cuenta.php" class="btn btn-borde">Crear cuenta</a>
      </div>
      <p class="nota">Cuenta de prueba: <code>aspirante@buap.mx</code> / <code>fisio2026</code></p>
    </div>
  <?php else: ?>

  <?php if (!empty($_GET['folio'])): ?>
    <div class="folio tarjeta">
      <span>🎉</span>
      <div><b>¡Listo! Folio #<?= (int)$_GET['folio'] ?></b><p>Guarda este número. Puedes editar tu ficha mientras su estado sea «Pendiente».</p></div>
      <button type="button" class="btn btn-chico btn-borde" onclick="window.print()">🖨️ Imprimir</button>
    </div>
  <?php endif; ?>

  <div class="crud">
    <form method="post" class="formulario tarjeta" id="formFicha" novalidate>
      <?= csrf_campo() ?>
      <input type="hidden" name="accion" value="<?= $editando ? 'actualizar' : 'crear' ?>">
      <input type="hidden" name="id" value="<?= (int)$ficha['id'] ?>">
      <h3><?= $editando ? '✏️ Actualizar ficha #' . (int)$ficha['id'] : '➕ Nueva ficha' ?></h3>

      <?php if ($errores): ?><div class="alerta error">Revisa los campos marcados en rojo.</div><?php endif; ?>

      <div class="campos">
        <label class="campo ancho">Nombre completo
          <input name="nombre" value="<?= e($ficha['nombre']) ?>" required minlength="5" maxlength="100" autocomplete="name"<?= $inv('nombre') ?>>
          <?= $err('nombre') ?>
        </label>
        <label class="campo">CURP
          <input name="curp" value="<?= e($ficha['curp']) ?>" required maxlength="18" pattern="[A-Za-z]{4}\d{6}[HMXhmx][A-Za-z]{5}[A-Za-z0-9]\d" class="mayus" placeholder="AAAA000000HPLXXX00"<?= $inv('curp') ?>>
          <small class="ayuda"><span id="curpCuenta">0</span>/18 caracteres</small>
          <?= $err('curp') ?>
        </label>
        <label class="campo">Fecha de nacimiento
          <input type="date" name="fecha_nac" value="<?= e($ficha['fecha_nac']) ?>" required<?= $inv('fecha_nac') ?>>
          <?= $err('fecha_nac') ?>
        </label>
        <label class="campo">Correo electrónico
          <input type="email" name="correo" value="<?= e($ficha['correo']) ?>" required autocomplete="email"<?= $inv('correo') ?>>
          <?= $err('correo') ?>
        </label>
        <label class="campo">Teléfono (10 dígitos)
          <input type="tel" name="telefono" value="<?= e($ficha['telefono']) ?>" required pattern="\d{10}" maxlength="10" inputmode="numeric" autocomplete="tel"<?= $inv('telefono') ?>>
          <?= $err('telefono') ?>
        </label>
        <label class="campo">Bachillerato de procedencia
          <input name="bachillerato" value="<?= e($ficha['bachillerato']) ?>" required list="listaBach" placeholder="Ej. CBTIS 260"<?= $inv('bachillerato') ?>>
          <datalist id="listaBach"><option value="CBTIS 260"><option value="Preparatoria BUAP"><option value="COBAEP"><option value="CONALEP"><option value="Bachillerato particular"></datalist>
          <?= $err('bachillerato') ?>
        </label>
        <label class="campo">Promedio: <output id="promSalida"><?= e($ficha['promedio'] ?: '8.5') ?></output>
          <input type="range" name="promedio" min="6" max="10" step="0.1" value="<?= e($ficha['promedio'] ?: '8.5') ?>" id="promedio"<?= $inv('promedio') ?>>
          <?= $err('promedio') ?>
        </label>
        <label class="campo">Sede
          <select name="sede"><?php foreach ($SEDES as $s): ?><option<?= $ficha['sede'] === $s ? ' selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
        </label>
        <fieldset class="campo">
          <legend>Turno</legend>
          <div class="segmentado">
            <?php foreach ($TURNOS as $t): ?>
              <label><input type="radio" name="turno" value="<?= e($t) ?>"<?= $ficha['turno'] === $t ? ' checked' : '' ?>><span><?= $t === 'Matutino' ? '☀️' : '🌙' ?> <?= e($t) ?></span></label>
            <?php endforeach; ?>
          </div>
        </fieldset>
        <label class="campo">¿Cómo te enteraste?
          <select name="medio"><?php foreach ($MEDIOS as $m): ?><option<?= $ficha['medio'] === $m ? ' selected' : '' ?>><?= e($m) ?></option><?php endforeach; ?></select>
        </label>
      </div>

      <div class="form-btns">
        <button class="btn" type="submit"><?= $editando ? 'Guardar cambios' : 'Registrar ficha' ?></button>
        <?php if ($editando): ?><a href="registro.php#ficha" class="btn btn-borde">Cancelar</a><?php endif; ?>
      </div>
    </form>

    <div class="tarjeta lista-fichas">
      <div class="cabecera-sec">
        <h3><?= es_admin() ? '📂 Todas las fichas' : '📂 Mis fichas' ?> <span class="contador-chip"><?= count($fichas) ?></span></h3>
      </div>
      <form method="get" class="filtro-form" action="registro.php#ficha">
        <input type="search" name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre, correo o CURP…" aria-label="Buscar ficha">
        <select name="estado" aria-label="Filtrar por estado"><option value="">Todos los estados</option><?php foreach ($ESTADOS as $s): ?><option<?= $filtroEstado === $s ? ' selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?></select>
        <button class="btn btn-chico">Buscar</button>
      </form>

      <?php if (!$fichas): ?>
        <p class="vacio-msg"><?= $buscar || $filtroEstado ? 'Ninguna ficha coincide con tu búsqueda.' : 'Aún no tienes fichas. ¡Registra la primera con el formulario!' ?></p>
      <?php else: ?>
      <div class="scroll-x">
        <table class="tabla tabla-compacta">
          <thead><tr><th>Folio</th><th>Aspirante</th><th>Datos</th><th>Estado</th><th>Acciones</th></tr></thead>
          <tbody>
          <?php foreach ($fichas as $f): $clase = strtolower(strtr($f['estado'], [' ' => '-', 'ó' => 'o'])); ?>
            <tr>
              <td><b>#<?= (int)$f['id'] ?></b><br><small><?= e(date('d/m/Y', strtotime($f['creado']))) ?></small></td>
              <td><b><?= e($f['nombre']) ?></b><br><small><?= e($f['correo']) ?></small><br><small><code><?= e($f['curp']) ?></code></small></td>
              <td><small>📍 <?= e($f['sede']) ?> · <?= e($f['turno']) ?><br>🎓 <?= e($f['bachillerato']) ?> · <?= e(number_format((float)$f['promedio'], 1)) ?><br>📞 <?= e($f['telefono']) ?></small></td>
              <td>
                <?php if (es_admin()): ?>
                  <form method="post" class="form-estado">
                    <?= csrf_campo() ?><input type="hidden" name="accion" value="estado"><input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                    <select name="estado" class="estado-sel estado-<?= e($clase) ?>" data-auto-submit aria-label="Estado de la ficha <?= (int)$f['id'] ?>">
                      <?php foreach ($ESTADOS as $s): ?><option<?= $f['estado'] === $s ? ' selected' : '' ?>><?= e($s) ?></option><?php endforeach; ?>
                    </select>
                  </form>
                <?php else: ?>
                  <span class="estado estado-<?= e($clase) ?>"><?= e($f['estado']) ?></span>
                <?php endif; ?>
              </td>
              <td class="acciones">
                <a class="btn btn-chico" href="registro.php?editar=<?= (int)$f['id'] ?>#ficha">Editar</a>
                <form method="post" data-confirmar="¿Eliminar la ficha #<?= (int)$f['id'] ?> de <?= e($f['nombre']) ?>?">
                  <?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                  <button class="btn btn-chico btn-peligro">Eliminar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

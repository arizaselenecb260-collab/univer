<?php
/* =====================================================================
   CATÁLOGO DE MATERIAS · CRUD (solo administrador)
   ===================================================================== */
require_once __DIR__ . '/includes/auth.php';
requiere_login('admin');

$AREAS = ['Básica', 'Formativa', 'Profesional', 'Integración'];
$errores = [];
$m = ['id' => 0, 'semestre' => 1, 'clave' => '', 'nombre' => '', 'area' => $AREAS[0], 'creditos' => 6];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $accion = post('accion');

    if ($accion === 'eliminar') {
        $fila = q('SELECT nombre FROM materias WHERE id = ?', [(int)post('id')])->fetch();
        q('DELETE FROM materias WHERE id = ?', [(int)post('id')]);
        flash('🗑️ Materia «' . ($fila['nombre'] ?? '') . '» eliminada.', 'aviso');
        redirigir('materias.php');
    }

    if ($accion === 'crear' || $accion === 'actualizar') {
        $m = [
            'id'       => (int)post('id'),
            'semestre' => (int)post('semestre'),
            'clave'    => strtoupper(post('clave')),
            'nombre'   => post('nombre'),
            'area'     => post('area'),
            'creditos' => (int)post('creditos'),
        ];
        if ($m['semestre'] < 1 || $m['semestre'] > 8) $errores['semestre'] = 'Semestre de 1 a 8.';
        if (!preg_match('/^[A-Z]{2,4}-\d{3}$/', $m['clave'])) $errores['clave'] = 'Formato: FIS-101';
        if (mb_strlen($m['nombre']) < 3) $errores['nombre'] = 'Escribe el nombre de la materia.';
        if (!in_array($m['area'], $AREAS, true)) $errores['area'] = 'Elige un área.';
        if ($m['creditos'] < 1 || $m['creditos'] > 15) $errores['creditos'] = 'Créditos de 1 a 15.';
        $dup = q('SELECT id FROM materias WHERE clave = ? AND id <> ?', [$m['clave'], $m['id']])->fetch();
        if ($dup) $errores['clave'] = 'Ya existe una materia con esa clave.';

        if (!$errores) {
            if ($accion === 'crear') {
                q('INSERT INTO materias (semestre, clave, nombre, area, creditos) VALUES (?, ?, ?, ?, ?)', [$m['semestre'], $m['clave'], $m['nombre'], $m['area'], $m['creditos']]);
                flash('✅ Materia «' . $m['nombre'] . '» agregada.');
            } else {
                q('UPDATE materias SET semestre=?, clave=?, nombre=?, area=?, creditos=? WHERE id=?', [$m['semestre'], $m['clave'], $m['nombre'], $m['area'], $m['creditos'], $m['id']]);
                flash('✏️ Materia «' . $m['nombre'] . '» actualizada.');
            }
            redirigir('materias.php?sem=' . $m['semestre']);
        }
    }
}

if (isset($_GET['editar']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $fila = q('SELECT * FROM materias WHERE id = ?', [(int)$_GET['editar']])->fetch();
    if ($fila) $m = $fila;
}

$sem = (int)($_GET['sem'] ?? 0);
$buscar = trim((string)($_GET['q'] ?? ''));
$sql = 'SELECT * FROM materias WHERE 1=1';
$p = [];
if ($sem >= 1 && $sem <= 8) { $sql .= ' AND semestre = ?'; $p[] = $sem; }
if ($buscar !== '') { $sql .= ' AND (nombre LIKE ? OR clave LIKE ?)'; array_push($p, "%$buscar%", "%$buscar%"); }
$lista = q($sql . ' ORDER BY semestre, clave', $p)->fetchAll();
$total = (int)q('SELECT COUNT(*) FROM materias')->fetchColumn();

$editando = (int)$m['id'] > 0;
$err = fn($c) => isset($errores[$c]) ? '<small class="error-campo">' . e($errores[$c]) . '</small>' : '';
$slug = fn($a) => strtolower(strtr($a, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'Á' => 'a']));

$titulo = 'Administrar materias';
$pagina = 'materias';
require __DIR__ . '/includes/header.php';
?>

<section class="contenedor encabezado-admin">
  <div>
    <span class="etiqueta">Panel del administrador</span>
    <h1>Catálogo de materias</h1>
    <p>Insertar, consultar, actualizar y eliminar materias del plan de estudios. Hay <b><?= $total ?></b> materias en la base de datos.</p>
  </div>
  <a href="plan.php" class="btn btn-borde">Ver plan público →</a>
</section>

<section class="contenedor crud">
  <form method="post" class="formulario tarjeta" novalidate>
    <?= csrf_campo() ?>
    <input type="hidden" name="accion" value="<?= $editando ? 'actualizar' : 'crear' ?>">
    <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
    <h3><?= $editando ? '✏️ Editar materia' : '➕ Nueva materia' ?></h3>
    <?php if ($errores): ?><div class="alerta error">Revisa los campos marcados.</div><?php endif; ?>
    <div class="campos">
      <label class="campo">Clave<input name="clave" value="<?= e($m['clave']) ?>" required placeholder="FIS-101" class="mayus" maxlength="8"><?= $err('clave') ?></label>
      <label class="campo">Semestre
        <select name="semestre"><?php for ($s = 1; $s <= 8; $s++): ?><option value="<?= $s ?>"<?= (int)$m['semestre'] === $s ? ' selected' : '' ?>><?= $s ?>.º semestre</option><?php endfor; ?></select><?= $err('semestre') ?>
      </label>
      <label class="campo ancho">Nombre de la materia<input name="nombre" value="<?= e($m['nombre']) ?>" required maxlength="120"><?= $err('nombre') ?></label>
      <label class="campo">Área
        <select name="area"><?php foreach ($AREAS as $a): ?><option<?= $m['area'] === $a ? ' selected' : '' ?>><?= e($a) ?></option><?php endforeach; ?></select><?= $err('area') ?>
      </label>
      <label class="campo">Créditos<input type="number" name="creditos" value="<?= (int)$m['creditos'] ?>" min="1" max="15" required><?= $err('creditos') ?></label>
    </div>
    <div class="form-btns">
      <button class="btn"><?= $editando ? 'Guardar cambios' : 'Agregar materia' ?></button>
      <?php if ($editando): ?><a href="materias.php" class="btn btn-borde">Cancelar</a><?php endif; ?>
    </div>
  </form>

  <div class="tarjeta lista-fichas">
    <form method="get" class="filtro-form">
      <input type="search" name="q" value="<?= e($buscar) ?>" placeholder="Buscar materia o clave…" aria-label="Buscar materia">
      <select name="sem" aria-label="Filtrar por semestre"><option value="0">Todos los semestres</option><?php for ($s = 1; $s <= 8; $s++): ?><option value="<?= $s ?>"<?= $sem === $s ? ' selected' : '' ?>><?= $s ?>.º</option><?php endfor; ?></select>
      <button class="btn btn-chico">Filtrar</button>
    </form>
    <div class="scroll-x">
      <table class="tabla tabla-compacta">
        <thead><tr><th>Sem.</th><th>Clave</th><th>Materia</th><th>Área</th><th class="num">Cr.</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (!$lista): ?>
          <tr class="vacio"><td colspan="6">No hay materias con ese filtro.</td></tr>
        <?php else: foreach ($lista as $fila): ?>
          <tr>
            <td><?= (int)$fila['semestre'] ?>.º</td>
            <td><code><?= e($fila['clave']) ?></code></td>
            <td><?= e($fila['nombre']) ?></td>
            <td><span class="chip area-<?= e($slug($fila['area'])) ?>"><?= e($fila['area']) ?></span></td>
            <td class="num"><?= (int)$fila['creditos'] ?></td>
            <td class="acciones">
              <a class="btn btn-chico" href="materias.php?editar=<?= (int)$fila['id'] ?>">Editar</a>
              <form method="post" data-confirmar="¿Eliminar la materia «<?= e($fila['nombre']) ?>»?">
                <?= csrf_campo() ?><input type="hidden" name="accion" value="eliminar"><input type="hidden" name="id" value="<?= (int)$fila['id'] ?>">
                <button class="btn btn-chico btn-peligro">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

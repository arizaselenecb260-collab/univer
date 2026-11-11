<?php
/* =====================================================================
   SESIÓN, SEGURIDAD Y FUNCIONES DE APOYO
   ===================================================================== */
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_name('FISIOSESS');
    session_start();
}

/** Escapa texto para imprimirlo en HTML (evita XSS). */
function e($texto): string
{
    return htmlspecialchars((string)($texto ?? ''), ENT_QUOTES, 'UTF-8');
}

function redirigir(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/* ---------- Mensajes "flash" (se muestran una sola vez) ---------- */
function flash(string $mensaje, string $tipo = 'ok'): void
{
    $_SESSION['flash'] = [$mensaje, $tipo];
}

function tomar_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Token CSRF para todos los formularios POST ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function verificar_csrf(): void
{
    $enviado = $_POST['csrf'] ?? '';
    if (!is_string($enviado) || !hash_equals(csrf_token(), $enviado)) {
        flash('La sesión del formulario expiró. Inténtalo de nuevo.', 'error');
        redirigir($_SERVER['REQUEST_URI'] ?? 'index.php');
    }
}

/* ---------- Usuario actual ---------- */
function usuario(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function es_admin(): bool
{
    return (usuario()['rol'] ?? '') === 'admin';
}

function requiere_login(?string $rol = null): array
{
    $u = usuario();
    if (!$u) {
        flash('Inicia sesión para continuar.', 'aviso');
        redirigir('login.php?next=' . urlencode(basename($_SERVER['SCRIPT_NAME'])));
    }
    if ($rol !== null && $u['rol'] !== $rol) {
        flash('Esa sección es solo para la coordinación (administrador).', 'error');
        redirigir('index.php');
    }
    return $u;
}

function iniciar_sesion(array $fila): void
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = [
        'id'     => (int)$fila['id'],
        'nombre' => $fila['nombre'],
        'correo' => $fila['correo'],
        'rol'    => $fila['rol'],
    ];
}

/** Lee un campo de texto del POST, recortado. */
function post(string $campo): string
{
    $v = $_POST[$campo] ?? '';
    return is_string($v) ? trim($v) : '';
}

/** Primer nombre para saludar. */
function primer_nombre(string $nombre): string
{
    return explode(' ', trim($nombre))[0] ?? $nombre;
}

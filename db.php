<?php
/* =====================================================================
   CONEXIÓN A LA BASE DE DATOS (PDO)
   Todas las consultas usan sentencias preparadas → protege contra
   inyección SQL.
   ===================================================================== */
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        if (DB_DRIVER === 'sqlite') {
            $pdo = new PDO('sqlite:' . DB_NAME, null, null, $opciones);
        } else {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        }
    } catch (PDOException $ex) {
        error_log('Error de conexión: ' . $ex->getMessage());
        http_response_code(500);
        $titulo = 'Sin conexión a la base de datos';
        include __DIR__ . '/error_db.php';
        exit;
    }
    return $pdo;
}

/** Ejecuta una consulta preparada y devuelve el PDOStatement. */
function q(string $sql, array $params = []): PDOStatement
{
    $st = db()->prepare($sql);
    $st->execute($params);
    return $st;
}

function ahora(): string
{
    return date('Y-m-d H:i:s');
}

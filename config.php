<?php
/* =====================================================================
   CONFIGURACIÓN DE LA BASE DE DATOS
   ---------------------------------------------------------------------
   El sitio busca los datos de conexión en este orden:
     1) Variables que Clever Cloud crea solas al enlazar el add-on MySQL
        con la app PHP (MYSQL_ADDON_HOST, MYSQL_ADDON_DB, ...).
     2) Variables genéricas DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_PORT
        (Railway, Render, etc.).
     3) Valores locales para XAMPP (localhost, root, sin contraseña).
   Así el MISMO archivo funciona en tu compu y en la nube, y la
   contraseña no queda escrita en el código ni en GitHub.
   ===================================================================== */

function cfg(array $claves, string $porDefecto): string
{
    foreach ($claves as $clave) {
        $v = getenv($clave);
        if ($v === false || $v === '') {
            $v = $_SERVER[$clave] ?? $_ENV[$clave] ?? '';
        }
        if ($v !== '') {
            return (string)$v;
        }
    }
    return $porDefecto;
}

define('DB_HOST', cfg(['MYSQL_ADDON_HOST', 'DB_HOST'], 'localhost'));
define('DB_PORT', cfg(['MYSQL_ADDON_PORT', 'DB_PORT'], '3306'));
define('DB_NAME', cfg(['MYSQL_ADDON_DB', 'DB_NAME'], 'fisioterapia_buap'));
define('DB_USER', cfg(['MYSQL_ADDON_USER', 'DB_USER'], 'root'));
define('DB_PASS', cfg(['MYSQL_ADDON_PASSWORD', 'DB_PASS'], ''));

// 'mysql' para XAMPP y la nube. ('sqlite' solo se usa para pruebas rápidas sin MySQL.)
define('DB_DRIVER', cfg(['DB_DRIVER'], 'mysql'));

define('SITIO_NOMBRE', 'Fisioterapia BUAP');
date_default_timezone_set('America/Mexico_City');

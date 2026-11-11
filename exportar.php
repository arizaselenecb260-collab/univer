<?php
/* Exporta todas las fichas a un archivo CSV que abre Excel (solo administrador). */
require_once __DIR__ . '/includes/auth.php';
requiere_login('admin');

$filas = q('SELECT id, nombre, curp, correo, telefono, fecha_nac, bachillerato, promedio, sede, turno, medio, estado, creado FROM aspirantes ORDER BY id')->fetchAll();

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="fichas_fisioterapia_' . date('Y-m-d') . '.csv"');
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF"); // BOM para que Excel respete los acentos
fputcsv($out, ['Folio', 'Nombre', 'CURP', 'Correo', 'Teléfono', 'Fecha de nacimiento', 'Bachillerato', 'Promedio', 'Sede', 'Turno', 'Medio', 'Estado', 'Registrada']);
foreach ($filas as $f) {
    fputcsv($out, array_values($f));
}
fclose($out);

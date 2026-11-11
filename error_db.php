<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sin conexión a la base de datos</title>
<style>
  body{font-family:system-ui,sans-serif;background:#F3F7FA;color:#12324A;display:grid;place-items:center;min-height:100vh;margin:0;padding:20px}
  .caja{background:#fff;max-width:560px;border-radius:20px;padding:32px;box-shadow:0 10px 40px rgba(0,59,92,.12)}
  h1{margin-top:0;color:#003B5C}code{background:#E8F3F6;padding:2px 6px;border-radius:6px}
  li{margin:6px 0}
</style>
</head>
<body>
<div class="caja">
  <h1>⚠️ No se pudo conectar a MySQL</h1>
  <p>El sitio está bien, pero la base de datos no responde. Revisa:</p>
  <ol>
    <li>En XAMPP, que <b>Apache</b> y <b>MySQL</b> estén en verde (Start).</li>
    <li>Que exista la base <code>fisioterapia_buap</code> y que importaste <code>database/fisioterapia_buap.sql</code>.</li>
    <li>En la nube, que los datos de <code>includes/config.php</code> (host, base, usuario y contraseña) sean los de tu panel.</li>
  </ol>
  <p><a href="index.php">Volver a intentar</a></p>
</div>
</body>
</html>

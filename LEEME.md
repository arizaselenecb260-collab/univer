# Fisioterapia BUAP · Sitio web con PHP + MySQL (PWA)

Proyecto Final **Construye Aplicaciones Web** · CBTIS 260 · Grupo “B”

## ¿Qué hay en esta carpeta?

| Archivo / carpeta | Para qué sirve |
|---|---|
| `index.php` | Inicio: misión, visión, test vocacional interactivo, video, audio, galería y preguntas frecuentes |
| `plan.php` | **Página 1 · Plan de estudios** (materias leídas de la BD, pestañas por semestre, buscador, gráfica de créditos, perfil de ingreso, multimedia) |
| `campo.php` | **Página 2 · Perfil de egreso y campo laboral** (mapa corporal interactivo, tabla de empleos en Puebla, salarios, compromiso social con datos del INEGI) |
| `registro.php` | **Página 3 · Registro e inscripciones** (requisitos, costos, calendario y **CRUD de fichas de aspirantes**) |
| `encuesta.php` | Formulario de satisfacción: **10 preguntas × 3 opciones**, guardado en MySQL y con resultados en vivo |
| `login.php` · `crear_cuenta.php` · `logout.php` | Inicio de sesión de aspirantes y administrador (contraseñas cifradas con `password_hash`) |
| `materias.php` | **CRUD de materias** (solo administrador) |
| `panel.php` · `exportar.php` | Panel del administrador con datos del servidor y la BD; exporta fichas a CSV/Excel |
| `includes/` | Conexión PDO, sesión, seguridad (CSRF, XSS) y encabezado/pie comunes |
| `database/fisioterapia_buap.sql` | **Respaldo de la base de datos** para importar en phpMyAdmin |
| `assets/` | CSS, JavaScript, imágenes, íconos de redes, video MP4 y spot MP3 |
| `manifest.webmanifest` · `sw.js` · `offline.html` | Archivos de la **PWA** (se instala como app y funciona sin conexión) |

## Cuentas de prueba

| Rol | Correo | Contraseña |
|---|---|---|
| Aspirante | `aspirante@buap.mx` | `fisio2026` |
| Administrador | `admin@buap.mx` | `admin2026` |

## Dónde está cada operación CRUD

| Operación | Fichas de aspirantes (`registro.php`) | Materias (`materias.php`) |
|---|---|---|
| **Insertar** | Formulario «Nueva ficha» → `INSERT INTO aspirantes` | «Nueva materia» → `INSERT INTO materias` |
| **Consultar** | Lista «Mis fichas» con buscador y filtro → `SELECT` | Tabla con buscador y filtro por semestre |
| **Actualizar** | Botón «Editar» → `UPDATE aspirantes` (y cambio de estado por el admin) | Botón «Editar» → `UPDATE materias` |
| **Eliminar** | Botón «Eliminar» con ventana de confirmación → `DELETE` | Botón «Eliminar» → `DELETE` |

## Correrlo en XAMPP (resumen)

1. Copia la carpeta `fisioterapia-buap` a `C:\xampp\htdocs\`.
2. Abre XAMPP y presiona **Start** en Apache y MySQL.
3. Entra a `http://localhost/phpmyadmin` → **Nueva** → nombre `fisioterapia_buap` → **Crear**.
4. Pestaña **Importar** → elige `database/fisioterapia_buap.sql` → **Continuar**.
5. Abre `http://localhost/fisioterapia-buap/`.

Los pasos completos para subirlo a la nube están en `../3_Documentacion/GUIA_DESPLIEGUE.md`.

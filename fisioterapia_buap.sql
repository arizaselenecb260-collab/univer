-- =====================================================================
--  Base de datos: fisioterapia_buap
--  Proyecto Final "Construye Aplicaciones Web" · CBTIS 260 · Grupo B
--  Sitio: Licenciatura en Fisioterapia BUAP
--
--  CÓMO IMPORTAR
--  · XAMPP (local): abre http://localhost/phpmyadmin → "Nueva" → crea la base
--    "fisioterapia_buap" (utf8mb4_unicode_ci) → pestaña "Importar" → elige este archivo.
--  · InfinityFree (nube): Control Panel → MySQL Databases → crea la base
--    (queda como if0_XXXXXXX_fisio) → phpMyAdmin → Importar → este archivo.
--
--  Tablas: usuarios · materias · aspirantes · encuestas
--  Cuentas de prueba:
--    admin@buap.mx      / admin2026   (administrador: CRUD de materias y de todas las fichas)
--    aspirante@buap.mx  / fisio2026   (aspirante: CRUD de sus propias fichas)
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS encuestas;
DROP TABLE IF EXISTS aspirantes;
DROP TABLE IF EXISTS materias;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- 1. USUARIOS (inicio de sesión)
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre        VARCHAR(100) NOT NULL,
  correo        VARCHAR(120) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol           VARCHAR(15)  NOT NULL DEFAULT 'aspirante',   -- 'aspirante' | 'admin'
  creado        DATETIME     NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_correo (correo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. MATERIAS (plan de estudios · CRUD del administrador)
-- ---------------------------------------------------------------------
CREATE TABLE materias (
  id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  semestre  TINYINT UNSIGNED NOT NULL,
  clave     VARCHAR(12)  NOT NULL,
  nombre    VARCHAR(120) NOT NULL,
  area      VARCHAR(20)  NOT NULL,     -- Básica | Formativa | Profesional | Integración
  creditos  TINYINT UNSIGNED NOT NULL DEFAULT 6,
  PRIMARY KEY (id),
  UNIQUE KEY uq_materias_clave (clave),
  KEY idx_materias_semestre (semestre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. ASPIRANTES (fichas de pre-registro · CRUD)
-- ---------------------------------------------------------------------
CREATE TABLE aspirantes (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id    INT UNSIGNED NOT NULL,
  nombre        VARCHAR(100) NOT NULL,
  curp          CHAR(18)     NOT NULL,
  correo        VARCHAR(120) NOT NULL,
  telefono      VARCHAR(15)  NOT NULL,
  fecha_nac     DATE         NOT NULL,
  bachillerato  VARCHAR(120) NOT NULL,
  promedio      DECIMAL(4,2) NOT NULL,
  sede          VARCHAR(30)  NOT NULL DEFAULT 'Puebla (CU)',
  turno         VARCHAR(12)  NOT NULL DEFAULT 'Matutino',
  medio         VARCHAR(30)  NOT NULL DEFAULT 'Redes sociales',
  estado        VARCHAR(15)  NOT NULL DEFAULT 'Pendiente',   -- Pendiente | En revisión | Aceptada
  creado        DATETIME     NOT NULL,
  actualizado   DATETIME     NULL,
  PRIMARY KEY (id),
  KEY idx_aspirantes_usuario (usuario_id),
  CONSTRAINT fk_aspirantes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. ENCUESTAS (formulario de satisfacción: 10 preguntas × 3 opciones)
--    Cada pN guarda 1, 2 o 3 (la opción elegida).
-- ---------------------------------------------------------------------
CREATE TABLE encuestas (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre     VARCHAR(100) NOT NULL DEFAULT 'Anónimo',
  p1 TINYINT UNSIGNED NOT NULL, p2 TINYINT UNSIGNED NOT NULL, p3 TINYINT UNSIGNED NOT NULL,
  p4 TINYINT UNSIGNED NOT NULL, p5 TINYINT UNSIGNED NOT NULL, p6 TINYINT UNSIGNED NOT NULL,
  p7 TINYINT UNSIGNED NOT NULL, p8 TINYINT UNSIGNED NOT NULL, p9 TINYINT UNSIGNED NOT NULL,
  p10 TINYINT UNSIGNED NOT NULL,
  comentario VARCHAR(500) NULL,
  creado     DATETIME NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  DATOS INICIALES
-- =====================================================================
INSERT INTO usuarios (nombre, correo, password_hash, rol, creado) VALUES
('Coordinación Fisioterapia', 'admin@buap.mx', '$2y$10$a0b79Xpz1oR6D7Q3L0szR.TVkHUnlSG9e4JE6H8YUufIKj5P8mO2G', 'admin', '2026-10-20 09:00:00'),
('Valeria Hernández López', 'aspirante@buap.mx', '$2y$10$ADTEUBo6WojafUq3E5hlR.cm2vV5LYfB7DkknybN1GMZ2rn/GTkym', 'aspirante', '2026-10-21 10:30:00');

-- 48 materias de referencia (8 semestres · 342 créditos)
INSERT INTO materias (semestre, clave, nombre, area, creditos) VALUES
(1, 'FIS-101', 'Anatomía humana I', 'Básica', 8),
(1, 'FIS-102', 'Fisiología humana', 'Básica', 8),
(1, 'FIS-103', 'Bioquímica', 'Básica', 7),
(1, 'FIS-104', 'Biología celular e histología', 'Básica', 7),
(1, 'FIS-105', 'Introducción a la fisioterapia', 'Formativa', 6),
(1, 'FIS-106', 'Formación humana y social', 'Formativa', 6),
(2, 'FIS-201', 'Anatomía humana II (aparato locomotor)', 'Básica', 8),
(2, 'FIS-202', 'Fisiología del ejercicio', 'Básica', 8),
(2, 'FIS-203', 'Biomecánica', 'Básica', 8),
(2, 'FIS-204', 'Física aplicada a la fisioterapia', 'Básica', 6),
(2, 'FIS-205', 'Psicología de la salud', 'Formativa', 6),
(2, 'FIS-206', 'Lengua extranjera I', 'Formativa', 4),
(3, 'FIS-301', 'Kinesiología', 'Formativa', 8),
(3, 'FIS-302', 'Agentes físicos I (termo y electroterapia)', 'Formativa', 8),
(3, 'FIS-303', 'Valoración fisioterapéutica', 'Formativa', 8),
(3, 'FIS-304', 'Patología general', 'Básica', 7),
(3, 'FIS-305', 'Bioestadística', 'Básica', 6),
(3, 'FIS-306', 'Lengua extranjera II', 'Formativa', 4),
(4, 'FIS-401', 'Terapia manual', 'Profesional', 8),
(4, 'FIS-402', 'Ejercicio terapéutico', 'Profesional', 8),
(4, 'FIS-403', 'Agentes físicos II', 'Formativa', 7),
(4, 'FIS-404', 'Farmacología aplicada', 'Básica', 6),
(4, 'FIS-405', 'Metodología de la investigación', 'Formativa', 6),
(4, 'FIS-406', 'Desarrollo de habilidades digitales', 'Formativa', 5),
(5, 'FIS-501', 'Fisioterapia en ortopedia y traumatología', 'Profesional', 9),
(5, 'FIS-502', 'Fisioterapia cardiorrespiratoria', 'Profesional', 8),
(5, 'FIS-503', 'Imagenología para fisioterapeutas', 'Formativa', 6),
(5, 'FIS-504', 'Ergonomía y salud ocupacional', 'Profesional', 7),
(5, 'FIS-505', 'Bioética y deontología', 'Formativa', 5),
(5, 'FIS-506', 'Prácticas clínicas I', 'Integración', 8),
(6, 'FIS-601', 'Fisioterapia neurológica', 'Profesional', 9),
(6, 'FIS-602', 'Fisioterapia deportiva', 'Profesional', 8),
(6, 'FIS-603', 'Hidroterapia', 'Formativa', 7),
(6, 'FIS-604', 'Fisioterapia pediátrica', 'Profesional', 8),
(6, 'FIS-605', 'Salud pública y epidemiología', 'Formativa', 6),
(6, 'FIS-606', 'Prácticas clínicas II', 'Integración', 8),
(7, 'FIS-701', 'Fisioterapia geriátrica', 'Profesional', 8),
(7, 'FIS-702', 'Fisioterapia en ginecología y obstetricia', 'Profesional', 7),
(7, 'FIS-703', 'Prótesis, órtesis y ayudas técnicas', 'Profesional', 6),
(7, 'FIS-704', 'Telerehabilitación', 'Profesional', 6),
(7, 'FIS-705', 'Administración de servicios de fisioterapia', 'Formativa', 7),
(7, 'FIS-706', 'Prácticas clínicas III', 'Integración', 9),
(8, 'FIS-801', 'Práctica clínica integral', 'Integración', 12),
(8, 'FIS-802', 'Seminario de tesis', 'Integración', 8),
(8, 'FIS-803', 'Fisioterapia comunitaria', 'Profesional', 7),
(8, 'FIS-804', 'Optativa disciplinaria', 'Profesional', 7),
(8, 'FIS-805', 'Emprendimiento en salud', 'Formativa', 5),
(8, 'FIS-806', 'Práctica profesional', 'Integración', 8);

INSERT INTO aspirantes (usuario_id, nombre, curp, correo, telefono, fecha_nac, bachillerato, promedio, sede, turno, medio, estado, creado) VALUES
(2, 'Valeria Hernández López', 'HELV080315MPLRPLA4', 'aspirante@buap.mx', '2221234567', '2008-03-15', 'CBTIS 260', 9.10, 'Puebla (CU)', 'Matutino', 'Redes sociales', 'En revisión', '2026-10-28 11:15:00');

INSERT INTO encuestas (nombre, p1, p2, p3, p4, p5, p6, p7, p8, p9, p10, comentario, creado) VALUES
('Luis', 1, 1, 1, 2, 1, 1, 1, 2, 1, 1, 'Me gustó mucho el video.', '2026-10-29 12:00:00'),
('Anónimo', 1, 2, 1, 1, 2, 1, 2, 1, 1, 1, NULL, '2026-10-30 16:20:00'),
('Ana', 2, 1, 2, 1, 1, 1, 1, 1, 2, 1, 'Agregar más fotos de laboratorios.', '2026-10-31 09:45:00');

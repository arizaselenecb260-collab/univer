<?php
$titulo = 'Perfil de Egreso y Campo Laboral';
$pagina = 'campo';
$banner = ['img' => 'assets/img/fisio1.jpg', 'etiqueta' => 'Página 2 · Egreso y campo laboral', 'titulo' => 'Perfil de Egreso y Campo Laboral', 'sub' => 'Lo que serás capaz de hacer y dónde podrás trabajar en Puebla'];
require __DIR__ . '/includes/header.php';
?>

<section class="contenedor" id="egreso">
  <h2 class="titulo-sec">Perfil de egreso</h2>
  <p class="lead">Al terminar la licenciatura serás un profesional de la salud capaz de:</p>
  <div class="grid-3">
    <div class="mini-ico revelar"><span>🩺</span><h3>Evaluar</h3><p>Realizar el diagnóstico funcional del movimiento, la postura y el dolor.</p></div>
    <div class="mini-ico revelar"><span>💪</span><h3>Tratar</h3><p>Aplicar ejercicio terapéutico, terapia manual y agentes físicos.</p></div>
    <div class="mini-ico revelar"><span>🧠</span><h3>Rehabilitar</h3><p>Atender pacientes neurológicos, ortopédicos, pediátricos y geriátricos.</p></div>
    <div class="mini-ico revelar"><span>🏃</span><h3>Prevenir</h3><p>Diseñar programas de salud en escuelas, empresas y clubes deportivos.</p></div>
    <div class="mini-ico revelar"><span>📊</span><h3>Investigar</h3><p>Desarrollar proyectos científicos basados en evidencia.</p></div>
    <div class="mini-ico revelar"><span>🏥</span><h3>Emprender</h3><p>Administrar su propio consultorio, clínica o servicio a domicilio.</p></div>
  </div>
</section>

<!-- ============ MAPA CORPORAL INTERACTIVO ============ -->
<section class="contenedor">
  <div class="cuerpo tarjeta revelar">
    <div>
      <span class="etiqueta">Interactivo</span>
      <h2>¿Qué atiende un fisioterapeuta?</h2>
      <p>Toca una zona del cuerpo para conocer la especialidad.</p>
      <div class="cuerpo-info" id="cuerpoInfo" aria-live="polite">
        <h3>👆 Elige una zona</h3>
        <p>Cada punto corresponde a un área de la fisioterapia que estudiarás en la carrera.</p>
      </div>
    </div>
    <svg class="silueta" viewBox="0 0 200 400" role="group" aria-label="Silueta humana con zonas seleccionables">
      <g class="cuerpo-base">
        <circle cx="100" cy="45" r="28"/>
        <rect x="70" y="78" width="60" height="120" rx="26"/>
        <rect x="38" y="84" width="22" height="110" rx="11" transform="rotate(8 49 84)"/>
        <rect x="140" y="84" width="22" height="110" rx="11" transform="rotate(-8 151 84)"/>
        <rect x="74" y="190" width="24" height="175" rx="12"/>
        <rect x="102" y="190" width="24" height="175" rx="12"/>
      </g>
      <g class="zonas">
        <circle class="zona" tabindex="0" cx="100" cy="45" r="12" data-t="Neurológica" data-d="Rehabilitación después de un evento vascular cerebral, lesión medular, Parkinson o parálisis cerebral."/>
        <circle class="zona" tabindex="0" cx="100" cy="115" r="12" data-t="Cardiorrespiratoria" data-d="Recuperación de la capacidad pulmonar y cardiaca, por ejemplo después de COVID-19, cirugías o infarto."/>
        <circle class="zona" tabindex="0" cx="148" cy="100" r="11" data-t="Deportiva" data-d="Lesiones de hombro, esguinces y desgarres; regreso seguro a la cancha y prevención de lesiones."/>
        <circle class="zona" tabindex="0" cx="100" cy="175" r="11" data-t="Ergonomía y columna" data-d="Dolor lumbar, postura y salud ocupacional en industria y oficinas (gran demanda en la industria automotriz de Puebla)."/>
        <circle class="zona" tabindex="0" cx="86" cy="280" r="12" data-t="Ortopedia y traumatología" data-d="Fracturas, prótesis de rodilla o cadera, cirugías de ligamentos y artrosis."/>
        <circle class="zona" tabindex="0" cx="114" cy="350" r="10" data-t="Geriátrica" data-d="Equilibrio, prevención de caídas y movilidad del adulto mayor para que conserve su independencia."/>
      </g>
    </svg>
  </div>
</section>

<section class="contenedor" id="puebla">
  <h2 class="titulo-sec">Campo laboral en Puebla</h2>
  <p class="lead">Puebla tiene <b>6 583 278 habitantes</b> y es el <b>5.º estado más poblado</b> del país (INEGI, Censo 2020). Su fuerte industria automotriz, la actividad deportiva y una población que envejece hacen que la demanda de fisioterapeutas crezca cada año.</p>
  <div class="scroll-x tarjeta-tabla">
    <table class="tabla">
      <thead><tr><th>Sector</th><th>Dónde trabajar en Puebla</th><th>Puesto</th></tr></thead>
      <tbody>
        <tr><td>🏛️ Salud pública</td><td>IMSS, ISSSTE, IMSS-Bienestar, Hospital Universitario de Puebla (BUAP)</td><td>Fisioterapeuta clínico</td></tr>
        <tr><td>♿ Rehabilitación</td><td>CRIT Teletón Puebla, Centro Estatal de Rehabilitación del DIF, Unidades Básicas de Rehabilitación municipales</td><td>Neurorrehabilitación, pediatría</td></tr>
        <tr><td>🏥 Hospitales privados</td><td>Hospitales y clínicas privadas de la zona metropolitana</td><td>Fisioterapeuta, coordinador de área</td></tr>
        <tr><td>⚽ Deporte</td><td>Clubes profesionales, equipos universitarios, gimnasios y centros de alto rendimiento</td><td>Fisioterapeuta deportivo</td></tr>
        <tr><td>🏭 Industria</td><td>Plantas automotrices y de autopartes de la región</td><td>Ergonomía y salud ocupacional</td></tr>
        <tr><td>💼 Emprendimiento</td><td>Consultorio propio, atención a domicilio, telerehabilitación</td><td>Independiente</td></tr>
      </tbody>
    </table>
  </div>
</section>

<section class="contenedor" id="salarios">
  <h2 class="titulo-sec">Ingresos aproximados</h2>
  <div class="salarios">
    <div class="revelar"><small>Recién egresado</small><b>$9,000 – $13,000</b><span>mensuales</span></div>
    <div class="revelar"><small>3 a 5 años de experiencia</small><b>$14,000 – $22,000</b><span>mensuales</span></div>
    <div class="revelar"><small>Especialista / consultorio propio</small><b>$25,000 – $40,000+</b><span>mensuales</span></div>
  </div>
  <p class="nota">Estimación en pesos mexicanos con base en vacantes publicadas en portales de empleo; varía según institución, ciudad y especialidad.</p>
</section>

<section class="contenedor dos-col" id="social">
  <img src="assets/img/galeria/practica-grupal.jpg" alt="Estudiantes de fisioterapia practicando ejercicios de rehabilitación" class="foto-redonda revelar" loading="lazy">
  <div class="revelar">
    <span class="etiqueta">Compromiso social</span>
    <h2>Fisioterapia que llega a quien más la necesita</h2>
    <p>La fisioterapia ayuda a que las personas recuperen su independencia y regresen al trabajo, a la escuela y con su familia. En Puebla la necesidad es real:</p>
    <div class="datos-reales">
      <div><b>300 150</b><span>poblanos viven con alguna discapacidad (4.6 % de la población)</span></div>
      <div><b>139 174</b><span>tienen discapacidad para caminar, subir o bajar: la más frecuente</span></div>
      <div><b>11.3 %</b><span>de la población tiene 60 años o más (en 2000 era 7.6 %)</span></div>
    </div>
    <ul class="lista-check">
      <li>Brigadas de salud en comunidades de la Sierra Norte y la Mixteca.</li>
      <li>Campañas de prevención de caídas en adultos mayores.</li>
      <li>Servicio social en instituciones públicas de salud.</li>
      <li>Promoción de la inclusión de personas con discapacidad.</li>
    </ul>
  </div>
</section>

<section class="contenedor">
  <details class="fuentes">
    <summary>📑 Fuentes de los datos</summary>
    <ul>
      <li>INEGI. Censo de Población y Vivienda 2020, resultados para Puebla (población total).</li>
      <li>Gobierno del Estado de Puebla, Programa del Instituto de la Discapacidad con datos de COESPO/INEGI 2020 (personas con discapacidad por tipo de actividad).</li>
      <li>INEGI. Presentación de resultados del Censo 2020 Puebla (distribución por grupos de edad 2000–2020).</li>
      <li>Información de la Licenciatura en Fisioterapia, Facultad de Medicina BUAP (duración, créditos, sedes).</li>
    </ul>
  </details>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

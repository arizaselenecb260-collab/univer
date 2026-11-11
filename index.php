<?php
$titulo = 'Inicio';
$pagina = 'inicio';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="hero-txt revelar">
    <span class="etiqueta">Facultad de Medicina · BUAP</span>
    <h1>Devuelve el movimiento, <em>transforma vidas.</em></h1>
    <p>La Licenciatura en Fisioterapia une ciencia, manos y corazón para que las personas de Puebla vuelvan a caminar, trabajar y jugar sin dolor.</p>
    <div class="hero-btns">
      <a href="registro.php#ficha" class="btn">Solicitar ficha</a>
      <a href="plan.php" class="btn btn-borde">Ver plan de estudios</a>
    </div>
    <ul class="hero-datos">
      <li><b>Presencial</b><span>Puebla y Teziutlán</span></li>
      <li><b>4 años</b><span>8 semestres</span></li>
      <li><b>342</b><span>créditos</span></li>
    </ul>
  </div>
  <div class="hero-foto revelar">
    <svg class="arco" viewBox="0 0 500 500" aria-hidden="true"><circle class="arco-trazo" cx="250" cy="250" r="235" pathLength="1000"/></svg>
    <img src="assets/img/fisio1.jpg" alt="Fisioterapeuta valorando la rodilla de una paciente" width="1400" height="798" fetchpriority="high">
    <div class="flotante f1"><span>🦵</span><div><b>Terapia manual</b><small>desde 4.º semestre</small></div></div>
    <div class="flotante f2"><span>🏥</span><div><b>Prácticas clínicas</b><small>en hospitales de Puebla</small></div></div>
  </div>
</section>

<section class="cifras" aria-label="La carrera en cifras">
  <div><b class="contador" data-meta="8">0</b><span>semestres</span></div>
  <div><b class="contador" data-meta="342">0</b><span>créditos</span></div>
  <div><b class="contador" data-meta="5762">0</b><span>horas de formación</span></div>
  <div><b class="contador" data-meta="2">0</b><span>sedes BUAP</span></div>
</section>

<section class="contenedor dos-col mision">
  <article class="tarjeta revelar">
    <span class="icono">🎯</span>
    <h2>Misión</h2>
    <p>Formar profesionales de la fisioterapia competentes, éticos y humanistas, con sólidos conocimientos científicos, técnicos y clínicos para prevenir, evaluar, tratar y rehabilitar las alteraciones del movimiento corporal humano en todas las etapas de la vida, impulsando su desarrollo académico y profesional mediante prácticas clínicas, investigación y compromiso con la comunidad poblana.</p>
  </article>
  <article class="tarjeta revelar">
    <span class="icono">🔭</span>
    <h2>Visión</h2>
    <p>Ser en 2035 un programa de Fisioterapia referente nacional e internacional, acreditado e innovador en telerehabilitación e investigación, cuyos egresados lideren equipos multidisciplinarios y promuevan la inclusión, la actividad física y la cultura de la prevención en México.</p>
  </article>
</section>

<section class="contenedor">
  <h2 class="titulo-sec">¿Por qué estudiar Fisioterapia?</h2>
  <div class="grid-3">
    <article class="mini revelar">
      <img src="assets/img/galeria/practica-grupal.jpg" alt="Estudiantes practicando ejercicios con banda elástica y balón" loading="lazy" width="1200" height="684">
      <div><h3>Práctica desde el inicio</h3><p>Laboratorios de movimiento, ejercicio terapéutico y prácticas clínicas en hospitales.</p></div>
    </article>
    <article class="mini revelar">
      <img src="assets/img/galeria/terapia-manual.jpg" alt="Fisioterapeuta aplicando terapia manual en la rodilla" loading="lazy" width="800" height="600">
      <div><h3>Alta demanda</h3><p>Puebla es el 5.º estado más poblado del país y su población adulta mayor sigue creciendo.</p></div>
    </article>
    <article class="mini revelar">
      <img src="assets/img/galeria/telerehabilitacion.jpg" alt="Fisioterapeuta grabando una rutina de ejercicio para redes" loading="lazy" width="1200" height="684">
      <div><h3>Salud digital</h3><p>Telerehabilitación y contenido educativo para llegar a más pacientes.</p></div>
    </article>
  </div>
</section>

<!-- ============ TEST INTERACTIVO ============ -->
<section class="contenedor" id="test">
  <div class="test tarjeta revelar">
    <div class="test-intro">
      <span class="etiqueta">Interactivo · 1 minuto</span>
      <h2>¿Fisioterapia es para ti?</h2>
      <p>Responde 5 preguntas rápidas y descubre tu nivel de afinidad con la carrera.</p>
    </div>
    <div class="test-cuerpo" id="testCuerpo" data-preguntas='[
      {"p":"¿Te gusta ayudar a las personas a sentirse mejor?","o":["¡Mucho!","A veces","No mucho"]},
      {"p":"¿Te interesa cómo funcionan los músculos y huesos?","o":["Me encanta","Algo","Nada"]},
      {"p":"¿Practicas algún deporte o actividad física?","o":["Sí, seguido","De vez en cuando","No"]},
      {"p":"¿Tienes paciencia para procesos que toman semanas?","o":["Sí","Depende","No"]},
      {"p":"¿Te imaginas trabajando en hospital, club o consultorio?","o":["Totalmente","Tal vez","No"]}
    ]'>
      <noscript><p>Activa JavaScript para usar el test.</p></noscript>
    </div>
  </div>
</section>

<!-- ============ MULTIMEDIA ============ -->
<section class="contenedor dos-col multimedia-inicio">
  <div class="revelar">
    <h2>Conoce la carrera en 30 segundos</h2>
    <p>Mira el video promocional y escucha el spot de radio. También puedes abrirlos o descargarlos con los enlaces.</p>
    <div class="reproductor tarjeta">
      <div class="disco" aria-hidden="true">🎧</div>
      <div>
        <b>Spot de radio · Fisioterapia BUAP</b>
        <audio controls preload="none" src="assets/media/spot_fisioterapia.mp3">Tu navegador no reproduce audio.</audio>
      </div>
    </div>
    <p class="enlaces-media">
      <a href="assets/media/promo_fisioterapia.mp4" target="_blank" rel="noopener">▶ Abrir video (MP4)</a>
      <a href="assets/media/spot_fisioterapia.mp3" download>⬇ Descargar spot (MP3)</a>
    </p>
  </div>
  <div class="video-marco revelar">
    <video controls preload="none" poster="assets/img/poster-video.jpg" playsinline>
      <source src="assets/media/promo_fisioterapia.mp4" type="video/mp4">
      Tu navegador no reproduce video. <a href="assets/media/promo_fisioterapia.mp4">Descárgalo aquí</a>.
    </video>
  </div>
</section>

<!-- ============ GALERÍA ============ -->
<section class="contenedor">
  <h2 class="titulo-sec">Galería</h2>
  <div class="galeria" id="galeria">
    <a href="assets/img/galeria/valoracion-rodilla.jpg" class="g-ancha"><img src="assets/img/fisio1-sm.jpg" alt="Valoración de rodilla en camilla" loading="lazy"></a>
    <a href="assets/img/galeria/banda-elastica.jpg" class="g-alta"><img src="assets/img/galeria/banda-elastica.jpg" alt="Estudiante trabajando con banda elástica" loading="lazy"></a>
    <a href="assets/img/galeria/ejercicio-guiado.jpg" class="g-alta"><img src="assets/img/galeria/ejercicio-guiado.jpg" alt="Fisioterapeuta mostrando un estiramiento" loading="lazy"></a>
    <a href="assets/img/galeria/balon-terapeutico.jpg"><img src="assets/img/galeria/balon-terapeutico.jpg" alt="Ejercicio con balón terapéutico" loading="lazy"></a>
    <a href="assets/img/galeria/terapia-manual.jpg"><img src="assets/img/galeria/terapia-manual.jpg" alt="Terapia manual de rodilla" loading="lazy"></a>
    <a href="assets/img/galeria/practica-grupal.jpg" class="g-ancha"><img src="assets/img/fisio2-sm.jpg" alt="Práctica grupal en laboratorio" loading="lazy"></a>
    <a href="assets/img/galeria/telerehabilitacion.jpg" class="g-ancha"><img src="assets/img/colab-sm.jpg" alt="Grabación de contenido de telerehabilitación" loading="lazy"></a>
  </div>
</section>

<section class="contenedor">
  <h2 class="titulo-sec">Preguntas frecuentes</h2>
  <div class="acordeon">
    <details open><summary>¿Cuánto dura la carrera?</summary><p>8 semestres (4 años) de formación presencial con 342 créditos, más el servicio social.</p></details>
    <details><summary>¿Dónde se estudia?</summary><p>En la Facultad de Medicina de la BUAP en Puebla (13 Sur 2702, Col. Volcanes) y en la sede regional de Teziutlán.</p></details>
    <details><summary>¿Cómo ingreso?</summary><p>Registrándote en la convocatoria de admisión BUAP y presentando el examen. En este sitio puedes generar una ficha de pre-registro para organizar tus datos.</p></details>
    <details><summary>¿Necesito bachillerato del área de salud?</summary><p>Se recomienda el área químico-biológica, pero puedes ingresar desde cualquier bachillerato.</p></details>
  </div>
</section>

<section class="contenedor">
  <div class="cta revelar">
    <div>
      <h2>¿Listo para dar el primer paso?</h2>
      <p>Crea tu cuenta, solicita tu ficha y cuéntanos qué te pareció el sitio.</p>
    </div>
    <div class="hero-btns">
      <a href="<?= usuario() ? 'registro.php#ficha' : 'crear_cuenta.php' ?>" class="btn btn-oro">Comenzar</a>
      <a href="encuesta.php" class="btn btn-claro">Responder encuesta</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

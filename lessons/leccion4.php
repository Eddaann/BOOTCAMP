<?php 
require_once __DIR__ . '/../includes/auth.php'; 
$lesson_id = 4; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Día 2: Módulo 4 - Diseño Adaptable y Publicación</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/styles.css" />
  <style id="dynamic-styles"></style>
</head>
<body>
  <?php include __DIR__ . '/../includes/lesson_header.php'; ?>
  <main>
    <div class="container">
      <div class="lesson-container">
        <h2>Módulo 4: Diseño Adaptable y Publicación 🚀</h2>
        <div class="activity-instructions">
            <h3>Teoría y Conceptos Clave</h3>
            <p><strong>Instructor:</strong> Francisco Tapia</p>
            <p>¡Felicidades por llegar al tramo final! Ahora que saben construir y diseñar, nos falta el toque maestro: hacer que nuestras páginas se vean perfectas en cualquier dispositivo. A esto se le llama <strong>Diseño Adaptable (Responsive Design)</strong>. La herramienta clave para lograrlo son las <strong>Media Queries</strong> en CSS, que nos permiten aplicar estilos diferentes según el tamaño de la pantalla del usuario.</p>
            <p>Finalmente, aprenderemos sobre los conceptos de <strong>hosting</strong> (el lugar donde vivirán los archivos de tu web) y <strong>dominio</strong> (la dirección que la gente escribirá en el navegador). Al terminar, no solo tendrán un proyecto completo, sino que estará en línea para que puedan compartirlo con el mundo. ¡Es hora de lanzar su primer cohete al ciberespacio!</p>
        </div>
      </div>
       <div class="lesson-container" style="margin-top: 2rem;"><h3>Parte Práctica: ¡Adaptar y Desplegar!</h3></div>
      <div class="activity-wizard">
        <div class="html-base" style="display:none;"><div class="responsive-box" style="width:100%; padding: 20px; border: 2px solid var(--primary-color); background: lightyellow; text-align:center; font-size: 24px;">Soy una caja</div></div>
        <div class="activity-step active" data-step="1">
            <div class="activity-header"><h3>Actividad 1/10: Tu Primera Media Query</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Escribe una Media Query para pantallas de <strong>máximo 768px</strong> de ancho: <code>@media (max-width: 768px) { }</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-1">Editor CSS</label><textarea id="code-1"></textarea></div><div class="sandbox-preview"><label>Simulación (achica la ventana)</label><div class="sandbox-preview-box" id="preview-1"></div></div></div>
            <div class="feedback-message" id="feedback-1"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkMediaRule(1)">Verificar</button><button class="btn-action btn-next" onclick="nextStep(1)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="2">
            <div class="activity-header"><h3>Actividad 2/10: Cambiar Estilos en Móvil</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Dentro de la media query, haz que la caja <code>.responsive-box</code> tenga un color de fondo (<code>background-color</code>) <code>salmon</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-2">Editor CSS</label><textarea id="code-2"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-2"></div></div></div>
            <div class="feedback-message" id="feedback-2"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkMediaRule(2, '.responsive-box', 'background-color', 'salmon')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(2)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="3">
            <div class="activity-header"><h3>Actividad 3/10: Ajustar Tamaño de Fuente</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>En la misma media query, cambia el <code>font-size</code> de <code>.responsive-box</code> a <code>18px</code> para que sea más legible en pantallas pequeñas.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-3">Editor CSS</label><textarea id="code-3"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-3"></div></div></div>
            <div class="feedback-message" id="feedback-3"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkMediaRule(3, '.responsive-box', 'font-size', '18px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(3)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="11">
             <div class="activity-header"><h3>Desafío Final: ¡Hazlo Responsive y Publícalo!</h3></div>
             <div class="activity-instructions">
                <h4>Este es el último paso para convertirte en un desarrollador web.</h4>
                <ol><li><strong>Adapta tu Biografía a Móviles:</strong><ul><li>En <code>estilos.css</code>, añade <strong>Media Queries</strong> para pantallas pequeñas (e.g., <code>@media (max-width: 768px)</code>).</li><li>Dentro de la media query, cambia el layout de Flexbox. En lugar de estar uno al lado del otro, la barra lateral y el contenido principal deberían apilarse uno encima del otro (usa <code>flex-direction: column</code>).</li><li>Ajusta los tamaños de fuente para que sean más legibles en pantallas pequeñas.</li><li>Asegúrate de que no haya contenido que se desborde horizontalmente.</li></ul></li><li><strong>Publica tu Sitio Web (¡El Gran Paso!):</strong><ul><li>Crea una cuenta en <a href="https://github.com/" target="_blank">GitHub</a>. Es gratis y esencial para cualquier desarrollador.</li><li>Crea un nuevo repositorio y sube tus archivos (<code>mi_biografia.html</code> y <code>estilos.css</code>).</li><li>Ahora, crea una cuenta en <a href="https://www.netlify.com/" target="_blank">Netlify</a> o <a href="https://vercel.com/" target="_blank">Vercel</a> usando tu cuenta de GitHub.</li><li>Importa tu repositorio de GitHub a Netlify/Vercel. Con unos pocos clics, la plataforma construirá y publicará tu sitio, dándote una URL pública que podrás compartir con quien quieras.</li></ul></li></ol>
                <p><strong>¡Al completar esto, tendrás tu primer proyecto profesional en línea!</strong></p>
             </div>
             <div class="activity-controls"><button class="btn-action btn-next" onclick="nextStep(11)">¡Lo he logrado! →</button></div>
        </div>
        <div class="activity-step" data-step="12">
             <div class="activity-header"><h3>¡Bootcamp Completado! 🏆</h3></div>
             <div class="activity-instructions completion-form">
                <p><strong>¡MISIÓN CUMPLIDA!</strong> Has completado todos los módulos, has superado los desafíos y tienes tu primer sitio web publicado. Este es el comienzo de un emocionante viaje en el mundo del desarrollo web. ¡Sigue aprendiendo y creando cosas increíbles!</p>
                <form action="../complete_lesson.php" method="POST" style="display: inline;"><input type="hidden" name="lesson_id" value="<?= $lesson_id ?>"><button type="submit" class="btn-action btn-verify">¡Finalizar y reclamar mi logro!</button></form>
             </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../assets/js/lesson-script.js"></script>
</body>
</html>
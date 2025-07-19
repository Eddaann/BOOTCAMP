<?php 
require_once __DIR__ . '/../includes/auth.php'; 
$lesson_id = 3; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Día 2: Módulo 3 - Formularios y Diseño Flexbox</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/styles.css" />
  <style id="dynamic-styles"></style>
</head>
<body>
  <?php include __DIR__ . '/../includes/lesson_header.php'; ?>
  <main>
    <div class="container">
      <div class="lesson-container">
        <h2>Módulo 3: Formularios y Diseño Flexbox 📝</h2>
        <div class="activity-instructions">
            <h3>Teoría y Conceptos Clave</h3>
            <p><strong>Instructor:</strong>Emilio Acevedo</p>
            <p>¡Bienvenidos al segundo día! Hoy subimos el nivel. Primero, veremos los <strong>formularios</strong>, que son la manera en que nuestras páginas pueden "escuchar" al usuario, permitiéndole enviar datos. Luego, abordaremos un concepto fundamental del diseño moderno: <strong>Flexbox</strong>.</p>
            <p>Flexbox es un módulo de diseño de CSS que nos permite alinear y distribuir el espacio entre los elementos de una manera mucho más sencilla y, sobre todo, flexible. Olvídense de los viejos trucos para centrar un `div`; con Flexbox, organizar elementos en filas o columnas y hacer que se adapten al espacio disponible es increíblemente intuitivo. ¡Prepárense para diseñar layouts como profesionales!</p>
        </div>
      </div>
      <div class="lesson-container" style="margin-top: 2rem;"><h3>Parte Práctica: ¡Construye y Organiza!</h3></div>
      <div class="activity-wizard">
        <div class="html-base" style="display:none;"><div class="flex-container"><style>.flex-container{display:flex; border:2px dashed grey; padding:10px; min-height:100px;}.box{width:50px;height:50px;background:var(--primary-color);color:white;display:flex;justify-content:center;align-items:center;font-size:24px;border-radius:5px;}</style><div class="box">1</div><div class="box">2</div><div class="box">3</div></div></div>
        <div class="activity-step active" data-step="1">
            <div class="activity-header"><h3>Actividad 1/10: Creando el Formulario</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Todo formulario comienza con la etiqueta <code>&lt;form&gt;</code>. Crea una para empezar.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-1">Editor HTML</label><textarea id="code-1"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-1"></div></div></div>
            <div class="feedback-message" id="feedback-1"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkSimpleTag(1, 'form')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(1)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="2">
            <div class="activity-header"><h3>Actividad 2/10: Campo de Texto</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Dentro del formulario, añade un campo para el nombre. Usa <code>&lt;label for="nombre"&gt;Nombre:&lt;/label&gt;</code> y <code>&lt;input type="text" id="nombre"&gt;</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-2">Editor HTML</label><textarea id="code-2"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-2"></div></div></div>
            <div class="feedback-message" id="feedback-2"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkFormInput(2, 'text', 'nombre')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(2)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="3">
            <div class="activity-header"><h3>Actividad 3/10: Campo de Email</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Añade un campo para el correo. Usa <code>&lt;label for="email"&gt;Correo:&lt;/label&gt;</code> y <code>&lt;input type="email" id="email"&gt;</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-3">Editor HTML</label><textarea id="code-3"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-3"></div></div></div>
            <div class="feedback-message" id="feedback-3"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkFormInput(3, 'email', 'email')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(3)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="4">
            <div class="activity-header"><h3>Actividad 4/10: Área de Texto</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Para mensajes largos, usamos <code>&lt;textarea&gt;</code>. Añade una con su <code>&lt;label&gt;</code> para "Mensaje".</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-4">Editor HTML</label><textarea id="code-4"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-4"></div></div></div>
            <div class="feedback-message" id="feedback-4"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkSimpleTag(4, 'textarea')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(4)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="5">
            <div class="activity-header"><h3>Actividad 5/10: Botón de Envío</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Añade un botón para enviar el formulario. Usa <code>&lt;button type="submit"&gt;Enviar&lt;/button&gt;</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-5">Editor HTML</label><textarea id="code-5"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-5"></div></div></div>
            <div class="feedback-message" id="feedback-5"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkActivity(5, 'button', 'Enviar')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(5)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="6">
            <div class="activity-header"><h3>Actividad 6/10: Introducción a Flexbox</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>¡Hora de diseñar! Al contenedor <code>.flex-container</code>, aplícale <code>display: flex;</code> para activar Flexbox.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-6">Editor CSS</label><textarea id="code-6"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-6"></div></div></div>
            <div class="feedback-message" id="feedback-6"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(6, '.flex-container', 'display', 'flex')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(6)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="7">
            <div class="activity-header"><h3>Actividad 7/10: Justificar Contenido</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Usa <code>justify-content: space-between;</code> en <code>.flex-container</code> para distribuir las cajas con espacio entre ellas.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-7">Editor CSS</label><textarea id="code-7"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-7"></div></div></div>
            <div class="feedback-message" id="feedback-7"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(7, '.flex-container', 'justify-content', 'space-between')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(7)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="8">
            <div class="activity-header"><h3>Actividad 8/10: Alinear Items</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Ahora, céntralos verticalmente con <code>align-items: center;</code> en el mismo contenedor.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-8">Editor CSS</label><textarea id="code-8"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-8"></div></div></div>
            <div class="feedback-message" id="feedback-8"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(8, '.flex-container', 'align-items', 'center')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(8)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="9">
            <div class="activity-header"><h3>Actividad 9/10: Espaciado (Gap)</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Para añadir espacio entre las cajas, usa <code>gap: 10px;</code> en <code>.flex-container</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-9">Editor CSS</label><textarea id="code-9"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-9"></div></div></div>
            <div class="feedback-message" id="feedback-9"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(9, '.flex-container', 'gap', '10px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(9)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="10">
            <div class="activity-header"><h3>Actividad 10/10: Dirección de Flexbox</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Cambia la dirección del contenedor a columnas con <code>flex-direction: column;</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-10">Editor CSS</label><textarea id="code-10"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-10"></div></div></div>
            <div class="feedback-message" id="feedback-10"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(10, '.flex-container', 'flex-direction', 'column')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(10)">Siguiente →</button></div>
        </div>
        <div class="activity-step" data-step="11">
             <div class="activity-header"><h3>Desafío Práctico (Opcional)</h3></div>
             <div class="activity-instructions">
                <h4>Mejora tu Biografía con un Formulario y Flexbox</h4>
                <p>Lleva tu página personal al siguiente nivel añadiendo interactividad y un diseño más avanzado.</p>
                <ol><li><strong>Añade una sección de Contacto:</strong><ul><li>En tu archivo <code>mi_biografia.html</code>, agrega un <code>&lt;h2&gt;</code> que diga "Contacto".</li><li>Debajo, crea un formulario (<code>&lt;form&gt;</code>) que pida el nombre, el correo y un mensaje (<code>&lt;textarea&gt;</code>).</li><li>En <code>estilos.css</code>, dale un diseño limpio y atractivo a tu formulario.</li></ul></li><li><strong>Reestructura tu página con Flexbox:</strong><ul><li>Crea un contenedor principal (<code>&lt;div class="main-container"&gt;</code>) que envuelva el contenido principal.</li><li>Crea otro contenedor para una barra lateral (<code>&lt;aside class="sidebar"&gt;</code>).</li><li>Aplica <code>display: flex</code> al contenedor padre de ambos para ponerlos uno al lado del otro.</li></ul></li></ol>
             </div>
             <div class="activity-controls"><button class="btn-action btn-next" onclick="nextStep(11)">¡Listo para terminar! →</button></div>
        </div>
        <div class="activity-step" data-step="12">
             <div class="activity-header"><h3>¡Módulo 3 Completado! ✅</h3></div>
             <div class="activity-instructions completion-form">
                <p>¡Impresionante! Ya sabes recolectar datos y organizar layouts como un profesional. Estás a un solo paso de finalizar el bootcamp.</p>
                <form action="../complete_lesson.php" method="POST" style="display: inline;"><input type="hidden" name="lesson_id" value="<?= $lesson_id ?>"><button type="submit" class="btn-action btn-verify">Marcar Módulo 3 como Completado</button></form>
             </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../assets/js/lesson-script.js"></script>
</body>
</html>
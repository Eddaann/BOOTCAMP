<?php 
require_once __DIR__ . '/../includes/auth.php'; 
$lesson_id = 2; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Día 1: Módulo 2 - Dando Estilo con CSS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/styles.css" />
  <style id="dynamic-styles"></style>
</head>
<body>
  <?php include __DIR__ . '/../includes/lesson_header.php'; ?>
  <main>
    <div class="container">
       <div class="lesson-container">
            <h2>Módulo 2: Dando Estilo con CSS 🎨</h2>
            <div class="activity-instructions">
                <h3>Teoría y Conceptos Clave</h3>
                <p><strong>Instructora:</strong> Sofía Vargas</p>
                <p>¡Hola, equipo! Ahora que ya tenemos el esqueleto de nuestra página, vamos a vestirlo. Para eso usamos CSS (Cascading Style Sheets). CSS nos permite aplicar estilos como colores, fuentes y espaciados.</p>
                <p>La magia de CSS reside en los <strong>selectores</strong>. Un selector es una regla que le dice al navegador a qué elemento HTML queremos aplicarle un estilo. Hoy aprenderemos los tres selectores básicos: por <strong>etiqueta</strong> (para afectar a todas las etiquetas de un tipo, como todos los `h1`), por <strong>clase</strong> (para afectar a todos los elementos que compartan una clase, como `.noticia-destacada`) y por <strong>ID</strong> (para afectar a un único elemento específico, como `#menu-principal`).</p>
            </div>
        </div>
        <div class="lesson-container" style="margin-top: 2rem;"><h3>Parte Práctica: ¡A pintar se ha dicho!</h3></div>
        <div class="activity-wizard">
            <div class="html-base" style="display:none;">
                <h1>Mi Página con Estilo</h1>
                <p class="intro">Este es un párrafo para practicar CSS.</p>
                <div id="caja-especial">Una caja con ID.</div>
            </div>
            <div class="activity-step active" data-step="1">
              <div class="activity-header"><h3>Actividad 1/10: Coloreando el Título</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Usa un <strong>selector de etiqueta</strong> para que el título <code>h1</code> sea de color <code>purple</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-1">Editor CSS</label><textarea id="code-1"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-1"></div></div></div>
              <div class="feedback-message" id="feedback-1"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(1, 'h1', 'color', 'purple')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(1)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="2">
              <div class="activity-header"><h3>Actividad 2/10: Selector de Clase</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Usa un <strong>selector de clase</strong> para que el párrafo <code>.intro</code> tenga un tamaño de fuente (<code>font-size</code>) de <code>20px</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-2">Editor CSS</label><textarea id="code-2"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-2"></div></div></div>
              <div class="feedback-message" id="feedback-2"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(2, '.intro', 'font-size', '20px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(2)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="3">
                <div class="activity-header"><h3>Actividad 3/10: Selector de ID</h3><div class="progress-bar"><div class="progress"></div></div></div>
                <div class="activity-instructions"><p>Usa un <strong>selector de ID</strong> para que la caja <code>#caja-especial</code> tenga un fondo (<code>background-color</code>) <code>lightblue</code>.</p></div>
                <div class="sandbox"><div class="sandbox-editor"><label for="code-3">Editor CSS</label><textarea id="code-3"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-3"></div></div></div>
                <div class="feedback-message" id="feedback-3"></div>
                <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(3, '#caja-especial', 'background-color', 'lightblue')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(3)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="4">
              <div class="activity-header"><h3>Actividad 4/10: Añadiendo Relleno (Padding)</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>El padding añade espacio *dentro* de un elemento. Aplica un <code>padding</code> de <code>15px</code> a la caja <code>#caja-especial</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-4">Editor CSS</label><textarea id="code-4"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-4"></div></div></div>
              <div class="feedback-message" id="feedback-4"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(4, '#caja-especial', 'padding', '15px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(4)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="5">
              <div class="activity-header"><h3>Actividad 5/10: Añadiendo Margen (Margin)</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>El margen añade espacio *fuera* de un elemento. Aplica un <code>margin-top</code> de <code>20px</code> al párrafo <code>.intro</code> para separarlo del título.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-5">Editor CSS</label><textarea id="code-5"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-5"></div></div></div>
              <div class="feedback-message" id="feedback-5"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(5, '.intro', 'margin-top', '20px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(5)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="6">
              <div class="activity-header"><h3>Actividad 6/10: Creando Bordes</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Vamos a añadir un borde al título <code>h1</code>. Usa <code>border</code> con el valor <code>2px solid purple</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-6">Editor CSS</label><textarea id="code-6"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-6"></div></div></div>
              <div class="feedback-message" id="feedback-6"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(6, 'h1', 'border', '2px solid purple')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(6)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="7">
              <div class="activity-header"><h3>Actividad 7/10: Esquinas Redondeadas</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Suaviza las esquinas de la caja <code>#caja-especial</code>. Usa <code>border-radius</code> con un valor de <code>10px</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-7">Editor CSS</label><textarea id="code-7"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-7"></div></div></div>
              <div class="feedback-message" id="feedback-7"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(7, '#caja-especial', 'border-radius', '10px')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(7)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="8">
              <div class="activity-header"><h3>Actividad 8/10: Cambiando la Fuente</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Cambia la fuente de toda la página. Crea un selector para <code>body</code> y aplica la propiedad <code>font-family</code> con el valor <code>sans-serif</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-8">Editor CSS</label><textarea id="code-8"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-8"></div></div></div>
              <div class="feedback-message" id="feedback-8"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(8, 'p', 'font-family', 'sans-serif')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(8)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="9">
              <div class="activity-header"><h3>Actividad 9/10: Alineando Texto</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Centra el texto del título <code>h1</code>. Usa la propiedad <code>text-align</code> con el valor <code>center</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-9">Editor CSS</label><textarea id="code-9"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-9"></div></div></div>
              <div class="feedback-message" id="feedback-9"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCss(9, 'h1', 'text-align', 'center')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(9)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="10">
              <div class="activity-header"><h3>Actividad 10/10: Comentarios en CSS</h3><div class="progress-bar"><div class="progress"></div></div></div>
              <div class="activity-instructions"><p>Añade un comentario en tu CSS que diga <code>/* Mis primeros estilos */</code>. Los comentarios en CSS empiezan con <code>/*</code> y terminan con <code>*/</code>.</p></div>
              <div class="sandbox"><div class="sandbox-editor"><label for="code-10">Editor CSS</label><textarea id="code-10"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-10"></div></div></div>
              <div class="feedback-message" id="feedback-10"></div>
              <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCssComment(10, '/* Mis primeros estilos */')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(10)">Siguiente →</button></div>
            </div>
            <div class="activity-step" data-step="11">
                <div class="activity-header"><h3>Desafío Práctico (Opcional)</h3></div>
                <div class="activity-instructions">
                    <h4>Dale Estilo a tu Página de Biografía</h4>
                    <p>Es hora de aplicar tus nuevas habilidades de CSS al proyecto que iniciaste en el módulo anterior.</p>
                    <ol><li>Crea un nuevo archivo en la misma carpeta que tu <code>mi_biografia.html</code> y llámalo <code>estilos.css</code>.</li><li>Enlaza tu archivo CSS a tu HTML usando la etiqueta <code>&lt;link&gt;</code> en el <code>&lt;head&gt;</code>.</li><li>En tu archivo <code>estilos.css</code>, añade reglas para:<ul><li>Cambiar el color de fondo del <code>&lt;body&gt;</code>.</li><li>Elegir una fuente diferente para toda la página (puedes buscar en Google Fonts).</li><li>Ponerle un color llamativo y centrar tu título principal (<code>&lt;h1&gt;</code>).</li><li>Añadir un borde y esquinas redondeadas a tu imagen.</li><li>Darle un color de fondo diferente a las secciones usando clases (<code>class</code>).</li></ul></li><li>¡Experimenta! Juega con los colores y espaciados hasta que estés orgulloso del resultado.</li></ol>
                </div>
                <div class="activity-controls"><button class="btn-action btn-next" onclick="nextStep(11)">¡Listo para terminar! →</button></div>
            </div>
            <div class="activity-step" data-step="12">
                 <div class="activity-header"><h3>¡Módulo 2 Completado! ✅</h3></div>
                 <div class="activity-instructions completion-form">
                    <p>¡Fantástico! Ya sabes cómo darle vida a tus páginas. Has completado el Día 1 con éxito. ¡Mañana te esperan desafíos aún más grandes!</p>
                    <form action="../complete_lesson.php" method="POST" style="display: inline;"><input type="hidden" name="lesson_id" value="<?= $lesson_id ?>"><button type="submit" class="btn-action btn-verify">Marcar Módulo 2 como Completado</button></form>
                 </div>
            </div>
        </div>
    </div>
  </main>
  <script src="../assets/js/lesson-script.js"></script>
</body>
</html>
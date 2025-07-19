<?php 
require_once __DIR__ . '/../includes/auth.php'; 
$lesson_id = 1; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Día 1: Módulo 1 - El Esqueleto de la Web con HTML</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/styles.css" />
</head>
<body>
  <?php include __DIR__ . '/../includes/lesson_header.php'; ?>
  <main>
    <div class="container">
      <div class="lesson-container">
        <h2>Módulo 1: El Esqueleto de la Web con HTML 🦴</h2>
        <div class="activity-instructions">
            <h3>Teoría y Conceptos Clave</h3>
            <p><strong>Instructor:</strong> Fernando Mojica</p>
            <p>¡Hola a todos! Antes de empezar a escribir código, es crucial entender qué es HTML. Piensen en HTML (HyperText Markup Language) como el esqueleto de una casa. No define los colores ni los muebles, pero sí establece las habitaciones, las puertas y las ventanas. En la web, esto se traduce en títulos, párrafos, imágenes y enlaces.</p>
            <p>Hoy nos enfocaremos en las <strong>etiquetas</strong>, que son como los ladrillos de nuestra página. Cada etiqueta le da un significado al contenido que envuelve. Por ejemplo, <code>&lt;h1&gt;</code> le dice al navegador "esto es un título muy importante", y <code>&lt;p&gt;</code> le dice "esto es un párrafo de texto". ¡Vamos a construir nuestro primer esqueleto!</p>
        </div>
      </div>
      
      <div class="lesson-container" style="margin-top: 2rem;">
        <h3>Parte Práctica: ¡Manos a la Obra!</h3>
      </div>
      <div class="activity-wizard">
        <div class="activity-step active" data-step="1">
            <div class="activity-header"><h3>Actividad 1/10: Tu Primer Título</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>¡Empecemos! Usa la etiqueta <code>&lt;h1&gt;</code> para crear un título principal que diga "Mi Biografía".</p></div>
            <div class="sandbox">
                <div class="sandbox-editor"><label for="code-1">Editor HTML</label><textarea id="code-1"></textarea></div>
                <div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-1"></div></div>
            </div>
            <div class="feedback-message" id="feedback-1"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkActivity(1, 'h1', 'Mi Biografía')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(1)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="2">
            <div class="activity-header"><h3>Actividad 2/10: Escribiendo Párrafos</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>¡Genial! Ahora, añade un párrafo con <code>&lt;p&gt;</code> que diga "Estoy aprendiendo a crear páginas web."</p></div>
            <div class="sandbox">
                <div class="sandbox-editor"><label for="code-2">Editor HTML</label><textarea id="code-2"></textarea></div>
                <div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-2"></div></div>
            </div>
            <div class="feedback-message" id="feedback-2"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkActivity(2, 'p', 'Estoy aprendiendo a crear páginas web.')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(2)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="3">
            <div class="activity-header"><h3>Actividad 3/10: Agregando Subtítulos</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Usa <code>&lt;h2&gt;</code> para un subtítulo que diga "Mis Hobbies".</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-3">Editor HTML</label><textarea id="code-3"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-3"></div></div></div>
            <div class="feedback-message" id="feedback-3"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkActivity(3, 'h2', 'Mis Hobbies')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(3)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="4">
            <div class="activity-header"><h3>Actividad 4/10: Creando una Lista</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Crea una lista. Usa <code>&lt;ul&gt;</code> para la lista y <code>&lt;li&gt;</code> para cada elemento. Añade dos hobbies: "Leer" y "Programar".</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-4">Editor HTML</label><textarea id="code-4"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-4"></div></div></div>
            <div class="feedback-message" id="feedback-4"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkListActivity(4, ['Leer', 'Programar'])">Verificar</button><button class="btn-action btn-next" onclick="nextStep(4)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="5">
            <div class="activity-header"><h3>Actividad 5/10: Creando un Enlace</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Crea un enlace con <code>&lt;a&gt;</code> que diga "Buscar en Google" y apunte a <code>https://google.com</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-5">Editor HTML</label><textarea id="code-5"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-5"></div></div></div>
            <div class="feedback-message" id="feedback-5"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkLinkActivity(5, 'Buscar en Google', 'https://google.com/')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(5)">Siguiente →</button></div>
        </div>
        
        <div class="activity-step" data-step="6">
            <div class="activity-header"><h3>Actividad 6/10: Insertando una Imagen</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Inserta una imagen con <code>&lt;img&gt;</code>. Usa la URL <code>https://via.placeholder.com/150</code> como <code>src</code> y "Imagen de ejemplo" como <code>alt</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-6">Editor HTML</label><textarea id="code-6"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-6"></div></div></div>
            <div class="feedback-message" id="feedback-6"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkImageActivity(6, 'https://via.placeholder.com/150', 'Imagen de ejemplo')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(6)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="7">
            <div class="activity-header"><h3>Actividad 7/10: Texto en Negrita</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>En tu primer párrafo, encierra la palabra "web" con la etiqueta <code>&lt;strong&gt;</code> para ponerla en negrita.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-7">Editor HTML</label><textarea id="code-7"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-7"></div></div></div>
            <div class="feedback-message" id="feedback-7"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkTagInParagraph(7, 'strong', 'web')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(7)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="8">
            <div class="activity-header"><h3>Actividad 8/10: Texto en Cursiva</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Ahora, encierra la palabra "aprendiendo" con la etiqueta <code>&lt;em&gt;</code> para ponerla en cursiva.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-8">Editor HTML</label><textarea id="code-8"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-8"></div></div></div>
            <div class="feedback-message" id="feedback-8"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkTagInParagraph(8, 'em', 'aprendiendo')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(8)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="9">
            <div class="activity-header"><h3>Actividad 9/10: Línea Horizontal</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Separa tus hobbies del resto con una línea horizontal. Usa la etiqueta <code>&lt;hr&gt;</code> debajo de la lista.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-9">Editor HTML</label><textarea id="code-9"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-9"></div></div></div>
            <div class="feedback-message" id="feedback-9"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkSimpleTag(9, 'hr')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(9)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="10">
            <div class="activity-header"><h3>Actividad 10/10: Dejando Comentarios</h3><div class="progress-bar"><div class="progress"></div></div></div>
            <div class="activity-instructions"><p>Los comentarios no se ven en la página, pero ayudan a otros programadores. Añade un comentario al final que diga <code>&lt;!-- Esta es mi primera página --&gt;</code>.</p></div>
            <div class="sandbox"><div class="sandbox-editor"><label for="code-10">Editor HTML</label><textarea id="code-10"></textarea></div><div class="sandbox-preview"><label>Vista Previa</label><div class="sandbox-preview-box" id="preview-10"></div></div></div>
            <div class="feedback-message" id="feedback-10"></div>
            <div class="activity-controls"><button class="btn-action btn-verify" onclick="checkCommentActivity(10, '')">Verificar</button><button class="btn-action btn-next" onclick="nextStep(10)">Siguiente →</button></div>
        </div>

        <div class="activity-step" data-step="11">
             <div class="activity-header"><h3>Desafío Práctico (Opcional)</h3></div>
             <div class="activity-instructions">
                <h4>Crea tu Propia Página de Biografía</h4>
                <p>Ahora que dominas las etiquetas básicas, es tu turno de crear algo desde cero en tu propia computadora.</p>
                <ol><li>Abre un editor de código (como Visual Studio Code).</li><li>Crea un nuevo archivo y guárdalo como <code>mi_biografia.html</code>.</li><li>Usando lo que aprendiste, crea una página sobre ti. Debe incluir:<ul><li>Un título principal (<code>&lt;h1&gt;</code>) con tu nombre.</li><li>Una foto tuya (puedes buscar una imagen de placeholder online).</li><li>Varios subtítulos (<code>&lt;h2&gt;</code>) como "Sobre mí", "Mis Pasiones", "Contacto".</li><li>Párrafos (<code>&lt;p&gt;</code>) con información sobre ti.</li><li>Una lista (<code>&lt;ul&gt;</code>) de tus 3 películas o series favoritas.</li><li>Un enlace (<code>&lt;a&gt;</code>) a una de tus redes sociales.</li></ul></li><li>Guarda el archivo y ábrelo en tu navegador para ver el resultado. ¡Este será el primer proyecto para tu portafolio!</li></ol>
             </div>
             <div class="activity-controls"><button class="btn-action btn-next" onclick="nextStep(11)">¡Listo para terminar! →</button></div>
        </div>

        <div class="activity-step" data-step="12">
             <div class="activity-header"><h3>¡Módulo 1 Completado! ✅</h3></div>
             <div class="activity-instructions completion-form">
                <p>¡Felicidades! Has dominado las etiquetas fundamentales de HTML y has creado tu primera página. Estás listo para el siguiente desafío: darle estilo.</p>
                <form action="../complete_lesson.php" method="POST" style="display: inline;"><input type="hidden" name="lesson_id" value="<?= $lesson_id ?>"><button type="submit" class="btn-action btn-verify">Marcar Módulo 1 como Completado</button></form>
             </div>
        </div>
      </div>
    </div>
  </main>
  <script src="../assets/js/lesson-script.js"></script>
</body>
</html>
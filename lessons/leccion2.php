<?php
require '../includes/auth.php';
require '../includes/db.php';

$lesson_id = 2;
$user_id = $_SESSION['user_id'];

// --- Lógica para registrar el inicio de la lección ---
$sql_check_start = "SELECT id FROM user_lessons WHERE user_id = ? AND lesson_id = ?";
$stmt_check_start = $conn->prepare($sql_check_start);
$stmt_check_start->bind_param("ii", $user_id, $lesson_id);
$stmt_check_start->execute();
$result_start = $stmt_check_start->get_result();
if ($result_start->num_rows == 0) {
    $sql_start_lesson = "INSERT INTO user_lessons (user_id, lesson_id, started_at) VALUES (?, ?, NOW())";
    $stmt_start = $conn->prepare($sql_start_lesson);
    $stmt_start->bind_param("ii", $user_id, $lesson_id);
    $stmt_start->execute();
    $stmt_start->close();
}
$stmt_check_start->close();

// --- Lógica para verificar si la lección ya está completada Y OBTENER PROGRESO ---
$is_completed = false;
$completed_steps_count = 0;
$sql_check = "SELECT points_awarded, completed_steps FROM user_lessons WHERE user_id = ? AND lesson_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $lesson_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows > 0) {
    $row = $result_check->fetch_assoc();
    $completed_steps_count = $row['completed_steps'];
    if ($row['points_awarded'] !== null) {
        $is_completed = true;
    }
}
$stmt_check->close();

// --- Definición de los Ejercicios y Teoría de la Semana 2 ---
$exercises = [
    [
        'day' => 6,
        'title' => 'Introducción a CSS',
        'objective' => 'Cambia el color de fondo, el color del texto de los encabezados y la fuente de los párrafos. Mini-Reto: Crea una clase `.destacado` y aplícala a un párrafo para que tenga un color de fondo diferente.',
        'theory' => 'CSS (Cascading Style Sheets) es el lenguaje que usamos para diseñar nuestras páginas web. Funciona con "selectores" para apuntar a elementos HTML (por etiqueta `h1`, por clase `.mi-clase`, o por ID `#mi-id`) y "propiedades" para cambiar su apariencia (como `color`, `background-color`, `font-family`).',
        'example_code' => "<style>
    body {
        background-color: navy;
    }
    .titulo-principal {
        color: white;
    }
</style>",
        'starter_code' => "<!-- Pega aquí tu código del proyecto de la Semana 1 -->
<style>
    /* Escribe tus estilos aquí */

</style>",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('body') && css.includes('background-color') && (css.includes('h1') || css.includes('h2')) && css.includes('color') && css.includes('p') && css.includes('font-family') && css.includes('.destacado');"
    ],
    [
        'day' => 7,
        'title' => 'El Modelo de Caja',
        'objective' => 'Crea 3 <div>. Usa `margin` para separarlos, `padding` para darles espacio interno y `border`. Mini-Reto: Experimenta con bordes punteados (`dotted`) y redondeados (`border-radius`).',
        'theory' => 'En CSS, cada elemento es una caja rectangular. El "Modelo de Caja" describe cómo se compone esta caja: el contenido, el `padding` (espacio interior), el `border` (borde) y el `margin` (espacio exterior). Dominar estas cuatro propiedades es fundamental para controlar el espaciado y el tamaño de los elementos.',
        'example_code' => "<style>
    .caja {
        width: 100px;
        padding: 20px;
        border: 2px solid red;
        border-radius: 10px; /* Borde redondeado */
        margin: 10px;
    }
</style>",
        'starter_code' => "<style>
    .tarjeta {
        
    }
</style>

<div class=\"tarjeta\">Tarjeta 1</div>
<div class=\"tarjeta\">Tarjeta 2</div>
<div class=\"tarjeta\">Tarjeta 3</div>",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('.tarjeta') && css.includes('margin') && css.includes('padding') && css.includes('border') && css.includes('border-radius');"
    ],
    [
        'day' => 8,
        'title' => 'Layout con Flexbox (Parte 1)',
        'objective' => 'Crea una barra de navegación. Usa `display: flex` en el `<nav>` y `justify-content` para distribuir los enlaces. Mini-Reto: Cambia el valor de `justify-content` a `space-between` y `center` para ver las diferencias.',
        'theory' => 'Flexbox es un modelo de diseño moderno para alinear y distribuir elementos en un contenedor. Al aplicar `display: flex` a un contenedor, sus hijos se convierten en "flex items". La propiedad `justify-content` controla la alineación horizontal (ej: `space-around`, `center`) y `align-items` controla la vertical.',
        'example_code' => "<style>
    .container {
        display: flex;
        justify-content: space-between; /* o center, flex-start, etc. */
        align-items: center;
    }
</style>",
        'starter_code' => "<style>
    nav {
        background-color: #333;
        padding: 1rem;
    }
    a {
        color: white;
        text-decoration: none;
    }
</style>
<nav>
    <a href=\"#\">Inicio</a>
    <a href=\"#\">Servicios</a>
    <a href=\"#\">Galería</a>
    <a href=\"#\">Contacto</a>
</nav>",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('nav') && css.includes('display:flex') && css.includes('justify-content');"
    ],
    [
        'day' => 9,
        'title' => 'Layout con Flexbox (Parte 2)',
        'objective' => 'Crea una sección con 6 "tarjetas" de Pokémon. Usa Flexbox con `flex-wrap: wrap` para que las tarjetas se acomoden en varias filas. Mini-Reto: Usa `flex-direction: column` para ver cómo se apilan verticalmente.',
        'theory' => 'Flexbox es muy potente. `flex-direction` nos permite cambiar el eje principal (a `column` por ejemplo). Cuando los elementos no caben en una sola línea, `flex-wrap: wrap` les permite pasar a la línea siguiente, creando un diseño adaptable o "responsive" de forma muy sencilla.',
        'example_code' => "<style>
    .container {
        display: flex;
        flex-wrap: wrap;
    }
    .item {
        width: 100px; /* Ancho de cada item */
    }
</style>",
        'starter_code' => "<style>
    .equipo-pokemon {
        
    }
    .pokemon-card {
        border: 1px solid #ccc;
        padding: 1rem;
        margin: 0.5rem;
        width: 120px;
        text-align: center;
    }
</style>
<div class=\"equipo-pokemon\">
    <div class=\"pokemon-card\">Pikachu</div>
    <div class=\"pokemon-card\">Charmander</div>
    <div class=\"pokemon-card\">Squirtle</div>
    <div class=\"pokemon-card\">Bulbasaur</div>
    <div class=\"pokemon-card\">Jigglypuff</div>
    <div class=\"pokemon-card\">Snorlax</div>
</div>",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('display:flex') && css.includes('flex-wrap:wrap');"
    ],
    [
        'day' => 10,
        'title' => 'Proyecto Semanal 2',
        'objective' => 'Diseña una réplica visual de un perfil de Instagram (foto, nombre, bio, cuadrícula de imágenes). Mini-Reto: Añade debajo de la biografía los "Highlights" (círculos con texto) usando Flexbox.',
        'theory' => '¡Es hora de aplicar tus habilidades de diseño! Este proyecto consiste en replicar una interfaz conocida. Presta atención a los detalles: espaciado, alineación y estructura. Usa `div` para crear las secciones principales (cabecera, galería) y aplica Flexbox para posicionar todo correctamente. ¡El reto es que se parezca lo más posible al original!',
        'example_code' => "<!-- Tu misión es recrear la estructura y estilo de un perfil de Instagram usando HTML y CSS con Flexbox. -->",
        'starter_code' => "<!-- Construye tu clon de perfil de Instagram aquí -->",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('display:flex');"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semana 2: Dando Estilo con CSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
        #editor { font-family: 'Fira Code', 'Courier New', Courier, monospace; tab-size: 4; font-size: 16px; line-height: 1.5; }
        .step.active { border-color: #818cf8; transform: scale(1.02); }
        .step.completed { border-color: #22c55e; background-color: #16a34a20; }
        .prose h1 { color: #818cf8; }
        .prose h2 { color: white; }
        .prose p, .prose li { color: #d1d5db; }
        .prose code { background-color: #374151; color: #f3f4f6; padding: 0.2em 0.4em; border-radius: 0.25rem; }
    </style>
</head>
<body class="bg-gray-900 text-white overflow-hidden">
    <!-- Fase 1: Vista de Teoría -->
    <div id="concept-view" class="h-screen flex flex-col items-center justify-center p-6 sm:p-8">
        <div class="w-full max-w-4xl text-left bg-gray-800/50 p-8 rounded-2xl shadow-2xl backdrop-blur-sm border border-gray-700">
            <div class="prose prose-invert max-w-none">
                <div class="flex justify-between items-start">
                    <h1 id="concept-title" class="text-4xl md:text-5xl font-bold mt-0 mb-4"></h1>
                    <a href="../dashboard.php" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors no-underline whitespace-nowrap"><i class="fas fa-times-circle mr-1"></i> Salir</a>
                </div>
                <p id="concept-theory" class="text-lg md:text-xl leading-relaxed mb-6"></p>
                <h2>Ejemplo de Código:</h2>
                <pre><code id="concept-example" class="language-html rounded-lg text-base"></code></pre>
            </div>
            <div class="mt-8 text-center">
                <button id="start-lab-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-10 rounded-lg transition-transform hover:scale-105 text-xl shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-vial mr-2"></i>¡Entendido, al Laboratorio!
                </button>
            </div>
        </div>
    </div>

    <!-- Fase 2: Vista de Laboratorio -->
    <div id="lab-view" class="hidden h-screen flex flex-col md:flex-row">
        <aside class="w-full md:w-1/3 xl:w-1/4 p-6 bg-gray-800 overflow-y-auto flex flex-col">
            <div>
                <button id="back-to-theory-btn" class="text-indigo-400 hover:text-indigo-300 mb-4 block transition-colors"><i class="fas fa-arrow-left mr-2"></i>Volver a la Teoría</button>
                <h1 class="text-2xl font-bold text-indigo-400">Semana 2: Laboratorio</h1>
                <div class="w-full bg-gray-700 rounded-full h-2.5 my-4">
                    <div id="progress-bar" class="bg-indigo-400 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div id="mission-container" class="mb-4 p-4 bg-indigo-900/50 rounded-lg border border-indigo-500/50 shadow-inner">
                <h2 class="font-bold text-lg text-indigo-300 mb-2 flex items-center"><i class="fas fa-bullseye mr-3"></i>Tu Misión Actual</h2>
                <p id="mission-objective" class="text-gray-200 text-sm font-medium leading-relaxed"></p>
            </div>
            <div id="steps-container" class="space-y-3 flex-grow"></div>
            <div class="mt-auto pt-4">
                <div id="feedback-container" class="h-12 mb-2"></div>
                <button id="check-code-btn" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i>Comprobar Código
                </button>
                <form id="complete-form" action="../complete_lesson.php" method="POST" class="hidden">
                    <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
                </form>
            </div>
        </aside>
        <main class="w-full md:w-2/3 xl:w-3/4 flex flex-col">
            <div class="h-1/2 flex flex-col bg-gray-900 border-b-4 border-gray-700">
                <div class="flex-shrink-0 bg-gray-900 p-2 flex justify-between items-center">
                    <h3 class="text-lg font-semibold"><i class="fas fa-code mr-2 text-gray-400"></i>Editor</h3>
                    <button id="reset-step-btn" class="text-xs text-gray-400 hover:text-white transition-colors"><i class="fas fa-redo mr-1"></i>Reiniciar Ejercicio</button>
                </div>
                <div class="flex-grow relative">
                    <textarea id="editor" class="w-full h-full p-4 bg-[#1e1e1e] text-white resize-none border-none outline-none" spellcheck="false"></textarea>
                </div>
            </div>
            <div class="h-1/2 flex flex-col bg-white">
                <div class="flex-shrink-0 bg-gray-200 p-2"><h3 class="text-lg font-semibold text-gray-800"><i class="fas fa-rocket mr-2 text-gray-600"></i>Vista Previa</h3></div>
                <div class="flex-grow"><iframe id="preview" class="w-full h-full border-none"></iframe></div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        const exercises = <?php echo json_encode($exercises); ?>;
        const isCompleted = <?php echo json_encode($is_completed); ?>;
        const completedStepsCount = <?php echo $completed_steps_count; ?>;
        const conceptView = document.getElementById('concept-view');
        const labView = document.getElementById('lab-view');
        const conceptTitle = document.getElementById('concept-title');
        const conceptTheory = document.getElementById('concept-theory');
        const conceptExample = document.getElementById('concept-example');
        const startLabBtn = document.getElementById('start-lab-btn');
        const backToTheoryBtn = document.getElementById('back-to-theory-btn');
        const editor = document.getElementById('editor');
        const preview = document.getElementById('preview');
        const stepsContainer = document.getElementById('steps-container');
        const missionObjective = document.getElementById('mission-objective');
        const checkCodeBtn = document.getElementById('check-code-btn');
        const resetStepBtn = document.getElementById('reset-step-btn');
        const progressBar = document.getElementById('progress-bar');
        const feedbackContainer = document.getElementById('feedback-container');
        const completeForm = document.getElementById('complete-form');

        let completedSteps = new Array(exercises.length).fill(false).map((val, index) => index < completedStepsCount);
        let currentStep = completedStepsCount < exercises.length ? completedStepsCount : exercises.length - 1;

        async function saveProgressToServer() {
            const completedCount = completedSteps.filter(Boolean).length;
            try {
                await fetch('../save_progress.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        lesson_id: <?php echo $lesson_id; ?>,
                        completed_steps: completedCount
                    })
                });
            } catch (error) {
                console.error('Failed to save progress:', error);
            }
        }

        function showConcept(stepIndex) {
            conceptTitle.textContent = `Día ${exercises[stepIndex].day}: ${exercises[stepIndex].title}`;
            conceptTheory.innerHTML = exercises[stepIndex].theory;
            conceptExample.textContent = exercises[stepIndex].example_code;
            hljs.highlightElement(conceptExample);
            conceptView.classList.remove('hidden');
            labView.classList.add('hidden');
        }

        function showLab() {
            conceptView.classList.add('hidden');
            labView.classList.remove('hidden');
            labView.classList.add('flex');
            loadStep(currentStep);
        }

        function renderSteps() {
            stepsContainer.innerHTML = '';
            exercises.forEach((exercise, index) => {
                const stepElement = document.createElement('div');
                stepElement.className = `step p-3 border-2 border-gray-700 rounded-lg transition-all ${index === currentStep ? 'active' : ''} ${completedSteps[index] ? 'completed' : ''}`;
                stepElement.innerHTML = `<div class="flex justify-between items-center"><h3 class="font-bold">Día ${exercise.day}: ${exercise.title}</h3><i class="fas ${completedSteps[index] ? 'fa-check-circle text-green-400' : 'fa-circle text-gray-600'}"></i></div>`;
                stepsContainer.appendChild(stepElement);
            });
        }

        function loadStep(stepIndex) {
            currentStep = stepIndex;
            editor.value = exercises[stepIndex].starter_code;
            missionObjective.textContent = exercises[stepIndex].objective;
            updatePreview();
            renderSteps();
            feedbackContainer.innerHTML = '';
        }

        function updatePreview() { preview.srcdoc = editor.value; }

        function updateProgress() {
            const completedCount = completedSteps.filter(Boolean).length;
            const progress = (completedCount / exercises.length) * 100;
            progressBar.style.width = `${progress}%`;
            if (completedCount === exercises.length) {
                checkCodeBtn.innerHTML = isCompleted ? '<i class="fas fa-check-double mr-2"></i>Semana Completada' : '<i class="fas fa-trophy mr-2"></i>Finalizar Semana';
                checkCodeBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                checkCodeBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                if (isCompleted) checkCodeBtn.disabled = true;
            }
        }

        function showFeedback(message, isSuccess) {
            feedbackContainer.innerHTML = `<div class="p-2 rounded-lg text-center font-semibold ${isSuccess ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300'}">${message}</div>`;
        }
        
        checkCodeBtn.addEventListener('click', () => {
            if (completedSteps.filter(Boolean).length === exercises.length) {
                if (!isCompleted) completeForm.submit();
                return;
            }
            try {
                const validationFn = new Function('preview', `return (() => { ${exercises[currentStep].validation} })()`);
                const isCorrect = validationFn(preview);
                if (isCorrect) {
                    showFeedback('¡Correcto! Pasando al siguiente nivel.', true);
                    if (!completedSteps[currentStep]) {
                        completedSteps[currentStep] = true;
                        saveProgressToServer();
                    }
                    updateProgress();
                    setTimeout(() => {
                        if (currentStep < exercises.length - 1) {
                            currentStep++;
                            showConcept(currentStep);
                        } else {
                            showFeedback('¡Felicidades! Has completado todos los ejercicios.', true);
                        }
                    }, 1500);
                } else {
                    showFeedback('Aún no está bien. ¡Revisa el código y sigue intentando!', false);
                }
            } catch (e) {
                showFeedback('Hubo un error en la vista previa. Revisa si hay etiquetas sin cerrar.', false);
                console.error("Error de validación:", e);
            }
        });

        startLabBtn.addEventListener('click', showLab);
        backToTheoryBtn.addEventListener('click', () => showConcept(currentStep));
        resetStepBtn.addEventListener('click', () => loadStep(currentStep));
        
        const selfClosingTags = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

        editor.addEventListener('input', (e) => {
            updatePreview();
            
            if (e.inputType === 'insertText' && e.data === '>') {
                const cursorPosition = editor.selectionStart;
                const textBeforeCursor = editor.value.substring(0, cursorPosition);
                const lastOpeningBracket = textBeforeCursor.lastIndexOf('<');
                
                if (lastOpeningBracket !== -1 && textBeforeCursor[lastOpeningBracket + 1] !== '/') {
                    const tagContent = textBeforeCursor.substring(lastOpeningBracket + 1, cursorPosition - 1);
                    const tagName = tagContent.split(' ')[0].replace(/>$/, '');

                    if (tagName && !selfClosingTags.includes(tagName)) {
                        const closingTag = `</${tagName}>`;
                        const textAfterCursor = editor.value.substring(cursorPosition);
                        editor.value = textBeforeCursor + closingTag + textAfterCursor;
                        editor.selectionStart = cursorPosition;
                        editor.selectionEnd = cursorPosition;
                        updatePreview();
                    }
                }
            }
        });
        
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(end);
                editor.selectionStart = editor.selectionEnd = start + 4;
            }
        });

        window.addEventListener('load', () => {
            updateProgress();
            showConcept(currentStep);
        });
    </script>
</body>
</html>
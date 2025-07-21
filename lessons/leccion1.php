<?php
require '../includes/auth.php';
require '../includes/db.php';

$lesson_id = 1;
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

// --- Definición de los Ejercicios y Teoría de la Semana ---
$exercises = [
    [
        'day' => 1,
        'title' => '¿Qué es la Web?',
        'objective' => 'Crea un encabezado <h1> que diga "Mi Biografía", un párrafo <p> con tu nombre y edad, y un subtítulo <h2> que diga "Mis Metas".',
        'theory' => 'HTML es el lenguaje que usamos para crear páginas web. Toda página tiene una estructura básica: <html>, que envuelve todo; <head>, para metadatos como el título de la pestaña; y <body>, donde va todo el contenido visible. Las etiquetas como <h1> (título principal) y <p> (párrafo) nos ayudan a organizar ese contenido.',
        'example_code' => "<!DOCTYPE html>
<html>
<head>
    <title>Título de la Pestaña</title>
</head>
<body>
    <h1>Título Principal Visible</h1>
    <p>Este es un párrafo de texto.</p>
</body>
</html>",
        'starter_code' => "<!DOCTYPE html>
<html>
<head>
    <title>Biografía</title>
</head>
<body>
    <!-- Completa tu biografía y metas -->
    <h1>Mi Biografía</h1>
    <p></p>
    <h2>Mis Metas</h2>
</body>
</html>",
        'validation' => "const h1 = preview.contentDocument.querySelector('h1'); const p = preview.contentDocument.querySelector('p'); const h2 = preview.contentDocument.querySelector('h2'); return h1 && h1.textContent.trim().toLowerCase() === 'mi biografía' && p && p.textContent.trim() !== '' && h2 && h2.textContent.trim().toLowerCase() === 'mis metas';"
    ],
    [
        'day' => 2,
        'title' => 'Texto y Listas',
        'objective' => 'Crea una lista ordenada (<ol>) con tus 5 series favoritas. Dentro de un <li>, pon el nombre de la serie en negritas (<strong>) y una descripción en cursiva (<em>).',
        'theory' => 'Además de <h1>, tenemos encabezados del <h2> al <h6> para distintas jerarquías. Para enfatizar texto, usamos <strong> (negrita) y <em> (itálica). Las listas son cruciales: <ol> para listas numeradas (ordenadas) y <ul> para listas con viñetas. Cada elemento de la lista siempre va dentro de una etiqueta <li>.',
        'example_code' => "<h2>Mi Top 3</h2>\n<ol>\n    <li><strong>Elemento 1:</strong> <em>Descripción 1</em></li>\n    <li>Elemento 2</li>\n    <li>Elemento 3</li>\n</ol>",
        'starter_code' => "<h2>Mi Top 5 Series</h2>\n<ol>\n    <li></li>\n    <li></li>\n    <li></li>\n    <li></li>\n    <li></li>\n</ol>",
        'validation' => "const ol = preview.contentDocument.querySelector('ol'); const lis = preview.contentDocument.querySelectorAll('li'); const strong = preview.contentDocument.querySelector('strong'); const em = preview.contentDocument.querySelector('em'); return ol && lis.length >= 5 && Array.from(lis).every(li => li.textContent.trim() !== '') && strong && em;"
    ],
    [
        'day' => 3,
        'title' => 'Imágenes y Enlaces',
        'objective' => 'Crea una página de homenaje con una imagen principal, un párrafo, un enlace que se abra en una pestaña nueva y una segunda imagen.',
        'theory' => 'La etiqueta <img> inserta imágenes. Necesita dos atributos clave: `src` (la URL de la imagen) y `alt` (texto alternativo). La etiqueta <a> crea enlaces. Necesita el atributo `href` (la URL de destino). Para que el enlace se abra en una nueva pestaña, añade `target=\"_blank\"`.',
        'example_code' => "<img src=\"https://placehold.co/150/38bdf8/ffffff?text=IMG\" alt=\"Texto descriptivo\">\n<a href=\"https://google.com\" target=\"_blank\">Ir a Google</a>",
        'starter_code' => "<h1>Homenaje a...</h1>\n\n<img src=\"\" alt=\"\">\n\n<p></p>\n\n<a href=\"\"></a>\n\n<img src=\"\" alt=\"\">",
        'validation' => "const p = preview.contentDocument.querySelector('p'); const a = preview.contentDocument.querySelector('a'); const imgs = preview.contentDocument.querySelectorAll('img'); return p && p.textContent.trim() !== '' && a && a.href.trim() !== '' && a.target === '_blank' && imgs.length >= 2 && Array.from(imgs).every(img => img.src.trim() !== '' && img.alt.trim() !== '');"
    ],
    [
        'day' => 4,
        'title' => 'Tablas y Divisores',
        'objective' => 'Crea una tabla con un horario de clases. La tabla debe tener al menos dos filas (<tr>), incluyendo la fila de encabezados (<th>).',
        'theory' => 'Las tablas organizan datos en filas y columnas. Se crean con <table>. Dentro, <tr> define una fila, <th> una celda de encabezado y <td> una celda de datos. La etiqueta <div> es un contenedor genérico que nos ayuda a agrupar y organizar secciones de nuestra página.',
        'example_code' => "<div>\n    <h2>Sección 1</h2>\n</div>\n<table>\n    <tr>\n        <th>Hora</th>\n        <th>Actividad</th>\n    </tr>\n    <tr>\n        <td>9:00 AM</td>\n        <td>Desayunar</td>\n    </tr>\n</table>",
        'starter_code' => "<h1>Mi Horario</h1>\n\n<div>\n    <h2>Lunes</h2>\n    <table>\n        <tr>\n            <th>Hora</th>\n            <th>Materia</th>\n        </tr>\n        <tr>\n            <td></td>\n            <td></td>\n        </tr>\n    </table>\n</div>",
        'validation' => "const table = preview.contentDocument.querySelector('table'); const trs = preview.contentDocument.querySelectorAll('tr'); return table && trs.length >= 2;"
    ],
    [
        'day' => 5,
        'title' => 'Proyecto Semanal 1',
        'objective' => 'Crea una página "Sobre Mí" completa: título, foto, descripción, lista de hobbies, tabla de comidas favoritas y una nueva sección con una lista de cosas que quieres aprender.',
        'theory' => '¡Es hora de unirlo todo! En este proyecto final de la semana, aplicarás todos los conceptos que hemos visto. Demuestra tu dominio de la estructura, texto, listas, multimedia y tablas para crear una página personal completa. ¡No hay una única respuesta correcta, sé creativo!',
        'example_code' => "<!-- ¡Combina todo lo que aprendiste! -->\n<h1>Tu Nombre</h1>\n<img src=\"...\" alt=\"...\">\n<p>...</p>\n<ul>...</ul>\n<table>...</table>",
        'starter_code' => "<!-- ¡Es tu turno de brillar! Construye tu página \"Sobre Mí\" aquí. -->\n",
        'validation' => "const h1 = preview.contentDocument.querySelector('h1'); const img = preview.contentDocument.querySelector('img'); const p = preview.contentDocument.querySelector('p'); const uls = preview.contentDocument.querySelectorAll('ul'); const table = preview.contentDocument.querySelector('table'); return h1 && img && p && uls.length >= 2 && table;"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semana 1: Fundamentos de HTML5</title>
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
        .step.active { border-color: #38bdf8; transform: scale(1.02); }
        .step.completed { border-color: #22c55e; background-color: #16a34a20; }
        .prose h1 { color: #38bdf8; }
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
                    <a href="../dashboard.php" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors no-underline whitespace-nowrap"><i class="fas fa-times-circle mr-1"></i> Salir</a>
                </div>
                <p id="concept-theory" class="text-lg md:text-xl leading-relaxed mb-6"></p>
                <h2>Ejemplo de Código:</h2>
                <pre><code id="concept-example" class="language-html rounded-lg text-base"></code></pre>
            </div>
            <div class="mt-8 text-center">
                <button id="start-lab-btn" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-4 px-10 rounded-lg transition-transform hover:scale-105 text-xl shadow-lg shadow-cyan-500/20">
                    <i class="fas fa-vial mr-2"></i>¡Entendido, al Laboratorio!
                </button>
            </div>
        </div>
    </div>

    <!-- Fase 2: Vista de Laboratorio -->
    <div id="lab-view" class="hidden h-screen flex flex-col md:flex-row">
        <aside class="w-full md:w-1/3 xl:w-1/4 p-6 bg-gray-800 overflow-y-auto flex flex-col">
            <div>
                <button id="back-to-theory-btn" class="text-cyan-400 hover:text-cyan-300 mb-4 block transition-colors"><i class="fas fa-arrow-left mr-2"></i>Volver a la Teoría</button>
                <h1 class="text-2xl font-bold text-cyan-400">Semana 1: Laboratorio</h1>
                <div class="w-full bg-gray-700 rounded-full h-2.5 my-4">
                    <div id="progress-bar" class="bg-cyan-400 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div id="mission-container" class="mb-4 p-4 bg-cyan-900/50 rounded-lg border border-cyan-500/50 shadow-inner">
                <h2 class="font-bold text-lg text-cyan-300 mb-2 flex items-center"><i class="fas fa-bullseye mr-3"></i>Tu Misión Actual</h2>
                <p id="mission-objective" class="text-gray-200 text-sm font-medium leading-relaxed"></p>
            </div>
            <div id="steps-container" class="space-y-3 flex-grow"></div>
            <div class="mt-auto pt-4">
                <div id="feedback-container" class="h-12 mb-2"></div>
                <button id="check-code-btn" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
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
                checkCodeBtn.classList.remove('bg-cyan-600', 'hover:bg-cyan-700');
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
                // --- CORRECCIÓN DEFINITIVA ---
                // La función de validación ahora se crea sin el `return` extra, lo que evita el error.
                const validationFn = new Function('preview', exercises[currentStep].validation);
                const isCorrect = validationFn(preview);
                
                if (isCorrect) {
                    showFeedback('¡Correcto! Pasando al siguiente nivel.', true);
                    if (!completedSteps[currentStep]) {
                        completedSteps[currentStep] = true;
                        saveProgressToServer(); // Guardar progreso
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
        editor.addEventListener('input', updatePreview);
        
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = editor.selectionStart;
                editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(start);
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
<?php
require '../includes/auth.php';
require '../includes/db.php';

$lesson_id = 4;
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

// --- Definición de los Ejercicios y Teoría de la Semana 4 ---
$exercises = [
    [
        'day' => 16,
        'title' => 'Planificación del Proyecto',
        'objective' => 'Elige tu idea de proyecto y crea el archivo HTML con la estructura básica de todas las secciones que tendrá (ej. Inicio, Sobre Mí, Galería, Contacto). Extra: Define la paleta de colores y las fuentes que usarás en un comentario en tu CSS.',
        'theory' => 'Todo gran proyecto empieza con un plan. Antes de escribir una línea de código, piensa: ¿qué quiero construir? Un portafolio, una página para un negocio ficticio, un sitio de fans... Dibuja un boceto simple en papel. Hoy, nos enfocaremos en crear el "esqueleto" de tu proyecto usando solo etiquetas HTML semánticas como `&lt;header>`, `&lt;main>`, `&lt;section>` y `&lt;footer>`.',
        'example_code' => "<header></header>\n<main>\n    <section id=\"inicio\"></section>\n    <section id=\"galeria\"></section>\n</main>\n<footer></footer>",
        'starter_code' => "\n",
        'validation' => "const main = preview.contentDocument.querySelector('main'); const section = preview.contentDocument.querySelector('section'); return main && section;"
    ],
    [
        'day' => 17,
        'title' => 'Maquetación y Estilo Base',
        'objective' => 'Traduce tu boceto a HTML y CSS. Usa Flexbox para el layout principal y aplica la paleta de colores y fuentes que usarás. Extra: Crea al menos dos secciones de tu página (ej. \'Inicio\' y \'Sobre Mí\').',
        'theory' => 'Ahora, damos vida al esqueleto. Usando CSS, definiremos los estilos base: colores, tipografías y, lo más importante, la maquetación. Aplicaremos `display: flex` a los contenedores principales para organizar las secciones de nuestra página como lo planeamos en el boceto. Este es el momento de definir la "personalidad" visual de tu sitio.',
        'example_code' => "<style>\n    body { font-family: 'Arial'; background: #f0f0f0; }\n    .container { display: flex; justify-content: center; }\n</style>",
        'starter_code' => "\n<style>\n\n</style>\n",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('display:flex');"
    ],
    [
        'day' => 18,
        'title' => 'Contenido e Interactividad',
        'objective' => 'Rellena el sitio con el contenido real. Añade al menos una funcionalidad con JS, como un menú móvil que aparece y desaparece. Mini-Reto: Asegúrate de que tu funcionalidad JS esté bien comentada.',
        'theory' => 'Una página bonita necesita contenido. Es hora de añadir los textos finales, las imágenes de tu galería y toda la información. Además, le daremos inteligencia con JavaScript. Una buena primera interacción es un "menú hamburguesa" para móviles, o botones que muestren u oculten información. ¡Piensa en qué puede hacer tu página más útil o divertida para el usuario!',
        'example_code' => "<script>\n    const boton = document.getElementById('miBoton');\n    boton.addEventListener('click', () => {\n        alert('¡Funciona!');\n    });\n</script>",
        'starter_code' => "\n",
        'validation' => "const script = preview.contentDocument.querySelector('script'); return script && script.textContent.trim() !== '';"
    ],
    [
        'day' => 19,
        'title' => 'Pulido y Pruebas',
        'objective' => 'Arregla cualquier error de diseño o de código. Añade pequeños detalles como sombras a las cajas (`box-shadow`) o transiciones suaves a los botones (`transition`). Extra: Usa el inspector del navegador para ver cómo se ve tu página en un tamaño de celular.',
        'theory' => 'Los detalles marcan la diferencia. Esta fase se llama "pulido". Revisa tu sitio en busca de errores, pide a un amigo que lo pruebe. Añade pequeños toques de calidad: una sombra sutil con `box-shadow` puede hacer que una tarjeta resalte; una `transition` en un botón hace que el cambio de color al pasar el ratón sea suave y elegante.',
        'example_code' => "<style>\n    .boton:hover {\n        background-color: blue;\n        transition: background-color 0.3s ease;\n    }\n</style>",
        'starter_code' => "\n",
        'validation' => "const style = preview.contentDocument.querySelector('style'); if (!style) return false; const css = style.textContent.toLowerCase(); return css.includes('transition') || css.includes('box-shadow');"
    ],
    [
        'day' => 20,
        'title' => '¡Día de Demo!',
        'objective' => '¡Lo lograste! Has completado el bootcamp. Este último paso es para celebrar tu increíble trabajo. ¡Presiona el botón para finalizar!',
        'theory' => '¡Felicidades, desarrollador! Has completado un viaje increíble desde los fundamentos de HTML hasta crear un proyecto completo y funcional. Este es el resultado de tu esfuerzo y dedicación. Siéntete orgulloso de lo que has construido. Este es solo el comienzo de tu aventura en el desarrollo web. ¡Sigue aprendiendo, sigue construyendo y nunca dejes de ser curioso!',
        'example_code' => "",
        'starter_code' => "<h1>¡MI PROYECTO ESTÁ TERMINADO!</h1>\n<p>¡Gracias por este increíble bootcamp!</p>",
        'validation' => "return true;"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semana 4: ¡A Construir! Proyecto Final</title>
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
        .step.active { border-color: #f43f5e; transform: scale(1.02); }
        .step.completed { border-color: #22c55e; background-color: #16a34a20; }
        .prose h1 { color: #f43f5e; }
        .prose h2 { color: white; }
        .prose p, .prose li { color: #d1d5db; }
        .prose code { background-color: #374151; color: #f3f4f6; padding: 0.2em 0.4em; border-radius: 0.25rem; }
    </style>
</head>
<body class="bg-gray-900 text-white overflow-hidden">
    <div id="concept-view" class="h-screen flex flex-col items-center justify-center p-6 sm:p-8">
        <div class="w-full max-w-4xl text-left bg-gray-800/50 p-8 rounded-2xl shadow-2xl backdrop-blur-sm border border-gray-700">
            <div class="prose prose-invert max-w-none">
                <div class="flex justify-between items-start">
                    <h1 id="concept-title" class="text-4xl md:text-5xl font-bold mt-0 mb-4"></h1>
                    <a href="../dashboard.php" class="text-sm text-rose-400 hover:text-rose-300 transition-colors no-underline whitespace-nowrap"><i class="fas fa-times-circle mr-1"></i> Salir</a>
                </div>
                <p id="concept-theory" class="text-lg md:text-xl leading-relaxed mb-6"></p>
                <h2>Ejemplo de Código:</h2>
                <pre><code id="concept-example" class="language-html rounded-lg text-base"></code></pre>
            </div>
            <div class="mt-8 text-center">
                <button id="start-lab-btn" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-10 rounded-lg transition-transform hover:scale-105 text-xl shadow-lg shadow-rose-500/20">
                    <i class="fas fa-vial mr-2"></i>¡Entendido, al Laboratorio!
                </button>
            </div>
        </div>
    </div>

    <div id="lab-view" class="hidden h-screen flex flex-col md:flex-row">
        <aside class="w-full md:w-1/3 xl:w-1/4 p-6 bg-gray-800 overflow-y-auto flex flex-col">
            <div>
                <button id="back-to-theory-btn" class="text-rose-400 hover:text-rose-300 mb-4 block transition-colors"><i class="fas fa-arrow-left mr-2"></i>Volver a la Teoría</button>
                <h1 class="text-2xl font-bold text-rose-400">Semana 4: Laboratorio</h1>
                <div class="w-full bg-gray-700 rounded-full h-2.5 my-4">
                    <div id="progress-bar" class="bg-rose-400 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div id="mission-container" class="mb-4 p-4 bg-rose-900/50 rounded-lg border border-rose-500/50 shadow-inner">
                <h2 class="font-bold text-lg text-rose-300 mb-2 flex items-center"><i class="fas fa-bullseye mr-3"></i>Tu Misión Actual</h2>
                <p id="mission-objective" class="text-gray-200 text-sm font-medium leading-relaxed"></p>
            </div>
            <div id="steps-container" class="space-y-3 flex-grow"></div>
            <div class="mt-auto pt-4">
                <div id="feedback-container" class="h-12 mb-2"></div>
                <button id="check-code-btn" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
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
            // Para el proyecto final, el código de los pasos anteriores se mantiene
            if (stepIndex > 0 && editor.value.length < exercises[stepIndex].starter_code.length) {
                 editor.value += `\n\n${exercises[stepIndex].starter_code}`;
            } else if (stepIndex === 0) {
                 editor.value = exercises[stepIndex].starter_code;
            }
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
                checkCodeBtn.classList.remove('bg-rose-600', 'hover:bg-rose-700');
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
                const validationFn = new Function('preview', `return ${exercises[currentStep].validation}`);
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
        editor.addEventListener('input', updatePreview);
        
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
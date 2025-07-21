<?php
require '../includes/auth.php';
require '../includes/db.php';

$lesson_id = 3;
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

// --- Definición de los Ejercicios y Teoría de la Semana 3 ---
$exercises = [
    [
        'day' => 11,
        'title' => 'Introducción a JavaScript',
        'objective' => 'Declara una variable con tu edad, multiplícala por 7 y muestra el resultado en la consola. Mini-Reto: Declara otra variable con tu nombre y muestra un saludo completo en la consola.',
        'theory' => 'JavaScript (JS) es un lenguaje de programación que nos permite añadir interactividad. Usamos variables para guardar datos (con `let` o `const`). El comando `console.log()` es tu mejor amigo: te permite imprimir cualquier valor en la consola del navegador para depurar tu código.',
        'example_code' => "const nombre = \"Ana\";\nlet edad = 25;\nconsole.log(nombre);\nconsole.log(\"El doble de la edad es: \" + (edad * 2));",
        'starter_code' => "// Escribe tu código JavaScript aquí\n",
        'validation' => "const code = editor.value; return (code.includes('let') || code.includes('const')) && code.includes('*') && code.includes('console.log') && code.includes('nombre');"
    ],
    [
        'day' => 12,
        'title' => 'Funciones y Eventos',
        'objective' => 'Crea un botón que al darle clic, cambie el texto de un párrafo. Mini-Reto: Crea un segundo botón que, al darle clic, revierta el texto del párrafo a su estado original.',
        'theory' => 'Las funciones son bloques de código reutilizables. Las declaramos con `function miFuncion() { ... }`. Para interactuar con el HTML, usamos eventos. El más común es `onclick`. Primero, seleccionamos un elemento HTML con `document.getElementById(\"su-id\")`, y luego le decimos qué función ejecutar cuando ocurra un evento.',
        'example_code' => "const miElemento = document.getElementById(\"titulo\");\n\nfunction cambiarColor() {\n    miElemento.style.color = \"red\";\n}",
        'starter_code' => "<p id=\"mensaje\">Hola</p>\n<button onclick=\"cambiarTexto()\">Haz Clic</button>\n<button onclick=\"revertirTexto()\">Revertir</button>\n\n<script>\nfunction cambiarTexto() {\n    // Tu código aquí\n}\n\nfunction revertirTexto() {\n    // Tu código del mini-reto aquí\n}\n</script>",
        'validation' => "const code = editor.value; return code.includes('function cambiarTexto') && code.includes('function revertirTexto') && code.includes('getElementById') && code.includes('textContent');"
    ],
    [
        'day' => 13,
        'title' => 'Manipulación del DOM',
        'objective' => 'Crea un botón que alterne entre un tema claro y uno oscuro. Mini-Reto: Haz que el texto del botón también cambie (ej. de "Activar Modo Oscuro" a "Activar Modo Claro").',
        'theory' => 'El DOM (Document Object Model) es la representación de tu HTML en JavaScript. Podemos manipularlo para cambiar casi cualquier cosa. Con `.style`, podemos acceder y modificar las propiedades CSS de un elemento. Con `.innerHTML` o `.textContent`, podemos cambiar el contenido HTML que se muestra.',
        'example_code' => "function modoNoche() {\n    const body = document.body;\n    body.style.backgroundColor = \"black\";\n    body.style.color = \"white\";\n}",
        'starter_code' => "<h1>Modo Día/Noche</h1>\n<button id=\"theme-button\">Activar Modo Oscuro</button>\n\n<script>\n\n</script>",
        'validation' => "const code = editor.value; return code.includes('document.body') && code.includes('.style.backgroundColor') && code.includes('.style.color') && code.includes('textContent');"
    ],
    [
        'day' => 14,
        'title' => 'Condicionales y Arrays',
        'objective' => 'Crea un juego de "Adivina el Número". Un mensaje debe decir si el número del usuario es mayor, menor o igual al número secreto. Mini-Reto: Muestra el número de intentos que le tomó al usuario adivinar.',
        'theory' => 'La lógica de programación se basa en tomar decisiones. La estructura `if (condicion) { ... } else { ... }` nos permite ejecutar diferente código si una condición es verdadera o falsa. Los `arrays` son listas que nos permiten guardar múltiples valores en una sola variable.',
        'example_code' => "let edad = 18;\nif (edad >= 18) {\n    console.log(\"Es mayor de edad\");\n} else {\n    console.log(\"Es menor de edad\");\n}",
        'starter_code' => "<input type=\"number\" id=\"numeroUsuario\" placeholder=\"Escribe un número\">\n<button>Adivinar</button>\n<p id=\"resultado\"></p>\n\n<script>\n    const numeroSecreto = 7;\n    let intentos = 0;\n    // Tu lógica aquí\n</script>",
        'validation' => "const code = editor.value; return code.includes('if') && code.includes('else') && (code.includes('==') || code.includes('===')) && code.includes('intentos');"
    ],
    [
        'day' => 15,
        'title' => 'Proyecto Semanal 3',
        'objective' => 'Crea un generador de excusas al azar. Mini-Reto: Añade un `prompt()` para que el usuario pueda agregar su propia excusa a la lista.',
        'theory' => '¡Vamos a crear una mini-app! Para seleccionar un elemento al azar de un array, usamos `Math.random()`. Esta función nos da un número decimal entre 0 y 1. Multiplicándolo por la longitud del array (`array.length`) y redondeándolo hacia abajo con `Math.floor()`, obtenemos un índice aleatorio válido para nuestro array.',
        'example_code' => "const colores = [\"rojo\", \"verde\", \"azul\"];\nconst indiceAleatorio = Math.floor(Math.random() * colores.length);\nconst colorAzar = colores[indiceAleatorio];\nconsole.log(colorAzar);",
        'starter_code' => "<h1>Generador de Excusas</h1>\n<p id=\"excusa\">...</p>\n<button>Generar Nueva Excusa</button>\n<button>Añadir Excusa</button>\n\n<script>\n    const excusas = [\n        'Mi perro se comió la tarea.',\n        'Hubo una invasión alienígena en mi calle.',\n        'Me quedé atrapado en el ascensor con un unicornio.'\n    ];\n\n    // Tu lógica aquí\n</script>",
        'validation' => "const code = editor.value; return code.includes('[') && code.includes(']') && code.includes('Math.random') && code.includes('Math.floor') && code.includes('prompt');"
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semana 3: La Magia de JavaScript</title>
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
        .step.active { border-color: #d946ef; transform: scale(1.02); }
        .step.completed { border-color: #22c55e; background-color: #16a34a20; }
        .prose h1 { color: #d946ef; }
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
                    <a href="../dashboard.php" class="text-sm text-fuchsia-400 hover:text-fuchsia-300 transition-colors no-underline whitespace-nowrap"><i class="fas fa-times-circle mr-1"></i> Salir</a>
                </div>
                <p id="concept-theory" class="text-lg md:text-xl leading-relaxed mb-6"></p>
                <h2>Ejemplo de Código:</h2>
                <pre><code id="concept-example" class="language-js rounded-lg text-base"></code></pre>
            </div>
            <div class="mt-8 text-center">
                <button id="start-lab-btn" class="bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-bold py-4 px-10 rounded-lg transition-transform hover:scale-105 text-xl shadow-lg shadow-fuchsia-500/20">
                    <i class="fas fa-vial mr-2"></i>¡Entendido, al Laboratorio!
                </button>
            </div>
        </div>
    </div>

    <!-- Fase 2: Vista de Laboratorio -->
    <div id="lab-view" class="hidden h-screen flex flex-col md:flex-row">
        <aside class="w-full md:w-1/3 xl:w-1/4 p-6 bg-gray-800 overflow-y-auto flex flex-col">
            <div>
                <button id="back-to-theory-btn" class="text-fuchsia-400 hover:text-fuchsia-300 mb-4 block transition-colors"><i class="fas fa-arrow-left mr-2"></i>Volver a la Teoría</button>
                <h1 class="text-2xl font-bold text-fuchsia-400">Semana 3: Laboratorio</h1>
                <div class="w-full bg-gray-700 rounded-full h-2.5 my-4">
                    <div id="progress-bar" class="bg-fuchsia-400 h-2.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            <div id="mission-container" class="mb-4 p-4 bg-fuchsia-900/50 rounded-lg border border-fuchsia-500/50 shadow-inner">
                <h2 class="font-bold text-lg text-fuchsia-300 mb-2 flex items-center"><i class="fas fa-bullseye mr-3"></i>Tu Misión Actual</h2>
                <p id="mission-objective" class="text-gray-200 text-sm font-medium leading-relaxed"></p>
            </div>
            <div id="steps-container" class="space-y-3 flex-grow"></div>
            <div class="mt-auto pt-4">
                <div id="feedback-container" class="h-12 mb-2"></div>
                <button id="check-code-btn" class="w-full bg-fuchsia-600 hover:bg-fuchsia-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center">
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
                checkCodeBtn.classList.remove('bg-fuchsia-600', 'hover:bg-fuchsia-700');
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
                const isCorrect = new Function('editor', 'preview', `return ${exercises[currentStep].validation}`)(editor, preview);
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
                showFeedback('Hubo un error en tu código. Revisa la consola para más detalles.', false);
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
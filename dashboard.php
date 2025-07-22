<?php
require 'includes/auth.php';
require 'includes/db.php';

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, email, avatar, points FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// Nueva lógica para obtener el progreso completo de las lecciones
$completed_lessons = [];
$sql_lessons = "SELECT lesson_id, points_awarded, completed_steps FROM user_lessons WHERE user_id = ?";
$stmt_lessons = $conn->prepare($sql_lessons);
$stmt_lessons->bind_param("i", $user_id);
$stmt_lessons->execute();
$lessons_result = $stmt_lessons->get_result();
while ($row = $lessons_result->fetch_assoc()) {
    $completed_lessons[$row['lesson_id']] = $row;
}
$stmt_lessons->close();

$weeks = [
    1 => ['title' => 'dia 1: Los Cimientos de la Web - HTML', 'color' => 'cyan-400', 'file' => 'lessons/leccion1.php'],
    2 => ['title' => 'dia 1: Dando Estilo con CSS', 'color' => 'indigo-400', 'file' => 'lessons/leccion2.php'],
    3 => ['title' => 'dia 2: La Magia de JavaScript', 'color' => 'fuchsia-400', 'file' => 'lessons/leccion3.php'],
    4 => ['title' => 'dia 2: ¡A Construir! Proyecto Final', 'color' => 'rose-400', 'file' => 'lessons/leccion4.php'],
];

// Temas clave de cada semana, basados en tu plan de estudios
$weekly_topics = [
    1 => ['leccion 1: ¿Qué es la Web?', 'leccion 2: Texto y Listas', 'leccion 3: Imágenes y Enlaces', 'leccion 4: Tablas y Divisores', 'leccion 5: Proyecto de seccion 1'],
    2 => ['leccion 6: Introducción a CSS', 'leccion 7: El Modelo de Caja', 'leccion 8: Layout con Flexbox (Parte 1)', 'leccion 9: Layout con Flexbox (Parte 2)', 'leccion 10: Proyecto de seccion  2'],
    3 => ['leccion 11: Introducción a JavaScript', 'leccion 12: Funciones y Eventos', 'leccion 13: Manipulación del DOM', 'leccion 14: Condicionales y Arrays', 'leccion 15: Proyecto de seccion 3'],
    4 => ['leccion 16: Planificación del Proyecto', 'leccion 17: Maquetación y Estilo Base', 'leccion 18: Contenido e Interactividad', 'leccion 19: Pulido y Pruebas', 'leccion 20: ¡Día de Demo!']
];

// Lógica mejorada para determinar la lección más alta completada
$highest_completed_lesson = 0;
foreach ($completed_lessons as $lesson_id => $details) {
    if ($details['points_awarded'] !== null && $lesson_id > $highest_completed_lesson) {
        $highest_completed_lesson = $lesson_id;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Código Bootcamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; }
        .cta-button { transition: all 0.3s ease; }
        .cta-button:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="text-white">

    <header class="bg-gray-900/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">
                <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
            </a>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="dashboard.php" class="text-cyan-400 font-semibold">Dashboard</a>
                <a href="leaderboard.php" class="hover:text-cyan-400 transition-colors">Leaderboard</a>
                
                <div class="relative group">
                    <button class="flex items-center space-x-2">
                        <?php 
                            $avatar_url = (isset($user['avatar']) && $user['avatar'] !== 'assets/img/default-avatar.png') 
                                ? htmlspecialchars($user['avatar']) 
                                : 'https://placehold.co/100x100/0a0a0a/ffffff?text=' . strtoupper(substr($user['username'], 0, 1));
                        ?>
                        <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover border-2 border-gray-700">
                        <span class="font-semibold"><?php echo htmlspecialchars($user['username']); ?></span>
                        <svg class="h-5 w-5 text-gray-400 transition-transform duration-300 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Mi Perfil</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Cerrar Sesión</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-6 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold">Bienvenido, <span class="text-cyan-400"><?php echo htmlspecialchars($user['username']); ?></span></h1>
            <p class="text-gray-400 mt-2 text-lg">Tu viaje para convertirte en un desarrollador web continúa aquí. ¡Sigue así!</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <?php foreach ($weeks as $week_num => $week_data): ?>
                <?php
                    $is_completed = isset($completed_lessons[$week_num]) && $completed_lessons[$week_num]['points_awarded'] !== null;
                    $is_unlocked = ($week_num <= $highest_completed_lesson + 1);
                    $color_parts = explode('-', $week_data['color']);
                    $main_color = $color_parts[0];
                ?>
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg transition-all duration-300 <?php echo $is_unlocked ? 'opacity-100' : 'opacity-50'; ?>">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <div class="flex-grow">
                            <h2 class="text-2xl font-bold text-<?php echo $week_data['color']; ?>">
                                <?php echo $week_data['title']; ?>
                            </h2>
                            <div class="mt-4 border-l-2 border-gray-700 pl-4">
                                <h3 class="text-sm font-semibold text-gray-400 mb-2">Temas de las lecciones:</h3>
                                <ul class="space-y-1 text-sm text-gray-300">
                                    <?php foreach ($weekly_topics[$week_num] as $topic): ?>
                                        <li class="flex items-center">
                                            <svg class="h-3 w-3 mr-2 text-<?php echo $main_color; ?>-400 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                            <?php echo $topic; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-6 sm:mt-0 sm:ml-6 flex-shrink-0">
                             <?php if ($is_unlocked): ?>
                                <?php if ($is_completed): ?>
                                    <a href="<?php echo $week_data['file']; ?>" class="flex items-center justify-center w-full sm:w-auto bg-green-600 text-white font-bold py-3 px-6 rounded-lg cta-button">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        Completado
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $week_data['file']; ?>" class="w-full sm:w-auto bg-<?php echo $main_color; ?>-600 hover:bg-<?php echo $main_color; ?>-700 text-white font-bold py-3 px-6 rounded-lg cta-button shadow-lg shadow-<?php echo $main_color; ?>-500/20">
                                        Empezar lecciones
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button disabled class="w-full sm:w-auto bg-gray-700 text-gray-500 font-bold py-3 px-6 rounded-lg cursor-not-allowed flex items-center">
                                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                                    Bloqueado
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-1 space-y-8">
                <div class="bg-gray-900 rounded-xl p-6 text-center shadow-lg">
                    <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="h-32 w-32 rounded-full object-cover mx-auto mb-4 border-4 border-cyan-400">
                    <h3 class="text-2xl font-bold"><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p class="text-gray-400"><?php echo htmlspecialchars($user['email']); ?></p>
                    <a href="profile.php" class="mt-4 inline-block bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg cta-button">Editar Perfil</a>
                </div>
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-cyan-400">Tu Progreso</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-base font-medium text-white">Lecciones Completadas</span>
                                <span class="text-sm font-medium text-white"><?php echo count(array_filter($completed_lessons, fn($l) => $l['points_awarded'] !== null)); ?> de <?php echo count($weeks); ?></span>
                            </div>
                            <?php $progress_percentage = count($weeks) > 0 ? (count(array_filter($completed_lessons, fn($l) => $l['points_awarded'] !== null)) / count($weeks)) * 100 : 0; ?>
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-cyan-500 h-2.5 rounded-full" style="width: <?php echo $progress_percentage; ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <p class="text-lg">Puntos Acumulados: <span class="font-bold text-2xl text-cyan-400"><?php echo htmlspecialchars($user['points'] ?? 0); ?></span></p>
                        </div>
                        <a href="leaderboard.php" class="w-full mt-2 block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg cta-button">Ver Leaderboard</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
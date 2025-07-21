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

$completed_lessons = [];
$sql_lessons = "SELECT lesson_id FROM user_lessons WHERE user_id = ?";
$stmt_lessons = $conn->prepare($sql_lessons);
$stmt_lessons->bind_param("i", $user_id);
$stmt_lessons->execute();
$lessons_result = $stmt_lessons->get_result();
while ($row = $lessons_result->fetch_assoc()) {
    $completed_lessons[$row['lesson_id']] = true;
}
$stmt_lessons->close();

$all_lessons = [];
$sql_all_lessons = "SELECT id, title, file_path, week FROM lessons ORDER BY id ASC";
$result_all_lessons = $conn->query($sql_all_lessons);
while ($row = $result_all_lessons->fetch_assoc()) {
    $all_lessons[$row['id']] = $row;
}

$weeks = [
    1 => ['title' => 'Semana 1: Los Cimientos de la Web - HTML', 'color' => 'cyan-400', 'file' => 'lessons/leccion1.php'],
    2 => ['title' => 'Semana 2: Dando Estilo con CSS', 'color' => 'indigo-400', 'file' => 'lessons/leccion2.php'],
    3 => ['title' => 'Semana 3: La Magia de JavaScript', 'color' => 'fuchsia-400', 'file' => 'lessons/leccion3.php'],
    4 => ['title' => 'Semana 4: ¡A Construir! Proyecto Final', 'color' => 'rose-400', 'file' => 'lessons/leccion4.php'],
];

// Temas clave de cada semana
$weekly_topics = [
    1 => ['Estructura HTML', 'Texto y Listas', 'Imágenes y Enlaces', 'Tablas y Divisores', 'Proyecto Semanal'],
    2 => ['Selectores CSS', 'Modelo de Caja', 'Flexbox (Parte 1)', 'Flexbox (Parte 2)', 'Proyecto Semanal'],
    3 => ['Variables y Funciones', 'Eventos y DOM', 'Manipulación de Estilos', 'Condicionales y Arrays', 'Proyecto Semanal'],
    4 => ['Planificación', 'Maquetación', 'Interactividad', 'Pulido y Pruebas', '¡Día de Demo!']
];

$highest_completed_lesson = empty($completed_lessons) ? 0 : max(array_keys($completed_lessons));
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
        /* La lógica del dropdown ahora se manejará con las clases 'group' y 'group-hover' de Tailwind, por lo que el CSS personalizado ya no es necesario aquí. */
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
                
                <!-- INICIO DE LA CORRECCIÓN: Se añade la clase 'group' al contenedor principal -->
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
                    <!-- El menú ahora se hace visible cuando se hace hover sobre el 'group' (el div padre) -->
                    <div class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl py-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Mi Perfil</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Cerrar Sesión</a>
                    </div>
                </div>
                <!-- FIN DE LA CORRECCIÓN -->
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
                    $is_completed = isset($completed_lessons[$week_num]);
                    $is_unlocked = ($week_num <= $highest_completed_lesson + 1);
                    $color_parts = explode('-', $week_data['color']);
                    $main_color = $color_parts[0];
                ?>
                <div class="bg-gray-900 rounded-xl p-6 shadow-lg transition-all duration-300 <?php echo $is_unlocked ? 'opacity-100' : 'opacity-50'; ?>">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-<?php echo $week_data['color']; ?>">
                                <?php echo $week_data['title']; ?>
                            </h2>
                            <!-- Lista de Temas -->
                            <ul class="mt-3 space-y-1 text-sm text-gray-400 list-disc list-inside">
                                <?php foreach ($weekly_topics[$week_num] as $topic): ?>
                                    <li><?php echo $topic; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0">
                             <?php if ($is_unlocked): ?>
                                <?php if ($is_completed): ?>
                                    <a href="<?php echo $week_data['file']; ?>" class="flex items-center justify-center w-full sm:w-auto bg-green-600 text-white font-bold py-3 px-6 rounded-lg cta-button">
                                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        Completado
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $week_data['file']; ?>" class="w-full sm:w-auto bg-<?php echo $main_color; ?>-600 hover:bg-<?php echo $main_color; ?>-700 text-white font-bold py-3 px-6 rounded-lg cta-button shadow-lg shadow-<?php echo $main_color; ?>-500/20">
                                        Empezar Semana
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
                                <span class="text-base font-medium text-white">Semanas Completadas</span>
                                <span class="text-sm font-medium text-white"><?php echo count($completed_lessons); ?> de <?php echo count($weeks); ?></span>
                            </div>
                            <?php $progress_percentage = count($weeks) > 0 ? (count($completed_lessons) / count($weeks)) * 100 : 0; ?>
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
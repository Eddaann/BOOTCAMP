<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_lesson_page = isset($is_lesson_page) && $is_lesson_page;
$base_path = $is_lesson_page ? '../' : './';

// Inicializar variables de usuario
$user_avatar_url = $base_path . 'assets/images/avatar_default.png'; // Un avatar por defecto

// Si el usuario está logueado, obtener su información
if (isset($_SESSION['user_id'])) {
    // Es una buena práctica tener la conexión a la BD aquí si la necesitamos en el header
    require_once __DIR__ . '/db.php'; 
    
    $stmt_user_header = $pdo->prepare("SELECT id, avatar FROM users WHERE id = ?");
    $stmt_user_header->execute([$_SESSION['user_id']]);
    $user_header = $stmt_user_header->fetch();

    if ($user_header && !empty($user_header['avatar']) && file_exists($base_path . $user_header['avatar'])) {
        $user_avatar_url = $base_path . htmlspecialchars($user_header['avatar']);
    } elseif ($user_header) {
        // Usar un placeholder consistente si no hay avatar personalizado
        $user_avatar_url = 'https://i.pravatar.cc/40?u=' . $user_header['id'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>Bootcamp Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $base_path ?>assets/css/styles.css" />
</head>
<body>
<header class="navbar">
    <div class="container">
        <h1><a href="<?= $base_path ?>index.php">Bootcamp Web</a></h1>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= $base_path ?>dashboard.php">Mi Panel</a>
                <a href="<?= $base_path ?>leaderboard.php">Ranking</a>
                
                <div class="user-menu">
                    <button id="user-menu-button" class="user-menu-toggle">
                        <img src="<?= $user_avatar_url ?>" alt="Mi Avatar" class="navbar-avatar">
                    </button>
                    <div id="user-menu-dropdown" class="user-menu-dropdown">
                        <a href="<?= $base_path ?>profile.php">Editar Perfil</a>
                        <a href="<?= $base_path ?>logout.php">Cerrar Sesión</a>
                    </div>
                </div>

            <?php else: ?>
                <a href="<?= $base_path ?>index.php">Inicio</a>
                <a href="<?= $base_path ?>login.php">Iniciar Sesión</a>
            <?php endif; ?>
            
            <button id="theme-toggle" title="Cambiar tema">
                <svg id="theme-toggle-dark-icon" class="toggle-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg id="theme-toggle-light-icon" class="toggle-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
        </nav>
    </div>
</header>
<main>

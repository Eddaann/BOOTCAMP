<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- CÁLCULO AUTOMÁTICO DE LA RUTA BASE ---
// Esta sección calcula la ruta correcta sin importar dónde alojes el proyecto.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// Reemplaza las diagonales invertidas de Windows por diagonales normales.
$script_path = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// Si el proyecto está en la raíz, la ruta es solo '/', si no, añade una diagonal al final.
$base_url = rtrim($script_path, '/') . '/';

// Si estás en una subcarpeta como 'lessons', necesitamos subir un nivel.
if (strpos($_SERVER['REQUEST_URI'], '/lessons/') !== false) {
    $base_url = dirname($base_url) . '/';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Bootcamp Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/styles.css" />
</head>
<body>
<header class="navbar">
    <div class="container">
        <h1><a href="<?= $base_url ?>index.php">Bootcamp Web</a></h1>
        <nav>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="<?= $base_url ?>dashboard.php">Mi Panel</a>
                <a href="<?= $base_url ?>logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="<?= $base_url ?>login.php">Iniciar Sesión</a>
                <a href="<?= $base_url ?>register.php">Registrarse</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
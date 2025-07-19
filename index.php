<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bootcamp - Crea tu primera página web</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <header class="navbar">
    <div class="container">
      <h1><a href="index.php" style="color:white; text-decoration:none;">Bootcamp Web</a></h1>
      <nav>
        <a href="index.php">Inicio</a>
        <?php if (!isset($_SESSION['user_id'])): ?>
          <a href="login.php">Iniciar Sesión</a>
          <a href="register.php">Registrarse</a>
        <?php else: ?>
          <a href="dashboard.php">Mi Panel</a>
          <a href="logout.php">Cerrar Sesión</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <section class="hero">
    <div class="container">
      <h2>¡Crea tu primera página web desde cero!</h2>
      <p>Un bootcamp gratuito para que aprendas las bases del desarrollo web.</p>
      <a href="register.php" class="btn">Regístrate Ahora</a>
    </div>
  </section>

  <footer>
    <div class="container">
      <p>&copy; <?= date('Y') ?> Bootcamp Web. Todos los derechos reservados.</p>
    </div>
  </footer>
</body>
</html>
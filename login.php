<?php
session_start();
require_once 'includes/db.php';
$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (empty($correo) || empty($password)) {
        $error = 'Por favor, ingresa correo y contraseña.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE correo = ?");
        $stmt->execute([$correo]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}

// Usamos el header principal para consistencia
include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper">
        <h2>Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <p class="form-message error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <p class="form-link">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
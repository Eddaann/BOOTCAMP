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
            
            $redirect_url = $_SESSION['redirect_url'] ?? 'dashboard.php';
            unset($_SESSION['redirect_url']);
            header('Location: ' . $redirect_url);
            exit();
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}

$page_title = "Iniciar Sesión";
$is_lesson_page = false;
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
                
                <!-- ¡AQUÍ ESTÁ EL CAMBIO! -->
                <div style="text-align: right; margin-top: 0.5rem;">
                    <a href="forgot-password.php" style="font-size: 0.9rem;">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <p class="form-link">¿No tienes cuenta? <a href="register.php">Regístrate aquí</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

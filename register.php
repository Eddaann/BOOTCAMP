<?php
require_once 'includes/db.php';
$mensaje = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (empty($nombre) || empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico no es válido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE correo = ?");
        $stmt->execute([$correo]);

        if ($stmt->rowCount() > 0) {
            $error = "Este correo electrónico ya está registrado.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (nombre, correo, password) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$nombre, $correo, $hashed_password])) {
                $mensaje = "¡Registro exitoso! Ahora puedes <a href='login.php'>iniciar sesión</a>.";
            } else {
                $error = "Error al registrar. Por favor, intenta de nuevo.";
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper">
        <h2>Crear una Cuenta</h2>
        
        <?php if ($mensaje): ?><p class="form-message success"><?= $mensaje ?></p><?php endif; ?>
        <?php if ($error): ?><p class="form-message error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <?php if (!$mensaje): ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
             <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($_POST['correo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Registrarse</button>
        </form>
        <?php endif; ?>

        <p class="form-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
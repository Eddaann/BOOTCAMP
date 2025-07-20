<?php
require_once 'includes/db.php';
$error = '';
$message = '';
$token = $_GET['token'] ?? '';
$is_valid_token = false;

if (empty($token)) {
    $error = "Token no proporcionado.";
} else {
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $reset_request = $stmt->fetch();

    if (!$reset_request) {
        $error = "Token inválido.";
    } else {
        $expires_at = new DateTime($reset_request['expires_at']);
        if (new DateTime() > $expires_at) {
            $error = "El token ha expirado. Por favor, solicita uno nuevo.";
        } else {
            $is_valid_token = true;
        }
    }
}

if ($is_valid_token && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($password) || empty($password_confirm)) {
        $error = "Ambos campos de contraseña son obligatorios.";
    } elseif ($password !== $password_confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        $email = $reset_request['email'];
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Actualizar la contraseña del usuario
        $stmt_update = $pdo->prepare("UPDATE users SET password = ? WHERE correo = ?");
        $stmt_update->execute([$hashed_password, $email]);

        // Eliminar el token usado
        $stmt_delete = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt_delete->execute([$token]);

        $message = "¡Tu contraseña ha sido actualizada con éxito! Ahora puedes iniciar sesión.";
        $is_valid_token = false; // Para ocultar el formulario después del éxito
    }
}

$page_title = "Restablecer Contraseña";
include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper">
        <h2>Restablecer Contraseña</h2>

        <?php if ($error): ?><p class="form-message error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <?php if ($message): ?><p class="form-message success"><?= htmlspecialchars($message) ?></p><?php endif; ?>

        <?php if ($is_valid_token): ?>
        <form method="POST" action="reset-password.php?token=<?= htmlspecialchars($token) ?>">
            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit" class="btn-submit">Guardar Contraseña</button>
        </form>
        <?php elseif ($message): ?>
            <p class="form-link"><a href="login.php">Ir a Iniciar Sesión</a></p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

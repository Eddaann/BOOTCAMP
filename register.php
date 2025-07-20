<?php
require_once 'includes/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';
$nombre_val = '';
$correo_val = '';
$nickname_val = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $nickname = trim($_POST['nickname']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    $nombre_val = $nombre;
    $nickname_val = $nickname;
    $correo_val = $correo;

    if (empty($nombre) || empty($nickname) || empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico no es válido.";
    } elseif (strlen($password) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        // Verificar si el correo o el apodo ya existen
        $stmt = $pdo->prepare("SELECT id FROM users WHERE correo = ? OR nickname = ?");
        $stmt->execute([$correo, $nickname]);
        if ($stmt->rowCount() > 0) {
            $error = "El correo o el apodo ya están registrados. Por favor, elige otros.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt_insert = $pdo->prepare("INSERT INTO users (nombre, nickname, correo, password) VALUES (?, ?, ?, ?)");
            
            if ($stmt_insert->execute([$nombre, $nickname, $correo, $hashed_password])) {
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['flash_message'] = "¡Te has registrado con éxito, " . htmlspecialchars($nombre) . "! Bienvenido a tu aventura de desarrollo web.";
                header('Location: dashboard.php');
                exit();
            } else {
                $error = "Error al registrar. Por favor, intenta de nuevo.";
            }
        }
    }
}

$page_title = "Crear una Cuenta";
$is_lesson_page = false;
include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper">
        <h2>Crear una Cuenta</h2>
        
        <?php if ($error): ?><p class="form-message error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

        <form method="POST" action="register.php" novalidate>
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($nombre_val) ?>">
            </div>
            <div class="form-group">
                <label for="nickname">Apodo (para el ranking)</label>
                <input type="text" id="nickname" name="nickname" required value="<?= htmlspecialchars($nickname_val) ?>">
            </div>
             <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" required value="<?= htmlspecialchars($correo_val) ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña (mín. 8 caracteres)</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-submit">Crear Mi Cuenta</button>
        </form>

        <p class="form-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
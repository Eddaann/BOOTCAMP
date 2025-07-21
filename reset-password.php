<?php
session_start();
require 'includes/db.php';

$message = '';
$message_type = ''; // 'success' or 'error'
$token_valid = false;
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $message = "Token no proporcionado.";
    $message_type = 'error';
} else {
    $sql = "SELECT user_id, expires FROM password_resets WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['expires'] >= date("U")) {
            $token_valid = true;
            $user_id = $row['user_id'];
        } else {
            $message = "El token ha expirado.";
            $message_type = 'error';
        }
    } else {
        $message = "Token inválido.";
        $message_type = 'error';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $token_valid) {
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($password) || empty($password_confirm)) {
        $message = "Por favor, rellena todos los campos.";
        $message_type = 'error';
    } elseif ($password !== $password_confirm) {
        $message = "Las contraseñas no coinciden.";
        $message_type = 'error';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password_hash, $user_id);
        
        if ($stmt->execute()) {
            // Borrar el token después de usarlo
            $sql_delete = "DELETE FROM password_resets WHERE user_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $user_id);
            $stmt_delete->execute();

            $message = "¡Tu contraseña ha sido actualizada con éxito!";
            $message_type = 'success';
            $token_valid = false; // Ocultar el formulario después del éxito
        } else {
            $message = "Hubo un error al actualizar tu contraseña.";
            $message_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Código Bootcamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; }
        .cta-button { transition: all 0.3s ease; }
        .cta-button:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }
        .form-input { background-color: #1a1a1a; border-color: #333; }
        .form-input:focus { background-color: #2a2a2a; border-color: #38bdf8; outline: none; box-shadow: none; }
    </style>
</head>
<body class="text-white">

    <!-- Header -->
    <header class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold">
            <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
        </a>
        <nav class="flex space-x-6 items-center">
            <a href="login.php" class="hover:text-cyan-400 transition-colors">Iniciar Sesión</a>
        </nav>
    </header>

    <!-- Reset Password Form Section -->
    <main class="flex items-center justify-center min-h-[calc(100vh-160px)]">
        <div class="bg-gray-900 p-8 md:p-12 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <h2 class="text-3xl font-bold text-center mb-8">Crea una Nueva Contraseña</h2>
            
            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-500/20 border-green-500 text-green-300' : 'bg-red-500/20 border-red-500 text-red-300'; ?> px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($token_valid): ?>
            <form action="reset-password.php?token=<?php echo htmlspecialchars($token); ?>" method="post" class="space-y-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Nueva Contraseña</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirm" id="password_confirm" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg cta-button text-lg">
                        Restablecer Contraseña
                    </button>
                </div>
            </form>
            <?php elseif ($message_type === 'success'): ?>
                <div class="text-center">
                    <a href="login.php" class="w-full inline-block bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg cta-button text-lg">
                        Ir a Iniciar Sesión
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8">
        <div class="container mx-auto px-6 text-center text-gray-500">
            <p>&copy; 2025 Código Bootcamp. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
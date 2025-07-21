<?php
session_start();
// 1. Incluir la conexión a la base de datos.
// Si este archivo falla, el script se detendrá con un error claro.
require 'includes/db.php';

$error = '';
$success = '';

// 2. El código solo se ejecuta si se envía el formulario (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Por favor, rellena todos los campos.';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El formato del correo electrónico no es válido.';
    } else {
        // 3. La variable $conn ya existe gracias al 'require' de arriba.
        // Se comprueba si el usuario o el email ya existen.
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El nombre de usuario o el correo electrónico ya están en uso.';
        } else {
            // Si no existen, se crea el nuevo usuario.
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $username, $email, $password_hash);

            if ($stmt_insert->execute()) {
                $success = '¡Registro exitoso! Ahora puedes iniciar sesión.';
            } else {
                $error = 'Hubo un error al crear tu cuenta. Por favor, inténtalo de nuevo.';
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Código Bootcamp</title>
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

    <header class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold">
            <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
        </a>
        <nav class="flex space-x-6 items-center">
            <a href="index.php#features" class="hidden md:block hover:text-cyan-400 transition-colors">Características</a>
            <a href="login.php" class="hover:text-cyan-400 transition-colors">Iniciar Sesión</a>
        </nav>
    </header>

    <main class="flex items-center justify-center min-h-[calc(100vh-160px)] py-12">
        <div class="bg-gray-900 p-8 md:p-12 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <h2 class="text-3xl font-bold text-center mb-2">Crea tu Cuenta</h2>
            <p class="text-center text-gray-400 mb-8">Únete a la comunidad y empieza a aprender.</p>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="bg-green-500/20 border border-green-500 text-green-300 px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo htmlspecialchars($success); ?> <a href="login.php" class="font-bold underline">Inicia Sesión</a></p>
                </div>
            <?php endif; ?>

            <form action="register.php" method="post" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Nombre de Usuario</label>
                    <input type="text" name="username" id="username" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-300 mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirm" id="password_confirm" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg cta-button text-lg">
                        Crear Cuenta
                    </button>
                </div>
            </form>
            <p class="text-center text-gray-400 mt-8">
                ¿Ya tienes una cuenta? <a href="login.php" class="font-medium text-cyan-400 hover:underline">Inicia sesión</a>.
            </p>
        </div>
    </main>

    <footer class="py-8">
        <div class="container mx-auto px-6 text-center text-gray-500">
            <p>&copy; 2025 Código Bootcamp. Todos los derechos reservados.</p>
        </div>
    </footer>
    <?php
        // 4. Cerrar la conexión al final del script si aún existe.
        if (isset($conn)) {
            $conn->close();
        }
    ?>
</body>
</html>
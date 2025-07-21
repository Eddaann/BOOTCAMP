<?php
session_start();
require 'includes/db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Por favor, rellena todos los campos.';
    } else {
        $sql = "SELECT id, username, password, avatar FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['avatar'] = $user['avatar'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'La contraseña es incorrecta.';
            }
        } else {
            $error = 'No se encontró ningún usuario con ese correo electrónico.';
        }
        $stmt->close();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Código Bootcamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
        }
        .cta-button {
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .form-input {
            background-color: #1a1a1a;
            border-color: #333;
        }
        .form-input:focus {
            background-color: #2a2a2a;
            border-color: #38bdf8; /* cyan-400 */
            outline: none;
            box-shadow: none;
        }
    </style>
</head>
<body class="text-white">

    <!-- Header -->
    <header class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php" class="text-2xl font-bold">
            <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
        </a>
        <nav class="flex space-x-6 items-center">
            <a href="index.php#features" class="hidden md:block hover:text-cyan-400 transition-colors">Características</a>
            <a href="register.php" class="bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-lg cta-button">¡Inscríbete!</a>
        </nav>
    </header>

    <!-- Login Form Section -->
    <main class="flex items-center justify-center min-h-[calc(100vh-160px)]">
        <div class="bg-gray-900 p-8 md:p-12 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <h2 class="text-3xl font-bold text-center mb-2">Bienvenido de Nuevo</h2>
            <p class="text-center text-gray-400 mb-8">Inicia sesión para continuar tu aventura.</p>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post" class="space-y-6">
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
                <div class="text-right">
                    <a href="forgot-password.php" class="text-sm text-cyan-400 hover:underline">¿Olvidaste tu contraseña?</a>
                </div>
                <div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg cta-button text-lg">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
            <p class="text-center text-gray-400 mt-8">
                ¿No tienes una cuenta? <a href="register.php" class="font-medium text-cyan-400 hover:underline">Regístrate aquí</a>.
            </p>
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
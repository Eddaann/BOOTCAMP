<?php
session_start();
require 'includes/db.php';

// Importar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$message = '';
$message_type = ''; // 'success' or 'error'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Por favor, introduce un correo electrónico válido.';
        $message_type = 'error';
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $user_id = $user['id'];
            
            $token = bin2hex(random_bytes(50));
            $expires = date("U") + 1800; // Token válido por 30 minutos

            // Borrar tokens antiguos para este usuario
            $sql = "DELETE FROM password_resets WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Insertar nuevo token
            $sql = "INSERT INTO password_resets (user_id, token, expires) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $token, $expires);
            $stmt->execute();

            // Enviar correo electrónico
            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor
                $mail->isSMTP();
                $mail->Host       = 'smtp.example.com'; // Introduce tu host SMTP
                $mail->SMTPAuth   = true;
                $mail->Username   = 'user@example.com'; // Tu usuario SMTP
                $mail->Password   = 'secret';           // Tu contraseña SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Destinatarios
                $mail->setFrom('no-reply@codigobootcamp.com', 'Codigo Bootcamp');
                $mail->addAddress($email);

                // Contenido
                $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/reset-password.php?token=" . $token;
                $mail->isHTML(true);
                $mail->Subject = 'Restablecimiento de Contraseña - Codigo Bootcamp';
                $mail->Body    = 'Hola,<br><br>Has solicitado restablecer tu contraseña. Por favor, haz clic en el siguiente enlace para continuar:<br><br><a href="' . $url . '">' . $url . '</a><br><br>Si no solicitaste esto, puedes ignorar este correo.<br><br>Gracias,<br>El equipo de Codigo Bootcamp';
                $mail->AltBody = 'Para restablecer tu contraseña, copia y pega esta URL en tu navegador: ' . $url;

                $mail->send();
                $message = 'Se ha enviado un enlace de recuperación a tu correo electrónico.';
                $message_type = 'success';
            } catch (Exception $e) {
                $message = "El mensaje no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
                $message_type = 'error';
            }
        } else {
            // Aún si el correo no existe, mostramos un mensaje genérico por seguridad
            $message = 'Si tu correo está en nuestra base de datos, recibirás un enlace de recuperación.';
            $message_type = 'success';
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Código Bootcamp</title>
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

    <!-- Forgot Password Form Section -->
    <main class="flex items-center justify-center min-h-[calc(100vh-160px)]">
        <div class="bg-gray-900 p-8 md:p-12 rounded-xl shadow-2xl w-full max-w-md mx-4">
            <h2 class="text-3xl font-bold text-center mb-2">Recuperar Contraseña</h2>
            <p class="text-center text-gray-400 mb-8">Introduce tu correo y te enviaremos un enlace para restablecerla.</p>
            
            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-500/20 border-green-500 text-green-300' : 'bg-red-500/20 border-red-500 text-red-300'; ?> px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>

            <form action="forgot-password.php" method="post" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-4 py-3 rounded-lg text-white form-input focus:ring-cyan-500 focus:border-cyan-500 transition">
                </div>
                <div>
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-3 px-4 rounded-lg cta-button text-lg">
                        Enviar Enlace de Recuperación
                    </button>
                </div>
            </form>
            <p class="text-center text-gray-400 mt-8">
                ¿Recordaste tu contraseña? <a href="login.php" class="font-medium text-cyan-400 hover:underline">Inicia sesión</a>.
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
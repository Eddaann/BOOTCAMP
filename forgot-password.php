<?php
require_once 'includes/db.php';

// Incluir las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Por favor, ingresa un correo electrónico válido.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE correo = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            // Generar un token seguro
            $token = bin2hex(random_bytes(32));
            $expires = new DateTime('now + 1 hour');
            
            // Guardar el token en la base de datos
            $stmt_insert = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
            $stmt_insert->execute([$email, $token, $expires->format('Y-m-d H:i:s')]);
            
            // Construir el enlace de recuperación
            $protocol = (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']) ? 'https' : 'http');
            $host = $_SERVER['HTTP_HOST'];
            $script_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            $reset_link = "{$protocol}://{$host}{$script_path}/reset-password.php?token={$token}";

            // Configuración y envío del correo electrónico
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                
                // --- ¡TUS CREDENCIALES YA ESTÁN CONFIGURADAS! ---
                $mail->Username   = 'franciscotapia1020@gmail.com';
                $mail->Password   = 'nwfqjvtrkuafigdq'; // ¡LISTO! Tu contraseña de aplicación está aquí.
                
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                // Remitente y destinatario
                $mail->setFrom('no-reply@bootcamp.com', 'Bootcamp Web');
                $mail->addAddress($email);

                // Contenido del correo
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Restablece tu contraseña de Bootcamp Web';
                $mail->Body    = "Hola,<br><br>Hemos recibido una solicitud para restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:<br><br><a href='{$reset_link}'>Restablecer mi contraseña</a><br><br>Si no solicitaste esto, puedes ignorar este correo.<br><br>Saludos,<br>El equipo de Bootcamp Web";
                $mail->AltBody = "Para restablecer tu contraseña, copia y pega este enlace en tu navegador: {$reset_link}";

                $mail->send();
                $message = 'Si una cuenta con ese correo electrónico existe, hemos enviado un enlace de recuperación.';
            } catch (Exception $e) {
                $error = "No se pudo enviar el correo. Revisa tus credenciales o contacta al administrador.";
                error_log("PHPMailer Error: {$mail->ErrorInfo}");
            }
        } else {
            $message = 'Si una cuenta con ese correo electrónico existe, hemos enviado un enlace de recuperación.';
        }
    }
}

$page_title = "Recuperar Contraseña";
include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper">
        <h2>Recuperar Contraseña</h2>
        <p style="text-align: center; margin-bottom: 1.5rem;">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
        
        <?php if ($error): ?><p class="form-message error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <?php if ($message): ?><p class="form-message success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
        
        <form method="POST" action="forgot-password.php">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="btn-submit">Enviar Enlace</button>
        </form>

        <p class="form-link">¿Recordaste tu contraseña? <a href="login.php">Inicia sesión</a>.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
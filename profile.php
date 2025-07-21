<?php
require 'includes/auth.php';
require 'includes/db.php';

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = ''; // 'success' or 'error'

// --- Lógica para actualizar el perfil ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar nombre de usuario
    if (isset($_POST['username'])) {
        $new_username = trim($_POST['username']);
        if (!empty($new_username)) {
            $sql_update_username = "UPDATE users SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update_username);
            $stmt->bind_param("si", $new_username, $user_id);
            if ($stmt->execute()) {
                $_SESSION['username'] = $new_username; // Actualizar la sesión
                $message = '¡Nombre de usuario actualizado con éxito!';
                $message_type = 'success';
            } else {
                $message = 'Ese nombre de usuario ya está en uso.';
                $message_type = 'error';
            }
            $stmt->close();
        } else {
            $message = 'El nombre de usuario no puede estar vacío.';
            $message_type = 'error';
        }
    }

    // Actualizar avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['avatar']['type'];

        if (in_array($file_type, $allowed_types)) {
            $upload_dir = 'uploads/avatars/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path)) {
                $sql_update_avatar = "UPDATE users SET avatar = ? WHERE id = ?";
                $stmt = $conn->prepare($sql_update_avatar);
                $stmt->bind_param("si", $upload_path, $user_id);
                if ($stmt->execute()) {
                    $_SESSION['avatar'] = $upload_path;
                    $message = '¡Avatar actualizado con éxito!';
                    $message_type = 'success';
                } else {
                    $message = 'Error al guardar el avatar en la base de datos.';
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message = 'Error al mover el archivo subido.';
                $message_type = 'error';
            }
        } else {
            $message = 'Tipo de archivo no permitido. Sube un JPG, PNG o GIF.';
            $message_type = 'error';
        }
    }
}

// --- Obtener datos actualizados del usuario para mostrar en la página ---
$sql_user = "SELECT username, email, avatar, points, created_at FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Código Bootcamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; }
        .form-input { background-color: #1a1a1a; border-color: #333; }
        .form-input:focus { background-color: #2a2a2a; border-color: #38bdf8; outline: none; box-shadow: none; }
    </style>
</head>
<body class="text-white">

    <!-- Header (similar al del dashboard) -->
    <header class="bg-gray-900/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
             <a href="dashboard.php" class="text-2xl font-bold">
                <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
            </a>
            <nav>
                <a href="dashboard.php" class="text-cyan-400 hover:text-cyan-300 transition-colors">Volver al Dashboard</a>
            </nav>
        </div>
    </header>

    <!-- Contenido Principal del Perfil -->
    <main class="container mx-auto px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl font-extrabold mb-8">Editar Mi Perfil</h1>

            <?php if (!empty($message)): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-500/20 border-green-500 text-green-300' : 'bg-red-500/20 border-red-500 text-red-300'; ?> px-4 py-3 rounded-lg mb-6" role="alert">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <div class="bg-gray-900 rounded-xl shadow-lg p-8">
                <!-- Sección de Avatar -->
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-8">
                    <?php 
                        $avatar_url = (isset($user['avatar']) && $user['avatar'] !== 'assets/img/default-avatar.png') 
                            ? htmlspecialchars($user['avatar']) 
                            : 'https://placehold.co/128x128/0a0a0a/ffffff?text=' . strtoupper(substr($user['username'], 0, 1));
                    ?>
                    <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="h-32 w-32 rounded-full object-cover border-4 border-cyan-400">
                    <form action="profile.php" method="post" enctype="multipart/form-data" class="flex-grow">
                        <label for="avatar" class="block text-sm font-medium text-gray-300 mb-2">Cambiar foto de perfil</label>
                        <div class="flex">
                             <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-600 file:text-white hover:file:bg-cyan-700">
                             <button type="submit" class="ml-4 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">Guardar</button>
                        </div>
                    </form>
                </div>

                <!-- Formulario de Datos del Usuario -->
                <form action="profile.php" method="post" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Nombre de Usuario</label>
                        <div class="flex">
                            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full px-4 py-3 rounded-lg text-white form-input transition">
                            <button type="submit" class="ml-4 bg-cyan-600 hover:bg-cyan-700 text-white font-bold py-2 px-4 rounded-lg">Actualizar</button>
                        </div>
                    </div>
                </form>

                <hr class="border-gray-700 my-8">

                <!-- Información Adicional -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Correo Electrónico</label>
                        <p class="text-lg text-gray-200"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-gray-400">Puntos Totales</label>
                        <p class="text-lg text-cyan-400 font-bold"><?php echo htmlspecialchars($user['points']); ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Miembro Desde</label>
                        <p class="text-lg text-gray-200"><?php echo date("d M, Y", strtotime($user['created_at'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
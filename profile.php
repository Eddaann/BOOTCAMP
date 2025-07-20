<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$stmt = $pdo->prepare("SELECT id, nombre, nickname, correo, avatar FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        // Actualizar nombre
        if (!empty($_POST['nombre']) && $_POST['nombre'] !== $user['nombre']) {
            $new_name = trim($_POST['nombre']);
            $stmt_update_name = $pdo->prepare("UPDATE users SET nombre = ? WHERE id = ?");
            $stmt_update_name->execute([$new_name, $user_id]);
            $_SESSION['nombre'] = $new_name;
            $user['nombre'] = $new_name;
            $success = '¡Tu nombre ha sido actualizado!';
        }

        // Actualizar apodo
        if (!empty($_POST['nickname']) && $_POST['nickname'] !== $user['nickname']) {
            $new_nickname = trim($_POST['nickname']);
            $stmt_check = $pdo->prepare("SELECT id FROM users WHERE nickname = ? AND id != ?");
            $stmt_check->execute([$new_nickname, $user_id]);
            if ($stmt_check->rowCount() > 0) {
                $error = 'Ese apodo ya está en uso. Por favor, elige otro.';
            } else {
                $stmt_update_nick = $pdo->prepare("UPDATE users SET nickname = ? WHERE id = ?");
                $stmt_update_nick->execute([$new_nickname, $user_id]);
                $user['nickname'] = $new_nickname;
                if(empty($success)) $success = '¡Tu apodo ha sido actualizado!';
            }
        }

        // Manejar subida de avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            $upload_dir = 'uploads/avatars/';
            $allowed_types = ['image/jpeg', 'image/png'];
            $max_size = 2 * 1024 * 1024;

            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            if (!in_array($file['type'], $allowed_types)) {
                $error = 'Formato de archivo no permitido. Solo se aceptan JPG y PNG.';
            } elseif ($file['size'] > $max_size) {
                $error = 'El archivo es demasiado grande. El tamaño máximo es 2 MB.';
            } else {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    if (!empty($user['avatar']) && file_exists($user['avatar'])) unlink($user['avatar']);
                    $stmt_update_avatar = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
                    $stmt_update_avatar->execute([$upload_path, $user_id]);
                    $user['avatar'] = $upload_path;
                    if(empty($success)) $success = '¡Tu foto de perfil ha sido actualizada!';
                } else {
                    $error = 'Hubo un error al subir tu imagen.';
                }
            }
        }
        
        if (empty($error)) {
            $pdo->commit();
        } else {
            $pdo->rollBack();
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = 'Ocurrió un error inesperado.';
        error_log($e->getMessage());
    }
}

$avatar_url = 'assets/images/avatar_default.png';
if (!empty($user['avatar']) && file_exists($user['avatar'])) {
    $avatar_url = htmlspecialchars($user['avatar']);
} else {
    $avatar_url = 'https://i.pravatar.cc/150?u=' . $user['id'];
}

$page_title = "Editar Perfil";
$is_lesson_page = false;
$is_profile_page = true;
include 'includes/header.php';
?>

<div class="page-container">
    <div class="form-wrapper profile-form-wrapper">
        <h2>Editar mi Perfil</h2>
        
        <?php if ($error): ?><p class="form-message error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <?php if ($success): ?><p class="form-message success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

        <form method="POST" action="profile.php" enctype="multipart/form-data">
            
            <div class="avatar-upload-section">
                <label>Foto de Perfil</label>
                <img src="<?= $avatar_url ?>" alt="Avatar actual" class="avatar-preview" id="avatar-preview">
                <input type="file" name="avatar" id="avatar-input" class="input-file" accept="image/png, image/jpeg">
                <label for="avatar-input" class="btn-file">Seleccionar imagen</label>
                <p class="form-hint">Soportado: JPG, PNG. Máximo 2MB.</p>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" required value="<?= htmlspecialchars($user['nombre']) ?>">
            </div>
            
            <div class="form-group">
                <label for="nickname">Apodo (para el ranking)</label>
                <input type="text" id="nickname" name="nickname" required value="<?= htmlspecialchars($user['nickname'] ?? '') ?>">
            </div>

            <button type="submit" class="btn-submit">Guardar Cambios</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

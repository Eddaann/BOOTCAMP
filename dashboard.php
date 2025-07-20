<?php
session_start();
require_once 'includes/db.php';

// Primero, verificar si hay una sesión activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener datos del usuario (incluyendo XP y Nivel)
$stmt_user = $pdo->prepare("SELECT nombre, xp, nivel FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

// --- ¡AQUÍ ESTÁ LA CORRECCIÓN! ---
// Verificar si el usuario realmente existe en la base de datos.
// Si no, la sesión es inválida y debemos forzar el logout.
if (!$user) {
    // Destruir la sesión y redirigir al login
    session_destroy();
    header("Location: login.php?error=session_expired");
    exit();
}

// Recuperar y limpiar el mensaje flash si existe
$flash_message = $_SESSION['flash_message'] ?? '';
unset($_SESSION['flash_message']);

// Obtener todas las lecciones y el progreso del usuario
$stmt_lessons = $pdo->prepare("
    SELECT 
        l.id, l.titulo, l.descripcion, l.archivo, l.orden, l.dia,
        up.status 
    FROM lessons l
    LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
    ORDER BY l.orden
");
$stmt_lessons->execute([$user_id]);
$lessons = $stmt_lessons->fetchAll();

// Agrupar lecciones por día
$lessons_by_day = [];
foreach ($lessons as $lesson) {
    $lessons_by_day[$lesson['dia']][] = $lesson;
}

// Lógica para desbloqueo de lecciones
$completed_lessons_count = 0;
foreach ($lessons as $lesson) {
    if (($lesson['status'] ?? 'no_iniciado') === 'completado') {
        $completed_lessons_count++;
    }
}

$page_title = "Mi Panel";
$is_lesson_page = false;
include 'includes/header.php';
?>

<div class="container">
    <?php if ($flash_message): ?>
        <div class="flash-message success"><p><?= htmlspecialchars($flash_message) ?></p></div>
    <?php endif; ?>

    <div class="dashboard-header">
        <h2>¡Bienvenido, <?= htmlspecialchars($user['nombre']) ?>!</h2>
        
        <div class="user-stats">
            <div class="stat-card">
                <span class="stat-label">Nivel</span>
                <span class="stat-value"><?= $user['nivel'] ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">XP</span>
                <span class="stat-value"><?= $user['xp'] ?></span>
            </div>
        </div>
    </div>

    <?php foreach ($lessons_by_day as $day => $day_lessons): ?>
        <h3 class="day-header">Día <?= $day ?></h3>
        <div class="lesson-grid">
            <?php foreach ($day_lessons as $lesson): ?>
                <?php
                    $status = $lesson['status'] ?? 'no_iniciado';
                    $is_completed = ($status === 'completado');
                    // La lección está desbloqueada si su orden es menor o igual al número de lecciones completadas + 1
                    $is_unlocked = ($lesson['orden'] <= $completed_lessons_count + 1);
                    
                    $status_text = $is_completed ? 'Completado' : 'No Iniciado';
                    $btn_text = $is_completed ? 'Repasar' : 'Empezar';
                    $btn_class = $is_completed ? 'btn-repasar' : '';
                    if (!$is_unlocked) {
                        $btn_text = 'Bloqueado';
                        $btn_class .= ' btn-locked';
                    }
                ?>
                <div class="lesson-card <?= !$is_unlocked ? 'locked' : '' ?>">
                    <div>
                        <span class="lesson-order">Módulo <?= $lesson['orden'] ?></span>
                        <h3><?= htmlspecialchars($lesson['titulo']) ?></h3>
                        <p><?= htmlspecialchars($lesson['descripcion']) ?></p>
                    </div>
                    <div>
                        <?php if ($is_unlocked): ?>
                            <span class="status <?= $status ?>"><?= $status_text ?></span>
                        <?php else: ?>
                            <span class="status locked">Bloqueado</span>
                        <?php endif; ?>
                        
                        <a href="<?= $is_unlocked ? htmlspecialchars($lesson['archivo']) : '#' ?>" class="btn-lesson <?= $btn_class ?>"><?= $btn_text ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>

<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener todas las lecciones y el progreso del usuario
$stmt = $pdo->prepare("
    SELECT 
        l.id, l.titulo, l.descripcion, l.archivo,
        up.status 
    FROM lessons l
    LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
    ORDER BY l.orden
");
$stmt->execute([$user_id]);
$lessons = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="container">
    <div class="dashboard-header">
        <h2>Bienvenido de nuevo, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h2>
        <p>Este es tu panel. Aquí puedes ver tu progreso y continuar aprendiendo.</p>
    </div>

    <h3>Tus Lecciones</h3>
    <div class="lesson-grid">
        <?php foreach ($lessons as $lesson): ?>
            <?php
                $status = $lesson['status'] ?? 'no_iniciado';
                $status_text = ($status === 'completado') ? 'Completado' : 'No Iniciado';
                $btn_text = ($status === 'completado') ? 'Repasar Lección' : 'Empezar Lección';
                $btn_class = ($status === 'completado') ? 'btn-repasar' : '';
            ?>
            <div class="lesson-card">
                <div>
                    <h3><?= htmlspecialchars($lesson['titulo']) ?></h3>
                    <p><?= htmlspecialchars($lesson['descripcion']) ?></p>
                </div>
                <div>
                    <span class="status <?= $status ?>"><?= $status_text ?></span>
                    
                    <a href="<?= htmlspecialchars($lesson['archivo']) ?>" class="btn-lesson <?= $btn_class ?>"><?= $btn_text ?></a>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_id'])) {
    $user_id = $_SESSION['user_id'];
    $lesson_id = filter_var($_POST['lesson_id'], FILTER_VALIDATE_INT);

    if ($lesson_id === false) {
        http_response_code(400);
        exit();
    }

    try {
        $pdo->beginTransaction();

        // Verificar si la lección ya fue completada para no dar XP de más
        $stmt_check = $pdo->prepare("SELECT id FROM user_progress WHERE user_id = ? AND lesson_id = ?");
        $stmt_check->execute([$user_id, $lesson_id]);
        $already_completed = $stmt_check->rowCount() > 0;

        if (!$already_completed) {
            // Marcar la lección como completada
            $stmt_progress = $pdo->prepare(
                "INSERT INTO user_progress (user_id, lesson_id, status, completado_en) VALUES (?, ?, 'completado', NOW())"
            );
            $stmt_progress->execute([$user_id, $lesson_id]);

            // Otorgar XP y recalcular nivel
            $xp_ganada = 100; // XP por cada lección
            $stmt_user = $pdo->prepare("UPDATE users SET xp = xp + ? WHERE id = ?");
            $stmt_user->execute([$xp_ganada, $user_id]);

            // Actualizar nivel (ejemplo: nuevo nivel cada 250 XP)
            $stmt_xp = $pdo->prepare("SELECT xp FROM users WHERE id = ?");
            $stmt_xp->execute([$user_id]);
            $current_xp = $stmt_xp->fetchColumn();
            $new_level = floor($current_xp / 250) + 1;

            $stmt_level = $pdo->prepare("UPDATE users SET nivel = ? WHERE id = ?");
            $stmt_level->execute([$new_level, $user_id]);
        }
        
        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error al completar lección: " . $e->getMessage());
    }
}

header("Location: dashboard.php");
exit();
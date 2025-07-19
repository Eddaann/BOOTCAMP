<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lesson_id'])) {
    $user_id = $_SESSION['user_id'];
    $lesson_id = $_POST['lesson_id'];

    try {
        // Verificar si ya existe un registro para evitar duplicados
        $stmt = $pdo->prepare("SELECT id FROM user_progress WHERE user_id = ? AND lesson_id = ?");
        $stmt->execute([$user_id, $lesson_id]);
        
        if ($stmt->rowCount() == 0) {
            // Si no existe, inserta el nuevo progreso como completado
            $stmt_insert = $pdo->prepare(
                "INSERT INTO user_progress (user_id, lesson_id, status, completado_en) VALUES (?, ?, 'completado', NOW())"
            );
            $stmt_insert->execute([$user_id, $lesson_id]);
        } else {
            // Si ya existe, solo actualiza el estado y la fecha
            $stmt_update = $pdo->prepare(
                "UPDATE user_progress SET status = 'completado', completado_en = NOW() WHERE user_id = ? AND lesson_id = ?"
            );
            $stmt_update->execute([$user_id, $lesson_id]);
        }

    } catch (PDOException $e) {
        // En un proyecto real, aquí manejarías el error (p.ej. registrarlo en un log)
        // Por ahora, simplemente redirigimos.
    }
}

// Redirigir siempre al dashboard
header("Location: dashboard.php");
exit();
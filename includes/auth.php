<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/db.php';

// Verificación de acceso a la lección
if (isset($lesson_id)) {
    $user_id = $_SESSION['user_id'];
    
    // La primera lección siempre es accesible
    if ($lesson_id > 1) {
        $previous_lesson_id = $lesson_id - 1;
        
        $stmt = $pdo->prepare(
            "SELECT id FROM user_progress WHERE user_id = ? AND lesson_id = ? AND status = 'completado'"
        );
        $stmt->execute([$user_id, $previous_lesson_id]);
        
        if ($stmt->rowCount() == 0) {
            // Si la lección anterior no está completada, redirigir al dashboard
            $_SESSION['flash_message'] = "Debes completar la lección anterior para poder continuar.";
            header("Location: ../dashboard.php");
            exit();
        }
    }
}

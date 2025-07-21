<?php
require 'includes/auth.php';
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lesson_id'])) {
    
    $lesson_id = (int)$_POST['lesson_id'];
    $user_id = $_SESSION['user_id'];

    // 1. Obtener la puntuación máxima para esta lección
    $sql_lesson = "SELECT max_points FROM lessons WHERE id = ?";
    $stmt_lesson = $conn->prepare($sql_lesson);
    $stmt_lesson->bind_param("i", $lesson_id);
    $stmt_lesson->execute();
    $result_lesson = $stmt_lesson->get_result();

    if ($result_lesson->num_rows == 0) {
        header("Location: dashboard.php"); // La lección no existe
        exit;
    }
    $lesson = $result_lesson->fetch_assoc();
    $max_points = $lesson['max_points'];
    $stmt_lesson->close();

    // 2. Obtener la hora de inicio y verificar si ya fue completada
    $sql_progress = "SELECT started_at, points_awarded FROM user_lessons WHERE user_id = ? AND lesson_id = ?";
    $stmt_progress = $conn->prepare($sql_progress);
    $stmt_progress->bind_param("ii", $user_id, $lesson_id);
    $stmt_progress->execute();
    $progress = $stmt_progress->get_result()->fetch_assoc();
    $stmt_progress->close();

    // Si ya tiene puntos, no hacer nada y redirigir
    if ($progress['points_awarded'] !== null) {
        header("Location: dashboard.php");
        exit;
    }

    // 3. Calcular la puntuación basada en el tiempo
    $start_time = strtotime($progress['started_at']);
    $end_time = time();
    $time_taken_seconds = $end_time - $start_time;

    // Fórmula: Se pierde 1 punto cada 10 segundos. Mínimo 10 puntos.
    $penalty = floor($time_taken_seconds / 10);
    $points_awarded = max(10, $max_points - $penalty);

    // 4. Actualizar el registro de la lección con los puntos y la fecha de finalización
    $sql_update_progress = "UPDATE user_lessons SET completed_at = NOW(), points_awarded = ? WHERE user_id = ? AND lesson_id = ?";
    $stmt_update_progress = $conn->prepare($sql_update_progress);
    $stmt_update_progress->bind_param("iii", $points_awarded, $user_id, $lesson_id);
    $stmt_update_progress->execute();
    $stmt_update_progress->close();

    // 5. Sumar los nuevos puntos al total del usuario
    $sql_update_user = "UPDATE users SET points = points + ? WHERE id = ?";
    $stmt_update_user = $conn->prepare($sql_update_user);
    $stmt_update_user->bind_param("ii", $points_awarded, $user_id);
    $stmt_update_user->execute();
    $stmt_update_user->close();
    
    $conn->close();

    // 6. Redirigir de vuelta al dashboard
    header("Location: dashboard.php");
    exit;

} else {
    header("Location: dashboard.php");
    exit;
}
?>
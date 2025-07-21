<?php
require 'includes/auth.php';
require 'includes/db.php';

// Lee el input JSON enviado desde JavaScript
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['lesson_id']) && isset($input['completed_steps'])) {
        $lesson_id = (int)$input['lesson_id'];
        $completed_steps = (int)$input['completed_steps'];
        $user_id = $_SESSION['user_id'];

        // Actualiza el número de pasos completados en la base de datos
        $sql = "UPDATE user_lessons SET completed_steps = ? WHERE user_id = ? AND lesson_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $completed_steps, $user_id, $lesson_id);
        
        if ($stmt->execute()) {
            // Envía una respuesta de éxito
            echo json_encode(['status' => 'success']);
        } else {
            // Envía una respuesta de error
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save progress']);
        }
        $stmt->close();
        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
}
?>
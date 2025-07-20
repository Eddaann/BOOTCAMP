<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- ¡AQUÍ ESTÁ EL CAMBIO! ---
// Usamos COALESCE para mostrar el apodo, y si no existe, el nombre real.
$stmt = $pdo->prepare("
    SELECT 
        id, 
        COALESCE(nickname, nombre) as display_name, 
        nivel, 
        xp, 
        avatar 
    FROM users 
    ORDER BY xp DESC, nivel DESC, nombre ASC 
    LIMIT 50
");
$stmt->execute();
$ranking = $stmt->fetchAll();

$page_title = "Tabla de Clasificación";
$is_lesson_page = false;
include 'includes/header.php';
?>

<div class="container">
    <div class="leaderboard-header">
        <h2>Tabla de Clasificación</h2>
        <p>¡Compite con otros estudiantes y llega a la cima!</p>
    </div>

    <div class="leaderboard-table-wrapper">
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nivel</th>
                    <th>XP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ranking as $index => $player): ?>
                    <?php
                        $avatar_url = !empty($player['avatar']) && file_exists($player['avatar'])
                            ? htmlspecialchars($player['avatar'])
                            : 'https://i.pravatar.cc/40?u=' . $player['id'];
                    ?>
                    <tr class="<?= ($player['id'] === $_SESSION['user_id']) ? 'current-user' : '' ?>">
                        <td><?= $index + 1 ?></td>
                        <td>
                            <div class="user-info">
                                <img src="<?= $avatar_url ?>" alt="Avatar de <?= htmlspecialchars($player['display_name']) ?>">
                                <span><?= htmlspecialchars($player['display_name']) ?></span>
                            </div>
                        </td>
                        <td><?= $player['nivel'] ?></td>
                        <td><?= $player['xp'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

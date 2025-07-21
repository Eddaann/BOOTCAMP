<?php
require 'includes/auth.php';
require 'includes/db.php';

// Obtener información del usuario actual para el header
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT username, avatar FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// Obtener todos los usuarios ordenados por puntos
$sql_leaderboard = "SELECT username, avatar, points FROM users ORDER BY points DESC";
$leaderboard_result = $conn->query($sql_leaderboard);

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Código Bootcamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0a; }
        .profile-dropdown { display: none; }
        .profile-dropdown-toggle:hover + .profile-dropdown, .profile-dropdown:hover { display: block; }
        .rank-1 { background: linear-gradient(135deg, #fde047, #f59e0b); color: #422006; }
        .rank-2 { background: linear-gradient(135deg, #e5e7eb, #9ca3af); color: #1f2937; }
        .rank-3 { background: linear-gradient(135deg, #fcd34d, #d97706); color: #451a03; }
    </style>
</head>
<body class="text-white">

    <!-- Header -->
    <header class="bg-gray-900/50 backdrop-blur-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">
                <span class="text-cyan-400">&lt;</span>Código<span class="text-cyan-400">/&gt;</span> Bootcamp
            </a>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="dashboard.php" class="hover:text-cyan-400 transition-colors">Dashboard</a>
                <a href="leaderboard.php" class="text-cyan-400 font-semibold">Leaderboard</a>
                
                <div class="relative">
                    <button class="profile-dropdown-toggle flex items-center space-x-2">
                         <?php 
                            $avatar_url = (isset($user['avatar']) && $user['avatar'] !== 'assets/img/default-avatar.png') 
                                ? htmlspecialchars($user['avatar']) 
                                : 'https://placehold.co/100x100/0a0a0a/ffffff?text=' . strtoupper(substr($user['username'], 0, 1));
                        ?>
                        <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="h-10 w-10 rounded-full object-cover border-2 border-gray-700">
                        <span class="font-semibold"><?php echo htmlspecialchars($user['username']); ?></span>
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                    <div class="profile-dropdown absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-xl py-2">
                        <a href="profile.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Mi Perfil</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-cyan-600 hover:text-white">Cerrar Sesión</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-extrabold">Tabla de Posiciones</h1>
            <p class="text-gray-400 mt-2 text-lg">¡Mira quién lidera el bootcamp y sigue sumando puntos!</p>
        </div>

        <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="p-4 font-semibold text-center w-1/12">Rango</th>
                        <th class="p-4 font-semibold w-7/12">Usuario</th>
                        <th class="p-4 font-semibold text-right w-4/12">Puntos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php 
                    $rank = 1;
                    while ($row = $leaderboard_result->fetch_assoc()): 
                        $rank_class = '';
                        if ($rank == 1) $rank_class = 'rank-1';
                        if ($rank == 2) $rank_class = 'rank-2';
                        if ($rank == 3) $rank_class = 'rank-3';
                    ?>
                    <tr class="hover:bg-gray-800 transition-colors <?php if ($row['username'] == $user['username']) echo 'bg-cyan-900/50'; ?>">
                        <td class="p-4 font-bold text-center">
                            <span class="w-8 h-8 flex items-center justify-center rounded-full <?php echo $rank_class; ?>">
                                <?php echo $rank; ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center space-x-4">
                                <?php 
                                    $row_avatar_url = (isset($row['avatar']) && $row['avatar'] !== 'assets/img/default-avatar.png') 
                                        ? htmlspecialchars($row['avatar']) 
                                        : 'https://placehold.co/100x100/0a0a0a/ffffff?text=' . strtoupper(substr($row['username'], 0, 1));
                                ?>
                                <img src="<?php echo $row_avatar_url; ?>" alt="Avatar" class="h-12 w-12 rounded-full object-cover">
                                <span class="font-semibold text-lg"><?php echo htmlspecialchars($row['username']); ?></span>
                            </div>
                        </td>
                        <td class="p-4 text-right font-bold text-cyan-400 text-xl"><?php echo htmlspecialchars($row['points']); ?></td>
                    </tr>
                    <?php 
                    $rank++;
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
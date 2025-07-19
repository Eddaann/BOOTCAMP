<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // Redirige al login.php en el directorio raíz.
  // La ruta '../' sube un nivel desde /lessons/ al directorio principal.
  header("Location: ../login.php");
  exit();
}
require_once 'db.php'; // Incluir la conexión a la BD
?>
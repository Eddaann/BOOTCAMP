<?php
$host = 'localhost';
$db   = 'bootcamp_db';
$user = 'root';
$pass = ''; // Tu contraseña de MySQL, si tienes una.
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  // En un sitio real, no mostrarías el error directamente al usuario.
  // Lo registrarías en un archivo de log y mostrarías un mensaje genérico.
  error_log("Error de conexión a la base de datos: " . $e->getMessage());
  die("Hubo un problema al conectar con el sitio. Por favor, intenta más tarde.");
}
?>

<?php
// --- Configuración de la Base de Datos ---
// Asegúrate de que estos datos sean correctos para tu XAMPP.
$servername = "localhost";    // Generalmente es 'localhost' o '127.0.0.1'
$username = "root";           // El usuario por defecto de XAMPP es 'root'
$password = "";               // La contraseña por defecto de XAMPP está vacía
$dbname = "bootcamp_db";      // El nombre de tu base de datos

// --- Crear Conexión ---
// Se crea un nuevo objeto de conexión a la base de datos.
$conn = new mysqli($servername, $username, $password, $dbname);

// --- Verificar Conexión ---
// Esta es la parte más importante. Si la conexión falla,
// el script se detendrá y mostrará un error claro y específico.
if ($conn->connect_error) {
    die("Error de Conexión Fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 para evitar problemas con acentos y caracteres especiales.
$conn->set_charset("utf8mb4");

// Si el script llega hasta aquí, la variable $conn existe y es válida.
?>
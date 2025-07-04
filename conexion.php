<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "tienda hara bd"; // Reemplaza con el nombre de tu base si es distinto

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Guardar en variable global por si se requiere
$GLOBALS['conn'] = $conn;
?>


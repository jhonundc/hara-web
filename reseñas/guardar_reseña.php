<?php
$conexion = new mysqli("localhost", "root", "", "tienda hara bd");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_usuario = $_POST['id_usuario'];
$id_producto = $_POST['id_producto'];
$comentario = $conexion->real_escape_string($_POST['comentario']);
$calificacion = $_POST['calificacion'];

$sql = "INSERT INTO reseñas (id_usuario, id_producto, comentario, calificacion)
        VALUES ('$id_usuario', '$id_producto', '$comentario', '$calificacion')";

if ($conexion->query($sql) === TRUE) {
    header("Location: mostrar_reseñas?exito=1");
    exit();
} else {
    echo "Error: " . $conexion->error;
}

$conexion->close();
?>

<?php
include '../conexion.php';
session_start();
$id = $_POST['id'];
$cantidad = $_POST['cantidad'];
$resultado = $conexion->query("SELECT * FROM productos WHERE id=$id");
$producto = $resultado->fetch_assoc();
if ($producto) {
    $_SESSION['carrito'][$id] = [
        'nombre' => $producto['nombre'],
        'precio' => $producto['precio'],
        'cantidad' => $cantidad
    ];
}
header("Location: carrito.php");
?>
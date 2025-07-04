<?php
include '../conexion.php';
session_start();
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$pago = $_POST['pago'];
$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}
$conexion->query("INSERT INTO pedidos (nombre, telefono, direccion, metodo_pago, total)
VALUES ('$nombre', '$telefono', '$direccion', '$pago', '$total')");
unset($_SESSION['carrito']);
echo "<script>alert('Gracias por tu compra 🛍️'); window.location='productos.php';</script>";
?>
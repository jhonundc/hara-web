<?php
session_start();
$id = $_POST['id'];
unset($_SESSION['carrito'][$id]);
header("Location: carrito.php");
?>
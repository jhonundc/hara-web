<?php
require_once('../conexion.php');

// Validar que existan los campos esperados
if (isset($_POST['nombre'], $_POST['email'], $_POST['contrasena'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $pass);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Faltan datos en el formulario.";
}


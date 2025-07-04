<?php
require_once('../conexion.php');

// Asegúrate de que en tu formulario uses name="email"
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT id_usuario, nombre, contraseña FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();

    if (password_verify($contrasena, $usuario['contraseña'])) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        header("Location: ../index.php");
        exit;
    } else {
        echo "⚠️ Contraseña incorrecta.";
    }
} else {
    echo "⚠️ No se encontró una cuenta con ese correo.";
}

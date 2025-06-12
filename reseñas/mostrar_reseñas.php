<?php
$conexion = new mysqli("localhost", "root", "", "tienda hara bd"); // ← Asegúrate que sea el nombre correcto

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$resultado = $conexion->query("SELECT * FROM reseñas ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reseñas</title>
    <link rel="stylesheet" href="reseña.css">
</head>
<body>
    <div class="contenedor">
        <h2>Reseñas de nuestros productos</h2>

        <?php while ($row = $resultado->fetch_assoc()): ?>
            <div class="tarjeta-reseña">
                <p><strong>Calificación:</strong> <?php echo $row['calificacion']; ?>/5</p>
                <p><?php echo htmlspecialchars($row['comentario']); ?></p>
                <small><em>Fecha: <?php echo $row['fecha']; ?></em></small>
            </div>
        <?php endwhile; ?>

        <a href="../index.php" class="boton-volver">← Volver al inicio</a>
    </div>
</body>
</html>
<?php
$conexion->close();
?>

<?php
$conexion = new mysqli("localhost", "root", "", "tienda hara bd");

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --verde-principal: #3bb77e;
            --verde-oscuro: #267a4a;
            --verde-suave: #7ed6a7;
            --blanco: #fff;
            --gris-fondo: #f7f7f7;
            --gris-borde: #e0e0e0;
            --texto: #222;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: var(--gris-fondo);
            color: var(--texto);
        }
        .contenedor {
            max-width: 700px;
            margin: 50px auto 0 auto;
            background: var(--blanco);
            border-radius: 18px;
            box-shadow: 0 2px 16px #e0e0e0;
            padding: 40px 24px 32px 24px;
        }
        h2 {
            color: var(--verde-principal);
            text-align: center;
            margin-bottom: 36px;
            font-size: 2em;
            letter-spacing: 1px;
        }
        .tarjeta-reseña {
            background: var(--gris-fondo);
            border-left: 6px solid var(--verde-principal);
            border-radius: 12px;
            box-shadow: 0 2px 8px #e0e0e0;
            padding: 22px 28px 18px 28px;
            margin-bottom: 28px;
            position: relative;
        }
        .tarjeta-reseña p {
            margin: 0 0 10px 0;
            font-size: 1.08em;
        }
        .tarjeta-reseña strong {
            color: var(--verde-oscuro);
        }
        .tarjeta-reseña small {
            color: #888;
            font-size: 0.98em;
        }
        .estrellas {
            color: #ffc107;
            font-size: 1.2em;
            margin-bottom: 6px;
            display: inline-block;
        }
        .sin-reseñas {
            text-align: center;
            color: #888;
            font-size: 1.1em;
            margin: 40px 0;
        }
        .boton-volver {
            display: inline-block;
            margin: 30px auto 0;
            padding: 12px 32px;
            background: var(--verde-principal);
            color: var(--blanco);
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.08em;
            box-shadow: 0 2px 8px #0001;
            transition: background .2s, transform .2s;
            letter-spacing: 1px;
            text-align: center;
        }
        .boton-volver:hover {
            background: var(--verde-oscuro);
            transform: translateY(-2px) scale(1.04);
        }
        .nueva-reseña-btn {
            display: inline-block;
            margin: 0 auto 30px auto;
            padding: 10px 28px;
            background: var(--verde-suave);
            color: var(--blanco);
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1em;
            box-shadow: 0 2px 8px #0001;
            transition: background .2s, transform .2s;
            letter-spacing: 1px;
        }
        .nueva-reseña-btn:hover {
            background: var(--verde-oscuro);
            transform: translateY(-2px) scale(1.04);
        }
        @media (max-width: 600px) {
            .contenedor {
                padding: 18px 4vw 18px 4vw;
            }
            .tarjeta-reseña {
                padding: 16px 10px 14px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h2><i class="fas fa-star"></i> Reseñas de nuestros productos</h2>
        <div style="text-align:center;">
            <a href="formulario.php" class="nueva-reseña-btn"><i class="fas fa-pen"></i> Escribir una reseña</a>
        </div>
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <div class="tarjeta-reseña">
                    <div class="estrellas">
                        <?php
                        $calificacion = intval($row['calificacion']);
                        for ($i = 1; $i <= 5; $i++) {
                            echo $i <= $calificacion ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                        }
                        ?>
                    </div>
                    <p><strong>Calificación:</strong> <?php echo $row['calificacion']; ?>/5</p>
                    <p><?php echo htmlspecialchars($row['comentario']); ?></p>
                    <small><em>Fecha: <?php echo $row['fecha']; ?></em></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="sin-reseñas">
                <i class="far fa-face-smile"></i> ¡Aún no hay reseñas! Sé el primero en dejar tu opinión.
            </div>
        <?php endif; ?>
        <div style="text-align:center;">
            <a href="../index.php" class="boton-volver"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
        </div>
    </div>
</body>
</html>
<?php
$conexion->close();
?>

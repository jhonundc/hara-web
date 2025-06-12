<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Deja tu reseña</title>
  <link rel="stylesheet" href="reseña.css">
  <style>
    .btn-volver {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #333;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    .btn-volver:hover {
      background-color: #555;
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <h2>Escribe una reseña</h2>
    <form action="guardar_reseña.php" method="POST">
      <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['usuario_id'] ?? 1; ?>">
      <input type="hidden" name="id_producto" value="1">

      <label for="comentario">Comentario:</label>
      <textarea name="comentario" required></textarea>

      <label for="calificacion">Calificación:</label>
      <div class="estrellas">
        <input type="radio" id="estrella5" name="calificacion" value="5" required><label for="estrella5" title="Excelente">★</label>
        <input type="radio" id="estrella4" name="calificacion" value="4"><label for="estrella4" title="Muy bueno">★</label>
        <input type="radio" id="estrella3" name="calificacion" value="3"><label for="estrella3" title="Bueno">★</label>
        <input type="radio" id="estrella2" name="calificacion" value="2"><label for="estrella2" title="Regular">★</label>
        <input type="radio" id="estrella1" name="calificacion" value="1"><label for="estrella1" title="Malo">★</label>
      </div>

      <button type="submit">Enviar reseña</button>
    </form>

    <a href="../index.php" class="btn-volver">Volver al Inicio</a>
  </div>
</body>
</html>

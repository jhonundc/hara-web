<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear cuenta - Hará Artesanal</title>
  <link rel="stylesheet" href="estilo_cesion.css"> <!-- Asegúrate que esta ruta sea correcta -->
</head>
<body>
  <div class="registro-container">
    <h2>Crear cuenta</h2>
    <form class="registro-form" action="procesar_registro.php" method="POST">
      <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>
      </div>
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email" required>
      </div>
      <div class="form-group">
        <label for="contrasena">Contraseña</label> <!-- Usar sin ñ -->
        <input type="password" name="contrasena" id="contrasena" required>
      </div>
      <button type="submit" class="boton-registro">Registrarse</button>
    </form>

    <div class="login-link">
      ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>
    </div>
    <div class="volver-tienda">
  <a href="../index.php" class="boton-volver">← Volver a la tienda</a>
</div>

  </div>
</body>
</html>

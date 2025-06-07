<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar sesión - Hará Artesanal</title>
  <link rel="stylesheet" href="estilo_cesion.css"> <!-- Asegúrate que la ruta sea correcta -->
</head>
<body>

<div class="login-container">
  <h2>Iniciar sesión</h2>
  <form method="POST" action="procesar_login.php">
    <div class="form-group">
      <label for="email">Correo electrónico</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="contrasena">Contraseña</label>
      <input type="password" name="contrasena" id="contrasena" required>
    </div>
    <button type="submit" class="boton-login">Iniciar sesión</button>
  </form>

  <div class="registro-link">
    ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
  </div>
  <div class="volver-tienda">
  <a href="../index.php" class="boton-volver">← Volver a la tienda</a>
</div>

</div>

</body>
</html>


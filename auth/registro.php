<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear cuenta - Hará Artesanal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .registro-container {
      max-width: 400px;
      margin: 60px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 24px #e0e0e0;
      padding: 36px 32px 28px 32px;
      text-align: center;
    }
    .registro-container h2 {
      color: #27ae60;
      margin-bottom: 24px;
      font-size: 2em;
      letter-spacing: 1px;
    }
    .registro-form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .form-group {
      text-align: left;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      color: #218c5a;
      font-weight: 500;
    }
    .form-group input {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #d1e7dd;
      font-size: 1em;
      background: #f9f9f9;
      transition: border .2s;
    }
    .form-group input:focus {
      border-color: #27ae60;
      outline: none;
    }
    .boton-registro {
      background: #27ae60;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 12px 0;
      font-size: 1.1em;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
      transition: background .2s;
    }
    .boton-registro:hover {
      background: #218c5a;
    }
    .login-link, .volver-tienda {
      margin-top: 18px;
      font-size: 0.98em;
    }
    .login-link a, .volver-tienda a {
      color: #27ae60;
      text-decoration: none;
      font-weight: 500;
      transition: color .2s;
    }
    .login-link a:hover, .volver-tienda a:hover {
      color: #14532d;
      text-decoration: underline;
    }
    .mensaje-error {
      background: #ffeaea;
      color: #c0392b;
      border: 1px solid #e57373;
      border-radius: 8px;
      padding: 10px;
      margin-bottom: 14px;
      font-size: 0.98em;
      display: none;
    }
    .form-group .fa-eye, .form-group .fa-eye-slash {
      position: absolute;
      right: 16px;
      top: 38px;
      cursor: pointer;
      color: #888;
    }
    .form-group.password-group {
      position: relative;
    }
  </style>
</head>
<body>
  <div class="registro-container">
    <h2><i class="fas fa-user-plus"></i> Crear cuenta</h2>
    <div id="mensajeError" class="mensaje-error"></div>
    <form class="registro-form" action="procesar_registro.php" method="POST" onsubmit="return validarRegistro();">
      <div class="form-group">
        <label for="nombre"><i class="fas fa-user"></i> Nombre completo</label>
        <input type="text" name="nombre" id="nombre" required maxlength="60" autocomplete="name">
      </div>
      <div class="form-group">
        <label for="email"><i class="fas fa-envelope"></i> Correo electrónico</label>
        <input type="email" name="email" id="email" required maxlength="80" autocomplete="email">
      </div>
      <div class="form-group password-group">
        <label for="contrasena"><i class="fas fa-lock"></i> Contraseña</label>
        <input type="password" name="contrasena" id="contrasena" required minlength="6" maxlength="32" autocomplete="new-password">
        <i class="fas fa-eye" id="togglePass1" onclick="togglePassword('contrasena', this)"></i>
      </div>
      <div class="form-group password-group">
        <label for="confirmar_contrasena"><i class="fas fa-lock"></i> Confirmar contraseña</label>
        <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" required minlength="6" maxlength="32" autocomplete="new-password">
        <i class="fas fa-eye" id="togglePass2" onclick="togglePassword('confirmar_contrasena', this)"></i>
      </div>
      <div class="form-group">
        <label>
          <input type="checkbox" name="acepto" id="acepto" required>
          Acepto los <a href="../footer-pages/terminos.html" target="_blank">términos y condiciones</a>
        </label>
      </div>
      <button type="submit" class="boton-registro"><i class="fas fa-user-plus"></i> Registrarse</button>
    </form>
    <div class="login-link">
      ¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a>
    </div>
    <div class="volver-tienda">
      <a href="../index.php" class="boton-volver">← Volver a la tienda</a>
    </div>
  </div>
  <script>
    function validarRegistro() {
      var nombre = document.getElementById('nombre').value.trim();
      var email = document.getElementById('email').value.trim();
      var pass = document.getElementById('contrasena').value;
      var pass2 = document.getElementById('confirmar_contrasena').value;
      var acepto = document.getElementById('acepto').checked;
      var mensaje = "";

      if (nombre.length < 2) mensaje += "El nombre es demasiado corto.<br>";
      if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) mensaje += "El nombre solo debe contener letras.<br>";
      if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(email)) mensaje += "Correo electrónico no válido.<br>";
      if (pass.length < 6) mensaje += "La contraseña debe tener al menos 6 caracteres.<br>";
      if (pass !== pass2) mensaje += "Las contraseñas no coinciden.<br>";
      if (!acepto) mensaje += "Debes aceptar los términos y condiciones.<br>";

      if (mensaje) {
        document.getElementById('mensajeError').innerHTML = mensaje;
        document.getElementById('mensajeError').style.display = 'block';
        return false;
      }
      document.getElementById('mensajeError').style.display = 'none';
      return true;
    }

    function togglePassword(id, icon) {
      var input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>
</html>

<?php
session_start();
require_once '../conexion.php';

// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['usuario'] ?? ''); // Usamos el campo 'usuario' del form pero es email
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Solo login por email
        $stmt = $conn->prepare("SELECT id_usuario, nombre, apellido, email, contraseña, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nombre, $apellido, $email_db, $hash, $rol);
            $stmt->fetch();
            if (password_verify($password, $hash)) {
                $_SESSION['usuario_id'] = $id;
                $_SESSION['usuario_nombre'] = $nombre . ' ' . $apellido;
                $_SESSION['rol'] = $rol;
                header('Location: ../index.php');
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "Email no encontrado.";
        }
        $stmt->close();
    } else {
        $error = "Completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Hará Artesanal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px #e0e0e0;
            padding: 36px 32px 28px 32px;
            text-align: center;
        }
        .login-container h2 {
            color: #27ae60;
            margin-bottom: 24px;
            font-size: 2em;
            letter-spacing: 1px;
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .form-group {
            text-align: left;
            position: relative;
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
        .form-group .fa-eye, .form-group .fa-eye-slash {
            position: absolute;
            right: 16px;
            top: 38px;
            cursor: pointer;
            color: #888;
        }
        .boton-login {
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
        .boton-login:hover {
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
        }
        @media (max-width: 500px) {
            .login-container {
                padding: 18px 6px 18px 6px;
            }
        }
    </style>
</head>
<body>
  <div class="login-container">
    <h2><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>
    <?php if ($error): ?>
      <div class="mensaje-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form class="login-form" method="post" autocomplete="off" onsubmit="return validarLogin();">
      <div class="form-group">
        <label for="usuario"><i class="fas fa-envelope"></i> Email</label>
        <input type="email" name="usuario" id="usuario" required maxlength="100" autocomplete="username">
      </div>
      <div class="form-group">
        <label for="password"><i class="fas fa-lock"></i> Contraseña</label>
        <input type="password" name="password" id="password" required minlength="6" maxlength="32" autocomplete="current-password">
        <i class="fas fa-eye" id="togglePassword" onclick="togglePassword('password', this)"></i>
      </div>
      <button type="submit" class="boton-login"><i class="fas fa-sign-in-alt"></i> Ingresar</button>
    </form>
    <div class="login-link">
      ¿No tienes cuenta? <a href="registro.php">Regístrate</a>
      <br>
      <a href="recuperar.php"><i class="fas fa-key"></i> ¿Olvidaste tu contraseña?</a>
    </div>
    <div class="volver-tienda">
      <a href="../index.php" class="boton-volver">← Volver a la tienda</a>
    </div>
  </div>
  <script>
    function validarLogin() {
      var usuario = document.getElementById('usuario').value.trim();
      var pass = document.getElementById('password').value;
      var mensaje = "";

      if (usuario.length < 5) mensaje += "El email es demasiado corto.<br>";
      if (pass.length < 6) mensaje += "La contraseña debe tener al menos 6 caracteres.<br>";

      if (mensaje) {
        var div = document.querySelector('.mensaje-error');
        if (!div) {
          div = document.createElement('div');
          div.className = 'mensaje-error';
          document.querySelector('.login-container').insertBefore(div, document.querySelector('.login-form'));
        }
        div.innerHTML = mensaje;
        return false;
      }
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


<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Deja tu reseña</title>
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
      background: var(--gris-fondo);
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      color: var(--texto);
    }
    .contenedor {
      max-width: 420px;
      margin: 60px auto;
      padding: 36px 32px 28px 32px;
      background: var(--blanco);
      box-shadow: 0 6px 24px rgba(59,183,126,0.10), 0 1.5px 6px #3bb77e22;
      border-radius: 18px;
      position: relative;
    }
    h2 {
      text-align: center;
      color: var(--verde-principal);
      margin-bottom: 22px;
      font-size: 2em;
      letter-spacing: 1px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    label {
      font-size: 1em;
      color: var(--verde-oscuro);
      margin-bottom: 4px;
      font-weight: 500;
    }
    textarea {
      width: 100%;
      min-height: 90px;
      max-height: 220px;
      padding: 12px 14px;
      border: 1.5px solid var(--gris-borde);
      border-radius: 8px;
      font-size: 1em;
      background: var(--gris-fondo);
      transition: border-color 0.2s, background 0.2s;
      outline: none;
      resize: vertical;
      color: var(--texto);
    }
    textarea:focus {
      border-color: var(--verde-principal);
      background: #e6f7ef;
    }
    .estrellas {
      display: flex;
      flex-direction: row-reverse;
      justify-content: flex-start;
      gap: 2px;
      margin-bottom: 8px;
    }
    .estrellas input[type="radio"] {
      display: none;
    }
    .estrellas label {
      font-size: 2em;
      color: #ccc;
      cursor: pointer;
      transition: color 0.2s;
      margin: 0 2px;
    }
    .estrellas input[type="radio"]:checked ~ label,
    .estrellas label:hover,
    .estrellas label:hover ~ label {
      color: #f5b301;
    }
    button[type="submit"] {
      width: 100%;
      padding: 13px;
      background: linear-gradient(90deg, var(--verde-principal) 80%, var(--verde-oscuro) 100%);
      color: var(--blanco);
      font-size: 1.1em;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      letter-spacing: 1px;
      box-shadow: 0 2px 8px #3bb77e22;
      transition: background 0.2s;
      margin-top: 8px;
    }
    button[type="submit"]:hover {
      background: linear-gradient(90deg, var(--verde-oscuro) 80%, var(--verde-principal) 100%);
    }
    .btn-volver {
      display: inline-block;
      margin-top: 22px;
      padding: 10px 22px;
      background: var(--verde-principal);
      color: var(--blanco);
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.2s;
      font-size: 1em;
      text-align: center;
      width: 100%;
    }
    .btn-volver:hover {
      background: var(--verde-oscuro);
    }
    #notificacion {
      display: none;
      position: fixed;
      top: 30px;
      right: 30px;
      background: var(--verde-principal);
      color: var(--blanco);
      padding: 16px 28px;
      border-radius: 8px;
      box-shadow: 0 2px 12px #0002;
      font-size: 1.08em;
      z-index: 2000;
      min-width: 200px;
      text-align: center;
      transition: opacity .3s;
    }
    @media (max-width: 600px) {
      .contenedor {
        padding: 18px 6vw 18px 6vw;
      }
      h2 {
        font-size: 1.4em;
      }
      #notificacion {
        right: 10px;
        left: 10px;
        min-width: unset;
      }
    }
  </style>
</head>
<body>
  <div class="contenedor">
    <h2><i class="fas fa-pen"></i> Escribe una reseña</h2>
    <form action="guardar_reseña.php" method="POST" id="formResena">
      <input type="hidden" name="id_usuario" value="<?php echo $_SESSION['usuario_id'] ?? 1; ?>">
      <input type="hidden" name="id_producto" value="1">

      <label for="comentario">Comentario:</label>
      <textarea name="comentario" id="comentario" required maxlength="400" placeholder="¿Qué te pareció el producto?"></textarea>

      <label for="calificacion">Calificación:</label>
      <div class="estrellas">
        <input type="radio" id="estrella5" name="calificacion" value="5" required><label for="estrella5" title="Excelente">★</label>
        <input type="radio" id="estrella4" name="calificacion" value="4"><label for="estrella4" title="Muy bueno">★</label>
        <input type="radio" id="estrella3" name="calificacion" value="3"><label for="estrella3" title="Bueno">★</label>
        <input type="radio" id="estrella2" name="calificacion" value="2"><label for="estrella2" title="Regular">★</label>
        <input type="radio" id="estrella1" name="calificacion" value="1"><label for="estrella1" title="Malo">★</label>
      </div>

      <button type="submit"><i class="fas fa-paper-plane"></i> Enviar reseña</button>
    </form>

    <a href="../index.php" class="btn-volver"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
  </div>
  <div id="notificacion"></div>
  <script>
    // Validación frontend para reseña
    document.getElementById('formResena').addEventListener('submit', function(e) {
      const comentario = document.getElementById('comentario').value.trim();
      if (comentario.length < 10) {
        mostrarNotificacion('El comentario debe tener al menos 10 caracteres.', 'error');
        e.preventDefault();
        return false;
      }
    });

    // Notificación flotante
    function mostrarNotificacion(msg, tipo = 'exito') {
      var notif = document.getElementById('notificacion');
      notif.textContent = msg;
      notif.style.background = tipo === 'exito' ? '#3bb77e' : '#e74c3c';
      notif.style.display = 'block';
      notif.style.opacity = '1';
      setTimeout(function() {
        notif.style.opacity = '0';
        setTimeout(function() { notif.style.display = 'none'; }, 400);
      }, 3500);
    }

    // Mostrar notificación si viene de guardar_reseña.php
    <?php if (isset($_GET['exito'])): ?>
      mostrarNotificacion('¡Gracias por tu reseña!', 'exito');
    <?php elseif (isset($_GET['error'])): ?>
      mostrarNotificacion('Hubo un error al guardar la reseña.', 'error');
    <?php endif; ?>
  </script>
</body>
</html>

<?php
require_once 'conexion.php';
$conn = $GLOBALS['conn']; // Traemos la conexión global

// Filtro por tipo (si existe en la URL)
$filtro = isset($_GET['tipo']) ? $_GET['tipo'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Hará Artesanal</title>
  <link rel="stylesheet" href="estilos.css">
  <style>
    /* Slider pantalla completa */
    .slider-fullscreen {
      position: relative;
      width: 100%;
      height: 80vh;
      overflow: hidden;
    }

    .slider-fullscreen .slide {
      display: none;
      width: 100%;
      height: 100%;
    }

    .slider-fullscreen .slide img {
  width: 100%;
  height: auto;
  max-height: 100%;
  object-fit: contain;
  display: block;
  margin: 0 auto;
}

    .slider-fullscreen .active {
      display: block;
    }

    .slider-fullscreen .prev,
    .slider-fullscreen .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 40px;
      color: white;
      background: rgba(0,0,0,0.5);
      padding: 10px;
      border-radius: 50%;
      cursor: pointer;
      user-select: none;
      z-index: 10;
    }

    .slider-fullscreen .prev {
      left: 20px;
    }

    .slider-fullscreen .next {
      right: 20px;
    }

    /* Productos y estilos anteriores */
    .productos {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      padding: 20px;
    }

    .producto {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 10px;
      width: 200px;
      box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
      text-align: center;
      position: relative;
    }

    .imagen-contenedor {
      position: relative;
      overflow: hidden;
    }

    .boton-carrito {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 5px;
      padding: 4px 6px;
      text-decoration: none;
      font-size: 18px;
      color: #333;
      box-shadow: 0 0 5px rgba(0,0,0,0.15);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.3s ease;
    }

    .imagen-contenedor:hover .boton-carrito {
      opacity: 1;
      pointer-events: auto;
    }

    .agotado {
      color: red;
      font-weight: bold;
    }
    
  </style>
</head>

<body>
  
<?php session_start(); ?>
<header>
  <div class="logo">Hará Artesanal</div>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="carrito.php">Carrito</a>
    <a href="reseñas/formulario.php">Dejar Reseña</a>

    <?php if (isset($_SESSION['usuario_id'])): ?>
      <span>Hola, <?php echo $_SESSION['usuario_nombre']; ?></span>
      <a href="auth/logout.php">Cerrar sesión</a>
    <?php else: ?>
      <a href="auth/login.php">Iniciar sesión</a>
    <?php endif; ?>
  </nav>

  <?php if (isset($_GET['exito']) && $_GET['exito'] == '1'): ?>
  <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin: 20px auto; max-width: 600px; text-align: center; border: 1px solid #c3e6cb;">
    ¡Tu mensaje fue enviado exitosamente!
  </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
  <div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin: 20px auto; max-width: 600px; text-align: center; border: 1px solid #f5c6cb;">
    Hubo un problema al enviar tu mensaje. Intenta nuevamente.
  </div>
<?php endif; ?>

</header>


<!-- SLIDER DE IMAGENES -->
<!-- SLIDER DE IMAGENES con filtros -->
<div class="slider-fullscreen">
  <div class="slide active">
    <a href="index.php?tipo=jabon">
      <img src="imagenes/slide1.jpg" alt="Filtrar por jabones">
    </a>
  </div>
  <div class="slide">
    <a href="index.php?tipo=vela">
      <img src="imagenes/slide2.jpg" alt="Filtrar por velas">
    </a>
  </div>
  <div class="slide">
    <a href="index.php">
      <img src="imagenes/slide3.jpg" alt="Ver todos los productos">
    </a>
  </div>

  <!-- Flechas -->
  <a class="prev" onclick="moverSlide(-1)">&#10094;</a>
  <a class="next" onclick="moverSlide(1)">&#10095;</a>
</div>

<!-- PRODUCTOS -->
<div class="productos">
<?php
$query = "SELECT id_producto, nombre, precio, imagen_url, stock FROM productos WHERE activo = 1";
if ($filtro) {
    $tipoSeguro = $conn->real_escape_string($filtro);
    $query .= " AND tipo = '$tipoSeguro'";
}
$resultado = $conn->query($query);

if ($resultado && $resultado->num_rows > 0) {
    while ($producto = $resultado->fetch_assoc()) {
        echo "<div class='producto'>
    <div class='imagen-contenedor'>
        <img src='{$producto['imagen_url']}' alt='{$producto['nombre']}'>
        <a href='carrito.php?agregar={$producto['id_producto']}' class='boton-carrito'>🛒</a>
    </div>
    <h3>
      <a href='detalle.php?id={$producto['id_producto']}'>
        {$producto['nombre']}
      </a>
    </h3>
    <p>Precio: \${$producto['precio']}</p>";
        if ($producto['stock'] <= 0) {
            echo "<p class='agotado'>Agotado</p>";
        }
        echo "</div>";
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}
?>
</div>

<footer style="background:#333; color:#fff; padding:40px 20px; font-family:Arial;">
  <div style="display: flex; flex-wrap: wrap; justify-content: space-around; text-align:left;">
    
    <div>
      <h4>Sobre Nosotros</h4>
      <ul style="list-style:none; padding:0;">
        <li><a href="footer-pages/quienes-somos.html" style="color:#fff; text-decoration:none;">Quiénes Somos</a></li>
        <li><a href="footer-pages/nuestros-productos.html" style="color:#fff; text-decoration:none;">Nuestros Productos</a></li>
        <li><a href="reseñas/mostrar_reseñas.php" style="color:#fff; text-decoration:none;">Reseñas</a></li>
      </ul>
    </div>

    <div>
      <h4>Soporte</h4>
      <ul style="list-style:none; padding:0;">
        <li><a href="footer-pages/preguntas-frecuentes.html" style="color:#fff; text-decoration:none;">Preguntas Frecuentes</a></li>
        <li><a href="footer-pages/contacto.html" style="color:#fff; text-decoration:none;">Contáctanos</a></li>
        <li><a href="footer-pages/politica-cambios.html" style="color:#fff; text-decoration:none;">Política de Cambios</a></li>
      </ul>
    </div>

    <div>
      <h4>Legales</h4>
      <ul style="list-style:none; padding:0;">
        <li><a href="#reclamaciones" style="color:#fff; text-decoration:none;">Libro de Reclamaciones</a></li>
      </ul>
    </div>
  </div>

  <div style="text-align:center; margin-top:30px;">
    <a href="https://www.instagram.com/hara_art11?igsh=b3dwcWp6bG1zaTNr" target="_blank">
      <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram" style="width:30px; margin:0 10px;">
    </a>
  </div>

  <div style="text-align:center; margin-top:20px; font-size:14px;">
    © 2025, HARA . Todos los derechos reservados.
  </div>

  <!-- WhatsApp botón flotante -->
  <a href="https://wa.me/51945666911?text=Deseo%20más%20información%20sobre%20sus%20productos" 
   style="position:fixed; bottom:20px; right:20px; z-index:1000;">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" 
       alt="WhatsApp" 
       style="width:50px;">
</a>

</footer>

<!-- JS PARA SLIDER -->
<script>
let slideIndex = 0;
const slides = document.querySelectorAll(".slide");

function mostrarSlide(index) {
  slides.forEach(slide => slide.classList.remove("active"));
  slides[index].classList.add("active");
}

function moverSlide(direccion) {
  slideIndex += direccion;
  if (slideIndex < 0) slideIndex = slides.length - 1;
  if (slideIndex >= slides.length) slideIndex = 0;
  mostrarSlide(slideIndex);
}
</script>

<!-- JS PARA MODAL -->
<script>
  const modal = document.getElementById("modal");
  const openModal = document.getElementById("openModal");
  const closeBtn = modal.querySelector(".close");
  const contenidoModal = document.getElementById("contenidoModal");

  openModal.onclick = function (e) {
    e.preventDefault();
    fetch("footer-pages/quienes-somos.html")
      .then(res => res.text())
      .then(data => {
        contenidoModal.innerHTML = data;
        modal.style.display = "block";
      })
      .catch(err => {
        contenidoModal.innerHTML = `<p style="color:red;">Error al cargar el contenido.</p>`;
        modal.style.display = "block";
        console.error(err);
      });
  };

  closeBtn.onclick = function () {
    modal.style.display = "none";
    contenidoModal.innerHTML = "";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
      contenidoModal.innerHTML = "";
    }
  };
</script>

</body>
</html>


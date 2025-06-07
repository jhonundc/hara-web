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
    <?php if (isset($_SESSION['usuario_id'])): ?>
      <span>Hola, <?php echo $_SESSION['usuario_nombre']; ?></span>
      <a href="auth/logout.php">Cerrar sesión</a>
    <?php else: ?>
      <a href="auth/login.php">Iniciar sesión</a>
    <?php endif; ?>
  </nav>
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

<footer>
  <p>&copy; 2025 Hará Artesanal. Todos los derechos reservados.</p>
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

// Auto slide (opcional)
// setInterval(() => moverSlide(1), 8000);
</script>

</body>
</html>

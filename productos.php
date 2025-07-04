<?php
require_once 'conexion.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Filtros
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtro_precio_min = isset($_GET['precio_min']) ? floatval($_GET['precio_min']) : '';
$filtro_precio_max = isset($_GET['precio_max']) ? floatval($_GET['precio_max']) : '';

// Consulta de tipos para el filtro
$tipos = [];
$resTipos = $conn->query("SELECT DISTINCT tipo FROM productos WHERE activo=1");
while ($row = $resTipos->fetch_assoc()) {
    $tipos[] = $row['tipo'];
}

// Consulta de productos con filtros
$query = "SELECT id_producto, nombre, precio, imagen_url, stock, tipo, descripcion FROM productos WHERE activo = 1";
if ($filtro_tipo) {
    $tipoSeguro = $conn->real_escape_string($filtro_tipo);
    $query .= " AND tipo = '$tipoSeguro'";
}
if ($filtro_precio_min !== '' && $filtro_precio_max !== '') {
    $query .= " AND precio BETWEEN $filtro_precio_min AND $filtro_precio_max";
} elseif ($filtro_precio_min !== '') {
    $query .= " AND precio >= $filtro_precio_min";
} elseif ($filtro_precio_max !== '') {
    $query .= " AND precio <= $filtro_precio_max";
}
$resultado = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Términos y Condiciones - Hará Artesanal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="estilos.css?v=2">
    <style>
        :root {
  --verde-suave: #7ed6a7;
  --verde-oscuro: #218c5a;
  --gris-claro: #f5f7fa;
  --beige: #f9f6f2;
  --blanco: #fff;
  --texto: #333;
}

body {
  background: #f5f7fa;
  color: #333;
  margin: 0;
  padding: 0;
}

header, .header-superior {
  background: #fff;
  box-shadow: 0 2px 8px #e0e0e0;
  width: 100vw;
  margin: 0;
  padding: 0;
}

.header-barra {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 40px;
  width: 100%;
  box-sizing: border-box;
  /* Elimina cualquier max-width o margin: 0 auto aquí */
}

.logo {
  display: flex;
  align-items: center;
  font-size: 2em;
  font-weight: bold;
  color: #27ae60;
  letter-spacing: 1px;
  white-space: nowrap;
  gap: 12px;
}

.logo-img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #7ed6a7;
  background: #fff;
}

.busqueda-productos {
  flex: 1 1 400px;
  display: flex;
  justify-content: center;
  align-items: center;
  max-width: 420px;
}

.busqueda-productos input[type="text"] {
  width: 260px;
  padding: 8px 12px;
  border-radius: 8px 0 0 8px;
  border: 1px solid #d1e7dd;
  font-size: 1em;
  outline: none;
}

.busqueda-productos button {
  background: #27ae60;
  color: #fff;
  border: none;
  border-radius: 0 8px 8px 0;
  padding: 8px 16px;
  font-size: 1.1em;
  cursor: pointer;
  transition: background .2s;
}

.busqueda-productos button:hover {
  background: #14532d;
}

nav {
  display: flex;
  gap: 18px;
  align-items: center;
}

nav a {
  color: #222;
  text-decoration: none;
  font-size: 1.08em;
  font-weight: 500;
  transition: color .2s;
}

nav a:hover {
  color: #27ae60;
}

.btn-carrito, .btn-pagar, .btn-seguir, .btn-carrito-producto {
  background: #7ed6a7;
  color: #fff;
  border: none;
}

.btn-carrito:hover, .btn-pagar:hover, .btn-seguir:hover, .btn-carrito-producto:hover {
  background: #218c5a;
}

input, select, textarea {
  border: 1px solid #d1e7dd;
  border-radius: 6px;
  padding: 7px 10px;
  background: var(--blanco);
  color: var(--texto);
}

.filtros-productos {
  background: #f9f6f2;
  border-radius: 12px;
  padding: 18px 24px;
  margin: 30px auto 0 auto;
  max-width: 900px;
  display: flex;
  gap: 18px;
  align-items: center;
  justify-content: center;
  flex-wrap: wrap;
}

.filtros-productos select,
.filtros-productos input {
  border: 1px solid #d1e7dd;
  border-radius: 6px;
  padding: 7px 10px;
  background: var(--blanco);
  color: var(--texto);
}

.filtros-productos button {
  background: var(--verde-suave);
  color: var(--blanco);
  border: none;
  border-radius: 6px;
  padding: 8px 18px;
  font-size: 1em;
  cursor: pointer;
  transition: background .2s;
}

.filtros-productos button:hover {
  background: var(--verde-oscuro);
}

.productos {
  display: flex;
  flex-wrap: wrap;
  gap: 32px;
  justify-content: flex-start; /* Cambia de center a flex-start */
  margin: 40px auto;
  max-width: 1400px; /* Más ancho para ocupar más pantalla */
  padding: 0 32px;
  min-height: 400px;
}

.producto {
  background: var(--blanco);
  border-radius: 16px;
  box-shadow: 0 2px 12px #e0e0e0;
  width: 240px;
  padding: 18px 16px 22px 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: box-shadow .2s;
  margin-bottom: 24px;
}

.producto-card:hover {
  box-shadow: 0 6px 24px #b7e4c7;
}

.producto-card img {
  width: 180px;
  height: 180px;
  object-fit: cover;
  border-radius: 12px;
  margin-bottom: 14px;
  background: var(--beige);
}

.producto-nombre {
  font-size: 1.1em;
  font-weight: bold;
  color: var(--verde-oscuro);
  margin-bottom: 6px;
  text-align: center;
}

.producto-precio {
  color: var(--verde-suave);
  font-size: 1.1em;
  margin-bottom: 10px;
}

.btn-carrito-producto {
  background: var(--verde-suave);
  color: var(--blanco);
  border: none;
  border-radius: 8px;
  padding: 10px 18px;
  font-size: 1em;
  font-weight: bold;
  cursor: pointer;
  transition: background .2s;
  margin-top: 8px;
}

.btn-carrito-producto:hover {
  background: var(--verde-oscuro);
}


@media (max-width: 900px) {
  .header-barra {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 10px 12px 10px;
  }
  nav {
    gap: 14px;
    flex-wrap: wrap;
  }
  .logo {
    font-size: 1.3em;
  }
  .logo-img {
    width: 32px;
    height: 32px;
    margin-right: 8px;
  }
  .busqueda-productos input[type="text"] {
    width: 100%;
    min-width: 0;
  }
}

.slider {
  width: 100%;
  height: 320px; /* Ajusta aquí la altura */
  overflow: hidden;
  position: relative;
}

.slider img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.footer {
  background: #111;
  color: #fff;
  padding: 40px 0 0 0;
  font-size: 1em;
  margin-top: 60px;
}

.footer-contenido {
  display: flex;
  justify-content: center;
  gap: 60px;
  max-width: 1100px;
  margin: 0 auto;
  padding-bottom: 20px;
  flex-wrap: wrap;
}

.footer-col {
  flex: 1 1 200px;
  min-width: 180px;
}

.footer-col h4 {
  color: #7ed6a7;
  margin-bottom: 14px;
  font-size: 1.1em;
}

.footer-col ul {
  list-style: none;
  padding: 0;
  margin: 0 0 10px 0;
}

.footer-col ul li {
  margin-bottom: 8px;
}

.footer-col ul li a {
  color: #fff;
  text-decoration: none;
  font-size: 0.98em;
  transition: color .2s;
}

.footer-col ul li a:hover {
  color: #7ed6a7;
}

.footer-social {
  margin-top: 10px;
}

.footer-social a {
  color: #fff;
  font-size: 1.3em;
  margin-right: 12px;
  transition: color .2s;
  display: inline-block;
}

.footer-social a:hover {
  color: #7ed6a7;
}

.footer-copy {
  text-align: center;
  padding: 18px 10px 18px 10px;
  background: #181818;
  color: #bbb;
  font-size: 0.98em;
  border-radius: 0 0 12px 12px;
}

@media (max-width: 900px) {
  .footer-contenido {
    flex-direction: column;
    align-items: center;
    gap: 30px;
  }
  .footer-col {
    min-width: 0;
    width: 100%;
    text-align: center;
  }
}

 {
  .header-barra {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 10px 12px 10px;
  }
  nav {
    gap: 14px;
    flex-wrap: wrap;
  }
  .logo {
    font-size: 1.3em;
  }
  .logo-img {
    width: 32px;
    height: 32px;
    margin-right: 8px;
  }
  .header-verde {
    min-height: 90px;
    padding-bottom: 18px;
  }
  .busqueda-productos input[type="text"] {
    width: 100%;
    min-width: 0;
  }
}
.modal-login {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0; width: 100vw; height: 100vh;
  background: rgba(0,0,0,0.4);
  justify-content: center;
  align-items: center;
}

.modal-login-contenido {
  background: #fff;
  padding: 32px 24px;
  border-radius: 10px;
  min-width: 320px;
  box-shadow: 0 4px 24px #0002;
  position: relative;
  text-align: center;
}

.cerrar-modal-login {
  position: absolute;
  top: 12px; right: 18px;
  font-size: 1.5em;
  color: #888;
  cursor: pointer;
}

.modal-login input[type="text"],
.modal-login input[type="password"] {
  width: 90%;
  padding: 10px;
  margin: 10px 0;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.modal-login button {
  background: #27ae60;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 10px 24px;
  font-size: 1em;
  cursor: pointer;
  margin-top: 10px;
}

.modal-login button:hover {
  background: #218c5a;
}

.filtros-bar {
  background: #f9f6f2;
  border-radius: 16px;
  padding: 22px 32px;
  max-width: 900px;
  margin: 32px auto 0 auto !important;
  box-shadow: 0 2px 12px #e0e0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}

.filtro-item {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 0;
}

.filtros-bar select:focus,
.filtros-bar input:focus {
  outline: 2px solid #7ed6a7;
  border-color: #7ed6a7;
}

@media (max-width: 700px) {
  .filtros-bar {
    flex-direction: column;
    gap: 18px;
    padding: 18px 10px;
  }
  .filtro-item {
    width: 100%;
    justify-content: center;
  }
}
    </style>
</head>
<body>
<!-- Encabezado barra blanca -->
<header class="header-superior">
  <div class="header-barra">
    <div class="logo">
      <img src="imagenes/logo-marca.png" alt="Logo" class="logo-img">
      <span>Hará Artesanal</span>
    </div>
    <form class="busqueda-productos" method="get" action="buscar.php" style="max-width:420px;width:100%;display:flex;align-items:center;gap:0;">
      <input 
        type="text" 
        name="query" 
        placeholder="Buscar productos..." 
        style="width:100%;padding:10px 14px;border-radius:10px 0 0 10px;border:1px solid #d1e7dd;font-size:1em;outline:none;box-shadow:none;transition:border .2s;"
        required
      >
      <button 
        type="submit" 
        style="background:#27ae60;color:#fff;border:none;border-radius:0 10px 10px 0;padding:10px 20px;font-size:1.1em;cursor:pointer;transition:background .2s;"
      >
        <i class="fas fa-search"></i>
      </button>
    </form>
    <nav>
      <a href="index.php">Inicio</a>
      <a href="reseñas/mostrar_reseñas.php">Reseñas</a>
      <a href="footer-pages/contacto.html">Contacto</a>
      <!-- Botón de carrito en el encabezado -->
      <a href="carrito/carrito.php" id="btnCarritoHeader" title="Ver carrito" style="position:relative;">
        <i class="fas fa-shopping-cart"></i>
        <span id="carritoCantidadHeader" style="position:absolute;top:-8px;right:-10px;background:#27ae60;color:#fff;border-radius:50%;padding:2px 7px;font-size:0.8em;display:none;">0</span>
      </a>
      <?php if (isset($_SESSION['usuario_id'])): ?>
        <span style="color:var(--verde-principal);"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
        <a href="auth/logout.php">Cerrar sesión</a>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
          <a href="admin/dashboard.php"><i class="fas fa-cogs"></i> Panel Admin</a>
        <?php endif; ?>
      <?php else: ?>
  <a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
<?php endif; ?>
    </nav>
  </div>
</header>


<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
  <div class="admin-menu">
    <a href="admin/productos.php"><i class="fas fa-box"></i> Gestionar Productos</a>
    <a href="admin/usuarios.php"><i class="fas fa-users"></i> Usuarios</a>
    <a href="admin/pedidos.php"><i class="fas fa-clipboard-list"></i> Pedidos</a>
    <a href="admin/estadisticas.php"><i class="fas fa-chart-bar"></i> Estadísticas</a>
  </div>
<?php endif; ?>

<div class="slider-fullscreen">
  <div class="slide active">
    <a href="productos.php?tipo=jabon">
      <img src="imagenes/slide1.jpg" alt="Filtrar por jabones">
    </a>
  </div>
  <div class="slide">
    <a href="productos.php?tipo=vela">
      <img src="imagenes/slide2.jpg" alt="Filtrar por velas">
    </a>
  </div>
  <div class="slide">
    <a href="productos.php">
      <img src="imagenes/slide3.jpg" alt="Ver todos los productos">
    </a>
  </div>
  <a class="prev" onclick="moverSlide(-1)">&#10094;</a>
  <a class="next" onclick="moverSlide(1)">&#10095;</a>
</div>

<!-- FILTROS -->
<form class="filtros-bar" method="get" action="productos.php" style="display:flex;justify-content:center;align-items:center;gap:24px;margin:32px 0 0 0;">
  <div class="filtro-item">
    <label for="tipo" style="font-weight:600;color:#218c5a;margin-right:6px;"><i class="fas fa-tags"></i> Tipo:</label>
    <select name="tipo" id="tipo" style="padding:8px 14px;border-radius:8px;border:1px solid #d1e7dd;min-width:120px;">
      <option value="">Todos</option>
      <?php foreach ($tipos as $tipo): ?>
        <option value="<?php echo htmlspecialchars($tipo); ?>" <?php if ($filtro_tipo == $tipo) echo 'selected'; ?>>
          <?php echo htmlspecialchars(ucfirst($tipo)); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="filtro-item">
    <label style="font-weight:600;color:#218c5a;margin-right:6px;"><i class="fas fa-money-bill-wave"></i> Precio:</label>
    <input type="number" name="precio_min" min="0" step="0.1" placeholder="Mín" value="<?php echo $filtro_precio_min; ?>" style="width:80px;padding:8px 10px;border-radius:8px 0 0 8px;border:1px solid #d1e7dd;">
    <span style="margin:0 6px;color:#888;">-</span>
    <input type="number" name="precio_max" min="0" step="0.1" placeholder="Máx" value="<?php echo $filtro_precio_max; ?>" style="width:80px;padding:8px 10px;border-radius:0 8px 8px 0;border:1px solid #d1e7dd;">
  </div>
  <button type="submit" style="background:#27ae60;color:#fff;border:none;border-radius:8px;padding:10px 22px;font-size:1em;font-weight:bold;cursor:pointer;transition:background .2s;">
    <i class="fas fa-filter"></i> Filtrar
  </button>
  <a href="productos.php" style="color:#6c63ff; margin-left:10px;text-decoration:underline;">Limpiar</a>
</form>

<style>
.filtros-bar {
  background: #f9f6f2;
  border-radius: 16px;
  padding: 22px 32px;
  max-width: 900px;
  margin: 32px auto 0 auto !important;
  box-shadow: 0 2px 12px #e0e0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
}
.filtro-item {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 0;
}
.filtros-bar select:focus,
.filtros-bar input:focus {
  outline: 2px solid #7ed6a7;
  border-color: #7ed6a7;
}
@media (max-width: 700px) {
  .filtros-bar {
    flex-direction: column;
    gap: 18px;
    padding: 18px 10px;
  }
  .filtro-item {
    width: 100%;
    justify-content: center;
  }
}
</style>

<!-- PRODUCTOS -->
<div class="productos">
<?php
if ($resultado && $resultado->num_rows > 0) {
    while ($producto = $resultado->fetch_assoc()) {
        echo "<div class='producto'>
    <img src='" . htmlspecialchars($producto['imagen_url'] ?? 'imagenes/default.jpg') . "' alt='" . htmlspecialchars($producto['nombre'] ?? '') . "'>
    <h3>" . htmlspecialchars($producto['nombre']) . "</h3>
    <p>Tipo: " . htmlspecialchars(ucfirst($producto['tipo'])) . "</p>
    <p>Precio: S/ " . htmlspecialchars($producto['precio']) . "</p>
    <p>" . htmlspecialchars($producto['descripcion'] ?? '') . "</p>";
        if ($producto['stock'] <= 0) {
            echo "<p class='agotado'>Agotado</p>";
        } else {
            // Botón que redirige a carrito/carrito.php con el id del producto
            echo "<form method='post' action='carrito/carrito.php' style='display:inline;'>
      <input type='hidden' name='id_producto' value='" . $producto['id_producto'] . "'>
      <input type='hidden' name='nombre' value='" . htmlspecialchars($producto['nombre']) . "'>
      <input type='hidden' name='precio' value='" . $producto['precio'] . "'>
      <input type='hidden' name='imagen' value='" . htmlspecialchars($producto['imagen_url'] ?? 'imagenes/default.jpg') . "'>
      <button type='submit' class='btn-carrito-producto'>
        <i class='fas fa-cart-plus'></i> Añadir al carrito
      </button>
    </form>";
        }
        echo "<button class='boton-detalle' onclick=\"window.location='detalle.php?id=" . $producto['id_producto'] . "'\"><i class='fas fa-eye'></i> Ver detalle</button>";
        echo "</div>";
    }
} else {
    echo "<p>No hay productos disponibles con los filtros seleccionados.</p>";
}
?>
</div>1

<!-- Carrito lateral -->
<div id="carritoLateral" class="carrito-lateral">
  <div class="carrito-header">
    <span>Tu Carrito</span>
    <button id="cerrarCarrito" class="cerrar-carrito">&times;</button>
  </div>
  <div class="carrito-contenido" id="carritoContenido">
    <p style="text-align:center;color:#888;">Tu carrito está vacío.</p>
  </div>
  <div class="carrito-footer">
    <button class="btn-pagar" onclick="finalizarCompra()">Finalizar compra</button>
  </div>
</div>
<div id="carritoOverlay" class="carrito-overlay"></div>

<!-- Modal de inicio de sesión -->
<div id="modal-login" class="modal-login">
  <div class="modal-login-contenido">
    <span class="cerrar-modal-login" id="cerrar-modal-login">&times;</span>
    <h2>Iniciar sesión</h2>
    <form method="post" action="auth/login.php">
      <input type="text" name="usuario" placeholder="Usuario" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <button type="submit">Entrar</button>
    </form>
  </div>
</div>

<footer class="footer">
  <div class="footer-contenido">
    <div class="footer-col">
      <h4>Sobre Nosotros</h4>
      <ul>
        <li><a href="footer-pages/quienes-somos.html">Quiénes Somos</a></li>
        <li><a href="footer-pages/nuestros-productos.html">Nuestros Productos</a></li>
        <li><a href="reseñas/mostrar_reseñas.php">Reseñas</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Soporte</h4>
      <ul>
        <li><a href="footer-pages/preguntas-frecuentes.html">Preguntas Frecuentes</a></li>
        <li><a href="footer-pages/contacto.html">Contáctanos</a></li>
        <li><a href="footer-pages/politica-cambios.html">Política de Cambios</a></li>
        <li><a href="footer-pages/terminos.php">Términos y condiciones</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Legales</h4>
      <ul>
        <li><a href="footer-pages/terminos.php">Términos y condiciones</a></li>
        <li><a href="#">Política de privacidad</a></li>
      </ul>
      <div class="footer-social">
        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </div>
  <div class="footer-copy">
    © 2025 Hará Artesanal. Todos los derechos reservados.
  </div>
</footer>

<!-- WhatsApp botón flotante -->
<a href="https://wa.me/51945666911?text=Deseo%20más%20información%20sobre%20sus%20productos"
   style="position:fixed; bottom:20px; right:20px; z-index:1000;">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png"
       alt="WhatsApp"
       style="width:50px;">
</a>

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
// Mostrar el primer slide al cargar
document.addEventListener('DOMContentLoaded', function() {
  mostrarSlide(slideIndex);
});

// Autocompletado buscador
const buscador = document.getElementById('buscador');
const sugerencias = document.getElementById('sugerencias');
buscador.addEventListener('input', function() {
  let query = this.value;
  if (query.length < 2) {
    sugerencias.innerHTML = '';
    return;
  }
  fetch('buscar_sugerencias.php?query=' + encodeURIComponent(query))
    .then(res => res.json())
    .then(data => {
      let html = '';
      data.forEach(item => {
        html += `<div onclick="window.location='detalle.php?id=${item.id}'">${item.nombre}</div>`;
      });
      sugerencias.innerHTML = html;
    });
});
document.addEventListener('click', function(e) {
  if (!buscador.contains(e.target)) sugerencias.innerHTML = '';
});

function obtenerCarrito() {
  return JSON.parse(localStorage.getItem('carrito')) || [];
}
function guardarCarrito(carrito) {
  localStorage.setItem('carrito', JSON.stringify(carrito));
}
function agregarAlCarrito(id, nombre, precio, imagen) {
  let carrito = obtenerCarrito();
  let existe = carrito.find(p => p.id == id);
  if (existe) {
    existe.cantidad += 1;
  } else {
    carrito.push({id, nombre, precio, imagen, cantidad: 1});
  }
  guardarCarrito(carrito);
  mostrarCarrito();
  abrirCarrito();
}
function mostrarCarrito() {
  let carrito = obtenerCarrito();
  let cont = document.getElementById('carritoContenido');
  if (carrito.length === 0) {
    cont.innerHTML = '<p style="text-align:center;color:#888;">Tu carrito está vacío.</p>';
    return;
  }
  let html = '';
  let total = 0;
  carrito.forEach(p => {
    html += `<div class="carrito-producto">
      <img src="${p.imagen}" alt="${p.nombre}">
      <div class="carrito-producto-info">
        <div class="carrito-producto-nombre">${p.nombre}</div>
        <div class="carrito-producto-precio">S/ ${p.precio} x <span class="carrito-producto-cantidad">${p.cantidad}</span></div>
      </div>
      <button class="carrito-producto-eliminar" onclick="eliminarDelCarrito(${p.id})" title="Eliminar">&times;</button>
    </div>`;
    total += p.precio * p.cantidad;
  });
  html += `<div style="font-weight:bold;text-align:right;margin-top:10px;">Total: S/ ${total.toFixed(2)}</div>`;
  cont.innerHTML = html;
}
function eliminarDelCarrito(id) {
  let carrito = obtenerCarrito();
  carrito = carrito.filter(p => p.id != id);
  guardarCarrito(carrito);
  mostrarCarrito();
}
function abrirCarrito() {
  document.getElementById('carritoLateral').classList.add('abierto');
  document.getElementById('carritoOverlay').classList.add('abierto');
}
function cerrarCarrito() {
  document.getElementById('carritoLateral').classList.remove('abierto');
  document.getElementById('carritoOverlay').classList.remove('abierto');
}
function finalizarCompra() {
  alert('Aquí puedes redirigir al checkout o procesar el pedido.');
  // window.location = 'carrito/checkout.php';
}
document.addEventListener('DOMContentLoaded', function() {
  mostrarCarrito();
  actualizarCarritoHeader();
  document.querySelectorAll('.btn-carrito-producto').forEach(btn => {
    btn.onclick = function() {
      agregarAlCarrito(
        this.getAttribute('data-id'),
        this.getAttribute('data-nombre'),
        parseFloat(this.getAttribute('data-precio')),
        this.getAttribute('data-imagen')
      );
      actualizarCarritoHeader();
    };
  });
  document.getElementById('btnCarrito').onclick = function() {
    abrirCarrito();
  };
  document.getElementById('cerrarCarrito').onclick = function() {
    cerrarCarrito();
  };
  document.getElementById('carritoOverlay').onclick = function() {
    cerrarCarrito();
  };

  // MODAL LOGIN
  document.addEventListener('DOMContentLoaded', function() {
  var abrirModal = document.getElementById('abrir-modal-login');
  var cerrarModal = document.getElementById('cerrar-modal-login');
  var modal = document.getElementById('modal-login');
  if (abrirModal && cerrarModal && modal) {
    abrirModal.onclick = function(e) {
      e.preventDefault();
      modal.style.display = 'flex';
    };
    cerrarModal.onclick = function() {
      modal.style.display = 'none';
    };
    window.onclick = function(event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }
  }
});
});
</script>
</body>
</html>


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
$query = "SELECT id_producto, nombre, precio, imagen_url, stock, tipo FROM productos WHERE activo = 1";
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
  <title>Hará Artesanal - Inicio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --verde-principal: #3bb77e;
      --verde-oscuro: #267a4a;
      --verde-claro: #e6f7ef;
      --gris-suave: #f7f7f7;
      --blanco: #fff;
    }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f7f7f7; margin:0; }
    header {
      background: #fff;
      box-shadow: 0 1px 4px #e0e0e0;
      position: sticky;
      top: 0;
      z-index: 100;
      min-height: 70px;
      display: flex;
      align-items: center;
      padding: 0;
    }
    .logo {
      font-size: 2.2em;
      font-weight: bold;
      color: #3bb77e;
      padding: 18px 32px;
      line-height: 1;
    }
    nav {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 20px;
      padding: 0 32px;
      flex: 1;
      min-height: 70px;
    }
    nav a { color: #333; text-decoration: none; margin: 0 10px; font-weight: 500; }
    nav a:hover { color: var(--verde-principal); }
    .hero {
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(120deg, #3bb77e 60%, #6ee7b7 100%);
      color: #fff;
      padding: 90px 0 80px 0;
      min-height: 350px;
      position: relative;
      overflow: hidden;
      width: 100%;
      margin-top: 0 !important;
    }
    .hero-img-lateral {
      width: 350px;
      min-width: 250px;
      background: #f7f7f7;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
    }
    .hero-img-lateral img {
      width: 200px;
      height: auto;
      border-radius: 18px;
      box-shadow: 0 4px 18px #0002;
    }
    .hero-content {
      z-index: 2;
      max-width: 600px;
      margin-right: 5vw;
      color: #fff;
    }
    .hero h1 { font-size: 3em; margin-bottom: 10px; letter-spacing: 2px; color: var(--blanco);}
    .hero p { font-size: 1.4em; margin-bottom: 35px; color: var(--blanco);}
    .hero .cta {
      background: var(--blanco); color: var(--verde-principal); border: none; border-radius: 6px;
      padding: 16px 38px; font-size: 1.2em; text-decoration: none; font-weight: bold;
      transition: background .2s, color .2s;
      box-shadow: 0 2px 8px #aaa;
      display: inline-block;
      margin-top: 10px;
    }
    .hero .cta:hover { background: var(--verde-principal); color: var(--blanco); }
    .hero-banners {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-left: 5vw;
      z-index: 2;
    }
    .mini-banner {
      background: var(--blanco);
      border-radius: 16px;
      box-shadow: 0 2px 12px #e0e0e0;
      padding: 18px 28px;
      min-width: 220px;
      display: flex;
      align-items: center;
      gap: 15px;
      color: var(--verde-oscuro);
      font-weight: bold;
      font-size: 1.1em;
      transition: box-shadow .2s;
    }
    .mini-banner i {
      font-size: 2em;
      color: var(--verde-principal);
    }
    .destacados { background: var(--blanco); padding: 50px 10px 30px 10px; }
    .destacados h2 { text-align: center; color: var(--verde-principal); margin-bottom: 40px; font-size:2em;}
    .destacados-lista { display: flex; flex-wrap: wrap; gap: 35px; justify-content: center; }
    .destacado {
      background: var(--gris-suave); border-radius: 16px; box-shadow: 0 2px 16px #eee;
      width: 270px; padding: 22px; text-align: center; transition: box-shadow .2s;
      display: flex; flex-direction: column; align-items: center;
      position: relative;
    }
    .destacado img { width: 100%; height: 170px; object-fit: cover; border-radius: 10px; }
    .destacado h3 { font-size: 1.15em; margin: 14px 0 8px; }
    .destacado .precio { color: var(--verde-principal); font-weight: bold; font-size: 1.1em; }
    .destacado .vermas {
      background:var(--verde-principal); color:var(--blanco); border-radius:5px; padding:7px 16px; text-decoration:none; margin-top:10px; display:inline-block;
      transition: background .2s;
    }
    .destacado .vermas:hover { background:var(--verde-oscuro); }
    .ventajas { background: var(--verde-claro); padding: 50px 10px; }
    .ventajas h2 { text-align: center; color: var(--verde-principal); margin-bottom: 40px; font-size:2em;}
    .ventajas-lista { display: flex; flex-wrap: wrap; gap: 35px; justify-content: center; }
    .ventaja {
      background: var(--blanco); border-radius: 16px; box-shadow: 0 2px 12px #eee;
      width: 250px; padding: 22px; text-align: center;
      display: flex; flex-direction: column; align-items: center;
    }
    .ventaja i { font-size: 2.4em; color: var(--verde-principal); margin-bottom: 12px; }
    .testimonios { background: var(--blanco); padding: 50px 10px; }
    .testimonios h2 { text-align: center; color: var(--verde-principal); margin-bottom: 40px; font-size:2em;}
    .testimonios-lista { display: flex; flex-wrap: wrap; gap: 35px; justify-content: center; }
    .testimonio {
      background: var(--verde-claro); border-radius: 16px; box-shadow: 0 2px 12px #eee;
      width: 340px; padding: 26px; text-align: center; font-style: italic;
      display: flex; flex-direction: column; align-items: center;
    }
    .testimonio .autor { margin-top: 14px; font-weight: bold; color: var(--verde-principal); }
    .accesos-rapidos { text-align: center; margin: 50px 0 40px 0; }
    .accesos-rapidos a {
      background: var(--verde-principal); color: var(--blanco); border: none; border-radius: 6px;
      padding: 14px 28px; font-size: 1.1em; text-decoration: none; font-weight: bold;
      margin: 0 12px 12px 12px; display: inline-block; transition: background .2s;
    }
    .accesos-rapidos a:hover { background: var(--verde-oscuro); }
    @media (max-width: 1000px) {
      .hero { flex-direction: column; text-align: center; }
      .hero-img-lateral { width: 100%; justify-content: center; margin-bottom: 20px; }
      .hero-content, .hero-banners { margin: 0; }
      .hero-banners { flex-direction: row; justify-content: center; margin-top: 30px; }
    }
    @media (max-width: 900px) {
      .hero { flex-direction: column; text-align: center; }
      .hero-img-lateral { margin: 0 0 25px 0; }
    }
    @media (max-width: 600px) {
      .hero h1 { font-size: 2em; }
      .hero p { font-size: 1em; }
      .mini-banner { min-width: 140px; font-size: 0.95em; }
    }

    /* Estilos para el carrito lateral */
    .carrito-fab {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--verde-principal);
      color: var(--blanco);
      border: none;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5em;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      z-index: 1000;
      transition: background .3s;
    }
    .carrito-fab:hover {
      background: var(--verde-oscuro);
    }
    .carrito-lateral {
      position: fixed;
      top: 0;
      right: 0;
      width: 300px;
      height: 100%;
      background: var(--blanco);
      box-shadow: -4px 0 12px rgba(0, 0, 0, 0.2);
      transform: translateX(100%);
      transition: transform .3s;
      z-index: 1001;
    }
    .carrito-lateral.activo {
      transform: translateX(0);
    }
    .carrito-header {
      background: var(--verde-principal);
      color: var(--blanco);
      padding: 16px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;
    }
    .carrito-header span {
      font-size: 1.2em;
      font-weight: bold;
    }
    .cerrar-carrito {
      background: none;
      border: none;
      color: var(--blanco);
      font-size: 1.5em;
      cursor: pointer;
    }
    .carrito-contenido {
      padding: 16px;
      overflow-y: auto;
      flex: 1;
    }
    .carrito-footer {
      padding: 16px;
      text-align: center;
    }
    .btn-pagar {
      background: var(--verde-principal);
      color: var(--blanco);
      border: none;
      border-radius: 6px;
      padding: 12px 24px;
      font-size: 1.1em;
      cursor: pointer;
      transition: background .3s;
    }
    .btn-pagar:hover {
      background: var(--verde-oscuro);
    }
    .carrito-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      transform: translateX(100%);
      transition: transform .3s;
      z-index: 1000;
    }
    .carrito-overlay.activo {
      transform: translateX(0);
    }

    /* Estilos del footer */
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
  </style>
</head>
<body>
<header>
  <div class="logo">Hará Artesanal</div>
  <nav>
    <a href="productos.php">Productos</a>
    
    <a href="reseñas/formulario.php">Reseñas</a>
    <a href="footer-pages/contacto.html">Contacto</a>
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
</header>

<section class="hero">
  <div class="hero-content">
    <h1>Bienvenido a Hará Artesanal</h1>
    <p>Descubre productos artesanales únicos, hechos con amor y dedicación.<br>¡Bienestar natural para tu piel y tu hogar!</p>
    <a href="productos.php" class="cta"><i class="fas fa-store"></i> Ver Catálogo</a>
  </div>
  <div class="hero-banners">
    <div class="mini-banner"><i class="fas fa-leaf"></i> 100% Natural</div>
    <div class="mini-banner"><i class="fas fa-truck"></i> Envíos a todo el Cañete</div>
    <div class="mini-banner"><i class="fas fa-gift"></i> Kits de regalo</div>
    <div class="mini-banner"><i class="fas fa-hand-holding-heart"></i> Hecho a mano</div>
  </div>
</section>

<section class="destacados">
  <h2>Productos Destacados</h2>
  <div class="destacados-lista">
    <div class="destacado">
      <img src="imagenes/lavanda.jpg" alt="Jabón de Lavanda">
      <h3>Jabón de Lavanda</h3>
      <div class="precio">S/ 7.50</div>
      <a href="productos.php?tipo=jabon" class="vermas">Ver más</a>
    </div>
    <div class="destacado">
      <img src="imagenes/vela_lavanda.jpg" alt="Vela de Lavanda">
      <h3>Vela de Lavanda</h3>
      <div class="precio">S/ 7.00</div>
      <a href="productos.php?tipo=vela" class="vermas">Ver más</a>
    </div>
     <div class="destacado">
      <img src="imagenes/menta.jpg" alt="Jabon de Menta">
      <h3>Vela de Lavanda</h3>
      <div class="precio">S/ 7.00</div>
      <a href="productos.php?tipo=vela" class="vermas">Ver más</a>
    </div>
</section>

<section class="ventajas">
  <h2>¿Por qué elegirnos?</h2>
  <div class="ventajas-lista">
    <div class="ventaja">
      <i class="fas fa-leaf"></i>
      <h4>Ingredientes Naturales</h4>
      <p>Solo usamos ingredientes puros y ecológicos.</p>
    </div>
    <div class="ventaja">
      <i class="fas fa-hand-holding-heart"></i>
      <h4>Hecho a Mano</h4>
      <p>Cada producto es elaborado artesanalmente con dedicación.</p>
    </div>
    <div class="ventaja">
      <i class="fas fa-truck"></i>
      <h4>Envíos a Todo el País</h4>
      <p>Recibe tus productos en la puerta de tu casa.</p>
    </div>
    <div class="ventaja">
      <i class="fas fa-lock"></i>
      <h4>Compra Segura</h4>
      <p>Pagos protegidos y atención personalizada.</p>
    </div>
  </div>
</section>

<section class="testimonios">
  <h2>Lo que dicen nuestros clientes</h2>
  <div class="testimonios-lista">
    <div class="testimonio">
      "¡Me encantaron los jabones! Dejan mi piel suave y el aroma es delicioso."
      <div class="autor">- María G.</div>
    </div>
    <div class="testimonio">
      "Las velas son perfectas para relajarme después del trabajo. ¡Recomiendo mucho Hará!"
      <div class="autor">- Luis P.</div>
    </div>
    <div class="testimonio">
      "Excelente atención y productos de calidad. Volveré a comprar."
      <div class="autor">- Ana R.</div>
    </div>
  </div>
</section>

<div class="accesos-rapidos">
  <a href="productos.php"><i class="fas fa-store"></i> Catálogo</a>
  <a href="reseñas/formulario.php"><i class="fas fa-star"></i> Dejar Reseña</a>
  <a href="footer-pages/contacto.html"><i class="fas fa-envelope"></i> Contacto</a>
  <?php if (!isset($_SESSION['usuario_id'])): ?>
    <a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</a>
    <a href="auth/registro.php"><i class="fas fa-user-plus"></i> Registrarse</a>
  <?php endif; ?>
  <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <a href="admin/dashboard.php"><i class="fas fa-cogs"></i> Panel Admin</a>
  <?php endif; ?>
</div>

<!-- Botón flotante para abrir el carrito -->

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
      </ul>
    </div>
    <div class="footer-col">
      <h4>Legales</h4>
      <ul>
        <li><a href="#">Términos y condiciones</a></li>
        <li><a href="#">Política de privacidad</a></li>
      </ul>
      <div class="footer-social">
        <a href="https://www.instagram.com/hara_art11?igsh=b3dwcWp6bG1zaTNr" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="https://wa.me/51945666911?text=Deseo%20más%20información%20sobre%20sus%20productos" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
      </div>
    </div>
  </div>
  <div class="footer-copy">
    © 2025 Hará Artesanal. Todos los derechos reservados.
  </div>
</footer>

<a href="https://wa.me/51945666911?text=Deseo%20más%20información%20sobre%20sus%20productos"
   style="position:fixed; bottom:20px; right:20px; z-index:1000;">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png"
       alt="WhatsApp"
       style="width:50px;">
</a>

<script>
document.getElementById('btnCarrito').onclick = function() {
  document.getElementById('carritoLateral').classList.add('abierto');
  document.getElementById('carritoOverlay').classList.add('abierto');
};
document.getElementById('cerrarCarrito').onclick = function() {
  document.getElementById('carritoLateral').classList.remove('abierto');
  document.getElementById('carritoOverlay').classList.remove('abierto');
};
document.getElementById('carritoOverlay').onclick = function() {
  document.getElementById('carritoLateral').classList.remove('abierto');
  document.getElementById('carritoOverlay').classList.remove('abierto');
};
</script>
</body>
</html>
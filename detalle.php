<?php
require_once 'conexion.php';

if (!isset($_GET['id'])) {
    die('Producto no especificado.');
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id_producto = $id";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $producto = $res->fetch_assoc();
} else {
    die('Producto no encontrado.');
}

// Aquí deberías cargar las reseñas si las usas
$reseñas = []; // Ejemplo vacío
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($producto['nombre']); ?> - Hará Artesanal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .detalle-container {
      max-width: 900px;
      margin: 40px auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px #e0e0e0;
      display: flex;
      gap: 40px;
      padding: 40px;
      flex-wrap: wrap;
    }
    .detalle-imgs {
      flex: 1 1 320px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 18px;
    }
    .detalle-imgs img {
      width: 320px;
      height: 320px;
      object-fit: cover;
      border-radius: 14px;
      box-shadow: 0 2px 12px #d0d0d0;
    }
    .detalle-info {
      flex: 2 1 400px;
      display: flex;
      flex-direction: column;
      gap: 18px;
    }
    .detalle-nombre {
      font-size: 2em;
      color: #3bb77e;
      font-weight: bold;
    }
    .detalle-precio {
      font-size: 1.5em;
      color: #267a4a;
      font-weight: bold;
    }
    .detalle-stock {
      color: #888;
      font-size: 1em;
    }
    .detalle-desc {
      font-size: 1.1em;
      color: #444;
    }
    .detalle-beneficios, .detalle-ingredientes {
      background: #f7f7f7;
      border-radius: 10px;
      padding: 12px 18px;
      margin-bottom: 8px;
      font-size: 1em;
    }
    .detalle-acciones {
      display: flex;
      align-items: center;
      gap: 18px;
      margin-top: 10px;
    }
    .detalle-acciones input[type="number"] {
      width: 60px;
      padding: 7px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 1em;
    }
    .btn-carrito, .btn-favorito {
      background: #3bb77e;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 10px 22px;
      font-size: 1.1em;
      cursor: pointer;
      transition: background .2s;
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .btn-carrito:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    .btn-carrito:hover, .btn-favorito:hover {
      background: #267a4a;
    }
    .btn-favorito {
      background: #fff;
      color: #3bb77e;
      border: 2px solid #3bb77e;
      padding: 10px 16px;
    }
    .btn-favorito.active, .btn-favorito:active {
      background: #3bb77e;
      color: #fff;
    }
    .detalle-social {
      margin-top: 18px;
    }
    .detalle-social a {
      color: #3bb77e;
      font-size: 1.5em;
      margin-right: 14px;
      text-decoration: none;
      transition: color .2s;
    }
    .detalle-social a:hover { color: #267a4a; }
    .detalle-reseñas {
      margin: 40px auto 0 auto;
      background: #f7f7f7;
      border-radius: 12px;
      padding: 24px;
      max-width: 900px;
    }
    .detalle-reseñas h3 { color: #3bb77e; }
    .reseña {
      border-bottom: 1px solid #e0e0e0;
      padding: 12px 0;
    }
    .reseña:last-child { border-bottom: none; }
    .reseña-autor { font-weight: bold; color: #267a4a; }
    .reseña-fecha { color: #888; font-size: 0.95em; }
    .reseña-comentario { margin: 6px 0; }
    .reseña-calificacion { color: #f7b731; }
    footer {
      background: #111;
      color: #fff;
      text-align: center;
      padding: 30px 10px 20px 10px;
      font-size: 1.1em;
      margin-top: 60px;
      border-radius: 12px 12px 0 0;
      letter-spacing: 1px;
    }
    @media (max-width: 900px) {
      .detalle-container { flex-direction: column; padding: 20px; }
      .detalle-imgs img { width: 100%; height: auto; }
      .detalle-reseñas { padding: 14px; }
    }
  </style>
</head>
<body>
<div class="detalle-container">
  <div class="detalle-imgs">
    <img src="imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
  </div>
  <div class="detalle-info">
    <div class="detalle-nombre"><?php echo htmlspecialchars($producto['nombre']); ?></div>
    <div class="detalle-precio">S/ <?php echo htmlspecialchars($producto['precio']); ?></div>
    <div class="detalle-stock">
      <?php if ($producto['stock'] > 0): ?>
        <span style="color:#3bb77e;">En stock: <?php echo $producto['stock']; ?></span>
      <?php else: ?>
        <span style="color:#e74c3c;">Agotado</span>
      <?php endif; ?>
    </div>
    <div class="detalle-desc"><?php echo htmlspecialchars($producto['descripcion']); ?></div>
    <?php if (!empty($producto['ingredientes'])): ?>
      <div class="detalle-ingredientes"><b>Ingredientes:</b> <?php echo htmlspecialchars($producto['ingredientes']); ?></div>
    <?php endif; ?>
    <?php if (!empty($producto['beneficios'])): ?>
      <div class="detalle-beneficios"><b>Beneficios:</b> <?php echo htmlspecialchars($producto['beneficios']); ?></div>
    <?php endif; ?>
    <form class="detalle-acciones" method="post" action="carrito/carrito.php">
      <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
      <input type="number" name="cantidad" min="1" max="<?php echo $producto['stock']; ?>" value="1" <?php if ($producto['stock'] <= 0) echo 'disabled'; ?>>
      <button type="submit" class="btn-carrito" <?php if ($producto['stock'] <= 0) echo 'disabled'; ?>>
        <i class="fas fa-cart-plus"></i> Añadir al carrito
      </button>
      <button type="button" class="btn-favorito"><i class="fas fa-heart"></i></button>
    </form>
    <div class="detalle-social">
      <span>Compartir:</span>
      <a href="https://wa.me/?text=<?php echo urlencode($producto['nombre'].' - '.$producto['descripcion']); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
      <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://tusitio.com/detalle.php?id='.$producto['id_producto']); ?>" target="_blank"><i class="fab fa-facebook"></i></a>
      <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($producto['nombre'].' - '.$producto['descripcion']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
    </div>
  </div>
</div>

<div class="detalle-reseñas">
  <h3>Reseñas de clientes</h3>
  <?php if (!empty($reseñas)): foreach ($reseñas as $r): ?>
    <div class="reseña">
      <span class="reseña-autor"><?php echo htmlspecialchars($r['nombre']); ?></span>
      <span class="reseña-fecha"><?php echo htmlspecialchars($r['fecha']); ?></span>
      <span class="reseña-calificacion">
        <?php for ($i=0; $i<$r['calificacion']; $i++) echo '★'; ?>
      </span>
      <div class="reseña-comentario"><?php echo htmlspecialchars($r['comentario']); ?></div>
    </div>
  <?php endforeach; else: ?>
    <p>No hay reseñas aún.</p>
  <?php endif; ?>
  <form method="post" action="reseñas/guardar_reseña.php" style="margin-top:18px;">
    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
    <textarea name="comentario" placeholder="Escribe tu reseña..." required style="width:100%;padding:8px;border-radius:8px;"></textarea>
    <select name="calificacion" required style="margin-top:8px;">
      <option value="">Calificación</option>
      <option value="5">★★★★★</option>
      <option value="4">★★★★☆</option>
      <option value="3">★★★☆☆</option>
      <option value="2">★★☆☆☆</option>
      <option value="1">★☆☆☆☆</option>
    </select>
    <button type="submit" class="btn-carrito" style="margin-top:8px;"><i class="fas fa-paper-plane"></i> Enviar reseña</button>
  </form>
</div>

<footer>
  © 2025, HARA . Todos los derechos reservados.
</footer>
</body>
</html>

<?php
require_once 'conexion.php';

$busqueda = isset($_GET['query']) ? trim($_GET['query']) : '';

echo <<<STYLE
<style>
.resultados-busqueda-container {
  max-width: 1100px;
  margin: 40px auto 0 auto;
  padding: 24px 16px;
  background: #f9f6f2;
  border-radius: 18px;
  box-shadow: 0 2px 16px #e0e0e0;
}
.resultado-producto {
  display: flex;
  align-items: flex-start;
  gap: 28px;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 8px #e0e0e0;
  margin-bottom: 28px;
  padding: 22px 18px;
  transition: box-shadow .2s;
  position: relative;
}
.resultado-producto:hover {
  box-shadow: 0 6px 24px #b7e4c7;
}
.resultado-producto img {
  width: 140px;
  height: 140px;
  object-fit: cover;
  border-radius: 10px;
  background: #f5f7fa;
  border: 1px solid #e0e0e0;
}
.resultado-info {
  flex: 1;
}
.resultado-producto h3 {
  margin: 0 0 8px 0;
  color: #218c5a;
  font-size: 1.25em;
}
.resultado-producto p {
  margin: 4px 0 0 0;
  color: #444;
  font-size: 1em;
}
.resultado-producto .btn-ver-detalle {
  background: #27ae60;
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 18px;
  font-size: 1em;
  font-weight: bold;
  cursor: pointer;
  transition: background .2s;
  margin-top: 12px;
  display: inline-block;
  text-decoration: none;
}
.resultado-producto .btn-ver-detalle:hover {
  background: #14532d;
}
.resultado-producto .agotado {
  color: #e74c3c;
  font-weight: bold;
  margin-top: 8px;
}
@media (max-width: 700px) {
  .resultado-producto {
    flex-direction: column;
    align-items: center;
    padding: 14px 6px;
    gap: 12px;
  }
  .resultado-producto img {
    width: 90vw;
    max-width: 220px;
    height: 120px;
  }
}
</style>
<div class="resultados-busqueda-container">
STYLE;

if ($busqueda === '') {
    echo "<p>Por favor, escribe algo para buscar.</p></div>";
    exit;
}

$busqueda = $conn->real_escape_string($busqueda);

$sql = "SELECT * FROM productos 
        WHERE activo = 1 AND (
            nombre LIKE '%$busqueda%' 
            OR descripcion LIKE '%$busqueda%'
            OR ingredientes LIKE '%$busqueda%'
            OR beneficios LIKE '%$busqueda%'
            OR tipo LIKE '%$busqueda%'
        )";

$resultado = $conn->query($sql);

echo "<h2 style='color:#218c5a;margin-bottom:24px;'>Resultados para: <em style='color:#27ae60'>" . htmlspecialchars($busqueda) . "</em></h2>";

if ($resultado->num_rows > 0) {
    while ($producto = $resultado->fetch_assoc()) {
        $imagen = $producto['imagen_url'] ?? $producto['imagen'];
        $agotado = ($producto['stock'] <= 0);

        echo "<div class='resultado-producto'>";
        if (!empty($imagen)) {
            echo "<img src='" . htmlspecialchars($imagen) . "' alt='Imagen del producto'>";
        }
        echo "<div class='resultado-info'>";
        echo "<h3>" . htmlspecialchars($producto['nombre']) . "</h3>";
        echo "<p><strong>Descripción:</strong> " . htmlspecialchars($producto['descripcion']) . "</p>";
        echo "<p><strong>Ingredientes:</strong> " . htmlspecialchars($producto['ingredientes']) . "</p>";
        echo "<p><strong>Beneficios:</strong> " . htmlspecialchars($producto['beneficios']) . "</p>";
        echo "<p><strong>Tipo:</strong> " . htmlspecialchars(ucfirst($producto['tipo'])) . "</p>";
        echo "<p><strong>Precio:</strong> S/. " . number_format($producto['precio'], 2) . "</p>";
        echo "<p><strong>Stock:</strong> " . $producto['stock'] . "</p>";
        if ($agotado) {
            echo "<div class='agotado'>Agotado</div>";
        } else {
            // Nueva funcionalidad: botón para ver detalle y botón para añadir al carrito
            echo "<a href='detalle.php?id=" . $producto['id_producto'] . "' class='btn-ver-detalle'><i class='fas fa-eye'></i> Ver detalle</a> ";
            echo "<form method='post' action='carrito/carrito.php' style='display:inline;'>
                <input type='hidden' name='id_producto' value='" . $producto['id_producto'] . "'>
                <input type='hidden' name='nombre' value='" . htmlspecialchars($producto['nombre']) . "'>
                <input type='hidden' name='precio' value='" . $producto['precio'] . "'>
                <input type='hidden' name='imagen' value='" . htmlspecialchars($imagen) . "'>
                <button type='submit' class='btn-ver-detalle' style='background:#7ed6a7;margin-left:8px;'><i class='fas fa-cart-plus'></i> Añadir al carrito</button>
            </form>";
        }
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No se encontraron productos.</p>";
}
echo "</div>";
$conn->close();
?>

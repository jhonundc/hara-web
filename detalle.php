<?php
require_once 'conexion.php';
$conn = $GLOBALS['conn'];

if (!isset($_GET['id'])) {
    echo "Producto no especificado.";
    exit;
}

$id = intval($_GET['id']);
$query = "SELECT nombre, descripcion, precio, imagen, stock, ingredientes, beneficios FROM productos WHERE id_producto = $id AND activo = 1";
$resultado = $conn->query($query);

if ($resultado->num_rows == 0) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?php echo $producto['nombre']; ?> - Detalles</title>
  <link rel="stylesheet" href="estilos.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .detalle-container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
      display: flex;
      gap: 40px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0,0,0,0.08);
    }

    .detalle-container img {
      max-width: 400px;
      width: 100%;
      border-radius: 10px;
    }

    .detalle-info {
      flex: 1;
    }

    .detalle-info h1 {
      font-size: 2em;
      margin-bottom: 10px;
    }

    .detalle-info p {
      font-size: 1.1em;
      margin: 10px 0;
    }

    .stock {
      color: green;
      font-weight: bold;
    }

    .detalle-info select {
      padding: 6px;
      font-size: 1em;
      margin-top: 5px;
    }

    .detalle-info a.boton {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #333;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }

    .accordion {
      margin-top: 30px;
    }

    .accordion-item {
      border-top: 1px solid #ccc;
    }

    .accordion-header {
      cursor: pointer;
      padding: 12px 0;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .accordion-content {
      display: none;
      padding: 10px 0;
      color: #555;
    }

    .accordion-header::after {
      content: "+";
      font-weight: bold;
    }

    .accordion-header.active::after {
      content: "-";
    }
  </style>
</head>
<body>

<header>
  <div class="logo">Hará Artesanal</div>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="carrito.php">Carrito</a>
  </nav>
</header>

<div class="detalle-container">
 <img src="imagenes/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>">

  <div class="detalle-info">
    <h1><?php echo $producto['nombre']; ?></h1>
    <p><strong>Precio:</strong> S/ <?php echo $producto['precio']; ?></p>
    <p class="stock">✔ El artículo está en stock</p>

    <label for="cantidad">Cantidad</label><br>
    <select id="cantidad" name="cantidad">
      <?php for ($i = 1; $i <= min(10, $producto['stock']); $i++): ?>
        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
      <?php endfor; ?>
    </select>

    <br>
    <a class="boton" href="carrito.php?agregar=<?php echo $id; ?>">AÑADIR AL CARRITO</a>

    <div class="accordion">
      <div class="accordion-item">
        <div class="accordion-header">DESCRIPCIÓN</div>
        <div class="accordion-content"><?php echo nl2br($producto['descripcion']); ?></div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">INGREDIENTES</div>
        <div class="accordion-content"><?php echo nl2br($producto['ingredientes']); ?></div>
      </div>
      <div class="accordion-item">
        <div class="accordion-header">BENEFICIOS</div>
        <div class="accordion-content"><?php echo nl2br($producto['beneficios']); ?></div>
      </div>
    </div>
  </div>
</div>

<footer>
  <p>&copy; 2025 Hará Artesanal. Todos los derechos reservados.</p>
</footer>

<script>
  const headers = document.querySelectorAll('.accordion-header');
  headers.forEach(header => {
    header.addEventListener('click', () => {
      header.classList.toggle('active');
      const content = header.nextElementSibling;
      content.style.display = content.style.display === 'block' ? 'none' : 'block';
    });
  });
</script>

</body>
</html>

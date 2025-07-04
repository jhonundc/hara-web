<?php
session_start();

// Inicializa el carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito (por POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $id = intval($_POST['id_producto']);
    $nombre = $_POST['nombre'] ?? '';
    $precio = floatval($_POST['precio'] ?? 0);
    $imagen = $_POST['imagen'] ?? '';
    $cantidad = intval($_POST['cantidad'] ?? 1);

    // Si ya existe, suma cantidad
    $encontrado = false;
    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['id'] == $id) {
            $item['cantidad'] += $cantidad;
            $encontrado = true;
            break;
        }
    }
    if (!$encontrado) {
        $_SESSION['carrito'][] = [
            'id' => $id,
            'nombre' => $nombre,
            'precio' => $precio,
            'imagen' => $imagen,
            'cantidad' => $cantidad
        ];
    }
    header('Location: carrito.php');
    exit;
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $_SESSION['carrito'] = array_filter($_SESSION['carrito'], function($item) use ($id) {
        return $item['id'] != $id;
    });
    header('Location: carrito.php');
    exit;
}

// Vaciar carrito
if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
    header('Location: carrito.php');
    exit;
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras - Hará Artesanal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background: #f7f7f7;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .carrito-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px #e0e0e0;
            padding: 40px 30px 30px 30px;
        }
        h1 {
            color: #3bb77e;
            text-align: center;
            margin-bottom: 30px;
        }
        .carrito-lista {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .carrito-lista th, .carrito-lista td {
            padding: 14px 10px;
            text-align: center;
        }
        .carrito-lista th {
            background: #e6f7ef;
            color: #267a4a;
            font-size: 1.1em;
        }
        .carrito-lista td {
            background: #fafafa;
            border-bottom: 1px solid #eee;
        }
        .carrito-lista img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
        }
        .carrito-eliminar {
            background: none;
            border: none;
            color: #e74c3c;
            font-size: 1.3em;
            cursor: pointer;
            transition: color .2s;
        }
        .carrito-eliminar:hover { color: #c0392b; }
        .carrito-total {
            text-align: right;
            font-size: 1.2em;
            color: #267a4a;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .carrito-acciones {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }
        .btn-vaciar, .btn-seguir, .btn-pagar {
            background: #3bb77e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-vaciar { background: #e74c3c; }
        .btn-vaciar:hover { background: #c0392b; }
        .btn-seguir { background: #267a4a; }
        .btn-seguir:hover { background: #14532d; }
        .btn-pagar:hover { background: #267a4a; }
        .carrito-vacio {
            text-align: center;
            color: #888;
            font-size: 1.2em;
            margin: 40px 0;
        }
        @media (max-width: 700px) {
            .carrito-container { padding: 15px 5px; }
            .carrito-lista th, .carrito-lista td { padding: 8px 2px; }
        }
    </style>
</head>
<body>
<div class="carrito-container">
    <h1><i class="fas fa-shopping-cart"></i> Tu Carrito</h1>
    <?php if (empty($_SESSION['carrito'])): ?>
        <div class="carrito-vacio">
            <i class="fas fa-shopping-basket" style="font-size:2em;color:#3bb77e;"></i><br>
            Tu carrito está vacío.<br>
            <a href="../productos.php" class="btn-seguir" style="margin-top:18px;">Seguir comprando</a>
        </div>
    <?php else: ?>
        <table class="carrito-lista">
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Eliminar</th>
            </tr>
            <?php foreach ($_SESSION['carrito'] as $item): ?>
            <tr>
                <td><img src="../<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>"></td>
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                <td>S/ <?php echo number_format($item['precio'], 2); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>S/ <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                <td>
                    <a href="carrito.php?eliminar=<?php echo $item['id']; ?>" class="carrito-eliminar" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <div class="carrito-total">
            Total: S/ <?php echo number_format($total, 2); ?>
        </div>
        <div class="carrito-acciones">
            <a href="carrito.php?vaciar=1" class="btn-vaciar" onclick="return confirm('¿Vaciar carrito?')"><i class="fas fa-trash"></i> Vaciar carrito</a>
            <a href="../productos.php" class="btn-seguir"><i class="fas fa-arrow-left"></i> Seguir comprando</a>
            <a href="checkout.php" class="btn-pagar"><i class="fas fa-credit-card"></i> Finalizar compra</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
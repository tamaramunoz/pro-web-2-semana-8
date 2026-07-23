<?php
// inicialización del entorno de sesiones en el servidor
session_start();

// inventario oculto en el backend solo para validar y mapear los IDs al añadir
$inventario_productos = [
    1 => ["nombre" => "Nintendo Switch 2", "precio" => 485991],
    2 => ["nombre" => "PlayStation 5 Slim", "precio" => 512991],
    3 => ["nombre" => "Acer Nitro Lite 16", "precio" => 737991],
    4 => ["nombre" => "Asus TUF 15", "precio" => 649990],
    5 => ["nombre" => "Mouse Inalámbrico", "precio" => 32291],
    6 => ["nombre" => "Monitor Gamer 27", "precio" => 101990],
    7 => ["nombre" => "Apple iPad Air", "precio" => 799990],
    8 => ["nombre" => "Teclado Redragon", "precio" => 38990]
];

// agregar un producto al carrito
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $id = (int)$_GET['id'];
    
    if (array_key_exists($id, $inventario_productos)) {
        if (!isset($_SESSION['carrito_tienda'])) {
            $_SESSION['carrito_tienda'] = [];
        }

        if (isset($_SESSION['carrito_tienda'][$id])) {
            $_SESSION['carrito_tienda'][$id]['cantidad']++;
        } else {
            $_SESSION['carrito_tienda'][$id] = [
                "nombre" => $inventario_productos[$id]['nombre'],
                "precio" => $inventario_productos[$id]['precio'],
                "cantidad" => 1
            ];
        }
    }
    header("Location: carrito_sesion.php");
    exit;
}

// vaciar el carrito de forma segura usando unset()
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    if (isset($_SESSION['carrito_tienda'])) {
      // destrucción selectiva de la variable de sesión
      unset($_SESSION['carrito_tienda']);
    }
    header("Location: carrito_sesion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="carrito.css">
</head>
<body>

    <h1>Carrito de Compras</h1>
    <p>Estos son los productos agregados en tu carrito.</p>

    <?php if (empty($_SESSION['carrito_tienda'])): ?>
        <p>El carrito de compras está vacío actualmente.</p>
        <br>
        <a href="index.php" class="btn-accion btn-volver">⬅ Volver al Catálogo</a>
    <?php else: ?>
        
        <table class="tabla-cuadrcula">
            <thead>
                <tr>
                    <th>Nombre del Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_acumulado = 0;
                foreach ($_SESSION['carrito_tienda'] as $id => $item): 
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total_acumulado += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td>$<?php echo number_format($item['precio'], 0, ',', '.'); ?></td>
                    <td><?php echo $item['cantidad']; ?></td>
                    <td>$<?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr class="total-fila">
                    <td>VALOR TOTAL GENERAL DE LA COMPRA</td>
                    <td>-</td>
                    <td>-</td>
                    <td>$<?php echo number_format($total_acumulado, 0, ',', '.'); ?> CLP</td>
                </tr>
            </tbody>
        </table>

        <div class="footer-tabla">
            <a
              href="index.php"
              class="btn-accion btn-volver">⬅ Seguir Comprando</a>
            <a
              href="carrito_sesion.php?action=clear"
              class="btn-accion btn-vaciar">Vaciar Carrito</a>
        </div>
    <?php endif; ?>

</body>
</html>

<?php
require_once 'conexion.php';

$mensaje_producto = "";
$mensaje_cliente = "";
$mensaje_compra = "";

// procesar formulario de producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_producto') {
    $nombre = $conn->real_escape_string(trim($_POST['nombre_prod']));
    $precio = (int)$_POST['precio_prod'];
    $stock = (int)$_POST['stock_prod'];

    if (!empty($nombre) && $precio > 0 && $stock >= 0) {
        $sql = "INSERT INTO PRODUCTO (nombre, precio, stock) VALUES ('$nombre', $precio, $stock)";
        if ($conn->query($sql) === TRUE) {
            $mensaje_producto = "<p class='success'>¡Producto registrado exitosamente!</p>";
        } else {
            $mensaje_producto = "<p class='error'>Error al registrar: " . $conn->error . "</p>";
        }
    }
}

// procesar formulario de cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_cliente') {
    $nombre = $conn->real_escape_string(trim($_POST['nombre_cli']));
    $email = $conn->real_escape_string(trim($_POST['email_cli']));

    if (!empty($nombre) && !empty($email)) {
        $sql = "INSERT INTO CLIENTE (nombre, email) VALUES ('$nombre', '$email')";
        if ($conn->query($sql) === TRUE) {
            $mensaje_cliente = "<p class='success'>¡Cliente registrado exitosamente!</p>";
        } else {
            $mensaje_cliente = "<p class='error'>Error al registrar: " . $conn->error . "</p>";
        }
    }
}

// procesar formulario de compra
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_compra') {
    $id_cliente = (int)$_POST['id_cliente'];
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad_compra'];

    $res_stock = $conn->query("SELECT stock FROM PRODUCTO WHERE id_producto = $id_producto");
    if ($res_stock && $row = $res_stock->fetch_assoc()) {
        if ($row['stock'] >= $cantidad) {
            $sql = "INSERT INTO COMPRA (id_cliente, id_producto, cantidad) VALUES ($id_cliente, $id_producto, $cantidad)";
            if ($conn->query($sql) === TRUE) {
                $conn->query("UPDATE PRODUCTO SET stock = stock - $cantidad WHERE id_producto = $id_producto");
                $mensaje_compra = "<p class='success'>¡Operación de Compra registrada con éxito!</p>";
            } else {
                $mensaje_compra = "<p class='error'>Error: " . $conn->error . "</p>";
            }
        } else {
            $mensaje_compra = "<p class='error'>Error: Disponibilidad insuficiente en stock.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="administracion.css">
</head>
<body>

    <div class="panel-header">
        <h1>Base de Datos Tienda Online</h1>
        <a href="index.php" class="btn-volver btn-volver-panel">⬅ Volver a la Tienda</a>
    </div>
    <hr>

    <!-- formulario de registro -->
    <div class="grid-forms">
        <div class="card-form">
            <h3>Registrar Nuevo Producto</h3>
            <?php echo $mensaje_producto; ?>
            <form id="formProducto" method="POST" action="gestion.php" onsubmit="return validarProducto();">
                <input type="hidden" name="action" value="add_producto">
                <div class="form-group">
                    <label for="nombre_prod">Nombre del Producto:</label>
                    <input type="text" id="nombre_prod" name="nombre_prod">
                </div>
                <div class="form-group">
                    <label for="precio_prod">Precio:</label>
                    <input type="number" id="precio_prod" name="precio_prod">
                </div>
                <div class="form-group">
                    <label for="stock_prod">Stock Inicial Disponible:</label>
                    <input type="number" id="stock_prod" name="stock_prod">
                </div>
                <input type="submit" value="Guardar Producto" class="btn-accion">
            </form>
        </div>

        <div class="card-form">
            <h3>Registrar Nuevo Cliente</h3>
            <?php echo $mensaje_cliente; ?>
            <form id="formCliente" method="POST" action="gestion.php" onsubmit="return validarCliente();">
                <input type="hidden" name="action" value="add_cliente">
                <div class="form-group">
                    <label for="nombre_cli">Nombre Completo:</label>
                    <input type="text" id="nombre_cli" name="nombre_cli">
                </div>
                <div class="form-group">
                    <label for="email_cli">Correo Electrónico:</label>
                    <input type="text" id="email_cli" name="email_cli">
                </div>
                <input type="submit" value="Guardar Cliente" class="btn-accion">
            </form>
        </div>
    </div>

    <!-- transacciones de compra -->
    <div class="card-form card-compra">
        <h3>Registrar Transacción de Compra</h3>
        <?php echo $mensaje_compra; ?>
        <form method="POST" action="gestion.php">
            <input type="hidden" name="action" value="add_compra">
            <div class="grid-forms grid-no-margin">
                <div class="form-group">
                    <label>Seleccione Cliente:</label>
                    <select name="id_cliente" required>
                        <?php
                        $clientes = $conn->query("SELECT id_cliente, nombre FROM CLIENTE");
                        while($c = $clientes->fetch_assoc()) {
                            echo "<option value='{$c['id_cliente']}'>{$c['nombre']} (ID: {$c['id_cliente']})</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Seleccione Producto:</label>
                    <select name="id_producto" required>
                        <?php
                        $productos = $conn->query("SELECT id_producto, nombre, stock FROM PRODUCTO");
                        while($p = $productos->fetch_assoc()) {
                            echo "<option value='{$p['id_producto']}'>{$p['nombre']} [Disponibles: {$p['stock']}]</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Cantidad a comprar:</label>
                <input type="number" name="cantidad_compra" value="1" min="1" required class="input-cantidad">
            </div>
            <input type="submit" value="Registrar Compra" class="btn-accion btn-registrar">
        </form>
    </div>

    <!-- sección de reportes -->
    <h2>Visualización de datos del servidor</h2>

    <h3>PRODUCTOS</h3>
    <table class="tabla-cuadrcula">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock Disponible</th></tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM PRODUCTO");
            if($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    echo "<tr><td>{$row['id_producto']}</td><td>{$row['nombre']}</td><td>\${$row['precio']}</td><td>{$row['stock']} unidades</td></tr>";
                }
            } else { echo "<tr><td colspan='4'>No hay registros. Introduce al menos 3 productos.</td></tr>"; }
            ?>
        </tbody>
    </table>

    <h3>CLIENTES</h3>
    <table class="tabla-cuadrcula">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Email</th></tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM CLIENTE");
            if($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    echo "<tr><td>{$row['id_cliente']}</td><td>{$row['nombre']}</td><td>{$row['email']}</td></tr>";
                }
            } else { echo "<tr><td colspan='3'>No hay registros. Introduce al menos 3 clientes.</td></tr>"; }
            ?>
        </tbody>
    </table>

    <!-- consulta simple -->
    <h3>COMPRAS</h3>
    <table class="tabla-cuadrcula">
        <thead>
            <tr><th>ID Compra</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Fecha/Hora Transacción</th></tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT co.id_compra, cl.nombre as cliente, pr.nombre as producto, co.cantidad, co.fecha_compra
                                 FROM COMPRA co
                                 INNER JOIN CLIENTE cl ON co.id_cliente = cl.id_cliente
                                 INNER JOIN PRODUCTO pr ON co.id_producto = pr.id_producto");
            if($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    echo "<tr><td>{$row['id_compra']}</td>
                    <td>{$row['cliente']}</td><td>{$row['producto']}</td>
                    <td>{$row['cantidad']}</td><td>{$row['fecha_compra']}</td></tr>";
                }
            } else { echo "<tr><td colspan='5'>No se han registrado operaciones de compra aún.</td></tr>"; }
            ?>
        </tbody>
    </table>

    <!-- consulta avanzada -->
    <div class="advanced-query-box">
        <h2>Clientes con más de 2 Compras</h2>
        <table class="tabla-cuadrcula tabla-blanca">
            <thead>
                <tr>
                    <th>Nombre del Cliente</th>
                    <th>Email</th>
                    <th>Cantidad Total de Compras Registradas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_avanzado = "SELECT c.nombre, c.email, COUNT(co.id_compra) as total_compras
                                 FROM CLIENTE c
                                 INNER JOIN COMPRA co ON c.id_cliente = co.id_cliente
                                 GROUP BY c.id_cliente, c.nombre, c.email
                                 HAVING total_compras > 2
                                 ORDER BY total_compras DESC";
                
                $res_avanzado = $conn->query($sql_avanzado);
                if($res_avanzado && $res_avanzado->num_rows > 0) {
                    while($row = $res_avanzado->fetch_assoc()) {
                        echo "<tr class='fila-destacada'>
                                <td>👤 {$row['nombre']}</td>
                                <td>{$row['email']}</td>
                                <td class='texto-azul'>{$row['total_compras']} compras</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center; color: #666;'>
                    Ningún cliente supera las 2 compras registradas actualmente.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- validaciones de productos y clientes -->
    <script>
    function validarProducto() {
        const nombre = document.getElementById('nombre_prod').value.trim();
        const precio = document.getElementById('precio_prod').value;
        const stock = document.getElementById('stock_prod').value;

        if (nombre === "") {
            alert("El nombre del producto no puede estar vacío.");
            return false;
        }
        if (precio <= 0 || precio === "") {
            alert("El precio debe ser un número entero mayor a 0.");
            return false;
        }
        if (stock < 0 || stock === "") {
            alert("El stock no puede ser un valor negativo.");
            return false;
        }
        return true;
    }

    function validarCliente() {
        const nombre = document.getElementById('nombre_cli').value.trim();
        const email = document.getElementById('email_cli').value.trim();
        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (nombre === "") {
            alert("Por favor, introduce el nombre completo del cliente.");
            return false;
        }
        if (email === "") {
            alert("El correo electrónico es obligatorio.");
            return false;
        }
        if (!regexEmail.test(email)) {
            alert("Estructura de correo electrónico no válida (ejemplo@dominio.com).");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>

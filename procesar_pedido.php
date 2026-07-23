<?php
require_once 'Pedido.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="procesamiento.css"> 
    <title>Confirmación de Pedido</title>
</head>
<body>

<?php
echo "<div class='processor-container'>";
echo "<h2 class='processor-title'>Confirmación del Pedido</h2>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $producto      = isset($_POST['producto']) ? trim($_POST['producto']) : '';
    $unidades      = isset($_POST['unidades']) ? $_POST['unidades'] : 0;
    $tipo_pedido   = isset($_POST['tipo_pedido']) ? $_POST['tipo_pedido'] : '';
    $descripcion   = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : 'Sin observaciones.';

    if (empty($producto) || empty($unidades) || empty($tipo_pedido) || empty($descripcion)) {
        echo "<p class='processor-error'>Error: Campos obligatorios vacíos.</p>";
        echo "<a href='javascript:history.back()' class='btn-back'>Regresar al formulario</a>";
        echo "</div></body></html>";
        exit;
    }

    $nuevoPedido = new Pedido($descripcion, $tipo_pedido, $producto, $unidades, $observaciones);

    echo "<p class='processor-success'><strong>Instanciación Exitosa:</strong> Se ha creado un objeto de la clase Pedido en la memoria del servidor.</p>";
    
    echo "<h3>Detalles recuperados:</h3>";
    echo "<ul>";
    echo "<li><strong>Resumen General:</strong> " . $nuevoPedido->obtenerResumen() . "</li>";
    echo "<li><strong>Descripción Técnica:</strong> " . htmlspecialchars($nuevoPedido->descripcion) . "</li>";
    echo "<li><strong>Comentarios / Notas:</strong> " . htmlspecialchars($nuevoPedido->observaciones) . "</li>";
    echo "</ul>";

    echo "<h3>Prueba del Motor de Búsqueda de la Clase:</h3>";
    $criterioDePrueba = "Express";
    echo "<p>Buscando coincidencia con el término '<strong>{$criterioDePrueba}</strong>'...</p>";
    
    if ($nuevoPedido->coincideConBusqueda($criterioDePrueba)) {
        echo "<p class='search-match'>¡Coincidencia Encontrada! El pedido calza con los parámetros de filtrado.</p>";
    } else {
        echo "<p class='search-no-match'>No se encontraron coincidencias para este término.</p>";
    }

    echo "<a href='index.php' class='btn-back'>Volver a la Tienda</a>";

} else {
    echo "<p class='processor-error'>Acceso restringido.</p>";
}

echo "</div>";
?>

</body>
</html>

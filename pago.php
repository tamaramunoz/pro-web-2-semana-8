<?php
session_start();

$total_pagar = 0;
if (isset($_SESSION['carrito_tienda']) && !empty($_SESSION['carrito_tienda'])) {
    foreach ($_SESSION['carrito_tienda'] as $item) {
        $total_pagar += ($item['precio'] * $item['cantidad']);
    }
}

$pago_exitoso = false;
$id_transaccion = "";

// Simulación de procesamiento de pago POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_pago'])) {
    if ($total_pagar > 0) {
        $pago_exitoso = true;
        $id_transaccion = "TRX-" . strtoupper(substr(md5(time()), 0, 8));
        
        // Vaciar carrito tras pago exitoso
        unset($_SESSION['carrito_tienda']);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Pago Segura</title>
    <link rel="stylesheet" href="pago.css">
</head>
<body>

<div class="pay-container">
    <?php if ($pago_exitoso): ?>
        <div class="success-box">
            <div class="success-icon">✓</div>
            <h2>¡Pago Realizado con Éxito!</h2>
            <p>Tu transacción ha sido procesada de manera segura.</p>
            <p><strong>N° Transacción:</strong> <?php echo $id_transaccion; ?></p>
            <br>
            <a href="index.php" class="btn-pay btn-pay-link">Volver a la Tienda</a>
        </div>
    <?php elseif ($total_pagar == 0): ?>
        <h2>Carrito Vacío</h2>
        <p>No tienes productos pendientes de pago.</p>
        <a href="index.php" class="btn-back">⬅ Ir al Catálogo</a>
    <?php else: ?>
        <span class="disclaimer-secure">NO INGRESAR DATOS REALES, ESTE SITIO ES EXCLUSIVO PARA FINES ACADÉMICOS</span>
        <h2>Pasarela de Pago</h2>
        <p><strong>Monto Total a Pagar:</strong> $<?php echo number_format($total_pagar, 0, ',', '.'); ?> CLP</p>
        <hr><br>

        <form method="POST" action="pago.php">
            <input type="hidden" name="procesar_pago" value="1">
            
            <div class="form-group">
                <label for="titular">Nombre del Titular:</label>
                <input type="text" id="titular" name="titular" placeholder="Ej: Juan Díaz" required>
            </div>

            <div class="form-group">
                <label for="tarjeta">Número de Tarjeta:</label>
                <input type="text" id="tarjeta" name="tarjeta" placeholder="4532 •••• •••• 8890" maxlength="19" required>
            </div>

            <div class="form-row">
                <div class="form-group form-group-flex-2">
                    <label for="expiracion">Vencimiento (MM/AA):</label>
                    <input type="text" id="expiracion" name="expiracion" placeholder="12/28" maxlength="5" required>
                </div>
                <div class="form-group form-group-flex-1">
                    <label for="cvc">CVC:</label>
                    <input type="password" id="cvc" name="cvc" placeholder="123" maxlength="4" required>
                </div>
            </div>

            <button type="submit" class="btn-pay">Pagar $<?php echo number_format($total_pagar, 0, ',', '.'); ?> CLP</button>
        </form>

        <a href="carrito_sesion.php" class="btn-back">⬅ Volver al Carrito</a>
    <?php endif; ?>
</div>

</body>
</html>

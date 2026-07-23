<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pliant:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <title>Tienda de Comercio Electrónico - FullStack</title>
</head>
<body>
    <div id="notification-toast" class="toast" style="display: none;"></div>
    <header class="store-header">
      <h2>Tienda Online</h2>
      <div>
          <a href="gestion.php" class="btn-accion" style="margin-right: 15px; background-color: #28a745; text-decoration: none;">⚙️ Panel Administración (Semana 6)</a>
          
          <a href="carrito_sesion.php" id=\"cart-status\" class=\"cart-button\">
                Carrito: <span id=\"cart-counter\"><?php
                echo isset($_SESSION['carrito_tienda']) ? array_sum(array_column($_SESSION['carrito_tienda'], 'cantidad')) : 0;
              ?></span> productos
          </a>
      </div>
    </header>

    <div class="search-container">
        <input type="text" id="product-search" placeholder="Buscar producto">
        <button id="search-btn">Buscar</button>
        <button id="clear-btn">Limpiar</button> 
    </div>

    <div id="results-container"></div>

    <hr class="section-divider">

    <?php
    function generarTarjetaResena($usuario, $calificacion, $comentario) {
        $calificacion = (int)$calificacion;
        if ($calificacion < 1) $calificacion = 1;
        if ($calificacion > 5) $calificacion = 5;
        
        $estrellas = str_repeat("★", $calificacion) . str_repeat("☆", 5 - $calificacion);
        $usuarioLimpio = htmlspecialchars($usuario);
        $comentarioLimpio = htmlspecialchars($comentario);

        return "
        <div class='review-card'>
            <h4>Usuario: {$usuarioLimpio} <span class='review-stars'>{$estrellas}</span></h4>
            <p>{$comentarioLimpio}</p>
        </div>";
    }
    ?>

    <div>
      <div class="reviews-section">
        <h3>Calificar Producto Comprado</h3>
        <form action="" method="POST">
          <p>
            <label for="usuario">Nombre de Usuario:</label><br>
            <input type="text" id="usuario" name="usuario" required>
          </p>
          <p>
            <label for="calificacion">Calificación (1 al 5):</label><br>
            <input type="number" id="calificacion" name="calificacion" min="1" max="5" required>
          </p>
          <p>
              <label for="comentario">Tu Reseña:</label><br>
              <textarea id="comentario" name="comentario" rows="3" required></textarea>
          </p>
          <input type="submit" name="enviar_resena" value="Publicar Reseña" class="btn-pedido">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar_resena'])) {
            echo "<h4>Reseñas Recientes:</h4>";
            echo generarTarjetaResena($_POST['usuario'], $_POST['calificacion'], $_POST['comentario']);
        }
        ?>
      </div>
    </div>

    <div class="pedido-container">
      <h2>Formulario de Registro de Pedidos</h2>
      
      <form action="procesar_pedido.php" method="POST">
         <p>
            <label for="producto">Producto Solicitado:</label><br>
            <select id="producto" name="producto" required>
                <option value="">-- Seleccione un producto del catálogo --</option>
            </select>
         </p>
          <p>
            <label for="unidades">Unidades:</label><br>
            <input type="number" id="unidades" name="unidades" min="1" required>
          </p>
          <p>
              <label for="tipo_pedido">Tipo de Pedido:</label><br>
              <select id="tipo_pedido" name="tipo_pedido" required>
                  <option value="Normal">Despacho Normal (3 a 5 días)</option>
                  <option value="Express">Envío Express de 24 horas</option>
                  <option value="Internacional">Importación Internacional</option>
              </select>
          </p>
          <p>
              <label for="descripcion">Descripción del Pedido:</label><br>
              <input 
                type="text"
                id="descripcion"
                name="descripcion"
                placeholder="Ej: Pedido de consolas para sucursal" 
                required>
          </p>
          <p>
              <label for="observaciones">Observaciones Especiales:</label><br>
              <textarea
                id="observaciones"
                name="observaciones"
                placeholder="Ej: Dejar con conserjería...">
            </textarea>
          </p>
          <p class="pedido-footer">
              <input type="submit" value="Registrar y Enviar Pedido" class="btn-pedido">
          </p>
      </form>
    </div>

    <script src="producto.js"></script> 
    <script src="app.js"></script>
</body>
</html>

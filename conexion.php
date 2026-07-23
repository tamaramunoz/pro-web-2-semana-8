<?php
// parámetros de configuración
$host = "localhost";
$usuario = "root";
$password = "";
$basedatos = "TIENDA";

// establecer la conexión utilizando el método mysqli
$conn = new mysqli($host, $usuario, $password, $basedatos);

// validar la conexión segura
if ($conn->connect_error) {
    die("Conexión fallida - ERROR de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>

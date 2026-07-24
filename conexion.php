<?php
$host = "localhost";
$usuario = "root";
$password = "";
$basedatos = "TIENDA";

$conn = null;
$db_connected = false;

mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = @new mysqli($host, $usuario, $password, $basedatos);

    if ($conn->connect_error) {
        $db_connected = false;
        $conn = null;
    } else {
        $db_connected = true;
        $conn->set_charset("utf8");
    }
} catch (Throwable $e) {
    $db_connected = false;
    $conn = null;
}

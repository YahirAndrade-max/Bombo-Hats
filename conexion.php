<?php
// conexion.php
$host = "localhost";
$user = "root";
$pass = "";
$db = "bombolombo_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Fallo en la conexión de red interna: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
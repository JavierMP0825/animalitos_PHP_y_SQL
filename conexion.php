<?php
// Archivo: conexion.php

$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "adopcion";

$conn = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>

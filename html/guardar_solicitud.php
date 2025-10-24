<?php
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$usuario_id         = $_SESSION['usuario_id'];
$nombre_animal      = $conn->real_escape_string($_POST['nombreAnimal']);
$nombre_solicitante = $conn->real_escape_string($_POST['nombreSolicitante']);
$correo             = $conn->real_escape_string($_POST['correoSolicitante']);
$telefono           = $conn->real_escape_string($_POST['telefono']);
$motivo             = $conn->real_escape_string($_POST['motivo']);
$fecha              = date("Y-m-d H:i:s");
$estado             = "Pendiente"; // Valor inicial por defecto

// Insertar la solicitud en la tabla "solicitudes"
$sql = "INSERT INTO solicitudes 
        (usuario_id, nombre_animal, nombre_solicitante, correo, telefono, motivo, estado, fecha)
        VALUES 
        ('$usuario_id', '$nombre_animal', '$nombre_solicitante', '$correo', '$telefono', '$motivo', '$estado', '$fecha')";

if ($conn->query($sql) === TRUE) {
    // Redirigir a estado.php con mensaje de éxito
    header("Location: estado.php?exito=1");
    exit();
} else {
    echo "Error al guardar la solicitud: " . $conn->error;
}

$conn->close();
?>

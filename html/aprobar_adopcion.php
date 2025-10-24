<?php
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Verificar si recibimos los parámetros necesarios
if (!isset($_GET['id']) || !isset($_GET['estado'])) {
    header("Location: admin.php");
    exit();
}

$id_solicitud = intval($_GET['id']);
$nuevo_estado = $_GET['estado']; // Aprobada o Rechazada

// Validar estado
if ($nuevo_estado !== 'Aprobada' && $nuevo_estado !== 'Rechazada') {
    header("Location: admin.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Actualizar estado de la solicitud
$sql = "UPDATE solicitudes SET estado='$nuevo_estado' WHERE id=$id_solicitud";
if ($conn->query($sql) === TRUE) {
    // Redirigir de vuelta al panel con mensaje
    header("Location: admin.php?mensaje=" . urlencode("Solicitud actualizada a $nuevo_estado"));
    exit();
} else {
    echo "Error al actualizar la solicitud: " . $conn->error;
}

$conn->close();
?>

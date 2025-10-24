<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que se haya recibido el ID del usuario
if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$id = (int)$_GET['id'];

// Obtener datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Usuario no encontrado.");
}

$usuario = $result->fetch_assoc();

// Procesar confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1️⃣ Eliminar adopciones asociadas a este usuario
    $conn->query("DELETE FROM adopciones WHERE id_usuario = $id");

    // 2️⃣ Luego eliminar el usuario
    $sqlDelete = "DELETE FROM usuarios WHERE id = $id";
    if ($conn->query($sqlDelete) === TRUE) {
        // Redirigir al panel de admin con mensaje
        header("Location: admin.php?mensaje=usuario_eliminado");
        exit();
    } else {
        $error = "Error al eliminar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <div class="container">
            <a class="navbar-brand" href="admin.php">
                <i class="bi bi-house-heart-fill"></i> Animalitos sin Dueño A.C.
            </a>
        </div>
    </nav>
</header>

<div class="mt-5 pt-5">
    <h2 class="text-center text-danger mb-4">⚠️ Confirmar eliminación de usuario</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

    <div class="card shadow p-4">
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
        <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($usuario['ciudad']); ?></p>
        <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($usuario['telefono']); ?></p>

        <div class="alert alert-warning mt-4">
            Esta acción eliminará <strong>permanentemente</strong> al usuario y todas sus adopciones asociadas.
        </div>

        <form method="POST">
            <button type="submit" class="btn btn-danger w-100 mb-2">Eliminar Usuario</button>
            <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='admin.php'">Cancelar</button>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>

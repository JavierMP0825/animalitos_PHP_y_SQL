<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que se haya recibido el ID de la mascota
if (!isset($_GET['id'])) {
    die("ID de mascota no especificado.");
}

$id = (int)$_GET['id'];

// Obtener datos de la mascota
$sql = "SELECT * FROM mascotas WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Mascota no encontrada.");
}

$mascota = $result->fetch_assoc();

// Procesar confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1️⃣ Eliminar solicitudes relacionadas con esta mascota (si existen)
    $conn->query("DELETE FROM solicitudes WHERE nombre_animal = '" . $conn->real_escape_string($mascota['nombre']) . "'");

    // 2️⃣ Eliminar la foto del servidor (si existe)
    $fotoPath = realpath(__DIR__ . '/../img/' . $mascota['foto']);
    if ($fotoPath && file_exists($fotoPath)) {
        unlink($fotoPath); // Elimina la imagen del disco
    }

    // 3️⃣ Eliminar el registro de la mascota
    $sqlDelete = "DELETE FROM mascotas WHERE id = $id";
    if ($conn->query($sqlDelete) === TRUE) {
        header("Location: admin.php?mensaje=mascota_eliminada");
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
    <title>Eliminar Mascota</title>
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
    <h2 class="text-center text-danger mb-4">⚠️ Confirmar eliminación de mascota</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>

    <div class="card shadow p-4">
        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($mascota['tipo']); ?></p>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($mascota['nombre']); ?></p>
        <p><strong>Edad:</strong> <?php echo htmlspecialchars($mascota['edad']); ?></p>
        <p><strong>Raza:</strong> <?php echo htmlspecialchars($mascota['raza']); ?></p>
        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($mascota['descripcion']); ?></p>

        <div class="text-center mb-3">
            <img src="../img/<?php echo htmlspecialchars($mascota['foto']); ?>" alt="Foto de mascota" class="img-thumbnail" width="200">
        </div>

        <div class="alert alert-warning mt-3">
            Esta acción eliminará <strong>permanentemente</strong> a la mascota y cualquier solicitud de adopción asociada.
        </div>

        <form method="POST">
            <button type="submit" class="btn btn-danger w-100 mb-2">Eliminar Mascota</button>
            <button type="button" class="btn btn-secondary w-100" onclick="window.location.href='admin.php'">Cancelar</button>
        </form>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>

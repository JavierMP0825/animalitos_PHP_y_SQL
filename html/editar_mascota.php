<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

// Verificar ID de mascota
if (!isset($_GET['id'])) die("ID de mascota no especificado.");
$id = (int)$_GET['id'];

// Obtener datos actuales
$sql = "SELECT * FROM mascotas WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) die("Mascota no encontrada.");
$mascota = $result->fetch_assoc();

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $conn->real_escape_string($_POST["tipo"]);
    $nombre = $conn->real_escape_string($_POST["nombre"]);
    $edad = $conn->real_escape_string($_POST["edad"]);
    $raza = $conn->real_escape_string($_POST["raza"]);
    $descripcion = $conn->real_escape_string($_POST["descripcion"]);

    // Manejo de imagen
    $fotoNombre = $mascota['foto'];
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0) {
        $imgDir = __DIR__ . '/../img/';
        if (!is_dir($imgDir)) mkdir($imgDir, 0755, true);

        $fotoNombre = basename($_FILES["foto"]["name"]);
        $fotoDestino = $imgDir . $fotoNombre;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $fotoDestino);
    }

    // Actualizar DB
    $sqlUpdate = "UPDATE mascotas SET
                  tipo='$tipo',
                  nombre='$nombre',
                  edad='$edad',
                  raza='$raza',
                  descripcion='$descripcion',
                  foto='$fotoNombre'
                  WHERE id=$id";

    if ($conn->query($sqlUpdate) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Animalitos sin Dueño A.C.</a>
        </div>
    </nav>
</header>

<div class="mt-5 pt-5">
    <h1 class="text-center mb-4 text-success">Editar Mascota 🐾</h1>

    <?php if(isset($mensaje)) echo $mensaje; ?>

    <form method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <input type="text" name="tipo" class="form-control" value="<?php echo htmlspecialchars($mascota['tipo']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($mascota['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Edad</label>
            <input type="text" name="edad" class="form-control" value="<?php echo htmlspecialchars($mascota['edad']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Raza</label>
            <input type="text" name="raza" class="form-control" value="<?php echo htmlspecialchars($mascota['raza']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?php echo htmlspecialchars($mascota['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto actual</label><br>
            <img src="../img/<?php echo htmlspecialchars($mascota['foto']); ?>" alt="Foto mascota" class="img-thumbnail mb-2" width="150">
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success w-100">Actualizar Mascota</button>
        <br><br>
        <button type="button" class="btn btn-danger w-100" onclick="window.location.href='admin.php'">Cancelar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que se recibió el id del usuario
if (!isset($_GET['id'])) {
    die("ID de usuario no especificado.");
}

$id = (int)$_GET['id'];

// Obtener datos actuales del usuario
$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    die("Usuario no encontrado.");
}
$usuario = $result->fetch_assoc();

// Procesar formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $conn->real_escape_string($_POST['nombre']);
    $correo    = $conn->real_escape_string($_POST['correo']);
    $telefono  = $conn->real_escape_string($_POST['telefono']);
    $ciudad    = $conn->real_escape_string($_POST['ciudad']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $edad      = (int)$_POST['edad'];
    $genero    = $conn->real_escape_string($_POST['genero']);
    $rol       = $conn->real_escape_string($_POST['rol']);

    // Si se ingresó nueva contraseña, se hashea
    $passwordSql = "";
    if (!empty($_POST['password'])) {
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $passwordSql = ", password='$passwordHash'";
    }

    // Actualizar usuario
    $sqlUpdate = "UPDATE usuarios SET
                    nombre='$nombre',
                    correo='$correo',
                    telefono='$telefono',
                    ciudad='$ciudad',
                    direccion='$direccion',
                    edad=$edad,
                    genero='$genero',
                    rol='$rol'
                    $passwordSql
                  WHERE id=$id";

    if ($conn->query($sqlUpdate) === TRUE) {
        // Redirigir a admin.php después de actualizar
        header("Location: admin.php");
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
    <h1 class="text-center mb-4 text-success">Editar Usuario 👤</h1>

    <?php if(isset($mensaje)) echo $mensaje; ?>

    <form method="POST" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ciudad</label>
            <input type="text" name="ciudad" class="form-control" value="<?php echo htmlspecialchars($usuario['ciudad']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($usuario['direccion']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Edad</label>
            <input type="number" name="edad" class="form-control" value="<?php echo htmlspecialchars($usuario['edad']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Género</label>
            <select name="genero" class="form-select" required>
                <option value="Masculino" <?php if($usuario['genero']=='Masculino') echo 'selected'; ?>>Masculino</option>
                <option value="Femenino" <?php if($usuario['genero']=='Femenino') echo 'selected'; ?>>Femenino</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="usuario" <?php if($usuario['rol']=='usuario') echo 'selected'; ?>>Usuario</option>
                <option value="admin" <?php if($usuario['rol']=='admin') echo 'selected'; ?>>Administrador</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nueva Contraseña (opcional)</label>
            <input type="password" name="password" class="form-control" placeholder="Dejar vacío si no quieres cambiar">
        </div>
        <button type="submit" class="btn btn-success w-100">Actualizar Usuario</button>
        <br><br>
        <button type="button" class="btn btn-danger w-100" onclick="window.location.href='admin.php'">Cancelar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require '../conexion.php'; // Ajusta la ruta según tu estructura

$usuario_id = $_SESSION['usuario_id'];

// Obtener datos actuales del usuario
$sql = "SELECT nombre, correo, telefono, direccion FROM usuarios WHERE id = $usuario_id";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
} else {
    die("Usuario no encontrado.");
}

// Procesar actualización del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $conn->real_escape_string($_POST['nombre']);
    $telefono  = $conn->real_escape_string($_POST['telefono']);
    $direccion = $conn->real_escape_string($_POST['direccion']);

    $update = "UPDATE usuarios SET nombre='$nombre', telefono='$telefono', direccion='$direccion' WHERE id=$usuario_id";
    if ($conn->query($update) === TRUE) {
        $mensaje = "Perfil actualizado correctamente.";
        $_SESSION['usuario_nombre'] = $nombre; // Actualizar nombre en sesión
    } else {
        $error = "Error al actualizar el perfil: " . $conn->error;
    }

    // Refrescar datos
    $result = $conn->query($sql);
    $usuario = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi Perfil - Adopción de Animalitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
      <a class="navbar-brand" href="usuario.php"><i class="bi bi-person-circle"></i> Mi Perfil</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="usuario.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="estado.php">Mis solicitudes</a></li>
          <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Contenido -->
<main class="container mt-5 pt-4 flex-grow-1">
  <h2 class="text-success text-center mb-4">Editar Perfil</h2>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <?php
      if (isset($mensaje)) echo "<div class='alert alert-success'>$mensaje</div>";
      if (isset($error)) echo "<div class='alert alert-danger'>$error</div>";
      ?>
      <form action="perfil.php" method="POST" class="card p-4 shadow">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre Completo</label>
          <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="correo" class="form-label">Correo Electrónico</label>
          <input type="email" id="correo" class="form-control" value="<?php echo htmlspecialchars($usuario['correo']); ?>" disabled>
        </div>
        <div class="mb-3">
          <label for="telefono" class="form-label">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($usuario['telefono']); ?>" required>
        </div>
        <div class="mb-3">
          <label for="direccion" class="form-label">Dirección</label>
          <textarea id="direccion" name="direccion" class="form-control" rows="2" required><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
      </form>
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="bg-success text-white text-center py-3 mt-auto">
  <p>&copy; 2025 Instituto de Animalitos sin Dueño A.C.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

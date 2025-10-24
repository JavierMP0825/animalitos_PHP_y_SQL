<?php
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_id'])){
    header("Location: login.php");
    exit();
}

// Tomar el nombre del animal desde GET
$nombreAnimal = isset($_GET['animal']) ? $_GET['animal'] : '';
$nombreUsuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '';
$correoUsuario = isset($_SESSION['usuario_correo']) ? $_SESSION['usuario_correo'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Solicitud de Adopción - Animalitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
      <a class="navbar-brand" href="usuario.php"><i class="bi bi-house-heart-fill"></i> Animalitos sin Dueño A.C.</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="usuario.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="perfil.php">Mi perfil</a></li>
          <li class="nav-item"><a class="nav-link" href="estado.php">Mis solicitudes</a></li>
          <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Formulario -->
<main class="container py-5 mt-5 flex-grow-1">
  <h2 class="text-center text-success mb-4">Solicitud de Adopción 🐾</h2>
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-body">
          <form action="guardar_solicitud.php" method="POST">
            <div class="mb-3">
              <label for="nombreAnimal" class="form-label">Nombre del animal</label>
              <input type="text" id="nombreAnimal" name="nombreAnimal" class="form-control" value="<?php echo htmlspecialchars($nombreAnimal); ?>" readonly>
            </div>
            <div class="mb-3">
              <label for="nombreSolicitante" class="form-label">Tu nombre completo</label>
              <input type="text" id="nombreSolicitante" name="nombreSolicitante" class="form-control" value="<?php echo htmlspecialchars($nombreUsuario); ?>" required>
            </div>
            <div class="mb-3">
              <label for="correoSolicitante" class="form-label">Correo electrónico</label>
              <input type="email" id="correoSolicitante" name="correoSolicitante" class="form-control" value="<?php echo htmlspecialchars($correoUsuario); ?>" required>
            </div>
            <div class="mb-3">
              <label for="telefono" class="form-label">Teléfono de contacto</label>
              <input type="tel" id="telefono" name="telefono" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="motivo" class="form-label">Motivo de adopción</label>
              <textarea id="motivo" name="motivo" class="form-control" rows="3" required></textarea>
            </div>
            <input type="hidden" name="usuario_id" value="<?php echo $_SESSION['usuario_id']; ?>">
            <button type="submit" class="btn btn-success w-100">Enviar solicitud</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<footer class="bg-success text-white py-3 mt-auto">
  <div class="container text-center">
    <p>&copy; 2025 Instituto de Animalitos sin Dueño A.C.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

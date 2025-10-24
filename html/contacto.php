<?php
// Iniciar sesión si quieres usar datos de usuario (opcional)
// session_start();

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = $conn->real_escape_string($_POST['nombre']);
    $correo  = $conn->real_escape_string($_POST['correo']);
    $mensaje = $conn->real_escape_string($_POST['mensaje']);

    $sql = "INSERT INTO contactos (nombre, correo, mensaje) VALUES ('$nombre', '$correo', '$mensaje')";
    if ($conn->query($sql) === TRUE) {
        $alert = "¡Mensaje enviado con éxito!";
    } else {
        $alert = "Error al enviar el mensaje: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contacto - Adopción de Animalitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
      <a class="navbar-brand" href="../index.php">Animalitos sin Dueño A.C.</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="registro.php">Registrarse</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Hero -->
<section class="text-center py-5 bg-success text-white mt-5">
  <div class="container">
    <h1 class="display-4">Contáctanos 🐾</h1>
    <p class="lead">Estamos aquí para ayudarte con cualquier duda o sugerencia</p>
  </div>
</section>

<!-- Formulario de contacto -->
<main class="container py-5 flex-grow-1">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4>Formulario de Contacto</h4>
        </div>
        <div class="card-body">

          <?php if(isset($alert)) echo "<div class='alert alert-info'>$alert</div>"; ?>

          <form action="contacto.php" method="POST" id="contactoForm">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" id="nombre" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="correo" class="form-label">Correo Electrónico</label>
              <input type="email" id="correo" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="mensaje" class="form-label">Mensaje</label>
              <textarea id="mensaje" name="mensaje" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Enviar</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

<!-- Footer -->
<footer class="bg-success text-white py-4 mt-auto">
  <div class="container text-center">
    <p>&copy; 2025 Instituto de Animalitos sin Dueño A.C.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

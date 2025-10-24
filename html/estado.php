<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require '../conexion.php'; // Ajusta ruta según tu estructura
$usuario_id = $_SESSION['usuario_id'];

// Traer solicitudes del usuario
$sql = "SELECT * FROM solicitudes WHERE usuario_id = $usuario_id ORDER BY fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estado de Mis Solicitudes - Adopción de Animalitos</title>
  <!-- Bootstrap -->
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
          <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Hero -->
<section class="text-center py-5 bg-success text-white mt-5">
  <div class="container">
    <h1 class="display-5">📋 Estado de tus Solicitudes</h1>
    <p class="lead">Consulta el estado de tus adopciones en proceso</p>
  </div>
</section>

<!-- Contenido principal -->
<main class="container py-5 flex-grow-1">
  <div class="row">
    <?php
    if ($result->num_rows > 0) {
        while ($solicitud = $result->fetch_assoc()) {
            echo "
            <div class='col-md-6 mb-4'>
              <div class='card shadow'>
                <div class='card-body'>
                  <h5 class='card-title'>{$solicitud['nombre_animal']}</h5>
                  <p><strong>Solicitante:</strong> {$solicitud['nombre_solicitante']}</p>
                  <p><strong>Correo:</strong> {$solicitud['correo']}</p>
                  <p><strong>Teléfono:</strong> {$solicitud['telefono']}</p>
                  <p><strong>Motivo:</strong> {$solicitud['motivo']}</p>
                  <p><strong>Estado:</strong> <span class='badge ";
            switch ($solicitud['estado']) {
                case 'Aprobada': echo 'bg-success'; break;
                case 'Rechazada': echo 'bg-danger'; break;
                default: echo 'bg-warning text-dark'; break;
            }
            echo "'>{$solicitud['estado']}</span></p>
                  <p><small class='text-muted'>Fecha: {$solicitud['fecha']}</small></p>
                </div>
              </div>
            </div>
            ";
        }
    } else {
        echo "<p class='text-center'>No has realizado ninguna solicitud de adopción aún 🐾</p>";
    }
    ?>
  </div>
</main>

<!-- Footer -->
<footer class="bg-success text-white py-4 mt-auto">
  <div class="container text-center">
    <p class="mb-1">&copy; 2025 Instituto de Animalitos sin Dueño A.C. | Todos los derechos reservados.</p>
    <nav>
      <a href="contacto.html" class="text-white me-3">Contacto</a>
      <a href="faq.html" class="text-white me-3">FAQ</a>
    </nav>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

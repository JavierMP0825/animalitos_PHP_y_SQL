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

// Consulta de mascotas
$sql = "SELECT * FROM mascotas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuario - Adopción de Animalitos</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- CSS personalizado -->
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
      <a class="navbar-brand" href="usuario.php">
        <i class="bi bi-house-heart-fill"></i> Animalitos sin Dueño A.C.
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="usuario.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="perfil.php">Mi perfil</a></li>
          <li class="nav-item"><a class="nav-link" href="estado.php">Mis solicitudes</a></li>
          <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Hero -->
<section class="text-center py-5 bg-success text-white mt-5">
  <div class="container">
    <h1 class="display-4">Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?> 🐾</h1>
    <p class="lead">Aquí puedes solicitar la adopción de nuestros animalitos</p>
  </div>
</section>

<!-- Mascotas -->
<main class="container py-5 flex-grow-1" id="animales">
  <h2 class="text-center mb-4 text-success">Nuestros Animalitos</h2>
  <div class="row g-4">

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Redirige a adopcion.php con el nombre del animal
            $nombreAnimal = urlencode($row['nombre']);
            echo "
            <div class='col-12 col-sm-6 col-md-4 col-lg-3'>
              <div class='card h-100 text-center shadow'>
                <img src='../img/{$row['foto']}' class='card-img-top' alt='{$row['nombre']}'>
                <div class='card-body'>
                  <h5 class='card-title'>{$row['nombre']}</h5>
                  <p class='card-text'>{$row['tipo']}, {$row['edad']}</p>
                  <p class='card-text text-muted'>{$row['raza']}</p>
                  <p class='small'>{$row['descripcion']}</p>
                  <a href='adopcion.php?animal={$nombreAnimal}' class='btn btn-success w-100'>Adoptar</a>
                </div>
              </div>
            </div>
            ";
        }
    } else {
        echo "<p class='text-center'>No hay mascotas registradas por el momento 🐕🐈</p>";
    }
    ?>

  </div>
</main>

<!-- Footer -->
<footer class="bg-success text-white text-center py-3 mt-auto">
  <p class="mb-0">© 2025 Animalitos sin Dueño A.C. | Todos los derechos reservados</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>

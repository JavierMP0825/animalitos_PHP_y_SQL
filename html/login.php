<?php
// Iniciar sesión solo si no hay una activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../conexion.php'; // Ajusta la ruta si es necesario

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo   = $conn->real_escape_string($_POST['correo']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo='$correo'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            $_SESSION['usuario_correo'] = $usuario['correo'];

            // Redirigir según rol
            if ($usuario['rol'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: usuario.php");
            }
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Adopción de Animalitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>

  <!-- Navbar -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
      <div class="container">
        <a class="navbar-brand" href="../index.php">
          <i class="bi bi-house-heart-fill"></i> Animalitos sin Dueño A.C.
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link active" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="registro.php">Registrarse</a></li>
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

<body class="d-flex flex-column min-vh-100">

<main class="container py-5 flex-grow-1 mt-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow">
        <div class="card-header bg-success text-white text-center">
          <h4>Iniciar Sesión</h4>
        </div>
        <div class="card-body">
          <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
          <form action="login.php" method="POST">
            <div class="mb-3">
              <label for="correo" class="form-label">Correo Electrónico</label>
              <input type="email" id="correo" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Entrar</button>
          </form>
        </div>
        <div class="card-footer text-center">
          <small>¿No tienes cuenta? <a href="registro.php" class="text-success">Regístrate aquí</a></small>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

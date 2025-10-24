<?php
require '../conexion.php';

// Inicializar mensaje de error o éxito
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $conn->real_escape_string($_POST['nombre']);
    $correo    = $conn->real_escape_string($_POST['correo']);
    $telefono  = $conn->real_escape_string($_POST['telefono']);
    $ciudad    = $conn->real_escape_string($_POST['ciudad']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $edad      = (int)$_POST['edad'];
    $genero    = $conn->real_escape_string($_POST['genero']);
    $password  = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    // Validaciones
    if (empty($nombre) || empty($correo) || empty($password)) {
        $error = "Todos los campos obligatorios deben estar llenos.";
    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Hashear contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Verificar correo existente
        $check = $conn->query("SELECT id FROM usuarios WHERE correo='$correo'");
        if ($check->num_rows > 0) {
            $error = "Ya existe un usuario con ese correo.";
        } else {
            // Insertar usuario
            $sql = "INSERT INTO usuarios (nombre, correo, telefono, ciudad, direccion, edad, genero, password, rol)
                    VALUES ('$nombre', '$correo', '$telefono', '$ciudad', '$direccion', $edad, '$genero', '$passwordHash', 'usuario')";
            if ($conn->query($sql) === TRUE) {
                $success = "Registro exitoso. Puedes iniciar sesión <a href='login.php'>aquí</a>.";
            } else {
                $error = "Error al registrar: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registro - Adopción de Animalitos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

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
            <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

<div class="container py-5 mt-5">
  <h2 class="text-center mb-4 text-success">Registro de Usuario</h2>

  <?php if($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php elseif($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-3" id="registroForm">
    <div class="col-md-6">
      <label for="nombre" class="form-label">Nombre completo</label>
      <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="col-md-6">
      <label for="correo" class="form-label">Correo</label>
      <input type="email" class="form-control" id="correo" name="correo" required>
    </div>
    <div class="col-md-6">
      <label for="telefono" class="form-label">Teléfono</label>
      <input type="text" class="form-control" id="telefono" name="telefono" required>
    </div>
    <div class="col-md-6">
      <label for="ciudad" class="form-label">Ciudad</label>
      <input type="text" class="form-control" id="ciudad" name="ciudad" required>
    </div>
    <div class="col-md-6">
      <label for="direccion" class="form-label">Dirección</label>
      <input type="text" class="form-control" id="direccion" name="direccion" required>
    </div>
    <div class="col-md-3">
      <label for="edad" class="form-label">Edad</label>
      <input type="number" class="form-control" id="edad" name="edad" required>
    </div>
    <div class="col-md-3">
      <label for="genero" class="form-label">Género</label>
      <select class="form-select" id="genero" name="genero" required>
        <option value="">Selecciona</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>
    </div>
    <div class="col-md-6">
      <label for="password" class="form-label">Contraseña</label>
      <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="col-md-6">
      <label for="confirmar" class="form-label">Confirmar Contraseña</label>
      <input type="password" class="form-control" id="confirmar" name="confirmar" required>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-success w-100">Registrarse</button>
    </div>
  </form>
</div>

<script>
document.getElementById("registroForm").addEventListener("submit", function(e){
  const password = document.getElementById("password").value;
  const confirmar = document.getElementById("confirmar").value;
  if(password !== confirmar){
    e.preventDefault();
    alert("Las contraseñas no coinciden.");
  }
});
</script>

</body>
</html>

<?php $conn->close(); ?>

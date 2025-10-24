<?php
session_start();

// Verificar si el usuario está logueado y es administrador
if(!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin'){
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "adopcion");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultas a la BD
$animales = $conn->query("SELECT * FROM mascotas");
$usuarios = $conn->query("SELECT id, nombre, correo, telefono, direccion, rol FROM usuarios");
$adopciones = $conn->query("SELECT s.id, s.nombre_animal, s.nombre_solicitante, s.correo, s.telefono, s.motivo, s.estado
                            FROM solicitudes s
                            JOIN usuarios u ON s.usuario_id = u.id");
$contactos = $conn->query("SELECT * FROM contactos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración - Animalitos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="d-flex flex-column min-vh-100">

<header>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
      <a class="navbar-brand" href="admin.php">
        <i class="bi bi-speedometer2"></i> Panel de Administración
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="#animales">Animalitos</a></li>
          <li class="nav-item"><a class="nav-link" href="#usuarios">Usuarios</a></li>
          <li class="nav-item"><a class="nav-link" href="#adopciones">Adopciones</a></li>
          <li class="nav-item"><a class="nav-link" href="#contactos">Contactos</a></li>
          <li class="nav-item"><a class="nav-link" href="cerrar_sesion.php">Cerrar Sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<section class="text-center py-5 bg-success text-white mt-5">
  <div class="container">
    <h1 class="display-5">Bienvenido Administrador 🐾</h1>
    <p class="lead">Gestiona usuarios, adopciones y mensajes desde este panel</p>
  </div>
</section>

<!-- 🔔 Mensajes de confirmación -->
<?php
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
    $alerta = "";

    switch ($mensaje) {
        case 'mascota_agregada':
            $alerta = "<div class='alert alert-success text-center m-3'>🐶 Mascota agregada exitosamente.</div>";
            break;
        case 'mascota_actualizada':
            $alerta = "<div class='alert alert-info text-center m-3'>✏️ Mascota actualizada correctamente.</div>";
            break;
        case 'mascota_eliminada':
            $alerta = "<div class='alert alert-danger text-center m-3'>❌ Mascota eliminada permanentemente.</div>";
            break;
        case 'usuario_eliminado':
            $alerta = "<div class='alert alert-warning text-center m-3'>👤 Usuario eliminado correctamente.</div>";
            break;
        case 'usuario_actualizado':
            $alerta = "<div class='alert alert-info text-center m-3'>🧾 Usuario actualizado correctamente.</div>";
            break;
        case 'solicitud_aprobada':
            $alerta = "<div class='alert alert-success text-center m-3'>✅ Solicitud de adopción aprobada.</div>";
            break;
        case 'solicitud_rechazada':
            $alerta = "<div class='alert alert-danger text-center m-3'>🚫 Solicitud de adopción rechazada.</div>";
            break;
    }

    echo $alerta;
}
?>

<main class="container py-5 flex-grow-1">

  <!-- Gestión de Animalitos -->
  <section id="animales" class="mb-5">
    <h2 class="text-success mb-3">Gestión de Animalitos</h2>
    <a href="agregar_mascota.php" class="btn btn-success mb-3">Agregar Mascota</a>
    <div class="row g-4">
      <?php if($animales->num_rows > 0): ?>
        <?php while($a = $animales->fetch_assoc()): ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 text-center shadow">
              <img src="../img/<?php echo $a['foto']; ?>" class="card-img-top" alt="<?php echo $a['nombre']; ?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo $a['nombre']; ?></h5>
                <p class="card-text"><?php echo $a['tipo'] . ', ' . $a['edad']; ?></p>
                <p class="card-text text-muted"><?php echo $a['raza']; ?></p>
                <a href="editar_mascota.php?id=<?php echo $a['id']; ?>" class="btn btn-secondary btn-sm me-2">Editar</a>
                <a href="eliminar_mascota.php?id=<?php echo $a['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas dar de baja esta mascota?')">Dar de baja</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No hay mascotas registradas.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Gestión de Usuarios -->
  <section id="usuarios" class="mb-5">
    <h2 class="text-success mb-3">Gestión de Usuarios</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-success">
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if($usuarios->num_rows > 0): ?>
            <?php while($u = $usuarios->fetch_assoc()): ?>
              <tr>
                <td><?php echo $u['nombre']; ?></td>
                <td><?php echo $u['correo']; ?></td>
                <td><?php echo $u['telefono']; ?></td>
                <td><?php echo $u['direccion']; ?></td>
                <td><?php echo $u['rol']; ?></td>
                <td>
                  <a href="editar_usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-secondary btn-sm me-2">Editar</a>
                  <a href="eliminar_usuario.php?id=<?php echo $u['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar este usuario?')">Eliminar</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">No hay usuarios registrados</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Gestión de Adopciones -->
  <section id="adopciones" class="mb-5">
    <h2 class="text-success mb-3">Gestión de Adopciones</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-success">
          <tr>
            <th>Animal</th>
            <th>Solicitante</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Motivo</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if($adopciones->num_rows > 0): ?>
            <?php while($s = $adopciones->fetch_assoc()): ?>
              <tr>
                <td><?php echo $s['nombre_animal']; ?></td>
                <td><?php echo $s['nombre_solicitante']; ?></td>
                <td><?php echo $s['correo']; ?></td>
                <td><?php echo $s['telefono']; ?></td>
                <td><?php echo $s['motivo']; ?></td>
                <td><?php echo $s['estado']; ?></td>
                <td>
                  <?php if($s['estado'] === 'Pendiente'): ?>
                    <a href="aprobar_adopcion.php?id=<?php echo $s['id']; ?>&estado=Aprobada" class="btn btn-success btn-sm me-2">Aprobar</a>
                    <a href="aprobar_adopcion.php?id=<?php echo $s['id']; ?>&estado=Rechazada" class="btn btn-danger btn-sm">Rechazar</a>
                  <?php else: ?>
                    <span class="badge bg-secondary"><?php echo $s['estado']; ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center">No hay solicitudes</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Mensajes de Contacto -->
  <section id="contactos" class="mb-5">
    <h2 class="text-success mb-3">Mensajes de Contacto</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="table-success">
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Mensaje</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          <?php if($contactos->num_rows > 0): ?>
            <?php while($c = $contactos->fetch_assoc()): ?>
              <tr>
                <td><?php echo $c['nombre']; ?></td>
                <td><?php echo $c['correo']; ?></td>
                <td><?php echo $c['mensaje']; ?></td>
                <td><?php echo $c['fecha']; ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay mensajes</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

</main>

<footer class="bg-success text-white py-4 mt-auto">
  <div class="container text-center">
    <p class="mb-1">&copy; 2025 Instituto de Animalitos sin Dueño A.C. | Todos los derechos reservados.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Ocultar alertas después de 4 segundos
  setTimeout(() => {
    const alerta = document.querySelector('.alert');
    if (alerta) alerta.remove();
  }, 4000);
</script>

</body>
</html>

<?php $conn->close(); ?>

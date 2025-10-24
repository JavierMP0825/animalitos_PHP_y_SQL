document.addEventListener("DOMContentLoaded", () => {
  const cerrarSesionBtn = document.getElementById("cerrarSesion");
  const form = document.getElementById("perfilForm");

  let usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
  if (!usuarioActivo) {
    alert("Debes iniciar sesión primero.");
    window.location.href = "../index.html";
    return;
  }

  // Rellenar datos del usuario
  document.getElementById("nombre").value = usuarioActivo.nombre;
  document.getElementById("correo").value = usuarioActivo.correo;
  document.getElementById("telefono").value = usuarioActivo.telefono;
  document.getElementById("direccion").value = usuarioActivo.direccion;

  // Guardar cambios
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    usuarioActivo.nombre = document.getElementById("nombre").value;
    usuarioActivo.telefono = document.getElementById("telefono").value;
    usuarioActivo.direccion = document.getElementById("direccion").value;

    // Actualizar en usuarios
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    usuarios = usuarios.map(u => u.correo === usuarioActivo.correo ? usuarioActivo : u);
    localStorage.setItem("usuarios", JSON.stringify(usuarios));

    // Actualizar usuario activo
    localStorage.setItem("usuarioActivo", JSON.stringify(usuarioActivo));

    alert("Perfil actualizado con éxito.");
  });

  // Cerrar sesión
  if (cerrarSesionBtn) {
    cerrarSesionBtn.addEventListener("click", () => {
      localStorage.removeItem("usuarioActivo");
      window.location.href = "../index.html";
    });
  }
});

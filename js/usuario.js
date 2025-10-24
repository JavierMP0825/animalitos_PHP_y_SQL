document.addEventListener("DOMContentLoaded", () => {
  const cerrarSesionBtn = document.getElementById("cerrarSesion");
  const botonesAdoptar = document.querySelectorAll(".adoptar");

  // --- Cerrar sesión ---
  if (cerrarSesionBtn) {
    cerrarSesionBtn.addEventListener("click", (e) => {
      e.preventDefault();
      localStorage.removeItem("usuarioActivo"); // eliminar sesión
      window.location.href = "../index.html"; // volver a inicio
    });
  }

  // --- Adoptar ---
  if (botonesAdoptar.length > 0) {
    botonesAdoptar.forEach((boton) => {
      boton.addEventListener("click", () => {
        const nombre = boton.dataset.nombre;
        localStorage.setItem("animalSeleccionado", nombre);
        window.location.href = "adopcion.html";
      });
    });
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formAdopcion");
  const nombreAnimalInput = document.getElementById("nombreAnimal");

  // Recuperar animal desde localStorage (si viene de "Adoptar")
  const animalSeleccionado = localStorage.getItem("animalSeleccionado");
  if (animalSeleccionado) {
    nombreAnimalInput.value = animalSeleccionado;
    localStorage.removeItem("animalSeleccionado"); // limpiar para futuras visitas
  }

  // Autocompletar datos del usuario logueado
  const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));
  if (usuarioActivo) {
    document.getElementById("nombreSolicitante").value = usuarioActivo.nombre;
    document.getElementById("correoSolicitante").value = usuarioActivo.correo;
  }

  // Guardar solicitud
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const solicitud = {
      usuario: usuarioActivo ? usuarioActivo.correo : null, // <-- clave para estado.js
      animal: nombreAnimalInput.value,
      nombre: document.getElementById("nombreSolicitante").value,
      correo: document.getElementById("correoSolicitante").value,
      telefono: document.getElementById("telefono").value,
      motivo: document.getElementById("motivo").value,
      estado: "Pendiente"
    };

    let solicitudes = JSON.parse(localStorage.getItem("solicitudes")) || [];
    solicitudes.push(solicitud);
    localStorage.setItem("solicitudes", JSON.stringify(solicitudes));

    alert("¡Solicitud enviada con éxito! 🐾");
    form.reset();
    window.location.href = "estado.html";
  });
});

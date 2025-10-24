document.addEventListener("DOMContentLoaded", () => {
  const botones = document.querySelectorAll(".adoptar");
  const modal = new bootstrap.Modal(document.getElementById("registroModal"));
  const animalNombre = document.getElementById("animalNombre");

  botones.forEach(boton => {
    boton.addEventListener("click", () => {
      const nombre = boton.dataset.nombre;
      const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));

      if (!usuarioActivo) {
        // No hay sesión → mostrar modal de registro/login
        animalNombre.textContent = nombre;
        modal.show();
      } else {
        // Hay sesión → guardar adopción
        let adopciones = JSON.parse(localStorage.getItem("adopciones")) || [];
        adopciones.push({ usuario: usuarioActivo.correo, animal: nombre, fecha: new Date().toISOString() });
        localStorage.setItem("adopciones", JSON.stringify(adopciones));
        alert(`¡Felicidades, ${usuarioActivo.nombre}! Has adoptado a ${nombre}.`);
      }
    });
  });
});

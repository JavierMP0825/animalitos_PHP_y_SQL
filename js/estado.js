document.addEventListener("DOMContentLoaded", () => {
  const contenedor = document.getElementById("listaSolicitudes");
  const usuarioActivo = JSON.parse(localStorage.getItem("usuarioActivo"));

  if (!usuarioActivo) {
    contenedor.innerHTML = "<p class='text-danger'>Debes iniciar sesión para ver tus solicitudes.</p>";
    return;
  }

  let solicitudes = JSON.parse(localStorage.getItem("solicitudes")) || [];
  let solicitudesUsuario = solicitudes.filter(s => s.usuario === usuarioActivo.correo);

  if (solicitudesUsuario.length === 0) {
    contenedor.innerHTML = "<p class='text-muted'>No tienes solicitudes registradas.</p>";
    return;
  }

  // Mostrar todas las solicitudes
  solicitudesUsuario.forEach((s, i) => {
    const card = document.createElement("div");
    card.className = "card shadow-sm mb-3";
    card.innerHTML = `
      <div class="card-body">
        <h5 class="card-title">🐶 Solicitud #${i + 1}</h5>
        <p><strong>Animal:</strong> ${s.animal}</p>
        <p><strong>Nombre solicitante:</strong> ${s.nombre}</p>
        <p><strong>Correo:</strong> ${s.correo}</p>
        <p><strong>Teléfono:</strong> ${s.telefono}</p>
        <p><strong>Motivo:</strong> ${s.motivo}</p>
        <p class="fw-bold">Estado: 
          <span class="badge ${s.estado === "Pendiente" ? "bg-warning text-dark" : s.estado === "Aprobado" ? "bg-success" : "bg-danger"}">
            ${s.estado}
          </span>
        </p>
      </div>
    `;
    contenedor.appendChild(card);
  });
});

document.addEventListener("DOMContentLoaded", () => {

  // Cerrar sesión
  const cerrarBtn = document.getElementById("cerrarSesion");
  cerrarBtn.addEventListener("click", () => {
    localStorage.removeItem("usuarioActivo");
    window.location.href = "../index.html";
  });

  // --- Gestión de Usuarios ---
  function cargarUsuarios() {
    const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    const tbody = document.querySelector("#tablaUsuarios tbody");
    tbody.innerHTML = "";

    usuarios.forEach((u, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${u.nombre}</td>
        <td>${u.correo}</td>
        <td>${u.telefono}</td>
        <td>${u.direccion}</td>
        <td>${u.rol}</td>
        <td>${u.bloqueado ? "Bloqueado" : u.rol !== "admin" ? `<button class="btn btn-danger btn-sm" data-index="${index}">Bloquear</button>` : "-"}</td>
      `;
      tbody.appendChild(tr);
    });

    // Eventos de bloqueo
    tbody.querySelectorAll("button").forEach(btn => {
      btn.addEventListener("click", () => {
        const idx = btn.dataset.index;
        usuarios[idx].bloqueado = true;
        localStorage.setItem("usuarios", JSON.stringify(usuarios));
        alert(`${usuarios[idx].nombre} ha sido bloqueado.`);
        cargarUsuarios();
      });
    });
  }

  // --- Gestión de Adopciones ---
  function cargarSolicitudes() {
    const solicitudes = JSON.parse(localStorage.getItem("solicitudes")) || [];
    const tbody = document.querySelector("#tablaAdopciones tbody");
    tbody.innerHTML = "";

    solicitudes.forEach((s, index) => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${s.animal}</td>
        <td>${s.nombre}</td>
        <td>${s.correo}</td>
        <td>${s.telefono}</td>
        <td>${s.motivo}</td>
        <td><span class="badge ${s.estado === "Aprobado" ? "bg-success" : s.estado === "Rechazado" ? "bg-danger" : "bg-warning"}">${s.estado}</span></td>
        <td>
          ${s.estado === "Pendiente" ? `
            <button class="btn btn-success btn-sm aprobar" data-index="${index}">Aprobar</button>
            <button class="btn btn-danger btn-sm rechazar" data-index="${index}">Rechazar</button>
          ` : ""}
        </td>
      `;
      tbody.appendChild(tr);
    });

    // Eventos aprobar/rechazar
    tbody.querySelectorAll(".aprobar").forEach(btn => {
      btn.addEventListener("click", () => {
        const idx = btn.dataset.index;
        solicitudes[idx].estado = "Aprobado";
        localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
        cargarSolicitudes();
        alert("Solicitud aprobada.");
      });
    });

    tbody.querySelectorAll(".rechazar").forEach(btn => {
      btn.addEventListener("click", () => {
        const idx = btn.dataset.index;
        solicitudes[idx].estado = "Rechazado";
        localStorage.setItem("solicitudes", JSON.stringify(solicitudes));
        cargarSolicitudes();
        alert("Solicitud rechazada.");
      });
    });
  }

  // --- Gestión de Mensajes de Contacto ---
  function cargarContactos() {
    const contactos = JSON.parse(localStorage.getItem("contactos")) || [];
    let tabla = document.querySelector("#tablaContactos");
    if (!tabla) {
      // Crear tabla si no existe
      const section = document.createElement("section");
      section.classList.add("mt-5");
      section.innerHTML = `
        <h2 class="text-success mb-3">Mensajes de Contacto</h2>
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="tablaContactos">
            <thead class="table-success">
              <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Mensaje</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      `;
      document.querySelector("main").appendChild(section);
      tabla = section.querySelector("#tablaContactos");
    }

    const tbody = tabla.querySelector("tbody");
    tbody.innerHTML = "";
    contactos.forEach(c => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${c.nombre}</td>
        <td>${c.correo}</td>
        <td>${c.mensaje}</td>
        <td>${c.fecha}</td>
      `;
      tbody.appendChild(tr);
    });
  }

  // Inicializar
  cargarUsuarios();
  cargarSolicitudes();
  cargarContactos();
});

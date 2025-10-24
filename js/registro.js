document.addEventListener("DOMContentLoaded", () => {
  // Inicializar usuarios demo si no existen
  if (!localStorage.getItem("usuarios")) {
    const usuariosDemo = [
      { nombre: "Cliente Demo", correo: "usuario@demo.com", telefono: "5512345678", direccion: "CDMX", password: "1234", rol: "usuario" },
      { nombre: "Admin", correo: "admin@demo.com", telefono: "5598765432", direccion: "CDMX", password: "admin", rol: "admin" }
    ];
    localStorage.setItem("usuarios", JSON.stringify(usuariosDemo));
  }

  const form = document.getElementById("registroForm");

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const nombre = document.getElementById("nombre").value.trim();
      const correo = document.getElementById("correo").value.trim();
      const telefono = document.getElementById("telefono").value.trim();
      const direccion = document.getElementById("direccion").value.trim();
      const password = document.getElementById("password").value;
      const confirmar = document.getElementById("confirmar").value;

      // Validaciones
      if (!/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(correo)) {
        alert("Correo no válido.");
        return;
      }

      if (!/^\d{10,}$/.test(telefono)) {
        alert("El teléfono debe tener al menos 10 dígitos numéricos.");
        return;
      }

      if (direccion.length < 5) {
        alert("La dirección es obligatoria.");
        return;
      }

      if (password !== confirmar) {
        alert("Las contraseñas no coinciden.");
        return;
      }

      // Guardar usuario en localStorage
      const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

      // Revisar si ya existe el correo
      const usuarioExistente = usuarios.find(u => u.correo === correo);
      if (usuarioExistente) {
        if (usuarioExistente.rol === "bloqueado") {
          alert("No se puede registrar: la cuenta con este correo ha sido bloqueada.");
          return;
        } else {
          alert("Ya existe un usuario con ese correo.");
          return;
        }
      }

      usuarios.push({ nombre, correo, telefono, direccion, password, rol: "usuario" });
      localStorage.setItem("usuarios", JSON.stringify(usuarios));

      alert("Registro exitoso. Ahora puedes iniciar sesión.");
      window.location.href = "login.html"; // Ajusta la ruta si está en carpeta
    });
  }
});

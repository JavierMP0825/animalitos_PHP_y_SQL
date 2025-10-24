document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("loginForm");

  if (form) {
    form.addEventListener("submit", (e) => {
      e.preventDefault();

      const correo = document.getElementById("correo").value.trim();
      const password = document.getElementById("password").value;

      let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

      // Buscar usuario por correo
      const usuario = usuarios.find(u => u.correo === correo);

      if (!usuario) {
        alert("Correo o contraseña incorrectos.");
        return;
      }

      if (usuario.rol === "bloqueado") {
        alert("Cuenta bloqueada. Contacta al administrador.");
        return;
      }

      // Verificar contraseña
      if (usuario.password !== password) {
        alert("Correo o contraseña incorrectos.");
        return;
      }

      // Guardamos el usuario activo en localStorage
      localStorage.setItem("usuarioActivo", JSON.stringify(usuario));

      // Redirigir según el rol
      if (usuario.rol === "admin") {
        alert("Bienvenido Administrador");
        window.location.href = "admin.html"; // Ajusta ruta
      } else {
        alert("Bienvenido Usuario");
        window.location.href = "usuario.html"; // Ajusta ruta
      }
    });
  }
});

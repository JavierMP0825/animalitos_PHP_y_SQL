iniciar el servidor locar para php
php -S localhost:3000
aqui se mostraran los cambios realizados en el proyecto


¿quieres reiniciar la base de datos?
ejecuata el siguiente codigo sql en phpMyAdmin

-- ELIMINAR BASE DE DATOS EXISTENTE
DROP DATABASE IF EXISTS adopcion;

-- CREAR NUEVA BASE DE DATOS
CREATE DATABASE adopcion;
USE adopcion;

-- TABLA mascotas
CREATE TABLE mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    nombre VARCHAR(50),
    edad VARCHAR(50),
    raza VARCHAR(50),
    descripcion TEXT,
    foto VARCHAR(100)
);

-- INSERTAR DATOS DE MASCOTAS
INSERT INTO mascotas (id, tipo, nombre, edad, raza, descripcion, foto) VALUES
(1, 'Perro', 'Max', '6 meses', 'Labrador', 'Cachorro juguetón y amigable.', 'perro1.jpg'),
(2, 'Gato', 'Michi', '2 años', 'Mestizo', 'Tranquilo y cariñoso.', 'gato1.jpg'),
(3, 'Perro', 'Rocky', '1 año', 'Mestizo', 'Muy activo y leal.', 'perro2.jpg'),
(4, 'Gato', 'Luna', '4 meses', 'Naranjoso', 'Gatita juguetona.', 'gato2.jpg');

-- TABLA usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    telefono VARCHAR(20),
    ciudad VARCHAR(50),
    correo VARCHAR(100),
    edad INT,
    genero VARCHAR(20),
    direccion VARCHAR(150),
    password VARCHAR(255),
    rol ENUM('admin', 'usuario') NOT NULL
);

-- INSERTAR USUARIOS CON CONTRASEÑAS CIFRADAS
INSERT INTO usuarios (id, nombre, telefono, ciudad, correo, edad, genero, direccion, password, rol) VALUES
(1, 'Admin Uno', '1234567890', 'Ciudad1', 'admin1@mail.com', 30, 'Masculino', 'Calle Admin 1', '$2y$10$glpRiWWOakykICSlAZ248OMsbbzN0qDzDblb0cWBiwleWqUjF0UU.', 'admin'),
(2, 'Admin Dos', '0987654321', 'Ciudad2', 'admin2@mail.com', 28, 'Femenino', 'Calle Admin 2', '$2y$10$rkjjYHq7QR16xOgcsQ4mE.vTUlv5Ee3rEzAweS7nPBBBgR9wpBi8O', 'admin'),
(3, 'Usuario Uno', '111222333', 'Ciudad3', 'user1@mail.com', 25, 'Masculino', 'Calle User 1', '$2y$10$wVw2UHzGGbKZ2C08B14Vk.HoKAe59ICcTi9111sacdwb/DTyEmEyS', 'usuario'),
(4, 'Usuario Dos', '444555666', 'Ciudad4', 'user2@mail.com', 22, 'Femenino', 'Calle User 2', '$2y$10$vB34jPY1r0kLkHG.xihUY.fqDTTYnlI3jGGaCPvlrIdfsed3BYuY2', 'usuario');

-- TABLA solicitudes
CREATE TABLE solicitudes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre_animal VARCHAR(50),
    nombre_solicitante VARCHAR(100),
    correo VARCHAR(100),
    telefono VARCHAR(20),
    motivo TEXT,
    estado ENUM('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- INSERTAR SOLICITUDES
INSERT INTO solicitudes (id, usuario_id, nombre_animal, nombre_solicitante, correo, telefono, motivo, estado, fecha) VALUES
(1, 3, 'Max', 'Usuario Uno', 'user1@mail.com', '2467535815', 'Quiero un cachorro', 'Pendiente', '2025-10-22 07:53:13'),
(2, 4, 'Michi', 'Usuario Dos', 'user2@mail.com', '2225491034', 'Quiero un gato naranja', 'Pendiente', '2025-10-23 17:54:01');

-- TABLA contactos (vacía)
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100),
    mensaje TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

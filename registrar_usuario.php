<?php
   // Conexión a la base de datos
   $conexion = new mysqli("localhost", "isverse31", "isverse31", "tienda");

   // Verificar la conexión
   if ($conexion->connect_error) {
       die("Error de conexión: " . $conexion->connect_error);
   }

   // Recibir los datos del formulario
   $email = $_POST['new-email'];
   $password = password_hash($_POST['new-password'], PASSWORD_DEFAULT); // Encriptar la contraseña

   // Insertar el nuevo usuario en la base de datos
   $sql = "INSERT INTO usuarios (email, password) VALUES ('$email', '$password')";

   if ($conexion->query($sql) === TRUE) {
       echo "Usuario registrado correctamente";
   } else {
       echo "Error al registrar el usuario: " . $conexion->error;
   }

   $conexion->close();
   ?>
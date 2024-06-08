<?php
// Conexión a la base de datos (reemplaza los valores con los de tu base de datos)
$servername = "localhost";
$username = "isverse31";
$password = "isverse31";
$dbname = "tienda";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los valores del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Encriptar la contraseña con el algoritmo de hashing bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];
        $user_id = $row['id'];

        // Verificar si la contraseña proporcionada coincide con la contraseña almacenada
        if (password_verify($password, $stored_password)) {
            if ($user_id == 1) {
                // Usuario administrador
                header("Location: admin.html");
                exit();
            } else {
                // Usuario no es administrador, almacenar el email en el sessionStorage
                session_start();
                $_SESSION['email'] = $email;
                header("Location: index.html");
                exit();
            }
        } else {
            // Credenciales inválidas, mostrar un mensaje de error
            echo "Usuario o contraseña incorrectos. Por favor, inténtalo de nuevo.";
        }
    } else {
        // Usuario no encontrado, mostrar un mensaje de error
        echo "Usuario no encontrado. Por favor, regístrate antes de iniciar sesión.";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
<?php
header('Content-Type: application/json');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $imagen_url = $_POST['imagen_url']; // URL de la imagen

    // Conectar a la base de datos
    $conexion = new mysqli('localhost', 'isverse31', 'isverse31', 'tienda');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para insertar una nueva prenda en la tabla 'ropa'
    $sql = "INSERT INTO ropa (nombre, precio, imagen_url) VALUES (?, ?, ?)";
    
    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sds", $nombre, $precio, $imagen_url);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(array('mensaje' => 'Prenda agregada correctamente', 'imagen' => $imagen_url));
    } else {
        echo json_encode(array('error' => 'Error al agregar la prenda'));
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo json_encode(array('error' => 'Método no permitido'));
}
?>
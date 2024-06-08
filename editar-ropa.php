<?php
header('Content-Type: application/json');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];

    // Conectar a la base de datos (ajusta los parámetros según tu configuración)
    $conexion = new mysqli('localhost', 'isverse31', 'isverse31', 'tienda');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para actualizar la prenda en la tabla 'ropa'
    $sql = "UPDATE ropa SET nombre=?, precio=?";

    // Verificar si se ha enviado una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_temp = $_FILES['imagen']['tmp_name'];
        $ruta_imagen = 'img/' . $imagen_nombre; // Ruta donde se guardará la imagen

        move_uploaded_file($imagen_temp, $ruta_imagen); // Mover la imagen a la ubicación permanente

        $sql .= ", imagen_url='$ruta_imagen'"; // Agregar la actualización de la imagen a la consulta SQL
    }

    $sql .= " WHERE id=?"; // Agregar la condición para actualizar la prenda específica

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sds", $nombre, $precio, $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(array('mensaje' => 'Prenda actualizada correctamente'));
    } else {
        echo json_encode(array('error' => 'Error al actualizar la prenda'));
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo json_encode(array('error' => 'Método no permitido'));
}
?>
<?php
header('Content-Type: application/json');

// Verificar si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener el ID de la prenda a eliminar
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;

    // Conectar a la base de datos (ajusta los parámetros según tu configuración)
    $conexion = new mysqli('localhost', 'isverse31', 'isverse31', 'tienda');

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error en la conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para eliminar la prenda de la tabla 'ropa'
    $sql = "DELETE FROM ropa WHERE id=?";

    // Preparar la consulta
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(array('mensaje' => 'Prenda eliminada correctamente'));
    } else {
        echo json_encode(array('error' => 'Error al eliminar la prenda'));
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    echo json_encode(array('error' => 'Método no permitido'));
}
?>
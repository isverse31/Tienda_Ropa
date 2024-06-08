<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$host = 'localhost';
$dbname = 'tienda';
$username = 'isverse31';
$password = 'isverse31';

try {
    // Crear una nueva conexión PDO
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta SQL para obtener todas las prendas de la tabla 'ropa'
    $sql = "SELECT id, nombre, precio, imagen_url FROM ropa";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    // Obtener todos los resultados como un arreglo asociativo
    $ropa = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($ropa) {
        // Devolver los datos en formato JSON
        http_response_code(200); // Código de éxito
        echo json_encode($ropa);
    } else {
        http_response_code(404); // No se encontraron prendas
        echo json_encode(array('mensaje' => 'No se encontraron prendas de ropa'));
    }
} catch (PDOException $e) {
    // Manejo de errores de conexión y consulta
    http_response_code(500); // Error del servidor
    echo json_encode(array('error' => 'Error en la conexión o consulta.'));
    // Log detallado del error solo en el servidor para seguridad
    error_log($e->getMessage());
}
?>
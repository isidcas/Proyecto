<?php
// Desactivamos errores en pantalla para que no ensucien el JSON si algo falla
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

// Cabeceras obligatorias para Angular
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

use App\Models\History;

// 1. Capturamos el usuario_id de la URL
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 1;

try {
    // 2. Llamamos al modelo (el que usa PDO)
    $datos = History::getByUser($usuario_id);
    
    // 3. Si no hay datos, devolvemos un array vacío [] en vez de un error
    if (!$datos) {
        echo json_encode([]);
    } else {
        echo json_encode($datos);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error en la consulta",
        "mensaje" => $e->getMessage()
    ]);
}
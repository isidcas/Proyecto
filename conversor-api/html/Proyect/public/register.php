<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

// CONFIGURACIÓN DOCKER
$host = "conversor-api-db-1"; 
$dbname = "conversor_divisas";
$user = "root";
$pass = "root"; // <--- PRUEBA CON 'root' O MIRA TU DOCKER-COMPOSE.YML

try {
    $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8";
    $db = new PDO($dsn, $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Si vuelve a fallar, este mensaje nos dirá si ahora sí intentó usar password
    echo json_encode(["error" => "Error de acceso: " . $e->getMessage()]);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data && !empty($data['email'])) {
    try {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$data['nombre'], $data['email'], $hash]);
        
        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error al guardar: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
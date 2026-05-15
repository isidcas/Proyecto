<?php
require_once __DIR__ . '/../vendor/autoload.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

use App\Database\Database;

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$data['email']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificamos password contra el hash de la BD
    if ($user && password_verify($data['password'], $user['password'])) {
        unset($user['password']); // Quitamos la pass antes de enviar al front
        echo json_encode(["user" => $user]);
    } else {
        echo json_encode(["error" => "No autorizado"]);
    }
}
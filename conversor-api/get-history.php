<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

if (isset($_GET['usuario_id']) && !empty($_GET['usuario_id'])) {
    $usuario_id = intval($_GET['usuario_id']);

    try {
        $db = new PDO("mysql:host=conversor-api-db-1;dbname=conversor_divisas;charset=utf8", "root", "root");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare("SELECT fecha, cantidad, moneda_origen, resultado, moneda_destino FROM historial WHERE usuario_id = ? ORDER BY fecha DESC LIMIT 30");
        $stmt->execute([$usuario_id]);
        
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows ? $rows : []);
        exit;

    } catch(PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]); exit;
    }
} else {
    echo json_encode(["error" => "Falta el parámetro usuario_id"]); exit;
}
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

// Capturamos los datos que vienen de Angular
$method = $_SERVER['REQUEST_METHOD'];

// Si Angular pide la lista de monedas (GET)
if ($method === 'GET' && isset($_GET['action']) && $_GET['action'] === 'currencies') {
    echo file_get_contents('https://api.frankfurter.app/currencies');
    exit;
}

// Si Angular pide una conversión (POST)
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $amount = $data['amount'];
    $from = $data['from'];
    $to = $data['to'];

    $url = "https://api.frankfurter.app/latest?amount=$amount&from=$from&to=$to";
    $response = file_get_contents($url);
    
    // Opcional: Aquí podrías conectar a tu BD Docker conversor-api-db-1
    // para guardar un log de la conversión.

    echo $response;
} else {
    echo json_encode(["error" => "No se recibieron parámetros"]);
}
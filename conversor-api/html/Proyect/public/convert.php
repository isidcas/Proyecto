<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Leer JSON
$json = file_get_contents("php://input");

$data = json_decode($json);

// Validar JSON
if (!$data) {
    echo json_encode([
        "error" => "JSON inválido o vacío"
    ]);
    exit;
}

// Validar campos
if (
    !isset($data->origen) ||
    !isset($data->destino) ||
    !isset($data->cantidad)
) {
    echo json_encode([
        "error" => "Faltan datos"
    ]);
    exit;
}

$origen = strtoupper($data->origen);
$destino = strtoupper($data->destino);
$cantidad = floatval($data->cantidad);

// API GRATIS Y FUNCIONAL
$url = "https://api.frankfurter.app/latest?from=$origen&to=$destino";

// Consumir API
$response = file_get_contents($url);

// Verificar conexión
if ($response === false) {
    echo json_encode([
        "error" => "Error al conectar con la API"
    ]);
    exit;
}

// Convertir JSON
$datos = json_decode($response, true);

// Verificar tasa
if (!isset($datos['rates'][$destino])) {
    echo json_encode([
        "error" => "Moneda no encontrada"
    ]);
    exit;
}

$tasa = $datos['rates'][$destino];

$resultado = $cantidad * $tasa;

// Respuesta
echo json_encode([
    "resultado" => round($resultado, 2)
]);

?>
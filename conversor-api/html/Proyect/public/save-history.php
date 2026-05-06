<?php

require_once '../vendor/autoload.php';

use App\Services\HistoryService;

$data = json_decode(
    file_get_contents("php://input")
);

$service = new HistoryService();

$result = $service->save(
    $data->usuario_id,
    $data->origen,
    $data->destino,
    $data->cantidad,
    $data->resultado,
    $data->tasa
);

echo json_encode($result);
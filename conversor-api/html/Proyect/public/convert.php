<?php

require_once '../vendor/autoload.php';

use App\Services\CurrencyService;

$data = json_decode(
    file_get_contents("php://input")
);

$service = new CurrencyService();

$result = $service->convert(
    $data->origen,
    $data->destino,
    $data->cantidad
);

echo json_encode($result);
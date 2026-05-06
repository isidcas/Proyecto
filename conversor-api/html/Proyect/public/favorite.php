<?php

require_once '../vendor/autoload.php';

use App\Services\FavoriteService;

$data = json_decode(
    file_get_contents("php://input")
);

$service = new FavoriteService();

$result = $service->save(
    $data->usuario_id,
    $data->moneda
);

echo json_encode($result);
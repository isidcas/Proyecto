<?php

require_once '../vendor/autoload.php';

use App\Services\AuthService;

$data = json_decode(
    file_get_contents("php://input")
);

$service = new AuthService();

$result = $service->login(
    $data->email,
    $data->password
);

echo json_encode($result);
<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json");

require_once '../vendor/autoload.php';

use App\Services\AuthService;

$data = json_decode(
    file_get_contents("php://input")
);

if(!$data){

    echo json_encode([
        "error" => "No hay datos"
    ]);

    exit;
}

if(
    !isset($data->email) ||
    !isset($data->password)
){

    echo json_encode([
        "error" => "Faltan datos"
    ]);

    exit;
}

$service = new AuthService();

$result = $service->login(
    $data->email,
    $data->password
);

echo json_encode($result);
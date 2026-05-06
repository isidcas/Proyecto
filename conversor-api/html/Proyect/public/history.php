<?php

require_once '../vendor/autoload.php';

use App\Services\HistoryService;

$id = $_GET['id'];

$service = new HistoryService();

$result = $service->getByUser($id);

echo json_encode($result);
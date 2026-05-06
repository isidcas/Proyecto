<?php

require_once './vendor/autoload.php';

header('Content-Type: application/json');

echo json_encode([
    'project' => 'Conversor de Divisas API',
    'version' => '1.0.0',
    'status' => 'OK',
    'endpoints' => [
        'register' => '/public/register.php',
        'login' => '/public/login.php',
        'convert' => '/public/convert.php',
        'save-history' => '/public/save-history.php',
        'history' => '/public/history.php?id=1',
        'favorite' => '/public/favorite.php',
        'contact' => '/public/contact.php'
    ]
]);
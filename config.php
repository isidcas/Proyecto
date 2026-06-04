<?php

// 1. Detectamos si estamos en producción (Render con Aiven)
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Si existe DATABASE_URL, troceamos la nueva URI de Aiven
    $fields = parse_url($databaseUrl);
    
    return [
        'db' => [
            'host'    => $fields['host'],
            'port'    => $fields['port'] ?? '12199',
            'dbname'  => 'defaultdb',
            'user'    => $fields['user'],
            'pass'    => $fields['pass'],
            'ssl'     => true
        ]
    ];
} else {
    // 2. Si no existe, estamos en tu entorno LOCAL (Docker de clase)
    return [
        'db' => [
            'host'    => 'conversor-api-db-1',
            'port'    => '3306',
            'dbname'  => 'conversor_divisas',
            'user'    => 'root',
            'pass'    => 'root',
            'ssl'     => false
        ]
    ];
}
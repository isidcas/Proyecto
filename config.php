<?php

// 1. Detectamos si estamos en producción (Render con Aiven)
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Si existe DATABASE_URL, troceamos la URI de Aiven automáticamente
    $fields = parse_url($databaseUrl);
    
    return [
        'db' => [
            'host'    => $fields['host'],
            'port'    => $fields['port'] ?? '12199',
            'dbname'  => 'defaultdb', // Aiven usa defaultdb por defecto
            'user'    => $fields['user'],
            'pass'    => $fields['pass'],
            'ssl'     => true,
            'ssl_ca'  => __DIR__ . '/ca.pem' // CORREGIDO: Está en la misma raíz que config.php
        ]
    ];
} else {
    // 2. Si no existe, estamos en tu entorno LOCAL (Docker)
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
<?php

// 1. Detectamos si estamos en producción (Render con Aiven)
$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    $fields = parse_url($databaseUrl);
    
    $host   = isset($fields['host']) ? $fields['host'] : 'mysql-3d75911a-project-fa07.f.aivencloud.com';
    $port   = isset($fields['port']) ? $fields['port'] : '12199';
    $user   = isset($fields['user']) ? $fields['user'] : 'avnadmin';
    $pass   = isset($fields['pass']) ? $fields['pass'] : ''; // Dejar vacío o leer de getenv
    $dbname = isset($fields['path']) ? ltrim($fields['path'], '/') : 'defaultdb';

    return [
        'db' => [
            'host'    => $host,
            'port'    => $port,
            'dbname'  => $dbname,
            'user'    => $user,
            'pass'    => $pass,
            'ssl'     => true,
            'ssl_ca'  => __DIR__ . '/ca.pem'
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
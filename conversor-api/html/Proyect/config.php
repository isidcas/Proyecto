<?php

return [
    'db' => [
        // getenv busca las variables que configuramos en Render. Si no existen, usa tu Docker local
        'host'   => getenv('DB_HOST') ?: 'conversor-api-db-1',
        'dbname' => getenv('DB_NAME') ?: 'conversor_divisas',
        'user'   => getenv('DB_USER') ?: 'root',
        'pass'   => getenv('DB_PASS') ?: 'root',
        'port'   => getenv('DB_PORT') ?: '3306'
    ]
];
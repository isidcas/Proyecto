<?php
namespace App\Database;

use PDO;
use Exception;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                // Cargamos el archivo de configuración
                $config = require __DIR__ . '/../../config.php'; 
                $dbConfig = $config['db'];

                // El DSN de MySQL limpio, sin parámetros extra de Postgres
                $dsn = "mysql:host=" . $dbConfig['host'] . ";port=" . $dbConfig['port'] . ";dbname=" . $dbConfig['dbname'] . ";charset=utf8mb4";
                
                // Inicializamos las opciones por defecto de PDO
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];

                // CORRECCIÓN CRÍTICA: Configuración correcta de SSL para MySQL en PDO
                if ($dbConfig['ssl']) {
                    $options[PDO::MYSQL_ATTR_SSL_CA] = $dbConfig['ssl_ca'];
                    // Esto evita errores si hay desajustes estrictos de nombres de host en entornos compartidos
                    $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
                }

                self::$instance = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], $options);

            } catch (Exception $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
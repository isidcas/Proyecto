<?php
namespace App\Database;

use PDO;
use Exception;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                // Cargamos el archivo de configuración que acabas de crear
                $config = require __DIR__ . '/../../config.php'; // Ajusta la ruta a tu config.php
                $dbConfig = $config['db'];

                $dsn = "mysql:host=" . $dbConfig['host'] . ";port=" . $dbConfig['port'] . ";dbname=" . $dbConfig['dbname'];
                
                // Si la configuración dice que requiere SSL (Aiven)
                if ($dbConfig['ssl']) {
                    $dsn .= ";sslmode=verify-ca;sslrootcert=" . $dbConfig['ssl_ca'];
                }

                self::$instance = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);

            } catch (Exception $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
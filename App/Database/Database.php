<?php
namespace App\Database;

use PDO;
use Exception;

class Database {
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                $config = require __DIR__ . '/../../config.php'; 
                $dbConfig = $config['db'];

                // Construimos el DSN base
                $dsn = "mysql:host=" . $dbConfig['host'] . ";port=" . $dbConfig['port'] . ";dbname=" . $dbConfig['dbname'];
                
                // Si estamos en producción (Aiven), aplicamos su DSN exacto de la guía
                if ($dbConfig['ssl']) {
                    // Subimos dos niveles desde App/Database para encontrar el ca.pem en la raíz
                    $rutaCertificado = __DIR__ . '/../../ca.pem';
                    $dsn .= ";sslmode=verify-ca;sslrootcert=" . $rutaCertificado;
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
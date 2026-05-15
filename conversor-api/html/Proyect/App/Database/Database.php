<?php

namespace App\Database;

use PDO;
use PDOException;

class Database {

    private static ?PDO $instance = null;

    public static function getInstance(): PDO {

        if(self::$instance === null){

            $config = require __DIR__ . '/../../config.php';

            $db = $config['db'];

            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8mb4";

            try{

                self::$instance = new PDO(
                    $dsn,
                    $db['user'],
                    $db['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );

            }catch(PDOException $e){

                die($e->getMessage());
            }
        }

        return self::$instance;
    }
}
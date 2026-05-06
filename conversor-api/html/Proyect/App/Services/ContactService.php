<?php

namespace App\Services;

use App\Database\Database;

class ContactService {

    public function save(
        $usuario_id,
        $email,
        $mensaje
    ){

        $db = Database::getInstance();

        $sql = "INSERT INTO contacto
        (usuario_id,email,mensaje)
        VALUES(?,?,?)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $usuario_id,
            $email,
            $mensaje
        ]);
    }
}
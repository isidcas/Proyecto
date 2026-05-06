<?php

namespace App\Services;

use App\Database\Database;

class FavoriteService {

    public function save($usuario_id,$moneda){

        $db = Database::getInstance();

        $sql = "INSERT INTO favoritos
        (usuario_id,moneda)
        VALUES(?,?)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $usuario_id,
            $moneda
        ]);
    }

    public function getByUser($usuario_id){

        $db = Database::getInstance();

        $sql = "SELECT * FROM favoritos WHERE usuario_id=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$usuario_id]);

        return $stmt->fetchAll();
    }
}
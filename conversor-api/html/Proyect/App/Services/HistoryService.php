<?php

namespace App\Services;

use App\Database\Database;

class HistoryService {

    public function save(
        $usuario_id,
        $origen,
        $destino,
        $cantidad,
        $resultado,
        $tasa
    ){

        $db = Database::getInstance();

        $sql = "INSERT INTO historial
        (usuario_id,moneda_origen,moneda_destino,cantidad,resultado,tasa_cambio)
        VALUES(?,?,?,?,?,?)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            $usuario_id,
            $origen,
            $destino,
            $cantidad,
            $resultado,
            $tasa
        ]);
    }

    public function getByUser($usuario_id){

        $db = Database::getInstance();

        $sql = "SELECT * FROM historial WHERE usuario_id=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$usuario_id]);

        return $stmt->fetchAll();
    }
}
<?php
namespace App\Models;
use App\Database\Database;
use PDO;

class History {
    public static function save($usuario_id, $origen, $destino, $cantidad, $resultado, $tasa) {
        $db = Database::getInstance();
        // Nombres exactos de tu imagen:
        $sql = "INSERT INTO historial (usuario_id, moneda_origen, moneda_destino, cantidad, resultado, tasa_cambio) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([$usuario_id, $origen, $destino, $cantidad, $resultado, $tasa]);
    }

    public static function getByUser($usuario_id) {
        $db = Database::getInstance();
        $sql = "SELECT * FROM historial WHERE usuario_id = ? ORDER BY fecha DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class Portfolio {
    
    // Obtiene las monedas que ya posee el usuario (Relación N:M)
    public static function obtenerPorUsuario(int $usuarioId): array {
        $db = Database::getInstance();
        $sql = "SELECT p.cantidad_poseida, d.codigo, d.nombre, d.simbolo 
                FROM portfolio p 
                JOIN divisas d ON p.divisa_codigo = d.codigo 
                WHERE p.usuario_id = ?
                ORDER BY p.cantidad_poseida DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Caso de uso nuevo: Permite al usuario "comprar" o "añadir" saldo a una divisa de su portfolio
    public static function actualizarSaldo(int $usuarioId, string $divisaCodigo, float $cantidad): bool {
        $db = Database::getInstance();
        
        // Comprobamos si el usuario ya tenía esa moneda en su portfolio
        $stmt = $db->prepare("SELECT cantidad_poseida FROM portfolio WHERE usuario_id = ? AND divisa_codigo = ?");
        $stmt->execute([$usuarioId, $divisaCodigo]);
        $existe = $stmt->fetch();

        if ($existe) {
            // Si ya existe, sumamos la nueva cantidad al saldo actual
            $nuevaCantidad = floatval($existe['cantidad_poseida']) + $cantidad;
            $sql = "UPDATE portfolio SET cantidad_poseida = ? WHERE usuario_id = ? AND divisa_codigo = ?";
            $stmtAct = $db->prepare($sql);
            return $stmtAct->execute([$nuevaCantidad, $usuarioId, $divisaCodigo]);
        } else {
            // Si no existe el registro en la tabla intermedia, lo insertamos desde cero
            $sql = "INSERT INTO portfolio (usuario_id, divisa_codigo, cantidad_poseida) VALUES (?, ?, ?)";
            $stmtIns = $db->prepare($sql);
            return $stmtIns->execute([$usuarioId, $divisaCodigo, $cantidad]);
        }
    }
}
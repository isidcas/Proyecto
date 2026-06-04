<?php
namespace App\Services;

use App\Database\Database;
use PDO;

class ConversorService {
    
    public static function obtenerMonedasDisponibles(): array {
        $json = @file_get_contents('https://api.frankfurter.app/currencies');
        return $json ? json_decode($json, true) : ['EUR' => 'Euro', 'USD' => 'Dólar'];
    }

    public static function convertirEHistoriar(int $usuarioId, string $from, string $to, float $amount): ?float {
        if ($from === $to) return null;

        $url = "https://api.frankfurter.app/latest?amount=$amount&from=$from&to=$to";
        $response = @file_get_contents($url);
        
        if ($response) {
            $resData = json_decode($response, true);
            if (isset($resData['rates'][$to])) {
                $resultado = floatval($resData['rates'][$to]);
                $tasa_cambio = $resultado / $amount;

                // Guardado en histórico
                $db = Database::getInstance();
                $sql = "INSERT INTO historial_conversiones (usuario_id, moneda_origen, moneda_destino, cantidad, resultado, tasa_cambio) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$usuarioId, $from, $to, $amount, $resultado, $tasa_cambio]);

                return $resultado;
            }
        }
        return null;
    }

    public static function obtenerHistorial(int $usuarioId, int $limite = 5): array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM historial_conversiones WHERE usuario_id = ? ORDER BY fecha DESC LIMIT ?");
        $stmt->bindValue(1, $usuarioId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene las divisas registradas en nuestra base de datos local
    public static function obtenerDivisasLocales(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM divisas ORDER BY codigo ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
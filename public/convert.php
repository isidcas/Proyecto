<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

// Lógica de rutas GET (se queda igual)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'currencies') {
        echo file_get_contents('https://api.frankfurter.app/currencies'); exit;
    } elseif ($_GET['action'] == 'market') {
        echo file_get_contents('https://api.frankfurter.app/latest?from=EUR'); exit;
    }
}

// Procesar el POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    $amount     = isset($data['amount']) ? floatval($data['amount']) : 1.0;
    $from       = isset($data['from']) ? $data['from'] : 'EUR';
    $to         = isset($data['to']) ? $data['to'] : 'USD';
    // CAPTURAMOS EL ID COMO ENTERO (Si no llega, ponemos 1 temporalmente para pruebas)
    $usuario_id = isset($data['usuario_id']) ? intval($data['usuario_id']) : 1;

    $url = "https://api.frankfurter.app/latest?amount=$amount&from=$from&to=$to";
    $response = file_get_contents($url);
    
    if ($response) {
        $resData = json_decode($response, true);
        
        if (isset($resData['rates'][$to])) {
            $resultado = $resData['rates'][$to];
            $tasa_cambio = $resultado / $amount;

            // Guardado directo en Docker MySQL respetando los tipos de tu imagen
            try {
                $db = new PDO("mysql:host=conversor-api-db-1;dbname=conversor_divisas;charset=utf8", "root", "root");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "INSERT INTO historial (usuario_id, moneda_origen, moneda_destino, cantidad, resultado, tasa_cambio) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$usuario_id, $from, $to, $amount, $resultado, $tasa_cambio]);
            } catch (Exception $e) {
                // Silencioso
            }
        }
        echo $response;
        exit;
    }
}
exit;
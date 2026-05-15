<?php
namespace App\Services;
use App\Database\Database;
use PDO;

class AuthService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($email, $password) {
        // Tu SQL dice tabla 'usuarios', no 'usuario'
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Tu SQL dice columna 'password', no 'contraseña'
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); 
            return ["user" => $user];
        }
        return ["error" => "Credenciales incorrectas"];
    }
}
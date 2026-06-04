<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class Usuario {
    public static function buscarPorEmail(string $email): ?array {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function registrar(string $nombre, string $email, string $password): bool {
        $db = Database::getInstance();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, 'user')");
        return $stmt->execute([$nombre, $email, $hashedPassword]);
    }

    public static function obtenerTodos(): array {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT id, nombre, email, rol, created_at FROM usuarios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php

namespace App\Services;

use App\Database\Database;

class AuthService {

    public function register($nombre,$email,$password){

        $db = Database::getInstance();

        $sql = "INSERT INTO usuarios
        (nombre,email,password)
        VALUES(?,?,?)";

        $stmt = $db->prepare($sql);

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        return $stmt->execute([
            $nombre,
            $email,
            $passwordHash
        ]);
    }

    public function login($email,$password){

        $db = Database::getInstance();

        $sql = "SELECT * FROM usuarios WHERE email=?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if($user && password_verify($password,$user['password'])){
            return $user;
        }

        return false;
    }
}
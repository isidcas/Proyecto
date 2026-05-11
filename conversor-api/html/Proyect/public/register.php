<?php

header("Access-Control-Allow-Origin: *");

include("../config/conexion.php");

$data = json_decode(
    file_get_contents("php://input")
);

$nombre = $data->nombre;

$email = $data->email;

$password = password_hash(
    $data->password,
    PASSWORD_DEFAULT
);

$sql = "INSERT INTO usuarios
(nombre,email,password)
VALUES
('$nombre','$email','$password')";

if($conexion->query($sql)){

    echo json_encode([
        "mensaje"=>"Usuario creado"
    ]);
}
?>

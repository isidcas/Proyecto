<?php

header("Access-Control-Allow-Origin: *");

include("../config/conexion.php");

$data = json_decode(
    file_get_contents("php://input")
);

$sql = "INSERT INTO favoritos
(usuario_id,moneda)
VALUES(
'$data->usuario_id',
'$data->moneda'
)";

$conexion->query($sql);
?>


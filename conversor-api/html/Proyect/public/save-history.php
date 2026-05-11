<?php

header("Access-Control-Allow-Origin: *");

include("../config/conexion.php");

$data = json_decode(
    file_get_contents("php://input")
);

$sql = "INSERT INTO historial
(usuario_id,origen,destino,cantidad,resultado)
VALUES(
'$data->usuario_id',
'$data->origen',
'$data->destino',
'$data->cantidad',
'$data->resultado'
)";

$conexion->query($sql);
?>

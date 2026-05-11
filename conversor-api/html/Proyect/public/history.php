<?php

header("Access-Control-Allow-Origin: *");

include("../config/conexion.php");

$id = $_GET['id'];

$sql = "SELECT * FROM historial
WHERE usuario_id='$id'";

$resultado = $conexion->query($sql);

$datos = [];

while($fila = $resultado->fetch_assoc()){

    $datos[] = $fila;
}

echo json_encode($datos);
?>

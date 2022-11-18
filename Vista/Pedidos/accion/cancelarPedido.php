<?php
include_once "../../../configuracion.php";

$data = data_submitted();

$objControl = new AbmCompra;

$result["respuesta"] = $objControl->cambiarEstado(["id" => $data["id"],"idcompraestadotipo" => 5]);

if(!$result["respuesta"]){
    $result["errorMsg"] = "No se pudo concretar la cancelación";
}

echo json_encode($result);

?>
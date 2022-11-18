<?php 
include_once "../../../configuracion.php";
$data = data_submitted();
$resultado = false;

if(isset($data["id"])){
    $objControl = new AbmCompra;

    $data["idcompraestadotipo"] = 2; // iniciada
    $resultado = $objControl->cambiarEstado($data);

    if(!$resultado){
        $errorMsg = "No se pudo concretar el pago.";
    }
}


if(isset($errorMsg)){
    $result["error"] = $errorMsg;
}
$result["resultado"] = $resultado;

echo json_encode($result);

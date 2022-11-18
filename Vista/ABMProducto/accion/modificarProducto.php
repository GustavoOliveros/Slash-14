<?php
include_once "../../../configuracion.php";
$data = data_submitted();
$respuesta = false;

if (isset($data['id'])){
    $objC = new AbmProducto();

    $data["imagen"] = $_FILES;
    $respuesta = $objC->modificacion($data);
    
    if (!$respuesta){
        $sms_error = "La modificación no pudo concretarse.";
    }
}else{
    $sms_error = "Hubo un error en el envío. Vuelva a intentarlo.";
}

$retorno['respuesta'] = $respuesta;

if (isset($sms_error)){
    $retorno['errorMsg']=$sms_error;
}

echo json_encode($retorno);
?>
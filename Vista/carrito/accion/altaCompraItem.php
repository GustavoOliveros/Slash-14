<?php 
include_once "../../../configuracion.php";


$objControl = new AbmCompra();
$session = new Session();

// Recibo idusuario, idproducto y cantidad
$param = data_submitted();
$param["idusuario"] = $_SESSION["idusuario"];
$respuesta = false;
$contador = 0;
$bandera = false;

// Busqueda de carrito
$compras = $objControl->buscar($param);

if(isset($compras) && count($compras) > 0){
    while($contador < count($compras) && !$bandera){
        $compraEstado = $objControl->buscarEstado(["id" => $compras[$contador]->getId(), "idcompraestadotipo" => 1]);
        if(isset($compraEstado) && count($compraEstado) > 0){
            // tengo carrito
            $param["id"] = $compras[$contador]->getId();
            $list = $objControl->buscarItems($param);
            $bandera = true;
        }
        $contador++;
    }
}

$banderaItem = false;

if($bandera){
    // En este punto tengo la id del usuario, la compra y sus items
    
    if(isset($list) && count($list) > 0){
        // Debo recorrer sus items y ver si tiene otro item del mismo producto
        $contador = 0;

        while($contador < count($list) && !$banderaItem){
            // Si tengo un item del mismo producto, sumo las cantidades y modifico

            if($list[$contador]->getObjProducto()->getId() == $param["idproducto"]){
                $param["idcompraitem"] = $list[$contador]->getId();
                $param["cantidad"] = $list[$contador]->getCantidad() + $param["cantidad"];
                $respuesta = $objControl->modificarItem($param);
                $banderaItem = true;
            }
            $contador++;
        }
    }
}

if(!$bandera){
    // Crear compra
    $altaRespuesta = $objControl->alta($param);
    if($altaRespuesta["resultado"]){
        $param["id"] = $altaRespuesta["obj"]->getId();

        // Crear estado
        $respuesta = $objControl->estadoInicial($param);
    }
}

if(!$banderaItem){
    // Crear item y asignarselo a la compra
    $respuesta = $objControl->agregarItem($param);
}


if(!$respuesta){
    $result["errorMsg"] = "No se pudo concretar la acción";
}

$result["respuesta"] = $respuesta;

echo json_encode($result);

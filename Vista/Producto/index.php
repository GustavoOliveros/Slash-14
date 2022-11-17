<?php
$titulo = "Producto";
include_once "../Estructura/headerInseguro.php";

$param = data_submitted();
$objC = new AbmProducto;
$producto = $objC->buscar($param);

$colecProductos = $objC->buscar(null);
$destacados = array_rand($colecProductos, 3);
$recomendaciones = "";

foreach ($destacados as $productoKey) {
    $recomendaciones .=
    '<div class="col-12 col-md-4 mb-3"><a class="text-dark text-decoration-none" href="../Producto/index.php?id='. $colecProductos[$productoKey]->getId() .'">
    <div class="card" style="width: 18rem;height:450px">
        <img src="../img/product-placeholder.jpg" class="card-img-top" alt="' . $colecProductos[$productoKey]->getNombre() . '">
        <div class="card-body">
            <p class="card-title">' . $colecProductos[$productoKey]->getNombre() . '</p>
            <h4>$10.000,00</h4>
        </div>
    </div></a>
    </div>';
}

?>
<!-- Contenido -->
<main class="col-12 my-3 mx-auto w-100 max">
    <?php
    if(!isset($producto)){
        echo mostrarError('El producto no fue encontrado.<br>
        <a href="../Home/index.php">Volver al menu principal</a>');

    }else{
        echo '<div class="col-12 rounded">
        <div class="row col-12 p-3 rounded mx-auto">

            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center flex-column p-5">
                <img src="../img/placeholder-pfp.jpg" class="rounded img-fluid d-flex align-items-center justify-content-center" alt="Foto de perfil">
            </div>
            <div class="col-12 col-md-6 p-5 d-flex align-items-center justify-content-center">
                <div id="errores"></div>

                <form method="POST" id="form-abm">
                    <div class="col-12 bg-light p-5 rounded" >
                        <h3 class="fw-bold">'. $producto[0]->getNombre() .'</h3>
                        <h4 class="fw-light my-3">$20,000</h4>
                        <div class="col-6">
                            <input type="number" class="form-control" placeholder="Cantidad">
                        </div>
                        <button class="btn btn-primary col-12 my-2">Agregar al Carrito</button>
                    </div>

                </form>
            </div>
            <h3 class="mb-4">Le podría interesar</h3>
            <div class="row col-12 mb-5">
            '. $recomendaciones .'
            </div>

            <div class="col-12 rounded bg-light p-5 mb-5">
                <h4>Detalles del Producto</h4>
                '. $producto[0]->getDetalle() . '
            </div>
        </div>
    </div>';
    }
    ?>
</main>
<script src="../js/perfil.js"></script>
<?php
include_once "../Estructura/footer.php";
?>
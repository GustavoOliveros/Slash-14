<?php
$titulo = "Producto";
include_once "../Estructura/headerInseguro.php";

$param = data_submitted();
$objC = new AbmProducto;
$producto = $objC->buscar($param);

$colecProductos = $objC->buscar(null);
$destacados = array_rand($colecProductos, 3);
$recomendaciones = "";

$imagen = "../../Control/Subidas/". md5($producto[0]->getId()) . ".jpg";
$imagen = (file_exists($imagen)) ? $imagen : "../img/product-placeholder.jpg";

foreach ($destacados as $productoKey) {
    $recomendacionesImg = "../../Control/Subidas/". md5($colecProductos[$productoKey]->getId()) . ".jpg";
    $recomendacionesImg = (file_exists($recomendacionesImg)) ? $recomendacionesImg : "../img/product-placeholder.jpg";

    $recomendaciones .=
        '<div class="col-12 col-md-4 mb-3"><a class="text-dark text-decoration-none" href="../Producto/index.php?id=' . $colecProductos[$productoKey]->getId() . '">
    <div class="card" style="width: 18rem;height:450px">
        <img src="'.$recomendacionesImg.'" class="card-img-top" alt="' . $colecProductos[$productoKey]->getNombre() . '">
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
    if (!isset($producto)) {
        echo mostrarError('El producto no fue encontrado.<br>
        <a href="../Home/index.php">Volver al menu principal</a>');
    } else {
        echo '<div class="col-12 rounded">
        <div id="exito"></div>
        <div id="errores"></div>
        <div class="row col-12 p-3 rounded mx-auto">

            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center flex-column p-5">
                <img src="'. $imagen .'" class="rounded img-fluid d-flex align-items-center justify-content-center" alt="Foto de perfil">
            </div> 

            <div class="col-12 col-md-6 p-5 d-flex align-items-center justify-content-center">

                <form method="POST" id="form-abm">
                    <div class="col-12 bg-light p-5 rounded" >
                        <h5 class="fw-bold">' . $producto[0]->getNombre() . '</h5>
                        <h3 class="my-4">$<span id="precio">20000</span></h3>
                        <div class="row col-12 mb-2">
                            <input type="text" id="idproducto" name="idproducto" value="'. $producto[0]->getId() .'" hidden>
                            <div class="col-6 d-flex align-items-center justify-content-center">
                                Cantidad
                            </div>
                        
                            <div class="col-6">
                                <input type="number" class="form-control" value="1" name="cantidad" id="cantidad" min="0">
                                <div class="invalid-feedback" id="feedback-cantidad"></div>
                            </div>
                        </div>
                        

                        <button class="btn btn-primary col-12 my-2" type="submit" id="btn-submit">Agregar al Carrito</button>
                    </div>

                </form>
            </div>
            <h3 class="mb-4">Le podría interesar</h3>
            <div class="row col-12 mb-5 mx-auto">
            ' . $recomendaciones . '
            </div>

            <div class="col-12 rounded bg-light p-5 mb-5">
                <h4>Detalles del Producto</h4>
                ' . $producto[0]->getDetalle() . '
            </div>
        </div>
    </div>';
    }
    ?>
</main>
<script>
    var url;
    const PRECIO = $("#precio").text();
    

    /**
     * Plantilla para mostrar un cartel de error
     * @param string $contenidoError
     * @return string
     */
    function mostrarError(contenidoError) {
        return (
            '<div class="col-12 alert alert-danger m-3 p-3 mx-auto alert-dismissible fade show" role="alert">' +
            contenidoError +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
        );
    }
    $("#form-abm").validate({
        rules: {
            cantidad: {
                required: true,
            },
        },
        messages: {
            cantidad: {
                required: "Obligatorio",
                min: "Debe ser mayor a 0",
                number: "Ingrese un número válido"
            },
        },
        errorPlacement: function(error, element) {
            let id = "#feedback-" + element.attr("id");
            element.addClass("is-invalid");

            $(id).text(error[0].innerText);
        },
        highlight: function(element) {
            $(element).removeClass("is-valid").addClass("is-invalid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        success: function(element) {
            $(element).addClass("is-valid");
        },
        submitHandler: function(e) {
            $.ajax({
                url: "../carrito/accion/altaCompraItem.php",
                type: "POST",
                data: $("#form-abm").serialize(),
                beforeSend: function() {
                    $("#btn-submit").html(
                        '<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Cargando...'
                    );
                },
                complete: function() {
                    $("#btn-submit").html("Agregar al Carrito");
                },
                success: function(result) {
                    result = JSON.parse(result);

                    if (result.respuesta) {
                        var exito = '<div class="col-12 alert alert-success m-3 p-3 mx-auto alert-dismissible fade show" role="alert">Se agregó con éxito<br>'+
                        '<a href="../carrito/index.php">Ver en su carrito.</a><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        $("#exito").html(exito);
                    } else {
                        $("#errores").html(mostrarError(result.errorMsg));
                    }
                },
            });
        },
    });

    $("#cantidad").on("input",function(){
        $("#precio").text($("#cantidad").val() * PRECIO);
    })

    function recargar() {
        $("#tabla").DataTable().ajax.reload();
    }

    function limpiar() {
        $("#form-abm").trigger("reset");
        $("#roldescripcion").removeClass("is-invalid").removeClass("is-valid");
        $("#permisos").removeClass("is-invalid").removeClass("is-valid");
        var arreglo = $(".permisos");
        for (var i = 0; i < arreglo.length; i++) {
            arreglo[i].removeAttribute("checked");
        }
    }

    function newMenu() {
        $("#title").html("Nuevo");
        $("#dlg").modal("show");

        limpiar();

        $("#btn-submit").html("Agregar");
        $("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

        url = "accion/altaRol.php";
    }

    function editMenu() {
        $("#tabla tbody").on("click", "button", function() {
            var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

            if (data != null) {
                $("#title").html("Editar");
                $("#dlg").modal("show");
                limpiar();

                var arreglo = $(".permisos");
                for (var i = 0; i < arreglo.length; i++) {
                    if ($("#" + data["id"] + "-" + arreglo[i].value).length != 0) {
                        arreglo[i].setAttribute("checked", "true");
                    }
                }

                $("#btn-submit").html("Editar");
                $("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

                $("#id").val(data["id"]);
                $("#roldescripcion").val(data["roldescripcion"]);

                url = "accion/modificarRol.php";
            }
        });
    }
</script>
<?php
include_once "../Estructura/footer.php";
?>
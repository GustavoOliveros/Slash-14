<?php
$titulo = "Mi carrito";
include_once "../Estructura/headerSeguro.php";

?>

<!-- Contenido -->
<main class="col-12 my-3 mx-auto w-100 max">
    <!-- TABLA -->
    <h2>Mi Carrito</h2>
    <button class="btn btn-secondary my-2" onclick="recargar();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
        </svg>
    </button>
    <a href="../pago/index.php" class="btn btn-primary mx-auto btn-pago">Proceder con el pago</a>
    <table class="table table-striped table-bordered nowrap" id="tabla">
        <thead class="bg-dark text-light">
            <th field="producto">Producto</th>
            <th field="cantidad">Cantidad</th>
            <th field="accion">Acciones</th>
        </thead>
        <tbody>

        </tbody>
    </table>

    <a href="../pago/index.php" class="btn btn-primary my-4 btn-pago">Proceder con el pago</a>

    <!-- MODAL -->
    <div class="modal fade" id="dlg" tabindex="-1" aria-labelledby="dlg" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="fw-5 text-center m-3" id="title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="" id="form-abm" method="post">
                    <div class="modal-body">
                        <div id="errores" class="col-12"></div>
                        <div id="edit-form">
                            <input type="text" name="idcompraitem" id="idcompraitem" hidden>
                            <div class="col-12 mb-2">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" min="0" class="form-control" name="cantidad" id="cantidad">
                                <div class="invalid-feedback" id="feedback-cantidad"></div>
                            </div>
                        </div>
                        <div id="delete-form">
                            <p class="text-danger">¿Esta seguro de que quiere sacar <span id="rol-name"></span> de su carrito?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btn-submit" class="btn btn-primary">Enviar</button>
                        <input type="button" value="Cancelar" class="btn btn-secondary" onclick="$('#dlg').modal('hide');">
                    </div>
                </form>
            </div>
        </div>
</main>
<script>
    var url;

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

    $(document).ready(function() {
        var table = $("#tabla").DataTable({
            responsive: true,
            ajax: {
                url: "accion/listarCompraItem.php",
                dataSrc: "",
            },
            columns: [
                {
                    data: "producto",
                },
                {
                    data: "cantidad",
                },
                {
                    data: "accion",
                },
            ]
        });
    });

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
                url: url,
                type: "POST",
                data: $("#form-abm").serialize(),
                beforeSend: function() {
                    $("#btn-submit").html(
                        '<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Cargando...'
                    );
                },
                complete: function() {
                    $("#btn-submit").html("Reintentar");
                },
                success: function(result) {
                    result = JSON.parse(result);

                    if (result.respuesta) {
                        $("#dlg").modal("hide");
                        $("#form-abm").trigger("reset");
                        recargar();
                    } else {
                        $("#errores").html(mostrarError(result.errorMsg));
                    }
                },
            });
        },
    });

    function recargar() {
        $("#tabla").DataTable().ajax.reload();
    }

    function limpiar() {
        $("#form-abm").trigger("reset");
        $("#cantidad").removeClass("is-invalid").removeClass("is-valid");
    }

    function editMenu() {
        $("#tabla tbody").on("click", "button", function() {
            var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

            if (data != null) {
                $("#title").html("Editar");
                $("#dlg").modal("show");
                limpiar();

                $("#delete-form").hide();
                $("#edit-form").show();

                $("#btn-submit").html("Editar");
                $("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

                $("#idcompraitem").val(data["id"]);
                $("#cantidad").val(data["cantidad"]);

                url = "accion/modificarCompraItem.php";
            }
        });
    }

    function destroyMenu() {
        $("#tabla tbody").on("click", "button", function() {
            var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

            if (data != null) {
                $("#title").html("Eliminar");
                $("#dlg").modal("show");

                limpiar();

                $("#edit-form").hide();
                $("#delete-form").show();

                $("#rol-name").text(data.producto);
                $("#btn-submit").html("Eliminar");
                $("#btn-submit").removeClass("btn-primary").addClass("btn-danger");

                $("#idcompraitem").val(data["id"]);
                $("#cantidad").val(data["cantidad"]);

                url = "accion/eliminarCompraItem.php";
            }
        });
    }
</script>

<?php
include_once "../Estructura/footer.php";
?>
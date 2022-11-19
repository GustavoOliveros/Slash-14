<?php
$titulo = "ABM Compras";
include_once "../Estructura/headerSeguro.php";

$objControl = new AbmCompra;
$estados = $objControl->listarEstados();

?>

<!-- Contenido -->
<main class="col-12 my-3 mx-auto w-100 max">
    <!-- TABLA -->
    <h2>ABM Compras</h2>
    <button class="btn btn-secondary my-2" onclick="recargar();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
        </svg>
    </button>
    <table class="table table-striped table-bordered nowrap" id="tabla">
        <thead class="bg-dark text-light">
            <th field="id">Id</th>
            <th field="usuario">Usuario</th>
            <th field="productos">Productos</th>
            <th field="fecha">Fecha de Compra</th>
            <th field="estado">Estado</th>
            <th field="fechaestado">Estado desde</th>
            <th field="accion">Acciones</th>
        </thead>
        <tbody>

        </tbody>
    </table>

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
                            <input type="text" name="id" id="id" hidden>
                            <div class="col-12 mb-2">
                                <label for="idcompraestadotipo" class="form-label">Padre</label>
                                <select name="idcompraestadotipo" id="idcompraestadotipo" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <?php
                                    foreach($estados as $estado){
                                        echo '<option class="estadostipos" value="'. $estado->getIdcet() . '">' . $estado->getCetdescripcion() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div id="delete-form">
                            <p class="text-danger">¿Esta seguro de que quiere cancelar esta compra? <em>Esta acción es irreversible</em></p>
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
                url: "accion/listarPedidos.php",
                dataSrc: "",
            },
            columns: [
                {
                    data: "id",
                },
                {
                    data: "usuario"
                },
                {
                    data: "productos",
                },
                {
                    data: "fecha",
                },
                {
                    data: "estado",
                },
                {
                    data: "estadofecha",
                },
                {
                    data: "accion",
                },
            ]
        });
    });

    $("#form-abm").validate({
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
        $("#idcompraestadotipo").removeClass("is-invalid").removeClass("is-valid");
        var arreglo = $(".estadostipo");
        for (var i = 0; i < arreglo.length; i++) {
            arreglo[i].removeAttribute("checked");
        }
    }


    function destroyMenu() {
        $("#tabla tbody").on("click", "button", function() {
            var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

            if (data != null) {
                $("#title").html("Cancelar Pedido");
                $("#dlg").modal("show");

                $("#edit-form").hide();
			    $("#delete-form").show();

                $("#btn-submit").html("Cancelar");
                $("#btn-submit").removeClass("btn-primary").addClass("btn-danger");

                $("#id").val(data["id"]);

                url = "accion/cancelarPedido.php";
            }
        });
    }

    
function editMenu() {
	$("#tabla tbody").on("click", "button", function () {
		var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

		if (data != null) {
			$("#title").html("Cambiar Estado");
			$("#dlg").modal("show");

			limpiar();

			$("#delete-form").hide();
			$("#edit-form").show();

			$("#btn-submit").val("Cambiar");
			$("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

			$("#id").val(data["id"]);
			url = "accion/cambiarEstadoPedido.php";
		}
	});



}
</script>

<?php
include_once "../Estructura/footer.php";
?>
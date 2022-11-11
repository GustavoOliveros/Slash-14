<?php
$titulo = "ABM Menu";
include_once "../Estructura/headerSeguro.php";
$objControl = new AbmMenu();
$colecMenu = $objControl->buscar(null);
?>

<!-- Contenido -->
<main class="col-12 my-3 max mx-auto">
    <!-- TABLA -->
    <h2>ABM - Menu</h2>
    <button class="btn btn-primary my-2" onclick="newMenu();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z" />
        </svg>
        Nuevo
    </button>
    <button class="btn btn-secondary my-2" onclick="recargar();">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
            <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
        </svg>
    </button>
    <table class="table table-striped table-bordered nowrap" id="tabla">
        <thead class="bg-dark text-light">
            <th field="id">Id</th>
            <th field="nombre">Nombre</th>
            <th field="descripcion">Descripcion</th>
            <th field="padre">Padre</th>
            <th field="deshabilitado">Estado</th>
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
                <form class="needs-validation" method="post" novalidate>
                    <div class="modal-body">
                        <input type="text" name="id" id="id" hidden>
                        <div class="col-12 mb-2">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre" pattern="/^[A-Z]+$/i" required>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="nombre" class="form-label">Descripción</label>
                            <input type="text" class="form-control" name="descripcion" id="descripcion" pattern="/^[A-Z]+$/i" required>
                        </div>
                        <div class="col-12 mb-2">
                            <label for="nombre" class="form-label">Padre</label>
                            <select name="" id=""></select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary">
                        <input type="button" value="Cancelar" class="btn btn-danger" onclick="$('#dlg').modal('hide');">
                    </div>
                </form>
            </div>
        </div>
</main>


<script>
    var url;

    $(document).ready(function() {
        var table = $("#tabla").DataTable({
            "ajax": {
                "url": "accion/listarMenu.php",
                "dataSrc": ""
            },
            "columns": [{
                    data: "id"
                },
                {
                    data: "nombre"
                },
                {
                    data: "descripcion"
                },
                {
                    data: "padre"
                },
                {
                    data: "deshabilitado"
                },
                {
                    data: "accion"
                }
            ]
        });
    });

    function recargar() {
        $("#tabla").DataTable().ajax.reload();
    }


    function newMenu() {
        $("#title").html("Nuevo");
        $('#dlg').modal('show');
        $('#form').trigger('reset');
        url = 'accion/altaMenu.php';
    }

    function editMenu() {
        $('#tabla tbody').on('click', 'button', function() {
            var data = $("#tabla").DataTable().row($(this).parents('tr')).data();

            if(data != null){
                $("#title").html("Editar");
                $('#dlg').modal('show');
                $('#form').trigger('reset');
                
                $( "#id" ).val( data["id"] );
                $( "#nombre" ).val( data["nombre"] );
                $( "#descripcion" ).val( data["descripcion"] );
                $( "#nombre" ).val( data["nombre"] );
            }
        });
    }

    function saveMenu() {
        $('#fm').form('submit', {
            url: 'accion/altaMenu.php',
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(result) {
                console.log(result);
                var result = eval('(' + result + ')');

                if (!result.respuesta) {
                    $.messager.show({
                        title: 'Error',
                        msg: result.errorMsg
                    });
                } else {

                    $('#dlg').dialog('close');
                    recargar();
                }
            }
        });
    }

    function destroyMenu(id) {
        $("#title").html("Eliminar - " + id);

        var row = $('#dg').datagrid('getSelected');
        if (row) {
            $.messager.confirm('Confirm', 'Seguro que desea eliminar el menu?', function(r) {
                if (r) {
                    $.post('accion/eliminarMenu.php?idmenu=' + row.idmenu, {
                            idmenu: row.id
                        },
                        function(result) {
                            if (result.respuesta) {

                                $('#dg').datagrid('reload');
                            } else {
                                $.messager.show({
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        }, 'json');
                }
            });
        }
    }
</script>


<?php
include_once "../Estructura/footer.php";
?>
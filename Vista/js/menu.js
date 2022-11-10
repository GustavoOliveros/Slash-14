var url;

function newMenu() {
    $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Menu');
    $('#fm').form('clear');
    url = 'accion/altaMenu.php';
}

function editMenu() {
    var row = $('#dg').datagrid('getSelected');
    if (row) {
        $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Menu');
        $('#fm').form('load', row);
        url = 'accion/modificarMenu.php?accion=mod&idmenu=' + row.idmenu;
    }
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
                $('#dg').datagrid('reload'); 
            }
        }
    });
}

function destroyMenu() {
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
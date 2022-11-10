<?php
$titulo = "test";
include_once "../Estructura/headerSeguro.php";
$objControl = new AbmMenu();
$colecMenu = $objControl->buscar(null);

$combo = '<select class="easyui-combobox"  id="idpadre"  name="idpadre" label="Submenu de:" labelPosition="top" style="width:90%;">
<option></option>';
foreach ($colecMenu as $objMenu) {
    $combo .= '<option value="' . $objMenu->getId() . '">' . $objMenu->getNombre() . ':' . $objMenu->getDescripcion() . '</option>';
}
$combo .= '</select>';

?>

<!-- Contenido -->
<h2>ABM - Menu</h2>
<p>Seleccione la acci&oacute;n que desea realizar.</p>

<table id="dg" title="Administrador de item menu" class="easyui-datagrid" style="width:700px;height:250px" url="accion/listarMenu.php" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
    <thead>
        <tr>
            <th field="id" width="50">ID</th>
            <th field="nombre" width="50">Nombre</th>
            <th field="descripcion" width="50">Descripci&oacute;n</th>
            <th field="idpadre" width="50">Submenu De:</th>
            <th field="deshabilitado" width="50">Deshabilitado</th>
        </tr>
    </thead>
</table>
<div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenu()">Nuevo Menu </a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenu()">Editar Menu</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMenu()">Baja Menu</a>
</div>

<div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
    <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
        <h3>Menu Informacion</h3>
        <div style="margin-bottom:10px">


            <input name="nombre" id="nombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="descripcion" id="descripcion" class="easyui-textbox" required="true" label="Descripcion:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <?php
            echo $combo;
            ?>

        </div>
        <div style="margin-bottom:10px">
            <input class="easyui-checkbox" name="deshabilitado" value="deshabilitado" label="Des-Habilitar:">
        </div>
    </form>
</div>
<div id="dlg-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveMenu()" style="width:90px">Aceptar</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
</div>
<script src="../js/menu.js"></script>


<?php
include_once "../Estructura/footer.php";
?>
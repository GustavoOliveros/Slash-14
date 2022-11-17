var url;


$("#compra").validate({
	rules: {
		cantidad: {
			required: true,
			number: true,
			digits: true,
		}
	},
	messages: {
		cantidad: {
			required: "Obligatorio",
			number: "Cantidad inválida",
			digits: "Cantidad inválida",
			min: "Mínimo 1 unidad",
			max: "Máximo 10 unidades",
		},
	},
	errorPlacement: function (error, element) {
		let id = "#feedback-compra";
		element.addClass("is-invalid");

		$(id).text(error[0].innerText);
	},
	highlight: function (element) {
		$(element).removeClass("is-valid").addClass("is-invalid");
	},
	unhighlight: function (element) {
		$(element).removeClass("is-invalid").addClass("is-valid");
	},
	success: function (element) {
		$(element).addClass("is-valid");
	},
	submitHandler: function (e) {
		$.ajax({
			url: "../carrito-accion/altaCompraItem",
			type: "POST",
			data: $("#compra").serialize(),
			success: function (result) {
				// recargarCarrito()
				$("#carrito").modal("show"); 
			},
		});
	},
});

function cargarSelectPadre() {
	var ids = $("#tabla").DataTable().column(0).data();
	var nombres = $("#tabla").DataTable().column(1).data();
	var html = '<option value="" selected>Seleccione uno</option>';

	for (var i = 0; i < ids.length; i++) {
		html += '<option value="' + ids[i] + '">' + nombres[i] + "</option>";
	}

	// PROBLEMAS:
	// PODER QUITAR PADRE
	// NO PODER AUTO-SELECCIONARSE

	$("#idpadre").html(html);
}

function recargar() {
	$("#tabla").DataTable().ajax.reload();
}

function limpiar() {
	$("#form-abm").trigger("reset");
	$("#nombre").removeClass("is-invalid").removeClass("is-valid");
	$("#descripcion").removeClass("is-invalid").removeClass("is-valid");
}

function newMenu() {
	cargarSelectPadre();
	$("#title").html("Nuevo");
	$("#dlg").modal("show");

	limpiar();

	$("#delete-form").hide();
	$("#edit-form").show();

	$("#btn-submit").val("Agregar");
	$("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

	url = "accion/altaMenu.php";
}

function editMenu() {
	cargarSelectPadre();
	$("#tabla tbody").on("click", "button", function () {
		var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

		if (data != null) {
			$("#title").html("Editar");
			$("#dlg").modal("show");

			limpiar();

			$("#delete-form").hide();
			$("#edit-form").show();

			$("#btn-submit").val("Editar");
			$("#btn-submit").removeClass("btn-danger").addClass("btn-primary");

			$("#id").val(data["id"]);
			$("#nombre").val(data["nombre"]);
			$("#descripcion").val(data["descripcion"]);

			url = "accion/modificarMenu.php";
		}
	});
}

function destroyMenu() {
	$("#tabla tbody").on("click", "button", function () {
		var data = $("#tabla").DataTable().row($(this).parents("tr")).data();

		if (data != null) {
			$("#title").html("Eliminar");
			$("#dlg").modal("show");

			limpiar();

			$("#edit-form").hide();
			$("#delete-form").show();
			$("#menu-name").text(data.nombre);
			$("#btn-submit").val("Eliminar");
			$("#btn-submit").removeClass("btn-primary").addClass("btn-danger");

			$("#id").val(data["id"]);
			$("#nombre").val(data["nombre"]);
			$("#descripcion").val(data["descripcion"]);

			url = "accion/eliminarMenu.php";
		}
	});
}

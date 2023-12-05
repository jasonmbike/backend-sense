var tabla_faq = {
    id: "#tabla_faq",
    url: base_url + "faq/getFaq",
};

$(document).ready(function () {
    cargarTablaFaq();
    abrirModalEdicion(); // Invoca la función para abrir el modal de edición al hacer clic en "Editar"
});

function cargarTablaFaq() {
    cargarTabla(tabla_faq);
}

function cargarTabla(info) {
    $(info.id).DataTable({
        "processing": true,
        "serverSide": false,
        "pageLength": 5,
        "lengthMenu": [6, 10, 25, 50],
        "ajax": {
            url: info.url,
            type: "POST",
            dataSrc: function (response) {
                return response.data;
            },
            error: function () {
                // Manejar el error si la carga falla
            }
        },
        "columns": [
            { data: 'id' },
            { data: 'titulo' },
            { data: 'descripcion' },
            { data: 'opciones' }
        ],
        "language": {
            // Configuración de idioma, si es necesario
        }
    });
}


function abrirModalEdicion() {
    // Evento al hacer clic en un elemento con la clase "cacv-editar"
        $(document).off("click", ".cacv-editar");
        $(document).on("click", ".cacv-editar", function () {
        var id = $(this).data('id');
        var titulo = $(this).data('titulo');
        var descripcion = $(this).data('descripcion');

        // Rellenar los campos del modal con los datos del registro
        $('#registroId').val(id);
        $('#titulo').val(titulo);
        $('#descripcion').val(descripcion);

        // Mostrar el modal de edición
        $('#editarRegistroModal').modal('show');
    });
}

function guardarEdicion() {

    var formulario = new FormData();
    formulario.append("id", $('#registroId').val());
    formulario.append("titulo", $('#titulo').val());
    formulario.append("descripcion", $('#descripcion').val());

    // Procesar
    getAjaxFormData(formulario, base_url + 'faq/actualizarRegistro').then(function (result) {

        if(isJson(result)) {

            var obj = JSON.parse(result);
            var result = obj.result;

            if (! result) {
                mostrarErrores(obj.errores);
                return false;
            }
                // Actualizar la tabla
        $('#tabla_faq').DataTable().ajax.reload();
        }else {
            console.log("error", "La acción no pudo ser realizada");
        }
          });
    // Cerrar el modal después de guardar cambios
    $('#editarRegistroModal').modal('hide');

}

function getAjaxFormData(form_data, url) {


    var call = $.ajax({
        type: "POST",
        url: url,
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        success: function () {}
    });

    return call;
}


function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
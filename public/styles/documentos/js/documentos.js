var tabla_doc = {
    id: "#tabla_doc",
    url: base_url + "documentos/getDocumentos",
};


$(document).ready(function () {
    cargarTablaDoc();
    //abrirModalEdicion(); // Invoca la función para abrir el modal de edición al hacer clic en "Editar"
});

function cargarTablaDoc() {
    cargarTabla(tabla_doc);
}

function cargarTabla(info) {
    $(info.id).DataTable({
        "processing": true,
        "serverSide": false,
        "pageLength": 3,
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
            { data: 'email' },
            { data: 'ruta_archivo' },
            { data: 'estado' },
            { data: 'nombre' },
            {
                data: 'id_documento',
                render: function (data, type, row) {
                    return '<a class="cacv-activar" href="#" data-id="' + data + '">Activar</a>';
                }
            }
        ],
        "language": {
            // Configuración de idioma, si es necesario
        }
    });
}


$(document).on("click", ".cacv-activar", function (e) {
    e.preventDefault();
    var id_documento = $(this).data('id');

    $.ajax({
        url: base_url + 'documentos/actualizarEstadoDocumento', // Asegúrate de reemplazar 'URL_para_actualizar_estado_del_documento' con tu URL real
        type: 'POST',
        data: { id_documento: id_documento },
        success: function(response) {
            console.log('id_documento', id_documento);
            console.log('Documento activado correctamente');
              // Destruir la tabla existente y luego volver a cargarla
            $('#tabla_doc').DataTable().destroy();
            cargarTabla(tabla_doc);
        },
        error: function(error) {
            console.error('Error al activar el documento');
        }
    });
});








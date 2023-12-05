var tabla_especialista = {
    id: "#tabla_usuarios",
    columnas: [
        {data: 'nombre'},
        {data: 'edad'},
        {data: 'nacionalidad'},
        {data: 'direccion'},
        {data: 'rut'},
        {data: 'comuna'},
        {data: 'region'},
        {data: 'genero'},
        {data: 'celular'},
        {data: 'puntuacion'},
        {data: 'educacion'},
        {data: 'tipo_consulta'},
        {data: 'ubicacion_consulta'},
        {data: 'estado'},
        {data: 'nestado'},
        {data: 'opciones'},

        
    ],
    lenguaje: [],
    sinorden: [3],
    invisible: [13],
    centrado: [1,2,3,15],
    order: [0,"asc"],
    rowReorder: false,
    
    url: base_url + "mantenedor/getEspecialistas",
    recordsTotal:0
};

var tabla_usuarios = {
    id: "#tabla_usuarios",
    columnas: [
        {data: 'nombre'},
        {data: 'edad'},
        {data: 'nacionalidad'},
        {data: 'direccion'},
        {data: 'rut'},
        {data: 'comuna'},
        {data: 'region'},
        {data: 'genero'},
        {data: 'celular'},
        {data: 'nombre'},
        {data: 'nombre'},
        {data: 'nombre'},
        {data: 'nombre'},
        {data: 'estado'},
        {data: 'nestado'},
        {data: 'opciones'},

        
    ],
    lenguaje: [],
    sinorden: [3],
    invisible: [9,10,11,12,13],
    centrado: [1,2,3,15],
    order: [0,"asc"],
    rowReorder: false,
    
    url: base_url + "mantenedor/getUsuarios",
    recordsTotal:0
};


var modal_cacv = "#modal-cacv";
var table;
var tipoUsuario;

var form_gestion_user = {

    id: null,
    nombre: $("#form-tipo-nombre"),
	estado: $("#form-filtro-estado-modal")

};


$(document).ready(function () {
    //cargar();
    cargarEventosModal();
    cargarOpcionesSelect();
    submit();
    filtrarEstado();
    cambioUsuario();

    

});


function cargar() {
    cargarTablacacv(tabla_especialista);
}

function cambioUsuario() {
    var tipoUsuarioInicial = $('#form-select-usuario').val();
    filtrarPorTipoUsuario(tipoUsuarioInicial);

    // Agregar un evento change al select para que la función se ejecute cuando cambie
    $('#form-select-usuario').on('change', function() {
        tipoUsuario = $(this).val();
        filtrarPorTipoUsuario(tipoUsuario);
    });
}



function filtrarEstado() {
    $('#form-select-estado').on('change', function() {
        var estadoSeleccionado = $(this).val();
        
        // Verifica si el valor seleccionado no es "todos"
        if (estadoSeleccionado !== 'Todos') {
            table.column(13).search(estadoSeleccionado).draw();
        } else {
            // Si es "todos", elimina el filtro y muestra todos los datos
            table.column(13).search('').draw();
        }
    });
}


function filtrarPorTipoUsuario(tipoUsuario) {
    if (tipoUsuario === '1') {
        // Si se seleccionó "Cliente", muestra la tabla de clientes y oculta la de especialistas
        $('#tablas').show();
        
        cargarTablacacv(tabla_usuarios); // Carga la tabla de clientes
    } else if (tipoUsuario === '2') {
        // Si se seleccionó "Especialista",     muestra la tabla de especialistas y oculta la de clientes
        
        $('#tablas').show();
        cargarTablacacv(tabla_especialista); // Carga la tabla de especialistas
    } else {
        // Si no se seleccionó nada o se seleccionó otra opción, muestra ambas tablas
        
        $('#tablas').hide();
        //cargarTablacacv(tabla_usuarios);
         // Carga la tabla de clientes por defecto
    }
}






function cargarTablacacv(info,tabla, mostarLoading) {
    var url = info.url;
    var data = info.data;

    //data[token_name] = token_hash;

 

   // data['cliente'] = cliente;

    if(mostarLoading){
        $(info.id).loading({message: 'Cargando información...'});
    }
    table = $(info.id).DataTable({
        "columns": info.columnas,
        "destroy": true,
        "processing": true,
        "serverSide": false,
        "paging": true,
        "bPaginate": true,
        "bLengthChange": true,
        "scrollX": true,
        "bFilter": true,
        "bInfo": true,
        "bAutoWidth": false,
        "ajax": {
            url: info.url,
            type: "POST",
            data: data,
            dataSrc: function (response) {
                
                return response.data; 
            },
            error: function (err, status) {
                _toastr("error", "No fue posible cargar los datos", true);
                $(info.id_tipo + '_processing').hide();
                // $('.box').hide();
                $(info.id).loading('stop');
            }
        },
        "initComplete": function(settings, json) {
            if(mostarLoading){
                $(info.id).loading('stop');
            }
          
        },
        order: [],
        "columnDefs": [
            {type: "spanish-string", targets: info.lenguaje},
            {"orderable": false, "targets": info.sinorden},
            {"bVisible": false, "aTargets": info.invisible},
            {"className": 'text-center', targets: info.centrado}
        ],
        "language": {
            "processing": '<i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>Cargando Datos...</span>',
            "sSearch": "",
            "sLengthMenu": "Mostrar _MENU_ &nbsp;&nbsp;&nbsp;&nbsp",
            "emptyTable": "No hay resultados disponibles",
            "info": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "",
            "infoFiltered": "(filtrado de _MAX_ resultados totales)",
            "sZeroRecords": "No hay resultados",
            "oPaginate": {
                "sNext": ">>",
                "sPrevious": "<<"
            }
        },
        "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
        "dom": '<"top"Bfrt> <"bottom" <"row" <"col-4" l><"col-4 text-center" i>  <"col-4" p>> >',
        
    });
    $('.dataTables_filter input').css("margin-right", "5px");
    $('.dataTables_processing').css("height", "50px");
    $('.dataTables_processing').css("z-index", "1");
    $('.dt-buttons').remove();
    $('.dataTables_filter input').attr("placeholder", "Buscar");
    $('.dataTables_filter input').attr("class", "form-control");

    
    
}



function cargarEventosModal() {

    $(document).off("click", ".cacv-agregar,.cacv-editar");

    $(document).on("click", " .cacv-agregar,.cacv-editar", function (e) {
        // Si el evento contiene un valor en el ID id, significa que se está editando la información.
        if ($(this).data("id")) {
            form_gestion_user.id = $(this).data("id");
            if (!$.isNumeric(form_gestion_user.id)) {
                _toastr("warning", "Opción no es válida. Intente nuevamente.", true);
                return false;
            }
            
            var formulario = new FormData();
            //formulario.append(token_name, token_hash);
            formulario.append("id", form_gestion_user.id);
            formulario.append("tipousuario", tipoUsuario);
            //formulario.append("cliente", cliente);


            getAjaxFormData(formulario, base_url + 'mantenedor/getDatosPorID').then(function (result) {
                if (isJson(result)) {
                    var obj = JSON.parse(result);
                    var result = obj.result;
                    $("#id").val(obj.id);
                    $("#form-tipo-nombre").val(obj.nombre);
                    $("#form-filtro-estado-modal").val(obj.estado);
                    //$("#idcliente").val(obj.idcliente);



                    // Muestra la modal y configura el título y el botón aquí, dentro de la función `then`.
                    $(modal_cacv).modal('show');
                    titulo = "Editar"
                    btn_texto = "Editar";
                    $(modal_cacv).find(".modal-title").text(titulo);
                    $(modal_cacv).find(".modal-submit").text(btn_texto);
                }
            });
        } else {

            


            //PENDIENTE ------------<>>>>>>>>>>>>>>>>
            //resetForm();
            $(modal_cacv).modal('show');
            //titulo = "Ingresar " +tablaSeleccionada.toString().toLowerCase();
            btn_texto = "Ingresar";
            $(modal_cacv).find(".modal-title").text('titulo');
            $(modal_cacv).find(".modal-submit").text(btn_texto);
        }
     });
    
}


    // $('#modal-cacv').on('hidden.bs.modal', function (e) {
    //     // Coloca aquí la acción que deseas ejecutar cuando el modal se cierra
        
    //     clienteseleccionado = undefined;
    //     // Desmarca todas las opciones dentro del select
        
    //     selector2.val('').selectpicker('refresh');
    //     // Puedes realizar otras acciones aquí, como reiniciar formularios o actualizar datos.
    // });





//}




function submit(){

    $("#formulario-cacv").off("submit");

    $("#formulario-cacv").on("submit", function (e) {

        e.preventDefault();

        //$(modal_rol).find(".modal-submit").prop("disabled", true);

        // Armar formulario
        //idclientes = $("#idcliente").val();
        //clienteseleccionado = $("#form-filtro-estado-modal").val();

        


        var formulario = new FormData();
        //formulario.append(token_name, token_hash);
        //formulario.append("cliente", cliente);
        //formulario.append("idcliente", idclientes);
        //formulario.append("clienteseleccionado", clienteseleccionado);
        var bandera_id = false;

        
    
        formulario.append("form-tipo-nombre",form_gestion_user.nombre.val());
        formulario.append("form-filtro-estado-modal", form_gestion_user.estado.val());

        

        if (form_gestion_user.id) {
            formulario.append("id", form_gestion_user.id);
            bandera_id = true;
    
        }

        

        
            
        //console.log(form_gestion_cacv.nombre.val());

        /*if(form_gestion_cacv.destino.val()===null){
        _toastr("error", "El origen es obligatorio", true);
        return;
        }*/

        //accionProcesar = "procesar_cacv"

        

        if (tipoUsuario == 1) {
             accionProcesar = "procesar_cliente";
         } else if (tipoUsuario == 2) {
             accionProcesar = "procesar_especialista";}
        // } else if (tablaSeleccionada == "Cargos") {
        //     accionProcesar = "procesar_cacv";
        // } else if (tablaSeleccionada == "Vicepresidencias") {
        //     accionProcesar = "procesar_vices";
        // } else if (tablaSeleccionada == "Jornadas") {
        //     accionProcesar = "procesar_jornada";
        // } else {
            
        //     return; // Salir del evento si la tabla no es reconocida
        // }


        // Procesar
        getAjaxFormData(formulario, base_url + 'mantenedor/'+ accionProcesar).then(function (result) {

            if(isJson(result)) {

                var obj = JSON.parse(result);
                var result = obj.result;

                if (! result) {
                    mostrarErrores(obj.errores);
                    return false;
                }
                if (bandera_id) {
                    
                    console.log("success", "Actualizado exitosamente");
                } else {
                    console.log("success", "Registrado exitosamente");
                }


                $(tabla_especialista.id).DataTable().ajax.reload();
                // $(tabla_muestra_roles.id).DataTable().order([1, 'asc']).draw();
                $(modal_cacv).modal("hide");

            }else {
                console.log("error", "La acción no pudo ser realizada");
            }


        });
        



    });


}


function cargarOpcionesSelect() {
    // Obtener el select por su ID
    var selectEstado = $('#form-filtro-estado-modal');
    
    // Hacer una solicitud AJAX para obtener los datos desde tu controlador PHP
    $.ajax({
        url: 'mantenedor/getEstado', // Reemplaza 'tu_controlador_php' con la URL correcta de tu controlador
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            // Limpiar las opciones actuales del select
            selectEstado.empty();
            
            // Agregar una opción por defecto (si lo deseas)
            selectEstado.append('<option value="">Seleccione estado</option>');
            
            // Iterar sobre los datos y agregar opciones al select
            $.each(data.data, function (index, estado) {
                selectEstado.append('<option value="' + estado.id + '">' + estado.estado + '</option>');
            });
        },
        error: function (error) {
            // Manejar el error, por ejemplo, mostrar un mensaje de error
            console.error('Error al cargar datos: ' + error);
        }
    });
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
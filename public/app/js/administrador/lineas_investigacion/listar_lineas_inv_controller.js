$(document).ready(function(){
    
	$(".custom-scrollbar").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	
	alertify.defaults.theme.input = "form-control";
	alertify.defaults.theme.ok = "btn btn-primary";
    alertify.defaults.theme.cancel = "btn btn-default";
});

sgpi_app.run(function(DTDefaultOptions) {
    
    DTDefaultOptions.setLanguage({
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "0 de 0, sin registros a mostrar",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    });
});

sgpi_app.controller('listar_lineas_inv_controller', function($scope, $http, Alertify, DTOptionsBuilder, DTColumnDefBuilder){
    
    // configuración de datatable
    $scope.dtOptions = {
        paginationType: 'full_numbers',
        displayLength: 5,
        hasBootstrap: true,
        lengthMenu: [[5, 10, 20], [5, 10, 20]]
        };
    $scope.dtColumnDefs = [
        DTColumnDefBuilder.newColumnDef(1).notSortable(),
        DTColumnDefBuilder.newColumnDef(2).notSortable()
    ];
    
    $scope.visibilidad = {
        show_velo_msj_operacion : true
    };
    $scope.data = {
        msj_operacion: 'Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i>'  
    };
    
    /*
    |--------------------------------------------------------------------------
    | ajax inicial
    |--------------------------------------------------------------------------
    | Solicita datos de la GUI necesarios iniciales
    */
    $http({
            method: 'GET',
            url: '/lineas_investigacion/data_inicial_vista_listar',
        })
        .success(function(data){
            console.log(data);
            if(data.consultado !== undefined && data.consultado == 1){
                $scope.data.lineas_investigacion = data.lineas_investigacion;
                $scope.visibilidad.show_velo_msj_operacion = false;
            }
            else{
                console.log('Error en la carga de datos iniciales, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                $scope.data.msj_operacion = 'Error en la carga de datos iniciales, código de error: ' + data.codigo;  
                Alertify.error('Error en la carga de datos iniciales, código de error: ' + data.codigo);
                $scope.data.msj_operacion = 'Error en la carga de datos iniciales, código de error: ' + data.codigo;
                $scope.visibilidad.show_velo_msj_operacion = true;
            }
        })
        .error(function(data, status) {
            console.log(data);
            Alertify.error('Error en la carga de datos iniciales, código de error: ' + status);
            $scope.data.msj_operacion = 'Error en la carga de datos iniciales, código de error: ' + status; 
            $scope.visibilidad.show_velo_msj_operacion = true;
        });
        
    /*
    |--------------------------------------------------------------------------
    | agregar_nueva_linea()
    |--------------------------------------------------------------------------
    | Agrega lineas de investigación enviando peticiones ajax
    | se comprueba primero desde servidor si el nombre no se encuentra repetido
    */
    $scope.agregar_nueva_linea = function(){
        
        $scope.data.msj_operacion = '<h3 class="text-center">Creando línea...<i class="fa fa-circle-o-notch fa-spin fa-fw"></i></h3>';
        $scope.visibilidad.show_velo_msj_operacion = true;
        $http({
           method: 'POST',
            url: '/lineas_investigacion/crear',
            params: {
                nombre: $scope.data.nueva_linea_inv
            }
        })
        .success(function(data) {
            if(data.consultado == 1){
                // crea nuevo obj de linea y lo agrega a la coleccion de lineas
                // para presentar la linea en datatable
                var nueva_linea = {
                    id : data.id,
                    nombre : $scope.data.nueva_linea_inv
                };
                $scope.data.lineas_investigacion.push(nueva_linea);
                Alertify.success('Línea de investigación creada');
                $scope.data.nueva_linea_inv = null;
            }
            else{
                // un error desde servidor
                console.log(data);
                Alertify.error('Un error ha ocurrido. ' + data.mensaje);
            }
        })
        .error(function(data, status) {
            // error no esperado
            console.log(data);
            Alertify.error('Un error inesperado a ocurrido en el registro de la línea de investigación');
        })
        .finally(function() {
            $scope.visibilidad.show_velo_msj_operacion = false;
        });
    };
    
    /*
    |--------------------------------------------------------------------------
    | editar_linea_inv()
    |--------------------------------------------------------------------------
    | Edita el nombre de una línea de investigación, enviando la edicion por ajax
    | permitiendo al servidor validar la edición.
    */    
    $scope.editar_linea_inv = function(linea_investigacion){
        /*
         * @title {String or DOMElement} The dialog title.
         * @message {String or DOMElement} The dialog contents.
         * @value {String} The default input value. 
         * @onok {Function} Invoked when the user clicks OK button.
         * @oncancel {Function} Invoked when the user clicks Cancel button or closes the dialog.
         *
         * alertify.prompt(title, message, value, onok, oncancel);
         * 
         */
        alertify.prompt( 
            'Editar línea de investigación', 
            'Editar nombre de línea', 
            linea_investigacion.nombre,
            function(evt, value) { 
                // antes de enviar la solicitud ajax para guardar la edición de la línea de investigación, se valida que no se encuentre vacío el nombre ingresado
                if(value.length == undefined || value.trim().length == 0){
                    alertify.error('Valor incorrecto');
                    return;
                }
                // el valor no está vacío, se envia ajax para validar y guardar edicion
                $scope.data.msj_operacion = '<h3 class="text-center">Guardando edición...<i class="fa fa-circle-o-notch fa-spin fa-fw"></i></h3>';
                $scope.visibilidad.show_velo_msj_operacion = true;
                $http({
                    method: 'POST',
                    url: '/lineas_investigacion/guardar_edicion',
                    params:{
                        id: linea_investigacion.id,
                        nombre: value
                    }
                })
                .success(function(data){
                    console.log(data);
                    if(data.consultado !== undefined && data.consultado == 1){
                        // se editó la línea de investigación. Se cambia el valor del modelo que correspone a la línea para reflejar los cambios
                        linea_investigacion.nombre = value;
                        Alertify.success('Línea de investigación editada');
                    }
                    else{
                        // El nombre editado ya existe u ocurrió un error en el servidor
                        console.log(data);
                        Alertify.error('Un error ha ocurrido. ' + data.mensaje);
                    }
                })
                .error(function(data, status) {
                    // Un error no esperado
                    console.log(data);
                    Alertify.error('Un error inesperado a ocurrido en la edición de la línea de investigación');
                })
                .finally(function(){
                    $scope.visibilidad.show_velo_msj_operacion = false;
                });
            },
            function() { 
                // controlador de evento de cancelar prompt
            })
            .set('labels', {ok:'Editar', cancel:'Cancelar'})
            .set('closable', false);
    };
    
    /*
    |--------------------------------------------------------------------------
    | eliminar_linea_inv()
    |--------------------------------------------------------------------------
    | Elimina una línea de investigación enviado petición ajax
    */    
    $scope.eliminar_linea_inv = function(linea_investigacion){
        
        /*
         * @message {String or DOMElement} The dialog contents.
         * @onok {Function} Invoked when the user clicks OK button.
         * @oncancel {Function} Invoked when the user clicks Cancel button or closes the dialog.
         *
         *  alertify.confirm(message, onok, oncancel);
         *
         */
        alertify.confirm(
            'Confirmar eliminación de la línea de investigación "<strong>' + linea_investigacion.nombre + '</strong>"', 
            function(){  
                // confirma eliminar
                $scope.data.msj_operacion = '<h3 class="text-center">Eliminando línea...<i class="fa fa-circle-o-notch fa-spin fa-fw"></i></h3>';
                $scope.visibilidad.show_velo_msj_operacion = true;
                $http({
                    method: 'GET',
                    url: '/lineas_investigacion/eliminar',
                    params: {
                        id: linea_investigacion.id
                    }
                })
                .success(function(data) {
                    if(data.consultado == 1){
                        for (var i = 0; i < $scope.data.lineas_investigacion.length; i++) {
                            if($scope.data.lineas_investigacion[i].id == linea_investigacion.id){
                                $scope.data.lineas_investigacion.splice(i, 1);
                                break;
                            }
                        }
                        Alertify.success('Línea de investigación eliminada');
                    }
                    else{
                        // Error de servidor
                        console.log(data);
                        Alertify.error('Un error ha ocurrido. ' + data.mensaje);
                    }
                })
                .error(function(data, status){
                    // Un error no esperado
                    console.log(data);
                    Alertify.error('Un error inesperado a ocurrido en la eliminación de la línea de investigación');
                })
                .finally(function(){
                    $scope.visibilidad.show_velo_msj_operacion = false;
                });
            }, 
            function(){ /* no eliminar */ }
            )
            .set('closable', false)
            .set('labels', {ok:'Eliminar', cancel:'Cancelar'})
            .setHeader('Eliminar línea de investigación'); ;
    };
});
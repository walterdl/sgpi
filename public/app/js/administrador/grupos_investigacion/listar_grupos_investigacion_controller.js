$(document).ready(function(){
    
    // $(".to-scroll").mCustomScrollbar();
	$(".custom-scrollbar").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
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

sgpi_app.controller('listar_grupos_investigacion_controller', function($scope, $http, $window, Alertify, DTOptionsBuilder){
    
    $scope.dtOptions = {
        paginationType: 'full_numbers',
        displayLength: 5,
        hasBootstrap: true,
        language: {
            "sEmptyTable": 'Sin grupos de investigación para la sede seleccionada'
            },
        lengthMenu: [[5, 10, 20], [5, 10, 20]],
        };
    
    $scope.visibilidad = {
        mostrar_velo: false,
        mostrar_mensaje_operacion: true,
        mostrar_grupos_investigacion: false,
        mostrar_mensaje_operacion_lineas_inv: true,
        mostrar_lineas_inv: false,
        show_sin_grupos: false
    };
    $scope.data = {
        mensaje_operacion: 'Seleccionar una sede',
        mensaje_operacion_lineas_inv: 'Seleccione un grupo de investigación',
        titulo_panel_lineas_investigacion: 'Seleccione un grupo de investigación'
    };
    
    $scope.window_resize = function(){
        if($window.innerWidth < 992){
            $('#btn_registrar_grupo').css('margin-top', '5px');
            $('#sede_select').css('max-width', 'initial')
            $scope.establecer_btn_block = true;
        }
        else{
            $('#sede_select').css('max-width', '350px')
            var height_col_sede = $('#col_sede').height() - 15;
            var height_registrar_grupo = $('#btn_registrar_grupo').outerHeight(false);
            var margin_top = height_col_sede - height_registrar_grupo;
            $('#btn_registrar_grupo').css('margin-top', margin_top + 'px');
            $scope.establecer_btn_block = false;
        }            
            
    };
    
    $(document).ready(function(){
        if($scope.notify_operacion_previa !== null && $scope.notify_operacion_previa !== undefined){
            if($scope.notify_operacion_previa)
                Alertify.success($scope.mensaje_operacion_previa);
            else{
                Alertify.error('Un error ha ocurrido. Codigo de estado: ' + $scope.codigo_error_operacion_previa);
                console.log('Un error ha ocurrido en la operación previa, detalles: ' + $scope.mensaje_operacion_previa + ', cod ' + $scope.codigo_error_operacion_previa);
            }
        }
        $($window).bind('resize', $scope.window_resize);
        $scope.window_resize();
        
        var BreakException = {};
        try{
            $scope.data.sedes.forEach(function(item) {
                if(item.nombre == 'Cali'){
                    $scope.data.sede = item;    
                    $scope.cambiaSede();
                    throw BreakException;
                }
            });
        }
        catch(e){
            if (e !== BreakException) throw e;
        }
        
    }); 
    
    $scope.cambiaSede = function(){
        
        $scope.predeterminar_panel_lineas_inv();
        $scope.visibilidad.mostrar_velo = true;
        $scope.visibilidad.mostrar_grupos_investigacion = false;
        if($scope.data.sede){
            $http({
                    method: 'GET',
                    url: '/grupos/grupos_investigacion_x_sede',
                    params: {
                        id_sede: $scope.data.sede.id,
                    }
                })
                .success(function(data){
                    if(data.length){
                        $scope.visibilidad.mostrar_mensaje_operacion = false;
                        $scope.visibilidad.mostrar_grupos_investigacion = true;
                        $scope.data.grupos_investigacion = data;
                    }
                    else{
                        $scope.data.grupos_investigacion = [];
                        // var sede_seleccionada = $('option.sede[value="'+ $scope.data.sede +'"]').text();
                        // $scope.data.mensaje_operacion = 'No hay grupos de investigación registrados para la sede ' + sede_seleccionada;
                        // $scope.visibilidad.mostrar_mensaje_operacion = true;
                    }
                })
                .error(function(data, status) {
                    Alertify.error('Un error ha ocurrido. Codigo de estado: ' + status);
                    $scope.data.mensaje_operacion = 'Un error ha ocurrido. Codigo de estado: ' + status;
                    $scope.visibilidad.mostrar_mensaje_operacion = true;
                    console.log('Error consultando los grupos de investigacion de la sede');
                    console.log(data);
                })
                .finally(function() {
                    $scope.visibilidad.mostrar_velo = false;
                });
        }
    };
    
    $scope.btn_ver_lineas_inv_click = function(grupo_investigacion){
        
        $scope.data.titulo_panel_lineas_investigacion = 'Cargando lineas de investigación';
        $scope.visibilidad.mostrar_cargando_lineas_inv = true;
        $scope.visibilidad.mostrar_mensaje_operacion_lineas_inv = false;
        $scope.visibilidad.mostrar_lineas_inv = false;
        
        $http({
                method: 'GET',
                url: '/grupos/lineas_investigacion_x_grupo_inv',
                params: {
                    id_grupo_investigacion: grupo_investigacion.id
                }
            })
            .success(function(data){
                if(data.length){
                    $scope.data.lineas_investigacion = data;
                    $scope.visibilidad.mostrar_lineas_inv = true;
                }
                else{
                    $scope.visibilidad.mostrar_mensaje_operacion_lineas_inv = true;
                    $scope.data.mensaje_operacion_lineas_inv = 'El grupo seleccionado no tiene líneas de investigación';
                }
                $scope.data.titulo_panel_lineas_investigacion = 'Líneas de investigación - ' + grupo_investigacion.nombre_grupo_investigacion;
            })
            .error(function(data, status) {
                Alertify.error('Error consultando las líneas de investigación. Estado: ' + status);
                $scope.data.titulo_panel_lineas_investigacion = 'Error';
                $scope.visibilidad.mostrar_mensaje_operacion_lineas_inv = true;
                $scope.data.mensaje_operacion_lineas_inv = 'Error consultando las líneas de investigación. Estado: ' + status;
                console.log('Error consultando las líneas de investigación. Estado: ' + status);
                console.log(data);
            })
            .finally(function(){
                $scope.visibilidad.mostrar_cargando_lineas_inv = false;
                // enfoca al contenedor de líneas de investigación
                var element = $("#div_lineas_inv");
                element.css('outline', 'none !important').attr("tabindex", -1).focus();
            });
    };
    
    $scope.predeterminar_panel_lineas_inv = function(){
        
        $scope.visibilidad.mostrar_cargando_lineas_inv = false;
        $scope.visibilidad.mostrar_mensaje_operacion_lineas_inv = true;
        $scope.visibilidad.mostrar_lineas_inv = false;
        $scope.data.mensaje_operacion_lineas_inv = 'Seleccione un grupo de investigación';
        $scope.data.titulo_panel_lineas_investigacion = 'Seleccione un grupo de investigación';
    };
    
    $scope.btn_eliminar_grupo_inv_click = function(grupo_investigacion){
        /*
        Un grupo de investigacion se puede eliminar si:
        -No tiene usuarios cuyo grupo de investigación sea el que se desee eliminar
        */
        // se envia solicitud ajax para realizar la validacion citada anteriormente
        $http({
                    method: 'GET',
                    url: 'grupos/validar_eliminacion_grupo_inv',
                    params: {
                        id: grupo_investigacion.id,
                    }
                })
                .success(function(data){
                    if(data.length){
                        $scope.visibilidad.mostrar_mensaje_operacion = false;
                        $scope.visibilidad.mostrar_grupos_investigacion = true;
                        $scope.data.grupos_investigacion = data;
                    }
                    else{
                        var sede_seleccionada = $('option.sede[value="'+ $scope.data.sede +'"]').text();
                        $scope.data.mensaje_operacion = 'No hay grupos de investigación registrados para la sede ' + sede_seleccionada;
                        $scope.visibilidad.mostrar_mensaje_operacion = true;
                    }
                })
                .error(function(data, status) {
                    Alertify.error('Un error ha ocurrido. Codigo de estado: ' + status);
                    $scope.data.mensaje_operacion = 'Un error ha ocurrido. Codigo de estado: ' + status;
                    $scope.visibilidad.mostrar_mensaje_operacion = true;
                    console.log('Error consultando los grupos de investigacion de la sede');
                    console.log(data);
                })
                .finally(function() {
                    $scope.visibilidad.mostrar_velo = false;
                });
    };
    
});
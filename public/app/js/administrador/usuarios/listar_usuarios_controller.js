$(document).ready(function(){
    
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

sgpi_app.controller('listar_usuarios_controller', function($scope, $http, Alertify, notify_operacion_previa, mensaje_operacion_previa){
    
    
    $(document).ready(function() {
        if(notify_operacion_previa){
            var delay = alertify.get('notifier','delay');
            alertify.set('notifier','delay', 0);
            
            if(notify_operacion_previa == 'success')
                alertify.success(mensaje_operacion_previa);
                
            else if(notify_operacion_previa == 'error'){
                alertify.error(mensaje_operacion_previa);
                console.log(mensaje_operacion_previa);
            }
            alertify.set('notifier','delay', delay);
        }
    });

    $scope.dtOptions = {
    paginationType: 'full_numbers',
    displayLength: 5,
    hasBootstrap: true,
    language: {
        "sEmptyTable": 'No existen Usuarios'
        },
    lengthMenu: [[5, 10, 20], [5, 10, 20]],
    };

    $scope.data = {
        msj_operacion_mas_info_usuario: 'Seleccione un usuario para ver más información',
        btn_mas_info_seleccionado: false,
        detalles_usuario: ''
    };
    
    $scope.visibilidad = {
        show_cargando_mas_info_usuario: false,
        show_operacion_mas_info_usuario: true,
        show_col_detalles_usuario: false,
        btn_cambiarEstado:true,
        show_cargando_cambiarEstado:false,
        show_tabla_usuario:false
    };
    
    
    listar();

    function listar() {
        $scope.visibilidad.show_tabla_usuario=true;
        $http({
                method: 'GET',
                url: '/usuarios/datos_iniciales_listar_usuarios'
            })
            .success(function(data){
                
                console.log(data);
                
                if(data.consulta == 1)
                {
                    $scope.data.usuarios = data.usuarios;
                    
                }else{
                    
                    $scope.visibilidad.show_tabla_usuario=false;
                    Alertify.error('Error al cargar los usurios, código de error: ' + status);
                    console.log('Error en inhabilitar usurio, código de error: ' + status);
                }
                
                
            })
            .error(function(data, status) {
                
                $scope.visibilidad.show_tabla_usuario=false;
                Alertify.error('Error al cargar los usurios, código de error: ' + status);
                console.log('Error en inhabilitar usurio, código de error: ' + status);
            })
            .finally(function(){
                console.log("finalizo");
                $scope.visibilidad.show_tabla_usuario=false;
            });
            
    }

    // cambia el estado del usuario
    $scope.btn_cambiar_estado= function(usuario,cambio){
        // $scope.visibilidad.show_cargando_mas_info_usuario = true;
        
        
        if(cambio == 1){
            estado="Habilitar";
        }else{
            estado="Inhabilitar";
        }
        
        alertify.confirm('Desea '+estado+' el usuario?', 
        function(){ // cuando acepta cambiar el esatdo del usuario
            
                $scope.visibilidad.btn_cambiarEstado=false;
                $scope.visibilidad.show_cargando_cambiarEstado=true;
                
                $http({
                        method: 'GET',
                        url: '/usuarios/cambiarEstado',
                        params: {
                            id_usuario: usuario.id_usuario 
                        }
                    })
                    .success(function(data){
                        console.log(data);
                        bandera=0;
                        datos=data;
                        
                        if(data.consultado == 1){
                            // $scope.data.info_usuario = data.info_usuario[0];
                            console.log("si consulto");
                        }
                        else{
                            //$scope.visibilidad.show_operacion_mas_info_usuario = false;
                            console.log('Error en inhabilitar usurio, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                            Alertify.error('Error en inhabilitar usurio, código de error: ' + data.codigo);
                            bandera=1;
                        }
                    })
                    .error(function(data, status){
                        
                        //$scope.visibilidad.show_operacion_mas_info_usuario = false;
                        Alertify.error('Error en inhabilitar usurio, código de error: ' + status);
                        console.log('Error en inhabilitar usurio, código de error: ' + status);
                        bandera=1;
                        
                    })
                    .finally(function() {
                        
                            $scope.visibilidad.btn_cambiarEstado=true;
                            $scope.visibilidad.show_cargando_cambiarEstado=false;
                        
                            if(bandera == 0){
                                alertify.success('Se a '+datos.cambio+' el Usuario');
                            
                                if(usuario.id_estado ==  2){
                                    usuario.id_estado =1;
                                    usuario.nombre_estado ="Activo";
                                }else{
                                    usuario.id_estado =2;
                                    usuario.nombre_estado ="Inactivo";
                                }
                            }
                        
                    });
            
        }).set('oncancel', function(closeEvent){ 
            //canccela la operacion cambia estado del usuario seleccionado
            alertify.error('Cancel');
        } ); 
            
    }
    
    
    // carga información detallada del usuario
    $scope.btn_mas_info_usuario_click = function(id_usuario){
        
        if(!$scope.data.btn_mas_info_seleccionado)
            $scope.data.btn_mas_info_seleccionado = true;
        
        $scope.visibilidad.show_cargando_mas_info_usuario = true;
        $scope.visibilidad.show_operacion_mas_info_usuario = false;
        $http({
                method: 'GET',
                url: '/usuarios/mas_info_usuario',
                params: {
                    id_usuario: id_usuario 
                }
            })
            .success(function(data){
                console.log(data);
                if(data.consultado !== undefined && data.consultado !== null && data.consultado == 1){
                    $scope.data.info_usuario = data.info_usuario[0];
                    $scope.visibilidad.show_col_detalles_usuario = true;
                    if($scope.data.info_usuario.id_rol == 1){
                        $scope.visibilidad.show_col_detalles_usuario = false;
                    }
                    else if($scope.data.info_usuario.id_rol == 2){
                        $scope.data.detalles_usuario = '\
                        <p class="text-left">Sede origen: <strong>' + $scope.data.info_usuario.nombre_sede  + '</strong></p>\
                        <p class="text-left">Grupo de investigación: <strong>' + $scope.data.info_usuario.nombre_grupo_inv + '</strong></p>\
                        ';
                    }
                    else if($scope.data.info_usuario.id_rol == 3){
                        $scope.data.detalles_usuario = '\
                        <p class="text-left">Sede origen: <strong>' + $scope.data.info_usuario.nombre_sede  + '</strong></p>\
                        <p class="text-left">Grupo de investigación: <strong>' + $scope.data.info_usuario.nombre_grupo_inv + '</strong></p>\
                        <p class="text-left">Categoría de investigador: <strong>' + $scope.data.info_usuario.categoria_investigador + '</strong></p>\
                        ';
                    }
                }
                else{
                    
                    $scope.visibilidad.show_operacion_mas_info_usuario = false;
                    
                    console.log('Error en la carga de más información del usuario, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                    Alertify.error('Error en la carga de más información del usuario, código de error: ' + data.codigo);
                
                    
                }
            })
            .error(function(data, status){
                
                $scope.visibilidad.show_operacion_mas_info_usuario = false;
                $scope.data.msj_operacion_mas_info_usuario = 'Error en la carga de más información del usuario, código de error: ' + data.codigo;
                Alertify.error('Error en la carga de más información del usuario, código de error: ' + status);
                console.log('Error en la carga de más información del usuario, código de error: ' + status);
                console.log(data);
            })
            .finally(function() {
                $scope.visibilidad.show_cargando_mas_info_usuario = false;
                // Simula enfoque a div que contiene la respuesta de más info de usuario
                var element = $("#mas_info_boxbody");
                element.css('outline', 'none !important').attr("tabindex", -1).focus();
            });
    };
    
    $scope.btn_editar=function(id){

        console.log(id);
        $http({
            method: 'GET',
            url: '/usuarios/datos/editar',
            params: {
                id_usuario: id 
            }
        })
        .success(function(data){
            
            console.log(data);
            if(data.consultado == 1 ){
                $scope.data.usuarioEditar=data.usuario;
            }
            else{
                console.log('Error en la carga de datos, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                Alertify.error('Error en la carga de datos, código de error: ' + data.codigo);
            }
        })
        .error(function(data, status) {
            Alertify.error('Error en la carga de datos, código de error: ' + status);
            console.log('Error en la carga de datos');
            console.log(data);
        })
        .finally(function() {
            location.href='usuarios/editar/'+id;
        });
        
        
        
    }
    
});
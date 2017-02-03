$(document).ready(function(){
    
    // aplica scrollbar personalizado a la tabla de usuarios
	$(".custom-scrollbar").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
// 	aplica efecto zoom a la foto de perfil 
    $('.foto_perfil').magnificPopup({
        type:'image',
        removalDelay: 300,
        mainClass: 'mfp-fade',
        zoom: {
            enabled: true, 
            duration: 300, 
            easing: 'ease-in-out', 
            opener: function(openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }            
    });	     	
});

sgpi_app.run(function(DTDefaultOptions) {
    
    DTDefaultOptions.setLanguage({
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Sin usuarios",
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
        btn_mas_info_seleccionado: false,
        msj_mas_info_usuario: '',
        mas_info_usuario: null
    };
    
    $scope.visibilidad = {
        btn_cambiarEstado: true,
        show_cargando_cambiarEstado: false,
        show_tabla_usuario: false,
        show_velo_mas_info_usuario: false
    };
    
    listar();

    function listar() {
        $scope.visibilidad.show_tabla_usuario = true;
        $http({
                method: 'GET',
                url: '/usuarios/datos_iniciales_listar_usuarios'
            })
            .success(function(data){
                console.log(data);
                if(data.consulta == 1)
                {
                    $scope.data.usuarios = data.usuarios;
                }
                else{
                    $scope.visibilidad.show_tabla_usuario=false;
                    Alertify.error('Error al cargar los usurios, código de error: ' + data.codigo);
                    console.log('Error en inhabilitar usurio, código de error: ' + data.codigo);
                }
            })
            .error(function(data, status) {
                
                $scope.visibilidad.show_tabla_usuario=false;
                Alertify.error('Error al cargar los usurios, código de error: ' + status);
                console.log('Error en inhabilitar usurio, código de error: ' + status);
            })
            .finally(function(){
                console.log("finalizo");
                $scope.visibilidad.show_tabla_usuario = false;
            });
    }

    // cambia el estado del usuario
    $scope.btn_cambiar_estado = function(usuario){
        // $scope.visibilidad.show_cargando_mas_info_usuario = true;
        
        var cambio = usuario.id_estado == 1 ? 2 : 1;
        var estado = null;
        if(cambio == 1)
        {
            estado = "habilitar";
        }
        else
        {
            estado = "inhabilitar";
        }

        alertify.confirm('Habilitar / deshabilitar usuario', 'Desea '+estado+' el usuario?', 
        function()
        { 
            // cuando acepta cambiar el esatdo del usuario
            
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
        },
        function () {
            // no hacer nada si cancela 
        })
        .set('labels', {ok:'Confirmar', cancel:'Cancelar'});
    };
    
    
    // carga información detallada del usuario
    $scope.btn_mas_info_usuario_click = function(id_usuario){
        
        $scope.data.msj_mas_info_usuario = '<h4 class="text-center">Cargando más información del usuario...<i class="fa fa-circle-o-notch fa-spin"></i></h4>';
        $scope.visibilidad.show_velo_mas_info_usuario = true;
        $http({
                method: 'GET',
                url: '/usuarios/mas_info_usuario',
                params: {
                    id_usuario: id_usuario 
                }
            })
            .success(function(data){
                console.log(data);
                if(data.consultado == 1){
                    $scope.data.mas_info_usuario = data.info_usuario;
                    $scope.visibilidad.show_velo_mas_info_usuario = false;
                }
                else{
                    console.log(data);
                    $scope.data.msj_mas_info_usuario = '<h4 class="text-center">Error en la carga de más información del usuario, código de error: ' + data.codigo + '</h4>';
                    Alertify.error('Error en la carga de más información del usuario, código de error: ' + data.codigo);
                }
            })
            .error(function(data, status){
                console.log(data);
                $scope.data.msj_mas_info_usuario = '<h4 class="text-center">Error XHR o de servodor en la carga de más información del usuario, código de estado: ' + status + '</h4>';
                Alertify.error('Error XHR o de servodor en la carga de más información del usuario, código de estado: ' + status);
            })
            .finally(function() {
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
    };
    
});
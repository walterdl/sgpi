// aplica estilo a la barra de desplazamiento de datatable de proyecto
$(document).ready(function(){
    
	$(".custom-scrollbar").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	
	// aplica propiedad "append to body" a las opciones del dropdown para evitar problemas de overflow
	// respuesta stackoverflow:
	//http://stackoverflow.com/questions/31029300/how-to-append-a-single-dropdown-menu-to-body-in-bootstrap
    (function () {
        
        console.log('Dentro de funcion anonima');
        
        // hold onto the drop down menu                                             
        var dropdownMenu;
    
        // and when you show it, move it to the body                                     
        $(window).on('show.bs.dropdown', function (e) {
    
            // grab the menu        
            dropdownMenu = $(e.target).find('.dropdown-menu');
    
            // detach it and append it to the body
            $('body').append(dropdownMenu.detach());
    
            // grab the new offset position
            var eOffset = $(e.target).offset();
    
            // make sure to place it where it would normally go (this could be improved)
            dropdownMenu.css({
                'display': 'block',
                    'top': eOffset.top + $(e.target).outerHeight(),
                    'left': eOffset.left
            });
        });
    
        // and when you hide it, reattach the drop down, and hide it normally                                                   
        $(window).on('hide.bs.dropdown', function (e) {
            $(e.target).append(dropdownMenu.detach());
            dropdownMenu.hide();
        });
    })();	
	
});

// COnfigura el idioma de tatable de proyectos
sgpi_app.run(function(DTDefaultOptions) {
    
    DTDefaultOptions.setLanguage({
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Sin proyectos",
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

sgpi_app.controller('listar_proyectos_controller', function($scope, $http, $log, id_usuario, DTOptionsBuilder){
    
    $(document).ready(function() {
        $('a[href="#contenido_tab_proyectos"]').on('shown.bs.tab', function (e) {
            $scope.visibilidad.show_mas_info_proyecto = true;
            $scope.$apply();
        });
    });
    
    // presenta box de mas info de usuario desde un inicio ya que inicialmente la pestaña de selecciónd eproyecto s eencuentra seleccionada
    $scope.visibilidad.show_mas_info_proyecto = true;
    
    // configura datatable de proyectos
    $scope.dtOptions = {
        paginationType: 'full_numbers',
        displayLength: 5,
        hasBootstrap: true,
        lengthMenu: [[5, 10, 20], [5, 10, 20]]
    };
    
    // presenta velo de aviso de cargando datos iniciales
    $scope.data.msj_operacion = '<h3 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $scope.visibilidad.show_velo_msj_operacion = true;
    
    // consulta inicial por los proyectos del investigador principal
    $http({
        url: '/proyectos/proyectos_investigador_principal',
        method: 'GET',
        params:{
            id_usuario: id_usuario
        }
    })
    .success(function (data) {
        $log.log(data);
        if(data.consultado == 1){
            $scope.data.proyectos = data.proyectos;
            $scope.visibilidad.show_velo_msj_operacion = false;
        }
        else{
            $scope.data.msj_operacion = '<h3 class="text-center">Error al consultar los datos iniciales. Código de error: ' + data.codigo + '</h3>';
        }
    })
    .error(function (data, status) {
        $log.log(data);
        $scope.data.msj_operacion = '<h3 class="text-center">Error al consultar los datos iniciales. Código de estado: ' + status + '</h3>';
    });
    
	/*
	|--------------------------------------------------------------------------
	| productos()
	|--------------------------------------------------------------------------
	| Presenta la pestaña de carga de documentos de productos del proyecto seleccionado
	| También genera el evento de "opción de carga de documentos de productos seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/    
    $scope.productos = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('cargar_docs_productos_seleccionado');
        $('a[href="#contenido_tab_productos"]').tab('show');
        $scope.visibilidad.show_mas_info_proyecto = false;
    };
    
	/*
	|--------------------------------------------------------------------------
	| gastos()
	|--------------------------------------------------------------------------
	| Presenta la pestaña de ngastos del proyecto seleccionado
	| Genera el evento de "gastos_seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/        
    $scope.gastos = function(id_proyecto){
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('gastos_seleccionado');
        $('a[href="#contenido_tab_gastos"]').tab('show');
        $scope.visibilidad.show_mas_info_proyecto = false;
    };
    
	/*
	|--------------------------------------------------------------------------
	| informe_avance()
	|--------------------------------------------------------------------------
	| Presenta la pestaña de informe de avance del proyecto seleccionado
	| Genera el evento de "informe_avance_seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/            
    $scope.informe_avance = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('informe_avance_seleccionado');
        $('a[href="#contenido_tab_informe_avance"]').tab('show');
        $scope.visibilidad.show_mas_info_proyecto = false;        
    };
    
	/*
	|--------------------------------------------------------------------------
	| final_proyecto()
	|--------------------------------------------------------------------------
	| Presenta la pestaña de final de proyecto del proyecto seleccionado
	| Genera el evento de "final_proyecto_seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/                
    $scope.final_proyecto = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('final_proyecto_seleccionado');
        $('a[href="#contenido_tab_final_proyecto"]').tab('show');
        $scope.visibilidad.show_mas_info_proyecto = false;                
    };
    
	/*
	|--------------------------------------------------------------------------
	| prorroga()
	|--------------------------------------------------------------------------
	| Presenta la pestaña de prorroga de final de proyecto
	| Genera el evento de "prorroga_seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/            
    $scope.prorroga = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('prorroga_seleccionado');
        $('a[href="#contenido_tab_prorroga"]').tab('show');
        $scope.visibilidad.show_mas_info_proyecto = false;                        
    };
    
	/*
	|--------------------------------------------------------------------------
	| mas_info()
	|--------------------------------------------------------------------------
	| Genera el evento de "mas_informacion_seleccionado"
	| Copia el id del proyecto seleccionado a data
	| Usado para que se consulte los detalles del proyecto y se presenten en el box de mas informacion
	*/                
    $scope.mas_info = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('mas_informacion_seleccionado');        
    };
});
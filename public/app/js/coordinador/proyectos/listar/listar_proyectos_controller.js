// aplica estilo a la barra de desplazamiento de datatable de proyecto
$(document).ready(function(){
    
	$("#contenedor_proyectos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	$("#contenedor_objetivos_especificos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
	$("#contenedor_entidades_grupos_inv").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	                	
	$("#contenedor_participantes").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	 
	$("#contenedor_productos_x_tipo").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	                			
	$("#contenedor_entidades_fuente_pres").mCustomScrollbar({
		axis:"y",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: false
	});	                				
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

sgpi_app.controller('listar_proyectos_controller', function($scope, $http, $log) {
	
    $(document).ready(function() {
        $('a[href="#contenido_tab_proyectos"]').on('shown.bs.tab', function (e) {
            $scope.visibilidad.show_mas_info_proyecto = true;
            $scope.$apply();
        });
    });    
    
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
        url: '/proyectos/proyectos_coordinador',
        method: 'GET',
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
	| También genera el evento "productos_seleccionado"
	| Copia el id del proyecto seleccionado a data
	*/    
    $scope.productos = function(id_proyecto) {
        $scope.data.id_proyecto = id_proyecto;
        $scope.$broadcast('productos_seleccionado');
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
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
    
    $scope.show_mas_info_proyecto = true;
    
    $(document).ready(function() {
        $('a[href="#contenido_tab_proyectos"]').on('shown.bs.tab', function (e) {
            $scope.show_mas_info_proyecto = true;
            $scope.$apply();
        });
    });
    
    $scope.dtOptions = {
        paginationType: 'full_numbers',
        displayLength: 5,
        hasBootstrap: true,
        lengthMenu: [[5, 10, 20], [5, 10, 20]]
    };
    
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
        $scope.show_mas_info_proyecto = false;
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
        $scope.show_mas_info_proyecto = false;
    };
});
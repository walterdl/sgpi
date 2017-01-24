sgpi_app.controller('gastos_controller', function ($scope, $http) {
    
    $scope.show_velo_msj_operacion = true;
    $scope.msj_operacion = '<h3 class="text-center">Seleccionar proyecto</h3>';
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la pestaña de gastos
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los gastos del proyecto identificado en data.id_proyecto
	*/            
	$scope.$on('gastos_seleccionado', function (event) {
        $scope.show_velo_msj_operacion = true;
        $scope.msj_operacion = '<h3 class="text-center">Cargando gastos del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
        $http({
            url: '/proyectos/gastos_de_proyecto',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
                $scope.data.gastos = data.gastos;
                $scope.show_velo_msj_operacion = false;
            }
            else if(data.consultado == 2){
                // devolver aquí a pestaña de proyectos con mensaje de error
                $('a[href="#contenido_tab_proyectos"]').tab('show');
                alertify.error('Error al consultar los gastos del proyecto. Código de error: ' + data.codigo);
            }
        })
        .error(function(data, status) {
            console.log(data);
            $('a[href="#contenido_tab_proyectos"]').tab('show');
            alertify.error('Error al consultar los gastos del proyecto. Código de estado: ' + status);            
        });	    
	});
	
	
	/*
	|--------------------------------------------------------------------------
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando los gastos
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.data.gastos = [];
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
    
});
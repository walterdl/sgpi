sgpi_app.controller('informe_avance_controller', function ($scope, $http) {
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la pestaña de informe de avance
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los gastos del proyecto identificado en data.id_proyecto
	*/            
	$scope.$on('informe_avance_seleccionado', function (event) {
	    $scope.data.pestania_actual = 'informe_avance';
        $scope.consultar_informe_avance('primera_consulta');
	});
	
	/*
	|--------------------------------------------------------------------------
	| consultar_informe_avance
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por el informe de avance del proyecto seleccionado
	| Recibe como parámetro una especificacion que permite identificar si se trata de la primera carga del informe o una recarga
	| En caso de alguna falla retorna a pestaña de seleccion de proyectos ejecutando alertify informado
	*/    	
	$scope.consultar_informe_avance = function(tipo_operacion) {
	    
        
	    if(tipo_operacion == 'primera_consulta')
            $scope.msj_operacion = '<h3 class="text-center">Cargando estado de revisión de informe de avance...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        else if(tipo_operacion == 'recarga')
            $scope.msj_operacion = '<h3 class="text-center">Actualizando estado de revisión de informe de avance...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
            
        $scope.show_velo_msj_operacion = true;
        
        $http({
            url: '/proyectos/informe_avance',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
                $scope.informe_avance = data.informe_avance;
                $scope.fecha_mitad_proyecto = data.fecha_mitad_proyecto;
                
                // formatea valor de aprobado a un valor booleano correcto
                if($scope.informe_avance)
                    if($scope.informe_avance.aprobado == 1)
                        $scope.informe_avance.aprobado = true;
                    else if($scope.informe_avance.aprobado == 0)
                        $scope.informe_avance.aprobado = false;
                        
                $scope.show_velo_msj_operacion = false;
            }
            else{
                alertify.error('Error al consultar el estado de revisión del informe de avance. Código de error: ' + data.codigo);
                $scope.volver_a_proyectos();
            }
        })
        .error(function(data, status) {
            console.log(data);
            alertify.error('Error XHR o de servidor al consultar el estado de revisión del informe de avance. Código de estado: ' + status);            
            $scope.volver_a_proyectos();
        });
	};
	
	/*
	|--------------------------------------------------------------------------
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando el informe de avance
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.informe_avance = null;
        $scope.data.pestania_actual = null;
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
sgpi_app.controller('prorroga_controller', function ($scope, $http, Upload) {
	
	/*
	|--------------------------------------------------------------------------
	| resetear_modelos()
	|--------------------------------------------------------------------------
	| Establece valor de modelos de manera que mla interfaz se limpie o no refleje datos antiguos
	| Se usa cuando se refresca el contenido de la pestaña de final de proyecto
	*/             	
    $scope.resetear_modelos = function(){

		$scope.cargando_revision = false;
		$scope.show_barra_progreso = false;
		$scope.casi_terminado = false;
		$scope.total_archivo = 0;
		$scope.carga_actual = 0;
		$scope.porcentaje_carga = 0;
		$scope.comentario_revision = null;
    };	
    
    // cuando se ejecuta este controlador, se limpian los modelos para presentar una interfaz limpia
    $scope.prorroga = null;
    $scope.resetear_modelos();
	
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la opción de prorroga de final de proyecto
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de final de proyecto del proyecto
	| justo cuando se selecciona la opción de final de proyecto desde la pestaña de proyectos
	*/            
	$scope.$on('prorroga_seleccionado', function (event) {
	    $scope.data.pestania_actual = 'prorroga_proyecto';
        $scope.consultar_prorroga('primera_consulta');
	});	
	
	/*
	|--------------------------------------------------------------------------
	| consultar_prorroga()
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de prórroga de final de proyecto del proyecto
	| Recibe como parámetro una especificacion que permite identificar si se trata de la primera carga o una recarga
	| En caso de alguna falla retorna a pestaña de seleccion de proyectos ejecutando alertify informado
	*/    	
	$scope.consultar_prorroga = function(tipo_operacion) {
	    
	    if(tipo_operacion == 'primera_consulta')
            $scope.msj_operacion = '<h3 class="text-center">Cargando estado de revisión de prórroga de  final de proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        else if(tipo_operacion == 'recarga')
            $scope.msj_operacion = '<h3 class="text-center">Actualizando estado de revisión de prórroga de final de proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
            
        $scope.show_velo_msj_operacion = true;
        
        $http({
            url: '/proyectos/prorroga',
            mehtod: 'GET',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
                
                $scope.prorroga = data.prorroga;
                if($scope.prorroga != null)
                {
                    if(data.prorroga.aprobado == 1)
                        $scope.prorroga.aprobado = true;
                    else if(data.prorroga.aprobado == 0)
                        $scope.prorroga.aprobado = false;
                }
            	$scope.show_velo_msj_operacion = false;
            }
            else{
                alertify.error('Error al consultar el estado de revisión de la prórroga de final de proyecto. Código de error: ' + data.codigo);
                $scope.volver_a_proyectos();
            }
        })
        .error(function(data, status) {
            console.log(data);
            alertify.error('Error XHR o de servidor al consultar el estado de la prórroga de final de proyecto. Código de estado: ' + status);            
            $scope.volver_a_proyectos();
        });	    	    
	};	
	
	/*
	|--------------------------------------------------------------------------
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando los datos de final de proyecto
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.resetear_modelos();
        $scope.prorroga = null;
        $scope.data.pestania_actual = null;
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
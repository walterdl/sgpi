sgpi_app.controller('mas_info_proyecto_controller', function ($scope, $http){
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento 'mas_informacion_seleccionado'
	| Detecta cuando la opción de más información de proyecto se ha sleccionado
	| Ejecuta consulta ajax por la información detallada del proyecto
	|--------------------------------------------------------------------------
	*/        
    $scope.$on('mas_informacion_seleccionado', function (event) {
        $http({
            url: '/proyectos/mas_info_proyecto'
            method: 'GET',
            params: {
                'id_proyecto': $scope.data.id_proyecto
            }
        })
        .success(function(data){
            console.log('success callback');
            console.log(data);
        })
        .error(function(data, status){
            alertify.error('error callback');
            console.log(data);            
        });
    });
});

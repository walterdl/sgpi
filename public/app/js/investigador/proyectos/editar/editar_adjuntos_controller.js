sgpi_app.controller('editar_adjuntos_controller', function ($scope, $http, id_proyecto, acta_inicio, presentacion_proyecto, presupuesto) {
    
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando datos del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>'; 
    $scope.visibilidad.show_velo_general = true;    
    
    $scope.acta_inicio = acta_inicio;
    $scope.presentacion_proyecto = presentacion_proyecto;
    $scope.presupuesto = presupuesto;
    
    $scope.visibilidad.show_velo_general = false;
    $scope.data.msj_operacion_general = '';
    
    $scope.editar_presupuesto = false;
    $scope.editar_presentacion_proyecto = false;
    $scope.editar_acta_inicio = false;
    
    $http({
        url: '/proyectos/info_basica_proyecto',
        method: 'GET',
        params: {
            id_proyecto: id_proyecto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            $scope.info_proyecto = data.informacion_proyecto;
        }
        else
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error al consultar los datos del proyecto. Código de error: ' + data.codigo + '</h3>'; 
    })
    .error(function(data, status) {
        console.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error al consultar los datos del proyecto. Código de estado: ' + status + '</h3>';         
    });
    
	/*
	|--------------------------------------------------------------------------
	| validar_documento_presupuesto()
	|--------------------------------------------------------------------------
	| Valida el archivo de presupuesto de proyecto cargado
	*/      
    $scope.validar_documento_presupuesto = function(){
        var validacion = true;
        
        if(!$scope.documento_presupuesto)
            alertify.error('Archivo de presupuesto de proyecto inválido. Tamaño máximo de archivo de 20 MB');
        else
            validacion = false;
        
        $scope.documento_presupuesto_invalido = validacion;
        return validacion;
    };
    
	/*
	|--------------------------------------------------------------------------
	| validar_documento_presentacion_proyecto()
	|--------------------------------------------------------------------------
	| Valida el archivo de presentación de proyecto cargado
	*/          
    $scope.validar_documento_presentacion_proyecto = function() {
        console.log('en validar_documento_presentacion_proyecto()');
        console.log($scope.documento_presentacion_proyecto);
        var validacion = true;
        
        if(!$scope.documento_presentacion_proyecto)
            alertify.error('Archivo de presentación de proyecto inválido. Tamaño máximo de archivo de 20 MB');
        else
            validacion = false;
        
        $scope.documento_presentacion_proyecto_invalido = validacion;
        return validacion;        
    };
    
	/*
	|--------------------------------------------------------------------------
	| validar_documento_acta_inicio()
	|--------------------------------------------------------------------------
	| Valida el archivo de acta de inicio de proyecto cargado
	*/              
    $scope.validar_documento_acta_inicio = function() {
        var validacion = true;
        
        if(!$scope.documento_acta_inicio)
            alertify.error('Archivo de acta de inicio de proyecto inválido. Tamaño máximo de archivo de 20 MB');
        else
            validacion = false;
        
        $scope.documento_acta_inicio_invalido = validacion;
        return validacion;           
    };    
    
    // ejecuta envío de formulario validando primero los archivos cargados
    $scope.guardar = function() {
        
        var validacion = false;
        
        if(!$scope.editar_presupuesto && !$scope.editar_presentacion_proyecto && !$scope.editar_acta_inicio)
        {
            alertify.error('Cargar al menos un documento nuevo');
            return;
        }
        
        if($scope.editar_presupuesto)
            validacion |= $scope.validar_documento_presupuesto();
        if($scope.editar_presentacion_proyecto)
            validacion |= $scope.validar_documento_presentacion_proyecto();
        if($scope.editar_acta_inicio)
            validacion |= $scope.validar_documento_acta_inicio();
            
        if(validacion)
        {
            // algun documento es inválido
            alertify.error('Los documentos del proyecto no son válidos');
            return;
        }        
        alertify.success('Cargando documentos');
        
        $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando documentos, puede tardar un tiempo si los archivos son grandes...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>'; 
        $scope.visibilidad.show_velo_general = true;
        $('#input_registrar_proyecto').trigger('click');
    };
});
sgpi_app.controller('prorroga_controller', function($scope, $http, Upload) {

	/*
	|--------------------------------------------------------------------------
	| resetear_modelos()
	|--------------------------------------------------------------------------
	| Establece valor de modelos de maner aque mla interfaz se limpie o no refleje datos antiguos
	| Se usa cuando se refresca el contenido de la pestaña de final de proyecto
	*/             	
    $scope.resetear_modelos = function(){

        $scope.prorroga = null;
        $scope.archivo_prorroga = null;
		$scope.nombre_archivo_prorroga = null;
		$scope.comentario_investigador = null;
		$scope.cargando_archivos = false;
		$scope.show_barra_progreso = false;
		$scope.casi_terminado = false;
		$scope.total_archivo = 0;
		$scope.carga_actual = 0;
		$scope.porcentaje_carga = 0;
		$scope.comentario_revision = null;
		$scope.archivo_prorroga_invalido = false;
		$scope.deshabilitar_btn_retorno_proyectos = false;
    };	
	$scope.resetear_modelos();
	
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la opción de prórroga de proyecto
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de prórroga del proyecto
	| justo cuando se selecciona la opción de prórroga desde la pestaña de proyectos
	*/            
	$scope.$on('prorroga_seleccionado', function (event) {
	    $scope.data.pestania_actual = 'prorroga_proyecto';
        $scope.consultar_prorroga('primera_consulta');
	});
	
	/*
	|--------------------------------------------------------------------------
	| consultar_prorroga()
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de prórroga del proyecto
	| Recibe como parámetro una especificacion que permite identificar si se trata de la primera carga o una recarga
	| En caso de alguna falla retorna a pestaña de seleccion de proyectos ejecutando alertify informado
	*/    	
	$scope.consultar_prorroga = function(tipo_operacion) {
	    
        $scope.show_velo_msj_operacion = true;
        
	    if(tipo_operacion == 'primera_consulta')
            $scope.msj_operacion = '<h3 class="text-center">Cargando estado de revisión de la prórroga...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        else if(tipo_operacion == 'recarga')
            $scope.msj_operacion = '<h3 class="text-center">Actualizando estado de revisión de la prórroga...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        
        $http({
            url: '/proyectos/prorroga',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
            	if(data.prorroga != null){
	                $scope.prorroga = data.prorroga;
	                $scope.comentario_investigador = data.prorroga.comentario_investigador;
	                $scope.comentario_revision = data.prorroga.comentario_revision;
	                $scope.nombre_archivo_prorroga = data.prorroga.archivo;
            	}
            	$scope.show_velo_msj_operacion = false;
            }
            else{
                alertify.error('Error al consultar el estado de revisión de la prórroga de la proyecto. Código de error: ' + data.codigo);
                $scope.volver_a_proyectos();
            }
        })
        .error(function(data, status) {
            console.log(data);
            alertify.error('Error XHR o de servidor al consultar el estado de revisión de la prórroga del proyecto. Código de estado: ' + status);            
            $scope.volver_a_proyectos();
        });	    	    
	};		
	
	/*
	|--------------------------------------------------------------------------
	| validar_documento()
	|--------------------------------------------------------------------------
	| Valida el archivo de acta de finalización y memoria académica
	*/    	
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        if($scope.archivo_prorroga == null || $scope.archivo_prorroga == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.archivo_prorroga_invalido = true;
            return true;
        }
        
        $scope.archivo_prorroga_invalido = false;
        return false;
    };    			
	
	/*
	|--------------------------------------------------------------------------
	| cargar_prorroga()
	|--------------------------------------------------------------------------
	| Carga archivos asincronamete validando antes que se hallan cargado los archivos correctos
	*/    	
    $scope.cargar_prorroga = function() {

        // si el archivo es invlaido se cancela operación
        if($scope.validar_documento())
            return;
            
        $scope.deshabilitar_btn_retorno_proyectos = true;
        $scope.total_archivo = $scope.archivo_prorroga.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_archivos = true;
        
        var data_upload = {
            archivo: $scope.archivo_prorroga,
            id_proyecto: $scope.data.id_proyecto
        };
        if($scope.comentario_investigador != null)
            data_upload['comentario'] = $scope.comentario_investigador;
        
        // Hace uso del servicio Upload que ngFile proporciona
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_prorroga',
            method: 'POST',
            data: data_upload
        });

        // realiza seguimiento de envío a travez del objeto promise
        $scope.upload_service.then(function (response) {
            console.log(response); // impirme respuesta de servidor para propósitos debug
            if(response.data.consultado == 1){
                alertify.success('Prorroga de final de proyecto cargada');
                $scope.resetear_modelos();
                $scope.consultar_prorroga('recarga');
            }
            else{
                alertify.error('Error al cargar archivos de la prórroga. Código de error: ' + response.data.codigo);            
                $scope.volver_a_proyectos();
            }
        }, function (response) {
            console.log(response);
            alertify.error('Error XHR o de servidor al cargar archivos de la prórroga. Código de estado: ' + response.status);            
            $scope.volver_a_proyectos();
        }, function (evt) {
            // Realiza cáclulo de porcentaje de carga
            $scope.carga_actual = evt.loaded;
            $scope.porcentaje_carga = Math.min(100, parseInt(100.0 * 
                                     evt.loaded / evt.total));

            if($scope.carga_actual >= $scope.total_archivo)
                $scope.casi_terminado = true;
        });        
    };               		
	
	/*
	|--------------------------------------------------------------------------
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando los datos de prorroga
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.resetear_modelos();
        $scope.data.pestania_actual = null;
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
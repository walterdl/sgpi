sgpi_app.controller('final_proyecto_controller', function ($scope, $http, Upload) {
	
	/*
	|--------------------------------------------------------------------------
	| resetear_modelos()
	|--------------------------------------------------------------------------
	| Establece valor de modelos de maner aque mla interfaz se limpie o no refleje datos antiguos
	| Se usa cuando se refresca el contenido de la pestaña de final de proyecto
	*/             	
    $scope.resetear_modelos = function(){

        $scope.fecha_final_proyecto = null;
        $scope.final_proyecto = null;
        $scope.archivo_acta_finalizacion = null;
		$scope.archivo_memoria_academica = null;
		$scope.documento_acta_finalizacion = null;
		$scope.documento_memoria_academica = null;
		$scope.comentario_investigador = null;
		$scope.cargando_archivos = false;
		$scope.show_barra_progreso = false;
		$scope.casi_terminado = false;
		$scope.total_archivo = 0;
		$scope.carga_actual = 0;
		$scope.porcentaje_carga = 0;
		$scope.comentario_revision = null;
		$scope.documento_acta_finalizacion_invalido = false;
		$scope.documento_memoria_academica_invalido = false;
		$scope.deshabilitar_btn_retorno_proyectos = false;
    };	
    
    // cuando se ejecuta este controlador, se limpian los modelos para presentar una interfaz limpia
    $scope.resetear_modelos();
	
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la opción de final de proyecto
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de final de proyecto del proyecto
	| justo cuando se selecciona la opción de final de proyecto desde la pestaña de proyectos
	*/            
	$scope.$on('final_proyecto_seleccionado', function (event) {
        $scope.consultar_final_proyecto('primera_consulta');
	});	
	
	/*
	|--------------------------------------------------------------------------
	| consultar_final_proyecto()
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los registros de final de proyecto del proyecto
	| Recibe como parámetro una especificacion que permite identificar si se trata de la primera carga o una recarga
	| En caso de alguna falla retorna a pestaña de seleccion de proyectos ejecutando alertify informado
	*/    	
	$scope.consultar_final_proyecto = function(tipo_operacion) {
	    
        $scope.show_velo_msj_operacion = true;
        
	    if(tipo_operacion == 'primera_consulta')
            $scope.msj_operacion = '<h3 class="text-center">Cargando estado de revisión de final de proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        else if(tipo_operacion == 'recarga')
            $scope.msj_operacion = '<h3 class="text-center">Actualizando estado de revisión de final de proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
        
        $http({
            url: '/proyectos/final_proyecto',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
            	if(data.final_proyecto != null){
	                $scope.final_proyecto = data.final_proyecto;
	                $scope.comentario_investigador = data.final_proyecto.comentario_investigador;
	                $scope.archivo_acta_finalizacion = data.final_proyecto.archivo_acta_finalizacion;
					$scope.archivo_memoria_academica = data.final_proyecto.archivo_memoria_academica;
            	}
                $scope.fecha_final_proyecto = data.fecha_final_proyecto;
            	$scope.show_velo_msj_operacion = false;
            }
            else{
                alertify.error('Error al consultar el estado de revisión de final de proyecto. Código de error: ' + data.codigo);
                $scope.volver_a_proyectos();
            }
        })
        .error(function(data, status) {
            console.log(data);
            alertify.error('Error XHR o de servidor al consultar el estado de revisión de final de proyecto. Código de estado: ' + status);            
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
        
        var resultado_validacion = false;
        // valida acta de finalización
        if($scope.documento_acta_finalizacion == null || $scope.documento_acta_finalizacion == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_acta_finalizacion_invalido = true;
            resultado_validacion |= true;
        }
        else
        {
            $scope.documento_acta_finalizacion_invalido = false;
            resultado_validacion |= false;
        }
        
        // valida memoria académica
        if($scope.documento_memoria_academica == null || $scope.documento_memoria_academica == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_memoria_academica_invalido = true;
            resultado_validacion |= true;
        }
        else
        {
            $scope.documento_memoria_academica_invalido = false;
            resultado_validacion |= false;
        }        
        
        return resultado_validacion;
    };    		
	
	/*
	|--------------------------------------------------------------------------
	| cargar_final_proyecto()
	|--------------------------------------------------------------------------
	| Carga archivos asincronamete validando antes que se hallan cargado los archivos correctos
	*/    	
    $scope.cargar_final_proyecto = function() {

        // si algun archivo es invlaido se cancela operación
        if($scope.validar_documento())
            return;
            
        $scope.deshabilitar_btn_retorno_proyectos = true;
        $scope.total_archivo = $scope.documento_acta_finalizacion.size + $scope.documento_memoria_academica.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_archivos = true;
        
        // Hace uso del servicio Upload que ngFile proporciona
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_final_proyecto',
            method: 'POST',
            data: {
                archivo_acta_finalizacion: $scope.documento_acta_finalizacion,
                archivo_memoria_academica: $scope.documento_memoria_academica,
                id_proyecto: $scope.data.id_proyecto,
                comentario: $scope.comentario_investigador
            }
        });

        // realiza seguimiento de envío a travez del objeto promise
        $scope.upload_service.then(function (response) {
            console.log(response); // impirme respuesta de servidor para propósitos debug
            if(response.data.consultado == 1){
                $scope.resetear_modelos();
                $scope.consultar_final_proyecto('recarga');
            }
            else{
                alertify.error('Error al cargar archivos de final de proyecto. Código de error: ' + response.data.codigo);            
                $scope.volver_a_proyectos();
            }
        }, function (response) {
            console.log(response);
            alertify.error('Error XHR o de servidor al cargar archivos de final de proyecto. Código de estado: ' + response.status);            
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
	| Retorna a pestaña de selecciónde proyectos eliminando los datos de final de proyecto
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.resetear_modelos();
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
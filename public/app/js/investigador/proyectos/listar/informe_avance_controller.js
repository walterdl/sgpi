sgpi_app.controller('informe_avance_controller', function ($scope, Upload, $http) {
    
    $scope.resetear_modelos = function(){

        $scope.data.deshabilitar_btn_retorno_proyectos = false;
        $scope.show_barra_progreso = false;
        $scope.cargando_doc = false;
        $scope.total_archivo = 0;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.nombre_archivo = null;
        $scope.comentario_investigador = null;
    };
    
    
    $scope.resetear_modelos();
    
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
                $scope.comentario_investigador = data.informe_avance != null ? data.informe_avance.comentario_investigador : null;
                $scope.comentario_revision = data.informe_avance != null ? data.informe_avance.comentario_revision : null;
                $scope.nombre_archivo = data.informe_avance != null ? data.informe_avance.archivo : null;
                $scope.show_velo_msj_operacion = false;
                console.log('$scope.comentario_investigador: ' + $scope.comentario_investigador);
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
	| validar_documento
	|--------------------------------------------------------------------------
	| Valida el archivo de informe de avance cargado
	*/    	
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        if($scope.documento_informe_avance == null || $scope.documento_informe_avance == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_invalido = true;
            return true;
        }
        $scope.documento_invalido = false;
        return false;
    };    	
    
	/*
	|--------------------------------------------------------------------------
	| cargar_informe_avance
	|--------------------------------------------------------------------------
	| Carga archivo asincronamete validando antes que se halla cargado archivo válido
	*/    	
    $scope.cargar_informe_avance = function() {

        console.log('$scope.comentario_investigador: ' + $scope.comentario_investigador);
        
        // si el documento es inválido se cancela operación de carga
        if($scope.validar_documento())
            return;
        
        $scope.data.deshabilitar_btn_retorno_proyectos = true;
        $scope.total_archivo = $scope.documento_informe_avance.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_doc = true;
        
        // Hace uso del servicio Upload que ngFile proporciona
        var data_upload = {
            archivo: $scope.documento_informe_avance,
            id_proyecto: $scope.data.id_proyecto
        };
        if($scope.comentario_investigador != null) 
            data_upload['comentario'] = $scope.comentario_investigador;
            
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_informe_avance',
            method: 'POST',
            data: data_upload
        });

        // realiza seguimiento de envío a travez del objeto promise
        $scope.upload_service.then(function (response) {
            console.log(response); // impirme respuesta de servidor para propósitos debug
            var data = response.data;
            if(data.consultado == 1){
                alertify.success('Informe de avance cargado');
                $scope.resetear_modelos();
                $scope.consultar_informe_avance('recarga');
            }
            else{
                alertify.error('Error al cargar archivo de informe de avance. Código de estado: ' + data.codigo);            
                $scope.volver_a_proyectos();
            }
        }, function (response) {
            console.log(response);
            alertify.error('Error XHR o de servidor. Código de estado: ' + response.status);            
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
	| Retorna a pestaña de selecciónde proyectos eliminando el informe de avance
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.informe_avance = null;
        $scope.data.pestania_actual = null;
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
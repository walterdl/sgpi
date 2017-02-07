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
	| validar_documento()
	|--------------------------------------------------------------------------
	| Valida el archivo de aprobación de final de proyecto
	*/    	
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        // valida archivo de aprobación
        // se establece com obligatorio el archivo si se esta aprobando la revisión
        if(!$scope.prorroga.aprobado) {
            $scope.documento_aprobacion_invalido = false;
            return false;
        }
        
        if($scope.documento_aprobacion == null || $scope.documento_aprobacion == undefined) {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_aprobacion_invalido = true;
            return true;
        }
        
        $scope.documento_aprobacion_invalido = false;
        return false;
    };    		
	
	/*
	|--------------------------------------------------------------------------
	| cambia_estado_aprobado()
	|--------------------------------------------------------------------------
	| Cuando se cambia la selección del checkbox de estado de aprobación, se llama funcion de validar documento 
	| para remover o presentar estado inválido del archivo de aprobación
	*/    		
	$scope.cambia_estado_aprobado = function() {
	    $scope.validar_documento();
	};
	
	/*
	|--------------------------------------------------------------------------
	| cargar_final_proyecto()
	|--------------------------------------------------------------------------
	| Carga archivos asincronamete validando antes que se hallan cargado los archivos correctos
	*/    	
    $scope.cargar_revision = function() {

        // si se trata de una aprobación se utiliza el servicio Upliad de ngf-file ya que se carga un archivo
        // si no se trata de una aprobación se utiliza servicio http de angular común
        if($scope.prorroga.aprobado){
            
            // Se valida documento de aprobación. Esto impide la operación si el archivo es incorrecto
            if($scope.validar_documento()) return;
                
            $scope.total_archivo = $scope.documento_aprobacion.size;
            $scope.carga_actual = 0;
            $scope.porcentaje_carga = 0;
            $scope.cargando_revision = true;
            
            $scope.upload_service = Upload.upload({
                url: '/proyectos/guardar_revision_prorroga',
                method: 'POST',
                data: {
                    id_proyecto: $scope.data.id_proyecto,
                    comentario_revision: $scope.prorroga.comentario_revision,
                    aprobado: $scope.prorroga.aprobado,
                    archivo: $scope.documento_aprobacion
                }
            });
    
            // realiza seguimiento de envío a travez del objeto promise
            $scope.upload_service.then(function (response) {
                console.log(response); // imprime respuesta de servidor para propósitos debug
                if(response.data.consultado == 1){
                    alertify.success('Revisión de prórroga final de proyecto realizada');
                    $scope.resetear_modelos();
                    $scope.consultar_prorroga('recarga');
                }
                else{
                    var delay = alertify.get('notifier','delay');
                    alertify.set('notifier','delay', 0);
                    alertify.error('Error al cargar archivos de prórroga de final de proyecto. Código de error: ' + response.data.codigo);
                    alertify.set('notifier','delay', delay);                
                    $scope.resetear_modelos();
                }
            }, function (response) {
                console.log(response);
                var delay = alertify.get('notifier','delay');
                alertify.set('notifier','delay', 0);
                alertify.error('Error XHR o de servidor al cargar archivos de prórroga de final de proyecto. Código de estado: ' + response.status);            
                alertify.set('notifier','delay', delay);                
                $scope.resetear_modelos();            
                
            }, function (evt) {
                // Realiza cáclulo de porcentaje de carga
                $scope.carga_actual = evt.loaded;
                $scope.porcentaje_carga = Math.min(100, parseInt(100.0 * 
                                         evt.loaded / evt.total));
    
                if($scope.carga_actual >= $scope.total_archivo)
                    $scope.casi_terminado = true;
            });        
        }
        else{
            
            $scope.msj_operacion = '<h3 class="text-center">Cargando revisión de prórroga final de proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';	    
            $scope.show_velo_msj_operacion = true;
            
            $http({
                url: '/proyectos/guardar_revision_prorroga',
                method: 'POST',
                params: {
                    id_proyecto: $scope.data.id_proyecto,
                    comentario_revision: $scope.prorroga.comentario_revision,
                    aprobado: $scope.prorroga.aprobado                    
                }
            })
            .success(function(data) {
                console.log(data);
                if(data.consultado == 1){
                    alertify.success('Revisión de prórroga de final de proyecto realizada');
                    $scope.resetear_modelos();
                    $scope.consultar_prorroga('recarga');                    
                }
                else{
                    var delay = alertify.get('notifier','delay');
                    alertify.set('notifier','delay', 0);
                    alertify.error('Error al cargar archivos de prórroga de final de proyecto. Código de error: ' + data.codigo);
                    alertify.set('notifier','delay', delay);                
                    $scope.resetear_modelos();
                    $scope.show_velo_msj_operacion = false;
                }
            })
            .error(function(data, status) {
                console.log(data);
                var delay = alertify.get('notifier','delay');
                alertify.set('notifier','delay', 0);
                alertify.error('Error XHR o de servidor al cargar archivos de prórroga de final de proyecto. Código de estado: ' + status);            
                alertify.set('notifier','delay', delay);                
                $scope.resetear_modelos(); 
                $scope.show_velo_msj_operacion = false;
            });
        }
    
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
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
});
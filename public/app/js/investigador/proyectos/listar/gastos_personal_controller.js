sgpi_app.controller('gastos_personal_controller', function($scope, $uibModal){

	/*
	|--------------------------------------------------------------------------
	| detalles_participante()
	|--------------------------------------------------------------------------
	| Ejecuta modal que presenta los detalles del participante
	*/             
    $scope.detalles_participante = function(gasto_personal){
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_mas_info_participante.html',
            controller: 'modal_mas_info_participante_controller',
            size: 'lg',
            scope: $scope,
            keyboard: true,
            resolve:{
                gasto_personal: function() {
                    return gasto_personal;
                }
            }
        });
    };
    
	/*
	|--------------------------------------------------------------------------
	| desembolso()
	|--------------------------------------------------------------------------
	| Ejecuta modal de carga de desembolos y visualización de revisión
	*/         
    $scope.desembolso = function(gasto_personal) {
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_desembolso_personal.html',
            controller: 'modal_desembolso_personal_controller',
            size: 'lg',
            scope: $scope,
            keyboard: false,
            backdrop: 'static',
            resolve:{
                gasto_personal: function() {
                    return gasto_personal;
                }
            }
        });        
        
        // captura los eventos de cierre de modal, determinando su causa para mostrar un alertify
        modalInstance.result.then(function(resultado){
            // El modal retorno una respuesta close. Se evalúa el éxito de la operacion de carga
            if(resultado.exito){
                alertify.success('Documento cargado');
            }
            else{
                alertify.error('Error al cargar documento. Código de error: ' + resultado.codigo + ', ' + resultado.mensaje);
            }
        }, function(resultado){
            // El modal retorno una respuesta dismiss- Se evalúa si canceló una carga
            if(resultado != undefined)
            {
                if(resultado.carga_cancelada)
                    alertify.error('Carga cancelada');
            }
        });         
    };
    
	/*
	|--------------------------------------------------------------------------
	| abre_cierra_acordion()
	|--------------------------------------------------------------------------
	| Simple controlador de evento para click de anchor que abre o cierra acordion. 
	| Establece el icono glyphicon adecuado al acordion
	*/     
    $scope.abre_cierra_acordion = function(id_acordion) {
        if($('#contenido_gastos_personal').hasClass('in')){
            $('#contenido_gastos_personal').parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#contenido_gastos_personal').parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };    
    
});

/*
|--------------------------------------------------------------------------
| modal_fecha_proyectada_radicacion_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta los detalles de un gasto de personal
*/              
sgpi_app.controller('modal_mas_info_participante_controller', function ($scope, $uibModalInstance, gasto_personal) {
    
    $scope.gasto_personal = gasto_personal;
    console.log(gasto_personal);
    $('#contenedor_detalles_participante').mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});
});

/*
|--------------------------------------------------------------------------
| modal_desembolso_personal_controller
|--------------------------------------------------------------------------
| Modal para la carga de desembolso de gasto de personal y vista de revisión
*/              
sgpi_app.controller('modal_desembolso_personal_controller', function ($scope, $http, $uibModalInstance, Upload, gasto_personal){
    
    $scope.gasto_personal = gasto_personal;
    $scope.msj_operacion = '<h4 class="text-center">Cargado estado de revisión de desembolso...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo = true;
    $scope.show_descargar_desembolso = false;
    $scope.codigo_aprobacion = null;
    $scope.show_datos_revision = false;
    $scope.show_barra_progreso = false;
    $scope.cargando_doc = false;
    
    // Realiza consulta por el estado de revisión del desembolso
    $http({
        url: '/proyectos/revision_desembolso_personal',
        method: 'GET',
        params: {
            id_detalle_gasto: gasto_personal.id_detalle_gasto
        }
    })
    .success(function(data) {
        console.log('success callback');
        console.log(data);
        if(data.consultado == 1)
        {
            $scope.show_velo = false;
            if(data.desembolso.hay_desembolso)
            {
                $scope.estado_revision = data.desembolso.aprobado == 1 ? 'Aprobado' : 'No aprobado';
                $scope.show_descargar_desembolso = true;
                $scope.nombre_archivo = data.desembolso.archivo;
                $scope.codigo_aprobacion = data.desembolso.codigo_aprobacion;
                $scope.comentario_revision = data.desembolso.comentario_revision;
                
                $scope.show_datos_revision = true;
                
            }
            else
            {
                $scope.estado_revision = 'Sin revisión (no se ha cargado desembolso aún)';
            }
        }
        else
        {
            alertify.error('Error con consulta de revisión. Código de error: ' + data.codigo);
            $scope.$close();
        }
    })
    .error(function(data, status) {
        console.log('error callback');
        console.log(data);
        alertify.error('Error XHR o de servidor al consultar revisión. Código de estado: ' + status);
        $scope.$close();
    });
    
    // valida el archivo cargado. Retorna true si es inválido.
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        if($scope.documento_desembolso == null || $scope.documento_desembolso == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_invalido = true;
            return true;
        }
        $scope.documento_invalido = false;
        return false;
    };
    
    // carga archivo asincronamete validando antes que se halla cargado archivo válido
    $scope.cargar_desembolso = function() {

        // si el documento es inválido se cancela operación de carga
        if($scope.validar_documento())
            return;
        
        // envía el documento asincronamente
        $scope.total_archivo = $scope.documento_desembolso.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_doc = true;
        
        // Hace uso del servicio Upload que ngFile proporciona
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_desembolso_gasto_personal',
            method: 'POST',
            data: {
                archivo: $scope.documento_desembolso,
                id_detalle_gasto: gasto_personal.id_detalle_gasto,
                comentario: $scope.comentario_desembolso
            }
        });

        // realiza seguimiento de envío a travez del objeto promise
        $scope.upload_service.then(function (response) {
            console.log(response); // impirme respuesta de servidor para propósitos debug
            var data = response.data;
            if(data.consultado == 1){
                $scope.$close({
                    exito: true
                });
            }
            else{
                $scope.$close({
                    exito: false,
                    mensaje: response.data.mensaje,
                    codigo: response.data.codigo
                });
            }
        }, function (response) {
            console.log(response);
            $scope.$close({
                exito: false,
                mensaje: 'Error XHR o de servidor al cargar desembolso',
                codigo: response.status
            });
        }, function (evt) {
            // Realiza cáclulo de porcentaje de carga
            $scope.carga_actual = evt.loaded;
            $scope.porcentaje_carga = Math.min(100, parseInt(100.0 * 
                                     evt.loaded / evt.total));

            if($scope.carga_actual >= $scope.total_archivo)
                $scope.casi_terminado = true;
        });        
    };        
    
    // Retorna a llamador de modal cancelando la carga si hay una en progreso
    $scope.cancelar = function() {
        if(Upload.isUploadInProgress()){
            $scope.upload_service.abort();
            $scope.$dismiss({
                carga_cancelada: true
            });
        }
        else{
            $scope.$dismiss({
                carga_cancelada: false
            });            
        }
    };        
    
});
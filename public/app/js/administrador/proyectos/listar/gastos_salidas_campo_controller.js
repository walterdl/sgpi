sgpi_app.controller('gastos_salidas_campo_controller', function ($scope, $uibModal) {
    
	/*
	|--------------------------------------------------------------------------
	| detalles_salida_campo()
	|--------------------------------------------------------------------------
	| Presenta modal dque presenta los detalle de la salida de campo seleccionada. 
	*/         
    $scope.detalles_salida_campo = function(gasto_salida_campo){
        $uibModal.open({
            animation: true,
            templateUrl: 'modal_mas_info_salida_campo.html',
            controller: 'modal_mas_info_salida_campo_controller',
            size: 'lg',
            scope: $scope,
            keyboard: true,
            resolve:{
                gasto_salida_campo: function() {
                    return gasto_salida_campo;
                }
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
        if($('#contenido_gastos_salidas_campo').hasClass('in')){
            $('#contenido_gastos_salidas_campo').parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#contenido_gastos_salidas_campo').parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };       
   
	/*
	|--------------------------------------------------------------------------
	| revision_desembolso()
	|--------------------------------------------------------------------------
	| Ejecuta modal de revision de salida de campo
	*/         
    $scope.revision_desembolso = function(gasto_salida){
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_revision_desembolso.html',
            controller: 'modal_revision_desembolso_salida_controller',
            size: 'lg',
            scope: $scope,
            keyboard: false,
            backdrop: 'static',
            resolve:{
                gasto_salida: function() {
                    return gasto_salida;
                }
            }
        });        
        
        // captura los eventos de cierre de modal, determinando su causa para mostrar un alertify
        modalInstance.result.then(function(resultado){
            // El modal retorno una respuesta close. Se evalúa el éxito de la operacion de carga
            if(resultado.exito){
                alertify.success('Revisión de desembolso realizada');
            }
            else{
                alertify.error(resultado.mensaje);
            }
        }, function(resultado){
            // El modal retorno una respuesta dismiss- Se evalúa si canceló una carga
            if(resultado != undefined)
            {
                if(resultado.carga_cancelada)
                    alertify.error('Revisión de desembolso cancelada');
            }
        });         
    };
});

/*
|--------------------------------------------------------------------------
| modal_mas_info_salida_campo_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta los detalles de un gasto de una salida de campo
*/              
sgpi_app.controller('modal_mas_info_salida_campo_controller', function ($scope, $uibModalInstance, gasto_salida_campo){
    $scope.gasto_salida_campo = gasto_salida_campo;
});

/*
|--------------------------------------------------------------------------
| modal_revision_desembolso_salida_controller
|--------------------------------------------------------------------------
| Controlador de modal para la carga de revisión de desembolso software
*/              
sgpi_app.controller('modal_revision_desembolso_salida_controller', function ($scope, $http, $uibModalInstance, gasto_salida){
    
    console.log(gasto_salida);
    $scope.gasto_salida = gasto_salida;
    $scope.titulo_modal = 'Desembolso de salida de campo';
    $scope.gasto_html = '<h4>Justificación de la salida de campo: <strong>{$ gasto_salida.justificacion $}</strong></h4>';
    $scope.msj_operacion = '<h4 class="text-center">Cargado estado de revisión de desembolso...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo = true;
    $scope.desembolso = null;
    $scope.guardando_revision = false;
    
    // consulta el estado de revisión del desembolso
    $http({
        url: '/proyectos/revision_desembolso',
        method: 'GET',
        params: {
            id_detalle_gasto: gasto_salida.id_detalle_gasto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            $scope.show_velo = false;
            $scope.desembolso = data.desembolso;
            // formatea valor de aprobado a un valor boleano correcto
            if($scope.desembolso.aprobado == 1)
                $scope.desembolso.aprobado = true;
            else if($scope.desembolso.aprobado == 0)
                $scope.desembolso.aprobado = false;
        }
        else
        {
            $scope.$close({
                exito: false,
                mensaje: 'Error al consultar el estado de revisión de desembolso. Código de error: ' + data.codigo
            });
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.$close({
            exito: false,
            mensaje: 'Error XHR o de servidor al consultar el estado de revisión de desembolso. Código de estado: ' + status
        });
    });
    
    // carga revisión asincronamente
    $scope.cargar_revision = function() {
        
        $scope.msj_operacion = '<h4 class="text-center">Guardando revisión de desembolso...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
        $scope.show_velo = true;
        $scope.guardando_revision = true;
        
        $http({
            url: '/proyectos/guardar_revision_desembolso',
            method: 'POST',
            params: {
                id_detalle_gasto: gasto_salida.id_detalle_gasto,
                aprobado: $scope.desembolso.aprobado ,
                comentario_revision: $scope.desembolso.comentario_revision,
                codigo_aprobacion: $scope.desembolso.codigo_aprobacion,
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1)
            {
                $scope.$close({
                    exito: true
                });
            }
            else
            {
                $scope.$close({
                    exito: false,
                    mensaje: 'Error al guardar revisión de desembolso. Código de error: ' + data.codigo
                });
            }            
        })
        .error(function(data, status) {
            console.log(data);
            $scope.$close({
                exito: false,
                mensaje: 'Error XHR o de servidor al guardar revisión de desembolso. Código de estado: ' + status
            });            
        })
        .finally(function() {
            $scope.guardando_revision = false;
            $scope.show_velo = false;
        });
    };        
    
    // Retorna a llamador de modal cancelando la carga si hay una en progreso
    $scope.cancelar = function() {
        $scope.$dismiss({});            
    };        
    
});

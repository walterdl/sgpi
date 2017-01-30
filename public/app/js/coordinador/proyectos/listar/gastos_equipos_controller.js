sgpi_app.controller('gastos_equipos_controller', function ($scope, $uibModal) {
    
	/*
	|--------------------------------------------------------------------------
	| abre_cierra_acordion()
	|--------------------------------------------------------------------------
	| Simple controlador de evento para click de anchor que abre o cierra acordion. 
	| Establece el icono glyphicon adecuado al acordion
	*/     
    $scope.abre_cierra_acordion = function(id_acordion) {
        if($('#contenido_gastos_equipos').hasClass('in')){
            $('#contenido_gastos_equipos').parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#contenido_gastos_equipos').parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };    
    
	/*
	|--------------------------------------------------------------------------
	| detalles_equipo()
	|--------------------------------------------------------------------------
	| Presenta modal dque presenta los detalle del equipo seleccionado. 
	*/         
    $scope.detalles_equipo = function(gasto_equipo){
        $uibModal.open({
            animation: true,
            templateUrl: 'modal_mas_info_equipo.html',
            controller: 'modal_mas_info_equipo_controller',
            size: 'lg',
            // scope: $scope,
            // keyboard: true,
            resolve:{
                gasto_equipo: function() {
                    return gasto_equipo;
                }
            }
        });        
    };
    
	/*
	|--------------------------------------------------------------------------
	| revision_desembolso()
	|--------------------------------------------------------------------------
	| Ejecuta modal de revision de equipo
	*/         
    $scope.revision_desembolso = function(gasto_equipo){
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_revision_desembolso.html',
            controller: 'modal_revision_desembolso_equipo_controller',
            size: 'lg',
            scope: $scope,
            keyboard: false,
            backdrop: 'static',
            resolve:{
                gasto_equipo: function() {
                    return gasto_equipo;
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
| modal_mas_info_equipo_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta los detalles de un gasto de equipo
*/              
sgpi_app.controller('modal_mas_info_equipo_controller', function ($scope, $uibModalInstance, gasto_equipo){
    $scope.gasto_equipo = gasto_equipo;
});

/*
|--------------------------------------------------------------------------
| modal_revision_desembolso_equipo_controller
|--------------------------------------------------------------------------
| Controlador de modal para la carga de revisión de desembolso equipo
*/              
sgpi_app.controller('modal_revision_desembolso_equipo_controller', function ($scope, $http, $uibModalInstance, gasto_equipo){
    
    $scope.gasto_equipo = gasto_equipo;
    $scope.titulo_modal = 'Revisión de desembolso de gasto de equipo';
    $scope.gasto_html = '<h4>Equipo: <strong>{$ gasto_equipo.concepto $}</strong></h4>';
    $scope.msj_operacion = '<h4 class="text-center">Cargado estado de revisión de desembolso...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo = true;
    $scope.desembolso = null;
    $scope.guardando_revision = false;
    
    // consulta el estado de revisión del desembolso
    $http({
        url: '/proyectos/revision_desembolso',
        method: 'GET',
        params: {
            id_detalle_gasto: gasto_equipo.id_detalle_gasto
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
    
});
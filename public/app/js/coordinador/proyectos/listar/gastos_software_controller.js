sgpi_app.controller('gastos_software_controller', function ($scope, $uibModal) {
    
	/*
	|--------------------------------------------------------------------------
	| detalles_software()
	|--------------------------------------------------------------------------
	| Presenta modal dque presenta los detalle del software seleccionado. 
	*/         
    $scope.detalles_software = function(gasto_software){
        $uibModal.open({
            animation: true,
            templateUrl: 'modal_mas_info_software.html',
            controller: 'modal_mas_info_software_controller',
            size: 'lg',
            scope: $scope,
            keyboard: true,
            resolve:{
                gasto_software: function() {
                    return gasto_software;
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
        if($('#contenido_gastos_software').hasClass('in')){
            $('#contenido_gastos_software').parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#contenido_gastos_software').parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };       
   
	/*
	|--------------------------------------------------------------------------
	| revision_desembolso()
	|--------------------------------------------------------------------------
	| Ejecuta modal de revision de software
	*/         
    $scope.revision_desembolso = function(gasto_software){
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_revision_desembolso.html',
            controller: 'modal_revision_desembolso_software_controller',
            size: 'lg',
            // scope: $scope,
            // keyboard: false,
            backdrop: 'static',
            resolve:{
                gasto_software: function() {
                    return gasto_software;
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
| modal_mas_info_software_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta los detalles de un gasto de software
*/              
sgpi_app.controller('modal_mas_info_software_controller', function ($scope, $uibModalInstance, gasto_software){
    $scope.gasto_software = gasto_software;
});

/*
|--------------------------------------------------------------------------
| modal_revision_desembolso_software_controller
|--------------------------------------------------------------------------
| Controlador de modal para la carga de revisión de desembolso software
*/              
sgpi_app.controller('modal_revision_desembolso_software_controller', function ($scope, $http, $uibModalInstance, Upload, gasto_software){
    
    $scope.gasto_software = gasto_software;
    $scope.titulo_modal = 'Desembolso de gasto de software';
    $scope.gasto_html = '<h4>Software: <strong>{$ gasto_software.concepto $}</strong></h4>';
    $scope.msj_operacion = '<h4 class="text-center">Cargado estado de revisión de desembolso...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo = true;
    $scope.desembolso = null;
    $scope.guardando_revision = false;
    
    // consulta el estado de revisión del desembolso
    $http({
        url: '/proyectos/revision_desembolso',
        method: 'GET',
        params: {
            id_detalle_gasto: gasto_software.id_detalle_gasto
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
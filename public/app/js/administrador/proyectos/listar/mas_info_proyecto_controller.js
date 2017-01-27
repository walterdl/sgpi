sgpi_app.controller('mas_info_proyecto_controller', function ($scope, $http){
    
    $scope.mas_info_poryecto_consultada = false;
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento 'mas_informacion_seleccionado'
	| Detecta cuando la opción de más información de proyecto se ha sleccionado
	| Ejecuta consulta ajax por la información detallada del proyecto
	|--------------------------------------------------------------------------
	*/        
    $scope.$on('mas_informacion_seleccionado', function (event) {
        
        $scope.data.msj_mas_info_proy = '<h4 class="text-center">Cargando más información del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></<h4>';
        $scope.visibilidad.show_velo_mas_info_proy = true;
        
        $http({
            url: '/proyectos/mas_info_proyecto',
            method: 'GET',
            params: {
                'id_proyecto': $scope.data.id_proyecto
            }
        })
        .success(function(data){
            console.log(data);
            if(data.consultado == 1)
            {
                $scope.mas_info_proyecto_consultada = true;
                $scope.datos_generales_proyecto = data.datos_generales_proyecto;
                $scope.objetivos_especificos = data.objetivos_especificos;
                $scope.entidades_grupos_investigacion = data.entidades_grupos_investigacion;
                $scope.investigadores = data.investigadores;
                $scope.entidades_fuente_presupuesto = data.entidades_fuente_presupuesto;
                $scope.cantidad_productos = data.cantidad_productos;
                $scope.documentos_iniciales = data.documentos_iniciales;
            }
            else
            {
                $scope.mas_info_proyecto_consultada = false;
                alertify.error('Error al consultar más información del proyecto. Código de error: ' + data.codigo);
            }
        })
        .error(function(data, status){
            console.log(data);            
            $scope.mas_info_proyecto_consultada = false;
            alertify.error('Error XHR o de servidor al consultar más información del proyecto. Código de estado: ' + status);            
        })
        .finally(function() {
            $scope.visibilidad.show_velo_mas_info_proy = false;
        });
    });
    
	/*
	|--------------------------------------------------------------------------
	| abre_cierra_acordion()
	|--------------------------------------------------------------------------
	| Simple controlador de evento para click de anchor que abre o cierra acordion. 
	| Establece el icono glyphicon adecuado al acordion
	*/     
    $scope.abre_cierra_acordion = function(id_acordion) {
        if($('#' + id_acordion).hasClass('in')){
            $('#' + id_acordion).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        }
        else{
            $('#' + id_acordion).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }
    };    
});

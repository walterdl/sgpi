sgpi_app.controller('productos_controller', function ($scope, $http, $uibModal) {
    
    $scope.show_velo_msj_operacion = true;
    $scope.msj_operacion = '<h3 class="text-center">Seleccionar proyecto</h3>';
    
	/*
	|--------------------------------------------------------------------------
	| Controlador de evento para la selección de la opción de carga de documentos de productos
	| opción que se encuentra como botón en la pestaña de Proyectos
	|--------------------------------------------------------------------------
	| Realiza una consulta ajax por los productos del proyecto identificado en data.id_proyecto
	*/        
    $scope.$on('productos_seleccionado', function (event) {
        $scope.show_velo_msj_operacion = true;
        $scope.msj_operacion = '<h3 class="text-center">Cargando productos del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
        $http({
            url: '/proyectos/productos_de_proyecto',
            params: {
                id_proyecto: $scope.data.id_proyecto
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
                $scope.show_velo_msj_operacion = false;
                $scope.productos = data.productos;
            }
            else if(data.consultado == 2){
                // devolver aquí a pestaña de proyectos con mensaje de error
                $('a[href="#contenido_tab_proyectos"]').tab('show');
                alertify.error('Error al consultar los productos del proyecto. Código de error: ' + data.codigo);
            }
        })
        .error(function(data, status) {
            console.log(data);
            $('a[href="#contenido_tab_proyectos"]').tab('show');
            alertify.error('Error XHR o de servidor al consultar los productos del proyecto. Cósigo de estado: ' + status);            
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
    
	/*
	|--------------------------------------------------------------------------
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando los propductos 
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.productos = [];
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };    
    
	/*
	|--------------------------------------------------------------------------
	| producto_fecha_proyectada_radicacion()
	|--------------------------------------------------------------------------
	| Ejecuta modal que presenta la carga del producto relacionado con la fecha proyectada de radicación
	*/                 
    $scope.detalles_producto = function(producto, tipo_fecha) {
        if(tipo_fecha == 'fecha_proyectada_radicacion' || tipo_fecha == 'fecha_publicacion')
            $uibModal.open({
                    animation: true,
                    templateUrl: 'modal_producto.html',
                    controller: 'modal_producto_controller',
                    size: 'lg',
                    scope: $scope,
                    // keyboard: false,
                    // backdrop: 'static',
                    resolve:{
                        producto: function() {
                            return producto;
                        },
                        tipo_fecha: function() {
                            return tipo_fecha
                        }
                    }
                });
    };

});

sgpi_app.controller('modal_producto_controller', function ($scope, $http, $uibModalInstance, producto, tipo_fecha) {
    
    $scope.producto = producto;
    if(tipo_fecha == 'fecha_proyectada_radicacion'){
        $scope.titulo_modal = 'Producto - fecha proyectada de radicación ({$ producto.fecha_proyectada_radicacion $})';
        $scope.url = '/proyectos/verificar_existencia_archivo_fecha_proyectada_postular';
    }
    else if(tipo_fecha == 'fecha_publicacion'){
        $scope.titulo_modal = 'Producto - fecha publicación ({$ producto.fecha_publicacion $})';
        $scope.url = '/proyectos/verificar_existencia_archivo_fecha_publicacion';
    }
    
    $scope.msj_operacion = '<h4 class="text-center">Cargando carga de producto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.show_velo = true;
    
    $http({
        url: $scope.url,
        method: 'GET',
        params: {
            id_producto: producto.id_producto
        }
    })
    .success(function(data){
        console.log(data);
        if(data.consultado==1){
            $scope.existe_archivo = data.existe_archivo;
            if($scope.existe_archivo == 1)
            {
                $scope.nombre_archivo = data.nombre_archivo;
                $scope.descripcion = data.descripcion;
                $scope.updated_at = data.updated_at;
            }
        }
        else{
            alertify.error('Error al consultar carga de producto relacionado con la fecha. Código de error: ' + data.codigo);
            $scope.$close();
        }
    })
    .error(function(data, status){
        console.log(data);
        alertify.error('Error XHR o de servidor al carga de producto relacionado con la fecha. Código de estado: ' + status);
        $scope.$close();        
    })
    .finally(function() {
        $scope.show_velo = false;
    });
    
});
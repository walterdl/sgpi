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
    $scope.$on('cargar_docs_productos_seleccionado', function (event) {
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
            alertify.error('Error al consultar los productos del proyecto. Cósigo de estado: ' + status);            
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
	| cargar_fecha_proyectada_radicacion()
	|--------------------------------------------------------------------------
	| Abre modal de carga de documento relacionado a la fecha proyectada de radicación
	*/         
    $scope.cargar_fecha_proyectada_radicacion = function(producto) {
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_fecha_proyectada_radicacion.html',
            controller: 'modal_fecha_proyectada_radicacion_controller',
            size: 'lg',
            scope: $scope,
            keyboard: false,
            backdrop: 'static',
            resolve:{
                producto: function() {
                    return producto;
                }
            }
        });

        // captura los eventos de cierre de modal, determinando su causa para mostrar un Alertify
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
	| cargar_fecha_publicacion()
	|--------------------------------------------------------------------------
	| Abre modal de carga de documento relacionado a la fecha de publicación
	*/             
    $scope.cargar_fecha_publicacion = function(producto) {
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_fecha_publicacion.html',
            controller: 'modal_fecha_publicacion_controller',
            size: 'lg',
            scope: $scope,
            keyboard: false,
            backdrop: 'static',
            resolve:{
                producto: function() {
                    return producto;
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
	| volver_a_proyectos()
	|--------------------------------------------------------------------------
	| Retorna a pestaña de selecciónde proyectos eliminando los propductos 
	| consultados almancenados en cliente para este proyecto
	*/             
    $scope.volver_a_proyectos = function() {
        $scope.productos = [];
        $('a[href="#contenido_tab_proyectos"]').tab('show');
    };
    
});

/*
|--------------------------------------------------------------------------
| modal_fecha_proyectada_radicacion_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta interfaz para cargar documento de producto
| relacionado con la fecha proyectada de radicación
*/              
sgpi_app.controller('modal_fecha_proyectada_radicacion_controller', function ($scope, $http, $uibModalInstance, Upload, producto) {
    
    $scope.show_velo = true;
    $scope.msj_operacion = '<h4 class="text-center">Verificando existencia de archivo...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.fecha_proyectada_radicacion = producto.fecha_proyectada_radicacion;
    $scope.documento_invalido = false;
    $scope.casi_terminado = false;
    $scope.show_barra_progreso = false;
    $scope.upload_service = null;
    $scope.cargando_doc = false;
    $scope.ya_hay_archivo = false;
    $scope.nombre_archivo = null;
    
    // realiza consulta ajax para verificar la existencia archivo 
    $http({
        url: '/proyectos/verificar_existencia_archivo_fecha_proyectada_postular',
        method: 'GET',
        params: {
            id_producto: producto.id_producto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1){
            if(data.existe_archivo == 1){
                $scope.nombre_archivo = data.nombre_archivo;
                $scope.ya_hay_archivo = true;
            }
            else
                $scope.ya_hay_archivo = false;
            $scope.show_velo = false;
        }
        else{
            $scope.$close({
                exito: false,
                mensaje: data.mensaje,
                codigo: data.codigo
            });            
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.$close({
            exito: false,
            mensaje: 'Error XHR o de servidor al consultar la existencia de archivo de producto proyectado a postular',
            codigo: status
        });        
    });
    
    // valida el archivo cargado. Retorna true si es inválido.
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        // var data = [
        //     files, file, newFiles, duplicateFiles, invalidFiles, event
        //     ];
        // console.log(data);
        if($scope.documento_fecha_proyectada_radicacion == null || $scope.documento_fecha_proyectada_radicacion == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_invalido = true;
            return true;;
        }
        $scope.documento_invalido = false;
        return false;
    };
    
    // carga archivo asincronamete validando antes que se halla cargado archivo válido
    $scope.cargar_doc = function() {

        // si el documento es inválido se cancela operación de carga
        if($scope.validar_documento())
            return;
        
        // envía el documento asincronamente
        $scope.total_archivo = $scope.documento_fecha_proyectada_radicacion.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_doc = true;
        
        // Hace uso del servicio Upload que ngFile proporciona
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_producto_fecha_proyectada_radicacion',
            method: 'POST',
            data: {
                archivo: $scope.documento_fecha_proyectada_radicacion,
                id_producto: producto.id_producto,
                descripcion: $scope.descripcion
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
                mensaje: 'Error XHR o de servidor',
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

/*
|--------------------------------------------------------------------------
| modal_fecha_publicacion_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta interfaz para cargar documento de producto
| relacionado con la fecha de publicación
*/              
sgpi_app.controller('modal_fecha_publicacion_controller', function ($scope, $http, $uibModalInstance, Upload, producto) {
    
    $scope.show_velo = true;
    $scope.msj_operacion = '<h4 class="text-center">Verificando existencia de archivo...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h4>';
    $scope.fecha_publicacion = producto.fecha_publicacion;
    $scope.documento_invalido = false;
    $scope.casi_terminado = false;
    $scope.show_barra_progreso = false;
    $scope.upload_service = null;
    $scope.cargando_doc = false;
    $scope.ya_hay_archivo = false;
    $scope.nombre_archivo = null;    
    
    // realiza consulta ajax para verificar la existencia archivo 
    $http({
        url: '/proyectos/verificar_existencia_archivo_fecha_publicacion',
        method: 'GET',
        params: {
            id_producto: producto.id_producto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1){
            if(data.existe_archivo == 1){
                $scope.nombre_archivo = data.nombre_archivo;
                $scope.ya_hay_archivo = true;
            }
            else
                $scope.ya_hay_archivo = false;
            $scope.show_velo = false;
        }
        else{
            $scope.$close({
                exito: false,
                mensaje: data.mensaje,
                codigo: data.codigo
            });            
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.$close({
            exito: false,
            mensaje: 'Error XHR o de servidor al consultar la existencia de archivo de producto publicado',
            codigo: status
        });        
    });    
    
    // valida el archivo cargado. Retorna true si es inválido.
    $scope.validar_documento = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        if($scope.documento_fecha_publicacion == null || $scope.documento_fecha_publicacion == undefined) 
        {
            // se ha cargado archivos inválidos sin importar el error, o no se ha cargado archivo...
            $scope.documento_invalido = true;
            return true;
        }
        $scope.documento_invalido = false;
        return false;
    };
    
    // carga archivo asincronamete validando antes que se halla cargado archivo válido
    $scope.cargar_doc = function() {

        // si el documento es inválido se cancela operación de carga
        if($scope.validar_documento())
            return;
        
        // envía el documento asincronamente
        $scope.total_archivo = $scope.documento_fecha_publicacion.size;
        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        $scope.show_barra_progreso = true;
        $scope.cargando_doc = true;
        
        // Hace uso del servicio Upload que ngFile proporciona
        $scope.upload_service = Upload.upload({
            url: '/proyectos/cargar_producto_fecha_publicacion',
            method: 'POST',
            data: {
                archivo: $scope.documento_fecha_publicacion,
                id_producto: producto.id_producto,
                descripcion: $scope.descripcion
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
                mensaje: 'Error XHR o de servidor',
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




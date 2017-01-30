sgpi_app.controller('crear_productos_proyecto_controller', function ($scope, $http) {
    
    // inicialización de variables
    $scope.data.productos = [];
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };        
    
    /*
	|--------------------------------------------------------------------------
	| cambia_tipo_prod_general()
	|--------------------------------------------------------------------------
	| Añade los tipos de prodcutos específicos que corresponden con el tipo de producto general seleccionado.
	| También remueve la selección del tipo específico que se tenga previamente.
	*/        
    $scope.cambia_tipo_prod_general = function() {
        
        if($scope.data.tipo_producto_general)
            $scope.data.tipos_productos_especificos = $scope.data.productos_especificos_x_prod_general
            [
                $scope.data.tipo_producto_general.id
            ]
            .tipos_productos_especificos;    
        else
            $scope.data.tipos_productos_especificos = [];
            
        $scope.validar_tipo_producto_general();
        $scope.data.tipo_producto_especifico = null;
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_producto()
	|--------------------------------------------------------------------------
	| Agrega un prodcuto con su tipo general y específico.
	| No se agrega el producto si la validacion de ambos tipos no son correctas
	*/            
    $scope.agregar_producto = function () {
        
        // si alguna validación retorna true entonces un campo es incorrecto
        if($scope.validar_tipo_producto_general() || $scope.validar_tipo_producto_especifico()){
            return;
        }
        else{
            // agregar prodcuto a tabla de productos..
            var tipo_producto_especifico = null;
            try{
                $scope.data.tipos_productos_especificos.forEach(function(item) {
                    if(item.id == $scope.data.tipo_producto_especifico){
                        tipo_producto_especifico = item;
                        throw 'ObjectFoundException';
                    }
                });
            }
            catch(err){}
                    
            var obj = {
                tipo_producto_general: $scope.data.tipo_producto_general,
                tipo_producto_especifico: tipo_producto_especifico ,
                nombre: null,
                fecha_proyectada_radicar: null,
                fecha_remision: null,
                fecha_confirmacion_editorial: null,
                fecha_recepcion_evaluacion: null,
                fecha_respuesta_evaluacion: null,
                fecha_aprobacion_publicacion: null,
                fecha_publicacion: null
            };
            $scope.data.productos.push(obj);
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_producto()
	|--------------------------------------------------------------------------
	| Remueve un prodcuto agregado de la colección de productos
	*/                
    $scope.remover_producto = function(producto) {
        var index_prodcuto = $scope.data.productos.indexOf(producto);
        if(index_prodcuto != -1)
            $scope.data.productos.splice(index_prodcuto, 1);
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_tipo_producto_general()
	|--------------------------------------------------------------------------
	| Valida la selección de un tipo de prodcuto general o categoría de prodcuto
	| Retorn true si no se ha seleccionado nada
	*/            
    $scope.validar_tipo_producto_general =  function() {
        if($scope.data.tipo_producto_general == null){
            $scope.visibilidad.tipos_productos_generales_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.tipos_productos_generales_invalido = false;        
            return false
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_tipo_producto_especifico()
	|--------------------------------------------------------------------------
	| Valida la selección del tipo de producto específico
	*/                
    $scope.validar_tipo_producto_especifico = function() {
        if($scope.data.tipo_producto_especifico == null){
            $scope.visibilidad.tipo_producto_especifico_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.tipo_producto_especifico_invalido = false;        
            return false
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_nombre_producto()
	|--------------------------------------------------------------------------
	| Valida el nombre de un producto determinado pasado como parametro
	| valida que el nombre se encuentre entre 5 y 200 caracteres
	*/                    
    $scope.validar_nombre_producto = function(producto) {
        
        // se valida el nombre del producto
        if(producto.nombre && producto.nombre.length >= 5 && producto.nombre.length < 200){
            producto.nombre_invalido = false;
            return false;
        }
        else{
            producto.nombre_invalido = true;
            return true;
        }
        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_participante_producto()
	|--------------------------------------------------------------------------
	| Valida que se halla seleccionado un participante ancargado para este producto
	*/                        
    $scope.validar_participante_producto = function(producto) {
    
        if(producto.participante){
            producto.participante_invalido = false;
            return false;
        }
        else{
            producto.participante_invalido = true;
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_proyectada_radicar()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_proyectada_radicar.
	*/              
    $scope.validar_fecha_proyectada_radicar = function(producto) {
        if(producto.fecha_proyectada_radicar){
            producto.fecha_proyectada_radicar_invalido = false;
            return false;
        }
        else{
            producto.fecha_proyectada_radicar_invalido = true;
            return true;
        }        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_remision()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_remision.
	*/                  
    $scope.validar_fecha_remision = function(producto) {
        if(producto.fecha_remision){
            producto.fecha_remision_invalido = false;
            return false;
        }
        else{
            producto.fecha_remision_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_confirmacion_editorial()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_confirmacion_editorial.
	*/                      
    $scope.validar_fecha_confirmacion_editorial = function(producto) {
        
        if(producto.fecha_confirmacion_editorial){
            producto.fecha_confirmacion_editorial_invalido = false;
            return false;
        }
        else{
            producto.fecha_confirmacion_editorial_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_recepcion_evaluacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_recepcion_evaluacion
	*/               
    $scope.validar_fecha_recepcion_evaluacion = function(producto) {
        
        if(producto.fecha_recepcion_evaluacion){
            producto.fecha_recepcion_evaluacion_invalido = false;
            return false;
        }
        else{
            producto.fecha_recepcion_evaluacion_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_respuesta_evaluacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_respuesta_evaluacion
	*/        
    $scope.validar_fecha_respuesta_evaluacion = function(producto) {
        if(producto.fecha_respuesta_evaluacion){
            producto.fecha_respuesta_evaluacion_invalido = false;
            return false;
        }
        else{
            producto.fecha_respuesta_evaluacion_invalido = true;        
            return true;
        }        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_aprobacion_publicacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_aprobacion_publicacion
	*/                   
    $scope.validar_fecha_aprobacion_publicacion = function(producto) {
        
        if(producto.fecha_aprobacion_publicacion){
            producto.fecha_aprobacion_publicacion_invalido = false;
            return false;
        }
        else{
            producto.fecha_aprobacion_publicacion_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_publicacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_publicacion
	| Valida que se halla seleccionado una fecha_proyectada_radicar menor a fecha_publicacion
	*/                       
    $scope.validar_fecha_publicacion = function(producto) {
        if(producto.fecha_publicacion){
            if(producto.fecha_proyectada_radicar){
                if(producto.fecha_proyectada_radicar < producto.fecha_publicacion){
                    producto.fecha_publicacion_invalido = false;
                    return false;
                }
                else{
                    producto.fecha_publicacion_invalido = true;        
                    producto.msj_fecha_publicacion_invalido = 'La fecha de publicación debe ser mayor a la fecha proyectada para radicar'
                    return true;                
                }
            }
            return true;
        }
        else{
            producto.fecha_publicacion_invalido = true;        
            producto.msj_fecha_publicacion_invalido = 'Ingresar fecha'
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| continuar_a_gastos()
	|--------------------------------------------------------------------------
	| Continúa a la pestaña de ingreso de gastos del proyecto solo si las validaciones de los prodcutos
	| ya ingresados son correctas.
	*/           
    $scope.continuar_a_gastos = function() {
        
        if($scope.data.productos.length == 0){
            alertify.error('Error. Ingresar al menos un producto');
            return;
        }
        
        var validaciones = [];
        $scope.data.productos.forEach(function(producto) {
            validaciones.push($scope.validar_tipo_producto_especifico(producto));
            validaciones.push($scope.validar_nombre_producto(producto));
            validaciones.push($scope.validar_participante_producto(producto));
            validaciones.push($scope.validar_fecha_proyectada_radicar(producto));
            validaciones.push($scope.validar_fecha_remision(producto));
            validaciones.push($scope.validar_fecha_confirmacion_editorial(producto));
            validaciones.push($scope.validar_fecha_recepcion_evaluacion(producto));
            validaciones.push($scope.validar_fecha_respuesta_evaluacion(producto));
            validaciones.push($scope.validar_fecha_aprobacion_publicacion(producto));
            validaciones.push($scope.validar_fecha_publicacion(producto));
        });
        
        if(validaciones.indexOf(true) != -1){ // alguna validacion es incorrecta
            alertify.error('Error con validaciones');
        }
        else{
            $('a[href="#contenido_gastos"]').tab('show');
        }
        
    };
    
    $scope.regresar_participantes = function() {
        $('a[href="#contenido_participantes"]').tab('show');
    };
    
});
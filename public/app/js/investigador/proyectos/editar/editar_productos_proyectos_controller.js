sgpi_app.controller('editar_productos_proyecto_controller', function ($scope, $http) {
    

    // inicialización de variables
    $scope.data.productos = [];
    $scope.producto = [{
        participante_invalido:false,
    }];
    
    // $scope.data.info_productos=[];
    //$scope.data.info_productos2=[];
    
    console.log("estas del controlador de productos ");
    // console.log($scope.data.participantes_proyecto);
    console.log($scope.data.info_productos); // no se ve nada por que no se an cargado por completo el json
    
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
                //console.log($scope.data.tipos_productos_especificos);
                $scope.data.tipos_productos_especificos.forEach(function(item) {
                    if(item.id == $scope.data.tipo_producto_especifico){
                        tipo_producto_especifico = item;
                        throw 'ObjectFoundException';
                    }
                });
            }
            catch(err){}
                    
            var obj = {
                'producto':{
                    'id':null,
                    'tipo_producto_e': {
                        'nombre':tipo_producto_especifico.nombre,
                        'id':tipo_producto_especifico.id,
                        'tipo_producto_g':$scope.data.tipo_producto_general
                    } ,
                    nombre: null,
                    fecha_proyectada_radicacion: null,
                    fecha_remision: null,
                    fecha_confirmacion_editorial: null,
                    fecha_recepcion_evaluacion: null,
                    fecha_respuesta_evaluacion: null,
                    fecha_aprobacion_publicacion: null,
                    fecha_publicacion: null,
                    'investigador':{
                        'id':null,
                        'persona':{
                            'id':null,
                            'info_investigador':{
                                'id':null,
                                'nombres':"",
                                'apellidos':"",
                            },
                        },
                    },
                },
                'resgitrado':0
            };
            $scope.data.info_productos.push(obj);
            console.log($scope.data.info_productos);
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_producto()
	|--------------------------------------------------------------------------
	| Remueve un prodcuto agregado de la colección de productos
	*/                
    $scope.remover_producto = function(producto) {
        
        console.log("estas en borrar");
        console.log(producto);
        console.log($scope.data.info_productos);
        
        if(producto.resgitrado == 0){
            var index_prodcuto = $scope.data.info_productos.indexOf(producto);
            if(index_prodcuto != -1)
                $scope.data.info_productos.splice(index_prodcuto, 1);
        }else{
            
            alertify.confirm('Eliminar producto', 'Desea eliminar este Producto?', 
            function(){ 
                
                $http({
                url: '/proyecto/eliminar/producto',
                method: 'GET',
                params: {
                    id_producto: producto.producto.id,
                }
                })
                .success(function(data) {
                    
                    console.log(data);
                    
                    if(!data.error){
                        
                        alertify.success(data.mensaje);
                        
                        var index_prodcuto = $scope.data.info_productos.indexOf(producto);
                        if(index_prodcuto != -1){
                            $scope.data.info_productos.splice(index_prodcuto, 1);
                        }
                        
                    }else{
                        alertify.error(data.mensaje);
                    }
                
                })
                .error(function(data, status) {
                    $log.log(data);
                    $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar eliminar el participante. Código de error: ' + status + '</h3>';
                    alertify.error('Error al eliminar el participante: ' + status);
                });
                
                
                
                
            }
                , function(){ 
                    alertify.error('Se cancelo el proceso');
                    
            });
            
            
        }
        
        
            
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
    
    
    // $scope.validar_tipo_producto_especifico2 = function() {
    //     if($scope.data.tipo_producto_especifico == null){
    //         $scope.visibilidad.tipo_producto_especifico_invalido = true;
    //         return true;
    //     }
    //     else{
    //         $scope.visibilidad.tipo_producto_especifico_invalido = false;        
    //         return false
    //     }
    // };
    
    /*
	|--------------------------------------------------------------------------
	| validar_nombre_producto()
	|--------------------------------------------------------------------------
	| Valida el nombre de un producto determinado pasado como parametro
	| valida que el nombre se encuentre entre 5 y 200 caracteres
	*/                    
    $scope.validar_nombre_producto = function(producto) {
        
        console.log(producto);
        
        // // se valida el nombre del producto
        if(producto.producto.nombre && producto.producto.nombre.length > 5 && producto.producto.nombre.length < 200){
            producto.producto.nombre_invalido = false;
            return false;
        }
        else{
            producto.producto.nombre_invalido = true;
            return true;
        }
        
    };
    
    $scope.validar_nombre_producto2 = function(producto) {
        
        console.log(producto);
        
        // // se valida el nombre del producto
        if(producto.nombre && producto.nombre.length > 5 && producto.nombre.length < 200){
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
    $scope.validar_participante_producto = function(item,selet) {
        
        // console.log('wwwwwww');
        // console.log(selected);
        console.log(selet);
        
       
        $scope.data.es_principal=selet.investigador_principarl;
        
        
        if(item.producto.investigador.persona.info_investigador.id){
                item.producto.participante_invalido = false;

            return false;
        }
        else{
            
                console.log("no hay persona");
                item.producto.participante_invalido = true;

            return true;
        }
    };
    
   $scope.validar_participante_producto2 = function(item) {
        
        // console.log('wwwwwww');
        // console.log(selected);
        console.log(item);
        

        if(item.producto.investigador.persona.info_investigador.id){
                item.producto.participante_invalido = false;

            return false;
        }
        else{
            
                console.log("no hay persona");
                item.producto.participante_invalido = true;

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
        
        if(producto.fecha_proyectada_radicacion){
            producto.fecha_proyectada_radicar_invalido = false;
            return false;
        }
        else{
            producto.fecha_proyectada_radicar_invalido = true;
            return true;
        }        
    };
    
    
    $scope.validar_fecha_proyectada_radicar2 = function(item) {
        
        console.log(item.producto);
        if(item.producto.fecha_proyectada_radicacion){
            item.producto.fecha_proyectada_radicar_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_proyectada_radicar_invalido = true;
            return true;
        }        
    };
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_remision()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_remision.
	*/                  
    $scope.validar_fecha_remision = function(item) {
        if(item.producto.fecha_remision){
            item.producto.fecha_remision_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_remision_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_confirmacion_editorial()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_confirmacion_editorial.
	*/                      
    $scope.validar_fecha_confirmacion_editorial = function(item) {
        
        if(item.producto.fecha_confirmacion_editorial){
            item.producto.fecha_confirmacion_editorial_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_confirmacion_editorial_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_recepcion_evaluacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_recepcion_evaluacion
	*/               
    $scope.validar_fecha_recepcion_evaluacion = function(item) {
        
        if(item.producto.fecha_recepcion_evaluacion){
            item.producto.fecha_recepcion_evaluacion_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_recepcion_evaluacion_invalido = true;        
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_respuesta_evaluacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_respuesta_evaluacion
	*/        
    $scope.validar_fecha_respuesta_evaluacion = function(item) {
        if(item.producto.fecha_respuesta_evaluacion){
            item.producto.fecha_respuesta_evaluacion_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_respuesta_evaluacion_invalido = true;        
            return true;
        }        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_aprobacion_publicacion()
	|--------------------------------------------------------------------------
	| Valida que se halla ingresado fecha_aprobacion_publicacion
	*/                   
    $scope.validar_fecha_aprobacion_publicacion = function(item) {
        
        if(item.producto.fecha_aprobacion_publicacion){
            item.producto.fecha_aprobacion_publicacion_invalido = false;
            return false;
        }
        else{
            item.producto.fecha_aprobacion_publicacion_invalido = true;        
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
    $scope.validar_fecha_publicacion = function(item) {
        
        if(item.producto.fecha_publicacion){
            if(item.producto.fecha_proyectada_radicacion){
                if(item.producto.fecha_proyectada_radicacion < item.producto.fecha_publicacion){
                    item.producto.fecha_publicacion_invalido = false;
                    return false;
                }
                else{
                    item.producto.fecha_publicacion_invalido = true;        
                    item.producto.msj_fecha_publicacion_invalido = 'La fecha de publicación debe ser mayor a la fecha proyectada para radicar'
                    return true;                
                }
            }
            return true;
        }
        else{
            item.producto.fecha_publicacion_invalido = true;        
            item.producto.msj_fecha_publicacion_invalido = 'Ingresar fecha'
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
        
        if($scope.data.info_productos.length == 0){
            alertify.error('Error. Ingresar al menos un producto');
            return;
        }
        
        var validaciones = [];
        $scope.data.info_productos.forEach(function(item) {
            //// validaciones.push($scope.validar_tipo_producto_especifico(producto));
            validaciones.push($scope.validar_nombre_producto2(item.producto));
            validaciones.push($scope.validar_participante_producto2(item));
            validaciones.push($scope.validar_fecha_proyectada_radicar2(item));
            validaciones.push($scope.validar_fecha_remision(item));
            validaciones.push($scope.validar_fecha_confirmacion_editorial(item));
            validaciones.push($scope.validar_fecha_recepcion_evaluacion(item));
            validaciones.push($scope.validar_fecha_respuesta_evaluacion(item));
            validaciones.push($scope.validar_fecha_aprobacion_publicacion(item));
            validaciones.push($scope.validar_fecha_publicacion(item));
        });
        
        if(validaciones.indexOf(true) != -1){ // alguna validacion es incorrecta
            alertify.error('Error con validaciones');
        }
        else{
            
            alertify.success("ok validado");
            $('#input_editar_proyecto').trigger('click');
        }
        
    };
    
  
    
});
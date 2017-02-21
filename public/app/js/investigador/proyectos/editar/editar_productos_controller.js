$(document).ready(function() {
    $('#contenedor_productos').perfectScrollbar();
    $(window).resize(function() {
        $('#contenedor_productos').perfectScrollbar('update');
    });
});

// servicio que valida cadena cuya longitud de estar entre [3, 200]
// retorna true si es invalida
sgpi_app.factory('validar_cadena', function () {
    return function (string, minlength, maxlength) {
        if(string == null || string == undefined || typeof(string) != 'string' || string.length < minlength || string.length > maxlength)
            return true;
        return false;
    };
});

// servicio que convierte una cadena de fecha a un objeto Date sin tener en cuenta la zona horaria del cliente
sgpi_app.factory('to_date_without_timezone', function () {
    return function (date) {
        date = new Date(date + 'T00:00:00');
        var userTimezoneOffset = new Date().getTimezoneOffset()*60000;
        return new Date(date.getTime() + userTimezoneOffset);        
    };
});

sgpi_app.filter('date_to_string', function () {
    return function(date){
        if(date != null && date != undefined)
        {
            return date.toISOString().substring(0, 10);
        }
        return null;
    };
});

sgpi_app.controller('editar_productos_controller', function ($scope, $http, id_proyecto, to_date_without_timezone, validar_cadena) {

    // muestra velo inicial con mensaje de cargando datos iniciales
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando productos del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $scope.visibilidad.show_velo_general = true;
    $scope.productos = [];
    
    $scope.$watch('productos', function () {
        setTimeout(function(){ $('#contenedor_productos').perfectScrollbar('update'); }, 200);
    }, true);
    
    // consulta los productos del proyecto y demas colecciones necesarias
    $http({
        url: '/proyectos/info_editar_productos',
        method: 'GET',
        params: {
            id_proyecto: id_proyecto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            $scope.init(data);
            $scope.visibilidad.show_velo_general = false;
        }
        else
        {
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error consultar los productos del proyecto. Código de error: ' + data.codigo + '</h3>';
        }
    })
    .error(function (data, status) {
        console.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error XHR o de servidor al consultar los productos del proyecto. Código de estado: ' + status + '</h3>';
    });
    
    // inicializa los modelos
    $scope.init = function(data){
        
        // inicializa las colecciones de tipos de productos y participantes del proyecto
        $scope.tipos_productos_generales = data.tipos_productos_generales;
        $scope.productos_especificos_x_prod_general = data.productos_especificos_x_prod_general;
        $scope.tipos_productos_especificos = [];
        $scope.participantes = data.investigadores ;
        
        // inicializa los productos existentes
        $scope.init_productos_existentes(data);
        
    };
    
    // inicializa los productos existentes
    $scope.init_productos_existentes = function(data) {
        
        var BreakException = {};
        
        data.productos.forEach(function(producto) {
            
            var modelo_producto = {
                id_producto: producto.id
            };
            
            // establece la colección de tipos de productos específicos del ui-select para el modelo del producto
            var tipo_especifico = null;
            try{
                for(var key in $scope.productos_especificos_x_prod_general){
                    for (var i = 0; i < $scope.productos_especificos_x_prod_general[key].tipos_productos_especificos.length; i++) {
                        tipo_especifico = $scope.productos_especificos_x_prod_general[key].tipos_productos_especificos[i];
                        if(tipo_especifico.id == producto.id_tipo_producto_especifico)
                        {
                            modelo_producto['coleccion_tipos_productos_especificos'] = $scope.productos_especificos_x_prod_general[key].tipos_productos_especificos;
                            throw BreakException;
                        }
                    }
                }
            } 
            catch (e) {
              if (e !== BreakException) throw e;
            }
            
            // selecciona del ui-select el tipo de producto específico
            for (var i = 0; i < modelo_producto.coleccion_tipos_productos_especificos.length; i++) {
                tipo_especifico = modelo_producto.coleccion_tipos_productos_especificos[i];
                if(tipo_especifico.id == producto.id_tipo_producto_especifico)
                {
                    modelo_producto['tipo_producto_especifico'] = tipo_especifico;
                }
            }
            
            // selecciona del ui-select de tipos de productos generales la opcion correspondiente
            for (var i = 0; i < $scope.tipos_productos_generales.length; i++) {
                if($scope.tipos_productos_generales[i].id == modelo_producto.tipo_producto_especifico.id_tipo_producto_general)
                {
                    modelo_producto['tipo_producto_general'] = $scope.tipos_productos_generales[i];
                    break;
                }
            }
            
            // agrega el nombre del producto
            modelo_producto['nombre'] = producto.nombre;
            
            // selecciona el participante encargado del ui-select
            for (var i = 0; i < $scope.participantes.length; i++) {
                if($scope.participantes[i].id_investigador == producto.id_investigador)
                {
                    modelo_producto['participante'] = $scope.participantes[i];
                    break;
                }
            }
            
            // inicaliza las fechas
            modelo_producto['fecha_aprobacion_publicacion'] = to_date_without_timezone(producto.fecha_aprobacion_publicacion);
            modelo_producto['fecha_confirmacion_editorial'] = to_date_without_timezone(producto.fecha_confirmacion_editorial);
            modelo_producto['fecha_proyectada_radicacion'] = to_date_without_timezone(producto.fecha_proyectada_radicacion);
            modelo_producto['fecha_publicacion'] = to_date_without_timezone(producto.fecha_publicacion);
            modelo_producto['fecha_recepcion_evaluacion'] = to_date_without_timezone(producto.fecha_recepcion_evaluacion);
            modelo_producto['fecha_remision'] = to_date_without_timezone(producto.fecha_remision);
            modelo_producto['fecha_respuesta_evaluacion'] = to_date_without_timezone(producto.fecha_respuesta_evaluacion);
            $scope.productos.push(modelo_producto);
        });
    };
    
    // ngChange para ui-select de tipo de producto general
    // alimenta las opciones del elemento select de tipo de producto específico
    $scope.cambia_tipo_producto_general = function(producto) {
        var validacion = $scope.validar_tipo_producto_general(producto);
        if(validacion) return; // no hay un tipo de producto general seleccionado
        producto.tipo_producto_especifico = null;
        producto.coleccion_tipos_productos_especificos = $scope.productos_especificos_x_prod_general[producto.tipo_producto_general.id].tipos_productos_especificos;
        $scope.validar_tipo_producto_especifico(producto);
    };
    
    // validacion de modelo de ui-select de tipo de prodcuto general
    $scope.validar_tipo_producto_general = function(producto) {
        var validacion = true;
        if(producto.tipo_producto_general)
            validacion = false;
        producto.tipo_producto_general_invalido = validacion;
        return validacion;
    };
    
    // validacion de modelo de elemento select de tipo de producto específico
    $scope.validar_tipo_producto_especifico = function(producto) {
        var validacion = true;
        if(producto.tipo_producto_especifico)
            validacion = false;
        producto.tipo_producto_especifico_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_nombre_producto = function(producto) {
        var validacion = validar_cadena(producto.nombre, 5, 200);
        producto.nombre_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_participante_encargado = function(producto) {
        var validacion = true;
        if(producto.participante)
            validacion = false;
        producto.participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_fecha = function(nombre_modelo_fecha, producto) {
        var validacion = true;
        if(producto[nombre_modelo_fecha])
            validacion = false;
        producto[nombre_modelo_fecha + '_invalido'] = validacion;
        
        // si es la fecja de publicación se verifica que sea mayor a la fecha proyectada de radicación
        if(nombre_modelo_fecha == 'fecha_publicacion')
        {
            if(validacion == false) // si hay fecha de publicación
            {
                if(producto.fecha_proyectada_radicacion) // y si hay fecha proyectada de radicación
                {
                    if(producto.fecha_publicacion < producto.fecha_proyectada_radicacion)
                    {
                        validacion = true; // validacion incorrecta
                        producto.fecha_publicacion_invalido = true; 
                    }
                }
            }
        }
        
        return validacion;        
    };
    
    $scope.agregar_producto = function() {
        $scope.productos.push({
            id_producto: 'nuevo',
            nombre: null,
            participante: null,
            coleccion_tipos_productos_especificos: [],
            tipo_producto_especifico: null,
            tipo_producto_general: null,
            fecha_aprobacion_publicacion: null,
            fecha_confirmacion_editorial: null,
            fecha_proyectada_radicacion: null,
            fecha_publicacion: null,
            fecha_recepcion_evaluacion: null,
            fecha_remision: null,
            fecha_respuesta_evaluacion: null
        });
    };
    
    $scope.remover_producto = function(producto) {
        
        if(producto.id_producto == 'nuevo')
        {
            var index_producto = $scope.productos.indexOf(producto);
            
            if(index_producto != -1)
                $scope.productos.splice(index_producto, 1);            
        }
        else
        {
            if(producto.tiene_postulacion_publicacion)
            {
                alertify.error('No se puede eliminar el producto a que cuenta con postulacion o publicación cargada');
                return;
            }
            var index_producto = $scope.productos.indexOf(producto);
            
            if(index_producto != -1)
                $scope.productos.splice(index_producto, 1);
            
            $('#contenedor_productos_a_eliminar')
                .append('<input type="hidden" name="productos_a_eliminar[]" value="' + producto.id_producto + '"/>');
        }
        
    };
    
    // envía formulario antes validando los datos ingresados
    $scope.guardar = function() {
        if($scope.productos.length == 0)
        {
            alertify.error('Se debe ingresar un producto como mínimo');
            return;
        }
        
        var validacion = false;
        $scope.productos.forEach(function(producto) {
            validacion |= $scope.validar_tipo_producto_general(producto);
            validacion |= $scope.validar_tipo_producto_especifico(producto);
            validacion |= $scope.validar_nombre_producto(producto);
            validacion |= $scope.validar_participante_encargado(producto);
            validacion |= $scope.validar_fecha('fecha_proyectada_radicacion', producto);
            validacion |= $scope.validar_fecha('fecha_remision', producto);
            validacion |= $scope.validar_fecha('fecha_confirmacion_editorial', producto);
            validacion |= $scope.validar_fecha('fecha_recepcion_evaluacion', producto);
            validacion |= $scope.validar_fecha('fecha_respuesta_evaluacion', producto);
            validacion |= $scope.validar_fecha('fecha_aprobacion_publicacion', producto);
            validacion |= $scope.validar_fecha('fecha_publicacion', producto);
        });
        if(validacion)
        {
            alertify.error('Error en validacion. Verificar datos ingresados');
            return;
        }
        
        alertify.success('Guardando cambios');
        $scope.data.msj_operacion_general = '<h3 class="text-center">Guardando cambios...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
        $scope.visibilidad.show_velo_general = true;
        $('#input_submit_form').trigger('click');
        
    };
});
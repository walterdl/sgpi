
sgpi_app.controller('editar_datos_basicos_controller', function($scope, $http, $window, $filter, id_usuario, id_proyecto){
    
    $(document).ready(function () {
        $($window).bind('resize', function () {
            $scope.windowInnerWidth = $window.innerWidth;
            $scope.$apply();
        });
        $scope.windowInnerWidth = $window.innerWidth;
        $scope.$apply();
    });    
    
    // inicialización de variables
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $scope.visibilidad.show_velo_general = true;
    var regex = /^\d+$/; // expresión regular para la validación de números enteros
    
    // configuración para los datepicker
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };        
    
    // consulta por los datos iniciales de la vista
    $http({
        url: '/proyectos/info_editar_datos_basicos',
        method: 'GET',
        params: {
            id_usuario: id_usuario,
            id_proyecto: id_proyecto,
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            var date = new Date(data.proyecto.fecha_inicio + 'T00:00:00');
            var userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            data.proyecto.fecha_inicio = new Date(date.getTime() + userTimezoneOffset);
            
            date = new Date(data.proyecto.fecha_fin + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;            
            data.proyecto.fecha_fin = new Date(date.getTime() + userTimezoneOffset);
            
            data.proyecto.duracion_meses = Number(data.proyecto.duracion_meses);
            data.proyecto.anio_convocatoria = Number(data.proyecto.anio_convocatoria);
            
            $scope.proyecto = data.proyecto;
            $scope.objetivos_especificos = data.objetivos_especificos;
            $scope.informacion_proyecto = data.informacion_proyecto;
            
            $scope.visibilidad.show_velo_general = false;
        }
        else
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos del proyecto. Código de error: ' + data.codigo + '</h3>';            
    })
    .error(function(data, status) {
        console.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos del proyecto. Código de estado: ' + status + '</h3>';            
    });

    /*
	|--------------------------------------------------------------------------
	| add_objetivo_especifico()
	|--------------------------------------------------------------------------
	| Añade una fila a la tabla de objetivos específicos
	*/    
    $scope.add_objetivo_especifico = function(){
        $scope.objetivos_especificos.push({
            id: null,
            nombre: null,
            invalido: false,
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| eliminar_objetivo_especifico()
	|--------------------------------------------------------------------------
	| Remueve un objetivo específico determinado
	*/        
    $scope.eliminar_objetivo_especifico = function(objetivo_especifico){
        
        if($scope.objetivos_especificos.length == 1){
            alertify.error('Eliminación de objetivo específico cancelada. El proyecto debe tener un objetivo específico como mínimo');
            return;
        }
        
        if(objetivo_especifico.id == null){
            // se elimina un objetivo especifico recientemente agregado desde GUI
            var indice_obj_esp = $scope.objetivos_especificos.indexOf(objetivo_especifico);
            $scope.objetivos_especificos.splice(indice_obj_esp, 1);
        }
        else{
            // se elimina un objetivo existente en la BD
            // se pregunta confirmacion de su eliminacion al usuario
            // se añade input hidden para identificar el obj. espf a eliminar en el server
            alertify.confirm('Eliminar objetivo específico', 'El objetivo específico "' + objetivo_especifico.nombre + '" actualmente hace parte del proyecto, ¿Confirma su eliminación?', 
            
                function () {
                    $('#objetivos_especificos_a_eliminar').append('<input type="hidden" name="objetivos_especificos_existentes_a_eliminar[]" value="' + objetivo_especifico.id + '"/>');                    
                    var indice_obj_esp = $scope.objetivos_especificos.indexOf(objetivo_especifico);
                    $scope.objetivos_especificos.splice(indice_obj_esp, 1);
                    $scope.$apply();
                },
                function(){ /*Cancela operación*/ })
                .set('labels', {ok:'Eliminar', cancel:'Cancelar'});   
        }
    }; 

    /*
	|--------------------------------------------------------------------------
	| validar_info_general()
	|--------------------------------------------------------------------------
	| valida todos los campos de la pestaña de información general
	*/            
    $scope.validar_info_general = function() {
        var validacion = [
            $scope.validar_codigo_fmi(),
            $scope.validar_subcentro_costo(),
            $scope.validar_nombre_proyecto(),
            $scope.validar_fecha_inicio(),
            $scope.validar_duracion_meses(),
            $scope.validar_convocatoria(),
            $scope.validar_anio_convocatoria(),
            $scope.validar_objetivo_general(),
            $scope.validar_objetivos_especificos()
            ];
        if(validacion.indexOf(true) != -1) // hubo un error
        {
            alertify.error('Validación de información general incorrecta');
        }
        else
        {
            $scope.data.msj_operacion_general = '<h3 class="text-center">Guardando ediciones del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
            $scope.visibilidad.show_velo_general = true;
            $('#input_editar_proyecto').trigger('click');
        }
    };

    /*
	|--------------------------------------------------------------------------
	| calcular_fecha_final()
	|--------------------------------------------------------------------------
	| ng-change para el cambio de duracion en meses y fecha de inicio. 
	| Calcula la fecha final junto con el valor de la fecha de inicio del proyecto
	*/        
    $scope.calcular_fecha_final = function(){
        
        if(regex.test($scope.proyecto.duracion_meses) && $scope.proyecto.duracion_meses >= 12 && $scope.proyecto.fecha_inicio){
            var fecha_inicio = new Date($scope.proyecto.fecha_inicio.getFullYear(), $scope.proyecto.fecha_inicio.getMonth(), $scope.proyecto.fecha_inicio.getDate());
            $scope.proyecto.fecha_fin = fecha_inicio.setMonth(fecha_inicio.getMonth() + $scope.proyecto.duracion_meses);
        }
        else{
            $scope.proyecto.fecha_fin = null;
        }
        $scope.validar_fecha_inicio();
        $scope.validar_duracion_meses();
    };
    
     
    // ****Todas las validaciones retornan true si la validacion es incorrecta
    /*
	|--------------------------------------------------------------------------
	| validar_codigo_fmi() 
	|--------------------------------------------------------------------------
	| valida codigo_fmi verificando que halla texto y que sea mayor a 2 y menor a 50
	*/             
    $scope.validar_codigo_fmi = function() {
        if($scope.proyecto.codigo_fmi && $scope.proyecto.codigo_fmi.length >= 2 && $scope.proyecto.codigo_fmi.length <= 50){
            $scope.codigo_fmi_invalido = false;
            return false;
        }
        $scope.codigo_fmi_invalido = true;
        return true;
    };

    /*
	|--------------------------------------------------------------------------
	| validar_subcentro_costo() 
	|--------------------------------------------------------------------------
	| valida subcentro_costo verificando que halla texto y que sea mayor a 2 y menor a 50
	*/         
    $scope.validar_subcentro_costo = function() {
        if($scope.proyecto.subcentro_costo && $scope.proyecto.subcentro_costo.length >= 2 && $scope.proyecto.subcentro_costo.length <= 50){
            $scope.subcentro_costo_invalido = false;
            return false;
        }
        $scope.subcentro_costo_invalido = true;
        return true;        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_nombre_proyecto() 
	|--------------------------------------------------------------------------
	| valida nombre_proyecto verificando que halla texto y que sea mayor a 2 y menor a 200
	*/             
    $scope.validar_nombre_proyecto = function() {
        if($scope.proyecto.nombre && $scope.proyecto.nombre.length >= 5 && $scope.proyecto.nombre.length <= 200){
            $scope.nombre_proyecto_invalido = false;
            return false;
        }
        $scope.nombre_proyecto_invalido = true;
        return true;                
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_inicio() 
	|--------------------------------------------------------------------------
	| valida fecha_inicio verificando que sea diferente de null y undefinded
	*/                 
    $scope.validar_fecha_inicio = function() {
        if($scope.proyecto.fecha_inicio){
            $scope.fecha_inicio_invalido = false;
            return false;
        }
        $scope.fecha_inicio_invalido = true;
        return true;                        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_duracion_meses() 
	|--------------------------------------------------------------------------
	| valida duracion_meses verificando que sea diferente de null y que sea mayor o igual a 12
	*/                     
    $scope.validar_duracion_meses = function() {
        if(regex.test($scope.proyecto.duracion_meses) && $scope.proyecto.duracion_meses >= 12){
            $scope.duracion_meses_invalido = false;
            return false;
        }
        $scope.duracion_meses_invalido = true;
        return true;                               
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_convocatoria() 
	|--------------------------------------------------------------------------
	| valida convocatoria permitiendo una cadena de texto con longitud mayor o igual a 5 y menor a 150 caractéres
	| este valor es opcional por lo que se permite valores null
	*/               
    $scope.validar_convocatoria = function() {

        if($scope.proyecto.convocatoria == null || $scope.proyecto.convocatoria == undefined || $scope.proyecto.convocatoria.length == 0)
        {
            $scope.convocatoria_invalido = false;
            return false;
        }
        if($scope.proyecto.convocatoria.length >= 5 && $scope.proyecto.convocatoria.length <= 150)
        {
            $scope.convocatoria_invalido = false;
            return false;            
        }
        $scope.convocatoria_invalido = true;
        return true;                    
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_anio_convocatoria() 
	|--------------------------------------------------------------------------
	| valida que sea un valor entero válido
	| este valor es opcional por lo que se permite valores null
	*/                   
    $scope.validar_anio_convocatoria = function() {
        if($scope.proyecto.anio_convocatoria === null)
        {
            $scope.anio_convocatoria_invalido = false;
            return false;
        }
        if(regex.test($scope.proyecto.anio_convocatoria))
        {
            $scope.anio_convocatoria_invalido = false;
            return false;            
        }
        $scope.anio_convocatoria_invalido = true;
        return true;                    
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_objetivo_general() 
	|--------------------------------------------------------------------------
	| valida objetivo_general verificando que la longitud sea mayor a 2 y menor a 200
	*/              
    $scope.validar_objetivo_general = function() {
        if($scope.proyecto.objetivo_general && $scope.proyecto.objetivo_general.length >= 5 && $scope.proyecto.objetivo_general.length <= 200){
            $scope.objetivo_general_invalido = false;
            return false;
        }
        $scope.objetivo_general_invalido = true;
        return true;
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_objetivos_especificos() 
	|--------------------------------------------------------------------------
	| valida todos o un objetivo específico verificando que la longitud sea mayor a 5 y menor a 200.
	| Se valida solo un objetivo específico si el parametro objetivo_especifico es != null
	*/                  
    $scope.validar_objetivos_especificos = function(objetivo_especifico=null){
        
        if(objetivo_especifico){ // si se especifica la validacion para un objetivo específico determinado
            if(objetivo_especifico.nombre && objetivo_especifico.nombre.length >= 5 && objetivo_especifico.nombre.length <= 200){
                objetivo_especifico.invalido = false;
                return false;
            }
            else{
                objetivo_especifico.invalido = true;
                return true;
            }
        }
        else{
            // se validan todos los objetivos específicos
            if($scope.objetivos_especificos.length == 0)
                return true;
            
            var resultado_validacion = false;
            for(var i = 0; i < $scope.objetivos_especificos.length; i++){
                objetivo_especifico = $scope.objetivos_especificos[i];
                if(objetivo_especifico.nombre && objetivo_especifico.nombre.length >= 5 && objetivo_especifico.nombre.length <= 200){
                    objetivo_especifico.invalido = false;
                }
                else{
                    objetivo_especifico.invalido = true;
                    resultado_validacion = true;                    
                }
            }
            return resultado_validacion;
        }
    };
});
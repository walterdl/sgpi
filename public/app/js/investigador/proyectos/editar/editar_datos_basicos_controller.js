var id_proyecto;
var pagina;


sgpi_app.controller('crear_proyecto_controller', function($scope, $http, $log, $window, $filter, id_usuario){
    
    // inicialización de variables
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $scope.visibilidad.show_velo_general = true;
    
    $scope.data.validacion_codigo_fmi = null;
    $scope.data.validacion_subcentro_costo = null;
    $scope.data.validacion_nombre_proyecto = null;
    $scope.data.validacion_fecha_inicio = null;
    $scope.data.validacion_duracion_meses = null;
    $scope.data.validacion_objetivo_general = null;
    
    $scope.data.producto=[];
    $scope.data.info_investigadores_usuario=[];
    $scope.data.entidad_fuente_presupuesto_seleccionadas = [];
    $scope.data.info_productos=[];
    
    // $scope.data.entidades_presupuesto_seleccionadas = [];
    
    ///fuentes de presupuesto participantes
    $scope.data.fuente_presupuesto={
        presupuesto:null,
        total_gastos_columnas:null,
        total_gastos_global:null,
    };

    $scope.data.objetivos_especificos = [{
            nombre: null,
            validacion: null
    }];
    
    $scope.data.validacion_objetivos_especificos = [];
    
    // configuración para los datepicker
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };        
    
    // consulta por los datos iniciales de la vista
    $http({
        url: '/proyectos/datos_iniciales_editar_proyecto',
        method: 'GET',
        params: {
            id_usuario: id_usuario,
            id_proyecto:id_proyecto,
            pagina:pagina,
        }
    })
    .success(function(data) {
        if(data.consultado == 1){

            $log.log(data);
            
            // convierte los datos string a int y date para algunos ng-model que requieren que los datos sean en dichos tipos de datos
            var date = new Date(data.proyecto.fecha_inicio + 'T00:00:00');
            var userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            data.proyecto.fecha_inicio = new Date(date.getTime() + userTimezoneOffset);
            
            date = new Date(data.proyecto.fecha_fin + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;            
            data.proyecto.fecha_fin = new Date(date.getTime() + userTimezoneOffset);
            
            data.proyecto.duracion_meses = Number(data.proyecto.duracion_meses);
            data.proyecto.anio_convocatoria = Number(data.proyecto.anio_convocatoria);
            
            // agrega al modelo proyecto los datos consultados y ya convertidos
            $scope.data.proyecto = data.proyecto;
            
            // oculta velo para permitir el control de los controles de usuario
            $scope.visibilidad.show_velo_general = false;
        }
        else{
            $log.log(data);
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos iniciales. Código de error: ' + data.codigo + '</h3>';
            alertify.error('Error al cargar los datos iniciales. Código de error: ' + data.codigo);
        }
    })
    .error(function(data, status) {
        $log.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos iniciales2. Código de estado: ' + status + '</h3>';
        alertify.error('Error al cargar los datos iniciales. Código de estado: ' + status);
    });
    
    $(document).ready(function () {
        $($window).bind('resize', function () {
            $scope.windowInnerWidth = $window.innerWidth;
            $scope.$apply();
        });
        $scope.windowInnerWidth = $window.innerWidth;
        $scope.$apply();
    });
    
    /*
	|--------------------------------------------------------------------------
	| add_objetivo_especifico()
	|--------------------------------------------------------------------------
	| Añade una fila a la tabla de objetivos específicos
	*/    
    $scope.add_objetivo_especifico = function(){
        $scope.data.proyecto.objetivos_especificos.push({
            id:null,
            nombre: null,
            validacion: null,
            nuevo:true
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| eliminar_objetivo_especifico()
	|--------------------------------------------------------------------------
	| Remueve un objetivo específico determinado
	*/        
    $scope.eliminar_objetivo_especifico = function(objetivo_especifico){
        
        if($scope.data.proyecto.objetivos_especificos.length == 1){
            alertify.error('Eliminación de objetivo específico cancelada. El proyecto debe tener un objetivo específico como mínimo');
            return;
        }
        
        if(objetivo_especifico.id == null){
            // se elimina un objetivo especifico recientemente agregado desde GUI
            var indice_obj_esp = $scope.data.proyecto.objetivos_especificos.indexOf(objetivo_especifico);
            $scope.data.proyecto.objetivos_especificos.splice(indice_obj_esp, 1);
            $scope.data.validacion_objetivos_especificos.splice(indice_obj_esp, 1);
        }
        else{
            // se elimina un objetivo existente en la BD
            // se pregunta confirmacion de su eliminacion al usuario
            // se añade input hidden para identificar el obj. espf a eliminar en el server
            alertify.confirm('Eliminar objetivo específico', 'El objetivo específico "' + objetivo_especifico.nombre + '" actualmente hace parte del proyecto, ¿Confirma su eliminación?', 
            
                function () {
                    $('#objetivos_especificos_a_eliminar').append('<input type="hidden" name="objetivos_especificos_existentes_a_eliminar[]" value="' + objetivo_especifico.id + '"/>');                    
                    var indice_obj_esp = $scope.data.proyecto.objetivos_especificos.indexOf(objetivo_especifico);
                    $scope.data.proyecto.objetivos_especificos.splice(indice_obj_esp, 1);
                    $scope.data.validacion_objetivos_especificos.splice(indice_obj_esp, 1);                    
                    $scope.$apply();
                },
                function(){ /*Cancela operación*/ })
                .set('labels', {ok:'Eliminar', cancel:'Cancelar'});   
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
        
        if($scope.data.proyecto.duracion_meses && $scope.data.proyecto.duracion_meses >= 12 && $scope.data.proyecto.fecha_inicio){
            var fecha_inicio = new Date($scope.data.proyecto.fecha_inicio.getFullYear(), $scope.data.proyecto.fecha_inicio.getMonth(), $scope.data.proyecto.fecha_inicio.getDate());
            $scope.data.proyecto.fecha_fin = fecha_inicio.setMonth(fecha_inicio.getMonth() + $scope.data.proyecto.duracion_meses);
        }
        else{
            $scope.data.proyecto.fecha_fin = null;
        }
        $scope.validar_fecha_inicio();
        $scope.validar_duracion_meses();
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
     
    // ****Todas las validaciones retornan true si la validacion es incorrecta
    /*
	|--------------------------------------------------------------------------
	| validar_codigo_fmi() 
	|--------------------------------------------------------------------------
	| valida codigo_fmi verificando que halla texto y que sea mayor a 2 y menor a 50
	*/             
    $scope.validar_codigo_fmi = function() {
        if($scope.data.proyecto.codigo_fmi && $scope.data.proyecto.codigo_fmi.length > 2 && $scope.data.proyecto.codigo_fmi.length < 50){
            // valido
            $scope.data.validacion_codigo_fmi = null;
            return false;
        }
        $scope.data.validacion_codigo_fmi = 'Longitud mínima 2 caracteres';
        return true;
    };

    /*
	|--------------------------------------------------------------------------
	| validar_subcentro_costo() 
	|--------------------------------------------------------------------------
	| valida subcentro_costo verificando que halla texto y que sea mayor a 2 y menor a 50
	*/         
    $scope.validar_subcentro_costo = function() {
        if($scope.data.proyecto.subcentro_costo && $scope.data.proyecto.subcentro_costo.length > 2 && $scope.data.proyecto.subcentro_costo.length < 50){
            // valido
            $scope.data.validacion_subcentro_costo = null;
            return false;
        }
        $scope.data.validacion_subcentro_costo = 'Longitud mínima 2 caracteres';
        return true;        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_nombre_proyecto() 
	|--------------------------------------------------------------------------
	| valida nombre_proyecto verificando que halla texto y que sea mayor a 2 y menor a 200
	*/             
    $scope.validar_nombre_proyecto = function() {
        if($scope.data.proyecto.nombre && $scope.data.proyecto.nombre.length > 2 && $scope.data.proyecto.nombre.length < 200){
            // valido
            $scope.data.validacion_nombre_proyecto = null;
            return false;
        }
        $scope.data.validacion_nombre_proyecto = 'Longitud mínima 2 caracteres y máxima 200';
        return true;                
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_fecha_inicio() 
	|--------------------------------------------------------------------------
	| valida fecha_inicio verificando que sea diferente de null y undefinded
	*/                 
    $scope.validar_fecha_inicio = function() {
        if($scope.data.proyecto.fecha_inicio){
            // valido
            $scope.data.validacion_fecha_inicio = null;
            return false;
        }
        $scope.data.validacion_fecha_inicio = 'Campo incorrecto. Seleccionar fecha';
        return true;                        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_duracion_meses() 
	|--------------------------------------------------------------------------
	| valida duracion_meses verificando que sea diferente de null y que sea mayor o igual a 12
	*/                     
    $scope.validar_duracion_meses = function() {
        if($scope.data.proyecto.duracion_meses && $scope.data.proyecto.duracion_meses >= 12){
            // valido
            $scope.data.validacion_duracion_meses = null;
            return false;
        }
        $scope.data.validacion_duracion_meses = 'Minimo debe ser 12 meses';
        return true;                               
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_objetivo_general() 
	|--------------------------------------------------------------------------
	| valida objetivo_general verificando que la longitud sea mayor a 2 y menor a 200
	*/              
    $scope.validar_objetivo_general = function() {
        if($scope.data.proyecto.objetivo_general != null && $scope.data.proyecto.objetivo_general.length > 2 && $scope.data.proyecto.objetivo_general.length < 200){
            $scope.data.validacion_objetivo_general = null;
            return false;
        }
        else{
            $scope.data.validacion_objetivo_general = 'Longitud mínima de 5 caracteres y máxima de 200';
            return true;                                
        }
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
            if(objetivo_especifico.nombre != null && objetivo_especifico.nombre.length > 5 && objetivo_especifico.nombre.length < 200){
                objetivo_especifico.validacion = null;
                return false;
            }
            else{
                objetivo_especifico.validacion = 'Longitud mínima de 5 caracteres y máxima de 200';
                return true;
            }
        }
        else{
            // se validan todos los objetivos específicos
            if($scope.data.proyecto.objetivos_especificos.length == 0)
                return true;
            
            var resultado_validacion = false;
            for(var i = 0; i < $scope.data.proyecto.objetivos_especificos.length; i++){
                objetivo_especifico = $scope.data.proyecto.objetivos_especificos[i];
                if(objetivo_especifico.nombre != null && objetivo_especifico.nombre.length > 5 && objetivo_especifico.nombre.length < 200){
                    objetivo_especifico.validacion = null;
                }
                else{
                    objetivo_especifico.validacion = 'Longitud mínima de 5 caracteres y máxima de 200';
                    resultado_validacion = true;                    
                }
            }
            return resultado_validacion;
        }
    };
});
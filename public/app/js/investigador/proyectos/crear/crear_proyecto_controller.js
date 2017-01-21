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
        url: '/proyectos/data_inicial_crear_proyecto',
        method: 'GET',
        params: {
            id_usuario: id_usuario
        }
    })
    .success(function(data) {
        if(data.consultado == 1){
            $log.log(data);
            $scope.data.roles = data.roles;
            $scope.data.sedes = data.sedes;
            $scope.data.grupos_investigacion_y_sedes = data.grupos_investigacion_y_sedes;
            $scope.data.facultades_dependencias = data.facultades_dependencias;
            $scope.data.tipos_identificacion = data.tipos_identificacion;
            $scope.data.info_investigador_principal = data.info_investigador_principal;
            data.tipos_productos_generales.forEach(function(item) {
                item.nombre = $filter('capitalizeWords')(item.nombre);
            });
            $scope.data.tipos_productos_generales = data.tipos_productos_generales;
            $scope.data.productos_especificos_x_prod_general = data.productos_especificos_x_prod_general;
            $scope.data.tipos_productos_especificos = [];
            $scope.visibilidad.show_velo_general = false;
            $scope.data.entidades_fuente_presupuesto = data.entidades_fuente_presupuesto;
            
            // se agrega investigador principal a colección de participantes del proyecto            
            $scope.data.participantes_proyecto.push({
                    es_investigador_principal: true,
                    nombres: data.info_investigador_principal.nombres,
                    apellidos: data.info_investigador_principal.apellidos,
                    identificacion: data.info_investigador_principal.identificacion,
                    formacion: data.info_investigador_principal.formacion,
                    rol: data.info_investigador_principal.nombre_rol, 
                    id_rol: data.info_investigador_principal.id_rol,
                    tipo_identificacion: data.info_investigador_principal.nombre_tipo_identificacion,
                    id_tipo_identificacion: data.info_investigador_principal.id_tipo_identificacion,
                    sexo: data.info_investigador_principal.sexo=='m' ? 'Hombre' : 'Mujer',
                    id_sexo: data.info_investigador_principal.sexo,
                    edad: data.info_investigador_principal.edad,
                    email: data.info_investigador_principal.email,
                    sede: data.info_investigador_principal.nombre_sede,
                    id_sede: data.info_investigador_principal.id_sede,
                    grupo_investigacion: data.info_investigador_principal.nombre_grupo_inv,
                    id_grupo_investigacion: data.info_investigador_principal.id_grupo_inv,
                    facultad_dependencia: data.info_investigador_principal.nombre_facultad, 
                    id_facultad_dependencia: data.info_investigador_principal.id_facultad_dependencia, 
                    entidad_grupo_inv_externo: null,
                    programa_academico: null,
                    dedicacion_semanal: 0,
                    total_semanas: 0,
                    valor_hora: 0,
                    presupuesto_ucc: 0,
                    otras_entidades_presupuesto: [],
                    presupuesto_total: 0,       
                    presupuesto_externo_invalido: [],
                    fecha_ejecucion: null,
                    fecha_ejecucion_invalido: false
                });
        }
        else{
            $log.log(data);
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos iniciales. Código de error: ' + data.codigo + '</h3>';
            alertify.error('Error al cargar los datos iniciales. Código de error: ' + data.codigo);
        }
    })
    .error(function(data, status) {
        $log.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error al cargar los datos iniciales. Código de error: ' + status + '</h3>';
        alertify.error('Error al cargar los datos iniciales. Código de error: ' + status);
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
        $scope.data.objetivos_especificos.push({
            nombre: null,
            validacion: null
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| eliminar_objetivo_especifico()
	|--------------------------------------------------------------------------
	| Remueve un objetivo específico determinado
	*/        
    $scope.eliminar_objetivo_especifico = function(objetivo_especifico){
        var indice_obj_esp = $scope.data.objetivos_especificos.indexOf(objetivo_especifico);
        $scope.data.objetivos_especificos.splice(indice_obj_esp, 1);
        $scope.data.validacion_objetivos_especificos.splice(indice_obj_esp, 1);
    }; 

    /*
	|--------------------------------------------------------------------------
	| calcular_fecha_final()
	|--------------------------------------------------------------------------
	| ng-change para el cambio de duracion en meses y fecha de inicio. 
	| Calcula la fecha final junto con el valor de la fecha de inicio del proyecto
	*/        
    $scope.calcular_fecha_final = function(){
        if($scope.data.duracion_meses && $scope.data.duracion_meses >= 12 && $scope.data.fecha_inicio){
            var fecha_inicio_obj = new Date($scope.data.fecha_inicio.getFullYear(), $scope.data.fecha_inicio.getMonth(), $scope.data.fecha_inicio.getDate());
            $scope.data.fecha_final = fecha_inicio_obj.setMonth(fecha_inicio_obj.getMonth() + $scope.data.duracion_meses);
        }
        else{
            $scope.data.fecha_final = null;
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
            $('a[href="#contenido_participantes"]').tab('show');
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
        if($scope.data.codigo_fmi && $scope.data.codigo_fmi.length > 2 && $scope.data.codigo_fmi.length < 50){
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
        if($scope.data.subcentro_costo && $scope.data.subcentro_costo.length > 2 && $scope.data.subcentro_costo.length < 50){
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
        if($scope.data.nombre_proyecto && $scope.data.nombre_proyecto.length > 2 && $scope.data.nombre_proyecto.length < 200){
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
        if($scope.data.fecha_inicio){
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
        if($scope.data.duracion_meses && $scope.data.duracion_meses >= 12){
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
        if($scope.data.objetivo_general != null && $scope.data.objetivo_general.length > 2 && $scope.data.objetivo_general.length < 200){
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
            if($scope.data.objetivos_especificos.length == 0)
                return true;
            
            var resultado_validacion = false;
            for(var i = 0; i < $scope.data.objetivos_especificos.length; i++){
                objetivo_especifico = $scope.data.objetivos_especificos[i];
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
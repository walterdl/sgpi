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

            // Código recuperado de Brandon
            $scope.data.roles = data.roles;
            $scope.data.sedes = data.sedes;

            if(pagina == 2){
                // data.info_investigadores_usuario.forEach(function(entry) {
                //     console.log(entry);
                //     //data.info_investigador_principal.sexo=='m' ? 'Hombre' : 'Mujer',
                //     if(entry.investigador_principarl == 0){
                //         //console.log("hola soy uinchi");
                //         entry.info_investigador.sexo==='m' ? 'Hombre' : 'Mujer';
                //     }
                // });
                $scope.data.info_investigadores_usuario=data.info_investigadores_usuario;
            }
            else if(pagina == 3){
                
                $scope.data.info_investigadores_usuario=data.info_investigadores_usuario;
     
                data.info_productos.forEach(function(entry) {

                    if(entry.producto){
                        
                        // date = new Date(entry.producto.fecha_proyectada_radicacion + 'T00:00:00');
                        // userTimezoneOffset = new Date().getTimezoneOffset()*60000;            
                        // entry.producto.fecha_proyectada_radicacion = new Date(date.getTime() + userTimezoneOffset);
                        
                        
                        entry.producto.fecha_proyectada_radicacion=Date.parse(entry.producto.fecha_proyectada_radicacion);
                        entry.producto.fecha_remision=Date.parse(entry.producto.fecha_remision);
                        entry.producto.fecha_confirmacion_editorial=Date.parse(entry.producto.fecha_confirmacion_editorial);
                        entry.producto.fecha_recepcion_evaluacion=Date.parse(entry.producto.fecha_recepcion_evaluacion);
                        entry.producto.fecha_respuesta_evaluacion=Date.parse(entry.producto.fecha_respuesta_evaluacion);
                        entry.producto.fecha_aprobacion_publicacion=Date.parse(entry.producto.fecha_aprobacion_publicacion);
                        entry.producto.fecha_publicacion=Date.parse(entry.producto.fecha_publicacion);
                        
                        entry.producto.investigador.persona={
                            info_investigador:entry.producto.investigador.persona,
                            resgitrado:1,
                        }
                        //$scope.data.producto.push(entry);
                        //console.log($scope.data.producto);
                    }
                });
                
                $scope.data.info_productos=data.info_productos;
                // $scope.data.info_productos=data.info_productos;
            }
            else if(pagina == 4){
                
                var cont=0;
                
                ///////gasto personal
                data.info_gastos.todo.gastos_personal.forEach(function(entry) {
                    cont_aux=0;
                    cont++;
                
                    entry.investigador.dedicacion_horas_semanales=parseInt(entry.investigador.dedicacion_horas_semanales);   
                    entry.investigador.total_semanas=parseInt(entry.investigador.total_semanas);   
                    entry.investigador.valor_hora=parseInt(entry.investigador.valor_hora);   
                    entry.fecha_ejecucion=Date.parse(entry.fecha_ejecucion);
                     
                    entry.gasto.forEach(function(item) {
                     
                        if(cont_aux == 0 && cont == 1){
                            
                            var arrayTemp=[];
                            entry.gasto.forEach(function(aux) {
                              arrayTemp.push(aux.entidad_fuente_presupuesto.nombre);
                              arrayTemp[aux.entidad_fuente_presupuesto.nombre]=aux.valor;
                            });
                            
                            $scope.data.fuente_presupuesto.total_gastos_columnas=arrayTemp;
                            
                        }//fin del if

                        cont_aux++;
                        item.valor=parseInt(item.valor); //parseo el valor a numerico
                    
                        aux_nombre=item.entidad_fuente_presupuesto.nombre;
                        $scope.data.fuente_presupuesto.total_gastos_global=$scope.data.fuente_presupuesto.total_gastos_global+item.valor;
                    
                        // para el total gstos por columna
                        if(cont > 1){
                            $scope.data.fuente_presupuesto.total_gastos_columnas[aux_nombre]=parseInt($scope.data.fuente_presupuesto.total_gastos_columnas[aux_nombre])+item.valor;
                        }
                    
                        if(cont_aux == 1){
                            entry['gasto_total']={'presupuesto_total_fila': item.valor};
                        }
                        else if(cont_aux >1 && item.valor != null){
                            entry['gasto_total'].presupuesto_total_fila=(entry['gasto_total'].presupuesto_total_fila+item.valor);
                        }  
                    
                        if(cont == 1){
                            
                        //     var entidad_presupuesto = {
                        //         id: item.entidad_fuente_presupuesto.id, 
                        //         nombre: item.entidad_fuente_presupuesto.nombre
                        //     }               
            
                        //   $scope.data.entidades_presupuesto_seleccionadas.push(entidad_presupuesto);
                        }
                    });   
                        
                    if(cont == 1){
                        $scope.data.fuente_presupuesto.presupuesto=entry.gasto;
                    }
                });
                
                //console.log($scope.data.fuente_presupuesto);
      
                //---------------------aplicar funcion
                
                /////////equipo
                $scope.data.fuente_presupuesto_equipos=$scope.procesarDatos(data.info_gastos.todo.gastos_equipos);
                
                /////////software
                $scope.data.fuente_presupuesto_software=$scope.procesarDatos(data.info_gastos.todo.gastos_software);
                
                /////////salidas de campo
                $scope.data.fuente_presupuesto_salida=$scope.procesarDatos(data.info_gastos.todo.gastos_salidas_campo);
                
              /////////materiales
                $scope.data.fuente_presupuesto_materiales=$scope.procesarDatos(data.info_gastos.todo.gastos_materiales);
                
                /////////servicios
                $scope.data.fuente_presupuesto_servicios=$scope.procesarDatos(data.info_gastos.todo.gastos_servicios);
                
                
                /////////bibliografia
                $scope.data.fuente_presupuesto_bibliografia=$scope.procesarDatos(data.info_gastos.todo.gastos_bibliograficos);
                
                
                /////////digitales
                $scope.data.fuente_presupuesto_digitales=$scope.procesarDatos(data.info_gastos.todo.gastos_digitales);
                
                $scope.data.gasto_personal=data.info_gastos.todo.gastos_personal;
                $scope.data.gastos_equipos=data.info_gastos.todo.gastos_equipos;
                $scope.data.gastos_software=data.info_gastos.todo.gastos_software;
                $scope.data.gastos_salidas_campo=data.info_gastos.todo.gastos_salidas_campo;
                $scope.data.gastos_materiales=data.info_gastos.todo.gastos_materiales;
                $scope.data.gastos_servicios_tecnicos=data.info_gastos.todo.gastos_servicios;
                $scope.data.gastos_bibliograficos=data.info_gastos.todo.gastos_bibliograficos;
                $scope.data.gastos_digitales=data.info_gastos.todo.gastos_digitales;
                
                $scope.data.info_investigadores_usuario=data.info_investigadores_usuario;
                $scope.data.investigadores= data.info_investigadores_usuario;
            }
            
            ///se necesita convertir el string a un entero
            if($scope.data.proyecto){
               $scope.data.proyecto.anio_convocatoria=parseInt($scope.data.proyecto.anio_convocatoria);  
               $scope.data.proyecto.fecha_fin=Date.parse($scope.data.proyecto.fecha_fin);
               $scope.data.proyecto.fecha_inicio=Date.parse($scope.data.proyecto.fecha_inicio);
            }
            
            //console.log(data.proyecto);
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
            
            console.log($scope.data.entidades_fuente_presupuesto);
            // se agrega investigador principal a colección de participantes del proyecto            
            if($scope.data.participantes_proyecto){
                
                console.log("hola si hay algo ");
                
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
                console.log("hola no hay nada ");
            }            
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
	| procesarDatos()
	|--------------------------------------------------------------------------
	| //esta funcion convierte los datos  strin a number y a date
	*/ 
    $scope.procesarDatos = function(datos){
        cont=0;
        
        datos.forEach(function(entry) {
        cont++;
            
            
            if(entry.valor_unitario){
                    entry.valor_unitario=parseInt(entry.valor_unitario); //parseo el valor_unitario a numerico para salidas de campo
            }
                        
            entry.gasto.forEach(function(item) {
                
                if(item.valor){
                    item.valor=parseInt(item.valor); //parseo el valor a numerico
                }  

            });
   
             
             entry.fecha_ejecucion=Date.parse(entry.fecha_ejecucion);
            //console.log(entry);
             
             if(cont == 1){
                 gasto_temp=entry.gasto;
             }
             
        });
        
        return gasto_temp;
    }
    
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
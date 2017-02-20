sgpi_app.controller('crear_participantes_proyectos_controller', function($scope, $http, $log, $uibModal) {
    
    // inicializacion de variables
    $scope.data.participantes_proyecto = [];
    $scope.visibilidad.velo_busqueda_id = false;
    $scope.data.msj_edicion_datos_participante = '<h4 class="text-center">Buscar datos básicos por identificación</h4>';
    $scope.visibilidad.velo_edicion_datos_participante = true;
    $scope.data.formaciones = ['Ph. D', 'Doctorado', 'Maestría', 'Especialización', 'Pregado'];
    $scope.data.sexos = [{id: 'm', nombre: 'Hombre'}, {id: 'f', nombre: 'Mujer'}];
    
    $('#input_buscar_id').on('keydown', function(e) {
        if (e.which == 13) {
            $scope.buscar_datos_x_id();
        }
    });
    
    console.log("hola estas desde participantes");
    /*
	|--------------------------------------------------------------------------
	| buscar_datos_x_id()
	|--------------------------------------------------------------------------
	| Consulta los datos básicos de un particiante
	*/          
    $scope.buscar_datos_x_id = function(){
        
        if($scope.data.identificacion_a_buscar == null){
            $scope.data.msj_label_busqueda_id = 'Error: ingrese una identificacion válida';
            return;
        }
            
            
        if($scope.data.identificacion_a_buscar == $scope.data.info_investigador_principal.identificacion){
            $scope.data.msj_label_busqueda_id = 'Error: ingrese una identificacion diferente al investigador principal';
            return;
        }
        else
            $scope.data.msj_label_busqueda_id = '';
        
        $scope.data.msj_busqueda_id = '<h4 class="text-center">Buscando datos...<i class="fa fa-circle-o-notch fa-spin fa-fw"></i></h4>';
        $scope.visibilidad.velo_busqueda_id = true;
        
        $http({
            url: '/usuarios/buscar_datos_basicos',
            method: 'GET',
            params: {
                identificacion: $scope.data.identificacion_a_buscar
            }
        })
        .success(function(data) {
            $log.log(data);
            $scope.data.id_persona=null;
            $scope.data.tiene_usuario=false;
            
            if(data.consultado == 1){
                if(data.existe_cc){
                    
                    console.log("////////////////// datos traidos desde db");
                    console.log(data);
                    
                    $scope.data.id_persona=data.persona.id;
                    $scope.data.nombres_nuevo_participante = data.persona.nombres;
                    $scope.data.apellidos_nuevo_participante = data.persona.apellidos;
                    $scope.data.tiene_usuario=data.tiene_usuario;
                    
                    
                    $scope.data.formaciones.forEach(function(item) {
                        if(item == data.persona.formacion)
                            $scope.data.formacion_nuevo_participante = item;
                    });
                    $scope.data.tipos_identificacion.forEach(function(item) {
                        if(data.persona.id_tipo_identificacion == item.id)
                            $scope.data.tipo_identificacion_nuevo_participante = item;
                    }); 
                    $scope.data.sexos.forEach(function(item) {
                        if(item.id == data.persona.sexo)
                            $scope.data.sexo_nuevo_participante = item;
                    });
                    $scope.data.edad_nuevo_participante = data.persona.edad;
                    
                    $scope.data.datos_basicos_persona_recuperados = true;
                    $scope.data.msj_busqueda_id = '<h4 class="text-center">Datos recuperados para la identificación ' + data.persona.identificacion + '</h4>';
                }
                else{
                    $scope.data.msj_busqueda_id = '<h4 class="text-center">No existen coincidencias con la identificacion. Registrar nuevos datos.</h4>';
                }
                $scope.data.identificacion_nuevo_participante = $scope.data.identificacion_a_buscar;
                $scope.visibilidad.velo_edicion_datos_participante = false;
            }
            else{
                $log.log(data);
                $scope.visibilidad.velo_busqueda_id = false;
                $scope.data.msj_label_busqueda_id = 'Error al buscar los datos, código de error: ' + data.codigo;
            }
        })
        .error(function(data, status) {
            
        });
    };
   
    /*
	|--------------------------------------------------------------------------
	| buscar_otra_id()
	|--------------------------------------------------------------------------
	| Controlador de evento click para el boton de consultar buscar otra identificaicon
	| desde la sección de ingreso de datos del nuevo participante. 
	| Retorna a la sección de busqueda de datos básicos por id borrando los campos de ingreso del nuevo participante
	| manteniendo los datos registrados desde la anterior seccion
	*/             
    $scope.buscar_otra_id = function(){
        $scope.data.identificacion_a_buscar = null;
        $scope.visibilidad.velo_busqueda_id = false;
        $scope.visibilidad.velo_edicion_datos_participante = true;
        $scope.data.datos_basicos_persona_recuperados = false;
        $scope.borrar_modelos_nuevo_participante();
    };

    /*
	|--------------------------------------------------------------------------
	| borrar_modelos_nuevo_participante()
	|--------------------------------------------------------------------------
	| Borra los modelos de los campos de edición de nuevo participante
	*/                 
    $scope.borrar_modelos_nuevo_participante = function(){

        $scope.data.nombres_nuevo_participante = null;
        $scope.visibilidad.nombres_nuevo_participante_invalido = false;
        
        $scope.data.apellidos_nuevo_participante = null;
        $scope.visibilidad.apellidos_nuevo_participante_invalido = false;
        
        $scope.data.identificacion_nuevo_participante = null;
        $scope.visibilidad.identificacion_nuevo_participante_invalido = false;
        
        $scope.data.formacion_nuevo_participante = null;
        $scope.visibilidad.formacion_nuevo_participante_invalido = false;
        
        $scope.data.rol_nuevo_participante = null;
        $scope.visibilidad.rol_nuevo_participante_invalido = false;
        
        $scope.data.tipo_identificacion = null;
        $scope.visibilidad.tipo_id_nuevo_participante_invalido = false;
        
        $scope.data.sexo_nuevo_participante = null;
        $scope.visibilidad.sexo_nuevo_participante_invalido = false;
        
        $scope.data.edad_nuevo_participante = null;
        $scope.visibilidad.edad_nuevo_participante_invalido = false;
        
        $scope.data.email_nuevo_participante = null;
        $scope.visibilidad.email_nuevo_participante_invalido = false;
        
        $scope.data.sede_nuevo_participante = null;
        $scope.visibilidad.sede_nuevo_participante_invalido = false;
        
        $scope.data.grupo_inv_nuevo_participante = null;
        $scope.visibilidad.grupo_inv_nuevo_participante_invalido = false;
        
        $scope.data.facultad_nuevo_participante = null;
        $scope.visibilidad.facultad_nuevo_participante_invalido = false;
        
        $scope.data.entidad_grupo_inv_externo_nuevo_participante = null;
        $scope.visibilidad.entidad_externa_nuevo_participante_invalido = false;
        
        $scope.data.programa_academico_nuevo_participante = null;
        $scope.visibilidad.programa_academico_participante_invalido = false;
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_participante()
	|--------------------------------------------------------------------------
	| ng-click para boton de agregar participante.
	| Añade al array de participantes el participante recien editado
	*/            
    $scope.agregar_participante = function(){
        
        var validacion = [
            
            $scope.validar_nombres_nuevo_participante(),
            
            $scope.validar_apellidos_nuevo_participante(),
            
            $scope.validar_identificacion_nuevo_participante(),
            
            $scope.validar_formacion_nuevo_participante(),
            
            $scope.validar_rol_proyecto_nuevo_participante(),
            
            $scope.validar_tipo_id_nuevo_participante(),
            
            $scope.validar_sexo_nuevo_participante(),
            
            $scope.validar_edad_nuevo_participante(),
            
            $scope.validar_email_nuevo_participante(),
        ];
        
        if($scope.data.rol_nuevo_participante == null){
            validacion.push($scope.validar_sede_nuevo_participante());
            validacion.push($scope.validar_grupo_inv_nuevo_participante());
            validacion.push($scope.validar_facultad_nuevo_participante());            
            validacion.push($scope.validar_entidad_externa_nuevo_participante());
            validacion.push($scope.validar_programa_acad_nuevo_participante());                        
        }
        else if($scope.data.rol_nuevo_participante.id == 4){
            validacion.push($scope.validar_sede_nuevo_participante());
            validacion.push($scope.validar_grupo_inv_nuevo_participante());
            validacion.push($scope.validar_facultad_nuevo_participante());
        }
        else if($scope.data.rol_nuevo_participante.id == 5){
            validacion.push($scope.validar_entidad_externa_nuevo_participante());
        }
        else if($scope.data.rol_nuevo_participante.id == 6){
            validacion.push($scope.validar_entidad_externa_nuevo_participante());
            validacion.push($scope.validar_programa_acad_nuevo_participante());
        }
        
        if(validacion.indexOf(true) != -1) // alguna validacion invalida
        {
            return;
        }
        // 4 interno
        // 5 externo
        // 6 estudiante
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });        
            
            
        // $scope.data.identificacion_nuevo_participante    
        
        bandera_temp=false;
        $scope.data.info_investigadores_usuario.forEach(function(item) {
            if(item.info_investigador.identificacion == $scope.data.identificacion_nuevo_participante){
                bandera_temp=false;//true es 
                alertify.error('Lo sentimos este usuario ya se encuentra en este proyecto');
            }
            
            
        });
            
        if(!bandera_temp){
            // agrego al panel el participante nuevo que e buscado   
                $scope.data.info_investigadores_usuario.push({
                    id:null,
                    tiene_usuario:$scope.data.tiene_usuario,
                    resgitrado:false,
                    sexo:{
                       'nombre': $scope.data.sexo_nuevo_participante.nombre,
                       'id':$scope.data.sexo_nuevo_participante.id, 
                    },
                    info_investigador:
                    {
                        'id':$scope.data.id_persona,
                        'id_persona':null,
                        'nombres':$scope.data.nombres_nuevo_participante,
                        'apellidos':$scope.data.apellidos_nuevo_participante,
                        'identificacion':$scope.data.identificacion_nuevo_participante,
                        'formacion':$scope.data.formacion_nuevo_participante,
                        'tipo_identificacion':{
                            'nombre':$scope.data.tipo_identificacion_nuevo_participante.nombre,
                            'id':$scope.data.tipo_identificacion_nuevo_participante.id,
                        },
                        'sexo_nombre': $scope.data.sexo_nuevo_participante.nombre,
                        'sexo':$scope.data.sexo_nuevo_participante.id,
                        'edad':$scope.data.edad_nuevo_participante,
                    },
                    datos_extras:{
                            'rol':{
                                'id':$scope.data.rol_nuevo_participante.id,
                                'nombre':$scope.data.rol_nuevo_participante.nombre,
                            },
                            'email':$scope.data.email_nuevo_participante,
                            'grupo':{
                                'nombre':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.grupo_inv_nuevo_participante.nombre : null,
                                'id':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.grupo_inv_nuevo_participante.id : null,
                                'facultad':{
                                    'nombre':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.facultad_nuevo_participante.nombre: null, 
                                    'id':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.facultad_nuevo_participante.id: null,
                                    'sede':{
                                        'nombre':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.sede_nuevo_participante.nombre : null,
                                        'ciudad':($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.sede_nuevo_participante.nombre : null,
                                        'id': ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.sede_nuevo_participante.id : null,
                                    },     
                                },
                            },
                            'entidad_o_grupo_investigacion':($scope.data.rol_nuevo_participante.id == 5 || $scope.data.rol_nuevo_participante.id == 6) ?  $scope.data.entidad_externa_nuevo_participante : null,
                            'programa_academico': ($scope.data.rol_nuevo_participante.id == 6) ? $scope.data.programa_academico_nuevo_participante : null,
        
                    },
                    nuevo:true,
                    investigador_principarl:0,
                    // es_investigador_principal: false,
                    // nombres: $scope.data.nombres_nuevo_participante,
                    // apellidos: $scope.data.apellidos_nuevo_participante,
                    // identificacion: $scope.data.identificacion_nuevo_participante,
                    // formacion: $scope.data.formacion_nuevo_participante,
                    // rol: $scope.data.rol_nuevo_participante.nombre, 
                    // id_rol: $scope.data.rol_nuevo_participante.id,
                    // tipo_identificacion: $scope.data.tipo_identificacion_nuevo_participante.nombre,
                    // id_tipo_identificacion: $scope.data.tipo_identificacion_nuevo_participante.id,
                    // sexo: $scope.data.sexo_nuevo_participante.nombre,
                    // id_sexo: $scope.data.sexo_nuevo_participante.id,
                    // edad: $scope.data.edad_nuevo_participante,
                    // email: $scope.data.email_nuevo_participante,
                    // sede: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.sede_nuevo_participante.nombre : null,
                    // id_sede: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.sede_nuevo_participante.id : null,
                    // grupo_investigacion: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.grupo_inv_nuevo_participante.nombre : null,
                    // id_grupo_investigacion: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.grupo_inv_nuevo_participante.id : null,
                    // facultad_dependencia: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.facultad_nuevo_participante.nombre: null, 
                    // id_facultad_dependencia: ($scope.data.rol_nuevo_participante.id == 4) ? $scope.data.facultad_nuevo_participante.id: null, 
                    // entidad_grupo_inv_externo: ($scope.data.rol_nuevo_participante.id == 5 || $scope.data.rol_nuevo_participante.id == 6) ?  $scope.data.entidad_externa_nuevo_participante : null,
                    // programa_academico: ($scope.data.rol_nuevo_participante.id == 6) ? $scope.data.programa_academico_nuevo_participante : null,
                    // dedicacion_semanal: 0,
                    // total_semanas: 0,
                    // valor_hora: 0,
                    // presupuesto_ucc: 0,
                    // otras_entidades_presupuesto: otras_entidades_presupuesto,
                    // presupuesto_total: 0,
                    // presupuesto_externo_invalido: [],
                    // fecha_ejecucion: null,
                    // fecha_ejecucion_invalido: false
                });
                console.log($scope.data.info_investigadores_usuario);
        }
        
        
        
        $scope.buscar_otra_id();
    };    
    
    /*
	|--------------------------------------------------------------------------
	| remover_participante()
	|--------------------------------------------------------------------------
	| ng-click para remover un participante del array de 
	| $scope.data.participantes_proyecto.
	| También resta los valores que sumaba el oarticipante a los totales generales de gastos de personal
	*/                
    $scope.remover_participante = function(participante) {
        
        console.log("estas en remover_participante //////////////");
        
        console.log(participante);
        
        if(participante.resgitrado == false){
            
            var index_de_participante = $scope.data.info_investigadores_usuario.indexOf(participante);
            if(index_de_participante != -1){
                
                // $scope.data.totales_personal.ucc -= participante.presupuesto_ucc;
                // $scope.data.totales_personal.conadi -= participante.presupuesto_conadi;
                // $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                //     $scope.data.totales_personal.otras_entidades_presupuesto[item.id] -= participante.otras_entidades_presupuesto[item.id];
                // })
                // $scope.data.totales_personal.total -= participante.presupuesto_total;  
                
                $scope.data.info_investigadores_usuario.splice(index_de_participante, 1);
            }
            
        }else{
            
            alertify.confirm('Eliminar partcipante', 'Desea eliminar este Participante?', 
            function(){ 
                
                
                $http({
                url: '/proyecto/eliminar/participante',
                method: 'GET',
                params: {
                    id_investigador: participante.info_investigador.id,
                }
                })
                .success(function(data) {
                    
                    console.log(data);
                    
                    if(!data.error){
                        
                        alertify.success(data.mensaje);
                        var index_de_participante = $scope.data.info_investigadores_usuario.indexOf(participante);
                        if(index_de_participante != -1){
                            $scope.data.info_investigadores_usuario.splice(index_de_participante, 1);
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
                
                
                
                //  alertify.success('true');
                 
                 //// fin de ok   
            }
                , function(){ 
                    alertify.error('Se a cancelado la opercaion');
                    
            });
            
        }
        
        
        
        
            
    };    
    
    /*
	|--------------------------------------------------------------------------
	| cambia_sede_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para ui-select de sedes, lo que permite alimentar el ui-select 
	| de grupos de invesitgacion con los grupos de investigacion correpsondientes a la sede seleccionada
	*/    
    $scope.cambia_sede_nuevo_participante = function(){
        
        if($scope.data.sede_nuevo_participante){
            $scope.data.grupos_inv_nuevo_participante = $scope.data.grupos_investigacion_y_sedes[$scope.data.sede_nuevo_participante.id].grupos_investigacion;
        }
        $scope.data.grupo_inv_nuevo_participante = null;
        $scope.data.facultad_nuevo_participante = null;
        $scope.validar_sede_nuevo_participante();
    };
    
    
    
    $scope.cambia_sede_nuevo_participante2 = function(sede,grupo){
        
        if(sede){
            $scope.data.grupo_temp=grupo;
            $scope.data.grupos_participante =[];
      
                    console.log("/////////////////// SEDE");
                    console.log(sede);
                    
                    facultad=sede.facultad;
                    cont=0;
                    
                    for (var i =0;  i<facultad.length; i++ ) {
                        if(facultad[i].grupo.length > 1){
                            
                            for (var j = 0 ; j < facultad[i].grupo.length; j++) {
                                $scope.data.grupos_participante.push(facultad[i].grupo[j]);
                            }
                            
                        }else if(facultad[i].grupo.length == 1){
                             $scope.data.grupos_participante.push(facultad[i].grupo[0]);
                        }
                        
                    }
                
                //  $scope.data.grupos_participante=facultad;
              
                  console.log("/////////////////// grupos");
                  console.log( $scope.data.grupos_participante);
            
            
            
            $scope.data.grupos_inv_nuevo_participante = $scope.data.grupos_investigacion_y_sedes[sede.id].grupos_investigacion;
            console.log("//////////////////////// grupos 2");
            console.log($scope.data.grupos_inv_nuevo_participante);
            
           
        }
   
        
        // $scope.data.grupo_inv_nuevo_participante = null;
        // $scope.data.facultad_nuevo_participante = null;
        $scope.validar_sede_nuevo_participante2(true,sede);
        
    };
    
    
    /*
	|--------------------------------------------------------------------------
	| validar_sede_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida el valor del modelo de sede de nuevo participante
	*/      
    $scope.validar_sede_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.sede_nuevo_participante){
            $scope.visibilidad.sede_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.sede_nuevo_participante_invalido = true;
            return true;
        }
    };
    
    $scope.validar_sede_nuevo_participante2 = function(mostrar_campo_invalido=true,sede) {
        if(sede){
            $scope.visibilidad.sede_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.sede_nuevo_participante_invalido2 = true;
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| cambia_grupo_inv_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para ui-select de grupos de investigación.
	| Se encarga de presentar la facultad que le corresponde al grupo de investigación ucc elegido
	*/    
    $scope.cambia_grupo_inv_nuevo_participante = function(){
        $scope.data.facultades_dependencias.forEach(function(facultad_dependencia) {
            if(facultad_dependencia.id == $scope.data.grupo_inv_nuevo_participante.id_facultad_dependencia_ucc){
                $scope.data.facultad_nuevo_participante = facultad_dependencia;
            }
        });
        $scope.validar_grupo_inv_nuevo_participante();
    };
    
    
    $scope.cambia_grupo_inv_nuevo_participante2 = function(seleecionado,grupo){
        
        
        // grupo=$scope.data.grupo_temp;
        console.log($scope.data.grupo_temp);
        console.log(grupo);
        
      
        // $scope.data.facultad_nuevo_participante = facultad_dependencia;
       
         $scope.validar_grupo_inv_nuevo_participante2(grupo);
    };

    /*
	|--------------------------------------------------------------------------
	| validar_grupo_inv_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida modelo de grupo de investigación
	*/    
    $scope.validar_grupo_inv_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.grupo_inv_nuevo_participante){
            $scope.visibilidad.grupo_inv_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.grupo_inv_nuevo_participante_invalido = true;
            return true;
        }        
    };
    
    $scope.validar_grupo_inv_nuevo_participante2 = function(dato,mostrar_campo_invalido=true) {
        if(dato){
            $scope.visibilidad.grupo_inv_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.grupo_inv_nuevo_participante_invalido2 = true;
            return true;
        }        
    };

    /*
	|--------------------------------------------------------------------------
	| cambia_rol_proyecto_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-change de ui-select de rol de particpante.
	| Muestra los campos que le corresponden al tipo de rol elegido
	*/        
    $scope.cambia_rol_proyecto_nuevo_participante = function(){
   
        if($scope.data.rol_nuevo_participante.id == 4){ // nuevo participante interno o externo
            $scope.visibilidad.institucion_nuevo_participante = true;
            $scope.visibilidad.sede_nuevo_participante = true;
            $scope.visibilidad.grupo_inv_nuevo_participante = true;
            $scope.visibilidad.facultad_nuevo_participante = true;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante = false;
            $scope.visibilidad.programa_academico_nuevo_participante = false;
        }
        else if($scope.data.rol_nuevo_participante.id == 5){
            $scope.visibilidad.institucion_nuevo_participante = false;
            $scope.visibilidad.sede_nuevo_participante = false;
            $scope.visibilidad.grupo_inv_nuevo_participante = false;
            $scope.visibilidad.facultad_nuevo_participante = false;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante = true;
            $scope.visibilidad.programa_academico_nuevo_participante = false;
        }
        else if($scope.data.rol_nuevo_participante.id == 6){
            $scope.visibilidad.institucion_nuevo_participante = false;
            $scope.visibilidad.sede_nuevo_participante = false;
            $scope.visibilidad.grupo_inv_nuevo_participante = false;
            $scope.visibilidad.facultad_nuevo_participante = false;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante = true;
            $scope.visibilidad.programa_academico_nuevo_participante = true;            
        }
        $scope.validar_rol_proyecto_nuevo_participante();
    };
    
    
    $scope.cambia_rol_proyecto_nuevo_participante2 = function(objeto){
       
        if(objeto.id == 4){ // nuevo participante interno o externo
            $scope.visibilidad.institucion_nuevo_participante2 = true;
            $scope.visibilidad.sede_nuevo_participante2 = true;
            $scope.visibilidad.grupo_inv_nuevo_participante2 = true;
            $scope.visibilidad.facultad_nuevo_participante2 = true;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante2 = false;
            $scope.visibilidad.programa_academico_nuevo_participante2 = false;
        }
        else if(objeto.id == 5){
            $scope.visibilidad.institucion_nuevo_participante2 = false;
            $scope.visibilidad.sede_nuevo_participante2 = false;
            $scope.visibilidad.grupo_inv_nuevo_participante2 = false;
            $scope.visibilidad.facultad_nuevo_participante2 = false;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante2 = true;
            $scope.visibilidad.programa_academico_nuevo_participante2 = false;
        }
        else if(objeto.id == 6){
            $scope.visibilidad.institucion_nuevo_participante2 = false;
            $scope.visibilidad.sede_nuevo_participante2 = false;
            $scope.visibilidad.grupo_inv_nuevo_participante2 = false;
            $scope.visibilidad.facultad_nuevo_participante2= false;
            $scope.visibilidad.entidad_grupo_inv_externo_nuevo_participante2 = true;
            $scope.visibilidad.programa_academico_nuevo_participante2 = true;            
        }
        $scope.validar_rol_proyecto_nuevo_participante2(objeto);
    };

    /*
	|--------------------------------------------------------------------------
	| validar_rol_proyecto_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida el valor del rol en el proyecto del nuevo participante
	| verificando que el valor de su modelo sea diferente de null
	*/            
    $scope.validar_rol_proyecto_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.rol_nuevo_participante){
            $scope.visibilidad.rol_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.rol_nuevo_participante_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_rol_proyecto_nuevo_participante2 = function(objeto,mostrar_campo_invalido=true) {
        if(objeto){
            $scope.visibilidad.rol_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.rol_nuevo_participante_invalido2 = true;
            return true;            
        }
    };

    /*
	|--------------------------------------------------------------------------
	| cambia_nombres_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para nombres de nuevo participante.
	| Valida el campo comprobando que tenga una longitud máxima de 240 caracteres.
	| Se retorna true si la validacion es incorrecta.
	*/          
    $scope.validar_nombres_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.nombres_nuevo_participante == null || 
            $scope.data.nombres_nuevo_participante.length == 5 || 
            $scope.data.nombres_nuevo_participante.length > 240)
        {
            if(mostrar_campo_invalido)
                $scope.visibilidad.nombres_nuevo_participante_invalido = true;
            $scope.data.msj_validacion_nombres_nuevo_participante = 'Logitud mínima de 5 catacteres y máximo de 240';
            return true; 
        }
        else
        {
            $scope.visibilidad.nombres_nuevo_participante_invalido = false;
            return false;
        }
    };
    
    
    $scope.validar_nombres_nuevo_participante2 = function(nombre,mostrar_campo_invalido=true) {
        
        if(nombre == null || 
            nombre.length <= 5 || 
            nombre.length > 240)
        {
            if(mostrar_campo_invalido)
                $scope.visibilidad.nombres_nuevo_participante_invalido2 = true;
                $scope.data.msj_validacion_nombres_nuevo_participante2 = 'Logitud mínima de 5 catacteres y máximo de 240';
            return true; 
        }
        else
        {
            $scope.visibilidad.nombres_nuevo_participante_invalido2 = false;
            return false;
        }
    };

    /*
	|--------------------------------------------------------------------------
	| cambia_apellidos_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para apellidos de nuevo participante.
	| Valida el campo comprobando que tenga una longitud máxima de 240 caracteres.
	| Se retorna true si la validacion es incorrecta.
	*/              
    $scope.validar_apellidos_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.apellidos_nuevo_participante == null || 
            $scope.data.apellidos_nuevo_participante.length == 0 || 
            $scope.data.apellidos_nuevo_participante.length > 240)
        {
            if(mostrar_campo_invalido)
                $scope.visibilidad.apellidos_nuevo_participante_invalido = true;
            $scope.data.msj_validacion_apellidos_nuevo_participante = 'Logitud mínima de 5 catacteres y máximo de 240';
            return true; 
        }
        else
        {
            $scope.visibilidad.apellidos_nuevo_participante_invalido = false;
            return false;
        }        
    };
    
    $scope.validar_apellidos_nuevo_participante2 = function(apellidos,mostrar_campo_invalido=true) {
        if(apellidos == null || 
            apellidos.length <= 5 || 
            apellidos.length > 240)
        {
            if(mostrar_campo_invalido)
                $scope.visibilidad.apellidos_nuevo_participante_invalido2 = true;
            $scope.data.msj_validacion_apellidos_nuevo_participante2 = 'Logitud mínima de 5 catacteres y máximo de 240';
            return true; 
        }
        else
        {
            $scope.visibilidad.apellidos_nuevo_participante_invalido2 = false;
            return false;
        }        
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_identificacion_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para identificacion de nuevo participante.
	| Valida el campo comprobando que tenga no sea menor a 1
	*/        
    $scope.validar_identificacion_nuevo_participante = function(mostrar_campo_invalido=true) {
        
        if($scope.data.identificacion_nuevo_participante < 1){
            if(mostrar_campo_invalido)
                $scope.visibilidad.identificacion_nuevo_participante_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.identificacion_nuevo_participante_invalido = false;
            return false;
        }
    };
    
    $scope.validar_identificacion_nuevo_participante2 = function(identificaicon,mostrar_campo_invalido=true) {
        
        if(identificaicon < 1){
            if(mostrar_campo_invalido)
                $scope.visibilidad.identificacion_nuevo_participante_invalido2 = true;
                $scope.data.msj_validacion_identificacion_nuevo_participante2="La identificacion no puede estar vacia";
            return true;
        }
        else{
            $scope.visibilidad.identificacion_nuevo_participante_invalido2 = false;
            return false;
        }
    };

    /*
	|--------------------------------------------------------------------------
	| validar_formacion_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para formación de nuevo participante.
	| Valida el campo comprobando sea diferente de null
	*/            
    $scope.validar_formacion_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.formacion_nuevo_participante){
            $scope.visibilidad.formacion_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.formacion_nuevo_participante_invalido = true;
            $scope.data.msj_validacion_formacion_nuevo_participante = 'Campo requerido. Elegir una opción.';
            return true;
        }
    };
    
    $scope.validar_formacion_nuevo_participante2 = function(dato,mostrar_campo_invalido=true) {
        
        if(dato){
            $scope.visibilidad.formacion_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.formacion_nuevo_participante_invalido2 = true;
                $scope.data.msj_validacion_formacion_nuevo_participante2 = 'Campo requerido. Elegir una opción.';
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_tipo_id_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para tipo de identificación de nuevo participante.
	| Valida que el campo sea diferente de null
	*/        
    $scope.validar_tipo_id_nuevo_participante = function(mostrar_campo_invalido=true) {
        

        if($scope.data.tipo_identificacion_nuevo_participante){
            $scope.visibilidad.tipo_id_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.tipo_id_nuevo_participante_invalido = true;
            return true;            
        }
    };
    
    
    
    $scope.validar_tipo_id_nuevo_participante2 = function(mostrar_campo_invalido=true,objeto) {
        
        if(objeto){
            $scope.visibilidad.tipo_id_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.tipo_id_nuevo_participante_invalido2 = true;
            return true;            
        }
    };
    
    
    
    
    
    
    
    
    /*
	|--------------------------------------------------------------------------
	| validar_sexo_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para sexo de nuevo participante.
	| Valida que el campo sea diferente de null
	*/            
    $scope.validar_sexo_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.sexo_nuevo_participante){
            $scope.visibilidad.sexo_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.sexo_nuevo_participante_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_sexo_nuevo_participante2 = function(data,mostrar_campo_invalido=true) {
        if(data){
            $scope.visibilidad.sexo_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.sexo_nuevo_participante_invalido2 = true;
            return true;            
        }
    };
    /*
	|--------------------------------------------------------------------------
	| validar_edad_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para edad de nuevo participante.
	| Valida que el vaor del modelo sea mayor a 10
	*/           
    $scope.validar_edad_nuevo_participante = function(mostrar_campo_invalido=true) {
        
        if($scope.data.edad_nuevo_participante == null || $scope.data.edad_nuevo_participante < 10){
            if(mostrar_campo_invalido)
                $scope.visibilidad.edad_nuevo_participante_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.edad_nuevo_participante_invalido = false;
            return false;            
        }
    };
    
    $scope.validar_edad_nuevo_participante2 = function(data,mostrar_campo_invalido=true) {
        
        if(data == null || data < 10){
            if(mostrar_campo_invalido)
                $scope.visibilidad.edad_nuevo_participante_invalido2 = true;
            return true;
        }
        else{
            $scope.visibilidad.edad_nuevo_participante_invalido2 = false;
            return false;            
        }
    };    
    
    /*
	|--------------------------------------------------------------------------
	| validar_email_nuevo_participante()
	|--------------------------------------------------------------------------
	| ng-chage para email de nuevo participante.
	| Valida que el modelo corresponda a un tipo email
	*/           
    $scope.validar_email_nuevo_participante = function(mostrar_campo_invalido=true) {
        if(/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/.test($scope.data.email_nuevo_participante)){
            $scope.visibilidad.email_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.email_nuevo_participante_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_email_nuevo_participante2 = function(data,mostrar_campo_invalido=true) {
        
        if(/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/.test(data)){
            $scope.visibilidad.email_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.email_nuevo_participante_invalido2 = true;
            return true;            
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_facultad_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida valor del modelo de facultad de nuevo participante
	*/               
    $scope.validar_facultad_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.facultad_nuevo_participante){
            $scope.visibilidad.facultad_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.facultad_nuevo_participante_invalido = true;
            return true;            
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_entidad_externa_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida valor del modelo de entidad / grupo de inv de nuevo participante
	*/      
    $scope.validar_entidad_externa_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.entidad_externa_nuevo_participante){
            $scope.visibilidad.entidad_externa_nuevo_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.entidad_externa_nuevo_participante_invalido = true;
            return true;
        }
    };
    
    $scope.validar_entidad_externa_nuevo_participante2 = function(data,mostrar_campo_invalido=true) {
        if(data){
            $scope.visibilidad.entidad_externa_nuevo_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.entidad_externa_nuevo_participante_invalido2 = true;
            return true;
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| validar_programa_acad_nuevo_participante()
	|--------------------------------------------------------------------------
	| Valida valor del modelo de entidad / grupo de inv de nuevo participante
	*/          
    $scope.validar_programa_acad_nuevo_participante = function(mostrar_campo_invalido=true) {
        if($scope.data.programa_academico_nuevo_participante){
            $scope.visibilidad.programa_academico_participante_invalido = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.programa_academico_participante_invalido = true;
            return true;            
        }
    };
    $scope.validar_programa_acad_nuevo_participante2 = function(data,mostrar_campo_invalido=true) {
        if(data){
            $scope.visibilidad.programa_academico_participante_invalido2 = false;
            return false;
        }
        else{
            if(mostrar_campo_invalido)
                $scope.visibilidad.programa_academico_participante_invalido2 = true;
            return true;            
        }
    };

    /*
	|--------------------------------------------------------------------------
	| mostrar_modal_grupos_investigacion()
	|--------------------------------------------------------------------------
	| Presenta el modal de entidades y grupos de investigación que participan en el proyecto
	*/  
    $scope.mostrar_modal_grupos_investigacion = function(participantes) {
        // Crea y muestra el modal de revisión de evidencia
        
        $scope.data.info_investigadores_usuario=participantes;
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_grupos_investigacion.html',
            controller: 'modal_grupos_investigacion_controller',
            size: 'lg',
            scope: $scope,
            backdrop: 'static'
        });
    };

    /*
	|--------------------------------------------------------------------------
	| continuar_a_productos()
	|--------------------------------------------------------------------------
	| Pasa a la pestaña de ingreso de productos
	*/              
    $scope.continuar_a_productos = function() {
        participante=$scope.data.info_investigadores_usuario;
        
        
        var validacion = []
 
        participante.forEach(function(item){
            
            if(!item.tiene_usuario){
                // console.log(item);
                
                validacion.push($scope.validar_nombres_nuevo_participante2(item.info_investigador.nombres));
                
                validacion.push($scope.validar_apellidos_nuevo_participante2(item.info_investigador.apellidos));
                
                validacion.push($scope.validar_identificacion_nuevo_participante2(item.info_investigador.identificacion));
                
                // validacion.push($scope.validar_formacion_nuevo_participante2());
                
                // validacion.push($scope.validar_rol_proyecto_nuevo_participante2());
                
                validacion.push($scope.validar_tipo_id_nuevo_participante2(true,item.info_investigador.tipo_identificacion));
                
                validacion.push($scope.validar_sexo_nuevo_participante2(item.sexo));
                
                validacion.push($scope.validar_edad_nuevo_participante2(item.info_investigador.edad));
                
                validacion.push($scope.validar_email_nuevo_participante2(item.datos_extras.email));
            
                if(item.datos_extras.id_rol == null){
                    console.log("id rol es igual a null");
                    
                    validacion.push($scope.validar_sede_nuevo_participante2(true,item.datos_extras.grupo.facultad.sede));
                    validacion.push($scope.validar_grupo_inv_nuevo_participante2(item));
                    // validacion.push($scope.validar_facultad_nuevo_participante2());  // no hay como validarla          
                    validacion.push($scope.validar_entidad_externa_nuevo_participante2(item.datos_extras.entidad_o_grupo_investigacion));
                    validacion.push($scope.validar_programa_acad_nuevo_participante2(item.datos_extras.programa_academico));                        
                }
                else if(item.datos_extras.id_rol == 4){
                    
                    console.log("id rol es igual a 4");
                    validacion.push($scope.validar_sede_nuevo_participante2(trrue,item.item.datos_extras.grupo.facultad.sede));
                    validacion.push($scope.validar_grupo_inv_nuevo_participante2(item));
                    // validacion.push($scope.validar_facultad_nuevo_participante2());//no hay funcion para vaidar esto
                }
                else if(item.datos_extras.id_rol == 5){
                    console.log("id rol es igual a 5");
                    validacion.push($scope.validar_entidad_externa_nuevo_participante2(item.datos_extras.entidad_o_grupo_investigacion));
                }
                else if(item.datos_extras.id_rol == 6){
                    
                    console.log("id rol es igual a 6");
                    validacion.push($scope.validar_entidad_externa_nuevo_participante2(item.datos_extras.entidad_o_grupo_investigacion));
                    validacion.push($scope.validar_programa_acad_nuevo_participante2(item.datos_extras.programa_academico));                        
                }
                
             /// fin del else
            }
            
        });
        

        if(validacion.indexOf(true) != -1) // alguna validacion invalida
        {
             alertify.error('Error con validaciones');
        
            
        }else{
            
            alertify.success("ok validado");
            $('#input_editar_proyecto').trigger('click');
        }
    
    };
    
    /*
	|--------------------------------------------------------------------------
	| regresar_info_general()
	|--------------------------------------------------------------------------
	| Regresa a contenido de información general del proyecto
	*/    
    $scope.regresar_info_general = function() {
        $('a[href="#contenido_info_general"]').tab('show');
    };    

});





/*
|--------------------------------------------------------------------------
| modal_grupos_investigacion_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta en resumen los grupos de investigación y entidades que participan
*/              
sgpi_app.controller('modal_grupos_investigacion_controller', function ($scope, $uibModalInstance) {
    
    console.log($scope.data.info_investigador_principal);
    
    $scope.entidades_grupos = [
        {
            nombre: $scope.data.info_investigador_principal.nombre_grupo_inv,
            rol: 'Ejecutor'
        }];
        
    
    $scope.entidad_grupo_ya_agregado = function(entidad_grupo) {
        // se vuelve true si se encuentra un nombre de entidad_grupo ya añadido
        var resultado_busqueda = false;
        
        $scope.entidades_grupos.forEach(function(item) {
            if(entidad_grupo.nombre == item.nombre)
                resultado_busqueda = true;
        });
        return resultado_busqueda;
    };    
    
    console.log("/////////////////// CONTOLRADOR MODAL");
    console.log($scope.data.info_investigadores_usuario);
    
    $scope.data.info_investigadores_usuario.forEach(function(participante) {
        
        
        
        if(participante.datos_extras){
            if(participante.datos_extras.id_rol == 4){
            
                var entidad_grupo = {
                    nombre: participante.datos_extras.grupo.nombre,
                    rol: 'Co-ejecutor'
                };
                
                if(!$scope.entidad_grupo_ya_agregado(entidad_grupo))
                    $scope.entidades_grupos.push(entidad_grupo);
            }
            else if(participante.datos_extras.id_rol == 5 || participante.datos_extras.id_rol == 6){
                
                var entidad_grupo = {
                    nombre: participante.datos_extras.entidad_o_grupo_investigacion,
                    rol: 'Co-ejecutor'
                }
                
                if(!$scope.entidad_grupo_ya_agregado(entidad_grupo))
                    $scope.entidades_grupos.push(entidad_grupo);        
            }
        }
        
        
    });
    
    $scope.cerrar = function () {
        $scope.$close();
    };
});



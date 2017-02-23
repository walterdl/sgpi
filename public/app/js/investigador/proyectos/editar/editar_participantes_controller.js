
$(document).ready(function() {
    
	$(window).keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});    
    
    $('#contenedor_tabla_participantes').mCustomScrollbar({
		axis:"y",
		theme: 'dark'
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

// servicio que valida numero para ser entero. Opcionalmente valida si el valor es mayor a una cantidad pasada
// retorna true si es invalida
sgpi_app.factory('validar_numero', function () {
    var regex = /^\d+$/;
    return function (number, min=null) {
        var validation_result = false;
        if(min!=null)
            if(number == null || number == undefined || typeof(number) != 'number' || !regex.test(number) || number < min)
                validation_result = true;
            else
                validation_result = false;
        else
            if(number == null || number == undefined || typeof(number) != 'number' || !regex.test(number) || number < 0)
                validation_result = true;
            else
                validation_result = false;        
        return validation_result;
    };
});

sgpi_app.filter('sin_timezone', function () {
    return function (date) {
        date = new Date(date + 'T00:00:00');
        var userTimezoneOffset = new Date().getTimezoneOffset()*60000;
        return new Date(date.getTime() + userTimezoneOffset);        
    };
});

sgpi_app.filter('boolean', function() {
    return function(input) {
        return Boolean(input);
    };
});

sgpi_app.controller('editar_participantes_controller', function($scope, $http, $uibModal, id_proyecto, validar_cadena, validar_numero) {
    
    // inicializa los modelos de los mensajes de los velos
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando participantes del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $scope.visibilidad.show_velo_general = true;
    $scope.data.msj_edicion_datos_participante = '<h4 class="text-center"><strong>Buscar datos básicos por identificación</strong></h4>';
    $scope.visibilidad.velo_edicion_datos_participante = true;
    
    $scope.formaciones = ['Ph. D', 'Doctorado', 'Maestría', 'Especialización', 'Pregado'];
    $scope.roles = [
        {id: 4, nombre: 'Investigador interno'},
        {id: 5, nombre: 'Investigador externo'},
        {id: 6, nombre: 'Estudiante'},
        ];
    $scope.sexos = [
        {id: 'm', nombre: 'Hombre'},
        {id: 'f', nombre: 'Mujer'}
        ];    
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };
    $scope.email_nuevo_participante == null;
    
    // permite accionar busqueda cuando se presiona enter en el input de buscar id
    $('#input_buscar_id').on('keydown', function(e) {
        if (e.which == 13) {
            $scope.buscar_datos_x_id();
            $scope.$apply();
        }
    });    
    
    // consulta los participantes del proyecto
    $http({
       url: '/proyectos/info_editar_participantes',
       method: 'GET',
       params: {
           'id_proyecto': id_proyecto
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
            $scope.data.msj_operacion_general = '<h3 class="text-center">Error al consultador los participantes del proyecto. Código de error: ' + data.codigo + '</h3>';
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Error al consultador los participantes del proyecto. Código de estado: ' + status + '</h3>';
    });
    
    // inicializa los modelos de cada investogador diferente al investigador ppal
    $scope.init = function(data) {
        
        $scope.visibilidad.show_velo_general = false;
        $scope.tipos_identificacion = data.tipos_identificacion;
        $scope.grupos_investigacion_x_sedes = data.grupos_investigacion_x_sedes;
        $scope.grupos_investigacion_ucc = data.grupos_investigacion_ucc;
        $scope.facultades_ucc = data.facultades_ucc;
        $scope.sedes_ucc = data.sedes_ucc;
        $scope.informacion_proyecto = data.informacion_proyecto;
            
        var date = null;
        var userTimezoneOffset = null;
        for (var i = 0; i < data.investigadores.length; i++) {
            
            // formatea la fecha de ejecución recibida en un objeto Date
            date = new Date(data.investigadores[i].fecha_ejecucion + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            data.investigadores[i].fecha_ejecucion = new Date(date.getTime() + userTimezoneOffset);
            
            // formatea los roles de los investigadores
            if(data.investigadores[i].id_rol == 3)
            {
                $scope.identificacion_investigador_principal = data.investigadores[i].identificacion;
                $scope.tipos_identificacion.forEach(function(item) {
                    if(item.id == data.investigadores[i].id_tipo_identificacio)
                        data.investigadores[i].nombre_tipo_identificacion = item.nombre;
                });
                for (var ii = 0; ii < $scope.grupos_investigacion_ucc.length; ii++) {
                    if($scope.grupos_investigacion_ucc[ii].id == data.investigadores[i].id_grupo_investigacion_ucc)
                    {
                        data.investigadores[i]['grupo_investigacion_ucc'] = $scope.grupos_investigacion_ucc[ii];
                        break;
                    }
                }
                data.investigadores[i]['rol'] = {
                    id: 3,
                    nombre: 'Investigador principal'
                };
            }
            
            if(data.investigadores[i].id_rol != 3)
            {
                // selecciona el rol de select
                $scope.roles.forEach(function(rol) {
                    if(data.investigadores[i].id_rol == rol.id)
                        data.investigadores[i].rol = rol;
                });
                
                // selecciona el tipo de identificacion de select
                $scope.tipos_identificacion.forEach(function(item) {
                    if(data.investigadores[i].id_tipo_identificacion == item.id)
                        data.investigadores[i].tipo_identificacion = item;
                });                
                
                // selecciona el sexo de select
                $scope.sexos.forEach(function(sexo) {
                    if(data.investigadores[i].sexo == sexo.id)
                        data.investigadores[i].sexo = sexo;
                });                
            }         
            
            // crea la lista de grupos de investigación y sedes de uiselect para el investigador
            if(data.investigadores[i].id_rol == 4)
            {
                // crea la lista de grupos de investigación
                var BreakException = {};
                try
                {
                    for(var key in $scope.grupos_investigacion_x_sedes)
                    {
                        var item = $scope.grupos_investigacion_x_sedes[key];
                        for(var ii = 0; ii < item.grupos_investigacion.length; ii++)
                        {
                            if(item.grupos_investigacion[ii].id == data.investigadores[i].id_grupo_investigacion_ucc)
                            {
                                data.investigadores[i]['grupos_investigacion_para_select'] = item.grupos_investigacion;
                                throw BreakException;
                            }
                        }
                    }
                }
                catch (e) {
                    if (e !== BreakException) throw e;
                }    
                
                // selecciona el grupo de investigacion de su select
                for (var ii = 0; ii < data.investigadores[i].grupos_investigacion_para_select.length; ii++) {
                    if(data.investigadores[i].grupos_investigacion_para_select[ii].id == data.investigadores[i].id_grupo_investigacion_ucc)
                    {
                        data.investigadores[i].grupo_investigacion_ucc = data.investigadores[i].grupos_investigacion_para_select[ii];
                        break;
                    }
                }
                
                // selecciona la sede de select
                try{
                    $scope.facultades_ucc.forEach(function(facultad) {
                        if(facultad.id == data.investigadores[i].grupo_investigacion_ucc.id_facultad_dependencia_ucc)
                        {
                            $scope.sedes_ucc.forEach(function(sede) {
                                if(sede.id == $scope.facultades_ucc[i].id_sede_ucc)
                                {
                                    data.investigadores[i].sede = sede;
                                    throw BreakException;
                                }                                
                            });
                        }
                    });
                }
                catch (e) {
                    if (e !== BreakException) throw e;
                }    
                
                // establece el nombre de la facultad del grupo de investigacion seleccionado
                data.investigadores[i].facultad_ucc = $scope.buscar_nombre_facultad_x_id_grupo_inv(data.investigadores[i].grupo_investigacion_ucc.id);
            } 
            
            if(data.investigadores[i].id_rol == 5 || data.investigadores[i].id_rol == 6)
            {
                data.investigadores[i].entidad_grupo_inv_externo = data.investigadores[i].entidad_o_grupo_investigacion;
            }
        }
        
        $scope.investigadores = data.investigadores;        
        console.log($scope.investigadores);
    };
    
    // las siguientes tres funciones permiten buscar el nombre de la sede, facultad y grupo respectivamente
    // para el investigador ppal en la inicializacaion de la vista
    $scope.buscar_nombre_sede_x_id_grupo_inv = function(id_grupo_investigacion_ucc) {
        
        var grupo = null;
        var facultad = null;
        var sede = null;
        for(var i = 0; i < $scope.grupos_investigacion_ucc.length; i++)
        {
            if($scope.grupos_investigacion_ucc[i].id == id_grupo_investigacion_ucc)
            {
                grupo = $scope.grupos_investigacion_ucc[i];
                break;                
            }
        }
        
        for(var i = 0; i < $scope.facultades_ucc.length; i++)
        {
            if($scope.facultades_ucc[i].id == grupo.id_facultad_dependencia_ucc)
            {
                facultad = $scope.facultades_ucc[i];
                break;
            }
        }
        
        for (var i = 0; i < $scope.sedes_ucc.length; i++) 
        {
            if($scope.sedes_ucc[i].id == facultad.id_sede_ucc)
            {
                sede = $scope.sedes_ucc[i];
                break;
            }
        }
        if(sede)
            return sede.nombre;
        return null;
    };
    $scope.buscar_nombre_facultad_x_id_grupo_inv = function(id_grupo_investigacion_ucc) {
        
        var grupo = null;
        var facultad = null;
        for(var i = 0; i < $scope.grupos_investigacion_ucc.length; i++)
        {
            if($scope.grupos_investigacion_ucc[i].id == id_grupo_investigacion_ucc)
            {
                grupo = $scope.grupos_investigacion_ucc[i];
                break;                
            }
        }
        
        for(var i = 0; i < $scope.facultades_ucc.length; i++)
        {
            if($scope.facultades_ucc[i].id == grupo.id_facultad_dependencia_ucc)
            {
                facultad = $scope.facultades_ucc[i];
                break;
            }
        }   
        
        if(facultad)
            return facultad.nombre;
        return null;
    };
    $scope.buscar_nombre_grupo_x_id_grupo_inv = function(id_grupo_investigacion_ucc) {
        
        var grupo = null;
        for(var i = 0; i < $scope.grupos_investigacion_ucc.length; i++)
        {
            if($scope.grupos_investigacion_ucc[i].id == id_grupo_investigacion_ucc)
            {
                grupo = $scope.grupos_investigacion_ucc[i];
                break;                
            }
        }
        
        if(grupo)
            return grupo.nombre;
        return null;
    };
    
    /*
	|--------------------------------------------------------------------------
	| buscar_datos_x_id()
	|--------------------------------------------------------------------------
	| Consulta los datos básicos de un particiante
	*/          
    $scope.buscar_datos_x_id = function(){
        
        if($scope.identificacion_a_buscar === null || $scope.identificacion_a_buscar === undefined){
            $scope.data.msj_label_busqueda_id = 'Error: ingrese una identificacion válida';
            return;
        }
        else
        {
            var regex = /^\d+$/;
            if(!regex.test($scope.identificacion_a_buscar)){
                $scope.data.msj_label_busqueda_id = 'Error: ingrese una identificacion válida';
                return;
            }
        }        
        
        if($scope.identificacion_a_buscar == $scope.identificacion_investigador_principal){
            $scope.data.msj_label_busqueda_id = 'Error: ingrese una identificacion diferente a la del investigador principal';
            return;
        }
        
        // no se permite agregar dos veces a la misma persona. Busca si la identificacion ya esta en los participantes agregados
        var BreakException = {};
        try{
            $scope.investigadores.forEach(function (investigador) {
                if(investigador.identificacion == $scope.identificacion_a_buscar)
                    throw BreakException;
            });
        }
        catch (e) {
            if (e !== BreakException) throw e
            else{
                $scope.data.msj_label_busqueda_id = 'Error: ya existe un participante agregado con la misma identificación';
                return;
            }
        }    
        
        $scope.data.msj_label_busqueda_id = '';
            
        $('#input_buscar_id').trigger( "blur" );
        
        $scope.data.msj_busqueda_id = '<h4 class="text-center">Buscando datos...<i class="fa fa-circle-o-notch fa-spin fa-fw"></i></h4>';
        $scope.visibilidad.velo_busqueda_id = true;
        
        $http({
            url: '/usuarios/buscar_datos_basicos',
            method: 'GET',
            params: {
                identificacion: $scope.identificacion_a_buscar
            }
        })
        .success(function(data) {
            console.log(data);
            if(data.consultado == 1){
                if(data.existe_cc){
                    
                    $scope.nombres_nuevo_participante = data.persona.nombres;
                    $scope.apellidos_nuevo_participante = data.persona.apellidos;
                    
                    $scope.formaciones.forEach(function(item) {
                        if(item == data.persona.formacion)
                            $scope.data.formacion_nuevo_participante = item;
                    });
                    $scope.tipos_identificacion.forEach(function(item) {
                        if(data.persona.id_tipo_identificacion == item.id)
                            $scope.data.tipo_identificacion_nuevo_participante = item;
                    }); 
                    $scope.sexos.forEach(function(item) {
                        if(item.id == data.persona.sexo)
                            $scope.data.sexo_nuevo_participante = item;
                    });
                    $scope.edad_nuevo_participante = data.persona.edad;
                    
                    $scope.datos_basicos_persona_recuperados = true;
                    $scope.data.msj_busqueda_id = '<h4 class="text-center">Datos recuperados para la identificación ' + data.persona.identificacion + '</h4>';
                }
                else{
                    $scope.data.msj_busqueda_id = '<h4 class="text-center">No existen coincidencias con la identificacion. Registrar nuevos datos.</h4>';
                }
                $scope.identificacion_nuevo_participante = Number($scope.identificacion_a_buscar);
                $scope.visibilidad.velo_edicion_datos_participante = false;
            }
            else{
                console.log(data);
                $scope.visibilidad.velo_busqueda_id = false;
                $scope.data.msj_label_busqueda_id = 'Error al buscar los datos, código de error: ' + data.codigo;
            }
        })
        .error(function(data, status) {
            $scope.data.msj_label_busqueda_id = 'Error al buscar los datos, código de estado: ' + status;
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| buscar_otra_id()
	|--------------------------------------------------------------------------
	| Controlador de evento click para el boton de buscar otra identificaicon
	| desde la sección de ingreso de datos del nuevo participante. 
	| Retorna a la sección de busqueda de datos básicos por id borrando los campos de ingreso del nuevo participante
	*/             
    $scope.buscar_otra_id = function(){
        $scope.identificacion_a_buscar = null;
        $scope.visibilidad.velo_busqueda_id = false;
        $scope.visibilidad.velo_edicion_datos_participante = true;
        $scope.datos_basicos_persona_recuperados = false;
        $scope.borrar_modelos_nuevo_participante();
        $('#input_buscar_id').trigger( "focus" );
    };    
    
    /*
	|--------------------------------------------------------------------------
	| borrar_modelos_nuevo_participante()
	|--------------------------------------------------------------------------
	| Borra los modelos de los inputs de ingreso de nuevo participante
	| Usado cuando se regresa a la barra de busqueda de nueva identifiación
	*/                 
    $scope.borrar_modelos_nuevo_participante = function() {
        $scope.identificacion_nuevo_participante = null;
        
        $scope.nombres_nuevo_participante = null;
        $scope.nombres_nuevo_participante_invalido = false;
        
        $scope.apellidos_nuevo_participante = null;
        $scope.apellidos_nuevo_participante_invalido = false;
        
        $scope.data.formacion_nuevo_participante = null;
        $scope.formacion_nuevo_participante_invalido = false;
        
        $scope.data.tipo_identificacion_nuevo_participante = null;
        $scope.tipo_id_nuevo_participante_invalido = false;
        
        $scope.data.sexo_nuevo_participante = null;
        $scope.sexo_nuevo_participante_invalido = false;
        
        $scope.edad_nuevo_participante = null;
        $scope.edad_nuevo_participante_invalido = false;
        
        $scope.email_nuevo_participante = null;
        $scope.email_nuevo_participante_invalido = false;
        
        $scope.data.sede_nuevo_participante = null;
        $scope.sede_nuevo_participante_invalido = false;
        
        $scope.data.grupo_inv_nuevo_participante = null;
        $scope.grupo_inv_nuevo_participante_invalido = false;
        
        $scope.facultad_nuevo_participante = null;
        $scope.facultad_nuevo_participante_invalido = false;
        
        $scope.entidad_externa_nuevo_participante = null;
        $scope.entidad_externa_nuevo_participante_invalido = false;
        
        $scope.programa_academico_nuevo_participante = null;
        $scope.programa_academico_participante_invalido = false;

        $scope.fecha_ejecucion_nuevo_participante = null;
        $scope.fecha_ejecucion_nuevo_participante_invalido = false;
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_participante()
	|--------------------------------------------------------------------------
	| Agrega nuevo investogador
	*/                     
    $scope.agregar_participante = function() {
        var validacion = false;
        validacion |= $scope.validar_nombres_nuevo_participante();
        validacion |= $scope.validar_apellidos_nuevo_participante();
        validacion |= $scope.validar_formacion_nuevo_participante();
        validacion |= $scope.cambia_rol_proyecto_nuevo_participante();
        validacion |= $scope.validar_tipo_id_nuevo_participante();
        validacion |= $scope.validar_sexo_nuevo_participante();
        validacion |= $scope.validar_edad_nuevo_participante();
        validacion |= $scope.validar_email_nuevo_participante();
        validacion |= $scope.validar_fecha_ejecucion_nuevo_participante();
        
        if($scope.rol_nuevo_participante_invalido.id == 4)
        {
            validacion |= $scope.validar_sede_nuevo_participante();
            validacion |= $scope.validar_grupo_inv_nuevo_participante();
            validacion |= $scope.validar_facultad_nuevo_participante();
        }
        else if($scope.rol_nuevo_participante_invalido.id == 5)
        {
            validacion |= $scope.validar_entidad_externa_nuevo_participante();
        }
        else if($scope.rol_nuevo_participante_invalido.id == 6)
        {
            validacion |= $scope.validar_entidad_externa_nuevo_participante();
            validacion |= $scope.validar_programa_acad_nuevo_participante();
        }
        
        if(validacion)
            return;
            
        var nuevo_investigador = {
            id_investigador: 'nuevo',
            es_nuevo_participante: 1,
            es_investigador_principal: 0,
            tiene_desembolso : 0,
            tiene_usuario: $scope.datos_basicos_persona_recuperados,
            es_encargado_de_algun_producto: 0,
            nombres: $scope.nombres_nuevo_participante,
            apellidos: $scope.apellidos_nuevo_participante,
            identificacion: $scope.identificacion_nuevo_participante,            
            edad: $scope.edad_nuevo_participante,
            email: $scope.email_nuevo_participante,
            formacion: $scope.data.formacion_nuevo_participante,
            fecha_ejecucion: $scope.fecha_ejecucion_nuevo_participante,
            dedicacion_horas_semanales: 0,
            total_semanas: 0,
            valor_hora: 0
        };
        // establece el rol del nuevo investigador
        $scope.roles.forEach(function(rol) {
            if(rol.id == $scope.data.rol_nuevo_participante.id)
                nuevo_investigador['rol'] = rol;
        });
        // establece tipo de identificacion
        $scope.tipos_identificacion.forEach(function(tipo_id) {
            if(tipo_id.id == $scope.data.tipo_identificacion_nuevo_participante.id)
                nuevo_investigador['tipo_identificacion'] = tipo_id;
        });
        // establece sexo
        $scope.sexos.forEach(function(sexo) {
            if(sexo.id == $scope.data.sexo_nuevo_participante.id)
                nuevo_investigador['sexo'] = sexo;
        });
        
        // establece campos específicos del rol del nuevo participante
        if(nuevo_investigador.rol.id == 4)
        {
            for(var key in $scope.sedes_ucc)
            {
                if($scope.sedes_ucc[key].id == $scope.data.sede_nuevo_participante.id)
                {
                    nuevo_investigador['sede'] = $scope.sedes_ucc[key];
                }
            }
            nuevo_investigador['grupos_investigacion_para_select'] = $scope.grupos_investigacion_x_sedes[nuevo_investigador.sede.id].grupos_investigacion;
            for (var i = 0; i < nuevo_investigador.grupos_investigacion_para_select.length; i++) {
                if(nuevo_investigador.grupos_investigacion_para_select[i].id == $scope.data.grupo_inv_nuevo_participante.id)
                {
                    nuevo_investigador['grupo_investigacion_ucc'] = nuevo_investigador.grupos_investigacion_para_select[i];
                    break;
                }
            }
            nuevo_investigador['facultad_ucc'] = $scope.buscar_nombre_facultad_x_id_grupo_inv(nuevo_investigador.grupo_investigacion_ucc.id);
        }
        else if(nuevo_investigador.rol.id == 5)
        {
            nuevo_investigador['entidad_grupo_inv_externo'] = $scope.entidad_externa_nuevo_participante;
        }
        else if(nuevo_investigador.rol.id == 6)
        {
            nuevo_investigador['entidad_grupo_inv_externo'] = $scope.entidad_externa_nuevo_participante;
            nuevo_investigador['programa_academico'] = $scope.programa_academico_nuevo_participante;
        }
        $scope.investigadores.push(nuevo_investigador);
        alertify.success('Participante agregado');
        $scope.buscar_otra_id();
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_participante()
	|--------------------------------------------------------------------------
	| Remueve un participante de la lista de investigadores agregados
	| Crea un input hidden que identifica el investigzdor existente en la BD a eliminar
	*/                         
    $scope.remover_participante = function(participante) {
        
        if(participante.es_encargado_de_algun_producto || participante.tiene_desembolso)
        {
            alertify.error('El participante no puede removerse ya que es encargado de algun producto del proyecto o su gasto de personal ya cuenta con un desembolso cargado');
            return;
        }
        
        var index_participante = $scope.investigadores.indexOf(participante);
        if(index_participante != -1)
            $scope.investigadores.splice(index_participante, 1);
            
        if(participante.id_investigador != 'nuevo')
            $('#contenedor_ids_participantes_a_eliminar')
                .append('<input type="hidden" name="participantes_a_eliminar[]" value="' + participante.id_investigador + '"/>');            
    };
    
    /*
	|--------------------------------------------------------------------------
	| show_modal_grupos_investigacion()
	|--------------------------------------------------------------------------
	| Presenta el modal de entidades y grupos de investigación que participan en el proyecto
	*/  
    $scope.show_modal_grupos_investigacion = function() {
        // Crea y muestra el modal de revisión de evidencia
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_grupos_investigacion.html',
            controller: 'modal_grupos_investigacion_controller',
            size: 'lg',
            scope: $scope,
            backdrop: 'static',
            resolve: {
                investigadores: function() {
                    return $scope.investigadores;
                }
            }
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de campos de nuevo participante
	| Todas las validaciones retornar true si la validación es incorrecta
	|--------------------------------------------------------------------------
	*/                         
    $scope.validar_nombres_nuevo_participante = function() {
        var validacion = validar_cadena($scope.nombres_nuevo_participante, 3, 200);
        $scope.nombres_nuevo_participante_invalido = validacion;
        return validacion;        
    };
    
    $scope.validar_apellidos_nuevo_participante = function() {
        var validacion = validar_cadena($scope.apellidos_nuevo_participante, 3, 200);
        $scope.apellidos_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_formacion_nuevo_participante = function() {
        var validacion = true;
        if($scope.data.formacion_nuevo_participante)
            validacion = false;
        $scope.formacion_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.cambia_rol_proyecto_nuevo_participante = function() {
        var validacion = true;
        if($scope.data.rol_nuevo_participante)
            validacion = false;
        $scope.rol_nuevo_participante_invalido = validacion;
        return validacion;        
    };
    
    $scope.validar_tipo_id_nuevo_participante = function() {
        var validacion = true;
        if($scope.data.tipo_identificacion_nuevo_participante)
            validacion = false;
        $scope.tipo_id_nuevo_participante_invalido = validacion;
        return validacion;        
    };
    
    $scope.validar_sexo_nuevo_participante = function() {
        var validacion = true;
        if($scope.data.sexo_nuevo_participante)
            validacion = false;
        $scope.sexo_nuevo_participante_invalido = validacion;
        return validacion;                     
    };
    
    $scope.validar_edad_nuevo_participante = function() {
        var validacion = validar_numero($scope.edad_nuevo_participante, 10);
        $scope.edad_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_email_nuevo_participante = function() {
        var validacion = false;
        if($scope.email_nuevo_participante == null || $scope.email_nuevo_participante == undefined)
            validacion = true;
        validacion = !/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/.test($scope.email_nuevo_participante);
        $scope.email_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_sede_nuevo_participante = function () {
        var validacion = true;
        if($scope.data.sede_nuevo_participante)
            validacion = false;
        $scope.sede_nuevo_participante_invalido = validacion;
        return validacion;
    };    
    
    $scope.validar_grupo_inv_nuevo_participante = function() {
        var validacion = true;
        if($scope.data.grupo_inv_nuevo_participante)
            validacion = false;
        $scope.grupo_inv_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_facultad_nuevo_participante = function () {
        var validacion = true;
        if($scope.facultad_nuevo_participante)        
            validacion = false;
        $scope.facultad_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_entidad_externa_nuevo_participante = function() {
        var validacion = validar_cadena($scope.entidad_externa_nuevo_participante, 5);
        $scope.entidad_externa_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    $scope.validar_programa_acad_nuevo_participante = function() {
        var validacion = validar_cadena($scope.programa_academico_nuevo_participante, 5);
        $scope.programa_academico_participante_invalido = validacion;
        return validacion;        
    };    
    
    $scope.validar_fecha_ejecucion_nuevo_participante = function() {
        var validacion = true;
        if($scope.fecha_ejecucion_nuevo_participante)
            validacion = false;
        $scope.fecha_ejecucion_nuevo_participante_invalido = validacion;
        return validacion;
    };
    
    /*
	|--------------------------------------------------------------------------
	| ngChanges para selects de sede y grupo de investigacion de nuevo participante
	| se encargan de poblar las opciones del secet de grupo de investigacion en funcion de la sede seleccionada
	| y de establecer la facultad del grupo de inv seleccionado
	|--------------------------------------------------------------------------
	*/                      
    $scope.cambia_sede_nuevo_participante = function() {
        
        $scope.data.grupo_inv_nuevo_participante = null;
        $scope.facultad_nuevo_participante = null;
        $scope.grupos_inv_nuevo_participante = $scope.grupos_investigacion_x_sedes[$scope.data.sede_nuevo_participante.id].grupos_investigacion;
        $scope.validar_sede_nuevo_participante();
    };
    
    $scope.cambia_grupo_inv_nuevo_participante = function() {
        for (var i = 0; i < $scope.facultades_ucc.length; i++) {
            if($scope.facultades_ucc[i].id == $scope.data.grupo_inv_nuevo_participante.id_facultad_dependencia_ucc)
            {
                $scope.facultad_nuevo_participante = $scope.facultades_ucc[i];
                break;
            }
        }
        $scope.validar_grupo_inv_nuevo_participante();
    };
    
    /*
	|--------------------------------------------------------------------------
	| ngCahnges y validaciones para campos de investigadores agregados 
	| las validaciones retornan true si la validacion ha sido incorrecta
	|--------------------------------------------------------------------------
	*/    
    $scope.validar_nombres_participante_agregado = function (investigador) {
        var validacion = validar_cadena(investigador.nombres, 3);
        investigador.nombres_invalidos = validacion;
        return validacion;
    };
    $scope.validar_apellidos_participante_agregado = function(investigador) {
        var validacion = validar_cadena(investigador.apellidos, 3);
        investigador.apellidos_invalidos = validacion;
        return validacion;        
    };
    $scope.validar_identificacion_participante_agregado = function(investigador) {
        var validacion = validar_numero(investigador.identificacion, 1);
        investigador.identificacion_invalido = validacion;
        return validacion;                
    };
    $scope.validar_formacion_participante_agregado = function(investigador) {
        var validacion = true;
        if(investigador.formacion)
            validacion = false;
        investigador.formacion_invalido = validacion;
        return validacion;
    };
    $scope.validar_rol_participante_agregado = function (investigador) {
        var validacion = true;
        if(investigador.rol)
            validacion = false;
        investigador.rol_invalido = validacion;
        return validacion;        
    };
    $scope.validar_tipo_id_participante_agregado = function(investigador){
        var validacion = true;
        if(investigador.tipo_identificacion)
            validacion = false;
        investigador.tipo_id_invalido = validacion;
        return validacion;                
    };
    $scope.validar_sexo_participante_agregado = function(investigador){
        var validacion = true;
        if(investigador.sexo)
            validacion = false;
        investigador.sexo_invalido = validacion;
        return validacion;                        
    };
    $scope.validar_edad_participante_agregado = function (investigador) {
        var validacion = validar_numero(investigador.edad, 10);
        investigador.edad_invalido = validacion;
        return validacion;
    };
    $scope.validar_email_participante_agregado = function(investigador) {
        var validacion = false;
        if(investigador.email == null || investigador.email == undefined)
            validacion = true;
        validacion = !/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,4}$/.test(investigador.email);
        $scope.email_invalido = validacion;
        return validacion;
    };
    $scope.validar_sede_participante_agregado = function(investigador) {
        var validacion = true;
        if(investigador.sede)
            validacion = false;
        investigador.sede_invalido = validacion;
        return validacion;                        
    };
    $scope.validar_grupo_inv_participante_agregado = function(investigador){
        var validacion = true;
        if(investigador.grupo_investigacion_ucc)
            validacion = false;
        investigador.grupo_inv_invalido = validacion;
        return validacion;                                
    };
    $scope.validar_entidad_externa_participante_agregado = function(investigador) {
        var validacion = validar_cadena(investigador.entidad_grupo_inv_externo , 5);
        investigador.entidad_o_grupo_investigacion_invalido = validacion;
        return validacion;        
    };
    $scope.validar_programa_academico_participante_agregado = function (investigador) {
        var validacion = validar_cadena(investigador.programa_academico, 5);
        investigador.programa_academico_invalido = validacion;
        return validacion;                
    };
    
    /*
	|--------------------------------------------------------------------------
	| Envía formulario, validando informacion de los participantes agregados primero
	|--------------------------------------------------------------------------
	*/        
    $scope.guardar = function() {
        
        var validacion = false;
        $scope.investigadores.forEach(function(investigador) {
            if(investigador.rol.id != 3 && !investigador.tiene_usuario)
            {
                validacion |= $scope.validar_nombres_participante_agregado(investigador);
                validacion |= $scope.validar_apellidos_participante_agregado(investigador);
                validacion |= $scope.validar_identificacion_participante_agregado(investigador);
                validacion |= $scope.validar_formacion_participante_agregado(investigador);
                validacion |= $scope.validar_rol_participante_agregado(investigador);
                validacion |= $scope.validar_tipo_id_participante_agregado(investigador);
                validacion |= $scope.validar_sexo_participante_agregado(investigador);
                validacion |= $scope.validar_edad_participante_agregado(investigador);
                validacion |= $scope.validar_email_participante_agregado(investigador);
                if(investigador.rol.id == 4)
                {
                    validacion |= $scope.validar_sede_participante_agregado(investigador);
                    validacion |= $scope.validar_grupo_inv_participante_agregado(investigador);
                }
                else if(investigador.rol.id == 5)
                {
                    validacion |= $scope.validar_entidad_externa_participante_agregado(investigador);
                }
                else if(investigador.rol.id == 6)
                {
                    validacion |= $scope.validar_entidad_externa_participante_agregado(investigador);
                    validacion |= $scope.validar_programa_academico_participante_agregado(investigador);
                }
            }
        });
        if (validacion)
        {
            alertify.error('Validacion incorrecta');
            return;
        }
        
        if($scope.investigadores.length > 1)
        {
            // valida que no se encuentren identificaciones repetidas
            var alguna_identificacion_repetida = false;
            for (var i = 0; i < $scope.investigadores.length; i++) {
                if($scope.investigadores[i].rol.id != 3)
                {
                    for (var ii = 0; ii < $scope.investigadores.length; ii++) {
                        if(ii == i) continue;
                        if($scope.investigadores[ii].rol.id != 3)
                        {
                            if($scope.investigadores[i].identificacion == $scope.investigadores[ii].identificacion || $scope.investigadores[i].identificacion == $scope.identificacion_investigador_principal)
                            {
                                $scope.investigadores[i].identificacion_invalido = true;
                                alguna_identificacion_repetida = true;
                            }
                        }
                    }
                }
            }
            if(alguna_identificacion_repetida){
                alertify.error('Error de validación de datos. Uno o varias identificaciones estan repetidas');
                return;
            }
            alertify.success('Guardando cambios');
            $scope.visibilidad.show_velo_general = true;
            $scope.data.msj_operacion_general = '<h3 class="text-center">Guardando cambios...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';            
            $('#btn_submit_form').trigger('click');
        }
        else{
            alertify.success('Guardando cambios');
            $scope.visibilidad.show_velo_general = true;
            $scope.data.msj_operacion_general = '<h3 class="text-center">Guardando cambios...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';                        
            $('#btn_submit_form').trigger('click');
        }
    };
});


/*
|--------------------------------------------------------------------------
| modal_grupos_investigacion_controller
|--------------------------------------------------------------------------
| Controlador para el modal que presenta en resumen los grupos de investigación y entidades que participan
*/              
sgpi_app.controller('modal_grupos_investigacion_controller', function ($scope, $uibModalInstance, investigadores) {
    
    $scope.entidades_grupos = [];
    
    $scope.entidad_grupo_ya_agregado = function(entidad_grupo) {
        // se vuelve true si se encuentra un nombre de entidad_grupo ya añadido
        var resultado_busqueda = false;
        
        $scope.entidades_grupos.forEach(function(item) {
            if(entidad_grupo.nombre == item.nombre && entidad_grupo.rol == item.rol)
                resultado_busqueda = true;
        });
        return resultado_busqueda;
    };    
    
    $scope.investigadores.forEach(function(participante) {
        
        if(participante.rol.id == 3)
        {
            var entidad_grupo = {
                nombre: participante.grupo_investigacion_ucc.nombre,
                rol: 'Ejecutor'
            };            
            $scope.entidades_grupos.push(entidad_grupo);
        }
        if(participante.rol.id == 4){
            
            var entidad_grupo = {
                nombre: participante.grupo_investigacion_ucc.nombre,
                rol: 'Co-ejecutor'
            };
            
            if(!$scope.entidad_grupo_ya_agregado(entidad_grupo))
                $scope.entidades_grupos.push(entidad_grupo);
        }
        else if(participante.rol.id == 5 || participante.rol.id == 6){
            
            var entidad_grupo = {
                nombre: participante.entidad_grupo_inv_externo,
                rol: 'Co-ejecutor'
            }
            
            if(!$scope.entidad_grupo_ya_agregado(entidad_grupo))
                $scope.entidades_grupos.push(entidad_grupo);        
        }
    });
    
    $scope.cerrar = function () {
        $scope.$close();
    };
});



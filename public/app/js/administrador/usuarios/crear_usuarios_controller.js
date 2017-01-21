sgpi_app.controller('crear_usuarios_controller', function($scope, $http, Alertify, Upload){
    
    /*
    |--------------------------------------------------------------------------
    | visibilidad y data
    |--------------------------------------------------------------------------
    | objetos para mantener los modelos de la vista y dispuestos para su acceso a $scopes hijos
    */
    $scope.data = {
        msj_busqueda_identificacion: '',
        msj_operacion: '<h3 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></h3>',
        datos_iniciales_consultados: false,
        grupos_investigacion_correspondientes: [],
        btn_guardar_seleccionado: false,
        token_integridad: null,
        identificacion_consultada : null,
        id_readonly: false
    };
    $scope.visibilidad = {
        show_velo_buscar_identificacion: false,
        show_velo_contenedor_datos_usuario: true,
        show_sede: false,
        show_grupo_inv: false,
        show_categoria_inv: false,
        deshabilitar_btn_guardar: false,
        show_cargando_guardado: false,
        show_error_cargando_guardado: false,
        show_msj_operacion_busqueda: false
    };
    
    /*
    |--------------------------------------------------------------------------
    | ajax inicial
    |--------------------------------------------------------------------------
    | Solicita datos de la GUI necesarios iniciales
    */
    $http({
            method: 'GET',
            url: '/usuarios/data_inicial_vista_crear',
        })
        .success(function(data){
            if(data.consultado !== undefined || data.consultado !== null){
                $scope.init(data);
                $scope.data.msj_operacion = '<h3 class="text-center">Buscar datos básicos por identificación</h3>';
                $scope.data.datos_iniciales_consultados = true;
            }
            else{
                console.log('Error en la carga de datos iniciales, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                Alertify.error('Error en la carga de datos iniciales, código de error: ' + data.codigo);
                $scope.data.msj_operacion = 'Error en la carga de datos iniciales, código de error: ' + data.codigo;
                $scope.data.datos_iniciales_consultados = false;
            }
        })
        .error(function(data, status) {
            Alertify.error('Error en la carga de datos iniciales, código de error: ' + status);
            console.log('Error en la carga de datos iniciales: ');
            console.log(data);
        });
        // .finally(function() {
        //     $scope.visibilidad.show_velo_contenedor_datos_usuario = false;
        // });
    
    /*
    |--------------------------------------------------------------------------
    | init()
    |--------------------------------------------------------------------------
    | Inicializa los modelos con los datos recuperados de la peticion ajax inicial.
    | Se inicializan todos los modelos en un mismo tiempo para que la GUI refleje en conjunto datos 
    */
    $scope.init = function(data){
        $scope.data.categorias_investigador = data.categorias_investigador;
        $scope.data.grupos_investigacion_y_sedes = data.grupos_investigacion_y_sedes;
        $scope.data.roles = data.roles;
        $scope.data.tipos_identificacion = data.tipos_identificacion;
        $scope.data.sedes = data.sedes;
        $scope.data.sexos = [{id: 'm', nombre: 'Hombre'}, {id: 'f', nombre: 'Mujer'}];
        $scope.data.formaciones = ['Ph. D', 'Doctorado', 'Maestría', 'Especialización', 'Pregado'];
    };
    
    /*
    |--------------------------------------------------------------------------
    | cambia_tipo_usuario()
    |--------------------------------------------------------------------------
    | ngChange para <select> de tipos de usuario; en funcion del tipo de uusario elegido se ocultan elementos GUI con el fin
    | de siolicitar al usuario solo los datos correspondientes al tipo de usuario elegido
    */
    $scope.cambia_tipo_usuario = function(){
        if($scope.data.rol.id == 1){
            $scope.visibilidad.show_sede = false;
            $scope.visibilidad.show_grupo_inv = false;
            $scope.visibilidad.show_categoria_inv = false;
        }
        else if($scope.data.rol.id == 2){
            $scope.visibilidad.show_sede = true;
            $scope.visibilidad.show_grupo_inv = true;
            $scope.visibilidad.show_categoria_inv = false;
        }
        else if($scope.data.rol.id == 3){
            $scope.visibilidad.show_sede = true;
            $scope.visibilidad.show_grupo_inv = true;
            $scope.visibilidad.show_categoria_inv = true;
        }
        $scope.validar_tipo_usuario();
    };

    /*
    |--------------------------------------------------------------------------
    | btn_add_usuario_click()
    |--------------------------------------------------------------------------
    | Realiza validaciones de los controles GUI para enviar solamente datos correctos
    */    
    $scope.btn_add_usuario_click = function(){
        

        if(!$scope.data.btn_guardar_seleccionado)
            $scope.data.btn_guardar_seleccionado = true;
            
        // Se validan solo los campos requeridos por el tipo de usuario actualmente seleccionado
        var validaciones = [
                $scope.validar_nombres(),
                $scope.validar_apellidos(),
                $scope.validar_id(),
                $scope.validar_tipo_id(),
                $scope.validar_sexo(),
                $scope.validar_edad(),
                $scope.validar_formacion(),
                $scope.validar_email(),
                $scope.validar_tipo_usuario(),
                $scope.validar_username(),
                $scope.validar_password()
            ];
        if($scope.data.rol){
            if($scope.data.rol.id == 2){ // coordinador, se añade a la validación la sede y el grupo de inv
                validaciones.push($scope.validar_sede());
                validaciones.push($scope.validar_grupo_inv());
            }
            else if($scope.data.rol.id == 3){ // investigador, se añade a la validación la sede, el grupo de inv y la categoria de inv
                validaciones.push($scope.validar_sede());
                validaciones.push($scope.validar_grupo_inv());
                validaciones.push($scope.validar_categoria_inv());
            } 
        }
        else
            return;
            
        var alguna_validacion_incorrecta = validaciones.indexOf(false);
        if(alguna_validacion_incorrecta != -1) // se encontró una validacion incorrecta (false), se cancela resto de operacion
            return;
            
        // Se alcanzó este código, quiere decir que las validaciones han sido correctas,
        // se procede a enviar identificación, combinacion de nombres y apellidos y nombre de usuario para validar que no se encuentren repetidos antes de enviar el formulario
        $scope.visibilidad.show_cargando_guardado = $scope.visibilidad.show_velo_contenedor_datos_usuario = true;
        $scope.data.msj_operacion = '<h3 class="text-center" style="top:45%;">Validando datos...<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></h3>';
        var hide_cargando_guardado = true;
        $scope.visibilidad.show_error_cargando_guardado = false;
        $http({
            method: 'GET',
            url: '/usuarios/validar_crear_usuario',
            params: {
                identificacion: $scope.data.identificacion,
                username: $scope.data.username,
                rol: $scope.data.rol.id,
                token_integridad: $scope.data.token_integridad
            }
        })
        .success(function(data){
            console.log(data);
            if(data.consultado !== undefined){
                if(data.consultado){
                    if(data.datos_validos){
                        // Enviar formulario
                        $scope.data.msj_operacion = '<h3 class="text-center" style="top:45%;">Registrando usuario...<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i></h3>';
                        hide_cargando_guardado = false;
                        $('#btn_cargar').trigger('click');
                    }
                    else{
                        if(data.error == 'existe_username'){
                            Alertify.error('Nombre de usuario repetido');
                            $scope.visibilidad.show_username_invalido = true;
                        }
                        else if(data.error == 'token_integridad'){
                            alertify.set('notifier','delay', 0);
                            alertify.error('Ya está registrada la identificación de la persona; consultar primero sus datos en la barra superior de búsqueda por identificación ');
                            $scope.visibilidad.show_id_invalido = true;
                            $scope.buscar_id();
                            $('#input_buscar_id').focus();
                        }
                        else if(data.error == 'rol'){
                            Alertify.error('Esta persona ya cuenta con un usuario ' + $scope.data.rol.nombre);
                            $scope.visibilidad.show_rol_invalido = true;
                        }
                    }
                }
                else{
                    console.log('Error en la consulta de validación de datos, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                    Alertify.error('Error en la consulta de validación de datos, código de error: ' + data.codigo);
                    $scope.visibilidad.show_error_cargando_guardado = true;
                    return;
                }
            }
            else{
                var msj = 'Error en validacion de datos';
                console.log(msj +  ' - Respuesta inesperada del servidor.');
                Alertify.error(msj);
                $scope.visibilidad.show_error_cargando_guardado = true;
                return;
            }
        })
        .error(function(data, status) {
            var msj = 'Error en la consulta de duplicidad de datos, código de error: ' + status;
            console.log(data);
            Alertify.error(msj);
            $scope.visibilidad.show_error_cargando_guardado = true;
        })
        .finally(function() {
            $scope.visibilidad.show_cargando_guardado = false;
            if(hide_cargando_guardado)
                $scope.visibilidad.show_velo_contenedor_datos_usuario = false;
        });
    };
    
    /*
    |--------------------------------------------------------------------------
    | cambia_sede()
    |--------------------------------------------------------------------------
    | ngChange para <select> de sede; en función de la sede elegida se alimenta el <select> de grupos de investigación
    */
    $scope.cambia_sede = function(){
        
        var grupos = $scope.data.grupos_investigacion_y_sedes[$scope.data.sede.id].grupos_investigacion;
        $scope.data.grupos_investigacion_correspondientes = grupos;
        $scope.validar_sede();
        $scope.data.grupo_investigacion = null;
        $scope.validar_grupo_inv();
    };
    
    /*
    |--------------------------------------------------------------------------
    | cambiaFoto()
    |--------------------------------------------------------------------------
    | establece explicitamente el modelo de foto en null
    */
    $scope.cambiaFoto = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        if(invalidFiles.length){
            $scope.data.foto = null;
            $('#foto').val("");
        }
    };    
    
    /*
    |--------------------------------------------------------------------------
    | validar_nombres()
    |--------------------------------------------------------------------------
    | Valida input nombres comprobrando longitud del texto
    */        
    $scope.validar_nombres = function(){
        if($scope.data.nombres != undefined && $scope.data.nombres.length > 0){
            $scope.visibilidad.show_nombres_invalido = false;
            return true;
        }
        $scope.visibilidad.show_nombres_invalido = true;
        return false;
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_apellidos()
    |--------------------------------------------------------------------------
    | Valida input apellidos comprobrando longitud del texto
    */
    $scope.validar_apellidos = function(){
        if($scope.data.apellidos != undefined && $scope.data.apellidos.length > 0){
            $scope.visibilidad.show_apellidos_invalido = false;
            return true;
        }
        $scope.visibilidad.show_apellidos_invalido = true;
        return false;        
    };

    /*
    |--------------------------------------------------------------------------
    | validar_id()
    |--------------------------------------------------------------------------
    | Valida input de identificacion asegurandose de que sea un numeroi mayor a 0
    */    
    $scope.validar_id = function(){
        
        if($scope.data.identificacion_consultada != null)
        {
            if($scope.data.identificacion_consultada == $scope.data.identificacion)
                $scope.data.token_integridad = 1;
            else
                $scope.data.token_integridad = null;
        }
            
        if($scope.data.identificacion != undefined && $scope.data.identificacion > 0){
            $scope.visibilidad.show_id_invalido = false;
            return true;
        }
        $scope.visibilidad.show_id_invalido = true;
        return false;                
    };

    /*
    |--------------------------------------------------------------------------
    | validar_tipo_id()
    |--------------------------------------------------------------------------
    | Valida <select> de tipo de identificacion asegurandose de que el modelo tenga un tipo de id seleccionado
    */        
    $scope.validar_tipo_id = function(){
        if($scope.data.tipo_identificacion){
            $scope.visibilidad.show_tipo_id_invalido = false;
            return true;
        }
        $scope.visibilidad.show_tipo_id_invalido = true;
        return false;
    };

    /*
    |--------------------------------------------------------------------------
    | validar_sexo()
    |--------------------------------------------------------------------------
    | Valida <select> de sexo asegurandose de que el modelo tenga un obj sexo
    */            
    $scope.validar_sexo = function(){
        if($scope.data.sexo){
            $scope.visibilidad.show_sexo_invalido = false;
            return true;
        }
        $scope.visibilidad.show_sexo_invalido = true;
        return false;        
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_edad()
    |--------------------------------------------------------------------------
    | Valida <select> de edad asegurandose de que el modelo tenga sea mayor a 0
    */            
    $scope.validar_edad = function(){
        if($scope.data.edad){
            $scope.visibilidad.show_edad_invalido = false;
            return true;
        }
        $scope.visibilidad.show_edad_invalido = true;
        return false;        
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_formacion()
    |--------------------------------------------------------------------------
    | Valida <select> de sexo asegurandose de que el modelo tenga un obj formacion
    */        
    $scope.validar_formacion = function(){
        if($scope.data.formacion){
            $scope.visibilidad.show_formacion_invalido = false;
            return true;
        }
        $scope.visibilidad.show_formacion_invalido = true;
        return false;          
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_email()
    |--------------------------------------------------------------------------
    | Valida input de email asegurandose que si contenido sea email con validacion nativa de angular
    */    
    $scope.validar_email = function(){
        if($scope.form_nuevo_usuario.email.$valid){
            $scope.visibilidad.show_email_invalido = false;
            return true;
        }
        $scope.visibilidad.show_email_invalido = true;
        return false;               
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_tipo_usuario()
    |--------------------------------------------------------------------------
    | Valida <select> de tipo de usuario verificando que el modelo tenga un obj de tipo de usuario
    */        
    $scope.validar_tipo_usuario = function(){
        if($scope.data.rol){
            $scope.visibilidad.show_rol_invalido = false;
            return true;
        }
        $scope.visibilidad.show_rol_invalido = true;
        return false;            
    };

    /*
    |--------------------------------------------------------------------------
    | validar_categoria_inv()
    |--------------------------------------------------------------------------
    | Valida <select> de categoria de investigador verificando que el modelo tenga un obj de categoria
    */            
    $scope.validar_categoria_inv = function(){
        if($scope.data.categoria_investigador){
            $scope.visibilidad.show_categoria_inv_invalido = false;
            return true;
        }
        $scope.visibilidad.show_categoria_inv_invalido = true;
        return false;                    
    };

    /*
    |--------------------------------------------------------------------------
    | validar_categoria_inv()
    |--------------------------------------------------------------------------
    | Valida <select> de sede verificando que el modelo tenga un obj de sede
    */                
    $scope.validar_sede = function(){
        if($scope.data.sede){
            $scope.visibilidad.show_sede_invalido = false;
            return true;
        }
        $scope.visibilidad.show_sede_invalido = true;
        return false;                    
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_grupo_inv()
    |--------------------------------------------------------------------------
    | Valida <select> de grupo de investigacion verificando que el modelo tenga un obj de grupo
    */                    
    $scope.validar_grupo_inv = function(){
        if($scope.data.grupo_investigacion){
            $scope.visibilidad.show_grupo_inv_invalido = false;
            return true;
        }
        $scope.visibilidad.show_grupo_inv_invalido = true;
        return false;                            
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_username()
    |--------------------------------------------------------------------------
    | Valida input de username comprobando que el texto no se encuentr evació y que tenga la longitud mínima
    */              
    $scope.validar_username = function(){
        if($scope.form_nuevo_usuario.username.$valid){
            $scope.visibilidad.show_username_invalido = false;
            return true;
        }
        $scope.visibilidad.show_username_invalido = true;
        return false;                                    
    };
    
    /*
    |--------------------------------------------------------------------------
    | validar_password()
    |--------------------------------------------------------------------------
    | Valida inputs de password verificando que las contraseñas coincidan
    */    
    $scope.validar_password = function(){
        if($scope.form_nuevo_usuario.password.$valid)
            if($scope.data.password == $scope.data.repeat_password){
                $scope.visibilidad.show_password_invalido = false;
                return true;
            }
        $scope.visibilidad.show_password_invalido = true;
        return false;
    };

    /*
    |--------------------------------------------------------------------------
    | buscar_id()
    |--------------------------------------------------------------------------
    | Busca la info de un usuario dado su identificacion en el input de busqueda de de usuario por identificacion
    */       
    $scope.buscar_id = function(){
        
        if($scope.data.buscar_identificacion == undefined || $scope.data.buscar_identificacion == 0)
            return;
        
        $scope.data.msj_operacion_busqueda = 'Buscando...<i class="fa fa-circle-o-notch fa-spin fa-1x fa-fw"></i>';
        $scope.visibilidad.show_msj_operacion_busqueda = true;
        $http({
                method: 'GET',
                url: '/usuarios/buscar_datos_basicos',
                params: {
                    identificacion: $scope.data.buscar_identificacion
                }
            })
            .success(function(data){
                if(data.consultado !== undefined){
                    if(data.consultado){
                        if(data.existe_cc){
                            
                            // se inicializa los modelos solo si los datos iniciales han sido recuperados
                            // ya que si se inicializan los modelos con los datos recuperados pero sinlos datos iniciales como las listas desplegables y demás, 
                            // se estaría llevando a cabo procesamiento innecesario
                            if($scope.data.datos_iniciales_consultados){
                                
                                $scope.visibilidad.show_msj_operacion_busqueda = false;
                                
                                $scope.data.nombres = data.persona.nombres;
                                $scope.data.apellidos = data.persona.apellidos;
                                $scope.data.identificacion_consultada = $scope.data.identificacion = data.persona.identificacion;
                                $scope.data.tipos_identificacion.forEach(function(item){
                                    if(item.id == data.persona.id_tipo_identificacion)
                                        $scope.data.tipo_identificacion = item;
                                });
                                $scope.data.sexos.forEach(function(item){
                                    if(item.id == data.persona.sexo)
                                        $scope.data.sexo = item
                                });
                                $scope.data.edad = data.persona.edad;
                                $scope.data.formaciones.forEach(function(item){
                                    if(item == data.persona.formacion)
                                        $scope.data.formacion = item; 
                                });
                                
                                $scope.data.token_integridad = 1;
    
                                $scope.validar_nombres();
                                $scope.validar_apellidos();
                                $scope.validar_id();
                                $scope.validar_tipo_id();
                                $scope.validar_sexo();
                                $scope.validar_edad();
                                $scope.validar_formacion();
                                
                                $scope.data.msj_busqueda_identificacion = 'Ingrese los datos del usuario';
                                Alertify.success('Datos básicos consultados');
                                
                                // se muestran los velos bloqueando la búsqueda de identificación y presentando los inputs de los datos de usario
                                $scope.visibilidad.show_velo_buscar_identificacion = true;
                                $scope.visibilidad.show_velo_contenedor_datos_usuario = false;
                            }
                        }
                        else{
                            $scope.data.msj_operacion_busqueda = 'No existen registros';
                            if($scope.data.datos_iniciales_consultados){
                                
                                $scope.data.msj_busqueda_identificacion = 'No se han encontrado registros, ingrese nuevos datos';
                                
                                // permite la edición del campo identificacion ya que se trata del registro de un usuario totalmente nuevo
                                $scope.data.id_readonly = false;
                                
                                $scope.data.identificacion = $scope.data.buscar_identificacion;
                                
                                // se muestran los velos bloqueando la búsqueda de identificación y presentando los inputs de los datos de usario
                                $scope.visibilidad.show_velo_buscar_identificacion = true;
                                $scope.visibilidad.show_velo_contenedor_datos_usuario = false;
                            }
                        }
                    }
                    else{
                        var msj = 'Error en la búsqueda de datos básicos: ' + data.codigo;
                        $scope.data.msj_operacion_busqueda = msj;
                        Alertify.error(msj);
                        console.log(msj + ', mensaje: ' + data.mensaje);
                    }
                }
                else{
                    var msj = 'Error en la búsqueda de datos básicos';
                    Alertify.error(msj);
                    $scope.data.msj_operacion_busqueda = msj;
                    $scope.visibilidad.show_msj_operacion_busqueda = true;
                    console.log(msj + ': respuesta inesperada de servidor.');
                }
            })
            .error(function(data, status) {
                var msj = 'Error en la búsqueda de datos básicos, código de error: ' + status;
                Alertify.error(msj);
                $scope.data.msj_operacion_busqueda = msj;
                $scope.visibilidad.show_msj_operacion_busqueda = true;
                console.log(msj);
                console.log(data);
            });
        
    };

    /*
    |--------------------------------------------------------------------------
    | volver_a_buscar()
    |--------------------------------------------------------------------------
    | Devuelve a la sección de búsqueda de identificación
    */    
    $scope.volver_a_buscar = function(){
        $scope.data.msj_operacion = '<h3 class="text-center">Buscar datos básicos por identificación</h3>';
        $scope.borrar_modelos();
        $scope.visibilidad.show_velo_buscar_identificacion = false;
        $scope.visibilidad.show_velo_contenedor_datos_usuario = true;
    };
    
    /*
    |--------------------------------------------------------------------------
    | borrar_modelos()
    |--------------------------------------------------------------------------
    | Borra los datos y las selecciones de los controles GUI,
    | esto es utilizado cuando se retorna a la sección de ingreso de identificación
    */    
    $scope.borrar_modelos = function(){
        $scope.data.nombres = null;
        $scope.data.apellidos = null;
        $scope.data.identificacion = null;
        $scope.data.tipo_identificacion = null;
        $scope.data.sexo = null;
        $scope.data.edad = null;
        $scope.data.formacion = null;
        $scope.data.username = null;
        $scope.data.password = null;
        $scope.data.repeat_password = null;
        $scope.data.email = null;
        $scope.data.rol = null;
        $scope.data.categoria_investigador = null;
        $scope.data.sede = null;
        $scope.data.grupo_investigacion = null;
        $scope.data.foto = null;
    };
    
});
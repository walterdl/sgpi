$(document).ready(function() {
	$(window).keydown(function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			return false;
		}
	});    
});

// idUsuario es un valor inicializado en el metodo value del módulo sgpi_app,
// ver vista html en la parte inferior; contiene el id_usuario actual
sgpi_app.controller('propio_perfil_controller', function($scope, $http, idUsuario, $window, $uibModal){
    
    $scope.data = {
        msj_operacion: '<h3 class="text-center">Cargando datos iniciales...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>',
        id_usuario: idUsuario
    };
    $scope.visibilidad = {
        show_velo_msj_operacion: true,
        show_foto_previa: true
    };
    
    // se registra el controlador resize para la ventana una vez que el documento halla cargado
    $(document).ready(function() {
        $($window).bind('resize', $scope.window_resize);
        $scope.window_resize();
    });
    
    // cuando la ventana cambie de tamaño (ancho), se aplica un valor a margin top a las columnas que estan enseguida de la columna de foto
    // ...esto para mantener aproximados los campos unos de otros
    $scope.window_resize = function(){
        if($window.innerWidth < 768){
            $scope.visibilidad.margin_top_nombres = $scope.visibilidad.margin_top_apellidos = $scope.visibilidad.margin_top_identificacion = false;
        }
        else if($window.innerWidth < 992){
            $scope.visibilidad.margin_top_nombres = true;
            $scope.visibilidad.margin_top_apellidos = $scope.visibilidad.margin_top_identificacion = false;
        }
        else if($window.innerWidth < 1200){
            $scope.visibilidad.margin_top_nombres = $scope.visibilidad.margin_top_apellidos = true; 
            $scope.visibilidad.margin_top_identificacion = false;
        }
        else{
            $scope.visibilidad.margin_top_nombres = $scope.visibilidad.margin_top_apellidos = $scope.visibilidad.margin_top_identificacion = true;
        }
    };
    
    // Consulta de los datos iniciales necesarios para la vista
    $http({
        url: '/usuarios/data_inicial_propio_perfil',
        mehtod: 'GET',
        params:{
            id_usuario: idUsuario
        }
    })
    .success(function(data){
        console.log(data);
        $scope.init(data);
        $scope.visibilidad.show_velo_msj_operacion = false;
    })
    .error(function(data, status){
        console.log(data);
        $scope.data.msj_operacion = '<h3 class="text-center">Un error ha ocurrido en la carga inicial de datos. Código de error: ' + data.codigo + '</h3>';
    });
    
	/*
	|--------------------------------------------------------------------------
	| init()
	|--------------------------------------------------------------------------
	| Inicializa los modelos de la vista con los datos del usuario consultados,
	| así como las listas para los controles ui-select
	*/              
    $scope.init = function(datos_iniciales){
        
        // inicializacion de modelos y controles de la sección de datos básicos
        $scope.data.nombres = datos_iniciales.datos_usuario.nombres;
        $scope.data.apellidos = datos_iniciales.datos_usuario.apellidos;
        $scope.data.identificacion = datos_iniciales.datos_usuario.identificacion;
        $scope.data.tipos_identificacion = datos_iniciales.tipos_identificacion;
        for(var i = 0; i < $scope.data.tipos_identificacion.length; i++){
            if($scope.data.tipos_identificacion[i].id == datos_iniciales.datos_usuario.id_tipo_identificacion){
                $scope.data.tipo_identificacion = $scope.data.tipos_identificacion[i];
                break;
            }
        }
        $scope.data.sexos = [{
                id: 'm',
                nombre: 'Hombre'
            },
            {
                id: 'f',
                nombre: 'Mujer'
            }];
        for(var i = 0; i < $scope.data.sexos.length; i++){
            if($scope.data.sexos[i].id == datos_iniciales.datos_usuario.sexo){
                $scope.data.sexo = $scope.data.sexos[i];
                break;
            }
        }
        $scope.data.edad = datos_iniciales.datos_usuario.edad;
        $scope.data.formaciones = ['Ph. D', 'Doctorado', 'Maestría', 'Especialización', 'Pregado'];
        for(var i = 0; i < $scope.data.formaciones.length; i++){
            if($scope.data.formaciones[i] == datos_iniciales.datos_usuario.formacion){
                $scope.data.formacion = $scope.data.formaciones[i];
                break;
            }
        }
        $scope.data.email = datos_iniciales.datos_usuario.email;
        $scope.data.rol = datos_iniciales.datos_usuario.id_rol;
        
        // inicializacion de modelos y controles de la sección de datos del usuario
        /*
        Si es administrador edita:
        -username, password y foto
        
        Si es coordinador edita:
        -username, password, foto, sede origen y grupo de investigación
        
        Si es investigador edita:
        -username, password, foto, sede origen, grupo de investigación y categoría de investigador
        */
        if(datos_iniciales.datos_usuario.id_rol == 1) // rol = 1 es admin
        {
            $scope.visibilidad.show_categoria_inv = $scope.visibilidad.show_sede = $scope.visibilidad.show_grupo_inv = false;
        }
        else if(datos_iniciales.datos_usuario.id_rol == 2) // rol = 2 es coordinador
        {
            $scope.visibilidad.show_categoria_inv = false;
            $scope.visibilidad.show_sede = $scope.visibilidad.show_grupo_inv = true;
        }
        else if(datos_iniciales.datos_usuario.id_rol == 3) // rol = 3 es investigador
        {
            $scope.visibilidad.show_categoria_inv = $scope.visibilidad.show_sede = $scope.visibilidad.show_grupo_inv = true;
            $scope.data.categorias_investigador = datos_iniciales.categorias_investigador;
            
            for (var i = 0; i < $scope.data.categorias_investigador.length; i++) {
                if($scope.data.categorias_investigador[i].id == datos_iniciales.datos_usuario.id_categoria_investigador){
                    $scope.data.categoria_investigador = $scope.data.categorias_investigador[i];
                    break;
                }
            } 
        }
        
        // inicializa de un solo 'golpe' sedes y grupo de investigacion si se trata de coordinador e investigador
        $scope.data.grupos_investigacion_y_sedes = datos_iniciales.grupos_investigacion_y_sedes;
        if(datos_iniciales.datos_usuario.id_rol == 2 || datos_iniciales.datos_usuario.id_rol == 3){
            $scope.data.sedes = datos_iniciales.sedes;
            for(var i = 0; i < $scope.data.sedes.length; i++){
                if($scope.data.sedes[i].id == datos_iniciales.datos_usuario.id_sede){
                    $scope.data.sede = $scope.data.sedes[i];
                    // se inicializa a la vez los grupos de investigacion correspondientes de la sede
                    $scope.data.grupos_investigacion_correspondientes = datos_iniciales.grupos_investigacion_y_sedes[$scope.data.sede.id].grupos_investigacion;
                    for(var ii = 0; ii < $scope.data.grupos_investigacion_correspondientes.length; ii++){
                        if($scope.data.grupos_investigacion_correspondientes[ii].id == datos_iniciales.datos_usuario.id_grupo_inv){
                            $scope.data.grupo_investigacion = $scope.data.grupos_investigacion_correspondientes[ii];
                            break;
                        }
                    }
                    break;
                }
            }
        }
        // $scope.data.copia_username_original
        $scope.data.username = datos_iniciales.datos_usuario.username;
        $scope.data.tipo_usuario = datos_iniciales.datos_usuario.nombre_rol;
    }
    
    // controlador de evento click para botón de cambiar contraseña
    // presenta el modal de cambio de contrasela
    $scope.cambiar_contrasenia = function(){
        
        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'modal_cambio_pasdword.html',
            controller: 'password_controller',
            size: 'md',
            scope: $scope,
            backdrop: 'static',
        });
        
        // Captura los eventos de cierre de modal, determinando su causa para mostrar un Alertify
        modalInstance.result.then(function(resultado){
            // modal cerrado
            alertify.success('Contraseña cambiada');
        }, function(resultado){
            // modal cancelado
            console.log(resultado);
            if(resultado != null && resultado != undefined)
                if(resultado != 'escape key press')
                    alertify.error('Error en cambio de contraseña. Detalles: ' + resultado);
        });        
    };

	/*
	|--------------------------------------------------------------------------
	| cambiaFoto()
	|--------------------------------------------------------------------------
	| Controlador de evento para selección de archivo,
	| se encarga de reconfirmar si se ha seleccionado un archivo inválido
	| para mostrar o no la previa de la foto actual o presentar la previa de la foto seleccionada
	*/                      
    $scope.cambiaFoto = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        
        if(file){ // si hay archivo quiere decir que se ha elegido una foto aceptable
            // se esconde la previa de la imagen actual para mostrar la previa de la imagen seleccionada
            $scope.visibilidad.show_foto_previa = false;
        }
        else{
            // no se selecciono una foto aceptable, se muestra foto previa actual
            $scope.visibilidad.show_foto_previa = true;
        }
    };

	/*
	|--------------------------------------------------------------------------
	| limpiar_foto()
	|--------------------------------------------------------------------------
	| Controlador de evento click del botón que limpia la previa de la imagen seleccionad actualemte
	*/                          
    $scope.limpiar_foto = function(){
        // se borra la foto que se ha seleccionado y se muestra la previa de la foto actual
        $scope.data.foto = null;
        $scope.visibilidad.show_foto_previa = true;
    };
    
	/*
	|--------------------------------------------------------------------------
	| guardar()
	|--------------------------------------------------------------------------
	| Aplica validaciones antes de enviar el formulario al servidor, así mismo se presenta
	| mensajes de error correspondientes a los campos con sus modelos invalidos
	*/                              
    $scope.guardar = function(){
        
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
                $scope.validar_username()
            ];

        if($scope.data.rol == 2){ // coordinador, se añade a la validación la sede y el grupo de inv
            validaciones.push($scope.validar_sede());
            validaciones.push($scope.validar_grupo_inv());
        }
        else if($scope.data.rol == 3){ // investigador, se añade a la validación la sede, el grupo de inv y la categoria de inv
            validaciones.push($scope.validar_sede());
            validaciones.push($scope.validar_grupo_inv());
            // validaciones.push($scope.validar_categoria_inv());
        } 
            
        var alguna_validacion_incorrecta = validaciones.indexOf(false);
        if(alguna_validacion_incorrecta != -1) // se encontró una validacion incorrecta (false), se cancela resto de operacion
        {
            alertify.error('Error: datos incorrectos');
            return;
        }
        
        // se alcanzó este código, quiere decir que las primeras validaciones son correctas
        // se procede a realizar otra validación en el servidor: nombre de usuario repetido y/o identificación repetida
        $scope.data.msj_operacion = '<h3 class="text-center">Validando datos...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
        $scope.visibilidad.show_velo_msj_operacion = true;
        $http({
            url: '/usuarios/validar_username_identificacion',
            method: 'POST',
            params: {
                identificacion: $scope.data.identificacion,
                username: $scope.data.username,
                id_usuario: idUsuario
            }
        })
        .success(function (data) {
            console.log(data);
            if(data.consultado == 1){
                // campos correctos, se envía formulario
                $scope.data.msj_operacion = '<h3 class="text-center">Guardando datos...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
                $('#btn_cargar').trigger('click');
            }
            else{
                var algun_dato_duplicado = false; // indica si algun dato se ha duplicado
                
                if(data.identificacion_duplicada){
                    $scope.visibilidad.show_identificacion_invalido = true;
                    alertify.error('Error: la identificación ya existe');
                    algun_dato_duplicado = true;
                }
                if(data.username_duplicado){
                    $scope.visibilidad.show_username_invalido = true;
                    alertify.error('Error: el nombre de usuario ya existe');
                    algun_dato_duplicado = true;
                }
                
                if(!algun_dato_duplicado)
                {
                    // si el servidor devuelve un codigo de error y no se trata de algun dato duplicado, 
                    // ...se trata de una excepcion de proceso de programación
                    console.log(data);
                    alertify.error('Error al validar los datos. Detalles: ' + data.codigo);
                }
                $scope.visibilidad.show_velo_msj_operacion = false;
            }
        })
        .error(function (data, status) {
            console.log(data);
            alertify.error('Error al validar los datos. Detalles: ' + status);
            $scope.visibilidad.show_velo_msj_operacion = false;
        });
    };
    
    
    /*
    |--------------------------------------------------------------------------
    | validar_nombres()
    |--------------------------------------------------------------------------
    | Valida input nombres comprobrando longitud del texto
    */        
    $scope.validar_nombres = function(){
        if($scope.data.nombres != undefined && $scope.data.nombres != null && $scope.data.nombres.length >= 3 && $scope.data.nombres.length <= 200){
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
        if($scope.data.apellidos != undefined && $scope.data.apellidos != null && $scope.data.apellidos.length >= 3 && $scope.data.apellidos.length <= 200){
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
        var regex = /^\d+$/;
        if(!regex.test($scope.data.identificacion))
        {
            $scope.visibilidad.show_identificacion_invalido = true;
            return false;                    
        }
        if($scope.data.identificacion != undefined && $scope.data.identificacion > 0){
            $scope.visibilidad.show_identificacion_invalido = false;
            return true;
        }
        $scope.visibilidad.show_identificacion_invalido = true;
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
        var regex = /^\d+$/;
        if(!regex.test($scope.data.identificacion)){
            $scope.visibilidad.show_edad_invalido = true;
            return false;                    
        }
        if($scope.data.edad >= 10){
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
        if($scope.form_edicion_propio_perfil.email.$valid){
            $scope.visibilidad.show_email_invalido = false;
            return true;
        }
        $scope.visibilidad.show_email_invalido = true;
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
        
        if($scope.data)
        
        if($scope.form_edicion_propio_perfil.username.$valid){
            $scope.visibilidad.show_username_invalido = false;
            return true;
        }
        $scope.visibilidad.show_username_invalido = true;
        return false;                                    
    };    
    
});


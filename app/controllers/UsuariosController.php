<?php 

    class UsuariosController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| listar()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de todos los usuarios registrados de tipo adminstrador (excepto el propio), coordinador e investigador
    	*/
        public function listar(){
            
            // provee estilos personalizados para la vista a cargar
            $styles = [
                'vendor/datatables/dataTables.bootstrap.css',
                'vendor/angular-datatables/css/angular-datatables.min.css',
                'vendor/angular-datatables/plugins/bootstrap/datatables.bootstrap.min.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'app/css/usuarios/usuarios_desactivados.css'
                ]; 
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/angular-datatables/angular-datatables.min.js',
                'vendor/angular-datatables/plugins/bootstrap/angular-datatables.bootstrap.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                ];

                
            $post_scripts = [
                'administrador/usuarios/listar_usuarios_controller.js'
                ];
            
            $angular_sgpi_app_extra_dependencies = ['ngSanitize', 'datatables', 'datatables.bootstrap'];
            
            return View::make('administrador.usuarios.listar', array(
                // 'usuarios' => $usuarios, 
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| usuarios_para_admin()
    	|--------------------------------------------------------------------------
        | retorna json con los usuarios
    	*/
        public function usuarios_para_admin(){
            $usuarios = Usuario::usuarios_para_admin();
            
            if(count($usuarios) >0){
               return json_encode(array(
                   "usuarios"=>$usuarios,
                   "consulta"=>1
                   )); 
            }else{
                return json_encode(array(
                    "usuarios"=>$usuarios,
                    "consulta"=>0
                    )); 
            }
        
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| mas_info_usuario()
    	|--------------------------------------------------------------------------
    	| Retorno json con más información de un usuario dado su id
    	*/
        public function mas_info_usuario(){
            
            // contendrá ya sea una respuesta de error o la información adicional del usuario
            // el contenido de esta variable se define en el transcurso del método
            $respuesta = array(); 
            try{
                $info_usuario = Usuario::mas_info_usuario(Input::all()['id_usuario']);
                $respuesta['info_usuario'] = $info_usuario;
                $respuesta['consultado'] = 1; // indica que se efectuo la consulta correctamente
            }
            catch(Exceocion $e){
                $respuesta['consultado'] = 0; // indica que no se efectuo la consulta correctamente
                $respuesta['codigo'] = $e->getCode();
                $respuesta['mensaje'] = $e->getMessage();
            }
            return json_encode($respuesta);
        }

    	/*
    	|--------------------------------------------------------------------------
    	| crear()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de creación de nuevo usuario
    	*/        
        public function crear(){
            
            $styles = ['vendor/angular-ui/ui-select.css', 'vendor/angular-ui/overflow-ui-select.css'];
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui/ui-select.js',
                ];
            $post_scripts = [
                'administrador/usuarios/crear_usuarios_controller.js'
                ];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize', 'ngFileUpload'];

            return View::make('administrador.usuarios.crear', array(
                    'styles' => $styles,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));            
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| editarVer()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de edición de nuevo usuario
    	*/  
        
        public function editarVer($id){
            
            $usuario=Usuario::find($id);
            // echo "hola";
            // print_r($usuario->estado);
            // die();
            
            $styles = ['vendor/angular-ui/ui-select.css', 'vendor/angular-ui/overflow-ui-select.css'];
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui/ui-select.js',
                ];
            $post_scripts = [
                'administrador/usuarios/editar_usuarios_controller.js'
                ];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize', 'ngFileUpload'];

            if(count($usuario) == 0){
                $vacio=true;
                
                $persona="";
                $usuario="";
                $rol="";
                $estado="";
                
            }else{
                $vacio=false;
                $persona=$usuario->persona;
                $rol=$usuario->rol;
                $estado=$usuario->estado;
                
                /// se colocan ya que al parecer para que aparescan en el json deben llamarse el objeto
                $temp=$usuario->persona->tipoIdentificacion;
                $temp2=$usuario->persona->categoria;
                $temp3=$usuario->rol;
                $temp4=$usuario->grupo;
                
                if($temp4){
                    $temp4->facultad->sede;
                }
                
            }

            return View::make('administrador.usuarios.editar', array(
                    'usuario' => $usuario,
                    'persona' => $persona,
                    'vacio' => $vacio,
                    'styles' => $styles,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                )); 
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| usuarioEditar()
    	|--------------------------------------------------------------------------
    	| Consulta los datos del usuario a editar
    	*/ 
        public function usuarioEditar(){
            
            $id=Input::all()['id_usuario'];
            $usuario=Usuario::find($id);
            
            if(count($usuario) > 0){
                return json_encode(array(
                    "consultado"=>1,
                    "usuario"=>$usuario,
                    "persona"=>$usuario->persona
                    ));
            }else{
                return json_encode(array("consultado"=>0));
            }
            
            
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| cambiarEstado()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de edición de nuevo usuario
    	*/  
        public function cambiarEstado(){
            
            $id=Input::all()['id_usuario'];
            $usuario=Usuario::find($id);

            if(count($usuario) > 0){
                
                if($usuario->id_estado == 1){
                    $usuario->id_estado =2;
                    $cambio="Inhabilitado";
                }else{
                    $usuario->id_estado =1;
                    $cambio="Habilitado";
                }
                
                $usuario->save();
                
                return json_encode(array("consultado"=>1,"usuario"=>$usuario,"cambio"=>$cambio));
            }else{
                return json_encode(array("consultado"=>0));
            }
            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| data_inicial_vista_crear()
    	|--------------------------------------------------------------------------
    	| Retorno json con los datos necesarios para el registro de un usuario en la vista crear de usuarios
    	*/          
        public function data_inicial_vista_crear(){
            
            // contendrá los datos requeridos a retornar
            // el contenido de esta variable se define en el transcurso del método
            $respuesta = null; 
            try{
                $tipos_identificacion = TipoIdentificacion::all();
                $categorias_investigador = CategoriaInvestigador::all();
                $roles = DB::table('roles')
                    ->whereIn('id', array(1, 2, 3))->get();
                $grupos_investigacion_y_sedes = GrupoInvestigacionUCC::get_grupos_investigacion_con_sedes();
                $sedes = SedeUCC::all();
                $respuesta = array(
                    'consultado' => 1, // indica que se efectuo la consulta correctamente
                    'tipos_identificacion' => $tipos_identificacion,
                    'categorias_investigador' => $categorias_investigador,
                    'roles' => $roles,
                    'grupos_investigacion_y_sedes' => $grupos_investigacion_y_sedes,
                    'sedes' => $sedes
                    );
            }
            catch(Excepcion $e){
                $respuesta['consultado'] = 0; // indica que no se efectuo la consulta correctamente
                $respuesta['codigo'] = $e->getCode();
                $respuesta['mensaje'] = $e->getMessage();
            }
            return json_encode($respuesta);
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| validar_crear_usuario()
    	|--------------------------------------------------------------------------
    	| Retorno json con respuesta de validación de datos del usuario antes de guardar o editar
    	*/           
        public function validar_crear_usuario(){
            
            try{
    
                $data = Input::all();
                $usuario = DB::table('usuarios')->where('username', '=', $data['username'])->get();

                if(isset($data['editar']))
                {
                    if($usuario){
                        if($data['username_verificar'] != $usuario[0]->username){
                            return json_encode(array(
                                'consultado' => 1,
                                'datos_validos' => 0,
                                'error' => 'existe_username'
                                ));
                        }
                    }
                }
                else
                {
                    if($usuario)
                    {
                        return json_encode(array(
                            'consultado' => 1,
                            'datos_validos' => 0,
                            'error' => 'existe_username'
                            ));
                    }
                    
                    // no existe nombre de usuario
                    $persona = DB::table('personas')->where('identificacion', '=', $data['identificacion'])->first();
                    if($persona)
                    {
                        if(isset($data['token_integridad']))
                        {
                            $usuario = DB::table('usuarios')->where('id_persona', '=', $persona->id)->where('id_rol', '=', $data['rol'])->first();
                            if($usuario)
                            {
                                return json_encode(array(
                                    'consultado' => 1,
                                    'datos_validos' => 0,
                                    'error' => 'rol'
                                    ));
                            }
                            else
                            {
                                return json_encode(array(
                                    'consultado' => 1,
                                    'datos_validos' => 1,
                                    ));
                            }
                        }
                        else
                        {
                            return json_encode(array(
                                'consultado' => 1,
                                'datos_validos' => 0,
                                'error' => 'token_integridad'
                                ));
                        }
                    }
                }//fin del else esta en el if principal
                
                // no existe identifcacion
                // se autoriza el envío de los datos
                return json_encode(array(
                    'consultado' => 1,
                    'datos_validos' => 1,
                    ));
                
            }
            catch(Exception $e){                
                return json_encode(array(
                    'consultado' => 0,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_nuevo_usuario()
    	|--------------------------------------------------------------------------
    	| 
    	*/                   
        public function guardar_nuevo_usuario(){
            
            try{
    
                $data = Input::all();
                $usuario = DB::table('usuarios')->where('username', '=', $data['username'])->first();
                if($usuario)
                {
                    Session::flash('notify_operacion_previa', 'error');
                    Session::flash('mensaje_operacion_previa', 'Error en la creación de usuario nuevo: el nombre de usuario usado ya existe');
                    return Redirect::to('usuarios');
                }
                
                // no existe nombre de usuario
                $persona = DB::table('personas')->where('identificacion', '=', $data['identificacion'])->first();
                if($persona)
                {
                    if(isset($data['token_integridad']) && $data['token_integridad'] == 1)
                    {
                        $usuario = DB::table('usuarios')->where('id_persona', '=', $persona->id)->where('id_rol', '=', $data['rol'])->first();
                        if($usuario)
                        {
                            Session::flash('notify_operacion_previa', 'error');
                            Session::flash('mensaje_operacion_previa', 'Error en la creación de usuario nuevo: la persona ya tiene el tipo de usuario');
                        }
                        else
                        {
                            // aquí crear usuario y asociarle el id de la persona con sus respetivas modificaciones
                            $persona = Persona::find($persona->id);
                            $persona->nombres = $data['nombres'];
                            $persona->apellidos = $data['apellidos'];
                            $persona->identificacion = $data['identificacion'];
                            $persona->sexo = $data['sexo'];
                            $persona->edad = $data['edad'];
                            $persona->formacion = $data['formacion'];
                            $persona->id_tipo_identificacion = $data['tipo_identificacion'];
                            if($persona->id_categoria_investigador == null)
                                $persona->id_categoria_investigador = $data['rol'] == 1 || $data['rol'] == 2 ? null : $data['categoria_investigador'];
                            $persona->save();
                            
                            Usuario::create(array(
                                'id_persona' => $persona->id,
                                'id_rol' => $data['rol'],
                                'id_estado' => 1,
                                'id_grupo_investigacion_ucc' => ($data['rol'] == 1 ? null : $data['grupo_investigacion']),
                                'username' => $data['username'],
                                'password' => Hash::make($data['password']),
                                'email' => $data['email']
                                ));
                            
                            Session::flash('notify_operacion_previa', 'success');
                            Session::flash('mensaje_operacion_previa', 'Usuario creado');
                        }
                    }
                    else
                    {
                        Session::flash('notify_operacion_previa', 'error');
                        Session::flash('mensaje_operacion_previa', 'Error en la creación de usuario nuevo: token_integridad');
                    }
                }
                else{
                    
                    // aquí crear tanto usuario como persona
                    $persona = Persona::create(array(
                        'nombres' => $data['nombres'],
                        'apellidos' => $data['apellidos'],
                        'identificacion' => $data['identificacion'],
                        'sexo' => $data['sexo'],
                        'edad' => $data['edad'],
                        'formacion' => $data['formacion'],
                        'id_tipo_identificacion' => $data['tipo_identificacion'],
                        'id_categoria_investigador' => ($data['rol'] == 1 || $data['rol'] == 2 ? null : $data['categoria_investigador']),
                        ));
                    Usuario::create(array(
                        'id_persona' => $persona->id,
                        'id_rol' => $data['rol'],
                        'id_estado' => 1,
                        'id_grupo_investigacion_ucc' => ($data['rol'] == 1 ? null : $data['grupo_investigacion']),
                        'username' => $data['username'],
                        'password' => Hash::make($data['password']),
                        'email' => $data['email']
                        ));
                    
                    Session::flash('notify_operacion_previa', 'success');
                    Session::flash('mensaje_operacion_previa', 'Usuario creado');
                }
                
            }
            catch(Exception $e){                
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la creación de usuario nuevo, código de error: '.$e->getCode().', detalle: '.$e->getMessage());
            }   
            return Redirect::to('usuarios/listar');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| actualizar_usuario()
    	|--------------------------------------------------------------------------
    	| 
    	*/                   
        public function actualizar_usuario(){
            
            try{
    
                $data = Input::all();
                $usuario = DB::table('usuarios')->where('username', '=', $data['username'])->first();
                $persona = DB::table('personas')->where('identificacion', '=', $data['identificacion'])->first();
                
                if($persona)
                {

                            //$usuario = DB::table('usuarios')->where('id_persona', '=', $persona->id)->where('id_rol', '=', $data['rol'])->first();
      
                            // aquí crear usuario y asociarle el id de la persona con sus respetivas modificaciones
                            $persona = Persona::find($persona->id);
                            $persona->nombres = $data['nombres'];
                            $persona->apellidos = $data['apellidos'];
                            $persona->identificacion = $data['identificacion'];
                            $persona->sexo = $data['sexo'];
                            $persona->edad = $data['edad'];
                            $persona->formacion = $data['formacion'];
                            $persona->id_tipo_identificacion = $data['tipo_identificacion'];
                            
                            if($persona->id_categoria_investigador == null)
                                $persona->id_categoria_investigador = $data['rol'] == 1 || $data['rol'] == 2 ? null : $data['categoria_investigador'];
                            $persona->save();
                            
                            $usuario=Usuario::find($usuario->id);
                            $usuario->id_rol = $data['rol'];
                            $usuario->id_grupo_investigacion_ucc = ($data['rol'] == 1 ? null : $data['grupo_investigacion']);
                            $usuario->username = $data['username'];
                            $usuario->email = $data['email'];
                            $usuario->save();
                            
                            Session::flash('notify_operacion_previa', 'success');
                            Session::flash('mensaje_operacion_previa', 'Usuario  acrualizado');
                    
                }else
                {
                    Session::flash('notify_operacion_previa', 'error');
                    Session::flash('mensaje_operacion_previa', 'Error en la Actualizacion del usuario : token_integridad');
                }
                
                
            }
            catch(Exception $e){                
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la Actualizacion del usuario , código de error: '.$e->getCode().', detalle: '.$e->getMessage());
            }   
            return Redirect::to('/usuarios/listar');
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| buscar_datos_basicos()
    	|--------------------------------------------------------------------------
    	| Busca los datos básicos de una persona dado su identificación
    	*/                           
        public function buscar_datos_basicos(){
            
            try{
                $persona = Persona::where('identificacion', '=', Input::all()['identificacion'])->get();
                
                if(count($persona))
                    return json_encode(array(
                        'consultado' => 1, 
                        'existe_cc' => 1,
                        'persona' => $persona[0]
                    ));
                else
                    return json_encode(array(
                        'consultado' => 1, 
                        'existe_cc' => 0
                    ));                    
            }
            catch(Exception $e){                
                
                return json_encode(array(
                    'consultado' => 0, 
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));
            }
        }

    	/*
    	|--------------------------------------------------------------------------
    	| ver_propio_perfil()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de edición de datos del propio perfil
    	*/          
        public function ver_propio_perfil(){
            
            $id_usuario = Auth::user()->id;
            $styles = ['vendor/angular-ui/ui-select.css', 'vendor/angular-ui/overflow-ui-select.css'];
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui/ui-select.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                ];
            $post_scripts = [
                'general/usuarios/propio_perfil_controller.js', 'general/usuarios/password_controller.js'
                ];
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ui.bootstrap', 'ui.select', 'ngFileUpload'];

            return View::make('general.usuarios.propio_perfil', array(
                    'id_usuario' => $id_usuario,
                    'styles' => $styles,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));                        
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| data_inicial_propio_perfil()
    	|--------------------------------------------------------------------------
    	| Retorno json con los datos del usuario actual y demás datos necesarios para la vista de edición de perfil
    	*/                  
        public function data_inicial_propio_perfil(){
            
            try{
                return json_encode(array(
                        'datos_usuario' => Usuario::mas_info_usuario(Input::all()['id_usuario']),
                        'sedes' => SedeUCC::all(),
                        'grupos_investigacion_y_sedes' => GrupoInvestigacionUCC::get_grupos_investigacion_con_sedes(),
                        'grupos_investigacion' => GrupoInvestigacionUCC::all(),
                        'tipos_identificacion' => TipoIdentificacion::all(),
                        'categorias_investigador' => CategoriaInvestigador::all(),
                        'consultado' => 1
                    ));
            }
            catch(Exception $e){
                return json_encode(array(
                        'consultado' => 2,
                        'mensaje' => $e->getMessage(),
                        'codigo' => $e->getCode()
                    ));
            }
        }

    	/*
    	|--------------------------------------------------------------------------
    	| guardar_edicion_propio_perfil()
    	|--------------------------------------------------------------------------
    	| Guarda las ediciones a los datos del propio perfil
    	*/          
        public function guardar_edicion_propio_perfil(){
            
            try{
                $data = Input::all();
                // antes de guardar los datos se realiza validaciones
                // la primera validación consiste en repetir la validacion del lado del cliente
                // sobre la duplicación de identificación y nombre de usuario
                
                $resultado_duplicidad = $this->validar_username_identificacion(array(
                        'identificacion' => $data['identificacion'],
                        'username' => $data['username'],
                        'id_usuario' => $data['id_usuario']
                        ));
                        
                // se decodifica json ya que la funcion devuelve json pues es diseñada originalmente para que sirva a peticiones ajax
                $resultado_duplicidad = json_decode($resultado_duplicidad);
                
                if($resultado_duplicidad->consultado != 1) // un código de consultado diferente de 1 significa error
                {
                    // el nombre de usuario y/o la identificación esta repetida, o se ha producido un error
                    Session::flash('notify_operacion_previa', 'error');
                    if(isset($resultado_duplicidad->identificacion_duplicada) || isset($resultado_duplicidad->username_duplicado))
                        Session::flash('mensaje_operacion_previa', 'Error en la edición del perfil: los datos provistos no son correctos; nombre de usuario y/o identificación inválidos');
                    else
                        Session::flash('mensaje_operacion_previa', 'Error en la edición del perfil. Detalles: '.$resultado_duplicidad->mensaje);
                    return Redirect::to('/usuarios/propio_perfil');
                }
                
                // se alcanzó este código, quiere decir que el nombre de usuario y la identificación no estan duplicados,
                // se procede a aplicar validaciones mas detalladas para cada dato
                // Se validan primero los datos básicos de todo tipo de usuario
                $usuario = Usuario::find($data['id_usuario']);
                $datos_basicos = array(
                        'nombres' => $data['nombres'],
                        'apellidos' => $data['apellidos'],
                        'identificacion' => $data['identificacion'],
                        'tipo_identificacion' => $data['tipo_identificacion'],
                        'sexo' => $data['sexo'],
                        'edad' => $data['edad'],
                        'formacion' => $data['formacion'],
                        'email' => $data['email'],
                    );
                $validaciones_datos_basicos = array(
                        'nombres' => array('required', 'min:5', 'max:250'),
                        'apellidos' => array('required', 'min:5', 'max:250'),
                        'identificacion' => array('required', 'integer', 'min:0', 'max:99999999999', 'unique:personas,identificacion,'.$usuario->id_persona),
                        'tipo_identificacion' => array('required', 'integer', 'digits_between:1,5'),
                        'sexo' => array('required', 'in:m,f'),
                        'edad' => array('required', 'integer', 'min:1', 'max:120'),
                        'formacion' => array('required', 'in:Ph. D,Doctorado,Maestría,Especialización,Pregado'),
                        'email' => 'required|email'
                    );
                if(Input::hasFile('foto')){
                    $datos_basicos['foto'] = $data['foto'];
                    $validaciones_datos_basicos['foto'] = 'mimes:jpg,jpeg,png';
                }
                
                $validator = Validator::make(
                    $datos_basicos,
                    $validaciones_datos_basicos
                );
                
                if($validator->fails())
                {
                    // The given data did not pass validation
                    Session::flash('notify_operacion_previa', 'error');
                    Session::flash('mensaje_operacion_previa', 'Error en la edición del perfil: los datos básicos provistos no son correctos');
                    // se deja este código para pruebas
                    // file_put_contents
                    // (
                    //     app_path().'/logs.log', 
                    //     "\r\n".print_r($validator->messages(), true)
                    //     ,FILE_APPEND
                    // );
                    return Redirect::to('/usuarios/propio_perfil');
                }
                
                // se alcanzó este código, quiere decir que los campos genéricos de todo tipo de usuario son correctos
                // se sigue con validación específica por tipo de usuario
                if($usuario->id_rol == 1) // admin
                {
                    $validator = Validator::make(
                        array(
                            'username' => $data['username']
                        ),
                        array(
                            'username' => array('required', 'min:5', 'max:50')
                        )
                    );
                }
                else if($usuario->id_rol == 2) // coordinador
                {
                    $validator = Validator::make(
                        array(
                            'username' => $data['username'],
                            'grupo_investigacion' => $data['grupo_investigacion']
                        ),
                        array(
                            'username' => array('required', 'min:5', 'max:50'),
                            'grupo_investigacion' => array('required', 'integer', 'min:1')
                        )
                    );                    
                }
                else if($usuario->id_rol == 3) // investigador
                {
                    $validator = Validator::make(
                        array(
                            'username' => $data['username'],
                            'grupo_investigacion' => $data['grupo_investigacion'],
                            'categoria_investigador' => $data['categoria_investigador']
                        ),
                        array(
                            'username' => array('required', 'min:5', 'max:50'),
                            'grupo_investigacion' => array('required', 'integer', 'min:1'),
                            'categoria_investigador' => array('required', 'integer', 'min:1')
                        )
                    );                                        
                }
                
                if($validator->fails())
                {
                    // The given data did not pass validation
                    Session::flash('notify_operacion_previa', 'error');
                    Session::flash('mensaje_operacion_previa', 'Error en la edición del perfil: los datos del usuario provistos no son correctos');
                    // se deja este código para pruebas
                    // file_put_contents
                    // (
                    //     app_path().'/logs.log', 
                    //     "\r\n".print_r($validator->messages(), true)
                    //     ,FILE_APPEND
                    // );
                    return Redirect::to('/usuarios/propio_perfil');
                }
                
                $persona = Persona::find($usuario->id_persona);
                // copia la imagen cargada a la carpeta de imagenes de perfil, eliminando la actual imagen de perfil si es que tiene
                if(Input::hasFile('foto')){
                    
                    Archivo::eliminar_imagen_perfil($persona);
                    $imagen_copiada = Archivo::copiar_imagen_perfil(Input::file("foto"), $persona);
                    if($imagen_copiada)
                        $persona->foto = $imagen_copiada->getFilename();
                }
                
                // se alcanzó este código, quiere decir que los datos de usuario son válidos, 
                // se procede a actualizar los datos del usuario
                $usuario->username = $data['username'];
                $usuario->email = $data['email'];
                $usuario->save();
                $persona->nombres = $data['nombres'];
                $persona->apellidos = $data['apellidos'];
                $persona->identificacion = $data['identificacion'];
                $persona->id_tipo_identificacion = $data['tipo_identificacion'];
                $persona->sexo = $data['sexo'];
                $persona->edad = $data['edad'];
                $persona->formacion = $data['formacion'];
                if($usuario->id_rol == 2){
                    $usuario->id_grupo_investigacion_ucc = $data['grupo_investigacion'];
                }
                else if($usuario->id_rol == 3){
                    $usuario->id_grupo_investigacion_ucc = $data['grupo_investigacion'];
                    $persona->id_categoria_investigador = $data['categoria_investigador'];
                }
                $usuario->save();
                $persona->save();
                
                Session::flash('notify_operacion_previa', 'success');
                Session::flash('mensaje_operacion_previa', 'Perfil modificado');
                return Redirect::to('/usuarios/propio_perfil');                
            }
            catch(Excepcion $e){
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la edición del perfil. Detalles: '.$e->getMessage());
                return Redirect::to('/usuarios/propio_perfil');
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| cambiar_contrasenia()
    	|--------------------------------------------------------------------------
    	| Retorno json con el resultado de la operación de edición de la contraseña 
    	| del uusario dado su id. 
    	| Los datos usados a travéz de la solicitud http son:
    	| nueva = nueva contraseña
    	| actual = actual contraseña
    	| confimada = confirmación de actual contraseña
    	| id_usuareio = id del usuario en el que se basa esta operacion
    	*/          
        public function cambiar_contrasenia(){
            
            try{
                $data = Input::all();
                // valida que las contraseñas coincidan
                if($data['nueva'] == $data['confirmada']){
                    // concifmacionde contraseña actual igual, 
                    // se procede a consultar si al actual contraseña es la correcta
                    $usuario = Usuario::find($data['id_usuario']);
                    if (Hash::check($data['actual'], $usuario->password))
                    {
                        // La contraseña calza...
                        // se camnia contraseña actual por la nueva
                        $usuario->password = Hash::make($data['nueva']);
                        $usuario->save();
                        // se retorna éxito
                        return json_encode(array(
                            'consultado' => 1
                            ));
                    }
                    else
                    {
                        // contraseña actual no coincide
                        return json_encode(array(
                            'consultado' => 2,
                            'error_de_servidor' => 1,
                            'mensaje' => 'contrasenia1'
                            ));
                    }
                }
                else{
                    // se informa que la confirmacion de la contraseña actual no coincide
                    return json_encode(array(
                        'consultado' => 2,
                        'error_de_servidor' => 1,
                        'mensaje' => 'contrasenia3'
                        ));
                }
            }
            catch(Exception $e){
                // error no esperado
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));
            }
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| validar_username_identificacion()
    	|--------------------------------------------------------------------------
    	| Retorno json con validación de duplicado de nombre de usuario e identifiación
    	| Recibe parametro opcional que permite pasar los datos a comparar de manera local,
    	| dejando la posibilidad de usar este metodo desde otra funcion de un controlador
    	*/
        public function validar_username_identificacion($data=null){
            
            try{
                if(!$data) // si no se ha pasado los datos a comparar como parámetro, se abstrae los datos por la solicitud http
                    $data = Input::all();
                $respuesta = array();
                if(Usuario::consultar_existencia_identificacion($data['identificacion'], $data['id_usuario'])->cantidad_registros > 0){
                    // ya hay un registro persona con la misma identificación
                    $respuesta['identificacion_duplicada'] = 1;
                }
                if(Usuario::consultar_existencia_nombre_usuario($data['username'], $data['id_usuario'])->cantidad_registros > 0){
                    // ya hay un registro usuario con el mismo nombre de ususario
                    $respuesta['username_duplicado'] = 1;
                }
                if(isset($respuesta['identificacion_duplicada']) || isset($respuesta['username_duplicado']))
                    $respuesta['consultado'] = 2;
                else
                    $respuesta['consultado'] = 1;
                return json_encode($respuesta);
            }
            catch(Exception $e){
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));
            }
        }
    }


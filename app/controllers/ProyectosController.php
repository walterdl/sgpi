<?php
    
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class ProyectosController extends BaseController {

    	/*
    	|--------------------------------------------------------------------------
    	| registra_sgpi()
    	|--------------------------------------------------------------------------
    	| Presenta un formulario que permite enviar un formulario POST para la creacion de un proyecto
    	*/          
        public function registra_sgpi(){
            return View::make('general.proyectos.formulario_simulacion_creacion_proyecto');
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| crear()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de registro de nuevo proyecto para un investigador principal dado
    	*/        
        public function crear(){
            
            // provee estilos personalizados para la vista a cargar
            $styles = [
                'vendor/ngAnimate/ngAnimate.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'vendor/angular-ui/ui-select.css', 
                'vendor/angular-ui/overflow-ui-select.css',
                'vendor/perfect-scrollbar/css/perfect-scrollbar.min.css'
                ]; 
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',                
                'vendor/angular-ui/ui-select.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                'vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'
                ];

            $post_scripts = [
                'investigador/proyectos/crear/crear_document_ready_externo.js',
                'investigador/proyectos/crear/crear_proyecto_controller.js',
                'investigador/proyectos/crear/crear_participantes_proyectos_controller.js',
                'investigador/proyectos/crear/crear_productos_proyectos_controller.js',
                'investigador/proyectos/crear/crear_gastos_proyectos_controller.js',
                'investigador/proyectos/crear/adjuntos_proyecto_controller.js'
                ];
            
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ngFileUpload', 'ui.bootstrap', 'ui.select'];
            
            return View::make('investigador.proyectos.crear', array(
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
 
    	/*
    	|--------------------------------------------------------------------------
    	| data_inicial_crear_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorno json con la información necesaria para la vista de creacion o registro de nuevo proyecto de investigación
    	*/          
        public function data_inicial_crear_proyecto(){
            
            try{
                return json_encode(array(
                    'info_investigador_principal' => Usuario::mas_info_usuario(Input::get('id_usuario')),
                    'tipos_productos_generales' => TipoProductoGeneral::all(),
                    'productos_especificos_x_prod_general' => TipoProductoEspecifico::productos_especificos_x_prod_general(),
                    'tipos_identificacion' => TipoIdentificacion::all(),
                    'sedes' => SedeUCC::all(),
                    'grupos_investigacion_y_sedes' => GrupoInvestigacionUCC::get_grupos_investigacion_con_sedes(),
                    'facultades_dependencias' => FacultadDependenciaUCC::all(),
                    'categorias_investigador' => CategoriaInvestigador::all(),
                    'roles' => Rol::whereNotIn('id', array(1, 2, 3))->get(),
                    'entidades_fuente_presupuesto' => DB::table('entidades_fuente_presupuesto')->whereNotIn('nombre', array('UCC', 'CONADI'))->get(),
                    'consultado' => 1,
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
    	| registrar_nuevo_proyecto()
    	|--------------------------------------------------------------------------
    	| Crea los nuevos proyectos de investigación
    	| Usa las funciones de soporte: 
    	|
    	| registrar_informacion_general_proyecto()
        | registrar_participantes_proyecto()
        | registrar_productos_proyecto()
        | registrar_gastos_proyecto()
        | registrar_documentos_iniciales_proyecto()
        |
        | A su vez, cada función utiliza otras funciones de soporte
    	*/                  
        public function registrar_nuevo_proyecto(){
            
            // return '<pre>'.print_r(Input::all(), true).'</pre>';
            
            file_put_contents
            (
                app_path().'/logs.log', 
                "\r\n".print_r(Input::all(), true)
                ,FILE_APPEND
            );            
            
            try{
                DB::transaction(function()
                {
                    $data = Input::all();
                    // valida la identificación del investigador principal
                    $validacion = Validator::make(
                        array('identificacion' => $data['identificacion_investigador_principal']),
                        array('identificacion' => array('required', 'integer', 'min:0', 'max:99999999999', 'exists:personas,identificacion'))
                    );              
                    if($validacion->fails())
                        throw new Exception('Error en el registro de nuevo proyecto. Detalles: Identificación de investigador principal '.$data['identificacion_investigador_principal'].' inválida');
                    
                    // validación de identificación correcta
                    // se obtiene el usuario del investigador principal 
                    $usuario_investigador_principal = Usuario::usuario_investigador_desde_identificacion($data['identificacion_investigador_principal']);
                                                            
                    // se registra la información general del proyecto
                    $proyecto = $this->registrar_informacion_general_proyecto($data, $usuario_investigador_principal);
                    
                    // se registran los participantes o investigadores del proyecto
                    $this->registrar_participantes_proyecto($data, $proyecto->id, $usuario_investigador_principal);
                    
                    // se registra los productos del proyecto
                    $this->registrar_productos_proyecto($data, $proyecto->id);
                    
                    // se registran los gastos del proyecto
                    $this->registrar_gastos_proyecto($data, $proyecto->id);
                    
                    // se registran los documentos iniciales del proyecto, esto es:
                    // presupuesto, presentacion de proyecto, acta de inicio
                    $this->registrar_documentos_iniciales_proyecto($proyecto->id);
                }); 
                
                Session::flash('notify_operacion_previa', 'success');
                Session::flash('mensaje_operacion_previa', 'Proyecto de investigación registrado');
                return Redirect::to('/proyectos/listar');                                    
            }
            catch (\Exception $e){
                // aquí redirigir a listar proyectos con mensaje flash de error
                throw $e;
                Log::error($e);
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en el registro de nuevo proyecto. Detalles: '.$e->getMessage());
                return Redirect::to('/proyectos/listar');
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_informacion_general_proyecto()
    	|--------------------------------------------------------------------------
    	| Crea un nuevo registro de proyecto con su información general (los campos en la tabla proyectos)
    	| retorna el registro proyecto creado. Se basa en el usuario de investigador principal pasado como
    	| parámetro para asociar el grupo de investigación que tenga como grupo de investigación ejecutor
    	*/          
        private function registrar_informacion_general_proyecto($data, $usuario_investigador_principal){
            
            $validacion = Validator::make(
                array(
                    'codigo_fmi' => $data['codigo_fmi'],
                    'subcentro_costo' => $data['subcentro_costo'],
                    'nombre' => $data['nombre_proyecto'],
                    'fecha_inicio' => $data['fecha_inicio'],
                    'fecha_fin' => $data['fecha_final'],
                    'duracion_meses' => $data['duracion_meses'],
                    'convocatoria' => $data['convocatoria'],
                    'anio_convocatoria' => !is_null($data['convocatoria']) && !is_null($data['anio_convocatoria']) ? $data['anio_convocatoria'] : null,
                    'objetivo_general' => $data['objetivo_general'],
                    'cantidad_objetivos_especificos' => $data['cantidad_objetivos_especificos']
                    ),
                array(
                    'codigo_fmi' => array('required', 'min:2', 'max:50'),
                    'subcentro_costo' => array('required', 'min:2', 'max:50'),
                    'nombre' => array('required', 'min:5', 'max:200'),
                    'fecha_inicio' => array('date_format:Y-m-d'),
                    'fecha_fin' => array('date_format:Y-m-d'),
                    'duracion_meses' => array('integer', 'min:12'),
                    'convocatoria' => array('max:250'),
                    'anio_convocatoria' => array('integer'),
                    'objetivo_general' => array('required', 'min:5', 'max:200'),
                    'cantidad_objetivos_especificos' => array('required', 'min:1')
                    )
            );
            
            if($validacion->fails()){
                throw new Exception('Información general del proyecto inválida. Detalles: '.$validacion->messages());
            }
            
            // echo $usuario_investigador_principal->id_grupo_investigacion_ucc;
            $proyecto = Proyecto::create(array(
                'id_grupo_investigacion_ucc' => $usuario_investigador_principal->id_grupo_investigacion_ucc,
                'id_estado' => 1,
                'codigo_fmi' => $data['codigo_fmi'],
                'subcentro_costo' => $data['subcentro_costo'],
                'nombre' => $data['nombre_proyecto'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_final'],
                'duracion_meses' => $data['duracion_meses'],
                'convocatoria' => $data['convocatoria'],
                'anio_convocatoria' => !is_null($data['convocatoria']) && !is_null($data['anio_convocatoria']) ? $data['anio_convocatoria'] : null,
                'objetivo_general' => $data['objetivo_general']
                ));
                
            if(!isset($data['cantidad_objetivos_especificos']) || $data['cantidad_objetivos_especificos'] == 0)
                throw new Exception('Cantidad inválida de objetivos específicos');
            
            // se registran los objetivos específicos del proyecto
            for($i = 0; $i < $data['cantidad_objetivos_especificos']; $i++)
            {
                if(!isset($data['objetivo_especifico_'.$i]) || strlen($data['objetivo_especifico_'.$i]) < 5)
                    throw new Exception('Nombre de objetivo específico "'.$data['objetivo_especifico_'.$i].'" inválido');
                    
                ObjetivoEspecifico::create(array(
                    'nombre' => $data['objetivo_especifico_'.$i],
                    'id_proyecto' => $proyecto->id,
                    'id_estado' => 1
                    ));
            }
                
            return $proyecto;
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_participantes_proyecto()
    	|--------------------------------------------------------------------------
    	| Crea y asocia los investigadores de un nuevo proyecto
    	| Tanto el investigador principal como los participantes secundarios
    	*/                  
        private function registrar_participantes_proyecto($data, $id_proyecto, $usuario_investigador_principal){
            
            if($data['cantidad_participantes'] > 1) // si hay más participantes aparte del investigador principal
            {
                for($i = 1; $i < $data['cantidad_participantes']; $i++){
                    
                    // se consulta los datos básicos de la persona, validando primero la identificación
                    $identificacion = $data['identificacion_'.$i];
                    $validacion = Validator::make(
                        array('identificacion' => $identificacion),
                        array('identificacion' => array('required', 'integer', 'min:0', 'max:99999999999'))
                    );              
                    if($validacion->fails()){
                        throw new Exception('Identificación '.$identificacion.' inválida');
                    }
                    
                    // Identificación válida
                    // Se llama función de soporte para que cree el investigador
                    $persona = Persona::where('identificacion', '=', $identificacion)->first();
                    try{
                        $this->registrar_coinvestigador_proyecto($data, $id_proyecto, $persona, $i);
                    }
                    catch(\Exception $e){
                        throw $e ;
                    }
                } // fin for
                
                // se han registrado los participantes coinvestigadores, se crea registro de investigador para el investigador principal
                $persona_investigador_principal = Persona::find($usuario_investigador_principal->id_persona);
                $dedicacion_semanal = isset($data['gasto_personal_dedicacion_semanal_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_dedicacion_semanal_'.$persona_investigador_principal->identificacion] : 1;
                $total_semanas = isset($data['gasto_personal_total_semanas_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_total_semanas_'.$persona_investigador_principal->identificacion] : 1;
                $valor_hora = isset($data['gasto_personal_valor_hora_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_valor_hora_'.$persona_investigador_principal->identificacion] : 1;                            
                
                Investigador::create(array(
                    'id_usuario_investigador_principal' => $usuario_investigador_principal->id,
                    'id_rol' => 3,
                    'id_proyecto' => $id_proyecto,
                    'dedicacion_horas_semanales' => $dedicacion_semanal,
                    'total_semanas' => $total_semanas,
                    'valor_hora' => $valor_hora
                    ));
            }
            else
            {
                // solo el investigador principal participa en el proyecto
                $persona_investigador_principal = Persona::find($usuario_investigador_principal->id_persona);
                $dedicacion_semanal = isset($data['gasto_personal_dedicacion_semanal_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_dedicacion_semanal_'.$persona_investigador_principal->identificacion] : 1;
                $total_semanas = isset($data['gasto_personal_total_semanas_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_total_semanas_'.$persona_investigador_principal->identificacion] : 1;
                $valor_hora = isset($data['gasto_personal_valor_hora_'.$persona_investigador_principal->identificacion]) ? $data['gasto_personal_valor_hora_'.$persona_investigador_principal->identificacion] : 1;                            
                
                Investigador::create(array(
                    'id_usuario_investigador_principal' => $usuario_investigador_principal->id,
                    'id_rol' => 3,
                    'id_proyecto' => $id_proyecto,
                    'dedicacion_horas_semanales' => $dedicacion_semanal,
                    'total_semanas' => $total_semanas,
                    'valor_hora' => $valor_hora
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_coinvestigador_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de participantes de un nuevo proyecto
    	| Se encarga de la validación y registro de los datos de un coinvestigador ya sea externo, interno o estudiante
    	| Se crea nuevo registro de persona si el parámetro $persona es null
    	*/          
        private function registrar_coinvestigador_proyecto($data, $id_proyecto, $persona, $indice_datos_coinvestigador){
            
            $i = $indice_datos_coinvestigador;

            // se obtiene los campos específicos de todo tipo de coinvestigador para aplicar validación
            $id_sede = isset($data['sede_'.$i]) ? $data['sede_'.$i] : null;
            $id_grupo_investigacion = isset($data['grupo_investigacion_'.$i]) ? $data['grupo_investigacion_'.$i]: null;
            $id_facultad_dependencia = isset($data['facultad_dependencia_'.$i]) ? $data['facultad_dependencia_'.$i] : null;
            $entidad_externa = isset($data['entidad_externa_'.$i]) ? $data['entidad_externa_'.$i] : null;
            $programa_academico = isset($data['programa_academico_'.$i]) ? $data['programa_academico_'.$i] : null;
            $email = $data['email_'.$i];
            $id_rol = $data['id_rol_'.$i];            
            
            // se prepara validación
            $datos_a_validar = array('id_rol' => $id_rol, 'email' => $email);
            $reglas_de_validacion = array('id_rol' => array('required', 'integer', 'exists:roles,id'), 'email' => 'required|email');
            
            // Se añade a la validación los campos especificos del tipo de participante externo o estudiante
            if($id_rol == 4)
            {
                $datos_a_validar['id_grupo_investigacion'] = $id_grupo_investigacion;
                $reglas_de_validacion['id_grupo_investigacion'] = array('required', 'integer', 'exists:grupos_investigacion_ucc,id');
            }
            if($id_rol == 5) // investigador externo
            {
                $datos_a_validar['entidad_externa'] = $entidad_externa;
                $reglas_de_validacion['entidad_externa'] = array('required', 'min:5', 'max:200');
            }
            else if($id_rol == 6) // investigador externo
            {
                $datos_a_validar['entidad_externa'] = $entidad_externa;
                $datos_a_validar['programa_academico'] = $programa_academico;
                
                $reglas_de_validacion['entidad_externa'] = array('required', 'min:5', 'max:200');
                $reglas_de_validacion['programa_academico'] = array('required', 'min:5', 'max:200');
            }                          
            
            // se ejecuta validación de campos específicos de tipo de coinvestigador
            $validacion = Validator::make(
                $datos_a_validar,
                $reglas_de_validacion
            );              
            if($validacion->fails()){
                throw new Exception('Datos del participante '.$data['identificacion_'.$i].' inválidos. Detálles: '.$validacion->messages());
            }
            
            // Se obtiene los campos genéricos para todo tipo de coinveestigador, si no se encuentra, se establece como 1 su valor
            $identificacion = $data['identificacion_'.$i];
            $dedicacion_semanal = isset($data['gasto_personal_dedicacion_semanal_'.$identificacion]) ? $data['gasto_personal_dedicacion_semanal_'.$identificacion] : 1;
            $total_semanas = isset($data['gasto_personal_total_semanas_'.$identificacion]) ? $data['gasto_personal_total_semanas_'.$identificacion] : 1;
            $valor_hora = isset($data['gasto_personal_valor_hora_'.$identificacion]) ? $data['gasto_personal_valor_hora_'.$identificacion] : 1;            
            
            if($persona)
            {
                // existe la persona, solo se crea registro de investigador
                return Investigador::create(array(
                    'id_persona_coinvestigador' => $persona->id,
                    'id_grupo_investigacion_ucc' => $id_grupo_investigacion,
                    'id_rol' => $id_rol,
                    'id_proyecto' => $id_proyecto,
                    'entidad_o_grupo_investigacion' => $entidad_externa,
                    'programa_academico' => $programa_academico,
                    'dedicacion_horas_semanales' => $dedicacion_semanal,
                    'email' => $email,
                    'total_semanas' => $total_semanas,
                    'valor_hora' => $valor_hora
                    ));                    
            }
            else
            {
                // no existe la persona, se crea tanto persona como investigador
                // se abstraen los datos de la persona a crear en variables para mejor manipulacion
                $nombres = $data['nombres_'.$i];
                $apellidos = $data['apellidos_'.$i];
                $formacion = $data['formacion_'.$i];
                $edad = $data['edad_'.$i];
                $sexo = $data['sexo_'.$i];
                $id_tipo_identificacion = $data['tipo_identificacion_'.$i];
                
                // Se prepara validación, añadiendo solo los campos genéricos de toda persona mas el id del rol
                $datos_a_validar = array(
                        'nombres' => $nombres,
                        'apellidos' => $apellidos,
                        'formacion' => $formacion,
                        'tipo_identificacion' => $id_tipo_identificacion,
                        'edad' => $edad,
                        'sexo' => $sexo
                        );
                $reglas_de_validacion = array(
                        'nombres' => array('required', 'min:5', 'max:200'),
                        'apellidos' => array('required', 'min:5', 'max:200'),
                        'formacion' => array('required', 'in:Ph. D,Doctorado,Maestría,Especialización,Pregado'),
                        'tipo_identificacion' => array('required', 'integer', 'exists:tipos_identificacion,id'),
                        'edad' => array('required', 'integer', 'min:10', 'max:120'),
                        'sexo' => array('required', 'in:m,f')
                        );
                
                // se ejecuta la validación
                $validacion = Validator::make(
                    $datos_a_validar,
                    $reglas_de_validacion
                );              
                if($validacion->fails()){
                    throw new Exception('Datos del participante '.$identificacion.' inválidos');
                }
                
                // validación correcta, se crea persona
                $persona = Persona::create(array(
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'formacion' => $formacion,
                    'edad' => $edad,
                    'sexo' => $sexo,
                    'identificacion' => $identificacion,
                    'id_tipo_identificacion' => $id_tipo_identificacion,
                    'foto' => null
                    ));
                
                // crea investigador con el registro de la persona creada
                return Investigador::create(array(
                    'id_persona_coinvestigador' => $persona->id,
                    'id_grupo_investigacion_ucc' => $id_grupo_investigacion,
                    'id_rol' => $id_rol,
                    'id_proyecto' => $id_proyecto,
                    'entidad_o_grupo_investigacion' => $entidad_externa,
                    'programa_academico' => $programa_academico,
                    'email' => $email,
                    'dedicacion_horas_semanales' => $dedicacion_semanal,
                    'total_semanas' => $total_semanas,
                    'valor_hora' => $valor_hora
                    ));    
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_productos_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de productos de un nuevo proyecto
    	*/                  
        private function registrar_productos_proyecto($data, $id_proyecto){

            // se valida la cantidad de productos
            if(!isset($data['cantidad_productos']) || count($data['cantidad_productos']) == 0)
                throw new Exception('Cantidad inválida de productos');
                
            for($i = 0; $i < $data['cantidad_productos']; $i++){
                
                // valida los datos recibidos del cliente
                $validacion = Validator::make(
                    array(
                        'id_tipo_producto_general' => $data['id_tipo_producto_general_'.$i],
                        'id_tipo_producto_especifico' => $data['id_tipo_producto_especifico_'.$i],
                        'nombre' => $data['nombre_producto_'.$i],
                        'encargado_producto' => $data['encargado_producto_'.$i],
                        'fecha_proyectada_radicar' => $data['fecha_proyectada_radicar_'.$i],
                        'fecha_remision' => $data['fecha_remision_'.$i],
                        'fecha_confirmacion_editorial' => $data['fecha_confirmacion_editorial_'.$i],
                        'fecha_recepcion_evaluacion' => $data['fecha_recepcion_evaluacion_'.$i],
                        'fecha_respuesta_evaluacion' => $data['fecha_respuesta_evaluacion_'.$i],
                        'fecha_aprobacion_publicacion' => $data['fecha_aprobacion_publicacion_'.$i],
                        'fecha_publicacion' => $data['fecha_publicacion_'.$i]
                        ),
                    array(
                        'id_tipo_producto_general' => 'exists:tipos_productos_generales,id',
                        'id_tipo_producto_especifico' => 'exists:tipos_productos_especificos,id',
                        'nombre' => array('required', 'min:5'),
                        'encargado_producto' => 'exists:personas,identificacion',
                        'fecha_proyectada_radicar' => array('required', 'date_format:Y-m-d'),
                        'fecha_remision' => array('required', 'date_format:Y-m-d'),
                        'fecha_confirmacion_editorial' => array('required', 'date_format:Y-m-d'),
                        'fecha_recepcion_evaluacion' => array('required', 'date_format:Y-m-d'),
                        'fecha_respuesta_evaluacion' => array('required', 'date_format:Y-m-d'),
                        'fecha_aprobacion_publicacion' => array('required', 'date_format:Y-m-d'),
                        'fecha_publicacion' => array('required', 'date_format:Y-m-d')
                        )
                );              
                if($validacion->fails())
                    throw new Exception('Datos del prodcuto "'.$data['nombre_producto_'.$i].'" inválidos');
                
                
                // se aplica segunda validación a la fecha de publicación, para asegurarse de que sea mayor a la fecha proyectada de radicación
                $fecha_proyectada_radicacion = date_create_from_format('Y-m-d', $data['fecha_proyectada_radicar_'.$i]);
                $fecha_publicacion = date_create_from_format('Y-m-d', $data['fecha_publicacion_'.$i]);
                
                if($fecha_publicacion < $fecha_proyectada_radicacion)
                    throw new Exception('La fecha de publicación del producto "'.$data['nombre_producto_'.$i].'" es menor a la fecha proyectada de radicación');
                
                // validaciones correctas, se crea producto
                Producto::create(array(
                    'id_proyecto' => $id_proyecto,
                    'id_tipo_producto_especifico' => $data['id_tipo_producto_especifico_'.$i],
                    'id_investigador' => Investigador::get_investigador_por_identificacion($id_proyecto, $data['encargado_producto_'.$i])->id,
                    'id_estado' => 1,
                    'nombre' => $data['nombre_producto_'.$i],
                    'fecha_proyectada_radicacion' => $data['fecha_proyectada_radicar_'.$i],
                    'fecha_remision' => $data['fecha_remision_'.$i],
                    'fecha_confirmacion_editorial' => $data['fecha_confirmacion_editorial_'.$i],
                    'fecha_recepcion_evaluacion' => $data['fecha_recepcion_evaluacion_'.$i],
                    'fecha_respuesta_evaluacion' => $data['fecha_respuesta_evaluacion_'.$i],
                    'fecha_aprobacion_publicacion' => $data['fecha_aprobacion_publicacion_'.$i],
                    'fecha_publicacion' => $data['fecha_publicacion_'.$i]
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_productos_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de un nuevo proyecto
    	*/          
        private function registrar_gastos_proyecto($data, $id_proyecto){
            
            $this->registrar_gastos_personal($data, $id_proyecto);
            $this->registrar_gastos_equipos($data, $id_proyecto);
            $this->registrar_gastos_software($data, $id_proyecto);
            $this->registrar_gastos_salidas_campo($data, $id_proyecto);
            $this->registrar_gastos_materiales($data, $id_proyecto);
            $this->registrar_gastos_servicios_tecnicos($data, $id_proyecto);
            $this->registrar_gastos_bibliograficos($data, $id_proyecto);
            $this->registrar_gastos_recursos_digitales($data, $id_proyecto);
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_productos_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos del personal del proyecto
    	*/                 
        private function registrar_gastos_personal($data, $id_proyecto){
            
            // antes de comenzar el registro de los gastos se registran las nuevas entidades de presupuesto
            $this->crear_nuevas_entidades_presupuesto($data);
            
            // registra los gastos personales del investigador principal
            $this->registrar_gastos_personal_investigador_principal($data, $id_proyecto);
            
            // registra los gastos personales de los coinvestigadores
            $this->registrar_gastos_coinvestigadores($data, $id_proyecto);
            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_personal_investigador_principal()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos del personal
    	| del investigador principal de un nuevo proyecto
    	*/                
        private function registrar_gastos_personal_investigador_principal($data, $id_proyecto){
            
            $investigador_principal = Investigador::get_investigador_por_identificacion($id_proyecto, $data['identificacion_investigador_principal']);
            $detalle_gasto_investigador_principal = DetalleGasto::create(array(
                'fecha_ejecucion' => $data['gasto_personal_fecha_ejecucion_'.$investigador_principal->identificacion],
                'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Personal')->first()->id,
                'id_investigador' => $investigador_principal->id
                ));
            $presupuesto_ucc_investigador_principal = (int)$data['gasto_personal_presupuesto_ucc_'.$investigador_principal->identificacion];    
            // crea el gasto presupuestado por la ucc
            Gasto::create(array(
                'id_proyecto' => $id_proyecto,
                'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                'id_detalle_gasto' => $detalle_gasto_investigador_principal->id,
                'valor' => is_null($presupuesto_ucc_investigador_principal) ? 0 : $presupuesto_ucc_investigador_principal
                ));
                
            // obtiene los presupuestos dados por entidades externas para los gastos personales del investigador principal
            if(isset($data['nuevas_entidad_presupuesto'])){
                foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                    // Obtiene el presupuesto otorgado por la $nuevas_entidad_presupuesto
                    // teniendo en cuenta que dicho valor se encuentra en $data[] como:
                    // gasto_personal_presupuesto_externo_<id_nueva_entidad>_<identificacion_participante>
                    
                    // obtiene <id_nueva_entidad>
                	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                    $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                    $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);
                    
                    // abstrae el valor del presupuesto
                    if(isset($data['gasto_personal_presupuesto_externo_'.$id_nueva_entidad.'_'.$investigador_principal->identificacion]))
                        $presupuesto_nueva_entidad = (int)$data['gasto_personal_presupuesto_externo_'.$id_nueva_entidad.'_'.$investigador_principal->identificacion];
                    else
                        $presupuesto_nueva_entidad = 0;
                    
                    // obtiene el id de la BD que le corresponde a la nueva entidad de presupuesto
                    $id_bd_nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first()->id;
                    
                    // crea el gasto presupuestado por la nueva entidad para el investigador principal
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => $id_bd_nueva_entidad_presupuesto,
                        'id_detalle_gasto' => $detalle_gasto_investigador_principal->id,
                        'valor' => $presupuesto_nueva_entidad
                        ));
                } 
            }
            
            // registra los gastos presupuestados por la entidades existentes
            if(isset($data['entidad_presupuesto_existentes'])){
                foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                    
                    // desde el cliente se envía el id de la entidad
                    // se vuelve a consultar para confirmar su id enviado
                    $id_entidad_existente = EntidadFuentePresupuesto::find($entidad_existente)->id; 
                    
                    if(isset($data['gasto_personal_presupuesto_externo_'.$id_entidad_existente.'_'.$investigador_principal->identificacion])){
                        $presupuesto_entidad_existente = $data['gasto_personal_presupuesto_externo_'.$id_entidad_existente.'_'.$investigador_principal->identificacion];
                        $validacion = Validator::make(
                            array('valor' => (int)$presupuesto_entidad_existente),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $presupuesto_entidad_existente = 0;
                    }
                    else
                        $presupuesto_entidad_existente = 0;                        
                    
                    // crea el gasto presupuestado por la entidad existente 
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => $id_entidad_existente,
                        'id_detalle_gasto' => $detalle_gasto_investigador_principal->id,
                        'valor' => $presupuesto_entidad_existente
                        ));
                }
            }               
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_coinvestigadores()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos del personal
    	| de los coinvestigadores de un nuevo proyecto
    	*/          
        private function registrar_gastos_coinvestigadores($data, $id_proyecto){
            
            $id_entidad_ucc = EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id;
            for($i = 1; $i < $data['cantidad_participantes']; $i++){
                $investigador = Investigador::get_investigador_por_identificacion($id_proyecto, $data['identificacion_'.$i]);
                $detalle_gasto = DetalleGasto::create(array(
                    'fecha_ejecucion' => $data['gasto_personal_fecha_ejecucion_'.$investigador->identificacion],
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Personal')->first()->id,
                    'id_investigador' => $investigador->id
                    ));
                $presupuesto_ucc_investigador = $data['gasto_personal_presupuesto_ucc_'.$investigador->identificacion];
                // crea el gasto presupuestado por la ucc
                Gasto::create(array(
                    'id_proyecto' => $id_proyecto,
                    'id_entidad_fuente_presupuesto' => $id_entidad_ucc,
                    'id_detalle_gasto' => $detalle_gasto->id,
                    'valor' => is_null($presupuesto_ucc_investigador) ? 0 : ((int)$presupuesto_ucc_investigador)
                    ));
                // obtiene los presupuestos dados por entidades externas para los gastos personales del investigador
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        // Obtiene el presupuesto otorgado por la $nuevas_entidad_presupuesto
                        // teniendo en cuenta que dicho valor se encuentra en $data[] como:
                        // gasto_personal_presupuesto_externo_<id_nueva_entidad>_<identificacion_participante>
                        
                        // obtiene <id_nueva_entidad>
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);
                        
                        // abstrae el valor del presupuesto
                        if(isset($data['gasto_personal_presupuesto_externo_'.$id_nueva_entidad.'_'.$investigador->identificacion]))
                            $presupuesto_nueva_entidad = (int)$data['gasto_personal_presupuesto_externo_'.$id_nueva_entidad.'_'.$investigador->identificacion];
                        else
                            $presupuesto_nueva_entidad = 0;
                            
                        // obtiene el id de la BD que le corresponde a la nueva entidad de presupuesto
                        $id_bd_nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first()->id;
                        
                        // crea el gasto presupuestado por la nueva entidad para el investigador principal
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $id_bd_nueva_entidad_presupuesto,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $presupuesto_nueva_entidad
                            ));                        
                    }
                }
                // registra los gastos presupuestados por la entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){

                        // desde el cliente se envía el id de la entidad
                        // se vuelve a consultar para confirmar su id enviado
                        $id_entidad_existente = EntidadFuentePresupuesto::find($entidad_existente)->id; 
                        
                        if(isset($data['gasto_personal_presupuesto_externo_'.$id_entidad_existente.'_'.$investigador->identificacion])){
                            $presupuesto_entidad_existente = (int)$data['gasto_personal_presupuesto_externo_'.$id_entidad_existente.'_'.$investigador->identificacion];
                            $validacion = Validator::make(
                                array('valor' => $presupuesto_entidad_existente),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $presupuesto_entidad_existente = 0;
                        }
                        else
                            $presupuesto_entidad_existente = 0;                        
                        
                        // crea el gasto presupuestado por la entidad existente 
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $id_entidad_existente,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $presupuesto_entidad_existente
                            ));
                    }
                }                
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| crear_nuevas_entidades_presupuesto()
    	|--------------------------------------------------------------------------
    	| Función de soporte ára el registro de los gastos de un proyecto
    	| Crea posibles nuevas entidades fuente de presupuesto
    	*/                          
        private function crear_nuevas_entidades_presupuesto($data){
            // entidad_presupuesto_existentes
            // nuevas_entidad_presupuesto
            if(isset($data['nuevas_entidad_presupuesto'])){
                foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad){
                    // se abstrae el verdadero nombre de la entidad, teniendo en cuenta que lo que se envia viene de la siguiente forma:
                    // <id>_<nombre nueva entidad>
                    $indice_primer_guion_bajo = strpos($nueva_entidad, '_');
                    $nueva_entidad = substr($nueva_entidad, $indice_primer_guion_bajo + 1);
                    
                    // comprueba que el nombre de la nueva entidad realmente no exista en la BD
                    if(count(EntidadFuentePresupuesto::where('nombre', '=', $nueva_entidad)->get()) == 0)
                    {
                        // la entidad no existe, se crea registro
                        $nueva_entidad_fuente_presupuesto = new EntidadFuentePresupuesto();
                        $nueva_entidad_fuente_presupuesto->nombre = $nueva_entidad;
                        $nueva_entidad_fuente_presupuesto->save();
                    }
                    else
                    {
                        throw new Exception('No de pueden crear duplicados de nombres de entidades fuente de presupuesto. Nombre duplicado a crear: '.$nueva_entidad);
                    }
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_equipos()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de los equipos del proyecto
    	*/                                  
        private function registrar_gastos_equipos($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_equipos']) || isset($data['cantidad_gastos_equipos']) == 0)
                return; // sin gastos de equipos para este proyecto
            
            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_equipos']; $i++){
                
                $nombre_equipo = $data['gasto_equipo_nombre_'.$i];
                $justificacion = $data['gasto_equipo_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_equipo_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'nombre' => $nombre_equipo,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'nombre' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del gasto de equipo "'.$nombre_equipo.'" inválidos');
                    
                $detalle_gasto_equipo = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Equipos')->first()->id,
                    'concepto' => $nombre_equipo,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_equipo_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_equipo_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto_equipo->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_equipo_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_equipo_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto_equipo->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_equipo_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_equipo_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto_equipo->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_equipo_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_equipo_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto_equipo->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_software()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de de software del proyecto
    	*/             
        private function registrar_gastos_software($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_software']) || isset($data['cantidad_gastos_software']) == 0)
                return; // sin gastos de software para este proyecto
            
            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_software']; $i++){
                
                $concepto = $data['gasto_software_nombre_'.$i];
                $justificacion = $data['gasto_software_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_software_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'nombre' => $concepto,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'nombre' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del software "'.$concepto.'" inválidos');
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Software')->first()->id,
                    'concepto' => $concepto,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_software_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_software_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_software_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_software_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_software_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_software_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_software_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_software_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_salidas_campo()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de las salidas de campo
    	*/          
        private function registrar_gastos_salidas_campo($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_salidas']) || isset($data['cantidad_gastos_salidas']) == 0)
                return; // sin gastos de software para este proyecto
            
            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_salidas']; $i++){
                
                $justificacion = $data['gasto_salida_justificacion_'.$i];
                $cantidad_salidas = $data['gasto_salida_cantidad_salidas_'.$i];
                $valor_unitario = $data['gasto_salida_valor_unitario_'.$i];
                $fecha_ejecucion = $data['gasto_salida_fecha_ejecucion_'.$i];

                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'justificacion' => $justificacion,
                        'cantidad_salidas' => (int)$cantidad_salidas,
                        'valor_unitario' => (int)$valor_unitario,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'cantidad_salidas' => array('required', 'integer', 'min:0'),
                        'valor_unitario' => array('required', 'integer', 'min:0'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Uno o varios datos de las salidas de campo son inválidos. Detalles: '.$validacion->messages());
                // throw new Exception($validacion->messages());
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Salidas de campo')->first()->id,
                    'justificacion' => $justificacion,
                    'numero_salidas' => $cantidad_salidas,
                    'valor_unitario' => $valor_unitario,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_salida_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_salida_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_salida_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_salida_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_salida_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_salida_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_salida_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_salida_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_materiales()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de los materiales del proyecto
    	*/             
        private function registrar_gastos_materiales($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_materiales']) || isset($data['cantidad_gastos_materiales']) == 0)
                return; // sin gastos de materiales para este proyecto

            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_materiales']; $i++){
                
                $concepto = $data['gasto_material_nombre_'.$i];
                $justificacion = $data['gasto_material_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_material_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'concepto' => $concepto,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'concepto' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del gasto de material "'.$concepto.'" inválidos');
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Materiales y suministros')->first()->id,
                    'concepto' => $concepto,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_material_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_material_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_material_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_material_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_material_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_material_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_material_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_material_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_servicios_tecnicos()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de servicios técnicos del proyecto
    	*/             
        private function registrar_gastos_servicios_tecnicos($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_servicios_tecnicos']) || isset($data['cantidad_gastos_servicios_tecnicos']) == 0)
                return; // sin gastos de servicios técnicos para este proyecto

            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_servicios_tecnicos']; $i++){
                
                $concepto = $data['gasto_servicio_nombre_'.$i];
                $justificacion = $data['gasto_servicio_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_servicio_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'concepto' => $concepto,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'concepto' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del gasto de servicio técnico "'.$concepto.'" inválidos');
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Servicios técnicos')->first()->id,
                    'concepto' => $concepto,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_servicio_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_servicio_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_servicio_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_servicio_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_servicio_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_servicio_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_servicio_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_servicio_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_bibliograficos()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos bibliográficos del proyecto
    	*/             
        private function registrar_gastos_bibliograficos($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_bibliograficos']) || isset($data['cantidad_gastos_bibliograficos']) == 0)
                return; // sin gastos bibliográficos proyecto

            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_bibliograficos']; $i++){
                
                $concepto = $data['gasto_bibliografico_nombre_'.$i];
                $justificacion = $data['gasto_bibliografico_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_bibliografico_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'concepto' => $concepto,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'concepto' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del gasto bibliográfico "'.$concepto.'" inválidos');
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Recursos bibliográficos')->first()->id,
                    'concepto' => $concepto,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_bibliografico_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_bibliografico_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_bibliografico_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_bibliografico_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_bibliografico_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_bibliografico_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_bibliografico_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_bibliografico_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_gastos_recursos_digitales()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los gastos de recursos digitales del proyecto
    	*/             
        private function registrar_gastos_recursos_digitales($data, $id_proyecto){
            
            if(!isset($data['cantidad_gastos_digitales']) || isset($data['cantidad_gastos_digitales']) == 0)
                return; // sin gastos de recursos digitales para este proyecto

            // los pasos mas importantes son:
            // 1 obtiene los campos de la tabla detalles_gastos para una mejor manipulación
            // 2 se crea el registro de detalles_gasto
            // 3 se crea cada
            // itera por cada una da las entidades de presupuesto, tanto nuevas como existentes y crea los gastos
            for($i = 0; $i < $data['cantidad_gastos_digitales']; $i++){
                
                $concepto = $data['gasto_digital_nombre_'.$i];
                $justificacion = $data['gasto_digital_justificacion_'.$i];
                $fecha_ejecucion = $data['gasto_digital_fecha_ejecucion_'.$i];
                // aplica validacion a los campos del DetalleGasto
                $validacion = Validator::make(
                    array(
                        'concepto' => $concepto,
                        'justificacion' => $justificacion,
                        'fecha_ejecucion' => $fecha_ejecucion
                        ),
                    array(
                        'concepto' => array('required', 'min:5', 'max:250'),
                        'justificacion' => array('required', 'min:5', 'max:250'),
                        'fecha_ejecucion' => array('required', 'date_format:Y-m-d')
                        )
                    );
                if($validacion->fails())
                    throw new Exception('Datos del gasto de recurso digital "'.$concepto.'" inválidos');
                    
                $detalle_gasto = DetalleGasto::create(array(
                    'id_tipo_gasto' => TipoGasto::where('nombre', '=', 'Recursos educativos digitales')->first()->id,
                    'concepto' => $concepto,
                    'justificacion' => $justificacion,
                    'fecha_ejecucion' => $fecha_ejecucion
                    ));
                
                // registra los gastos presupuestados por UCC
                {
                    if(isset($data['gasto_digital_presupuesto_ucc_'.$i])){
                        $valor_ucc = (int)$data['gasto_digital_presupuesto_ucc_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_ucc),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_ucc = 0;
                    }
                    else
                        $valor_ucc = 0;
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_ucc
                        ));                    
                }
                
                // registra los gastos presupuestados por CONADI
                {
                    if(isset($data['gasto_digital_presupuesto_conadi_'.$i])){
                        $valor_conadi = (int)$data['gasto_digital_presupuesto_conadi_'.$i];
                        $validacion = Validator::make(
                            array('valor' => $valor_conadi),
                            array('valor' => array('required', 'integer', 'min:0'))
                            );
                        if($validacion->fails())
                            $valor_conadi = 0;
                    }
                    else
                        $valor_conadi = 0;                
                    Gasto::create(array(
                        'id_proyecto' => $id_proyecto,
                        'id_entidad_fuente_presupuesto' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id,
                        'id_detalle_gasto' => $detalle_gasto->id,
                        'valor' => $valor_conadi
                        ));                    
                }
                
                // registra los gastos de las nuevas entidades
                if(isset($data['nuevas_entidad_presupuesto'])){
                    foreach($data['nuevas_entidad_presupuesto'] as $nueva_entidad_presupuesto){
                        
                        // obtiene el id de la nueva entidad ya creada previamente, buscando por su nombre
                    	$indice_primer_guion_bajo = strpos($nueva_entidad_presupuesto, '_');
                        $id_nueva_entidad = substr($nueva_entidad_presupuesto, 0, $indice_primer_guion_bajo);
                        $nombre_nueva_entidad = substr($nueva_entidad_presupuesto, $indice_primer_guion_bajo + 1);                        
                        $nueva_entidad_presupuesto = EntidadFuentePresupuesto::where('nombre', '=', $nombre_nueva_entidad)->first();
                        
                        if(isset($data['gasto_digital_presupuesto_externo_'.$id_nueva_entidad.'_'.$i])){
                            $valor = (int)$data['gasto_digital_presupuesto_externo_'.$id_nueva_entidad.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;
                            
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $nueva_entidad_presupuesto->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }
                
                // registra los gastos de las entidades existentes
                if(isset($data['entidad_presupuesto_existentes'])){
                    foreach($data['entidad_presupuesto_existentes'] as $entidad_existente){
                        
                        // desde el cliente se envía el id de la entidad
                        $entidad_existente = EntidadFuentePresupuesto::find($entidad_existente); 
                        
                        if(isset($data['gasto_digital_presupuesto_externo_'.$entidad_existente->id.'_'.$i])){
                            $valor = (int)$data['gasto_digital_presupuesto_externo_'.$entidad_existente->id.'_'.$i];
                            $validacion = Validator::make(
                                array('valor' => $valor),
                                array('valor' => array('required', 'integer', 'min:0'))
                                );
                            if($validacion->fails())
                                $valor = 0;
                        }
                        else
                            $valor = 0;                        
                        
                        Gasto::create(array(
                            'id_proyecto' => $id_proyecto,
                            'id_entidad_fuente_presupuesto' => $entidad_existente->id,
                            'id_detalle_gasto' => $detalle_gasto->id,
                            'valor' => $valor
                            ));
                    }
                }                    
            }
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| registrar_documentos_iniciales_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte para el registro de los documentos iniciales del propyecto los cuales son:
    	| presupuesto, presentacion de proyecto, acta de inicio
    	| Específicamente se encarga de copiar al sistema de ficheros los documentos citados 
    	| y crear los registros DocumentoProyecto asociandos con el id_proyecto pasado como parámetro
    	| Obtiene los archivos de la fachada Input::file
    	*/                  
        private function registrar_documentos_iniciales_proyecto($id_proyecto){
            
            // primero se aplica validaciones a los archivos enviados. Esto es si existe y si no sobrepasa el tamaño permitido
            if(!Input::hasFile('presupuesto'))
                throw new Exception('Documento de presupuesto de proyecto inválido. No se cargo archivo');
                
            if(!Input::hasFile('presentacion_proyecto'))
                throw new Exception('Documento de presentación de proyecto de proyecto inválido. No se cargo archivo');
                
            if(!Input::hasFile('acta_inicio'))
                throw new Exception('Documento de acta de inicio de proyecto inválido. No se cargo archivo');   
                
            // crea registro DocumentoProyecto dedicado al documento de presupuesto
            $presupuesto = new DocumentoProyecto();
            $presupuesto->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Presupuesto')->first()->id;
            $presupuesto->id_proyecto = $id_proyecto;
            $presupuesto->save();
            
            // copia archivo de presupuesto al sistema de archivos
            $archivo_copiado = Archivo::copiar_presupuesto(Input::file("presupuesto"), $presupuesto->id);
            if($archivo_copiado){ // archivo copiado con éxito
                $presupuesto->archivo = $archivo_copiado->getFilename(); // actualiza registro DocumentoProyecto con el nombre del archivo copiado
                $presupuesto->save(); 
            }
            else
                throw new Exception('Error al copiar archivo de presupuesto de proyecto');            

            // crea registro DocumentoProyecto dedicado al documento de presentación de proyecto
            $presentacion_proyecto = new DocumentoProyecto();
            $presentacion_proyecto->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Presentacion proyecto')->first()->id;
            $presentacion_proyecto->id_proyecto = $id_proyecto;
            $presentacion_proyecto->save();
            
            // copia archivo de presentación de proyecto al sistema de archivos
            $archivo_copiado = Archivo::copiar_presentacion_proyecto(Input::file("presentacion_proyecto"), $presentacion_proyecto->id);
            if($archivo_copiado){ // archivo copiado con éxito
                $presentacion_proyecto->archivo = $archivo_copiado->getFilename(); // actualiza registro DocumentoProyecto con el nombre del archivo copiado
                $presentacion_proyecto->save(); 
            }
            else
                throw new Exception('Error al copiar archivo de presentación de proyecto');        
                
            // crea registro DocumentoProyecto dedicado al documento de acta de inicio
            $acta_inicio = new DocumentoProyecto();
            $acta_inicio->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Acta inicio')->first()->id;
            $acta_inicio->id_proyecto = $id_proyecto;
            $acta_inicio->save();
            
            // copia archivo de presentación de proyecto al sistema de archivos
            $archivo_copiado = Archivo::copiar_acta_inicio(Input::file("acta_inicio"), $acta_inicio->id);
            if($archivo_copiado){ // archivo copiado con éxito
                $acta_inicio->archivo = $archivo_copiado->getFilename(); // actualiza registro DocumentoProyecto con el nombre del archivo copiado
                $acta_inicio->save(); 
            }
            else
                throw new Exception('Error al copiar archivo de acta de inicio de proyecto'); 
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_proyectos_filtrados()
    	|--------------------------------------------------------------------------
    	| Retorno json con los proyectos filtrados por sede, facultad o grupo de investigación
    	| función usada en vista de indicadores de proyectos
    	*/          
        public function consultar_proyectos_filtrados(){
            
            try{
                
                $proyectos = Proyecto::proyectos_filtrados(Input::get('filtro', null), Input::get('id_sede', null), Input::get('id_facultad', null), Input::get('id_grupo_investigacion', null));
                return json_encode([
                    'consultado' => 1,
                    'proyectos' => $proyectos
                    ]);
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $g->getCode()
                    ]);
            }
            
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| EDITAR  /////////////////////////////////////////////////////////////////
    	|--------------------------------------------------------------------------
    	*/ 
    	
    	/*
    	|--------------------------------------------------------------------------
    	| editarVer()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de registro de nuevo proyecto para un investigador principal dado
    	*/        
        public function editarVer($pagina,$id){
            
            // provee estilos personalizados para la vista a cargar
            $styles = [
                'vendor/ngAnimate/ngAnimate.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'vendor/angular-ui/ui-select.css', 
                'vendor/angular-ui/overflow-ui-select.css',
                'vendor/perfect-scrollbar/css/perfect-scrollbar.min.css'
                ]; 
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',                
                'vendor/angular-ui/ui-select.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                'vendor/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'
                ];
            
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ngFileUpload', 'ui.bootstrap', 'ui.select'];
            
            // echo "pagina No: ".$pagina;
            // die();
            
        
            switch ($pagina) {
                case '1':
                    
                    //inf. general
                    
                    $post_scripts = [
                    'investigador/proyectos/editar/editar_document_ready_externo.js',
                    'investigador/proyectos/editar/editar_datos_basicos_controller.js',
                    ];

                    return View::make('investigador.proyectos.editar.general', array(
                    'styles' => $styles,
                    'pagina'=>$pagina,
                    'proyecto_id' => $id,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                    ));
                    
                    
                    break;
                case '2':
                    
                    //participantes
                    
                    $post_scripts = [
                    'investigador/proyectos/editar/editar_document_ready_externo.js',
                    'investigador/proyectos/editar/editar_datos_basicos_controller.js',
                    'investigador/proyectos/editar/editar_participantes_proyectos_controller.js'
                    ];
                    
                    return View::make('investigador.proyectos.editar.participantes', array(
                    'styles' => $styles,
                    'pagina'=>$pagina,
                    'proyecto_id' => $id,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                    ));
                    
                    break;
                case '3':
                    
                    // productos
                    $post_scripts = [
                    'investigador/proyectos/editar/editar_document_ready_externo.js',
                    'investigador/proyectos/editar/editar_datos_basicos_controller.js',
                    'investigador/proyectos/editar/editar_productos_proyectos_controller.js',
                    ];
                    
                    return View::make('investigador.proyectos.editar.productos', array(
                    'styles' => $styles,
                    'pagina'=>$pagina,
                    'proyecto_id' => $id,
                    'pre_scripts' => $pre_scripts,
                    'post_scripts' => $post_scripts,
                    'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                    ));
                    
                    break;
                case '4':
                    
                    // gastos
                    $post_scripts = [
                        'investigador/proyectos/editar/document_ready_gastos.js',
                        'investigador/proyectos/editar/editar_gastos_proyectos_controller.js',
                    ];
                    
                    return View::make('investigador.proyectos.editar.gastos', array(
                        'styles' => $styles,
                        'pagina'=>$pagina,
                        'proyecto_id' => $id,
                        'pre_scripts' => $pre_scripts,
                        'post_scripts' => $post_scripts,
                        'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                    ));
                    
                    
                    break;
                case '5':
                    
                    // adjuntos
                    $post_scripts = [
                        'investigador/proyectos/editar/editar_document_ready_externo.js',
                        'investigador/proyectos/editar/editar_datos_basicos_controller.js',
                        'investigador/proyectos/editar/adjuntos_proyecto_controller.js'
                    ];
                    
                    return View::make('investigador.proyectos.editar.adjuntos', array(
                        'styles' => $styles,
                        'pagina'=>$pagina,
                        'proyecto_id' => $id,
                        'pre_scripts' => $pre_scripts,
                        'post_scripts' => $post_scripts,
                        'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                    ));
                    
                    break;
                default:
                    // code...
                    
                     return "<h1 style='font-size:40px;'>Error 404.</h1><p><a href='/'><b>Ir a Home</b></a></p>";
                      
                    break;
            }
            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| datos_iniciales_editar_proyecto()
    	|--------------------------------------------------------------------------
    	| Función que trae los datos iniciales para editar proyecto
    	*/  
    	public function datos_iniciales_editar_proyecto(){
    	    
    	     try{
    	         
    	        $proyecto=Proyecto::find(Input::get('id_proyecto'));
    	        $gruposInvestigacion_sedes_facultades=SedeUCC::all();
    	        
    	        foreach ($gruposInvestigacion_sedes_facultades as $sede) {
    	            
    	            if($sede->facultad){
    	                foreach ($sede->facultad as $facultad) {
        	                foreach ($facultad->grupo as $grupo) {
        	                    if($grupo->facultad)
        	                        $grupo->facultad->sede;
        	                }
    	                }
    	            }
    	        }
    	        
    	        //para que se pueda ver y utilizar de debe instanciar 
    	        if($proyecto){
    	            
    	            $pagina=Input::get('pagina');
    	            $mas_info_usuario=null;
    	            $mas_info_productos=null;
    	            $mas_info_gasto=null;
    	            $temp=false;

    	            if(isset($pagina)){
    	                
    	                $proyecto->estado;
                        $proyecto->grupo;
                        $proyecto->objetivosEspecificos;
                        $proyecto->documentosProyectos;
                        $proyecto->investigadores;
                        
                        if($pagina == 2){
                            
                            foreach ($proyecto->investigadores as $investigador) {
                                if($investigador->id_persona_coinvestigador == null){
                                    
                                    //echo "///////////////////// ".$investigador->id_usuario_investigador_principal;
                                    $usuario =Usuario::mas_info_usuario($investigador->id_usuario_investigador_principal);
                                    //print_r($usuario);
                                    
                                    $mas_info_usuario []=array(
                                    'info_investigador' =>  $usuario,
                                    'resgitrado'=>1,
                                    'tiene_usuario'=>true,
                                    'resgitrado'=>true,
                                    'investigador_principarl'=>1,
                                    );
                                    
                                }
                                else{
                                     //echo $investigador->id_persona_coinvestigador."<br>";
                                    
                                     $persona=Persona::find($investigador->id_persona_coinvestigador);
                                     $persona->tipoIdentificacion;
                                     
                                     $investigador=Investigador::find($investigador->id);
                                     $investigador->rol;
                                     
                                     if($investigador->grupo != null){
                                          $investigador->grupo->facultad;
                                          $investigador->grupo->facultad->sede;
                                     }
                                     
                                     $usuario=Usuario::buscarUsuario($persona->id);
                                     
                                     if($persona->sexo == "m"){
                                         $sexo=array("id"=>"m","nombre"=>"Hombre");
                                     }else{
                                         $sexo=array("id"=>"f","nombre"=>"Mujer");
                                     }
                                     
                                     if(count($usuario) > 0){
                                         $mas_info_usuario []=array(
                                        'info_investigador' =>  $persona,
                                        'datos_extras'=>$investigador,
                                        "sexo"=>$sexo,
                                        'resgitrado'=>true,
                                        'tiene_usuario'=>true,
                                        'investigador_principarl'=>0,
                                        );
                                     }else{
                                         $mas_info_usuario []=array(
                                        'info_investigador' =>  $persona,
                                        'datos_extras'=>$investigador,
                                        "sexo"=>$sexo,
                                        'resgitrado'=>true,
                                        'tiene_usuario'=>false,
                                        'investigador_principarl'=>0,
                                        );
                                     }
                                    
                                }//fin else
                                
                            }//fin for each

                        }
                        else if($pagina == 3){
                            
                            // $proyecto->productos;
                            foreach ($proyecto->productos as $producto) {
                                
                                $producto->tipoProductoE;
                                $producto->tipoProductoE->tipoProductoG;
                                $producto->investigador;
                                
                                if($producto->investigador->usuario){
                                  $per=$producto->investigador->usuario;
                                  $producto->investigador->persona=$per->persona;
                                }
                                else{
                                  $producto->investigador->persona;
                                }
                               
                                
                                if($producto->id_estado == 1){
                                    $mas_info_productos[]= array(
                                    'producto' => $producto,
                                    'resgitrado'=>1,
                                    );
                                }
                                
                            } ////// fin del foreach 
                                
                            $temp=true;
                        }
                        else if($pagina == 4)
                        { ///// comineso de la pagina 4
                            
                            // $gastos=$proyecto->gastos;
                            
                            // foreach ($gastos as $item) {
                            
                            //     $item->entidadFuentePresupuesto;//llamar ala entidad fuente de presupuesto
                                
                            //     $item->detalleGasto;//llamar los detalle de gasto
                            //     $item->detalleGasto->tipoGasto;//llamarel tipo de gasto  que es el detalle de gasto
                            //     $item->detalleGasto->desembolso;//llamarel tipo de gasto  que es el detalle de gasto
                                
                            //     if($item->detalleGasto->investigador){
                            //         $item->detalleGasto->investigador->persona;//llamar los investigadores y la persona
                            //         $item->detalleGasto->investigador->rol;//llamar los investigadores y su rol
                            //     }
                                
                            // }//fin foreach para gastos
                            
                            
                            $temp=Gasto::consultasDetalleGasto($proyecto->id);
                            $gasto_equipos=null;
                            $gasto_software=null;
                            $gasto_salida=null;
                            $gasto_materiales_suministros=null;
                            $gasto_servicios=null;
                            $gasto_bibliografia=null;
                            $gasto_digitales=null;
                            
                            foreach ($temp as $item) {
                                
                                $aux=DetalleGasto::find ($item->id_detalle_gasto);
                                $aux->tipoGasto;//llamarel tipo de gasto  que es el detalle de gasto
                                $aux->desembolso;//llamarel tipo de gasto  que es el detalle de gasto
                                
                                $aux->gasto;//llamar los gsatos de cada detalle de gasto
                                foreach($aux->gasto as $temp){
                                    $temp->entidadFuentePresupuesto;
                                }
                                
                                if($aux->id_tipo_gasto == 1){ //Personal
                                    $gasto_personal[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 2){ //Equipos
                                    $gasto_equipos[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 3){ //Software
                                    $gasto_software[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 4){ //Salidas de campo
                                    $gasto_salida[]=$aux;
                                
                                }else if($aux->id_tipo_gasto == 5){ //Materiales y suministros
                                    $gasto_materiales_suministros[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 6){ //Servicios técnicos
                                    $gasto_servicios[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 7){ //Recursos bibliográficos
                                    $gasto_bibliografia[]=$aux;
                                    
                                }else if($aux->id_tipo_gasto == 8){ //Recursos educativos digitales
                                    $gasto_digitales[]=$aux;
                                    
                                }

                                if($aux->investigador){

                                    if($aux->investigador->id_persona_coinvestigador == null){
                                        $aux->investigador->rol;
                                        $aux->investigador->usuario->persona;
                                        
                                    }else{
                                        $aux->investigador->rol;
                                        $aux->investigador->persona;
                                    }
                                    
                                }
                                
                                $detalle_gasto[]=$aux;
                            }
                            
                            $mas_info_gasto= array(
                            //'gastos' => $gastos,
                            'detalle_gastos'=>$detalle_gasto,
                            'gastos_royecto'=>Gasto::consultar_gastos_proyecto($proyecto->id),
                            'resgitrado'=>1,
                            'todo'=> [
                                    'gastos_personal' => $gasto_personal,
                                    'gastos_equipos' => $gasto_equipos,
                                    'gastos_software' => $gasto_software,
                                    'gastos_salidas_campo' => $gasto_salida,
                                    'gastos_materiales' => $gasto_materiales_suministros,
                                    'gastos_servicios' => $gasto_servicios,
                                    'gastos_bibliograficos' => $gasto_bibliografia,
                                    'gastos_digitales' => $gasto_digitales,
                                    ],
                            );

                            $temp=true;
                        }
                        
                        /// esto es para la pagina 3 y 4
                        if($temp){
                            foreach ($proyecto->investigadores as $investigador) {
                             
                                 if($pagina == 3){
                                    if($investigador->id_persona_coinvestigador == null){
                                        
                                        //echo "///////////////////// ".$investigador->id_usuario_investigador_principal;
                                        $usuario =Usuario::find($investigador->id_usuario_investigador_principal);
                                        $persona=Persona::find($usuario->id_persona);
                                        
                                        $mas_info_usuario []=array(
                                        'mas_info_investigador'=>$investigador,
                                        'info_investigador' =>  $persona,
                                        'resgitrado'=>1,
                                        'investigador_principarl'=>1
                                        );
                                    }
                                    else{
                                         //echo $investigador->id_persona_coinvestigador."<br>";
                                        
                                         $persona=Persona::find($investigador->id_persona_coinvestigador);
 
                                         $mas_info_usuario []=array(
                                         'mas_info_investigador'=>$investigador,
                                         'info_investigador' =>  $persona,
                                         'resgitrado'=>1,
                                         'investigador_principarl'=>0,
                                         );
                                    }//fin else
                                 }
                            }//fin for each
                        }
	                }
    	        }
    	       
                return json_encode(array(
                    'info_investigador_principal' => Usuario::mas_info_usuario(Input::get('id_usuario')),
                    'tipos_productos_generales' => TipoProductoGeneral::all(),
                    'productos_especificos_x_prod_general' => TipoProductoEspecifico::productos_especificos_x_prod_general(),
                    'tipos_identificacion' => TipoIdentificacion::all(),
                    'sedes' => SedeUCC::all(),
                    'grupos_investigacion_y_sedes' => GrupoInvestigacionUCC::get_grupos_investigacion_con_sedes(),
                    'grupos_investigacion_y_sedes2' => $gruposInvestigacion_sedes_facultades,
                    'facultades_dependencias' => FacultadDependenciaUCC::all(),
                    'categorias_investigador' => CategoriaInvestigador::all(),
                    'roles' => Rol::whereNotIn('id', array(1, 2, 3))->get(),
                    'entidades_fuente_presupuesto' => DB::table('entidades_fuente_presupuesto')->whereNotIn('nombre', array('UCC', 'CONADI'))->get(),
                    'consultado' => 1,
                    'proyecto'=> $proyecto,
                    'info_investigadores_usuario'=>$mas_info_usuario,
                    'info_productos'=>$mas_info_productos,
                    'info_gastos'=>$mas_info_gasto,
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
    	
        
    }
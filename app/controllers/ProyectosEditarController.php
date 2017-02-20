<?php
    
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class ProyectosEditarController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| Brandon
    	|--------------------------------------------------------------------------
    	*/ 
        
        public function eliminarObjEspecifico(){
            
            $input=Input::all();
            
            
            $obj_especifico = ObjetivoEspecifico::find($input['id_objetivo_especifico']);
            $obj_especifico->id_estado=2;
            $obj_especifico->save();
         
            echo json_encode(array("resultado"=>"Se a eliminado el objetivo especifico "));
        }
        
        public function editarPartcipantes(){
            
            try{
            
            $data=Input::all();
            $proyecto=Proyecto::find($data['id_proyecto_participantes']);
 
            // print_r(Input::all());
            // die();
            
                if($proyecto){
                    
                    if(isset($data['investigador_id_viejo'])){
                    
                        for ($i = 0; $i < count($data['investigador_id_viejo']); $i++) {
                                
                                $investigador=Investigador::find($data['investigador_id_viejo'][$i]);
                                
                                $persona = Persona::find($investigador->id_persona_coinvestigador);
                                
        
                                /// verificacion del documento
                                if($persona->identificacion != $data['identificacion_viejo'][$i]){
                                    $verificar_identificacion= DB::table('personas')->where('identificacion', $data['identificacion_viejo'][$i])->get();
                                
                                    if($verificar_identificacion){//*
                                        
                                        Session::flash('notify_operacion_previa', 'error');
                                        Session::flash('mensaje_operacion_previa', 'la identificacion para la persona '.$persona->nombres.' '.$persona->apellidos.' se encuentra registrada');
                                        
                                        return Redirect::to('/proyectos/listar');
                                        
                                    }else{
                                        $persona->identificacion = $data['identificacion_viejo'][$i];
                                    }
                                    
                                }else{
                                    $persona->identificacion = $data['identificacion_viejo'][$i];
                                }
                                
                                    
                                $persona->nombres = $data['nombres_viejo'][$i];//*
                                $persona->apellidos = $data['apellidos_viejo'][$i];//*
                                $persona->sexo = $data['sexo_viejo'][$i];//*
                                $persona->edad = $data['edad_viejo'][$i];//*
                                $persona->formacion = $data['formacion_viejo'][$i];//*
                                $persona->id_tipo_identificacion = $data['tipo_identificacion_viejo'][$i];//*
                                
                                // if($persona->id_categoria_investigador == null)
                                //     $persona->id_categoria_investigador = $data['rol'] == 1 || $data['rol'] == 2 ? null : $data['categoria_investigador'];
                                $persona->save();
                              
                                $investigador->id_rol= $data['id_rol_viejo'][$i];//*
                                $investigador->email= $data['email_viejo'][$i];//*
                                
                                if($investigador->id_rol ==4 ){ //interno 
                                    
                                    $investigador->id_grupo_investigacion_ucc= isset($data['grupo_investigacion_viejo'][$i]) ? $data['grupo_investigacion_viejo'][$i] : $investigador->id_grupo_investigacion_ucc;
                                    $investigador->entidad_o_grupo_investigacion= isset($data['entidad_externa_viejo'][$i]) ? $data['entidad_externa_viejo'][$i] : $investigador->entidad_o_grupo_investigacion;
                                    
                                }else if($investigador->id_rol == 5 ){//externo
                                
                                    $investigador->entidad_o_grupo_investigacion= isset($data['entidad_externa_viejo'][$i]) ? $data['entidad_externa_viejo'][$i] : $investigador->entidad_o_grupo_investigacion;
                                    
                                }else if($investigador->id_rol ==6 ){ //estudiante
                                    
                                    $investigador->entidad_o_grupo_investigacion= isset($data['entidad_externa_viejo'][$i]) ? $data['entidad_externa_viejo'][$i] : $investigador->entidad_o_grupo_investigacion;
                                    $investigador->programa_academico= isset($data['programa_academico_viejo'][$i]) ? $data['programa_academico_viejo'][$i] : $investigador->programa_academico;
                                    
                                }
                                
                                
                                $investigador->save();
                                
                                
                        }/// fin del for
                        
                     //// fin del if   
                    }
                    
                    
                    if(isset($data['persona_id_nuevo'])){
                    
                        for ($i = 0; $i < count($data['persona_id_nuevo']); $i++) {
                            
                                
                                $id_persona=$data['persona_id_nuevo'][$i];
                                
                                if($id_persona != null){
                                    
                                    
                                    // echo "persona no vacia";
                                    // die();
                                    $persona = Persona::find($id_persona);
                                    
                                     /// verificacion del documento
                                    if($persona->identificacion != $data['identificacion_nuevo'][$i]){
                                        $verificar_identificacion= DB::table('personas')->where('identificacion', $data['identificacion_nuevo'][$i])->get();
                                    
                                        if($verificar_identificacion){
                                            
                                            Session::flash('notify_operacion_previa', 'error');
                                            Session::flash('mensaje_operacion_previa', 'la identificacion para la persona '.$persona->nombres.' '.$persona->apellidos.' se encuentra registrada');
                                            
                                            return Redirect::to('/proyectos/listar');
                                            
                                        }else{
                                            $persona->identificacion = $data['identificacion_nuevo'][$i];
                                        }
                                        
                                    }else{
                                        $persona->identificacion = $data['identificacion_nuevo'][$i];
                                    }
                                    
                                    
                                    $persona->nombres = $data['nombres_nuevo'][$i];
                                    $persona->apellidos = $data['apellidos_nuevo'][$i];
                                    $persona->sexo = $data['sexo_nuevo'][$i];
                                    $persona->edad = $data['edad_nuevo'][$i];
                                    $persona->formacion = $data['formacion_nuevo'][$i];
                                    $persona->id_tipo_identificacion = $data['tipo_identificacion_nuevo'][$i];
                                    
                                    $persona->save();
                                    

                                    Investigador::create(array(
                                    'id_persona_coinvestigador'=>$persona->id,
                                    'id_rol' => $data['id_rol_nuevo'][$i],
                                    'id_proyecto'=>$data['id_proyecto_participantes'],
                                    'email' => $data['email_nuevo'][$i],
                                    'id_grupo_investigacion_ucc' => isset($data['grupo_investigacion_nuevo'][$i]) ? $data['grupo_investigacion_nuevo'][$i] : null,
                                    'entidad_o_grupo_investigacion' => isset($data['entidad_externa_nuevo'][$i]) ? $data['entidad_externa_nuevo'][$i] : null,
                                    'programa_academico' => isset($data['programa_academico_nuevo'][$i]) ? $data['programa_academico_nuevo'][$i] : null
                                    ));
                                    
                                }else{
                                    
                                    
                                    
                                    /// verificacion del documento
                                    $verificar_identificacion= DB::table('personas')->where('identificacion', $data['identificacion_nuevo'][$i])->get();
                                
                                    if($verificar_identificacion){
                                        
                                        Session::flash('notify_operacion_previa', 'error');
                                        Session::flash('mensaje_operacion_previa', 'la identificacion para la persona '.$data['nombres_nuevo'][$i].' '.$data['apellidos_nuevo'][$i].' se encuentra registrada');
                                        
                                        return Redirect::to('/proyectos/listar');
                                        
                                    }
                                    
                                    // echo "persona  vacia";
                                    // die(); 
                                    
                                    $temp=Persona::create(array(
                                    'nombres' => $data['nombres_nuevo'][$i],
                                    'apellidos' => $data['apellidos_nuevo'][$i],
                                    'sexo' => isset($data['sexo_nuevo'][$i]) ? $data['sexo_nuevo'][$i] : null,
                                    'edad' => isset($data['edad_nuevo'][$i]) ? $data['edad_nuevo'][$i] : null,
                                    'formacion' => isset($data['formacion_nuevo'][$i]) ? $data['formacion_nuevo'][$i] : null,
                                    'id_tipo_identificacion' => isset($data['tipo_identificacion_nuevo'][$i]) ? $data['tipo_identificacion_nuevo'][$i] : null,
                                    'identificacion' => isset($data['identificacion_nuevo'][$i]) ? $data['identificacion_nuevo'][$i] : null,
                                    ));
                                    
                                   
                                    
                                    Investigador::create(array(
                                    'id_persona_coinvestigador'=>$temp->id,
                                    'id_rol' => $data['id_rol_nuevo'][$i],
                                    'id_proyecto'=>$data['id_proyecto_participantes'],
                                    'email' => $data['email_nuevo'][$i],
                                    'id_grupo_investigacion_ucc' => isset($data['grupo_investigacion_nuevo'][$i]) ? $data['grupo_investigacion_nuevo'][$i] : null,
                                    'entidad_o_grupo_investigacion' => isset($data['entidad_externa_nuevo'][$i]) ? $data['entidad_externa_nuevo'][$i] : null,
                                    'programa_academico' => isset($data['programa_academico_nuevo'][$i]) ? $data['programa_academico_nuevo'][$i] : null
                                    ));
                                    
                                }
                                
                                
                            
                                
                                
                        }/// fin del for
                     //// fin del if   
                    }
                    
                    
                    if(isset($data['persona_id_nuevo_existente'])){
                    
                        for ($i = 0; $i < count($data['persona_id_nuevo_existente']); $i++) {
                            
                                
                                $id_persona=$data['persona_id_nuevo_existente'][$i];
                                
                                if($id_persona != null){
                                    
                                    
                                    
                                    $persona = Persona::find($id_persona);
                                    
                                     /// verificacion del documento
                                    if($persona->identificacion != $data['identificacion_nuevo_existente'][$i]){
                                        $verificar_identificacion= DB::table('personas')->where('identificacion', $data['identificacion_nuevo_existente'][$i])->get();
                                    
                                        if($verificar_identificacion){
                                            
                                            Session::flash('notify_operacion_previa', 'error');
                                            Session::flash('mensaje_operacion_previa', 'la identificacion para la persona '.$persona->nombres.' '.$persona->apellidos.' se encuentra registrada');
                                            
                                            return Redirect::to('/proyectos/listar');
                                            
                                        }else{
                                            $persona->identificacion = $data['identificacion_nuevo_existente'][$i];
                                        }
                                        
                                    }else{
                                        $persona->identificacion = $data['identificacion_nuevo_existente'][$i];
                                    }
                                    
                                    
                                    $persona->nombres = $data['nombres_nuevo_existente'][$i];
                                    $persona->apellidos = $data['apellidos_nuevo_existente'][$i];
                                    $persona->sexo = $data['sexo_nuevo_existente'][$i];
                                    $persona->edad = $data['edad_nuevo_existente'][$i];
                                    $persona->formacion = $data['formacion_nuevo_existente'][$i];
                                    $persona->id_tipo_identificacion = $data['tipo_identificacion_nuevo_existente'][$i];
                                    
                                    $persona->save();
                                    
                                    

                                    Investigador::create(array(
                                    'id_persona_coinvestigador'=>$persona->id,
                                    'id_rol' => $data['id_rol_nuevo_existente'][$i],
                                    'id_proyecto'=>$data['id_proyecto_participantes'],
                                    'email' => $data['email_nuevo_existente'][$i],
                                    'id_grupo_investigacion_ucc' => isset($data['grupo_investigacion_nuevo_existente'][$i]) ? $data['grupo_investigacion_nuevo_existente'][$i] : null,
                                    'entidad_o_grupo_investigacion' => isset($data['entidad_externa_nuevo_existente'][$i]) ? $data['entidad_externa_nuevo_existente'][$i] : null,
                                    'programa_academico' => isset($data['programa_academico_nuevo_existente'][$i]) ? $data['programa_academico_nuevo_existente'][$i] : null
                                    ));
                                    
                                }else{
                                    
                                    
                                    /// verificacion del documento
                                    $verificar_identificacion= DB::table('personas')->where('identificacion', $data['identificacion_nuevo_existente'][$i])->get();
                                
                                    if($verificar_identificacion){
                                        
                                        Session::flash('notify_operacion_previa', 'error');
                                        Session::flash('mensaje_operacion_previa', 'la identificacion para la persona '.$data['nombres_nuevo_existente'][$i].' '.$data['apellidos_nuevo_existente'][$i].' se encuentra registrada');
                                        
                                        return Redirect::to('/proyectos/listar');
                                        
                                    }
                                    
                                    
                                
                                    
                                    $temp=Persona::create(array(
                                    'nombres' => $data['nombres_nuevo_existente'][$i],
                                    'apellidos' => $data['apellidos_nuevo_existente'][$i],
                                    'sexo' => isset($data['sexo_nuevo_existente'][$i]) ? $data['sexo_nuevo_existente'][$i] : null,
                                    'edad' => isset($data['edad_nuevo_existente'][$i]) ? $data['edad_nuevo_existente'][$i] : null,
                                    'formacion' => isset($data['formacion_nuevo_existente'][$i]) ? $data['formacion_nuevo_existente'][$i] : null,
                                    'id_tipo_identificacion' => isset($data['tipo_identificacion_nuevo_existente'][$i]) ? $data['tipo_identificacion_nuevo_existente'][$i] : null,
                                    'identificacion' => isset($data['identificacion_nuevo_existente'][$i]) ? $data['identificacion_nuevo_existente'][$i] : null
                                    ));
                                    
                                    

                                    
                                    Investigador::create(array(
                                    'id_persona_coinvestigador'=>$temp->id,
                                    'id_rol' => $data['id_rol_nuevo_existente'][$i],
                                    'id_proyecto'=>$data['id_proyecto_participantes'],
                                    'email' => $data['email_nuevo_existente'][$i],
                                    'id_grupo_investigacion_ucc' => isset($data['grupo_investigacion_nuevo_existente'][$i]) ? $data['grupo_investigacion_nuevo_existente'][$i] : null,
                                    'entidad_o_grupo_investigacion' => isset($data['entidad_externa_nuevo_existente'][$i]) ? $data['entidad_externa_nuevo_existente'][$i] : null,
                                    'programa_academico' => isset($data['programa_academico_nuevo_existente'][$i]) ? $data['programa_academico_nuevo_existente'][$i] : null
                                    ));
                                    
                                }
                                
                                
                            
                                
                                
                        }/// fin del for
                     //// fin del if   
                    }
                        
                    
                    
                    
                    
                    
                         Session::flash('notify_operacion_previa', 'success');
                         Session::flash('mensaje_operacion_previa', 'Proyecto  acrualizado');
                    
                }else
                {
                    Session::flash('notify_operacion_previa', 'error');
                    Session::flash('mensaje_operacion_previa', 'Error en la Actualizacion del proyecto : token_integridad');
                }
            
            
            }
            catch(Exception $e){                
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la Actualizacion del proyecto , código de error: '.$e->getCode().', detalle: '.$e->getMessage());
            }   
            return Redirect::to('/proyectos/listar');
            
        }
        
        public function eliminarPartcipantes(){
            $input=Input::all();
            
            $invesitgador=Investigador::find($input['id_investigador']);
            // $producto=DB::table('productos')->where('id_investigador', $input['id_investigador'])->get();
            
            $producto=$invesitgador->producto;
            
            if(empty($producto)){
                
                $invesitgador->id_estado=2;
                $invesitgador->save();
                
                echo  json_encode(array("error"=>false,"mensaje"=> "Se a eliminado el participante"));
            }else{
                echo json_encode(array("error"=>true,"mensaje"=>"El participante, tiene asigando un producto"));
            }
            
           
        }
        
        public function editarProductos(){
            //   print_r(Input::all());
            //   die();
          try
            {
                $data = Input::all();
                
                DB::transaction(function () use($data)
                {      
                    
                    
                    for ($i = 0; $i < count($data['id_producto']); $i++) {
                    
                        if($data['id_producto'][$i]  != null){
                            
                            $producto=Producto::find($data['id_producto'][$i]);
                            
                            $persona = DB::table('personas')->where('id', $data['encargado_producto'][$i])->first();
                            // $persona = DB::table('usuarios')->where('id_persona', $data['encargado_producto'][$i])->first();
                            $investigador = Investigador::get_investigador_por_identificacion($data['id_proyecto_productos'],$persona->identificacion);
                            
                            // print_r($investigador);
                            // // echo $persona->identificacion;
                            // echo $investigador->id;
                            // die();
                            
                            $producto->id_investigador=$investigador->id;
                            $producto->nombre=$data['nombre_producto'][$i];
                            $producto->fecha_proyectada_radicacion=$data['fecha_proyectada_radicar'][$i];
                            $producto->fecha_remision=$data['fecha_remision'][$i];
                            $producto->fecha_confirmacion_editorial=$data['fecha_confirmacion_editorial'][$i];
                            $producto->fecha_recepcion_evaluacion=$data['fecha_recepcion_evaluacion'][$i];
                            $producto->fecha_respuesta_evaluacion=$data['fecha_respuesta_evaluacion'][$i];
                            $producto->fecha_aprobacion_publicacion=$data['fecha_aprobacion_publicacion'][$i];
                            $producto->fecha_publicacion=$data['fecha_publicacion'][$i];
                            
                            $producto->save();                            
                            
                        }else {
                            
                            $persona = DB::table('personas')->where('id', $data['encargado_producto'][$i])->first();
                            // $persona = DB::table('usuarios')->where('id_persona', $data['encargado_producto'][$i])->first();
                            $investigador = Investigador::get_investigador_por_identificacion($data['id_proyecto_productos'],$persona->identificacion);
                            
                            
                            Producto::create(array(
                                'id_proyecto'=>$data['id_proyecto_productos'],
                                'id_tipo_producto_especifico'=>$data['id_tipo_producto_especifico'][$i],
                                'id_investigador'=>$investigador->id,
                                'id_estado'=>1,
                                'nombre'=>$data['nombre_producto'][$i],
                                'fecha_proyectada_radicacion'=>$data['fecha_proyectada_radicar'][$i],
                                'fecha_remision'=>$data['fecha_remision'][$i],
                                'fecha_confirmacion_editorial'=>$data['fecha_confirmacion_editorial'][$i],
                                'fecha_recepcion_evaluacion'=>$data['fecha_recepcion_evaluacion'][$i],
                                'fecha_respuesta_evaluacion'=>$data['fecha_respuesta_evaluacion'][$i],
                                'fecha_aprobacion_publicacion'=>$data['fecha_aprobacion_publicacion'][$i],
                                'fecha_publicacion'=>$data['fecha_publicacion'][$i]
                            ));
                            
                        }
                        
                    }
                    
                    Session::flash('notify_operacion_previa', 'success');
                    Session::flash('mensaje_operacion_previa', 'Proyecto  acrualizado');
                  
                });
            }
            catch(Exception $e)
            {                
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la edición del proyecto , código de error: '.$e->getCode().', detalle: '.$e->getMessage());
            }   
            return Redirect::to('/proyectos/listar'); 
        }
        
        public function eliminarProducto(){
            $input=Input::all();

            $Producto=Producto::find($input['id_producto']);
            $postulaciones=DB::table('postulaciones_publicaciones')->where('id_producto', $input['id_producto'])->get();
            
            // print_r($postulaciones);
            // die();
            
            if(empty($postulaciones)){
                
                $Producto->id_estado=2;
                $Producto->save();
                
                echo  json_encode(array("error"=>false,"mensaje"=> "Se a eliminado el producto"));
            }else{
                echo json_encode(array("error"=>true,"mensaje"=>"El Porducto, tiene asigando una postulacion"));
            }
            
           
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| Walter
    	|--------------------------------------------------------------------------
    	*/       
    	
    	/*
    	|--------------------------------------------------------------------------
    	| get_datos_basicos()
    	|--------------------------------------------------------------------------
    	| Consulta los datos básicos del proyecto
    	*/                 
        public function get_datos_basicos(){
            
            try
            {
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido. No se ha enviado tal dato');
                    
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']);
                    
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido. No se encuentra registros con tal identificador');
                    
                $proyecto = Proyecto::find(Input::get('id_proyecto'));
                $objetivos_especificos = ObjetivoEspecifico::where('id_proyecto', '=', Input::get('id_proyecto'))->select('id', 'nombre')->get();
                
                return json_encode([
                    'consultado' => 1,
                    'proyecto' => $proyecto,
                    'objetivos_especificos' => $objetivos_especificos
                    ]);
            }
            catch(\Exception $e)
            {
                return json_encode([
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage(),
                    ]);
            }
        }    	
        
    	/*
    	|--------------------------------------------------------------------------
    	| post_datos_basicos()
    	|--------------------------------------------------------------------------
    	| Punto de recepción de formulario de edición de datos básicos o información general del proyecto
    	*/          
        public function post_datos_basicos(){
            
            try
            {
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido. No se ha enviado tal dato');
                    
                $validacion = Validator::make(['id_proyecto' => Input::get('id_proyecto')], ['id_proyecto' => 'required|exists:proyectos,id']);
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido. No se encuentra proyecto con tal identificador');                
                
                $data = Input::all();
                
                // valida datos enviados
                $validacion = Validator::make(
                    array(
                        'codigo_fmi' => $data['codigo_fmi'],
                        'subcentro_costo' => $data['subcentro_costo'],
                        'nombre' => $data['nombre_proyecto'],
                        'fecha_inicio' => $data['fecha_inicio'],
                        'fecha_fin' => $data['fecha_final'],
                        'duracion_meses' => $data['duracion_meses'],
                        'convocatoria' => $data['convocatoria'],
                        'anio_convocatoria' => $data['anio_convocatoria'],
                        'objetivo_general' => $data['objetivo_general'],
                        ),
                    array(
                        'codigo_fmi' => array('required', 'min:2', 'max:50'),
                        'subcentro_costo' => array('required', 'min:2', 'max:50'),
                        'nombre' => array('required', 'min:5', 'max:200'),
                        'fecha_inicio' => array('date_format:Y-m-d'),
                        'fecha_fin' => array('date_format:Y-m-d'),
                        'duracion_meses' => array('integer', 'min:12'),
                        'convocatoria' => array('min:5','max:200'),
                        'anio_convocatoria' => array('integer'),
                        'objetivo_general' => array('required', 'min:5', 'max:200'),
                        )
                );
                
                if($validacion->fails()){
                    throw new Exception('Información general del proyecto inválida. Detalles: '.$validacion->messages());
                }                
                
                DB::transaction(function () use($data)
                {
                    $proyecto = Proyecto::find($data['id_proyecto']);
                    $proyecto->codigo_fmi = $data['codigo_fmi'];
                    $proyecto->subcentro_costo = $data['subcentro_costo'];
                    $proyecto->nombre = $data['nombre_proyecto'];
                    $proyecto->fecha_inicio = $data['fecha_inicio'];
                    $proyecto->duracion_meses = $data['duracion_meses'];
                    $proyecto->fecha_fin = $data['fecha_final'];
                    $proyecto->convocatoria = $data['convocatoria'];
                    $proyecto->anio_convocatoria = $data['anio_convocatoria'];
                    $proyecto->objetivo_general = $data['objetivo_general'];
                    $proyecto->save();
                    
                    if(isset($data['objetivo_especifico_viejo'])){
                        $cont = 0;
                        foreach($data['objetivo_especifico_viejo'] as $value){
                            $obj_especifico = ObjetivoEspecifico::find($data['id_especifico_viejo'][$cont]);
                            $obj_especifico->nombre=$value;
                            $obj_especifico->save();
                            $cont++;
                        }   
                    }
                    if(isset($data['objetivo_especifico_nuevo'])){
                        foreach($data['objetivo_especifico_nuevo'] as $value){
                            $obj_especifico=array(
                                'id_proyecto' => $data['id_proyecto'],
                                'id_estado' => 1,
                                'nombre' => $value);
                            ObjetivoEspecifico::create($obj_especifico);
                        }
                    }
                    if(isset($data['objetivos_especificos_existentes_a_eliminar'])){
                        foreach($data['objetivos_especificos_existentes_a_eliminar'] as $obj_especifico_a_eliminar){
                            $obj_especifico_a_eliminar = ObjetivoEspecifico::find($obj_especifico_a_eliminar);
                            $obj_especifico_a_eliminar->delete();
                        }
                    }
                    
                    Session::flash('notify_operacion_previa', 'success');
                    Session::flash('mensaje_operacion_previa', 'Información general del proyecto editada');                    
                    
                });
            }
            catch(\Exception $e){
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la edición del proyecto , código de error: '.$e->getCode().', detalle: '.$e->getMessage());                
            }
            
            return Redirect::to('/proyectos/listar');
        }
    	
    	/*
    	|--------------------------------------------------------------------------
    	| get_gastos_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los gastos de un determinado proyecto de investigación
    	*/                
        public function get_gastos_proyecto(){
            
            try{
                
                // valida identificador de proeycto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido. No se ha enviado tal dato');
                $validacion = Validator::make(['id_proyecto' => Input::get('id_proyecto')], ['id_proyecto' => 'required|exists:proyectos,id']);
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido. No se encuentra proyecto con tal identificador');
                    
                // consulta los gasto del proyecto
                $gastos_proyecto = Gasto::consultar_gastos_proyecto(Input::get('id_proyecto'));
                
                // consulta las entidades fuente de presupuesto que patrocinan los gastos del proyecto
                $entidades_fuente_presupuesto_proyecto = EntidadFuentePresupuesto::entidades_fuente_presupuesto_proyecto(Input::get('id_proyecto'));
                
                // consulta todas las entidades para alimentar el multiselect de entidades
                $todas_las_entidades_fuente_pres = EntidadFuentePresupuesto::whereNotIn('nombre', ['UCC', 'CONADI'])->select('id', 'nombre')->orderBy('id')->get();
                
                return json_encode([
                    'consultado' => 1,
                    'gastos' => $gastos_proyecto,
                    'entidades_fuente_presupuesto_proyecto' => $entidades_fuente_presupuesto_proyecto,
                    'todas_las_entidades_fuente_presupuesto' => $todas_las_entidades_fuente_pres,
                    'id_entidad_fuente_presupuesto_ucc' => EntidadFuentePresupuesto::where('nombre', '=', 'UCC')->first()->id,
                    'id_entidad_fuente_presupuesto_conadi' => EntidadFuentePresupuesto::where('nombre', '=', 'CONADI')->first()->id
                    ]);
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getCode(),
                    'codigo' => $e->getCode()
                    ]);
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| post_gastos_proyecto()
    	|--------------------------------------------------------------------------
    	| Punto de llegada de envío de formulario de ediciones de gastos de un proyecto de investigación
    	| Reliza las ediciones de los registros de los gastos de un determinado proyecto
    	*/          
        public function post_gastos_proyecto(){
            file_put_contents
            (
                app_path().'/logs.log', 
                "\r\n".print_r(Input::all(), true)
                ,FILE_APPEND
            );
            return 'recibido';
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| get_participantes_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los participantes de un determinado proyecto de investigación
    	*/          
        public function get_participantes_proyecto(){
            try
            {
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido. No se ha enviado tal dato');
                    
                $validacion = Validator::make(['id_proyecto' => Input::get('id_proyecto')], ['id_proyecto' => 'required|exists:proyectos,id']);
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido. No se encuentra proyecto con tal identificador');                                            
                    
                $investigadores = [];
                $source_investigadores = Investigador::where('id_proyecto' ,'=', Input::get('id_proyecto'))->orderBy('id_usuario_investigador_principal', 'desc')->get(); 
                
                /*
                No se puede eliminar participante existente si:
                    1.Su gasto tiene desembolso
                    2.Está encargado de un producto
                No se pueden editar sus datos básicos si:
                    1.La persona tiene usuario
                */                
                foreach($source_investigadores as $investigador)
                {
                    if($investigador->id_usuario_investigador_principal != null) // se trata del investigador ppal
                    {
                        $usuario_inv_ppal = Usuario::find($investigador->id_usuario_investigador_principal);
                        $persona = Persona::find($usuario_inv_ppal->id_persona);
                        $detalle_gasto_personal = DetalleGasto::where('id_investigador', '=', $investigador->id)->first();
                        $tiene_desembolso = Desembolso::where('id_detalle_gasto', '=', $detalle_gasto_personal->id)->first() != null ? 1 : 0;
                        $es_encargado_de_algun_producto = Producto::where('id_investigador', '=', $investigador->id)->first() != null ? 1 : 0;
                        array_push($investigadores, [
                            'id_persona' => $persona->id,
                            'id_investigador' => $investigador->id,
                            'es_investigador_principal' => 1,
                            'tiene_desembolso' => $tiene_desembolso,
                            'es_encargado_de_algun_producto' => $es_encargado_de_algun_producto,
                            'id_usuario_investigador_principal' => $usuario_inv_ppal->id,
                            'id_rol' => $investigador->id_rol,
                            'nombre_rol' => Rol::find($investigador->id_rol)->nombre,
                            'nombres' => $persona->nombres,
                            'apellidos' => $persona->apellidos,
                            'identificacion' => $persona->identificacion,
                            'id_tipo_identificacion' => $persona->id_tipo_identificacion,
                            'edad' => $persona->edad,
                            'sexo' => $persona->sexo,
                            'formacion' => $persona->formacion,
                            'email' => $usuario_inv_ppal->email,
                            'id_grupo_investigacion_ucc' => $usuario_inv_ppal->id_grupo_investigacion_ucc,
                            'dedicacion_horas_semanales' => $investigador->dedicacion_horas_semanales,
                            'total_semanas' => $investigador->total_semanas,
                            'valor_hora' => $investigador->valor_hora,
                            'fecha_ejecucion' => $detalle_gasto_personal->fecha_ejecucion
                            ]);
                    }
                    else
                    {
                        // se trata de un coinvestigador
                        // averigua si el coinvestigador tiene usuarios
                        $persona = Persona::find($investigador->id_persona_coinvestigador);
                        
                        $tiene_usuario = 0;
                        if(count(Usuario::buscarUsuario($investigador->id_persona_coinvestigador)) > 0)
                            $tiene_usuario = 1;
                        $detalle_gasto_personal = DetalleGasto::where('id_investigador', '=', $investigador->id)->first();
                        $tiene_desembolso = Desembolso::where('id_detalle_gasto', '=', $detalle_gasto_personal->id)->first() != null ? 1 : 0;
                        $es_encargado_de_algun_producto = Producto::where('id_investigador', '=', $investigador->id)->first() != null ? 1 : 0;
                        
                        $nuevo_investigador = [
                            'id_persona' => $persona->id,
                            'id_investigador' => $investigador->id,
                            'es_investigador_principal' => 0,
                            'tiene_desembolso' => $tiene_desembolso,
                            'es_encargado_de_algun_producto' => $es_encargado_de_algun_producto,                            
                            'id_rol' => $investigador->id_rol,
                            'nombre_rol' => Rol::find($investigador->id_rol)->nombre,
                            'tiene_usuario' => $tiene_usuario,
                            'nombres' => $persona->nombres,
                            'apellidos' => $persona->apellidos,
                            'identificacion' => $persona->identificacion,
                            'id_tipo_identificacion' => $persona->id_tipo_identificacion,
                            'edad' => $persona->edad,
                            'sexo' => $persona->sexo,
                            'formacion' => $persona->formacion,
                            'email' => $investigador->email,
                            'dedicacion_horas_semanales' => $investigador->dedicacion_horas_semanales,
                            'total_semanas' => $investigador->total_semanas,
                            'valor_hora' => $investigador->valor_hora,
                            'fecha_ejecucion' => $detalle_gasto_personal->fecha_ejecucion
                            ];
                            
                        if($investigador->id_rol == 4)
                            $nuevo_investigador['id_grupo_investigacion_ucc'] = $investigador->id_grupo_investigacion_ucc;
                        else if($investigador->id_rol == 5)
                        {
                            $nuevo_investigador['entidad_o_grupo_investigacion'] = $investigador->entidad_o_grupo_investigacion;
                        }
                        else if($investigador->id_rol == 6)
                        {
                            $nuevo_investigador['entidad_o_grupo_investigacion'] = $investigador->entidad_o_grupo_investigacion;
                            $nuevo_investigador['programa_academico'] = $investigador->programa_academico;
                        }
                        
                        array_push($investigadores, $nuevo_investigador);                        
                    }
                }
                
                $tipos_identificacion = [];
                foreach(TipoIdentificacion::all() as $ti)
                    $tipos_identificacion[$ti->id] = ['id' => $ti->id, 'nombre' => $ti->nombre, 'acronimo' => $ti->acronimo];
                
                return json_encode([
                    'consultado' => 1,
                    'investigadores' => $investigadores,
                    'tipos_identificacion' => TipoIdentificacion::all(),
                    'grupos_investigacion_x_sedes' => GrupoInvestigacionUCC::get_grupos_investigacion_con_sedes(),
                    'grupos_investigacion_ucc' => GrupoInvestigacionUCC::all(),
                    'facultades_ucc' => FacultadDependenciaUCC::all(),
                    'sedes_ucc' => SedeUCC::all(),
                    ]);
            }
            catch(\Exception $e){
                throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getCode(),
                    'codigo' => $e->getCode()
                    ]);
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| post_participantes_proyecto()
    	|--------------------------------------------------------------------------
    	| Punto de llegada de envío de formulario de ediciones de los participantes de un proyecto de investigación
    	| Utiliza las siguientes funciones de soporte:
    	| -validar_identificaciones_repetidas()
    	| -eliminar_participantes()
    	| -editar_participantes_existentes()
    	| -crear_participantes_nuevos()
    	| -obtener_datos_persona()
    	*/          
        public function post_participantes_proyecto(){
            
            // file_put_contents
            // (
            //     app_path().'/logs.log', 
            //     "\r\n".print_r(Input::all(), true)
            //     ,FILE_APPEND
            // );            
            try
            {
                $data = Input::all();
                
                DB::transaction(function() use($data)
                {
                    // valida que no se halla enviado identificaciones repetidas
                    $this->validar_identificaciones_repetidas($data);
                    
                    // elimina participantes / investigadores
                    $this->eliminar_participantes($data);
                    
                    // editar, actualizar o modificar participantes existentes
                    $this->editar_participantes_existentes($data);
                    
                    // crea los participantes nuevos y los asocia al proyecto de investigacion
                    $this->crear_participantes_nuevos($data);
                });
                
                // return 'Participantes editados';
                Session::flash('notify_operacion_previa', 'success');
                Session::flash('mensaje_operacion_previa', 'Participantes de proyecto editados');                
            }
            catch(\Exception $e)
            {
                throw $e;
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error al editar los datos de los participantes del proyecto. Detalles: '.$e->getMessage());
            }
            return Redirect::to('/proyectos/listar');
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| validar_identificaciones_repetidas()
    	|--------------------------------------------------------------------------
    	| valida las identificaciones de los participantes enviados
        | valida que no hallan identificaciones repetidas
    	*/            
        private function validar_identificaciones_repetidas($data){
        	$llaves_data = array_keys($data);
        	$llaves_identificaciones_nuevos_participantes = preg_grep('/identificacion_nuevo_\d+/', $llaves_data);
        	$llaves_identificaciones_participantes_existentes = preg_grep('/identificacion_\d+_\d+/', $llaves_data);
        	$identificaciones = [];
        	$identificaciones[] = $data['identificacion_investigador_principal'];
        	foreach($llaves_identificaciones_nuevos_participantes as $llave)
                $identificaciones[] = $data[$llave];
        	foreach($llaves_identificaciones_participantes_existentes as $llave)
                $identificaciones[] = $data[$llave];                 
            for ($i = 0; $i < count($identificaciones); $i++) {
                for ($ii = 0; $ii < count($identificaciones); $ii++) {
                    if($ii == $i) continue;
                    if($identificaciones[$i] == $identificaciones[$ii])
                        throw new Exception('Error al guardar modificaciones de los datos de participantes de proyecto. La identificacion '.$identificaciones[$i].' está repetida');
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| eliminar_participantes()
    	|--------------------------------------------------------------------------
    	| Elimina los investigadores que se hallan eliminado
    	| estableciendo el campo deleted_at (softdeleted o eliminacion lógica)
    	*/                    
        private function eliminar_participantes($data){
            if(!isset($data['participantes_a_eliminar']))
                return;
            foreach($data['participantes_a_eliminar'] as $investigador_a_eliminar)
            {
                $investigador = Investigador::find($investigador_a_eliminar);
                if($investigador)
                {
                    // valida que el investigador no tenga productos a cargo y que su gasto de personal no cuente con desembolso ya cargado
                    $es_encargado_de_algun_producto = Producto::where('id_investigador', '=', $investigador->id)->first() != null ? true : false;                    
                    $detalle_gasto_personal = DetalleGasto::where('id_investigador', '=', $investigador->id)->first();
                    $tiene_desembolso = Desembolso::where('id_detalle_gasto', '=', $detalle_gasto_personal->id)->first() != null ? true : false;
                    if(!$es_encargado_de_algun_producto && !$tiene_desembolso)
                        $investigador->delete();
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| editar_participantes_existentes()
    	|--------------------------------------------------------------------------
    	| Edita los datos de los participantes existentes del proyecto
    	*/                       
        private function editar_participantes_existentes($data){
        	$llaves_data = array_keys($data);
            $llaves_identificaciones = preg_grep('/identificacion_\d+_\d+/', $llaves_data);
            foreach($llaves_identificaciones as $llave_identificacion)
            {
                // abstrae el id_investigador teniendo en cuenta que la llave de la identificacion se conforma como:
                // identificacion_<id_investigador>_<index coleccion investigadores>
                $explode_result = explode('_', $llave_identificacion);
                // el segundo elemento del resultado de explode contendrá el id_investigador
                $id_investigador = $explode_result[1];
                $index = $explode_result[2];
                
                // consulta el registro investigador
                $investigador = Investigador::find($id_investigador);
                
                // si la identificacion que desea cambiar no existe se crea la persona. 
                // Si existe se consulta si hay usuario asociado a esa identificacion, en cuyo caso no se editan los datos simplememtne se asocia el id_persona al investigador
                $identificacion = $data[$llave_identificacion];
                $persona = Persona::where('identificacion', '=', $identificacion)->first();
                if($persona)
                {
                    // hay persona
                    // consulta que halla usuario asociado a dicha persona
                    $hay_usuario = Usuario::where('id_persona', '=', $persona->id)->first() == null ? false : true;
                    if($hay_usuario)
                    {
                        // simplemente se asocia el id de la persona con el registro del investigador, actualizando solo los campos correspondientes del investigador
                        $investigador->id_persona_coinvestigador = $persona->id;
                        if($datos_participante['rol'] == 4)
                            $investigador->id_grupo_investigacion_ucc = $datos_participante['grupo_inv'];
                        if($datos_participante['rol'] == 5)
                            $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                        else if($datos_participante['rol'] == 6)
                        {
                            $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                            $investigador->programa_academico = $datos_participante['programa_academico'];
                        }
                        $investigador->email = $datos_participante['email'];
                        $investigador->id_rol = $datos_participante['rol'];
                        $investigador->save();
                    }
                    else
                    {
                        // no hay usuario, se editan los datos de la persona
                        // se obtiene los datos de la persona
                        $datos_participante = $this->obtener_datos_persona($data, true, $id_investigador, $index);
                        $persona = Persona::find($persona->id);
                        $persona->nombres = $datos_participante['nombres'];
                        $persona->apellidos = $datos_participante['apellidos'];
                        $persona->formacion = $datos_participante['formacion'];
                        $persona->sexo = $datos_participante['sexo'];
                        $persona->edad = $datos_participante['edad'];
                        $persona->id_tipo_identificacion = $datos_participante['tipo_id'];
                        $persona->save();
                        
                        $investigador->id_persona_coinvestigador = $persona->id;
                        if($datos_participante['rol'] == 4)
                            $investigador->id_grupo_investigacion_ucc = $datos_participante['grupo_inv'];
                        if($datos_participante['rol'] == 5)
                            $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                        else if($datos_participante['rol'] == 6)
                        {
                            $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                            $investigador->programa_academico = $datos_participante['programa_academico'];
                        }
                        $investigador->email = $datos_participante['email'];
                        $investigador->id_rol = $datos_participante['rol'];
                        $investigador->save();
                    }
                }
                else
                {
                    // crea la persona y se la asocia al registro del investigador
                    $persona = new Persona();
                    $persona->identificacion = $datos_participante['identificacion'];
                    $persona->nombres = $datos_participante['nombres'];
                    $persona->apellidos = $datos_participante['apellidos'];
                    $persona->formacion = $datos_participante['formacion'];
                    $persona->sexo = $datos_participante['sexo'];
                    $persona->edad = $datos_participante['edad'];
                    $persona->id_tipo_identificacion = $datos_participante['tipo_id'];
                    $persona->save();
                    
                    $investigador->id_persona_coinvestigador = $persona->id;
                    if($datos_participante['rol'] == 4)
                        $investigador->id_grupo_investigacion_ucc = $datos_participante['grupo_inv'];
                    if($datos_participante['rol'] == 5)
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                    else if($datos_participante['rol'] == 6)
                    {
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                        $investigador->programa_academico = $datos_participante['programa_academico'];
                    }
                    $investigador->email = $datos_participante['email'];
                    $investigador->id_rol = $datos_participante['rol'];
                    $investigador->save();                    
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| crear_participantes_nuevos()
    	|--------------------------------------------------------------------------
    	| Crea participantes nuevos y los asocia al proyecto de investigacion
    	*/          
        private function crear_participantes_nuevos($data){
        	$llaves_data = array_keys($data);
            $llaves_identificaciones = preg_grep('/identificacion_nuevo_\d+/', $llaves_data);
            foreach($llaves_identificaciones as $llave_identificacion)
            {
                
                // abstrae el id_investigador teniendo en cuenta que la llave de la identificacion se conforma como:
                // identificacion_nuevo_<index coleccion investigadores>
                $explode_result = explode('_', $llave_identificacion);
                $index = $explode_result[2];
                $identificacion = $data[$llave_identificacion];
                $datos_participante = $this->obtener_datos_persona($data, false, 'nuevo', $index);
                
                // consulta si existe persona con la identificacion
                $investigador = null; // se usará mas adelante para asociale el detalle gasto
                $persona = Persona::where('identificacion', '=', $identificacion)->first();
                if($persona)
                {
                    // se asocia la persona con el nuevo registro de investigador a crear
                    $investigador = new Investigador();
                    $investigador->id_persona_coinvestigador = $persona->id;
                    if($datos_participante['rol'] == 4)
                        $investigador->id_grupo_investigacion_ucc = $datos_participante['grupo_inv'];
                    else if($datos_participante['rol'] == 5)
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                    else if($datos_participante['rol'] == 6)
                    {
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                        $investigador->programa_academico = $datos_participante['programa_academico'];
                    }
                    $investigador->email = $datos_participante['email'];
                    $investigador->id_rol = $datos_participante['rol'];
                    $investigador->id_proyecto = $data['id_proyecto'];
                    $investigador->dedicacion_horas_semanales = 0;
                    $investigador->total_semanas = 0;
                    $investigador->valor_hora = 0;
                    $investigador->save();
                }
                else
                {
                    // crea la persona
                    $persona = new Persona();
                    $persona->identificacion = $datos_participante['identificacion'];
                    $persona->nombres = $datos_participante['nombres'];
                    $persona->apellidos = $datos_participante['apellidos'];
                    $persona->formacion = $datos_participante['formacion'];
                    $persona->sexo = $datos_participante['sexo'];
                    $persona->edad = $datos_participante['edad'];
                    $persona->id_tipo_identificacion = $datos_participante['tipo_id'];
                    $persona->save();
                    
                    $investigador = new Investigador();
                    $investigador->id_persona_coinvestigador = $persona->id;                    
                    if($datos_participante['rol'] == 4)
                        $investigador->id_grupo_investigacion_ucc = $datos_participante['grupo_inv'];
                    if($datos_participante['rol'] == 5)
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                    else if($datos_participante['rol'] == 6)
                    {
                        $investigador->entidad_o_grupo_investigacion = $datos_participante['entidad_externa'];
                        $investigador->programa_academico = $datos_participante['programa_academico'];
                    }
                    $investigador->email = $datos_participante['email'];
                    $investigador->id_rol = $datos_participante['rol'];
                    $investigador->id_proyecto = $data['id_proyecto'];
                    $investigador->dedicacion_horas_semanales = 0;
                    $investigador->total_semanas = 0;
                    $investigador->valor_hora = 0;
                    $investigador->save();    
                }
                
                // se crea detalle gasto personal del nuevo participante
                $detalle_gasto = new DetalleGasto();
                $detalle_gasto->id_tipo_gasto = TipoGasto::where('nombre', '=', 'Personal')->first()->id;
                $detalle_gasto->id_investigador = $investigador->id;      
                $detalle_gasto->fecha_ejecucion = substr($datos_participante['fecha_ejecucion'], 1, 10);
                $detalle_gasto->save();
                
                $entidades_fuente_presupuesto_proyecto = EntidadFuentePresupuesto::entidades_fuente_presupuesto_proyecto($data['id_proyecto']);
                foreach($entidades_fuente_presupuesto_proyecto['entidades'] as $entidad){
                    if($entidad->nombre_entidad_fuente_presupuesto != 'CONADI')
                    {
                        $gasto = new Gasto();
                        $gasto->id_proyecto = $data['id_proyecto'];
                        $gasto->id_entidad_fuente_presupuesto = $entidad->id_entidad_fuente_presupuesto;
                        $gasto->id_detalle_gasto = $detalle_gasto->id;
                        $gasto->valor = 0;
                        $gasto->save();
                    }
                }
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| obtener_datos_persona()
    	|--------------------------------------------------------------------------
    	| Abstrae de los datos enviados desde el formulario de edición de participantes
    	| los datos concenrnientes a un determinado participante
    	*/            
        private function obtener_datos_persona($data, $es_participante_existente, $id_investigador, $index){
            $datos_participante = []; // contendra los datos recuperados
            if($es_participante_existente)
            {
                $datos_participante['identificacion'] = $data['identificacion_'.$id_investigador.'_'.$index];
                $datos_participante['nombres'] = $data['nombres_'.$id_investigador.'_'.$index];
                $datos_participante['apellidos'] = $data['apellidos_'.$id_investigador.'_'.$index];
                $datos_participante['formacion'] = $data['formacion_'.$id_investigador.'_'.$index];
                $datos_participante['rol'] = $data['rol_'.$id_investigador.'_'.$index];
                $datos_participante['sexo'] = $data['sexo_'.$id_investigador.'_'.$index];
                $datos_participante['edad'] = $data['edad_'.$id_investigador.'_'.$index];
                $datos_participante['email'] = $data['email_'.$id_investigador.'_'.$index];
                $datos_participante['tipo_id'] = $data['tipo_id_'.$id_investigador.'_'.$index];
                if($datos_participante['rol'] == 4)
                    $datos_participante['grupo_inv'] = $data['grupo_inv_'.$id_investigador.'_'.$index];
                else if($datos_participante['rol'] == 5)
                    $datos_participante['entidad_externa'] = $data['entidad_externa_'.$id_investigador.'_'.$index];
                else if($datos_participante['rol'] == 6)
                {
                    $datos_participante['entidad_externa'] = $data['entidad_externa_'.$id_investigador.'_'.$index];
                    $datos_participante['programa_academico'] = $data['programa_academico_'.$id_investigador.'_'.$index];
                }
            }
            else
            {
                $datos_participante['identificacion'] = $data['identificacion_nuevo_'.$index];
                $datos_participante['nombres'] = $data['nombres_nuevo_'.$index];
                $datos_participante['apellidos'] = $data['apellidos_nuevo_'.$index];
                $datos_participante['formacion'] = $data['formacion_nuevo_'.$index];
                $datos_participante['rol'] = $data['rol_nuevo_'.$index];
                $datos_participante['sexo'] = $data['sexo_nuevo_'.$index];
                $datos_participante['edad'] = $data['edad_nuevo_'.$index];
                $datos_participante['email'] = $data['email_nuevo_'.$index];
                $datos_participante['tipo_id'] = $data['tipo_id_nuevo_'.$index];
                if($datos_participante['rol'] == 4)
                    $datos_participante['grupo_inv'] = $data['grupo_inv_nuevo_'.$index];
                else if($datos_participante['rol'] == 5)
                    $datos_participante['entidad_externa'] = $data['entidad_externa_nuevo_'.$index];
                else if($datos_participante['rol'] == 6)
                {
                    $datos_participante['entidad_externa'] = $data['entidad_externa_nuevo_'.$index];
                    $datos_participante['programa_academico'] = $data['programa_academico_nuevo_'.$index];
                }           
                $datos_participante['fecha_ejecucion'] = $data['fecha_ejecucion_nuevo_'.$index];
            }
            return $datos_participante;
        }
        
    }
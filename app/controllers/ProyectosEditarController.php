<?php
    
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class ProyectosEditarController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| editarGeneral()
    	|--------------------------------------------------------------------------
    	| edita los cambios del proyecto  lso datos generales
    	*/        
        public function editarGeneral(){   
            
            try
            {
                $data = Input::all();
                
                DB::transaction(function () use($data)
                {
                    $proyecto = Proyecto::find($data['id_proyecto_general']);
                    
                    if($proyecto){
                        
                        $proyecto->codigo_fmi=$data['codigo_fmi'];
                        $proyecto->subcentro_costo=$data['subcentro_costo'];
                        $proyecto->nombre=$data['nombre_proyecto'];
                        $proyecto->fecha_inicio=$data['fecha_inicio'];
                        $proyecto->duracion_meses=$data['duracion_meses'];
                        $proyecto->fecha_fin=$data['fecha_final'];
                        $proyecto->convocatoria=$data['convocatoria'];
                        $proyecto->anio_convocatoria=$data['anio_convocatoria'];
                        $proyecto->objetivo_general=$data['objetivo_general'];
                        
                        $proyecto->save();
                        
                        if(isset($data['objetivo_especifico_viejo'])){
                            $cont=0;
                            foreach($data['objetivo_especifico_viejo'] as $value){
                                $obj_especifico = ObjetivoEspecifico::find($data['obj_especifico_viejo'][$cont]);
                                $obj_especifico->nombre=$value;
                                $obj_especifico->save();
                                $cont++;
                            }   
                        }
                        if(isset($data['objetivo_especifico_nuevo'])){
                        
                            foreach($data['objetivo_especifico_nuevo'] as $value){
                                
                                $obj_especifico=array(
                                    'id_proyecto' => $data['id_proyecto_general'],
                                    'id_estado' => 1,
                                    'nombre' => $value);
                                
                                ObjetivoEspecifico::create($obj_especifico);
                            }
                        }
                        if(isset($data['objetivos_especificos_existentes_a_eliminar'])){
                            foreach($data['objetivos_especificos_existentes_a_eliminar'] as $obj_especifico_a_eliminar){
                                $obj_especifico_a_eliminar = ObjetivoEspecifico::find($obj_especifico_a_eliminar);
                                $obj_especifico_a_eliminar->id_estado = 2;
                                $obj_especifico_a_eliminar-> save();
                            }
                        }
                        
                        Session::flash('notify_operacion_previa', 'success');
                        Session::flash('mensaje_operacion_previa', 'Información general del proyecto editada');
                    }
                    else
                    {
                        throw new Exception('identificador de proyecto no recibido');
                    }
                });
            }
            catch(Exception $e)
            {                
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error en la edición del proyecto , código de error: '.$e->getCode().', detalle: '.$e->getMessage());
            }   
            return Redirect::to('/proyectos/listar');   
        }
        
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
        
    }
<?php
    
    class TareasProgramadasController extends Controller {
        
        /*
        Tarea programas para:
        alerta email para los investigadores principales, administradores y coordinadores sobre la carga de archivos de:
        - Producto fecha a postular
        - Producto fecha a publicar
        - Desembolso de cualquier tipo de gasto
        - Informe de avance
        - Final de proyecto
        - Todas las alertas se generaran con 20 días de anticipancion
        */
        public function tarea_programada(){
            try{
                $proyectos = Proyecto::all();
                
                // prepara la fecha a comprar, sumando 20 días a la fecha actual
            	$fecha_actual_mas_20_dias = date_create_from_format('Y-m-d', date('Y-m-d'));
            	$fecha_actual_mas_20_dias->modify('+20 days');
            	$fecha_actual_mas_20_dias = $fecha_actual_mas_20_dias->format('Y-m-d');                                    
            	
            	// consulta todos los administradores, será utilizado más adelante cada administrador enviarles a ellos las alertas tambn
            	$administradores = Usuario::administradores();
            	
            	foreach($proyectos as $proyecto){
            	    
                    // consulta el investigador principal del proyecto y los coordinadores del proyecto, adjuntando la información de su persona
                    $investigador_principal = Investigador::investigador_principal_proyecto($proyecto->id);
                    $coordinadores_grupo_inv_ejecutor = GrupoInvestigacionUCC::coordinadores_grupo_investigacion($proyecto->id_grupo_investigacion_ucc);
                        
                    // ejecuta alertas de las fechas de los prodcutos del proyecto
                    $this->alertas_fechas_productos
                        ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias);
                    
                    // ejecuta alertas relacionadas con las fechas de ejecución de cada detalle gast del proyecto
                    $this->alertas_desembolsos
                        ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias);
                    
                    // ejecuta alertas relacionadas con la fecha de mitad de proyecto
                    $this->alertas_informes_avances
                        ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias);
                        
                    $this->alertas_final_proyecto
                        ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias);
            	}
            }
            catch(\Exception $e){
                // throw $e;
                Log::error($e);                
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| alertas_fechas_productos()
    	|--------------------------------------------------------------------------
    	| Evía email de alerta relacionado con la fecha proyectada de radicación y de publicacción de los productos
    	*/                          
        private function alertas_fechas_productos
            ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias)
        {
            // debe consultar todos los productos de todos los proyectos, consultar sus fechas proyectadas de radicación
            // restar 20 dias a tal fecha y si la fecha resultado es la misma de hoy, entonces enviar alerta por email al
            // investigador principal del proyecto, al administrador (o todos los usuarios administradores) y a los usuarios
            // coordinadores cuyo grupo de investigación sea el mismo grupo ejecutor del proyecto abordado
            // esto aplica a los productos que no hallan cargado sus publicaciones            
            
            
            $productos_proyecto = Producto::where('id_proyecto', '=', $proyecto->id)->get();            
            
            // itera por cada uno de los productos de un proyecto para generar la alerta de sus fechas citadas
            foreach($productos_proyecto as $producto){
                        
                // compara si la fecha actual +20 es igual a la fecha proyectada de radicación
                if($fecha_actual_mas_20_dias == $producto->fecha_proyectada_radicacion)
                {
                    // consulta si el producto tiene publicacion relacionada con la fecha ya cargado
                    $postulacion_publicacion = PostulacionPublicacion::where('id_producto', '=', $producto->id)
                        ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Proyectado')->first()->id)->first();
                    if($postulacion_publicacion)
                        continue; // ya tiene, se cancela alerta
                    else
                    {
                        // envia los emails a todos los tipos de usuario, validando que su email sea válido
                        // envía email de alerta al investigador principal
                        $validacion_email = Validator::make(['email' => $investigador_principal->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails())
                            Mail::send('emails.alertas.productos.fecha_proyectada_radicacion', 
                            array('destinatario' => $investigador_principal, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'investigador'), 
                            function($message) use ($investigador_principal)
                            {
                                $message->to($investigador_principal->email)->subject('Alerta de carga de producto - fecha proyectada radicación');
                            });
                        
                        // envía email de alerta a los coordinadores del grupo de investigación ejecutor del proyecto de inv
                        foreach($coordinadores_grupo_inv_ejecutor as $coordinador){
                            
                            $validacion_email = Validator::make(['email' => $coordinador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                                Mail::send('emails.alertas.productos.fecha_proyectada_radicacion', 
                                array('destinatario' => $coordinador, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'coordinador'), 
                                function($message) use ($coordinador)
                                {
                                    $message->to($coordinador->email)->subject('Alerta de carga de producto - fecha proyectada radicación');
                                });                                                            
                        
                        }
                        
                        // envía email de alerta a los investigadores del sistema
                        foreach($administradores as $administrador){
                            
                            $validacion_email = Validator::make(['email' => $administrador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                                Mail::send('emails.alertas.productos.fecha_proyectada_radicacion', 
                                array('destinatario' => $administrador, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'administrador'), 
                                function($message) use ($administrador)
                                {
                                    $message->to($administrador->email)->subject('Alerta de carga de producto - fecha proyectada radicación');
                                });                                                            
                        }                                
                    }
                }
                
                // compara si la fecha actual +20 es igual a la fecha de publicación
                if($fecha_actual_mas_20_dias == $producto->fecha_publicacion)
                {
                    // consulta si el producto tiene publicacion relacionada con la fecha ya cargado
                    $postulacion_publicacion = PostulacionPublicacion::where('id_producto', '=', $producto->id)
                        ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Publicado')->first()->id)->first();                            
                    if($postulacion_publicacion)
                        continue; // ya tiene, se cancela alerta                                
                    else
                    {
                        // envia los emails a todos los tipos de usuario, validando que su email sea válido
                        // envía email de alerta al investigador principal
                        $validacion_email = Validator::make(['email' => $investigador_principal->email], ['email' =>'required|email']);
                        
                        if(!$validacion_email->fails()) // validacion correcta
                            Mail::send('emails.alertas.productos.fecha_publicacion', 
                            array('destinatario' => $investigador_principal, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'investigador'), 
                            function($message) use ($investigador_principal)
                            {
                                $message->to($investigador_principal->email)->subject('Alerta de carga de producto - fecha de publicación');
                            });                                
                            
                        // envía email de alerta a los coordinadores del grupo de investigación ejecutor del proyecto de inv
                        foreach($coordinadores_grupo_inv_ejecutor as $coordinador){
                            
                            $validacion_email = Validator::make(['email' => $coordinador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                                Mail::send('emails.alertas.productos.fecha_publicacion', 
                                array('destinatario' => $coordinador, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'coordinador'), 
                                function($message) use ($coordinador)
                                {
                                    $message->to($coordinador->email)->subject('Alerta de carga de producto - fecha proyectada radicación');
                                });                                                            
                        
                        }                                    
                        
                        // envía email de alerta a los investigadores del sistema
                        foreach($administradores as $administrador){
                            
                            $validacion_email = Validator::make(['email' => $administrador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                                Mail::send('emails.alertas.productos.fecha_publicacion', 
                                array('destinatario' => $administrador, 'producto' => $producto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'administrador'), 
                                function($message) use ($administrador)
                                {
                                    $message->to($administrador->email)->subject('Alerta de carga de producto - fecha proyectada radicación');
                                });                                                            
                        }                                                                
                    }
                }
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| alertas_desembolsos()
    	|--------------------------------------------------------------------------
    	| Evía email de alerta relacionado con la fecha de ejecución de cada gasto de cualquier tipo de gasto
    	*/          
        private function alertas_desembolsos
            ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias)
        {
            
            // consulta para todos los detalles gastos de un proyecto
            $query = '
                SELECT DISTINCT(g.id_detalle_gasto), dg.*, tg.nombre as nombre_tipo_gasto
                FROM gastos g, detalles_gastos dg, tipos_gastos tg
                WHERE
                	g.id_proyecto = '.$proyecto->id.'
                AND	g.id_detalle_gasto = dg.id
                AND dg.id_tipo_gasto = tg.id;';
            $detalles_gastos = DB::select(DB::raw($query));
            
            // itera por cada uno de los detalles gastos,
            // compara la fecha actual + 20 con la fecha de ejecución del detalle gasto, si coincide
            // evaluado que no tenga desembolso ya cargado
            foreach($detalles_gastos as $detalle_gasto){

                if($detalle_gasto->fecha_ejecucion == $fecha_actual_mas_20_dias)
                {
                    // consulta si el detalle_gasto no tiene desembolso cargado
                    $desembolso = Desembolso::where('id_detalle_gasto', '=', $detalle_gasto->id)->first();
                    if(is_null($desembolso))
                    {
                        // agrega información del investigador si se trata de un gasto de personal
                        if($detalle_gasto->id_tipo_gasto == TipoGasto::where('nombre', '=', 'Personal')->first()->id)
                        {
                            $investigador = Investigador::find($detalle_gasto->id_investigador);
                            if(isset($investigador->id_usuario_investigador_principal))
                                $investigador = $investigador_principal;
                            else
                                $investigador = Persona::find($investigador->id_persona_coinvestigador);
                            $detalle_gasto = (array)$detalle_gasto;
                            $detalle_gasto['investigador'] = $investigador;
                            $detalle_gasto = (object)$detalle_gasto;
                        }   
                        
                        // Valida para cada caso el email del destinatario que sea un email válido
                        // Envía notificación al investigador principal del proyecto
                        $validacion_email = Validator::make(['email' => $investigador_principal->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails()) // si el email es correcto, envía email
                        {
                            // envía notificación al investigador principal
                            Mail::send('emails.alertas.desembolso', 
                            ['destinatario' => $investigador_principal, 'detalle_gasto' => $detalle_gasto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'investigador', 'tipo_gasto' => $detalle_gasto->nombre_tipo_gasto], 
                            function($message) use ($investigador_principal, $detalle_gasto)
                            {
                                $message->to($investigador_principal->email)->subject('Alerta de carga de desembolso - '.$detalle_gasto->nombre_tipo_gasto);
                            }); 
                        }
                            
                        // envía notificación al coordinador del proy
                        foreach($coordinadores_grupo_inv_ejecutor as $coordinador){
                            $validacion_email = Validator::make(['email' => $coordinador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                            {
                                Mail::send('emails.alertas.desembolso', 
                                ['destinatario' => $coordinador, 'detalle_gasto' => $detalle_gasto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'coordinador', 'tipo_gasto' => $detalle_gasto->nombre_tipo_gasto], 
                                function($message) use ($coordinador, $detalle_gasto)
                                {
                                    $message->to($coordinador->email)->subject('Alerta de carga de desembolso - '.$detalle_gasto->nombre_tipo_gasto);
                                });                                 
                            }
                        }
                            
                        // envía notificación a los administradores
                        foreach($administradores as $administrador){
                            $validacion_email = Validator::make(['email' => $administrador->email], ['email' =>'required|email']);
                            if(!$validacion_email->fails())
                            {
                                Mail::send('emails.alertas.desembolso', 
                                ['destinatario' => $administrador, 'detalle_gasto' => $detalle_gasto, 'proyecto' => $proyecto, 'tipo_destinatario' => 'administrador', 'tipo_gasto' => $detalle_gasto->nombre_tipo_gasto], 
                                function($message) use ($administrador, $detalle_gasto)
                                {
                                    $message->to($administrador->email)->subject('Alerta de carga de desembolso - '.$detalle_gasto->nombre_tipo_gasto);
                                });                                 
                            }
                        }
                    }
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| alertas_informes_avances()
    	|--------------------------------------------------------------------------
    	| Evía email de alerta relacionado con la fecha de mitad de proyecto
    	*/           
        private function alertas_informes_avances
            ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias)
        {
            calcula la fecha de mitad de proyecto
            $duracion_meses_dividida = $proyecto->duracion_meses / 2;
            $fecha_mitad_proyecto = date_create_from_format('Y-m-d', $proyecto->fecha_inicio);
            $fecha_mitad_proyecto->modify('+'.$duracion_meses_dividida.' month');
            $fecha_mitad_proyecto = $fecha_mitad_proyecto->format('Y-m-d');            
            
            // permite enviar alerta si la fecha actual + 20 esta en el mismo mes y año solamente
            // $duracion_meses_dividida = $proyecto->duracion_meses / 2;
            // $fecha_mitad_proyecto = date_create_from_format('Y-m-d', $proyecto->fecha_inicio);
            // $fecha_mitad_proyecto->modify('+'.$duracion_meses_dividida.' month');
            // $fecha_mitad_proyecto = date('Y-m', strtotime($fecha_mitad_proyecto->format('Y-m-d')));
            // $fecha_actual_mas_20_dias = date('Y-m', strtotime($fecha_actual_mas_20_dias));            
            
            // compara si la fecha de mitad de proyecto es la misma fecha actual +20 días
            if($fecha_mitad_proyecto == $fecha_actual_mas_20_dias)
            {
                // verifica que no se halla cargado informe de avance 
                $informe_avance = DocumentoProyecto::where('id_proyecto', '=', $proyecto->id)
                    ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first()->id)->first();
                if(is_null($informe_avance))
                {
                    // Valida para cada caso el email del destinatario que sea un email válido
                    // Envía notificación al investigador principal del proyecto
                    $validacion_email = Validator::make(['email' => $investigador_principal->email], ['email' =>'required|email']);
                    if(!$validacion_email->fails()) // si el email es correcto, envía email
                    {
                        // envía notificación al investigador principal
                        Mail::send('emails.alertas.informe_avance', 
                        ['destinatario' => $investigador_principal, 'proyecto' => $proyecto, 'tipo_destinatario' => 'investigador', 'fecha_mitad_proyecto' => $fecha_mitad_proyecto], 
                        function($message) use ($investigador_principal)
                        {
                            $message->to($investigador_principal->email)->subject('Alerta de carga de informe de avance');
                        }); 
                    }
                       
                    // envía notificación al coordinador del proy
                    foreach($coordinadores_grupo_inv_ejecutor as $coordinador){
                        $validacion_email = Validator::make(['email' => $coordinador->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails())
                        {
                            Mail::send('emails.alertas.informe_avance', 
                            ['destinatario' => $coordinador, 'proyecto' => $proyecto, 'tipo_destinatario' => 'coordinador', 'fecha_mitad_proyecto' => $fecha_mitad_proyecto], 
                            function($message) use ($coordinador)
                            {
                                $message->to($coordinador->email)->subject('Alerta de carga de informe de avance');
                            });                                 
                        }
                    } 
                        
                    // envía notificación a los administradores
                    foreach($administradores as $administrador){
                        $validacion_email = Validator::make(['email' => $administrador->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails())
                        {
                            Mail::send('emails.alertas.informe_avance', 
                            ['destinatario' => $administrador, 'proyecto' => $proyecto, 'tipo_destinatario' => 'administrador', 'fecha_mitad_proyecto' => $fecha_mitad_proyecto], 
                            function($message) use ($administrador)
                            {
                                $message->to($administrador->email)->subject('Alerta de carga de informe de avance');
                            });                                 
                        }
                    }                        
                }
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| alertas_final_proyecto()
    	|--------------------------------------------------------------------------
    	| Evía email de alerta relacionado con la fecha final de proyecto
    	*/            
        private function alertas_final_proyecto
            ($proyecto, $investigador_principal, $coordinadores_grupo_inv_ejecutor, $administradores, $fecha_actual_mas_20_dias)
        {
            if($proyecto->fecha_fin == $fecha_actual_mas_20_dias)
            {
                // verifica que no se halla cargado acta de finalizacion o memoria academica
                $acta_finalizacion = DocumentoProyecto::where('id_proyecto', '=', $proyecto->id)
                    ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first()->id)->first();               
                $memoria_academica = DocumentoProyecto::where('id_proyecto', '=', $proyecto->id)
                    ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first()->id)->first();
                    
                if(is_null($acta_finalizacion) && is_null($memoria_academica))
                {
                    // Valida para cada caso el email del destinatario que sea un email válido
                    // Envía notificación al investigador principal del proyecto
                    $validacion_email = Validator::make(['email' => $investigador_principal->email], ['email' =>'required|email']);
                    if(!$validacion_email->fails()) // si el email es correcto, envía email
                    {
                        // envía notificación al investigador principal
                        Mail::send('emails.alertas.final_proyecto', 
                        ['destinatario' => $investigador_principal, 'proyecto' => $proyecto, 'tipo_destinatario' => 'investigador'], 
                        function($message) use ($investigador_principal)
                        {
                            $message->to($investigador_principal->email)->subject('Alerta de carga de final de proyecto');
                        }); 
                    } 
                    
                    // envía notificación al coordinador del proy
                    foreach($coordinadores_grupo_inv_ejecutor as $coordinador){
                        $validacion_email = Validator::make(['email' => $coordinador->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails())
                        {
                            Mail::send('emails.alertas.final_proyecto', 
                            ['destinatario' => $coordinador, 'proyecto' => $proyecto, 'tipo_destinatario' => 'coordinador'], 
                            function($message) use ($coordinador)
                            {
                                $message->to($coordinador->email)->subject('Alerta de carga de final de proyecto');
                            });                                 
                        }
                    }
                    
                    // envía notificación a los administradores
                    foreach($administradores as $administrador){
                        $validacion_email = Validator::make(['email' => $administrador->email], ['email' =>'required|email']);
                        if(!$validacion_email->fails())
                        {
                            Mail::send('emails.alertas.final_proyecto', 
                            ['destinatario' => $administrador, 'proyecto' => $proyecto, 'tipo_destinatario' => 'administrador'], 
                            function($message) use ($administrador)
                            {
                                $message->to($administrador->email)->subject('Alerta de carga de final de proyecto');
                            });                                 
                        }
                    }                           
                }
            }
        }
    }
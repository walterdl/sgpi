<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Proyecto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'proyectos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_grupo_investigacion_ucc',
            'id_estado',
            'codigo_fmi',
            'subcentro_costo',
            'nombre',
            'fecha_inicio',
            'fecha_fin',
            'duracion_meses',
            'convocatoria',
            'anio_convocatoria',
            'objetivo_general'
            ];

        public function estado() { 
            return $this->belongsTo('Estado', 'id_estado'); 
        }
        
        public function grupo() { 
            return $this->belongsTo('GrupoInvestigacionUCC', 'id_grupo_investigacion_ucc'); 
        }
        
        // Relaciones indirectas
        
        public function objetivosEspecificos()
        {
            return $this->hasMany('ObjetivoEspecifico','id_proyecto');
        }

        public function documentosProyectos()
        {
            return $this->hasMany('DocumentoProyecto','id_proyecto');
        }
        
        public function investigadores()
        {
            return $this->hasMany('Investigador','id_proyecto');
        }
        
        public function gastos()
        {
            return $this->hasMany('Gasto','id_proyecto');
        }
        
        public function productos()
        {
            return $this->hasMany('Producto','id_proyecto');
        }

    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_investigador_principal()
    	|--------------------------------------------------------------------------
    	| Consulta los proyectos de investigación de un investigador principal dado su id de usuario 
    	*/
        public static function proyectos_investigador_principal($id_investigador_principal){
            
            $query = 'SELECT p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  ';
            $query .= 'p.duracion_meses, gi.nombre as nombre_grupo_inv_principal ';
            $query .= 'FROM proyectos p, investigadores i, usuarios u, grupos_investigacion_ucc gi ';
            $query .= 'WHERE u.id = '.$id_investigador_principal.' AND i.id_usuario_investigador_principal AND i.deleted_at IS NULL AND i.id_proyecto = p.id AND p.id_grupo_investigacion_ucc = gi.id; ';
            return DB::select(DB::raw($query));
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_administrador()
    	|--------------------------------------------------------------------------
    	| Consulta todos los proyectos de investigación
    	*/
        public static function proyectos_administrador(){
            
            $query = 'SELECT p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  ';
            $query .= 'p.duracion_meses, gi.nombre as nombre_grupo_inv_principal ';
            $query .= 'FROM proyectos p, grupos_investigacion_ucc gi ';
            $query .= 'WHERE p.id_grupo_investigacion_ucc = gi.id; ';
            return DB::select(DB::raw($query));
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_filtrados()
    	|--------------------------------------------------------------------------
    	| Consulta los proyectos de investigación filstrados por sede, facultad o grupo de investigación
    	*/        
        public static function proyectos_filtrados($filtro, $id_sede=null, $id_facultad=null, $id_grupo_inv=null){
            
            if($filtro == 'sede')
                $query = '
                    SELECT 
                        p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  
                        p.duracion_meses, gi.nombre as nombre_grupo_inv_principal 
                    FROM proyectos p, grupos_investigacion_ucc gi, facultades_dependencias_ucc f, sedes_ucc s
                    WHERE 
                        p.id_grupo_investigacion_ucc = gi.id
                    AND gi.id_facultad_dependencia_ucc = f.id
                    AND f.id_sede_ucc = s.id
                    AND s.id = '.$id_sede.';';
            
            else if($filtro == 'facultad')
                $query = '
                    SELECT 
                        p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  
                        p.duracion_meses, gi.nombre as nombre_grupo_inv_principal 
                    FROM proyectos p, grupos_investigacion_ucc gi, facultades_dependencias_ucc f
                    WHERE 
                        p.id_grupo_investigacion_ucc = gi.id
                    AND gi.id_facultad_dependencia_ucc = f.id
                    AND f.id = '.$id_facultad.';';
                    
            else if($filtro == 'grupo')
                $query = '
                    SELECT 
                        p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  
                        p.duracion_meses, gi.nombre as nombre_grupo_inv_principal 
                    FROM proyectos p, grupos_investigacion_ucc gi
                    WHERE 
                        p.id_grupo_investigacion_ucc = gi.id
                    AND gi.id = '.$id_grupo_inv.';';
            else
                $query = '
                    SELECT 
                        p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  
                        p.duracion_meses, gi.nombre as nombre_grupo_inv_principal 
                    FROM proyectos p, grupos_investigacion_ucc gi 
                    WHERE p.id_grupo_investigacion_ucc = gi.id; ';
                    
            return DB::select(DB::raw($query));   
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| productos_de_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los productos de un determinado proyecto junto con las postulaciones/publicaciones que tengan los mismos, adjunta los datos del participante encargado
    	| Retorna un array de la siguiente forma:
    	| Array(
    	| [0] => Array(
    	|       campos_producto_1....,
    	|       postulaciones_publicaciones => Array([0] => Array(campos_postulaciones1...), [1] => campos_postulaciones2...]),
    	|       investigador => Array(campo_investigador1, campo_investigador2, ..., campo_investigadorN)
    	|   )
    	|)
    	| El item postulaciones_publicaciones tendra tantas postulaciones/publicaciones tenga el producto 
    	| El item investigador tendra los campos descriptivos del participante encargado
    	*/        
        public static function productos_de_proyecto($id_proyecto){
            
            $respuesta = Array();
            $query = '
                SELECT 
                    prod.id as id_producto, prod.id_investigador, prod.nombre, prod.fecha_proyectada_radicacion, prod.fecha_remision, prod.fecha_confirmacion_editorial,
                    prod.fecha_recepcion_evaluacion, prod.fecha_respuesta_evaluacion, prod.fecha_aprobacion_publicacion, prod.fecha_publicacion,
                    tpe.id as id_tipo_producto_especifico, tpe.nombre as nombre_tipo_producto_especifico,
                    tpg.id as id_tipo_producto_general, tpg.nombre as nombre_tipo_producto_general
                FROM productos prod
                INNER JOIN tipos_productos_especificos tpe ON prod.id_tipo_producto_especifico = tpe.id
                INNER JOIN tipos_productos_generales tpg ON tpe.id_tipo_producto_general = tpg.id
                WHERE prod.id_proyecto = '.$id_proyecto.' AND prod.deleted_at IS NULL;';
            $productos = DB::select(DB::raw($query));
            
            if(count($productos)) // hay productos para el proyecto
            {
                foreach($productos as $producto){
                    
                    // consulta las postulaciones/publicaciones       
                    $query = '
                        SELECT 
                            pp.id as id_postulacion_publicacion, pp.archivo as archivo_postulacion_publicacion, pp.descripcion as descripcion_postulacion_publicacion,
                            tpp.id as id_tipo_postulacion_publicacion, tpp.nombre as nombre_tipo_postulacion_publicacion
                        FROM postulaciones_publicaciones pp, tipos_postulaciones_publicaciones tpp
                        WHERE pp.id_producto = '.$producto->id_producto.' AND tpp.id = pp.id_tipo_postulacion_publicacion
                        ORDER BY tpp.id;';
                        
                    $producto = (array)$producto;
                    $producto['postulaciones_publicaciones'] = DB::select(DB::raw($query));
                    
                    // consulta los datos del participante encargado
                    $investigador = Investigador::find($producto['id_investigador']);
                    
                    if(isset($investigador->id_usuario_investigador_principal))
                    {
                        // se trata del investigador principal
                        $usuario_inv_ppal = Usuario::find($investigador->id_usuario_investigador_principal);
                        $persona_inv_ppal = Persona::find($usuario_inv_ppal->id_persona);
                        $grupo_inv_ppal = GrupoInvestigacionUCC::find($usuario_inv_ppal->id_grupo_investigacion_ucc);
                        $facultad = FacultadDependenciaUCC::find($grupo_inv_ppal->id_facultad_dependencia_ucc);
                        $sede = SedeUCC::find($facultad->id_sede_ucc);
                        $producto['investigador'] = [
                            'identificacion' => $persona_inv_ppal->identificacion,
                            'nombres' => $persona_inv_ppal->nombres,
                            'apellidos' => $persona_inv_ppal->apellidos,
                            'edad' => $persona_inv_ppal->edad,
                            'sexo' => $persona_inv_ppal->sexo,
                            'email' => $usuario_inv_ppal->email,
                            'formacion' => $persona_inv_ppal->formacion,
                            'acronimo_tipo_identificacion' => TipoIdentificacion::find($persona_inv_ppal->id_tipo_identificacion)->acronimo,
                            'id_rol' => 3,
                            'grupo_investigacion' => $grupo_inv_ppal->nombre,
                            'facultad' => $facultad->nombre,
                            'sede' => $sede->nombre,
                            ];
                    }
                    else
                    {
                        if($investigador->id_rol == 4)
                        {
                            // es un investigador interno
                            $persona = Persona::find($investigador->id_persona_coinvestigador);
                            $grupo_investigacion = GrupoInvestigacionUCC::find($investigador->id_grupo_investigacion_ucc);
                            $facultad = FacultadDependenciaUCC::find($grupo_investigacion->id_facultad_dependencia_ucc);
                            $sede = SedeUCC::find($facultad->id_sede_ucc);
                            $producto['investigador'] = [
                                'identificacion' => $persona->identificacion,
                                'nombres' => $persona->nombres,
                                'apellidos' => $persona->apellidos,
                                'edad' => $persona->edad,
                                'sexo' => $persona->sexo,
                                'email' => $investigador->email,
                                'formacion' => $persona->formacion,
                                'acronimo_tipo_identificacion' => TipoIdentificacion::find($persona->id_tipo_identificacion)->acronimo,
                                'id_rol' => 4,
                                'grupo_investigacion' => $grupo_investigacion->nombre,
                                'facultad' => $facultad->nombre,
                                'sede' => $sede->nombre
                                ];                            
                        }
                        else
                        {
                            $persona = Persona::find($investigador->id_persona_coinvestigador);
                            $producto['investigador'] = [
                                'identificacion' => $persona->identificacion,
                                'nombres' => $persona->nombres,
                                'apellidos' => $persona->apellidos,
                                'edad' => $persona->edad,
                                'sexo' => $persona->sexo,
                                'email' => $investigador->email,
                                'formacion' => $persona->formacion,
                                'acronimo_tipo_identificacion' => TipoIdentificacion::find($persona->id_tipo_identificacion)->acronimo,
                                'id_rol' => $investigador->id_rol,
                                'entidad_grupo_investigacion' => $investigador->entidad_o_grupo_investigacion,
                                'programa_academico' => $investigador->programa_academico
                                ];                                   
                        }
                    }
                    $respuesta[] = $producto;
                }
            }
            return $respuesta;
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| informacion_basica_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los datos básicos de un determinado proyecto, esto es:
    	| codigo fmi, subcentro de costo, nombre de proyecto, fecha de inicio, duracion meses, fecha proyectad ade fin, objetivos, entidades/grupos investigacion
    	*/        
        public static function detalles_proyecto($id_proyecto){
            
            // obtiene informacióndescriptiva básica del proyecto
            $proyecto = Proyecto::find($id_proyecto);
            
            // obtiene los objetivos específicos del proyecto
            $objetivos_especificos = ObjetivoEspecifico::where('id_proyecto', '=', $proyecto->id)->get();
            
            // obtiene las entidades o grupos de investigacion que participan en el proyecto
            $entidades_grupos_investigacion = Proyecto::grupos_investigacion_proyecto($id_proyecto);
            
            // obtiene los investigadores que participan en el proyecto
            $investigadores = Investigador::investigadores_proyecto($id_proyecto);
            
            // obtiene las entidades patrocinadoras en el proyecto junto con el total de presupuesto que financia cada entidad
            $entidades_fuente_presupuesto = EntidadFuentePresupuesto::entidades_fuente_presupuesto_proyecto($id_proyecto);
            
            // obtiene el total de productos del proyecto agrupados por su tipo especifico
            $cantidad_productos = Producto::total_productos_agrupados_proyecto($id_proyecto);
            
            // obtiene los archivos iniciales del proyecto, esto es acta de inicio, presupuesto y presentacion de proyecto
            $documentos_iniciales = DocumentoProyecto::documentos_iniciales_proyecto($id_proyecto);
            
            return [
                'datos_generales_proyecto' => $proyecto,
                'objetivos_especificos' => $objetivos_especificos,
                'entidades_grupos_investigacion' => $entidades_grupos_investigacion,
                'investigadores' => $investigadores,
                'entidades_fuente_presupuesto' => $entidades_fuente_presupuesto,
                'cantidad_productos' => $cantidad_productos,
                'documentos_iniciales' => $documentos_iniciales
                ];
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| datos_basicos_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta por datos básicos descriptivos del proyecto, esto es:
    	| nombre de proyecto, investigador ppal, grupo de investigadocion ejecutor, facultad y sede
    	*/           
        public static function datos_basicos_proyecto($id_proyecto){
            $proyecto = Proyecto::find($id_proyecto);
            $investigador_ppal = Investigador::where('id_proyecto', '=', $id_proyecto)->where('id_rol', '=', 3)->first();
            $usuario_inv_ppal = Usuario::find($investigador_ppal->id_usuario_investigador_principal);
            $persona_inv_ppal = Persona::find($usuario_inv_ppal->id_persona);
            $grupo_inv_proyecto = GrupoInvestigacionUCC::find($proyecto->id_grupo_investigacion_ucc);
            $facultad = FacultadDependenciaUCC::find($grupo_inv_proyecto->id_facultad_dependencia_ucc);
            $sede = SedeUCC::find($facultad->id_sede_ucc);
            return [
                'id_proyecto' => $id_proyecto,
                'nombre_proyecto' => $proyecto->nombre,
                'nombre_completo_investigador_principal' => ($persona_inv_ppal->nombres.' '.$persona_inv_ppal->apellidos),
                'grupo_investigacion_ejecutor' => $grupo_inv_proyecto->nombre,
                'facultad' => $facultad->nombre,
                'sede' => $sede->nombre
                ];
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| grupos_investigacion_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorna las entidades / grupos de investigacion coejecutores juncot con el grupo de investigacion ejecutor
        |	[ 
        |    	['entidad_grupo_investigacion' => <nombre>, 'rol_en_el_proyecto' => <Ejecutor/Coejecutor>, 'id_grupo_investigacion_ucc' => id(solo grupos inv ucc)],
        |    	[n],
        |    	[n+1],
        |	]
    	*/                      
        public static function grupos_investigacion_proyecto($id_proyecto){
            
            $entidades_grupos_investigacion = [];
            
            // agrega el grupo de investigacion ejecutor
            $proyecto = Proyecto::find($id_proyecto);
            $grupo_inv_ucc = GrupoInvestigacionUCC::find($proyecto->id_grupo_investigacion_ucc);
            $facultad_grupo_inv = FacultadDependenciaUCC::find($grupo_inv_ucc->id_facultad_dependencia_ucc);
            $sede_grupo_inv = SedeUCC::find($facultad_grupo_inv->id_sede_ucc);
            $entidades_grupos_investigacion[] = [
                'entidad_grupo_investigacion' => $grupo_inv_ucc->nombre.' - '.$facultad_grupo_inv->nombre.' - Sede '.$sede_grupo_inv->nombre,
                'rol_en_el_proyecto' => 'Ejecutor',
                'id_grupo_investigacion_ucc' => $grupo_inv_ucc->id
                ];                  
            
            $investigadores_proyecto = Investigador::where('id_proyecto' , '=', $id_proyecto)->get();
            
            // organiza la estructura de datos que contiene las entidades / grupos de investigación
            // agrega las entidades / grupos de investigación coejecutores
            foreach($investigadores_proyecto as $investigador){
                if($investigador->id_rol != 3) // se procesan las entidades / grupos investigacion externos, esto es aqeullos que no son del investigador principal
                {
                    if($investigador->id_rol == 4) // es investigador interno, por tanto se añade grupo de investigacion ucc
                    {
                        $grupo_inv_ucc = GrupoInvestigacionUCC::find($investigador->id_grupo_investigacion_ucc);
                        $facultad_grupo_inv = FacultadDependenciaUCC::find($grupo_inv_ucc->id_facultad_dependencia_ucc);
                        $sede_grupo_inv = SedeUCC::find($facultad_grupo_inv->id_sede_ucc);
                        $entidades_grupos_investigacion[] = [
                            'entidad_grupo_investigacion' => $grupo_inv_ucc->nombre.' - '.$facultad_grupo_inv->nombre.' - Sede '.$sede_grupo_inv->nombre,
                            'rol_en_el_proyecto' => 'Coejecutor',
                            'id_grupo_investigacion_ucc' => $grupo_inv_ucc->id
                            ];
                    }
                    else if($investigador->id_rol == 5 || $investigador->id_rol == 6){
                        $entidades_grupos_investigacion[] = [
                            'entidad_grupo_investigacion' => $investigador->entidad_o_grupo_investigacion,
                            'rol_en_el_proyecto' => 'Coejecutor'
                            ];                        
                    }
                }
            }
            return $entidades_grupos_investigacion;
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_de_un_grupo_investigacion()
    	|--------------------------------------------------------------------------
    	| Consulta todos los proyectos de investigación cuyo grupo de investigación ejecutor es el mismo identificado por el parámetro pasado
    	*/
        public static function proyectos_de_un_grupo_investigacion($id_grupo_investigación_ucc){
            
            $query = '
                SELECT p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  
                    p.duracion_meses, gi.nombre as nombre_grupo_inv_principal 
                FROM proyectos p, grupos_investigacion_ucc gi 
                WHERE p.id_grupo_investigacion_ucc = gi.id AND gi.id = '.$id_grupo_investigación_ucc.'; ';
                
            return DB::select(DB::raw($query));
        }                
        
    	/*
    	|--------------------------------------------------------------------------
    	| cantidad_mujeres_hombres()
    	|--------------------------------------------------------------------------
    	| Consulta la cantidad total de mujeres y de hombres participantes en todos los proyectos de iinvestigación
    	*/        
        public static function cantidad_mujeres_hombres(){
            
            $proyectos = Proyecto::all();
            
            $hombres = 0;
            $mujeres = 0;
            foreach($proyectos as $proyecto){
                $investigadores = Investigador::where('id_proyecto', '=', $proyecto->id)->get();
                foreach($investigadores as $investigador){
                    if($investigador->id_rol == 3){
                        // es inv ppal
                        $usuario_inv_ppal = Usuario::find($investigador->id_usuario_investigador_principal);
                        $persona_inv_ppal = Persona::find($usuario_inv_ppal->id_persona);
                        if($persona_inv_ppal->sexo == 'm')
                            $hombres++;
                        else if($persona_inv_ppal->sexo == 'f')
                            $mujeres++;
                    }
                    else{
                        $persona = Persona::find($investigador->id_persona_coinvestigador);
                        if($persona->sexo == 'm')
                            $hombres++;
                        else if($persona->sexo == 'f')
                            $mujeres++;                        
                    }
                }
            }
            return [
                'hombres' => $hombres,
                'mujeres' => $mujeres
                ];
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| cantidad_proyectos_final_aprobado()
    	|--------------------------------------------------------------------------
    	| Consulta la cantidad total de proyectos cuyo final de proyecto ha sido aprobado
    	*/          
        public static function cantidad_proyectos_final_aprobado(){
            
            $proyectos = Proyecto::all();
            
            $finales_aprobados = 0;
            $finales_no_aprobados = 0;
            foreach($proyectos as $proyecto){
                $acta_finalizacion = DocumentoProyecto::where('id_proyecto', '=', $proyecto->id)
                    ->where('id_formato_tipo_documento', '=', 'Aprobacion final proyecto');
                if($acta_finalizacion)
                    $finales_aprobados++;
                else
                    $finales_no_aprobados++;
            }
            return [
                'finales_aprobados' => $finales_aprobados,
                'finales_no_aprobados' => $finales_no_aprobados
                ];
        }
    
    }

?>
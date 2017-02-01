<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Gasto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'gastos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_proyecto',
            'id_entidad_fuente_presupuesto',
            'id_detalle_gasto',
            'valor'
        ];
            
            
            
        public function proyecto() { 
            return $this->belongsTo('Proyecto', 'id_proyecto'); 
        }
            
        
        public function entidadFuentePresupuesto() { 
            return $this->belongsTo('EntidadFuentePresupuesto', 'id_entidad_fuente_presupuesto'); 
        }
        
        
        public function detalleGasto() { 
            return $this->belongsTo('DetalleGasto', 'id_detalle_gasto'); 
        }
        
        
        /*
    	|--------------------------------------------------------------------------
    	| consultasDetalleGasto()
    	|--------------------------------------------------------------------------
    	| Retorna todos las consltas agrupadas cpor detalle gasto o por entidad fuente_presupuesto
    	*/                                 
        public static function consultasDetalleGasto($id_proyecto){
            $query = '
                SELECT * 
                FROM  gastos
                WHERE id_proyecto = '.$id_proyecto.'
                GROUP BY  id_detalle_gasto;';
                
                $gastos = DB::select(DB::raw($query));
            return $gastos;
            
        }
        
        public static function consultasDetallePresupuesto($id_proyecto){
            $query = '
                SELECT * 
                FROM  gastos
                WHERE id_proyecto = '.$id_proyecto.'
                GROUP BY  id_entidad_fuente_presupuesto;';
                
                $gastos = DB::select(DB::raw($query));
            return $gastos;
            
        }
        
            
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_gastos_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorna todos los gastos de un proyecto agrupados por el tipo de gasto
    	| hace uso de funciones de soporte para dividir la consulta de los gastos por tipo de gasto
    	*/                                 
        public static function consultar_gastos_proyecto($id_proyecto){
  
            
            $gastos_personal = Gasto::consultar_gastos_personal_proyecto($id_proyecto);
            $totales_gastos_personal = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Personal');
            
            $gastos_equipos = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Equipos');
            $totales_gastos_equipos = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Equipos');
            
            $gastos_software = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Software');
            $totales_gastos_software = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Software');
            
            $gastos_salidas_campo = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Salidas de campo');
            $totales_gastos_salidas_campo = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Salidas de campo');            
            
            $gastos_materiales = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Materiales y suministros');
            $totales_gastos_materiales = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Materiales y suministros');
            
            $gastos_servicios = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Servicios técnicos');
            $totales_gastos_servicios = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Servicios técnicos');            
             
            $gastos_bibliograficos = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Recursos bibliográficos');
            $totales_gastos_bibliograficos = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Recursos bibliográficos');                         
            
            $gastos_digitales = Gasto::consultar_gastos_x_tipo_gasto($id_proyecto, 'Recursos educativos digitales');
            $totales_gastos_digitales = Gasto::consultar_totales_gastos_x_tipo_gasto($id_proyecto, 'Recursos educativos digitales');                                     
            
            return [
                'gastos_personal' => $gastos_personal,
                'totales_gastos_personal' => $totales_gastos_personal,
                'gastos_equipos' => $gastos_equipos,
                'totales_gastos_equipos' => $totales_gastos_equipos,
                'gastos_software' => $gastos_software,
                'totales_gastos_software' => $totales_gastos_software,
                'gastos_salidas_campo' => $gastos_salidas_campo,
                'totales_gastos_salidas_campo' => $totales_gastos_salidas_campo,
                'gastos_materiales' => $gastos_materiales,
                'totales_gastos_materiales' => $totales_gastos_materiales,
                'gastos_servicios' => $gastos_servicios,
                'totales_gastos_servicios' => $totales_gastos_servicios,
                'gastos_bibliograficos' => $gastos_bibliograficos,
                'totales_gastos_bibliograficos' => $totales_gastos_bibliograficos,
                'gastos_digitales' => $gastos_digitales,
                'totales_gastos_digitales' => $totales_gastos_digitales
                ];
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_gastos_personal_proyecto()
    	|--------------------------------------------------------------------------
    	| Función de soporte que consulta los gastos de personal de un determinado proyecto
    	*/          
        private static function consultar_gastos_personal_proyecto($id_proyecto){
            
            $investigadores = Investigador::where('id_proyecto', '=', $id_proyecto)->get();

            // Aparte de los campos que la tabla investigadores provee, se añade:
            // los nombres y apellidos concatenados mas la identificación y su acrónimo
            // nombre grupo investigacion
            // nombre facultad
            // nombre sede
            // el presupuesto financiado por cada entidad fuente de presupuesto
            for($i = 0; $i < count($investigadores); $i++){
                
                $investigador = $investigadores[$i];

                // verifica si el investigador actual es el investigador principal
                if(isset($investigador->id_usuario_investigador_principal)){
                    
                    // se trata del investigador principal
                    $usuario_inv_principal = Usuario::find($investigador->id_usuario_investigador_principal);
                    $persona_inv_principal = Persona::find($usuario_inv_principal->id_persona);
                    
                    $investigador = $investigador->toArray();
                    
                    // añade los campos extras de persona
                    $investigador['nombre_completo'] = $persona_inv_principal->nombres.' '.$persona_inv_principal->apellidos;
                    $investigador['identificacion'] = $persona_inv_principal->identificacion;
                    $investigador['sexo'] = $persona_inv_principal->sexo == 'm' ? 'Hombre' : 'Mujer';
                    $investigador['edad'] = $persona_inv_principal->edad;
                    $investigador['email'] = $usuario_inv_principal->email;
                    $investigador['formacion'] = $persona_inv_principal->formacion;
                    $investigador['nombre_rol'] = Rol::find($investigador['id_rol'])->nombre;
                    $investigador['acronimo_id'] = TipoIdentificacion::find($persona_inv_principal->id_tipo_identificacion)->acronimo;
                    
                    // añade el nombre de grupo de investigacion
                    $grupo_investigacion = GrupoInvestigacionUCC::find($usuario_inv_principal->id_grupo_investigacion_ucc);
                    $investigador['nombre_grupo_investigacion'] = $grupo_investigacion->nombre;
                    
                    // añade el nombre de la facultad ucc
                    $facultad_dependencia = FacultadDependenciaUCC::find($grupo_investigacion->id_facultad_dependencia_ucc);
                    $investigador['nombre_facultad_ucc'] = $facultad_dependencia->nombre;
                    
                    // añade nombre de sede
                    $investigador['nombre_sede_ucc'] = SedeUCC::find($facultad_dependencia->id_sede_ucc)->nombre;
                }
                else{
                    // se trata de un coinvestigador
                    
                    $persona_coinvestigador = Persona::find($investigador->id_persona_coinvestigador);
                    
                    $investigador = $investigador->toArray();
                    
                    // comprueba si el coinvestigador es interno para añadir los campos ucc que le corresponde
                    if($investigador['id_rol'] == 4){
                        
                        // añade el nombre de grupo de investigacion
                        $grupo_investigacion = GrupoInvestigacionUCC::find($investigador['id_grupo_investigacion_ucc']);
                        $investigador['nombre_grupo_investigacion'] = $grupo_investigacion->nombre;                        
                        
                        // añade el nombre de la facultad ucc
                        $facultad_dependencia = FacultadDependenciaUCC::find($grupo_investigacion->id_facultad_dependencia_ucc);
                        $investigador['nombre_facultad_ucc'] = $facultad_dependencia->nombre;
                        
                        // añade nombre de sede
                        $investigador['nombre_sede_ucc'] = SedeUCC::find($facultad_dependencia->id_sede_ucc)->nombre;                        
                    }
                    
                    // añade los campos extras de persona
                    $investigador['nombre_completo'] = $persona_coinvestigador->nombres.' '.$persona_coinvestigador->apellidos;
                    $investigador['identificacion'] = $persona_coinvestigador->identificacion;
                    $investigador['sexo'] = $persona_coinvestigador->sexo == 'm' ? 'Hombre' : 'Mujer';
                    $investigador['edad'] = $persona_coinvestigador->edad;
                    $investigador['formacion'] = $persona_coinvestigador->formacion;
                    $investigador['nombre_rol'] = Rol::find($investigador['id_rol'])->nombre;                    
                    $investigador['acronimo_id'] = TipoIdentificacion::find($persona_coinvestigador->id_tipo_identificacion)->acronimo;
                }
                
                // agrega los gastos del investigador, agregando el nombre de la entidad fuente de presupuesto
                $investigador['gastos'] = [];   
                $detalle_gasto_investigador = DetalleGasto::where('id_investigador', '=', $investigador['id'])->first();
                $investigador['id_detalle_gasto'] = $detalle_gasto_investigador->id;
                $gastos_investigador = Gasto::where('id_detalle_gasto', '=', $detalle_gasto_investigador->id)->orderBy('id_entidad_fuente_presupuesto', 'asc')->get();
                
                foreach($gastos_investigador as $gasto_investigador){
                    $gasto_investigador = $gasto_investigador->toArray();
                    $gasto_investigador['entidad_fuente_presupuesto'] = EntidadFuentePresupuesto::find($gasto_investigador['id_entidad_fuente_presupuesto'])->nombre;
                    $gasto_investigador = (object)$gasto_investigador;
                    array_push($investigador['gastos'], $gasto_investigador);
                }
                
                // añade investigador actualizado a la colección de investigadores
                $investigador['id_investigador'] = $investigador['id'];
                $investigador = (object)$investigador;
                $investigadores[$i] = $investigador;                
            }

            return $investigadores;
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_gastos_x_tipo_gasto()
    	|--------------------------------------------------------------------------
    	| Función de soporte que consulta los gastos de un determinado tipo de gasto para un determinado proyecto
    	*/                  
        private static function consultar_gastos_x_tipo_gasto($id_proyecto, $tipo_gasto){
            $query = '
            SELECT DISTINCT dg.*
            FROM detalles_gastos dg, gastos g, tipos_gastos tg
            WHERE
                dg.id_tipo_gasto = tg.id
            AND tg.nombre = \''.$tipo_gasto.'\'
            AND dg.id = g.id_detalle_gasto
            AND g.id_proyecto = '.$id_proyecto.';';
                
            $detalles_gastos = DB::select(DB::raw($query));
            
            if(count($detalles_gastos) == 0)
                return [];
            
            for($i = 0; $i < count($detalles_gastos); $i++){
                $detalle_gasto = $detalles_gastos[$i];
                $detalle_gasto = (array)$detalle_gasto;
                $detalle_gasto['id_detalle_gasto'] = $detalle_gasto['id'];
                $gastos = Gasto::where('id_detalle_gasto', '=', $detalle_gasto['id'])->get();
                $detalle_gasto['gastos'] = [];
                foreach($gastos as $gasto){
                    $entidad_fuente_presupuesto = EntidadFuentePresupuesto::find($gasto->id_entidad_fuente_presupuesto);
                    $gasto = $gasto->toArray();
                    $gasto['nombre_entidad'] = $entidad_fuente_presupuesto->nombre;
                    array_push($detalle_gasto['gastos'], (object)$gasto);
                }
                $detalles_gastos[$i] = (object)$detalle_gasto;
            }
            return $detalles_gastos;                      
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_totales_gastos_x_tipo_gasto()
    	|--------------------------------------------------------------------------
    	| Función de soporte que consulta los totales de los gastos de un determinado tipo de gasto para un determinado proyecto
    	*/               
        private static function consultar_totales_gastos_x_tipo_gasto($id_proyecto, $tipo_gasto){
            $query = '
                SELECT e.id, e.nombre as nombre_entidad, sum(g.valor) as total_entidad
                FROM gastos g, entidades_fuente_presupuesto e, detalles_gastos dg, tipos_gastos tg
                WHERE 
                	g.id_proyecto = '.$id_proyecto.'
                AND	g.id_entidad_fuente_presupuesto = e.id
                AND g.id_detalle_gasto = dg.id
                AND dg.id_tipo_gasto = tg.id
				AND tg.id = '.TipoGasto::where('nombre', '=', $tipo_gasto)->first()->id.'
                GROUP BY e.id, e.nombre
                ORDER BY e.id';            
                
            $totales_x_entidad = DB::select(DB::raw($query));            
            $gran_total = null;
            foreach($totales_x_entidad as $total){
                $gran_total += $total->total_entidad;
            }
            return [
                'totales_por_entidad' => $totales_x_entidad,
                'gran_total' => $gran_total
                ];                     
        }
    }

?>
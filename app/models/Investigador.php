<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Investigador extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'investigadores';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_usuario_investigador_principal',
            'id_persona_coinvestigador',
            'id_grupo_investigacion_ucc',
            'id_rol',
            'id_proyecto',
            'entidad_o_grupo_investigacion',
            'programa_academico',
            'email',
            'dedicacion_horas_semanales',
            'total_semanas',
            'valor_hora'
            ];
            
            public function persona(){ 
                return $this->belongsTo('Persona', 'id_persona_coinvestigador'); 
            }
            
            public function grupo(){ 
                return $this->belongsTo('GrupoInvestigacionUCC', 'id_grupo_investigacion_ucc'); 
            }
            
            
            public function usuario(){ 
                return $this->belongsTo('Usuario', 'id_usuario_investigador_principal'); 
            }
            
            public function rol(){ 
                return $this->belongsTo('Rol', 'id_rol'); 
            }
 
            
    	/*
    	|--------------------------------------------------------------------------
    	| get_investigador()
    	|--------------------------------------------------------------------------
    	| Obtiene el registro del investigador de un proyecto determinado dada su identificacion
    	| Adicional anexa la identificación como campo de clase al objeto Investigador devuelto
    	*/                  
        public static function get_investigador_por_identificacion($id_proyecto, $identificacion){
            
            $query = ' 
                SELECT i.*, p.identificacion as identificacion 
                FROM investigadores i, personas p 
                WHERE 
                    p.identificacion = '.$identificacion.' 
                    AND p.id = i.id_persona_coinvestigador
                    AND i.id_proyecto = '.$id_proyecto.';';
            
            $resultado = DB::select(DB::raw($query));
            if($resultado) // se trata de un coinvestigador
                return $resultado[0];
            else // se trata del investigador principal
            {
                $query = '
                    SELECT i.*, p.identificacion as identificacion 
                    FROM investigadores i, personas p, usuarios u
                    WHERE 
                        p.identificacion = '.$identificacion.'
                        AND p.id = u.id_persona
                        AND u.id = i.id_usuario_investigador_principal;';
                return DB::select(DB::raw($query))[0];
            }
            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| get_investigadores_proyecto()
    	|--------------------------------------------------------------------------
    	| Obtiene los investigadrores de un determinado proyecto. 
    	| Los campos que se agregan a cada registro de investigador son todos los campos de la tabla investigadores mas los campos de personas
    	| Añade tambn el nombre del grupo de investigacion, su id, el nombre de la facultad y de la sede si es un investigador interno
    	*/                          
        public static function investigadores_proyecto($id_proyecto){
            
            $respuesta_investigaodres = [];
            $investigadores = Investigador::where('id_proyecto', '=', $id_proyecto)->orderBy('id_rol', 'asc')->get();
            foreach($investigadores as $investigador){
                
                if($investigador->id_rol == 3) // investigador principal
                {
                    // consulta los datos de persona
                    $usuario = Usuario::find($investigador->id_usuario_investigador_principal);
                    $persona = Persona::find($usuario->id_persona)->toArray();
                    
                    // consulta los datos del grupo de investigacion ucc que le corresponde
                    $grupo_investigacion = GrupoInvestigacionUCC::find($usuario->id_grupo_investigacion_ucc);
                    $facultad = FacultadDependenciaUCC::find($grupo_investigacion->id_facultad_dependencia_ucc);
                    $sede = SedeUCC::find($facultad->id_sede_ucc);
                    
                    // agrega campos de persona a la estructura de datos del investigador a añadir
                    $investigador = $investigador->toArray();
                    foreach ($persona as $campo => $valor){
                        $investigador[$campo] = $valor;
                    }
                    
                    $investigador['email'] = $usuario->email;
                    
                    // agrega campos de grupo de investigacion, facultad y sede
                    $investigador['id_grupo_investigacion_ucc'] = $grupo_investigacion->id;
                    $investigador['nombre_grupo_investigacion_ucc'] = $grupo_investigacion->nombre;
                    $investigador['nombre_facultad_dependencia_ucc'] = $facultad->nombre;
                    $investigador['nombre_sede_ucc'] = $sede->nombre;
                    
                    $respuesta_investigaodres[] = $investigador;
                }
                else
                {
                    if($investigador->id_rol == 4)
                    {
                        // es investigador interno, se procede de manera similar al investigador principal coinsultando informacion del grupo de investigacion ucc
                        // consulta los datos de persona
                        $persona = Persona::find($investigador->id_persona_coinvestigador)->toArray();
                        
                        // consulta los datos del grupo de investigacion ucc que le corresponde
                        $grupo_investigacion = GrupoInvestigacionUCC::find($investigador->id_grupo_investigacion_ucc);
                        $facultad = FacultadDependenciaUCC::find($grupo_investigacion->id_facultad_dependencia_ucc);
                        $sede = SedeUCC::find($facultad->id_sede_ucc);                        
                        
                        // agrega campos de persona a la estructura de datos del investigador a añadir
                        $investigador = $investigador->toArray();
                        foreach ($persona as $campo => $valor){
                            $investigador[$campo] = $valor;
                        }                        
                        
                        // agrega campos de grupo de investigacion, facultad y sede
                        $investigador['id_grupo_investigacion_ucc'] = $grupo_investigacion->id;
                        $investigador['nombre_grupo_investigacion_ucc'] = $grupo_investigacion->nombre;
                        $investigador['nombre_facultad_dependencia_ucc'] = $facultad->nombre;
                        $investigador['nombre_sede_ucc'] = $sede->nombre;
                        
                        $respuesta_investigaodres[] = $investigador;                        
                    }
                    else
                    {
                        // se trata de investigador externo o estudiante
                        // consulta los datos de persona
                        $persona = Persona::find($investigador->id_persona_coinvestigador)->toArray();                        
                        
                        // agrega campos de persona a la estructura de datos del investigador a añadir
                        $investigador = $investigador->toArray();
                        foreach ($persona as $campo => $valor){
                            $investigador[$campo] = $valor;
                        }                                                
                        
                        $respuesta_investigaodres[] = $investigador;
                    }
                }
            }
            
            return $respuesta_investigaodres;
        }
    
    }

?>
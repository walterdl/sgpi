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
    
    }

?>
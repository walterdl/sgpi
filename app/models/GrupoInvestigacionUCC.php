<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class GrupoInvestigacionUCC extends Eloquent {
        
        protected $table = 'grupos_investigacion_ucc';
        
        protected $dates = ['deleted_at'];
        
        protected $fillable = array('id','nombre', 'id_facultad_dependencia_ucc', 'id_area', 'id_clasificacion_grupo_investigacion');
        
        public function facultad() { 
            return $this->belongsTo('FacultadDependenciaUCC', 'id_facultad_dependencia_ucc'); 
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| get_grupos_investigacion_con_sedes()
    	|--------------------------------------------------------------------------
    	| Retorna los grupos de investigacipon y su información relacionada agrupados por sede en un array asociativo cuya clave es el id de la sede
    	| ej:
    	| Array(1 => Array(
    	|       'nombre_sede' => 'Arauca'
    	|       'grupos_investigacion' => Array(GrupoInvestigacion1, GrupoInvestigacion2,....)
    	|      ),
    	|       2 => Array(Otra sede junto con sus grupos de investigación...)
    	| )
    	*/                  
        public static function get_grupos_investigacion_con_sedes(){
            
            $sedes = SedeUCC::all();
            $respuesta = array(); // contendra el array con los grupos agrupados por sede a retornar
            foreach($sedes as $sede){
                
                $query = 'SELECT gi.*, fd.nombre as facultad_dependencia, a.id as id_area, a.id_gran_area, a.nombre as nombre_area, ga.nombre as nombre_gran_area, cgi.nombre as clasificacion_grupo_inv ';
                $query .= 'FROM grupos_investigacion_ucc gi, facultades_dependencias_ucc fd, areas a, gran_areas ga, clasificaciones_grupos_investigacion cgi ';
                $query .= 'WHERE  ';
                $query .= '    fd.id AND fd.id_sede_ucc = '.$sede->id.' ';
                $query .= 'AND gi.id_facultad_dependencia_ucc = fd.id ';
                $query .= 'AND gi.id_clasificacion_grupo_investigacion = cgi.id ';
                $query .= 'AND gi.id_area = a.id ';
                $query .= 'AND a.id_gran_area = ga.id; ';
                
                $resultado_query_grupos_investigacion = DB::select(DB::raw($query));
                
                $respuesta[$sede->id] = array(
                    'nombre_sede' => $sede->nombre,
                    'grupos_investigacion' => $resultado_query_grupos_investigacion
                    );
            }
            return $respuesta;
        }

    	/*
    	|--------------------------------------------------------------------------
    	| grupos_inv_x_facultades()
    	|--------------------------------------------------------------------------
    	| Retorna los grupos de investigacipon y su información relacionada agrupados por facultad en un array asociativo cuya clave es el id de la facultad
    	| ej:
    	| Array(1 => Array(
    	|       'nombre_facultad' => 'Ingeniería'
    	|       'grupos_investigacion' => Array(GrupoInvestigacion1, GrupoInvestigacion2,....)
    	|      ),
    	|       2 => Array(Otra facultad junto con sus grupos de investigación...)
    	| )
    	*/                  
        public static function grupos_inv_x_facultades(){
            
            $facultades_ucc = FacultadDependenciaUCC::all();
            $respuesta = [];
            foreach($facultades_ucc as $facultad){
                $grupos_investigacion_ucc = GrupoInvestigacionUCC::where('id_facultad_dependencia_ucc', '=', $facultad->id)->get();
                $respuesta[$facultad->id] = [
                    'nombre_facultad' => $facultad->nombre,
                    'grupos_investigacion' => $grupos_investigacion_ucc
                    ];
            }
            return $respuesta;
        }

    	/*
    	|--------------------------------------------------------------------------
    	| coordinadores_grupo_investigacion()
    	|--------------------------------------------------------------------------
    	| Obtiene los coordinadores de un determinado grupo de investigacion
    	*/         
        public static function coordinadores_grupo_investigacion($id_grupo_investigacion_ucc){
            
            $coordinadores = Usuario::where('id_grupo_investigacion_ucc', '=', $id_grupo_investigacion_ucc)
                ->where('id_rol', '=', 2)->get();
            for($i = 0; $i < count($coordinadores); $i++){
                
                $coordinador = $coordinadores[$i];
                $persona = Persona::find($coordinador->id);
                $coordinador = $coordinador->toArray();
                $coordinador['nombres'] = $persona->nombres; 
                $coordinador['apellidos'] = $persona->apellidos;
                $coordinador = (object)$coordinador;
                $coordinadores[$i] = $coordinador;
            }
            return $coordinadores;
        }

    }

?>
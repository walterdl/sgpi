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

    }

?>
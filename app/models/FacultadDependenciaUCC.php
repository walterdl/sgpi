<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class FacultadDependenciaUCC extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'facultades_dependencias_ucc';
        
        protected $dates = ['deleted_at'];
        
        protected $fillable = ['id','id_sede_ucc', 'nombre'];
        
        public function sede() { 
            return $this->belongsTo('SedeUCC', 'id_sede_ucc'); 
        }
        
        
        public function grupo() { 
            return $this->hasMany('GrupoInvestigacionUCC', 'id_facultad_dependencia_ucc'); 
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| facultades_x_sedes()
    	|--------------------------------------------------------------------------
    	| Retorna las facultades y su información relacionada agrupados por sede en un array asociativo cuya clave es el id de la sede
    	| ej:
    	| Array(1 => Array(
    	|       'nombre_sede' => 'Arauca'
    	|       'facultades' => Array(Facultad1, Facultad2,....)
    	|      ),
    	|       2 => Array(Otra sede junto con sus facultades...)
    	| )
    	*/                          
        public static function facultades_x_sedes(){
            
            $sedes = SedeUCC::all();
            $respuesta = [];
            foreach($sedes as $sede){
                $facultades = FacultadDependenciaUCC::where('id_sede_ucc', '=', $sede->id)->get();
                $respuesta[$sede->id] = [
                    'nombre_sede' => $sede->nombre,
                    'facultades' => $facultades
                    ];
            }
            return $respuesta;
        }

    }

?>
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Area extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'areas';
        
        protected $dates = ['deleted_at'];
        
        public static function cantidad_proys_x_area(){
          
            return DB::select(
                DB::raw('
                SELECT a.id as id_area, a.nombre as nombre_area, COUNT(*) as cantidad 
                FROM grupos_investigacion_ucc g, areas a, proyectos p
                WHERE 
                    g.id = p.id_grupo_investigacion_ucc
                AND g.id_area = a.id
                GROUP BY a.id, a.nombre;'));          
            
        }
    
    }

?>
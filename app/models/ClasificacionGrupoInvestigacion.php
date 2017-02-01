<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class ClasificacionGrupoInvestigacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'clasificaciones_grupos_investigacion';
        
        protected $dates = ['deleted_at'];
        
        public static function cantidad_proys_x_clasificacion(){
            
            return DB::select(
                DB::raw('
                SELECT c.id as id_clasificacion_grupo_investigacion, c.nombre as nombre_clasificacion_grupo_investigacion, COUNT(*) as cantidad 
                FROM grupos_investigacion_ucc g, clasificaciones_grupos_investigacion c, proyectos p
                WHERE 
                	c.id = g.id_clasificacion_grupo_investigacion
                AND g.id = p.id_grupo_investigacion_ucc
                GROUP BY c.id, c.nombre;'));
        }
        
    }

?>
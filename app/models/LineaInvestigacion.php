<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class LineaInvestigacion extends Eloquent {
        
        protected $fillable = ['nombre'];
        
        protected $table = 'lineas_investigacion';
        protected $dates = ['deleted_at'];
        
        /*
        |--------------------------------------------------------------------------
        | eliminar_linea_investigacion()
        |--------------------------------------------------------------------------
        | Elimina una linea de investigación dado su id
        | Se eliminan las relaciones de la linea a eliminar con los grupos de investigación correspondientes
        */            
        public static function eliminar_linea_investigacion($id){
            
            $query = 'DELETE FROM lineas_grupos_investigacion ';
            $query .= 'WHERE id_linea_investigacion = '.$id.';';
            DB::select(DB::raw($query));
            LineaInvestigacion::find($id)->delete();
        }
        
    }

?>
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class EntidadFuentePresupuesto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'entidades_fuente_presupuesto';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'nombre'
        ];
        
    	/*
    	|--------------------------------------------------------------------------
    	| entidades_fuente_presupuesto_proyecto()
    	|--------------------------------------------------------------------------
    	| Obtiene las entidades fuente presupuesto de un determiando proyecto dado su identificador
    	| Retorna el nombre de cada entidad, el total que cada entidad provee 
    	| y la cantidad total de entidades fuente de presupuesto que particiapn en el rpoyecto aparte de las entidades UUC y CONADI
    	*/                          
        public static function entidades_fuente_presupuesto_proyecto($id_proyecto){
            
            $query = '
            SELECT efp.id as id_entidad_fuente_presupuesto, efp.nombre as nombre_entidad_fuente_presupuesto, SUM(g.valor) as total
            FROM entidades_fuente_presupuesto efp
            INNER JOIN gastos g ON g.id_proyecto = '.$id_proyecto.' AND g.id_entidad_fuente_presupuesto = efp.id
            GROUP BY efp.id, efp.nombre;';
            
            $totales_x_entidad_fuente_presupuesto = DB::select(DB::raw($query));
            
            // cuenta el total de entidades fuente presupuesto distintas a UCC y CONADI
            $total_entidades_fuente_presupuesto_externas = 0;
            foreach($totales_x_entidad_fuente_presupuesto as $entidad){
                if($entidad->nombre_entidad_fuente_presupuesto != 'UCC' && $entidad->nombre_entidad_fuente_presupuesto != 'CONADI')
                    $total_entidades_fuente_presupuesto_externas++;
            }
            
            return [
                'entidades' => $totales_x_entidad_fuente_presupuesto,
                'cantidad_entidades_fuente_presupuesto_distintas' => $total_entidades_fuente_presupuesto_externas
                ];
        }
    
    }

?>
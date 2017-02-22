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
            INNER JOIN gastos g ON g.id_proyecto = '.$id_proyecto.' AND g.id_entidad_fuente_presupuesto = efp.id AND g.deleted_at IS NULL 
            GROUP BY efp.id, efp.nombre
            ORDER BY efp.id;';
            
            $entidades_fuente_presupuesto = DB::select(DB::raw($query));
            $total_entidades_fuente_presupuesto_externas = 0;
            
            // Organiza las entidades de tal manera que quede la entidad UCC y CONADI de primeras
            // Primero organiza la entidad UCC
            // Identifica donde se encuentra actualmente la entidad UCC
            $indice_entidad_ucc = null;
            for($i = 0; $i < count($entidades_fuente_presupuesto); $i++){
                $entidad = $entidades_fuente_presupuesto[$i];
                if($entidad->nombre_entidad_fuente_presupuesto == 'UCC')
                    $indice_entidad_ucc = $i;
                    
                // cuenta la cantidad fuente de presupuesto distintas de UCC y CONADI
                else if($entidad->nombre_entidad_fuente_presupuesto != 'UCC' && $entidad->nombre_entidad_fuente_presupuesto != 'CONADI')
                    $total_entidades_fuente_presupuesto_externas++;                    
            }
            if($indice_entidad_ucc!=0) // Si UCC no está de primera
            {
                $entidad_ucc = $entidades_fuente_presupuesto[$indice_entidad_ucc];
                array_splice($entidades_fuente_presupuesto, $indice_entidad_ucc, 1); // quita la entidad UCC de la posición donde se encuentra
                array_splice($entidades_fuente_presupuesto, 0, 0, $entidad_ucc); // coloca la entidad UCC de primera
            }
            
            // Identifica donde se encuentra actualmente la entidad CONADI
            $indice_entidad_conadi = null;
            for($i = 0; $i < count($entidades_fuente_presupuesto); $i++){
                $entidad = $entidades_fuente_presupuesto[$i];
                if($entidad->nombre_entidad_fuente_presupuesto == 'CONADI')
                    $indice_entidad_conadi = $i;
            }       
            if($indice_entidad_conadi != null && $indice_entidad_conadi != 1) // Si CONADI no está de segunda
            {
                $entidad_conadi = $entidades_fuente_presupuesto[$indice_entidad_conadi];
                array_splice($entidades_fuente_presupuesto, $indice_entidad_conadi, 1); // quita la entidad CONADI de la posición donde se encuentra
                array_splice($entidades_fuente_presupuesto, 0, 0, $entidad_conadi); // coloca la entidad CONADI de primera
            }            
            
            return [
                'entidades' => $entidades_fuente_presupuesto,
                'cantidad_entidades_fuente_presupuesto_distintas' => $total_entidades_fuente_presupuesto_externas
                ];
        }
    
    }

?>
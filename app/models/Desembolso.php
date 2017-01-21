<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Desembolso extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'desembolsos';
        protected $dates = ['deleted_at'];
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_desembolso_gasto_personal()
    	|--------------------------------------------------------------------------
    	| Consulta tanto la revisión como el archivo de desembolso para un detalle de gasto dado
    	*/                      
        public static function consultar_desembolso_gasto_personal($id_detalle_gasto){
            
            $desembolso = Desembolso::where('id_detalle_gasto', '=', $id_detalle_gasto)->first();
            if($desembolso){
                return [
                    'hay_desembolso' => 1,
                    'archivo' => $desembolso->archivo,
                    'aprobado' => $desembolso->aprobado,
                    'codigo_aprobacion' => $desembolso->codigo_aprobacion,
                    'comentario_revision' => $desembolso->comentario_revision
                    ];
            }
            else
                return ['hay_desembolso' => 0];
        }
    }

?>
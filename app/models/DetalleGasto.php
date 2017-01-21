<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class DetalleGasto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'detalles_gastos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id_tipo_gasto',
            'id_investigador',
            'fecha_ejecucion',
            'concepto',
            'justificacion',
            'numero_salidas',
            'valor_unitario'
            ];
            
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_desembolso_gasto_personal()
    	|--------------------------------------------------------------------------
    	| Consulta tanto la revisión como el archivo de desembolso para un detalle de gasto dado
    	*/                      
        public static function consultar_desembolso_gasto_personal($id_detalle_gasto){
            
        }
    }

?>
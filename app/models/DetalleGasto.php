<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class DetalleGasto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'detalles_gastos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_tipo_gasto',
            'id_investigador',
            'fecha_ejecucion',
            'concepto',
            'justificacion',
            'numero_salidas',
            'valor_unitario'
        ];
        
        
        public function tipoGasto() { 
            return $this->belongsTo('TipoGasto', 'id_tipo_gasto'); 
        }
        
        
        public function investigador() { 
            return $this->belongsTo('Investigador', 'id_investigador'); 
        }
        
        
        
        // Relaciones indirectas
        public function desembolso()
        {
            return $this->hasMany('Desembolso','id_detalle_gasto');
        }
        

        public function gasto()
        {
            return $this->hasMany('Gasto','id_detalle_gasto');
        }
        
            
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
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Desembolso extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'desembolsos';
        protected $dates = ['deleted_at'];
        
        protected $fillable = [
            'id',
            'id_detalle_gasto',
            'id_formato_tipo_documento',
            'archivo',
            'aprobado',
            'codigo_aprobacion',
            'comentario_investigador',
            'comentario_revision'
        ];
        
        
        public function detalleGasto() { 
            return $this->belongsTo('DetalleGasto', 'id_detalle_gasto'); 
        }
        
        
        public function formatoTipoDocumento() { 
            return $this->belongsTo('FormatoTipoDocumento', 'id_formato_tipo_documento'); 
        }
        
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_desembolso_gasto_equipo()
    	|--------------------------------------------------------------------------
    	| Consulta tanto la revisión como el archivo de desembolso para un detalle de gasto dado
    	*/          
        public static function consultar_desembolso($id_detalle_gasto){
            $desembolso = Desembolso::where('id_detalle_gasto', '=', $id_detalle_gasto)->first();
            if($desembolso){
                return [
                    'hay_desembolso' => 1,
                    'archivo' => $desembolso->archivo,
                    'aprobado' => $desembolso->aprobado,
                    'comentario_investigador' =>  $desembolso->comentario_investigador,
                    'codigo_aprobacion' => $desembolso->codigo_aprobacion,
                    'comentario_revision' => $desembolso->comentario_revision,
                    'updated_at' => $desembolso->updated_at->format('Y-m-d')
                    ];
            }
            else
                return ['hay_desembolso' => 0];            
        }
    }

?>
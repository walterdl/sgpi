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
        
    	/*
    	|--------------------------------------------------------------------------
    	| desembolsos_aprobados_x_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los desembolsos aprobados y no aprobados de un determinado proyecto
    	*/                  
        public static function desembolsos_aprobados_x_proyecto($id_proyecto){
            
            $query = '
                SELECT DISTINCT(g.id_detalle_gasto) as id_detalle_gasto
                FROM gastos g
                WHERE
                    g.id_proyecto = '.$id_proyecto.';';
            
            $gastod = DB::select(DB::raw($query));
            $no_aprobados = 0;
            $aprobados = 0;
            
            foreach($gastod as $gasto)
            {
                // verifica si tiene desembolso, 
                // si no lo tiene se cuenta como no aprobado, si lo tiene verifica estado de aprobacion
                $desembolso = Desembolso::where('id_detalle_gasto', '=', $gasto->id_detalle_gasto)->first();
                if($desembolso){
                    if($desembolso->aprobado)
                        $aprobados++;
                    else
                        $no_aprobados++;
                }
                else
                    $no_aprobados++;
            }
            return ['aprobados' => $aprobados, 'no_aprobados' => $no_aprobados];
        }
    }

?>
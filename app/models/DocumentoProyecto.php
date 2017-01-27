<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class DocumentoProyecto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'documentos_proyectos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_proyecto',
            'id_tipo_documento_proyecto',
            'archivo',
            'aprobado',
            'comentario_investigador',
            'comentario_revision',
            ];
            
    	/*
    	|--------------------------------------------------------------------------
    	| documentos_iniciales_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los registros relacionados con los documentos iniciales del proyecto
    	| esto es presupuesto, acta de inicio, presentacion de proy
    	*/                                              
        public static function documentos_iniciales_proyecto($id_proyecto){
            
            $presupuesto = DocumentoProyecto::where('id_proyecto', '=', $id_proyecto)
                ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Presupuesto')->first()->id)->first();
            
            $presentacion_proyecto = DocumentoProyecto::where('id_proyecto', '=', $id_proyecto)
                ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Presentacion proyecto')->first()->id)->first();            
            
            $acta_inicio = DocumentoProyecto::where('id_proyecto', '=', $id_proyecto)
                ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Acta inicio')->first()->id)->first();            
            
            return ['presupuesto' => $presupuesto, 'presentacion_proyecto' => $presentacion_proyecto, 'acta_inicio' => $acta_inicio];
        }
    
    }

?>
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
            'nombre',
            'ruta_archivo',
            'detalle_revision'
            ];
    
    }

?>
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class FormatoTipoDocumento extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'formatos_tipos_documentos';
        protected $dates = ['deleted_at'];
    
    }

?>
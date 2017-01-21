<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class TipoPostulacionPublicacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'tipos_postulaciones_publicaciones';
        protected $dates = ['deleted_at'];
    
    }

?>
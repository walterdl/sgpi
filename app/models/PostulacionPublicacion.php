<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class PostulacionPublicacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'postulaciones_publicaciones';
        protected $dates = ['deleted_at'];
    
    }

?>
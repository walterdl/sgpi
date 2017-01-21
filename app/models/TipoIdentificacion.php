<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class TipoIdentificacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'tipos_identificacion';
        protected $dates = ['deleted_at'];
    
    }

?>
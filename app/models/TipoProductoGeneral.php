<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class TipoProductoGeneral extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'tipos_productos_generales';
        protected $dates = ['deleted_at'];
    
    }

?>
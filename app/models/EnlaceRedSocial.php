<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class EnlaceRedSocial extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'enlaces_redes_sociales';
        protected $dates = ['deleted_at'];
    
    }

?>
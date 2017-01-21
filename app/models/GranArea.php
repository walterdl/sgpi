<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class GranArea extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'gran_areas';
        protected $dates = ['deleted_at'];
    
    }

?>
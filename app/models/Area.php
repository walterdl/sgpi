<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Area extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'areas';
        protected $dates = ['deleted_at'];
    
    }

?>
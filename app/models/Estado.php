<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Estado extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'estados';
        protected $dates = ['deleted_at'];
        public $incrementing = false;
    }

?>
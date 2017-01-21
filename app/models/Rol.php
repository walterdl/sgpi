<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Rol extends Eloquent {
        
        use SoftDeletingTrait;
        
        
        //protected $fillable = array('*');
        
        protected $table = 'roles';
        protected $fillable = array('id', 'nombre');
        protected $dates = ['deleted_at'];
        public $incrementing = false;
    
    }

?>
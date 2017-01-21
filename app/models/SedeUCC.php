<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class SedeUCC extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $fillable = ['id','nombre', 'ciudad', 'departamento_estado', 'pais', 'descripcion'];
        
        protected $table = 'sedes_ucc';
        protected $dates = ['deleted_at'];   
    }

?>
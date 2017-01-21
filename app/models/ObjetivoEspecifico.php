<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class ObjetivoEspecifico extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'objetivos_especificos';
        protected $dates = ['deleted_at'];
        protected $fillable = ['nombre', 'id_proyecto', 'id_estado'];
            
    }

?>
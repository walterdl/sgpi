<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class FacultadDependenciaUCC extends Eloquent {
        
        use SoftDeletingTrait;
        
        
        
        protected $table = 'facultades_dependencias_ucc';
        protected $dates = ['deleted_at'];
        
        protected $fillable = ['id','id_sede_ucc', 'nombre'];
        
        public function sede() { 
            return $this->belongsTo('SedeUCC', 'id_sede_ucc'); 
        }

    }

?>
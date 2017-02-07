<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class SedeUCC extends Eloquent {
        
        use SoftDeletingTrait;
        
        
        protected $table = 'sedes_ucc';
        protected $dates = ['deleted_at'];  
        
        protected $fillable = ['id','nombre', 'ciudad', 'departamento_estado', 'pais', 'descripcion'];
        
        
        public function facultad() { 
            return $this->hasMany('FacultadDependenciaUCC', 'id_sede_ucc'); 
        }
        
        public function facultades() { 
            return $this->hasMany('FacultadDependenciaUCC', 'id_sede_ucc')->join('grupos_investigacion_ucc', 'facultades_dependencias_ucc.id', '=', 'grupos_investigacion_ucc.id_facultad_dependencia_ucc'); 
        }
    }
    
     

?>
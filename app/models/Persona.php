<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Persona extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $fillable = [
            'nombres',
            'apellidos',
            'identificacion',
            'sexo',
            'edad',
            'formacion',
            'id_tipo_identificacion',
            'id_categoria_investigador',
            'foto'
            ];
        
        protected $table = 'personas';
        protected $dates = ['deleted_at'];
        
        public function tipoIdentificacion(){ 
            return $this->belongsTo('TipoIdentificacion', 'id_tipo_identificacion'); 
        }
        
        public function categoria() { 
            return $this->belongsTo('CategoriaInvestigador', 'id_categoria_investigador'); 
        }
        
        
    }

?>
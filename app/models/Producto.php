<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Producto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'productos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_proyecto',
            'id_tipo_producto_especifico',
            'id_investigador',
            'id_estado',
            'nombre',
            'fecha_proyectada_radicacion',
            'fecha_remision',
            'fecha_confirmacion_editorial',
            'fecha_recepcion_evaluacion',
            'fecha_respuesta_evaluacion',
            'fecha_aprobacion_publicacion',
            'fecha_publicacion'
            ];
            
            
            public function proyecto() { 
                return $this->belongsTo('Proyecto', 'id_proyecto'); 
            }
            
           
            public function tipoProductoE() { 
                return $this->belongsTo('TipoProductoEspecifico', 'id_tipo_producto_especifico'); 
            }
            
            public function investigador() { 
                return $this->belongsTo('Investigador', 'id_investigador'); 
            }
            
            
            public function estado() { 
                return $this->belongsTo('Estado', 'id_estado'); 
            }
    
    }

?>
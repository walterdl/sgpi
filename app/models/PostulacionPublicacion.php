<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class PostulacionPublicacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'postulaciones_publicaciones';
        protected $dates = ['deleted_at'];
        
        
        protected $fillable = [
            'id',
            'id_producto',
            'id_tipo_postulacion_publicacion',
            'archivo',
            'descripcion'
            ];
            
            
        public function producto()
        {       
            return $this->belongsTo('Producto', 'id_producto'); 
        }
    
    }

?>
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Producto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'productos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
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
    
    }

?>
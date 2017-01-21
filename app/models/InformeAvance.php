<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class InformeAvance extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'informes_avances';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_proyecto',
            'ruta_archivo',
            'aprobado',
            'revisado',
            'detalle_revision'
            ];
    
    }

?>
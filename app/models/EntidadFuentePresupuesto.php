<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class EntidadFuentePresupuesto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'entidades_fuente_presupuesto';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'nombre'
        ];
        
    
    }

?>
<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class TipoGasto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'tipos_gastos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'nombre',
        ];
    
    }

?>
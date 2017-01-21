<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class CategoriaInvestigador extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'categorias_investigadores';
        protected $dates = ['deleted_at'];
    
    }

?>
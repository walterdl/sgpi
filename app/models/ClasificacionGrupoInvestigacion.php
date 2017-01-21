<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class ClasificacionGrupoInvestigacion extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'clasificaciones_grupos_investigacion';
        protected $dates = ['deleted_at'];
    
    }

?>
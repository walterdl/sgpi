<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class LineaGrupoInvestigacion extends Eloquent {
        
        protected $fillable = ['id_grupo_investigacion_ucc', 'id_linea_investigacion'];
        
        protected $table = 'lineas_grupos_investigacion_ucc';
        protected $dates = ['deleted_at'];
    
    }

?>
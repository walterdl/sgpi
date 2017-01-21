<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Proyecto extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'proyectos';
        protected $dates = ['deleted_at'];
        protected $fillable = [
            'id',
            'id_grupo_investigacion_ucc',
            'id_estado',
            'codigo_fmi',
            'subcentro_costo',
            'nombre',
            'fecha_inicio',
            'fecha_fin',
            'duracion_meses',
            'convocatoria',
            'anio_convocatoria',
            'objetivo_general'
            ];
        
        
        public function estado() { 
            return $this->belongsTo('Estado', 'id_estado'); 
        }
        
        public function grupo() { 
            return $this->belongsTo('GrupoInvestigacionUCC', 'id_grupo_investigacion_ucc'); 
        }
        
        
        // Relaciones indirectas
        
        public function objetivosEspecificos()
        {
            return $this->hasMany('ObjetivoEspecifico','id_proyecto');
        }
        
        public function informeAvances()
        {
            return $this->hasMany('InformeAvance','id_proyecto');
        }
        
        
        public function documentosProyectos()
        {
            return $this->hasMany('DocumentoProyecto','id_proyecto');
        }
        
        
        public function investigadores()
        {
            return $this->hasMany('Investigador','id_proyecto');
        }
        
        public function gastos()
        {
            return $this->hasMany('Gasto','id_proyecto');
        }
        
        
        public function productos()
        {
            return $this->hasMany('Producto','id_proyecto');
        }
        
        
    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_investigador_principal()
    	|--------------------------------------------------------------------------
    	| Consulta los proyectos de investigación de un investigador principal dado su id de usuario 
    	*/
        public static function proyectos_investigador_principal($id_investigador_principal){
            
            $query = 'SELECT p.id, gi.nombre, p.codigo_fmi, p.subcentro_costo, p.nombre as nombre_proyecto, p.fecha_fin,  ';
            $query .= 'p.duracion_meses, gi.nombre as nombre_grupo_inv_principal ';
            $query .= 'FROM proyectos p, investigadores i, usuarios u, grupos_investigacion_ucc gi ';
            $query .= 'WHERE u.id = '.$id_investigador_principal.' AND i.id_usuario_investigador_principal AND i.id_proyecto = p.id AND p.id_grupo_investigacion_ucc = gi.id; ';
            return DB::select(DB::raw($query));
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| productos_de_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los productos de un determinado proyecto junto con las postulaciones/publicaciones que tengan los mismos
    	| Retorna un array de la siguiente forma:
    	| Array(
    	| [0] => Array(
    	|       campos_producto_1....,
    	|       postulaciones_publicaciones => Array([0] => Array(campos_postulaciones1...), [1] => campos_postulaciones2...])
    	|   )
    	|)
    	| El item postulaciones_publicaciones tendra tantas postulaciones/publicaciones tenga el producto 
    	*/        
        public static function productos_de_proyecto($id_proyecto){
            
            $respuesta = Array();
            $query = '
                SELECT 
                    prod.id as id_producto, prod.nombre, prod.fecha_proyectada_radicacion, prod.fecha_remision, prod.fecha_confirmacion_editorial,
                    prod.fecha_recepcion_evaluacion, prod.fecha_respuesta_evaluacion, prod.fecha_aprobacion_publicacion, prod.fecha_publicacion,
                    tpe.id as id_tipo_producto_especifico, tpe.nombre as nombre_tipo_producto_especifico,
                    tpg.id as id_tipo_producto_general, tpg.nombre as nombre_tipo_producto_general
                FROM productos prod
                INNER JOIN tipos_productos_especificos tpe ON prod.id_tipo_producto_especifico = tpe.id
                INNER JOIN tipos_productos_generales tpg ON tpe.id_tipo_producto_general = tpg.id
                WHERE prod.id_proyecto = '.$id_proyecto.';';
            $productos = DB::select(DB::raw($query));
            
            if(count($productos)) // hay productos para el proyecto
            {
                // consulta las postulaciones/publicaciones
                foreach($productos as $producto){
                    
                    $query = '
                        SELECT 
                            pp.id as id_postulacion_publicacion, pp.archivo as archivo_postulacion_publicacion, pp.descripcion as descripcion_postulacion_publicacion,
                            tpp.id as id_tipo_postulacion_publicacion, tpp.nombre as nombre_tipo_postulacion_publicacion
                        FROM postulaciones_publicaciones pp, tipos_postulaciones_publicaciones tpp
                        WHERE pp.id_producto = '.$producto->id_producto.' AND tpp.id = pp.id_tipo_postulacion_publicacion
                        ORDER BY tpp.id;';
                        
                    $producto = (array)$producto;
                    $producto['postulaciones_publicaciones'] = DB::select(DB::raw($query));
                    
                    $respuesta[] = $producto;
                }
            }
            return $respuesta;
        }
    
    }

?>
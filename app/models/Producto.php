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
         
    	/*
    	|--------------------------------------------------------------------------
    	| total_productos_agrupados_proyecto()
    	|--------------------------------------------------------------------------
    	| Obtiene el total de productos de un determinado proyecto agrupados por su tipo específico
    	*/                                   
        public static function total_productos_agrupados_proyecto($id_proyecto){
            
            $query = '
            SELECT tpe.id as id_tipo_producto_especifico, tpe.nombre as nombre_tipo_producto_especifico, COUNT(*) as cantidad_productos
            FROM tipos_productos_especificos tpe
            INNER JOIN productos p ON p.id_proyecto = '.$id_proyecto.' AND p.id_tipo_producto_especifico = tpe.id
			GROUP BY tpe.id, tpe.nombre;
            ';
            
            $productos = DB::select(DB::raw($query));
            
            for($i = 0; $i < count($productos); $i++){
                
                $producto = $productos[$i];
                $tipo_producto_especifico = TipoProductoEspecifico::find($producto->id_tipo_producto_especifico);
                $tipo_producto_general = TipoProductoGeneral::find($tipo_producto_especifico->id_tipo_producto_general);
                $producto = (array)$producto;
                // añade al registro el nombre del tipo de producto general
                $producto['nombre_tipo_producto_general'] = $tipo_producto_general['nombre'];
                $productos[$i] = (object)$producto;
            }
            
            return $productos;
        }
    
    }

?>
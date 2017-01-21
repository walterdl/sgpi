<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class TipoProductoEspecifico extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'tipos_productos_especificos';
        protected $dates = ['deleted_at'];
        
        
    	/*
    	|--------------------------------------------------------------------------
    	| productos_especificos_x_prod_general()
    	|--------------------------------------------------------------------------
    	| Retorna los tipos de prodcutos específicos agrupados por los tipos de productos generales,
    	| en un array donde el indice es el id del tipo general y su contenido otro array con los tipos específicos que le corresponden
    	| adicional se agrega en el contenido el nombre del tipo general
    	| ej:
    	| Array(1 => Array(
    	|       'nombre_tipo_producto_general' => 'SOFTWARE Y MAS COSAS...'
    	|       'tipos_productos_especificos' => Array(TipoProdcutoEspecifico1, TipoProdcutoEspecifico2,....)
    	|      ),
    	|       2 => Array(Otro tipo de producto general junto con sus tipos de prodcutos específicos...)
    	| )
    	*/                          
        public static function productos_especificos_x_prod_general(){
            
            $tipos_productos_generales = TipoProductoGeneral::all();
            $respuesta = array();
            foreach($tipos_productos_generales as $tipo_producto_general){
                $respuesta[$tipo_producto_general->id] = array(
                    'nombre_tipo_producto_general' => $tipo_producto_general->nombre,
                    'tipos_productos_especificos' => TipoProductoEspecifico::where('id_tipo_producto_general', '=', $tipo_producto_general->id)->get()
                    );
            }
            return $respuesta;
        }
    
    }

?>
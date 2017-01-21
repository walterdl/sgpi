<?php
    
    class GestionProyectosController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| listar()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de gestión de proyectos para el usuario investigador actual
    	*/
        public function listar_proyectos_investigador_principal(){
            
 
            // provee estilos personalizados para la vista a cargar
            $styles = [
                'vendor/ngAnimate/ngAnimate.css',
                'vendor/datatables/dataTables.bootstrap.css',
                'vendor/angular-datatables/css/angular-datatables.min.css',
                'vendor/angular-datatables/plugins/bootstrap/datatables.bootstrap.min.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                ]; 
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/angular-datatables/angular-datatables.min.js',
                'vendor/angular-datatables/plugins/bootstrap/angular-datatables.bootstrap.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                ];

                
            $post_scripts = [
                'investigador/proyectos/listar/listar_proyectos_controller.js',
                'investigador/proyectos/listar/productos_controller.js',
                'investigador/proyectos/listar/gastos_controller.js',
                'investigador/proyectos/listar/gastos_personal_controller.js',
                'investigador/proyectos/listar/gastos_equipos_controller.js',
                ];
            
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ngFileUpload', 'ui.bootstrap', 'datatables', 'datatables.bootstrap'];
            
            return View::make('investigador.proyectos.listar.listar', array(
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }

    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_investigador_principal()
    	|--------------------------------------------------------------------------
    	| Retorno json con los grupos de investigación del investigador principal identificado por su id usuario
    	*/        
        public function proyectos_investigador_principal(){
            
            try{
                $proyectos = Proyecto::proyectos_investigador_principal(Input::get('id_usuario'));
                return json_encode(array(
                    'proyectos' => $proyectos,
                    'consultado' => 1
                    ));
            }
            catch(Exception $e){
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));
            }
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| productos_de_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorno json con los productos de un proyecto determinado
    	*/                     
        public function productos_de_proyecto(){
            
            if(empty(Input::get('id_proyecto', null)))
                throw Exception('Error al consultar roductos de un proyecto. Identificador de proyecto inválido');
            try{
                return json_encode(array(
                    'consultado' => 1,
                    'productos' => Proyecto::productos_de_proyecto(Input::get('id_proyecto'))
                    ));
            }    
            catch(Exception $e){
                // throw $e;
                return json_encode(array(
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_producto_fecha_proyectada_radicacion()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de producto relacionado con la fecha proyectada de radicación
    	| Elimina el archivo del sistema de ficheros si el producto ya cuenta con un archivo tal
    	| Responde con json a la operación
    	*/          
        public function guardar_producto_fecha_proyectada_radicacion(){
             
            try{
                // primero aplica validaciones a los datos enviados
                
                // se valida id de producto
                if(is_null(Input::get('id_producto', null))){
                    throw new Exception('Identificador de prodcuto de proyecto inválido');
                }
                $validacion = Validator::make(
                    array('id_producto' => Input::get('id_producto')),
                    array('id_producto' => 'exists:productos,id')
                );
                if($validacion->fails()){
                    throw new Exception('Identificador de prodcuto de proyecto inválido. El producto no existe');
                }
                
                // se valida archivo
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de producto proyectado a publicar inválido');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de producto proyectado a publicar inválido. Tamaño maximo 20 MB');
                }
                
                // todas las validaciones son correctas. Se inicia transacción donde
                // Se consulta si el producto ya tiene archivo. Si lo tiene:
                // a-Se elimina el actual archivo
                // b-Se copia nuevo archivo y se actualiza registro en BD
                // Si no se tiene archivo:
                // a-Se crea registro PostulacionPublicacion
                // b-Se copia archivo enviado y se actualiza registro PostulacionPublicacion con el nombre del archivo copiado
                DB::transaction(function ()
                {
                    // consulta si ya existe un registro relacionado con la fecha proyectad a apostular
                    $postulacion_publicacion = PostulacionPublicacion::where('id_producto', '=', Input::get('id_producto'))
                    ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Proyectado')->first()->id)->first();
                    
                    if($postulacion_publicacion) // ya tiene registro relacionado con la fecha. Se asegura si el archivo existe
                    {
                        if(file_exists(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)))
                        {
                            // el archivo existe, se elimina el archivo
                            unlink(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)); // borra archivo
                        }
                        // copia archivo
                        $archivo_copiado = Archivo::copiar_documento_producto(Input::file("archivo"), $postulacion_publicacion->id);
                        
                        // actualiza campo archivo, guarda cambios y retorna respuesta
                        $postulacion_publicacion->archivo = $archivo_copiado->getFilename();
                        if(!empty(Input::get('descripcion')))
                            $postulacion_publicacion->descripcion = Input::get('descripcion');
                        $postulacion_publicacion->save();
                    }
                    else // no se tiene registro, se crea nuevo
                    {
                        $postulacion_publicacion = new PostulacionPublicacion();
                        $postulacion_publicacion->id_producto = Input::get('id_producto');
                        $postulacion_publicacion->id_tipo_postulacion_publicacion = TipoPostulacionPublicacion::where('nombre', '=', 'Proyectado')->first()->id;
                        
                        if(!empty(Input::get('descripcion')))
                            $postulacion_publicacion->descripcion = Input::get('descripcion');
                            
                        $postulacion_publicacion->save();
                        $archivo_copiado = Archivo::copiar_documento_producto(Input::file("archivo"), $postulacion_publicacion->id);
                        
                        if($archivo_copiado){
                            $postulacion_publicacion->archivo = $archivo_copiado->getFilename();
                            $postulacion_publicacion->save();
                        }
                        else{
                            throw new Exception('Error al copiar el archivo del producto a postular');
                        }
                    }
                });
                
                // operación exitosa
                return json_encode(array(
                    'consultado' => 1,
                    ));                
            }
            catch(\Exception $e){
                return json_encode(array(
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_producto_fecha_proyectada_postular()
    	|--------------------------------------------------------------------------
    	| Consulta la existencia de archivo de producto relacionado con la fecha proyetada de postulación
    	| Respondo json con el resultadod e la consulta
    	*/                  
        public function consultar_producto_fecha_proyectada_postular(){
            
            try{
                if(is_null(Input::get('id_producto', null)))
                    throw new Exception('Identificador de prodcuto inválido');
                    
                $validacion = Validator::make(
                    ['id_producto' => Input::get('id_producto')],
                    ['id_producto' => 'exists:productos,id']
                    );
                    
                if($validacion->fails())
                    throw new Exception('Identificador de prodcuto inválido. No existe el producto.');
                    
                $postulacion_publicacion = PostulacionPublicacion::
                    where('id_producto', '=', Input::get('id_producto'))
                    ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Proyectado')->first()->id)
                    ->first();
                    
                if($postulacion_publicacion){
                    if(file_exists(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)))
                        return json_encode([
                            'consultado' => 1,
                            'existe_archivo' => 1,
                            'nombre_archivo' => $postulacion_publicacion->archivo
                            ]);
                    else
                        return json_encode([
                            'consultado' => 1,
                            'existe_archivo' => 0
                            ]);                                 
                }
                else
                    return json_encode([
                        'consultado' => 1,
                        'existe_archivo' => 0
                        ]);                
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_producto_fecha_publicacion()
    	|--------------------------------------------------------------------------
    	| Consulta la existencia de archivo de producto relacionado con la fecha de publicación
    	| Respondo json con el resultadod e la consulta
    	*/          
        public function consultar_producto_fecha_publicacion(){
            
            try{
                if(is_null(Input::get('id_producto', null)))
                    throw new Exception('Identificador de prodcuto inválido');
                    
                $validacion = Validator::make(
                    ['id_producto' => Input::get('id_producto')],
                    ['id_producto' => 'exists:productos,id']
                    );
                    
                if($validacion->fails())
                    throw new Exception('Identificador de prodcuto inválido. No existe el producto.');
                    
                $postulacion_publicacion = PostulacionPublicacion::
                    where('id_producto', '=', Input::get('id_producto'))
                    ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Publicado')->first()->id)
                    ->first();
                    
                if($postulacion_publicacion){
                    if(file_exists(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)))
                        return json_encode([
                            'consultado' => 1,
                            'existe_archivo' => 1,
                            'nombre_archivo' => $postulacion_publicacion->archivo
                            ]);
                    else
                        return json_encode([
                            'consultado' => 1,
                            'existe_archivo' => 0
                            ]);                                 
                }
                else
                    return json_encode([
                        'consultado' => 1,
                        'existe_archivo' => 0
                        ]);                
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_producto_fecha_publicacion()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de producto relacionado con la fecha de publicación
    	| Elimina el archivo del sistema de ficheros si el producto ya cuenta con un archivo tal
    	| Responde con json a la operación
    	*/          
        public function guardar_producto_fecha_publicacion(){
             
            try{
                // primero aplica validaciones a los datos enviados
                
                // se valida id de producto
                if(is_null(Input::get('id_producto', null))){
                    throw new Exception('Identificador de prodcuto de proyecto inválido');
                }
                $validacion = Validator::make(
                    array('id_producto' => Input::get('id_producto')),
                    array('id_producto' => 'exists:productos,id')
                );
                if($validacion->fails()){
                    throw new Exception('Identificador de prodcuto de proyecto inválido. El producto no existe');
                }
                
                // se valida archivo
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de producto publicado');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de producto publicado inválido. Tamaño maximo 20 MB');
                }
                
                // todas las validaciones son correctas. Se inicia transacción donde
                // Se consulta si el producto ya tiene archivo. Si lo tiene:
                // a-Se elimina el actual archivo
                // b-Se copia nuevo archivo y se actualiza registro en BD
                // Si no se tiene archivo:
                // a-Se crea registro PostulacionPublicacion
                // b-Se copia archivo enviado y se actualiza registro PostulacionPublicacion con el nombre del archivo copiado
                DB::transaction(function ()
                {
                    // consulta si ya existe un registro relacionado con la fecha proyectad a apostular
                    $postulacion_publicacion = PostulacionPublicacion::where('id_producto', '=', Input::get('id_producto'))
                    ->where('id_tipo_postulacion_publicacion', '=', TipoPostulacionPublicacion::where('nombre', '=', 'Publicado')->first()->id)->first();
                    
                    if($postulacion_publicacion) // ya tiene registro relacionado con la fecha. Se asegura si el archivo existe
                    {
                        if(file_exists(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)))
                        {
                            // el archivo existe, se elimina el archivo
                            unlink(storage_path('archivos/productos/'.$postulacion_publicacion->archivo)); // borra archivo
                        }
                        // copia archivo
                        $archivo_copiado = Archivo::copiar_documento_producto(Input::file("archivo"), $postulacion_publicacion->id);
                        
                        // actualiza campo archivo, guarda cambios y retorna respuesta
                        $postulacion_publicacion->archivo = $archivo_copiado->getFilename();
                        if(!empty(Input::get('descripcion')))
                            $postulacion_publicacion->descripcion = Input::get('descripcion');
                        $postulacion_publicacion->save();
                    }
                    else // no se tiene registro, se crea nuevo
                    {
                        $postulacion_publicacion = new PostulacionPublicacion();
                        $postulacion_publicacion->id_producto = Input::get('id_producto');
                        $postulacion_publicacion->id_tipo_postulacion_publicacion = TipoPostulacionPublicacion::where('nombre', '=', 'Publicado')->first()->id;
                        
                        if(!empty(Input::get('descripcion')))
                            $postulacion_publicacion->descripcion = Input::get('descripcion');
                            
                        $postulacion_publicacion->save();
                        $archivo_copiado = Archivo::copiar_documento_producto(Input::file("archivo"), $postulacion_publicacion->id);
                        
                        if($archivo_copiado){
                            $postulacion_publicacion->archivo = $archivo_copiado->getFilename();
                            $postulacion_publicacion->save();
                        }
                        else{
                            throw new Exception('Error al copiar el archivo del producto publicado');
                        }
                    }
                });
                
                // operación exitosa
                return json_encode(array(
                    'consultado' => 1,
                    ));                
            }
            catch(\Exception $e){
                return json_encode(array(
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));
            }
                    
        }
     
    	/*
    	|--------------------------------------------------------------------------
    	| gastos_de_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorno json con los gastos de un proyecto determinado
    	*/                     
        public function gastos_de_proyecto(){
            
            try{
                $gastos = Gasto::consultar_gastos_proyecto(Input::get('id_proyecto'));
                return json_encode([
                    'consultado' => 1,
                    'gastos' => $gastos
                    ]);
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode(array(
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));                
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| revision_desembolso_personal()
    	|--------------------------------------------------------------------------
    	| Retorno json con la consulta de estado de revisión de un determinado desembolso personal
    	| Además retorna nombre de archivo de desembolso si se ha cargado
    	*/          
        public function revision_desembolso_personal(){
            
            // aplica validaciones al identificador de detalle gasto
            try{
                if(is_null(Input::get('id_detalle_gasto', null)))
                    throw new Exception('Identificador de detalle de gasto personal inválido');
                    
                $validacion = Validator::make(
                    ['id_detalle_gasto' => Input::get('id_detalle_gasto')],
                    ['id_detalle_gasto' => 'exists:detalles_gastos,id']
                    );
                    
                if($validacion->fails())                
                    throw new Exception('Identificador de detalle de gasto personal inválido');
                    
                return json_encode([
                    'consultado' => 1,
                    'desembolso' => Desembolso::consultar_desembolso_gasto_personal(Input::get('id_detalle_gasto'))
                    ]);
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_desembolso_gasto_personal()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de desembolso de gasto personal
    	| Elimina el archivo del sistema de ficheros si el gasto personal ya cuenta con un archivo tal
    	| Responde con json a la operación
    	*/                  
        public function guardar_desembolso_gasto_personal(){
            
            try{
                // aplica validación básica al identificador del detalle gasto personal
                if(is_null(Input::get('id_detalle_gasto', null)))
                    throw new Exception('Identificador de detalle de gasto personal inválido');
                    
                $validacion = Validator::make(
                    ['id_detalle_gasto' => Input::get('id_detalle_gasto')],
                    ['id_detalle_gasto' => 'exists:detalles_gastos,id']
                    );
                    
                if($validacion->fails())                
                    throw new Exception('Identificador de detalle de gasto personal inválido');
                    
                // se valida archivo
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de desembolso inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de desembolso inválido. Tamaño maximo 20 MB');
                }
                
                // todas las validaciones son correctas. Se inicia transacción donde
                // Se consulta si el detalle del gasto personal ya tiene un desembolso. Si lo tiene:
                // a-se valida que el detallegasto no esté aprobado
                // a-Se elimina el actual archivo
                // b-Se copia nuevo archivo y se actualiza registro en BD
                // Si no se tiene archivo:
                // a-Se crea registro Desembolso
                // b-Se copia archivo enviado y se actualiza registro Desembolso con el nombre del archivo copiado
                DB::transaction(function ()
                {
                    $desembolso = Desembolso::where('id_detalle_gasto', '=', Input::get('id_detalle_gasto'))->first();
                    if($desembolso){
                        // tiene desembolso, se verifica que no este aprobado para evitar la sobrescritura de un archivo validado
                        if($desembolso->aprobado)
                        {
                            // desembolos aprobado, se evita sobrescirtura 
                            throw new Exception('Carga de archivo de desembolso rechazada; desembolso ya aprobado');
                        }
                        else
                        {
                            // existe desembolso pero no se encuentra aprobado, se perimite sobrescirtura de archivo
                            
                            unlink(storage_path('archivos/desembolsos/'.$desembolso->archivo)); // borra archivo
                            
                            // copia archivo
                            $archivo_copiado = Archivo::copiar_desembolso(Input::file("archivo"), $desembolso->id);
                            
                            if($archivo_copiado){
                                // actualiza campo archivo, guarda cambios y retorna respuesta
                                $desembolso->archivo = $archivo_copiado->getFilename();
                                if(!empty(Input::get('comentario')))
                                    $desembolso->comentario_investigador = Input::get('comentario');
                                $desembolso->save();                            
                            }
                            else{
                                throw new Exception('Error al copiar el archivo de desembolso');
                            }                                  
                        }
                    }
                    else{
                        // no se ha cargado desembolso aún
                        $desembolso = new Desembolso();
                        $desembolso->id_detalle_gasto = Input::get('id_detalle_gasto');
                        $desembolso->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Desembolso')->first()->id;
                        
                        if(!empty(Input::get('comentario')))
                            $desembolso->comentario_investigador = Input::get('comentario');
                        $desembolso->save();
                        
                        // copia archivo de desembolso
                        $archivo_copiado = Archivo::copiar_desembolso(Input::file("archivo"), $desembolso->id);
                        
                        if($archivo_copiado){
                            // actualiza campo archivo, guarda cambios y retorna respuesta
                            $desembolso->archivo = $archivo_copiado->getFilename();
                            $desembolso->save();
                        }
                        else{
                            throw new Exception('Error al copiar el archivo de desembolso');
                        }                        
                    }
                });
                
                // operación exitosa
                return json_encode(array(
                    'consultado' => 1,
                    ));                                
            }
            catch(\Exception $e){
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                                
            }
        }
    }
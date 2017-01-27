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
                'investigador/proyectos/listar/gastos_software_controller.js',
                'investigador/proyectos/listar/gastos_salidas_campo_controller.js',
                'investigador/proyectos/listar/gastos_materiales_controller.js',
                'investigador/proyectos/listar/gastos_servicios_controller.js',
                'investigador/proyectos/listar/gastos_bibliograficos_controller.js',
                'investigador/proyectos/listar/gastos_digitales_controller.js',
                'investigador/proyectos/listar/informe_avance_controller.js',
                'investigador/proyectos/listar/final_proyecto_controller.js',
                'investigador/proyectos/listar/prorroga_controller.js',
                'investigador/proyectos/listar/mas_info_proyecto_controller.js',
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
                throw $e;
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
                            'nombre_archivo' => $postulacion_publicacion->archivo,
                            'descripcion' => $postulacion_publicacion->descripcion,
                            'updated_at' => $postulacion_publicacion->updated_at->format('Y-m-d')
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
                            'nombre_archivo' => $postulacion_publicacion->archivo,
                            'descripcion' => $postulacion_publicacion->descripcion,
                            'updated_at' => $postulacion_publicacion->updated_at->format('Y-m-d')
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
                throw $e;
                return json_encode(array(
                    'consultado' => 2,
                    'codigo' => $e->getCode(),
                    'mensaje' => $e->getMessage()
                    ));                
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| revision_desembolso()
    	|--------------------------------------------------------------------------
    	| Retorno json con la consulta de estado de revisión de un determinado desembolso
    	| Además retorna nombre de archivo de desembolso si se ha cargado
    	*/          
        public function revision_desembolso(){
            
            // aplica validaciones al identificador de detalle gasto
            try{
                if(is_null(Input::get('id_detalle_gasto', null)))
                    throw new Exception('Identificador de detalle de gasto inválido');
                    
                $validacion = Validator::make(
                    ['id_detalle_gasto' => Input::get('id_detalle_gasto')],
                    ['id_detalle_gasto' => 'exists:detalles_gastos,id']
                    );
                    
                if($validacion->fails())                
                    throw new Exception('Identificador de detalle de gasto inválido');
                    
                return json_encode([
                    'consultado' => 1,
                    'desembolso' => Desembolso::consultar_desembolso(Input::get('id_detalle_gasto'))
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
    	| guardar_desembolso()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de desembolso de cualquier tipo de gasto
    	| Elimina el archivo del sistema de ficheros si el detalle de gasto ya cuenta con un archivo tal
    	| Responde con json a la operación
    	*/                  
        public function guardar_desembolso(){
            
            try{
                // abstrae tipo de gasto para presentar mensajes de excepción más explícitos:
                $tipo_gasto = Input::get('tipo_gasto', null);
                if($tipo_gasto == 'personal'){}
                else if($tipo_gasto == 'equipo'){}
                else if($tipo_gasto == 'software'){}
                else if($tipo_gasto == 'salidas'){$tipo_gasto = 'de salida de campo';}
                else if($tipo_gasto == 'materiales'){$tipo_gasto = 'de materiales y suministros';}
                else if($tipo_gasto == 'servicios'){$tipo_gasto = 'de servicios técnicos';}
                else if($tipo_gasto == 'bibliograficos'){$tipo_gasto = 'de recursos bibliográficos';}
                else if($tipo_gasto == 'digitales'){$tipo_gasto = 'de recursos digitales';}
                else{
                    $tipo_gasto = '';
                }
                    
                // aplica validación básica al identificador del detalle gasto personal
                if(is_null(Input::get('id_detalle_gasto', null)))
                    throw new Exception('Identificador de detalle de gasto '.$tipo_gasto.' inválido');
                    
                $validacion = Validator::make(
                    ['id_detalle_gasto' => Input::get('id_detalle_gasto')],
                    ['id_detalle_gasto' => 'exists:detalles_gastos,id']
                    );
                    
                if($validacion->fails())                
                    throw new Exception('Identificador de detalle de gasto '.$tipo_gasto.' inválido');
                    
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
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_informe_avance()
    	|--------------------------------------------------------------------------
    	| Consulta el informe de avance y el estado de revisión del mismo de un determinado proyecto
    	*/          
        public function consultar_informe_avance(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');
                    
                // consulta por el registro de informe de avance del proyecto
                $informe_avance = DocumentoProyecto::where('id_proyecto', '=', Input::get('id_proyecto'))
                    ->where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first()->id)->first();
                    
                // calcula la fecha de mitad de proyecto que le corresponde al informe de avance
                $proyecto = Proyecto::find(Input::get('id_proyecto'));
                $duracion_meses_dividida = $proyecto->duracion_meses / 2;
                $fecha_mitad_proyecto = date_create_from_format('Y-m-d', $proyecto->fecha_inicio);
                $fecha_mitad_proyecto->modify('+'.$duracion_meses_dividida.' month');
                $fecha_mitad_proyecto = $fecha_mitad_proyecto->format('Y-m-d');
                
                if($informe_avance){
                    // hay informe de avance para el proyecto
                    return json_encode([
                        'consultado' => 1,
                        'informe_avance' => $informe_avance,
                        'fecha_mitad_proyecto' => $fecha_mitad_proyecto
                        ]);
                }
                else{
                    // no hay informe de avance para el proyecto
                    return json_encode([
                        'consultado' => 1,
                        'informe_avance' => null,
                        'fecha_mitad_proyecto' => $fecha_mitad_proyecto
                        ]);
                }
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }
                
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_informe_avance()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de informe de avance de un determinado proyecto
    	| Elimina el archivo del sistema de ficheros si el proyecto ya cuenta con un informe de avance
    	| Responde con json a la operación
    	*/          
        public function guardar_informe_avance(){
            
            try{
                // aplica validaciones al identificador del proyecto y al archivo enviado
                // aplica validación básica al identificador del detalle gasto personal
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido. No se ha enviado identificador.');
                    
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                    
                if($validacion->fails())                
                    throw new Exception('Identificador de proyecto inválido. No se encuentra proyecto con el identificador');
                    
                // se valida archivo
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de informe de avance inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de informe de avance inválido. Tamaño maximo 20 MB');
                }                
                
                // todas las validaciones son correctas. Se inicia transacción donde
                // Se consulta si el proyecto tiene un informe de avance cargado. Si lo tiene:
                // a-se valida que el informe de avance no esté aprobado
                // b-Se elimina el actual archivo
                // c-Se copia nuevo archivo y se actualiza registro en BD
                // Si no se tiene archivo:
                // a-Se crea registro de informe de avance
                // b-Se copia archivo enviado y se actualiza registro con el nombre del archivo copiado
                DB::transaction(function ()
                {
                    $doc_informe_avance = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first()->id)
                    ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                    
                    if($doc_informe_avance){
                        // tiene informe de avance cargado, se verifica que no este aprobado para evitar la sobrescritura de un archivo validado
                        if($doc_informe_avance->aprobado)
                        {
                            // desembolos aprobado, se evita sobrescirtura 
                            throw new Exception('Carga rechazada de archivo de informe de avance; informe ya aprobado');
                        }
                        else
                        {
                            // existe informe de avance pero no se encuentra aprobado, se perimite sobrescirtura de archivo
                            
                            unlink(storage_path('archivos/informes_avance/'.$doc_informe_avance->archivo)); // borra archivo
                            
                            // copia archivo
                            $archivo_copiado = Archivo::copiar_informe_avance(Input::file("archivo"), $doc_informe_avance->id);
                            
                            if($archivo_copiado){
                                // actualiza campo archivo, guarda cambios y retorna respuesta
                                $doc_informe_avance->archivo = $archivo_copiado->getFilename();
                                if(!empty(Input::get('comentario')))
                                    $doc_informe_avance->comentario_investigador = Input::get('comentario');
                                $doc_informe_avance->save();                            
                            }
                            else{
                                throw new Exception('Error al copiar el archivo del informe de avance');
                            }                                  
                        }
                    }
                    else{
                        // no se ha cargado desembolso aún
                        $doc_informe_avance = new DocumentoProyecto();
                        $doc_informe_avance->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first()->id;
                        $doc_informe_avance->id_proyecto = Input::get('id_proyecto');
                        
                        
                        if(!empty(Input::get('comentario')))
                            $doc_informe_avance->comentario_investigador = Input::get('comentario');
                        $doc_informe_avance->save();                            
                        
                        // copia archivo de informe avance
                        $archivo_copiado = Archivo::copiar_informe_avance(Input::file("archivo"), $doc_informe_avance->id);
                        
                        if($archivo_copiado){
                            // actualiza campo archivo, guarda cambios y retorna respuesta
                            $doc_informe_avance->archivo = $archivo_copiado->getFilename();
                            $doc_informe_avance->save();
                        }
                        else{
                            throw new Exception('Error al copiar el archivo del informe de avance');
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
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_final_proyecto()
    	|--------------------------------------------------------------------------
    	| Consulta los registros de final de proyecto y el estado de revisión del mismo de un determinado proyecto
    	*/          
        public function consultar_final_proyecto(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');                
                    
                // consulta registros relacionados con final de proyecto, esto es acta de finalizacion y memoria académica
                $acta_finalizacion = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first()->id)
                    ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                $memoria_academica = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first()->id)
                    ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                    
                // si algun archivo no se encuentra se considera que el proyecto no tiene final de proyecto diligenciado
                if(is_null($acta_finalizacion) || is_null($memoria_academica)){
                    
                    return json_encode([
                        'consultado' => 1,
                        'fecha_final_proyecto' => Proyecto::find(Input::get('id_proyecto'))->fecha_fin,
                        'final_proyecto' => null
                        ]);
                }
                else{
                    return json_encode([
                        'consultado' => 1,
                        'fecha_final_proyecto' => Proyecto::find(Input::get('id_proyecto'))->fecha_fin,
                        'final_proyecto' => [
                            'comentario_investigador' => $acta_finalizacion->comentario_investigador,
                            'archivo_acta_finalizacion' => $acta_finalizacion->archivo,
                            'archivo_memoria_academica' => $memoria_academica->archivo,
                            'aprobado' => $acta_finalizacion->aprobado,
                            'comentario_revision' => $acta_finalizacion->comentario_revision
                            ],
                        ]);         
                }
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_final_proyecto()
    	|--------------------------------------------------------------------------
    	| Almancena los archivos de final de proyecto, esto es acta de finalizacion y memoria académica
    	| Elimina los archivos en caso de que existan si no se han aprobado
    	| Responde con json a la operación
    	*/              
        public function guardar_final_proyecto(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');                
                    
                // se valida archivo de acta de finalización
                if(!Input::hasFile('archivo_acta_finalizacion')){
                    throw new Exception('Archivo de acta de finalización inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo_acta_finalizacion")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de acta de finalización inválido. Tamaño maximo 20 MB');
                }                                    
                    
                // se valida archivo de memoria académica
                if(!Input::hasFile('archivo_memoria_academica')){
                    throw new Exception('Archivo de memoria académica inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo_memoria_academica")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de memoria académica inválido. Tamaño maximo 20 MB');
                }                                                        
                
                DB::transaction(function ()
                {
                    // consulta registros relacionados con final de proyecto, esto es acta de finalizacion y memoria académica
                    $acta_finalizacion = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first()->id)
                        ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                    $memoria_academica = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first()->id)
                        ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                        
                    // si algun archivo no se encuentra se considera que el proyecto no tiene final de proyecto diligenciado
                    if(is_null($acta_finalizacion) || is_null($memoria_academica)){
                        
                        // el proyecto no tiene final de proyecto diligenciado
                        // se crean ambos registros
                        // como la entrada a este código sucede si algun registro no existe, se asegura si existe un documento especifico solamente
                        // si existe lo elimina. Esto con el fin de crear registros realmente nuevos
                        if(isset($acta_finalizacion)){
                            unlink(storage_path('archivos/actas_finalizacion/'.$acta_finalizacion->archivo)); //borra el archivo que le corresponde
                            DocumentoProyecto::find($acta_finalizacion->id)->forceDelete();
                        }
                        else if(isset($memoria_academica)){
                            unlink(storage_path('archivos/memorias_academicas/'.$memoria_academica->archivo)); //borra el archivo que le corresponde
                            DocumentoProyecto::find($memoria_academica->id)->forceDelete();
                        }
                        
                        // crea registro de acta de finalizacion 
                        $acta_finalizacion = new DocumentoProyecto();
                        $acta_finalizacion->id_proyecto = Input::get('id_proyecto');
                        $acta_finalizacion->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first()->id;
                        if(!empty(Input::get('comentario', null))){
                            $acta_finalizacion->comentario_investigador = Input::get('comentario');
                        }
                        $acta_finalizacion->save();
                        // copia archivo de acta de finalizacion
                        $archivo_copiado = Archivo::copiar_acta_finalizacion(Input::file("archivo_acta_finalizacion"), $acta_finalizacion->id);                    
                        if($archivo_copiado){
                            $acta_finalizacion->archivo = $archivo_copiado->getFilename();
                            $acta_finalizacion->save();
                        }
                        else
                            throw new Exception('Error al copiar el archivo de acta de finalización');
                            
                        // crea registro de memoria académica
                        $memoria_academica = new DocumentoProyecto();
                        $memoria_academica->id_proyecto = Input::get('id_proyecto');
                        $memoria_academica->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first()->id;                    
                        if(!empty(Input::get('comentario', null))){
                            $memoria_academica->comentario_investigador = Input::get('comentario');
                        }
                        $memoria_academica->save();
                        // copia archivo de memoria académica
                        $archivo_copiado = Archivo::copiar_memoria_academica(Input::file("archivo_memoria_academica"), $memoria_academica->id);                    
                        if($archivo_copiado){
                            $memoria_academica->archivo = $archivo_copiado->getFilename();
                            $memoria_academica->save();
                        }
                        else
                            throw new Exception('Error al copiar el archivo de memoria académica');                    
                    }
                    else{
                        // el proyecto tiene final de proyecto diligenciado
                        // se valida que ni el memorando ni la acta de finalizacion esten aprobados
                        if($acta_finalizacion->aprobado || $memoria_academica->aprobado)
                            throw new Exception('Los documentos de final de proyectos ya se encuentran aprobados');
                        
                        // archivos no aprobados, se perimite sobrescritura
                        

                        // borra archivo de acta de finalizacion actual
                        unlink(storage_path('archivos/actas_finalizacion/'.$acta_finalizacion->archivo));
                        // copia nuevo archivo de actia de finalizacion
                        $archivo_acta_finalizacion_copiado = Archivo::copiar_acta_finalizacion(Input::file("archivo_acta_finalizacion"), $acta_finalizacion->id);        
                        
                        // borra archivo de acta de memoria académica
                        unlink(storage_path('archivos/memorias_academicas/'.$memoria_academica->archivo));                        
                        // copia nuevo archivo de memoria académica
                        $archivo_memoria_academica_copiado = Archivo::copiar_memoria_academica(Input::file("archivo_memoria_academica"), $memoria_academica->id);                                
                        
                        // obtiene modelo de acta de inicio que permite actualizar el registro
                        $acta_finalizacion = DocumentoProyecto::find($acta_finalizacion->id);
                        // obtiene modelo de memoria académica que permite actualizar el registro
                        $memoria_academica = DocumentoProyecto::find($memoria_academica->id);                        
                        
                        if($archivo_acta_finalizacion_copiado && $archivo_memoria_academica_copiado){ // si ambos archivos se han copiado
                        
                            // actualiza registros, copaindo el comentario del investigador si existe
                            if(!empty(Input::get('comentario', null))){
                                $acta_finalizacion->comentario_investigador = Input::get('comentario');
                                $memoria_academica->comentario_investigador = Input::get('comentario');
                            }
                            
                            $acta_finalizacion->archivo = $archivo_copiado->getFilename();
                            $memoria_academica->archivo = $archivo_copiado->getFilename();
                            $acta_finalizacion->save();
                            $memoria_academica->save();
                        }
                        else
                            throw new Exception('Error al copiar los archivos de final de proyecto');                        
                    }
                });
                
                return json_encode([
                    'consultado' => 1
                    ]);                      
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| consultar_prorroga()
    	|--------------------------------------------------------------------------
    	| Consulta los registros de prórroga y el estado de revisión del mismo de un determinado proyecto
    	*/          
        public function consultar_prorroga(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');                
                    
                // consulta el registro de la prórroga del proyecto
                $prorroga = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Prorroga')->first()->id)
                    ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                    
                // si algun archivo no se encuentra se considera que el proyecto no tiene final de proyecto diligenciado
                if(is_null($prorroga)){
                    return json_encode([
                        'consultado' => 1,
                        'prorroga' => null,
                        ]);
                }
                else{
                    return json_encode([
                        'consultado' => 1,
                        'prorroga' => $prorroga
                        ]);         
                }
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| guardar_prorroga()
    	|--------------------------------------------------------------------------
    	| Almancena el archivo de prorroga, 
    	| Elimina los archivos en caso de que existan si no se han aprobado
    	| Responde con json a la operación
    	*/              
        public function guardar_prorroga(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');                                
                    
                // se valida archivo de prórroga
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de prórroga inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo_acta_finalizacion")),
                    array('archivo' => 'max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de acta de prórroga inválido. Tamaño maximo 20 MB');
                }                     
                
                DB::transaction(function ()
                {
                    // consulta el registro de la prórroga del proyecto
                    $prorroga = DocumentoProyecto::where('id_formato_tipo_documento', '=', FormatoTipoDocumento::where('nombre', '=', 'Prorroga')->first()->id)
                        ->where('id_proyecto', '=', Input::get('id_proyecto'))->first();
                    if($prorroga){
                        // el proyecto ya tiene prorroga cargada, se valida que la prórroga no se encuentre aprobada para evitar una sobrescritura de archivos aprobados
                        if($prorroga->aprobado)
                            throw new Exception('La solicitud de prórroga del proyecto  ya se encuentra aprobada.');
                            
                        // la prórroga no se encuentra aporbada, se permite sobrescritura
                        unlink(storage_path('archivos/prorrogas/'.$prorroga->archivo)); // borra archivo de prorroga
                        
                        // consulta modelo de prorroga ORM que permite actualizar registro
                        $prorroga = DocumentoProyecto::find($prorroga->id);
                        
                        // copia archivo y actualiza campo archivo del reigstro de la prórroga
                        $archivo_copiado = Archivo::copiar_prorroga(Input::file("archivo"), $prorroga->id);
                        if($archivo_copiado){
                            $prorroga->archivo = $archivo_copiado->getFilename();
                            // copia comentario si esta establecido
                            if(!empty(Input::get('comentario', null)))
                                $prorroga->comentario_investigador = Input::get('comentario');
                            $prorroga->save(); // actualiza registro
                        }
                        else
                            throw new Exception('Error al copiar archivo de prórroga de proyecto');
                    }
                    else{
                        // no se tiene prórroga, se crea reigstros y carga archivos de prórroga nuevo
                        $prorroga = new DocumentoProyecto();
                        $prorroga->id_formato_tipo_documento = FormatoTipoDocumento::where('nombre', '=', 'Prorroga')->first()->id;
                        $prorroga->id_proyecto = Input::get('id_proyecto');
                        if(!empty(Input::get('comentario', null)))
                            $prorroga->comentario_investigador = Input::get('comentario');
                            
                        $prorroga->save();
                        
                        $archivo_copiado = Archivo::copiar_prorroga(Input::file("archivo"), $prorroga->id);
                        if($archivo_copiado){   
                            $prorroga->archivo = $archivo_copiado->getFilename();
                            $prorroga->save();
                        }
                        else
                            throw new Exception('Error al copiar archivo de prórroga de proyecto');
                    }
                });
                
                return json_encode([
                    'consultado' => 1
                    ]);                
            }
            catch(\Exception $e){
                // throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| mas_info_proyecto()
    	|--------------------------------------------------------------------------
    	| Punto de entrada para la solicitud ajax de más información de proyecto
    	*/          
        public function mas_info_proyecto(){
            
            try{
                // valida identificador de proyecto enviado
                if(is_null(Input::get('id_proyecto', null)))
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no enviado');
                $validacion = Validator::make(
                    ['id_proyecto' => Input::get('id_proyecto')],
                    ['id_proyecto' => 'required|exists:proyectos,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de proyecto inválido; identificador de proyecto no encontrado');
                    
                $detalles_proyecto = Proyecto::detalles_proyecto(Input::get('id_proyecto'));
                
                $respuesta = ['consultado' => 1];
                foreach($detalles_proyecto as $key => $value){
                    $respuesta[$key] = $value;
                }
                
                return json_encode($respuesta);
            }
            catch(\Exception $e){
                throw $e;
                return json_encode([
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ]);                      
            }            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| listar_proyectos_administrador()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de gestión de proyectos para el usuario investigador
    	*/                  
        public function listar_proyectos_administrador(){
            
            // provee estilos personalizados para la vista
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
                'administrador/proyectos/listar/listar_proyectos_controller.js',
                'administrador/proyectos/listar/mas_info_proyecto_controller.js',
                'administrador/proyectos/listar/productos_controller.js',
                'administrador/proyectos/listar/gastos_controller.js',
                'administrador/proyectos/listar/gastos_personal_controller.js',
                'administrador/proyectos/listar/gastos_equipos_controller.js',
                'administrador/proyectos/listar/gastos_software_controller.js',
                'administrador/proyectos/listar/gastos_salidas_campo_controller.js',
                'administrador/proyectos/listar/gastos_materiales_controller.js',
                'administrador/proyectos/listar/gastos_servicios_controller.js',
                'administrador/proyectos/listar/gastos_bibliograficos_controller.js',
                'administrador/proyectos/listar/gastos_digitales_controller.js',
                ];
            
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ngFileUpload', 'ui.bootstrap', 'datatables', 'datatables.bootstrap'];
            
            return View::make('administrador.proyectos.listar.listar', array(
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| proyectos_administrador()
    	|--------------------------------------------------------------------------
    	| Retorno json con todos los proyectos de investigación.
    	*/          
        public function proyectos_administrador(){
            
            try{
                
                // verifica que el actual usuario sea administrador
                if(Auth::user()->id_rol != 1)
                    throw new Exception('Acceso denegado a los proyectos por falta de privilegios de usuario');
                    
                $proyectos = Proyecto::proyectos_administrador();
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
    	| guardar_revision_desembolso()
    	|--------------------------------------------------------------------------
    	| Guarda la revisión de desembolso de cualquier tipo de gasto de un determinado proyecto
    	*/          
        public function guardar_revision_desembolso(){
            
            try{               
                
                // se valida identificador de registro d edetalle gasto enviado
                if(is_null(Input::get('id_detalle_gasto', null)))
                    throw new Exception('Identificador de registro de gasto inválido. No se ha recibido tal registro');
                $validacion = Validator::make(
                    ['id_detalle_gasto' => Input::get('id_detalle_gasto')], 
                    ['id_detalle_gasto' => 'required|exists:detalles_gastos,id']);
                if($validacion->fails())
                    throw new Exception('Identificador de registro de gasto inválido. No se encuentra tal registro');
                
                // consulta desembolso
                $desembolso = Desembolso::where('id_detalle_gasto', '=', Input::get('id_detalle_gasto'))->first();
                
                $aprobado = null;
                // formatea valor de aprobado enviado
                if(Input::get('aprobado') == 'true')
                    $aprobado = 1;
                else if(Input::get('aprobado') == 'false')
                    $aprobado = 0;
                
                // aplica cambios a la revisión del desembolso
                $desembolso->aprobado = $aprobado;
                
                // agrega comentario de revision y codigo de aprobacion u orden de servicio si estan establecidos
                if(!is_null(Input::get('comentario_revision', null)))
                    $desembolso->comentario_revision = Input::get('comentario_revision');
                if(!is_null(Input::get('codigo_aprobacion', null)))
                    $desembolso->codigo_aprobacion = Input::get('codigo_aprobacion');
                    
                $desembolso->save(); // actualiza desembolso
                
                return json_encode([
                    'consultado' => 1
                    ]);
            }
            catch(\Exception $e){
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));                
            }
            
            // aprobado: $scope.desembolso.aprobado,
            // comentario_revision: $scope.desembolso.comentario_revision,
            // codigo_aprobacion: $scope.desembolso.codigo_aprobacion,            
        }
        
    }
    
    
    
    
    
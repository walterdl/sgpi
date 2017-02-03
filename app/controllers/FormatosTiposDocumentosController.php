<?php
    
    class FormatosTiposDocumentosController extends Controller {
        
        /*
        |--------------------------------------------------------------------------
        | listar_editar()
        |--------------------------------------------------------------------------
        | Presenta la vista de CRUD de formatos de tipos de documentos
        */
        public function listar_editar(){
            
            // provee estilos personalizados para la vista
            $styles = [
                'vendor/ngAnimate/ngAnimate.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                ];             
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/ng-file-upload/ng-file-upload-shim.js',
                'vendor/ng-file-upload/ng-file-upload.min.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                ];            
            $post_scripts = [
                'administrador/formatos_tipos_documentos/formatos_tipos_documentos_controller.js',
                ];
                
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ngFileUpload', 'ui.bootstrap'];
            
            return View::make('administrador.formatos_tipos_documentos.listar_editar', array(
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));                        
        }
        
        /*
        |--------------------------------------------------------------------------
        | guardar_nuevo_formato()
        |--------------------------------------------------------------------------
        | Guarda nuevo formato de tipo documento, eliminando archivo de formato previo y guardando el nuevo
        | Retorna respuesta json para confirmar resultado del proceso
        */        
        public function guardar_nuevo_formato(){
            
            try{
                
                // valida que se halla enviado archivo y un especificador de tipo de formato válido
                if(!Input::hasFile('archivo')){
                    throw new Exception('Archivo de formato inválido. No se ha cargado ningún archivo');
                }
                $validacion = Validator::make(
                    array('archivo' => Input::file("archivo")),
                    array('archivo' => 'required|max:20000') // unidad de medida predeterminada en Kylobytes. Aquí es 20MB
                );                
                if($validacion->fails()){
                    throw new Exception('Archivo de formato inválido. Tamaño maximo 20 MB');
                }
                
                // valida identificador de tipo de formato
                if(is_null(Input::get('formato', null)))
                    throw new Exception('Identificador de formato inválido. No se ha enviado tal dato');
                $validacion = Validator::make(
                    array('formato' => Input::get("formato")),
                    array('formato' => 'required|in:presupuesto,presentacion_proyecto,acta_inicio,informe_avance,desembolso,memoria_academica,acta_finalizacion') 
                );     
                if($validacion->fails())
                    throw new Exception('Identificador de formato inválido. El identificador no coincide con los aceptados');
                
                // obtiene el registro FormatoTipoDocumento actual
                $nombre_formato = Input::get('formato');
                if($nombre_formato == 'presupuesto')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Presupuesto')->first();
                else if($nombre_formato == 'presentacion_proyecto')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Presentacion proyecto')->first();
                else if($nombre_formato == 'acta_inicio')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Acta inicio')->first();                    
                else if($nombre_formato == 'informe_avance')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first();                    
                else if($nombre_formato == 'desembolso')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Desembolso')->first();                                        
                else if($nombre_formato == 'memoria_academica')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first();                                                            
                else if($nombre_formato == 'acta_finalizacion')
                    $formato_actual = FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first();                                                                            
                    
                // borra archivo de formato actual
                if(isset($formato_actual->archivo))
                    if(file_exists(storage_path('archivos/formatos_documentos/'.$formato_actual->archivo)))
                        unlink(storage_path('archivos/formatos_documentos/'.$formato_actual->archivo)); 
                    
                // copia archivo
                $archivo_copiado = Archivo::copiar_formato_tipo_documento(Input::file("archivo"));
                
                if($archivo_copiado){ // se copio el archivo correctamente
                
                    $formato_actual = FormatoTipoDocumento::find($formato_actual->id);
                
                    // actualiza campo archivo y actualiza registro
                    $formato_actual->archivo = $archivo_copiado->getFilename();
                        
                    $formato_actual->save();
                    
                    return json_encode([
                        'consultado' => 1
                        ]);
                }
                else
                    throw new Exception('Error al copiar nuevo archivo de formato. No se copio el archivo');
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
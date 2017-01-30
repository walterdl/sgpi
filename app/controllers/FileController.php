<?php
    
    /*
	|--------------------------------------------------------------------------
	| FileController
	|--------------------------------------------------------------------------
	| Controlador encargado de gestionar las peticiones de archivos e imagenes de perfil.
	| Todas las peticiones a las acciones de este controlador pasan por el filtro de autenticación.
	| El proceso principal de este controlador se basa de: http://laravel.io/forum/04-23-2015-securing-filesimages
	| Los archivos que gestiona se encuentran en app/storage/archivos/...
	*/        
    class FileController extends Controller {
        
        /*
    	|--------------------------------------------------------------------------
    	| get_imagen_perfil()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http con la solicitud a la imagen de perfil. 
    	| Si no se especifica el nombre del archivo de la imagen de perfil,
    	| se responde con una predeterminada.
    	*/    
        public function get_imagen_perfil($nombre_foto=null){
            
            if(isset($nombre_foto)){
                return Response::download(storage_path('archivos/imagenes_perfil/'.$nombre_foto), null, [], null);
            }
            else{
                return Response::download(storage_path('archivos/imagenes_perfil/sin-imagen.jpg'), null, [], null);
            }
        }

        /*
    	|--------------------------------------------------------------------------
    	| get_formato_documento()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar formato de documento guía
    	*/            
        public function get_formato_documento(){
            
            $nombre_formato = Input::get('nombre_formato');
            
            if($nombre_formato == 'presupuesto'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Presupuesto')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');                
            }
            else if($nombre_formato == 'presentacion_proyecto'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Presentacion proyecto')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');                                
            }
            else if($nombre_formato == 'acta_inicio'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Acta inicio')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');                                                
            }
            else if($nombre_formato == 'desembolso'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Desembolso')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');
            }            
            else if($nombre_formato == 'informe_avance'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Informe de avance')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');                
            }
            else if($nombre_formato == 'acta_finalizacion'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Acta finalizacion')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');                                
            }
            else if($nombre_formato == 'memoria_academica'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Memoria academica')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');
            }
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_presupuesto()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar presupuesto de proyecto
    	*/          
        public function get_presupuesto($nombre_archivo){
            return Response::download(storage_path('archivos/presupuestos/'.$nombre_archivo), null, [], 'attachment');                
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_presentacion_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar presentacion de proyecto
    	*/          
        public function get_presentacion_proyecto($nombre_archivo){
            return Response::download(storage_path('archivos/presentaciones_proyectos/'.$nombre_archivo), null, [], 'attachment');                
        }        
        
        /*
    	|--------------------------------------------------------------------------
    	| get_acta_inicio()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar acta de inicio de proyecto
    	*/          
        public function get_acta_inicio($nombre_archivo){
            return Response::download(storage_path('archivos/actas_inicio/'.$nombre_archivo), null, [], 'attachment');                
        }                
        
        /*
    	|--------------------------------------------------------------------------
    	| get_archivo_fecha_proyectada_radicacion()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de producto relacionado con
    	| la fecha proyectada de radicación
    	*/                    
        public function get_archivo_fecha_proyectada_radicacion($nombre_archivo){
            return Response::download(storage_path('archivos/productos/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_archivo_fecha_publicacion()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de producto relacionado con
    	| la fecha de publicación
    	*/          
        public function get_archivo_fecha_publicacion($nombre_archivo){
            return Response::download(storage_path('archivos/productos/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_desembolso()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de desembolso
    	*/                  
        public function get_desembolso($nombre_archivo){
            return Response::download(storage_path('archivos/desembolsos/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_informe_avance()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de informe de avance
    	*/          
        public function get_informe_avance($nombre_archivo){
            return Response::download(storage_path('archivos/informes_avance/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_acta_finalizacion()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de acta de finalización
    	*/          
        public function get_acta_finalizacion($nombre_archivo){
            return Response::download(storage_path('archivos/actas_finalizacion/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_memoria_academica()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de memoria académica
    	*/                  
        public function get_memoria_academica($nombre_archivo){
            return Response::download(storage_path('archivos/memorias_academicas/'.$nombre_archivo), null, [], 'attachment');
        }

        /*
    	|--------------------------------------------------------------------------
    	| get_prorroga()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de prorroga
    	*/                          
        public function get_prorroga($nombre_archivo){
            return Response::download(storage_path('archivos/prorrogas/'.$nombre_archivo), null, [], 'attachment');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_aprobacion_final_proyecto()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de aprobación de revisión de final de proyecto
    	*/                          
        public function get_aprobacion_final_proyecto($nombre_archivo){
            return Response::download(storage_path('archivos/aprobaciones_final_proyecto/'.$nombre_archivo), null, [], 'attachment');
        }        
        
        /*
    	|--------------------------------------------------------------------------
    	| get_aprobacion_prorroga()
    	|--------------------------------------------------------------------------
    	| Retorna respuesta http para descargar archivo de aprobación de prórorga de final de proyecto
    	*/                          
        public function get_aprobacion_prorroga($nombre_archivo){
            return Response::download(storage_path('archivos/aprobaciones_prorrogas/'.$nombre_archivo), null, [], 'attachment');
        }                
        
        
    }
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
        public function get_formato_documento($nombre_formato){
            if($nombre_formato == 'presupuesto'){
                return Response::download(storage_path('archivos/formatos_documentos/FMI6-25-V2_Presupuesto.xlsx'), null, [], 'attachment');
            }
            else if($nombre_formato == 'presentacion_proyecto'){
                return Response::download(storage_path('archivos/formatos_documentos/FMI6-11-V3_Proyecto.docx'), null, [], 'attachment');
            }
            else if($nombre_formato == 'acta_inicio'){
                return Response::download(storage_path('archivos/formatos_documentos/FMI6-1 Acta de Inicio_V5.docx'), null, [], 'attachment');
            }
            else if($nombre_formato == 'desembolso'){
                $nombre_archivo = FormatoTipoDocumento::where('nombre', '=', 'Desembolso')->first()->archivo;
                return Response::download(storage_path('archivos/formatos_documentos/'.$nombre_archivo), null, [], 'attachment');
            }            
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
    }
<?php
    
    class Archivo {
        
    	/*
    	|--------------------------------------------------------------------------
    	| eliminar_imagen_perfil()
    	|--------------------------------------------------------------------------
    	| Elimina una imagen de perfil de una persona dado su id
    	| Solo elimina la foto si el registro de la persona cuenta con una foto.
    	| Recibe como parámetro una instancia del modelo Persona o en su defecto, el id del mismo.
    	| Si recibe el id consulta la BD por la mpersona.
    	| Retorna true si se eliminó el archivo.
    	*/                  
        public static function eliminar_imagen_perfil($persona=null, $id_persona=null){
            
            if(isset($id_persona)){
                $persona = Persona::find($id_persona);
            }
            if($persona->foto != null && count($persona->foto) > 0)
            {
                return File::delete(storage_path('archivos/imagenes_perfil/'.$persona->foto));
            }
            else{
                return false;
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| copiar_imagen_perfil()
    	|--------------------------------------------------------------------------
    	| Copia la imagen de perfil cargada al sistema de ficheros distinguiendo su nombre con el id de la persona
    	| Recibe como parámetro una instancia del modelo Persona o en su defecto, el id del mismo.
    	| Si recibe el id consulta la BD por la persona.
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                          
        public static function copiar_imagen_perfil($imagen, $persona=null, $id_persona=null){
            
            if(isset($id_persona)){
                $persona = Persona::find($id_persona);
            }
            $imagen = $imagen->move(storage_path('archivos/imagenes_perfil/'), $persona->id.'__'.$imagen->getClientOriginalName());
            if($imagen) // el método move retorna un archivo si se movio el archivo correctamente.
                return $imagen;
            else
                return false;
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| copiar_documento_producto()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de producto ya sea proyectado a postular o publicado al sistema de ficheros, 
    	| distinguiendo su nombre con el id del registro de la postulacion/publicacion que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/         
        public static function copiar_documento_producto($archivo, $id_postulacion_publicacion){
            if(empty($archivo)) // no se paso ningun archivo
                throw new Exception('No se recibió archivo a copiar');
                
            $archivo = $archivo->move(storage_path('archivos/productos/'), $id_postulacion_publicacion.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;
        }

    	/*
    	|--------------------------------------------------------------------------
    	| copiar_desembolso()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de desembolso de cualquier tipo al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de desembolso que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                 
        public static function copiar_desembolso($archivo, $id_desembolso){
            $archivo = $archivo->move(storage_path('archivos/desembolsos/'), $id_desembolso.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;            
        }
    }

?>
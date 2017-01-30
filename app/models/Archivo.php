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
    	| copiar_presupuesto()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de presupuesto de proyecto al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                        
        public static function copiar_presupuesto($archivo, $id_presupuesto){
            $archivo = $archivo->move(storage_path('archivos/presupuestos/'), $id_presupuesto.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                                        
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_presentacion_proyecto()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de presentación de proyecto al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                                
        public static function copiar_presentacion_proyecto($archivo, $id_ppt_proyecto){
            $archivo = $archivo->move(storage_path('archivos/presentaciones_proyectos/'), $id_ppt_proyecto.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                                        
        }        
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_acta_inicio()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de acta de inicio de proyecto al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                                        
        public static function copiar_acta_inicio($archivo, $id_acta_inicio){
            $archivo = $archivo->move(storage_path('archivos/actas_inicio/'), $id_acta_inicio.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
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
        
    	/*
    	|--------------------------------------------------------------------------
    	| copiar_informe_avance()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de informe de avance al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le pertence
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                 
        public static function copiar_informe_avance($archivo, $id_documento_proyecto){
            $archivo = $archivo->move(storage_path('archivos/informes_avance/'), $id_documento_proyecto.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;            
        }        
        
    	/*
    	|--------------------------------------------------------------------------
    	| copiar_acta_finalizacion()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de acta de finalizacion al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/              
        public static function copiar_acta_finalizacion($archivo, $id_acta_finalizacion){
            $archivo = $archivo->move(storage_path('archivos/actas_finalizacion/'), $id_acta_finalizacion.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                        
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_memoria_academica()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de memoria académica al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/              
        public static function copiar_memoria_academica($archivo, $id_memoria_academica){
            $archivo = $archivo->move(storage_path('archivos/memorias_academicas/'), $id_memoria_academica.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_prorroga()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de prórroga al sistema de ficheros,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                      
        public static function copiar_prorroga($archivo, $id_prorroga){
            $archivo = $archivo->move(storage_path('archivos/prorrogas/'), $id_prorroga.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                            
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_aprobacion_final_proyecto()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de aprobación de final de proyecto,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                              
        public static function copiar_aprobacion_final_proyecto($archivo, $id_aprobacion_final_proyecto){
            $archivo = $archivo->move(storage_path('archivos/aprobaciones_final_proyecto/'), $id_aprobacion_final_proyecto.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                                        
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| copiar_aprobacion_prorroga()
    	|--------------------------------------------------------------------------
    	| Copia un archivo de aprobación de prórroga de final de proyecto,
    	| distinguiendo su nombre con el id del registro de DocumentoProyecto que le corresponde
    	| Recibe como parámetro el id citado
    	| Retorna el objeto File (Archivo) que representa el archivo copiado o false en caso de que no se halla copiado
    	*/                                     
        public static function copiar_aprobacion_prorroga($archivo, $id_aprobacion_prorroga){
            $archivo = $archivo->move(storage_path('archivos/aprobaciones_prorrogas/'), $id_aprobacion_prorroga.'__'.$archivo->getClientOriginalName());
            if($archivo)
                return $archivo;
            else
                return false;                                                    
        }
        
    }

?>
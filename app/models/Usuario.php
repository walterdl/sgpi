<?php

    use Illuminate\Database\Eloquent\SoftDeletingTrait;
    
    class Usuario extends Eloquent {
        
        use SoftDeletingTrait;
        
        protected $table = 'usuarios';
        protected $dates = ['deleted_at'];
        
        protected $fillable = [
            'id',
            'id_persona',
            'id_rol',
            'id_estado',
            'id_grupo_investigacion_ucc',
            'username',
            'password',
            'email'
            ];
        
        public function persona(){ 
            return $this->belongsTo('Persona', 'id_persona'); 
        }
        
        public function persona2(){ 
            return $this->belongsTo('Persona', 'id_persona'); 
        }
        
        public function rol() { 
            return $this->belongsTo('Rol', 'id_rol'); 
        }
        
        public function grupo() { 
            return $this->belongsTo('GrupoInvestigacionUCC', 'id_grupo_investigacion_ucc'); 
        }
        
        public function estado() { 
            return $this->belongsTo('Estado', 'id_estado'); 
        }
        
        public static function buscarUsuario($id_persona){
             $query = 'SELECT * FROM usuarios where id_persona='.$id_persona;
            
            return DB::select(DB::raw($query));
        }
        
        
        public static function usuario_investigador_desde_identificacion($identificacion){
            $query = '
            SELECT u.*
            FROM usuarios u, personas p
            WHERE 
                u.id_persona = p.id
            AND id_rol = 3
            AND p.identificacion = '.$identificacion.';';
            return DB::select(DB::raw($query))[0];
        }
        
        public static function usuarios_para_admin(){
            
            $query = 'SELECT u.id as id_usuario, u.id_rol, r.nombre as nombre_rol, u.id_estado, e.nombre as nombre_estado, u.username, p.nombres, p.apellidos, p.identificacion, ti.acronimo as acronimo_tipo_id ';
            $query .= 'FROM usuarios u, roles r, estados e, personas p, tipos_identificacion ti ';
            $query .= 'WHERE  ';
            $query .= ' 	u.id_persona = p.id  ';
            $query .= 'AND u.id not in('.Auth::user()->id.')' ;
            $query .= 'AND	u.id_rol = r.id ';
            $query .= 'AND  r.id in (1, 2, 3) ';
            $query .= 'AND	u.id_estado = e.id ';
            $query .= 'AND  p.id_tipo_identificacion = ti.id; ';
            
            return DB::select(DB::raw($query));
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| mas_info_usuario()
    	|--------------------------------------------------------------------------
    	| Consulta la siguiente información de un usuario determinado por su id:
    	| -id_usuario
    	| -nombre del rol y su id
    	| -nombre del estado y su id
    	| -nombre de usuario
    	| -email del usuario
    	| -nombre de la categoria del investigador y su id
    	| -apellidos, nombres, edad, identificación, sexo, formación, ruta de foto,  y fecha de creación del registro
    	| -id del tipo de identificación
    	| -nombre del grupo de investigación al que pertenece y su id
    	| -nombre de facultad y sede que pertenece junto sus id
    	| -nombre de la clasificación del grupo de investigación al que pertenece, junto con el nombre de la área y gran área colciencias del mismo
    	*/        
        public static function mas_info_usuario($id_usuario){
            
            $query = 'SELECT  ';
            $query .= '	u.id as id_usuario, u.id_rol, r.nombre as nombre_rol, u.id_estado, e.nombre as nombre_estado, u.username, u.email, ci.id as id_categoria_investigador, ci.nombre as categoria_investigador, ';
            $query .= '	p.nombres, p.apellidos, p.identificacion, ti.id as id_tipo_identificacion, ti.acronimo as acronimo_tipo_id, ti.nombre as nombre_tipo_identificacion, p.edad, p.sexo, p.formacion, p.foto, p.created_at, ci.nombre as nombre_clasificacion_investigador,  ';
            $query .= '	gi.id as id_grupo_inv, gi.nombre as nombre_grupo_inv, fd.id as id_facultad_dependencia, fd.nombre as nombre_facultad, s.id as id_sede, s.nombre as nombre_sede, ';
            $query .= ' cgi.nombre as nombre_clasificacion_grupo_inv, a.nombre as nombre_area, ga.nombre as nombre_gran_area ';
            $query .= 'FROM ';
            $query .= '	usuarios u ';
            $query .= 'INNER JOIN personas p ON u.id_persona = p.id ';
            $query .= 'INNER JOIN roles r ON u.id_rol = r.id AND r.id in (1, 2, 3) ';
            $query .= 'INNER JOIN estados e ON u.id_estado = e.id  ';
            $query .= 'INNER JOIN tipos_identificacion ti ON p.id_tipo_identificacion = ti.id  ';
            $query .= 'LEFT JOIN  categorias_investigadores ci ON p.id_categoria_investigador = ci.id  ';
            $query .= 'LEFT JOIN  grupos_investigacion_ucc gi ';
            $query .= '	INNER JOIN facultades_dependencias_ucc fd ON gi.id_facultad_dependencia_ucc = fd.id ';
            $query .= '	INNER JOIN sedes_ucc s ON fd.id_sede_ucc = s.id ';
            $query .= '	INNER JOIN clasificaciones_grupos_investigacion cgi ON gi.id_clasificacion_grupo_investigacion = cgi.id ';
            $query .= '	INNER JOIN areas a ON gi.id_area = a.id ';
            $query .= '	INNER JOIN gran_areas ga ON a.id_gran_area = ga.id ';
            $query .= 'ON u.id_grupo_investigacion_ucc = gi.id  ';
            $query .= 'WHERE u.id = '.$id_usuario.'; ';
            
            return DB::select(DB::raw($query))[0];
        }
        
        
        public static function mas_info_usuario2($id_persona){
            
            $query = 'SELECT  ';
            $query .= '	u.id as id_usuario, u.id_rol, r.nombre as nombre_rol, u.id_estado, e.nombre as nombre_estado, u.username, u.email, ci.id as id_categoria_investigador, ci.nombre as categoria_investigador, ';
            $query .= '	p.nombres, p.apellidos, p.identificacion, ti.id as id_tipo_identificacion, ti.acronimo as acronimo_tipo_id, ti.nombre as nombre_tipo_identificacion, p.edad, p.sexo, p.formacion, p.foto, p.created_at, ci.nombre as nombre_clasificacion_investigador,  ';
            $query .= '	gi.id as id_grupo_inv, gi.nombre as nombre_grupo_inv, fd.id as id_facultad_dependencia, fd.nombre as nombre_facultad, s.id as id_sede, s.nombre as nombre_sede, ';
            $query .= ' cgi.nombre as nombre_clasificacion_grupo_inv, a.nombre as nombre_area, ga.nombre as nombre_gran_area ';
            $query .= 'FROM ';
            $query .= '	usuarios u ';
            $query .= 'INNER JOIN personas p ON u.id_persona = p.id ';
            $query .= 'INNER JOIN roles r ON u.id_rol = r.id AND r.id in (1, 2, 3) ';
            $query .= 'INNER JOIN estados e ON u.id_estado = e.id  ';
            $query .= 'INNER JOIN tipos_identificacion ti ON p.id_tipo_identificacion = ti.id  ';
            $query .= 'LEFT JOIN  categorias_investigadores ci ON p.id_categoria_investigador = ci.id  ';
            $query .= 'LEFT JOIN  grupos_investigacion_ucc gi ';
            $query .= '	INNER JOIN facultades_dependencias_ucc fd ON gi.id_facultad_dependencia_ucc = fd.id ';
            $query .= '	INNER JOIN sedes_ucc s ON fd.id_sede_ucc = s.id ';
            $query .= '	INNER JOIN clasificaciones_grupos_investigacion cgi ON gi.id_clasificacion_grupo_investigacion = cgi.id ';
            $query .= '	INNER JOIN areas a ON gi.id_area = a.id ';
            $query .= '	INNER JOIN gran_areas ga ON a.id_gran_area = ga.id ';
            $query .= 'ON u.id_grupo_investigacion_ucc = gi.id  ';
            $query .= 'WHERE p.id = '.$id_persona.'; ';
            
            return DB::select(DB::raw($query))[0];
        }
        

        /*
    	|--------------------------------------------------------------------------
    	| consultar_duplicidad_nombres_apellidos()
    	|--------------------------------------------------------------------------
    	| Consulta cualquier registro en tabla personas dado un nombre y apellido.
    	| Usado para verificar si esxisten los mismos nombres y apellidos
    	*/
        public static function consultar_duplicidad_nombres_apellidos($nombres, $apellidos){
            
            $query = 'SELECT * ';
            $query .= 'FROM personas p ';
            $query .= 'INNER JOIN usuarios u ON u.id_persona = p.id ';
            $query .= 'WHERE p.nombres = \''.$nombres.'\' AND p.apellidos = \''.$apellidos.'\'; ';
            return DB::select(DB::raw($query));
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| consultar_duplicidad_identificacion()
    	|--------------------------------------------------------------------------
    	| Consulta cualquier registro en tabla personas dado una identificación.
    	| Usado para verificar si existe la misma identificación
    	| Opcionalmente se puede pasar un id_usuario para consultar por la existencia de la identificación
    	| excluyendo la persona del usuario dado
    	*/        
        public static function consultar_existencia_identificacion($identificacion, $id_usuario=null){
            
            
            if($id_usuario){
                $query = 'SELECT COUNT(*) as cantidad_registros ';
                $query .= 'FROM personas p, usuarios u ';
                $query .= 'WHERE u.id = '.$id_usuario.' AND u.id_persona <> p.id AND p.identificacion = '.$identificacion.';';
            }
            else{
                $query = 'SELECT COUNT(*) as cantidad_registros ';
                $query .= 'FROM personas p ';
                $query .= 'WHERE p.identificacion = '.$identificacion.'; ';
            }
                
            return DB::select(DB::raw($query))[0];
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| consultar_duplicidad_nombre_usuario()
    	|--------------------------------------------------------------------------
    	| Consulta cualquier registro en tabla usuarios dado un nombre de usuario.
    	| Usado para verificar si el nombre de usuario dado ya existe.
    	| Opcionalmente se puede pasar un id_usuario para consultar por la existencia del username
    	| el usuario dado
    	*/                
        public static function consultar_existencia_nombre_usuario($username, $id_usuario=null){
            
            if($id_usuario){
                $query = 'SELECT COUNT(*) as cantidad_registros ';
                $query .= 'FROM usuarios u ';
                $query .= 'WHERE u.id <> '.$id_usuario.' AND u.username = \''.$username.'\';';
            }
            else{
                $query = 'SELECT COUNT(*) as cantidad_registros ';
                $query .= 'FROM usuarios u ';
                $query .= 'WHERE u.username = \''.$username.'\';';
            }
            return DB::select(DB::raw($query))[0];
        }
  
        public static function administradores(){
            
            $administradores = Usuario::where('id_rol', '=', 1)->get();
            for ($i = 0; $i < count($administradores); $i++) {
                $administrador = $administradores[$i];
                $persona = Persona::find($administrador->id_persona);
                $administrador = $administrador->toArray();
                $administrador['nombres'] = $persona->nombres;
                $administrador['apellidos'] = $persona->apellidos;
                $administradores[$i] = (object)$administrador;
            }
            return $administradores;
        }  
        
    }

?>
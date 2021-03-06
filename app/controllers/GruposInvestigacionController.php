<?php
    
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class GruposInvestigacionController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| listar()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de consulta de todos los grupos de investigación por sede
    	*/
        public function listar(){
            
            $sedes = SedeUCC::all();
            
            $styles = [
                'vendor/datatables/dataTables.bootstrap.css',
                'vendor/angular-datatables/css/angular-datatables.min.css',
                'vendor/angular-datatables/plugins/bootstrap/datatables.bootstrap.min.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'vendor/angular-ui/ui-select.css', 
                'app/css/administrador/grupos_investigacion/listar.css'
                // 'vendor/bootstrap-chosen/bootstrap-chosen.css',
                ];
                
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/angular-datatables/angular-datatables.min.js',
                'vendor/angular-datatables/plugins/bootstrap/angular-datatables.bootstrap.min.js',
                'vendor/angular-ui/ui-select.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                // 'vendor/bootstrap-chosen/bootstrap-chosen.js'
                ];
                
            $post_scripts = ['administrador/grupos_investigacion/listar_grupos_investigacion_controller.js'];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize', 'datatables', 'datatables.bootstrap'];
            
            return View::make('administrador.grupos_investigacion.listar', array(
                'sedes' => $sedes,
                'styles' => $styles,
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_grupos_investigacion()
    	|--------------------------------------------------------------------------
    	| Retorno json con los grupos de investigación de una sede determinada por su id, añade el nombre de la facultad a la cual pertenece
    	*/
        public function get_grupos_investigacion(){
            
            $id_sede = Input::all()['id_sede'];
            
            $query = '
    			SELECT 
    				gi.id as id, gi.nombre as nombre_grupo_investigacion, 
    				fd.id as id_facultad_dependencia, fd.nombre as nombre_facultad_dependencia,
    				cgi.nombre as clasificacion_grupo_investigacion, a.nombre as area, ga.nombre as gran_area
                FROM grupos_investigacion_ucc gi, clasificaciones_grupos_investigacion cgi, areas a, gran_areas ga, facultades_dependencias_ucc fd 
                WHERE  
                	gi.id_clasificacion_grupo_investigacion = cgi.id  
                AND gi.id_area = a.id   
                AND a.id_gran_area = ga.id  
                AND fd.id_sede_ucc = '.$id_sede.'
                AND fd.id = gi.id_facultad_dependencia_ucc 
                ORDER BY gi.nombre;';
                
            $resultado = DB::select(DB::raw($query));
            
            return json_encode($resultado);
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| get_lineas_investigacion_x_grupo_inv()
    	|--------------------------------------------------------------------------
    	| Retorno json con las líneas de investigación de un grupo de inv determinado por su id
    	*/
        public function get_lineas_investigacion_x_grupo_inv(){
            
            $id_grupo_investigacion = Input::all()['id_grupo_investigacion'];
            
            $query = 'SELECT li.* ';
            $query .= 'FROM lineas_investigacion li, lineas_grupos_investigacion_ucc lig ';
            $query .= 'WHERE ';
            $query .= 'lig.id_grupo_investigacion_ucc = '.$id_grupo_investigacion.' ';
            $query .= 'AND	lig.id_linea_investigacion = li.id; ';
            
            $resultado = DB::select(DB::raw($query));
            
            return json_encode($resultado);
        }

        /*
    	|--------------------------------------------------------------------------
    	| crear()
    	|--------------------------------------------------------------------------
    	| Retorona la vista de creacion de nuevo grupo de investigacion
    	*/        
        public function crear(){
            
            $styles = ['vendor/angular-ui/ui-select.css', 'vendor/angular-ui/overflow-ui-select.css'];
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/angular-ui/ui-select.js',
                ];
            $post_scripts = ['administrador/grupos_investigacion/crear_grupos_investigacion_controller.js'];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize'];
                
            return View::make('administrador.grupos_investigacion.crear', array(
                'styles' => $styles,
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| data_inicial_vista_crear()
    	|--------------------------------------------------------------------------
    	| Retorno json con los datos iniciales necesarios de la vista para comenzar su funcionamiento
    	*/                
        public function data_inicial_vista_crear(){
            
            try{
                $sedes = SedeUCC::all();
                $lineas_investigacion = LineaInvestigacion::all();
                $areas = Area::all();
                $gran_areas = GranArea::all();
                $clasificaciones_grupos_inv = ClasificacionGrupoInvestigacion::all();
                $facultades_dependencias = FacultadDependenciaUCC::all();
                $data = [
                    'consultado' => 1,
                    'sedes' => $sedes,
                    'lineas_investigacion' => $lineas_investigacion,
                    'areas' => $areas,
                    'gran_areas' => $gran_areas,
                    'clasificaciones_grupos_inv' => $clasificaciones_grupos_inv,
                    'facultades_dependencias' => $facultades_dependencias
                    ];
                return json_encode($data);
            }
            catch(Exception $e){
                return json_encode(array('codigo' => $e->getCode(), 'mensaje' => $e->getMessage()));
            }
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| consultar_nombre_grupo()
    	|--------------------------------------------------------------------------
    	| Retorno json true o false si el nombre del grupo de investigacion dado existe en la BD
    	*/          
        public function consultar_nombre_grupo(){
            
            try 
            {
                try 
                {
                    $grupo_inv = GrupoInvestigacionUCC::where('nombre', Input::all()['nombre'])->firstOrFail();
                    echo json_encode(true);
                } 
                catch (ModelNotFoundException $e) 
                {
                    echo json_encode(false);
                }
            } 
            catch(Exception $e) 
            {
                echo json_encode(array('codigo' => $e->getCode(), 'mensaje' => $e->getMessage()));
            }    
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| guardar_nuevo_grupo_inv()
    	|--------------------------------------------------------------------------
    	| Guarda en la BD un nuevo grupo de investigación UCC para una sede dada
    	*/                  
        public function guardar_nuevo_grupo_inv(){
            
            try 
            {
                DB::transaction(function ()
                {
                    $data = Input::all();   
                    $facultad = FacultadDependenciaUCC::find($data['facultad']);  
                    
                    $grupo_inv = GrupoInvestigacionUCC::create(array(
                        'nombre' => $data['nombre'],
                        'id_facultad_dependencia_ucc' => $facultad->id,
                        'id_area' => $data['area'],
                        'id_clasificacion_grupo_investigacion' => $data['clasificacion_grupo_inv']
                        ));
                
                    if(isset($data['nuevas_lineas'])){
                        foreach($data['nuevas_lineas'] as $linea){
                            $linea = LineaInvestigacion::create(array('nombre' => $linea));
                            LineaGrupoInvestigacionUCC::create(array('id_linea_investigacion' => $linea->id, 'id_grupo_investigacion_ucc' => $grupo_inv->id));
                        }
                    }
                    if(isset($data['lineas_existentes'])){                           
                        foreach($data['lineas_existentes'] as $linea){
                            $linea = LineaInvestigacion::find($linea);
                            LineaGrupoInvestigacionUCC::create(array('id_linea_investigacion' => $linea->id, 'id_grupo_investigacion_ucc' => $grupo_inv->id));
                        }
                    }
                });
                
                Session::flash('notify_operacion_previa', 'success');
                Session::flash('mensaje_operacion_previa', 'Grupo de investigación registrado');
            } 
            catch(Exception $e) 
            {
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', $e->getMessage());
                Session::flash('codigo_error_operacion_previa', $e->getCode());
            }   
            return Redirect::to('/grupos/listar');
        }


        /*
    	|--------------------------------------------------------------------------
    	| editar_grupo_investigacion()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de Edicion de la información de un grupo de investigación
    	*/          
        public function editar(){
            
            $styles = ['vendor/angular-ui/ui-select.css', 'vendor/bootstrap-chosen/bootstrap-chosen.css'];
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/angular-ui/ui-select.js',
                'vendor/bootstrap-chosen/bootstrap-chosen.js'
                ];
            $post_scripts = ['administrador/grupos_investigacion/editar_grupo_investigacion_controller.js'];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize'];
            
            return View::make('administrador.grupos_investigacion.editar', array(
                'id_grupo_investigacion' => Input::all()['id'],
                'styles' => $styles,
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
        
        
        /*
    	|--------------------------------------------------------------------------
    	| data_inicial_vista_editar()
    	|--------------------------------------------------------------------------
    	| Retorno json con los datos iniciales necesarios de la vista para comenzar su funcionamiento
    	*/              
        public function data_inicial_vista_editar(){
            
            try{
                
                $lineas_investigacion = LineaInvestigacion::all();
                $areas = Area::all();
                $gran_areas = GranArea::all();
                $clasificaciones_grupos_inv = ClasificacionGrupoInvestigacion::all();
                $sedes = SedeUCC::all();
                $facultades = FacultadDependenciaUCC::all();
                
                $id_grupo_investigacion = Input::all()['id'];
                
                $query_grupo_inv = '
                    SELECT gi.*, s.id as id_sede, fd.id as id_facultad_dependencia 
                    FROM grupos_investigacion_ucc gi, facultades_dependencias_ucc fd, sedes_ucc s 
                    WHERE 
                    	gi.id = '.$id_grupo_investigacion.' 
                    AND gi.id_facultad_dependencia_ucc = fd.id 
                    AND fd.id_sede_ucc = s.id; ';
                $grupo_investigacion = DB::select(DB::raw($query_grupo_inv))[0];
                
                $query_lineas_inv_del_grupo =  'SELECT li.* ';
                $query_lineas_inv_del_grupo .=  'FROM lineas_investigacion li, lineas_grupos_investigacion_ucc lgi ';
                $query_lineas_inv_del_grupo .=  'WHERE ';
                $query_lineas_inv_del_grupo .=  '	 lgi.id_grupo_investigacion_ucc = '.$grupo_investigacion->id.' ';
                $query_lineas_inv_del_grupo .=  'AND lgi.id_linea_investigacion = li.id; ';
                $lineas_inv_del_grupo = DB::select(DB::raw($query_lineas_inv_del_grupo));
                
                return json_encode(array(
                    'consultado' => 1,
                    'sedes' => $sedes,
                    'facultades' => $facultades,
                    'lineas_investigacion' => $lineas_investigacion,
                    'areas' => $areas,
                    'gran_areas' => $gran_areas,
                    'clasificaciones_grupos_inv' => $clasificaciones_grupos_inv,
                    'grupo_investigacion' => $grupo_investigacion,
                    'lineas_inv_del_grupo' => $lineas_inv_del_grupo
                    ));
            }
            catch(Exception $e){
                return json_encode(array('codigo' => $e->getCode(), 'mensaje' => $e->getMessage()));
            }
        }
        
        
        /*
    	|--------------------------------------------------------------------------
    	| validar_edicion_grupo_inv()
    	|--------------------------------------------------------------------------
    	| Realiza la siguiente validacion de logica de negocio para campos clave de una edicion de un grupo de investigación:
    	| -solo se podra cambiar de sede si en dicha sede no existe un grupo de investigación con el mismo nombre
    	*/                
        public function validar_edicion_grupo_inv(){

            $nombre_grupo = Input::all()['nombre_grupo'];
            $id_sede = Input::all()['id_sede'];
            try 
            {
                $nombre_grupo_cambiado = json_decode(Input::all()['nombre_grupo_cambiado']);
                if($nombre_grupo_cambiado){
                    
                    $query = 'SELECT gi.* ';
                    $query .= 'FROM grupos_investigacion_ucc gi, facultades_dependencias_ucc fd ';
                    $query .= 'WHERE  ';
                    $query .= '	gi.nombre = \''.$nombre_grupo.'\' ';
                    $query .= 'AND gi.id_facultad_dependencia_ucc = fd.id ';
                    $query .= 'AND fd.id_sede_ucc = '.$id_sede.';';
                    $grupo_inv = DB::select(DB::raw($query));
                    
                    if($grupo_inv){
                        // existe un grupo de inv con el mismo nombre editado en la misma sede,
                        // se retorna respuesta json indicando tal validacion incorrecta
                        echo json_encode(array('valido' => 'false', 'mensaje_validacion' => 'Ya existe un grupo de investigación con el mismo nombre en la sede'));
                        return;
                    }
                }
                
                // se alcanzó este código; no se intenta cambiar el nombre del grupo de investigación
                echo json_encode(array('valido' => 'true'));
                return;
            } 
            catch(Exception $e) 
            {
                echo json_encode(array('codigo' => $e->getCode(), 'mensaje' => $e->getMessage()));
            }
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| guardar_edicion_grupo_inv()
    	|--------------------------------------------------------------------------
    	| Guarda las ediciones de un grupo de investigación
    	*/  
        public function guardar_edicion_grupo_inv(){
            
            try{
                DB::transaction(function ()
                {
                    $data = Input::all();
                    $grupo_investigacion = GrupoInvestigacionUCC::find($data['id_grupo_investigacion']);
                    $grupo_investigacion->nombre = $data['nombre'];
                    $grupo_investigacion->id_area = $data['area'];
                    $grupo_investigacion->id_clasificacion_grupo_investigacion = $data['clasificacion_grupo_inv'];
                    
                    $facultad = FacultadDependenciaUCC::find($data['facultad']);
                    
                    $grupo_investigacion->id_facultad_dependencia_ucc = $facultad->id;                
                    $grupo_investigacion->save();
                    
                    // trata las lineas existentes del grupo eliminadas
                    // primero se consulta por las lineas de investigacion almacenadas actualmente del grupo de inv 
                    $query = 'SELECT li.id as id_linea_investigacion, lgi.id as id_lgi ';
                    $query .= 'FROM lineas_investigacion li, lineas_grupos_investigacion_ucc lgi ';
                    $query .= 'WHERE  ';
                    $query .= '    li.id = lgi.id_linea_investigacion ';
                    $query .= 'AND lgi.id_grupo_investigacion_ucc = '.$grupo_investigacion->id.' ;';
                    $lineas_del_grupo_existentes = DB::select(DB::raw($query));
                    
                    if(count($lineas_del_grupo_existentes)){
                        
                        if(isset($data['lineas_existentes'])){
                            
                            $lineas_existentes_enviadas = $data['lineas_existentes'];
                            foreach($lineas_del_grupo_existentes as $linea_grupo_existente){
                                
                                $resultado_busqueda_linea = array_search($linea_grupo_existente->id_linea_investigacion, $lineas_existentes_enviadas);
                                if($resultado_busqueda_linea === false){
                                    
                                    // se elimino una linea de investigacion existente
                                    LineaGrupoInvestigacionUCC::find($linea_grupo_existente->id_lgi)->delete();
                                }
                            }
                        }
                        else{
                            // se han eliminado todas las lineas existentes del grupo de inv
                            foreach($lineas_del_grupo_existentes as $linea_grupo_existente){
                                LineaGrupoInvestigacionUCC::find($linea_grupo_existente->id_lgi)->delete();
                            }
                        }
                    }
                    
                    // trata las lineas existentes en la bd asociandas como nuevas al grupo de investigación
                    if(isset($data['lineas_existentes_agregadas'])){
                        foreach($data['lineas_existentes_agregadas'] as $linea_existente_agregada){
                            // se realiza primero una reconfirmación de que la linea ya no este relacionada con el grupo de investigación
                            $linea_ya_asociada = LineaGrupoInvestigacionUCC::
                                where('id_linea_investigacion', '=', $linea_existente_agregada)
                                ->where('id_grupo_investigacion_ucc', '=', $grupo_investigacion->id)->get();
                            if(count($linea_ya_asociada)){
                                // no se opera pues la linea ya se encuentra relacionada
                                continue;
                            }
                            else{
                                // no esta asociada al grupo de investigacio, se procede a relacionarla
                                LineaGrupoInvestigacionUCC::create(array('id_linea_investigacion' => $linea_existente_agregada, 'id_grupo_investigacion_ucc' => $grupo_investigacion->id));
                            }
                        }
                    }
                    
                    // trata la creación de nuevas lineas y su respctiva asociacion con el grupo de investigación
                    if(isset($data['nuevas_lineas'])){
                        foreach($data['nuevas_lineas'] as $linea){
                            $linea = LineaInvestigacion::create(array('nombre' => $linea));
                            LineaGrupoInvestigacionUCC::create(array('id_linea_investigacion' => $linea->id, 'id_grupo_investigacion_ucc' => $grupo_investigacion->id));
                        }
                    }
                });
                Session::flash('notify_operacion_previa', 'success');
                Session::flash('mensaje_operacion_previa', 'Grupo de investigación editado');
            }
            catch(Exception $e) 
            {
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', $e->getMessage());
                Session::flash('codigo_error_operacion_previa', $e->getCode());
            }   
            return Redirect::to('/grupos/listar');
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| validar_eliminacion_grupo_inv()
    	|--------------------------------------------------------------------------
    	| Resuesta ajax validando la peticion de eliminacion de grupo de investigacion haciendo uso de la funcion validar_eliminacion_grupo_investigacion()
        | Responde con un valor 1 al campo 'se_puede_eliminar' si el grupo sí se puede eliminar, retorna 0 de lo contrario
    	*/          
        public function validar_eliminacion_grupo_inv(){
            
            try{
                
                // valida el identificador del grupo de investigación ucc enviado
                if(is_null(Input::get('id_grupo_investigacion_ucc', null)))
                    throw new Exception('Identificador de grupo de investigación inválido. No se ha enviado tal dato');
                $validacion = Validator::make(
                    ['id_grupo_investigacion_ucc' => Input::get('id_grupo_investigacion_ucc')],
                    ['id_grupo_investigacion_ucc' => 'required|exists:grupos_investigacion_ucc,id']
                    );
                if($validacion->fails())
                    throw new Exception('Identificador de grupo de investigación inválido. No se encuentran registros con tal dato');
                
                $se_puede_eliminar = $this->validar_eliminacion_grupo_investigacion(Input::get('id_grupo_investigacion_ucc'));
                if($se_puede_eliminar)
                    $se_puede_eliminar = 1;
                else
                    $se_puede_eliminar = 0;
                
                // no hay usuario, proyecto ni investigador con el grupo de investigación referenciado, se puede eliminar
                return json_encode([
                    'consultado' => 1,
                    'se_puede_eliminar' => $se_puede_eliminar
                    ]);                                                        
            }
            catch(Exception $e){
                echo json_encode(array('consultado' => 2, 'codigo' => $e->getCode(), 'mensaje' => $e->getMessage()));
            }
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| validar_eliminacion_grupo_investigacion()
    	|--------------------------------------------------------------------------
    	| Valida si un grupo de investigación puede ser eliminado de la BD teniendo en cuenta las siguientes reglas:
        | -No se elimina si hay usuario coordinador o investigador cuyo grupo de investiigación es el grupo a liminar
        | -No se elimina si hay proyecto cuyo grupo de investigación ejecutor es el grupo a eliminar
        | -No se elimina si hay investigador interno cuyo grupo de investigación es el grupo a eliminar
        | Responde con false si no se puede eliminar
    	*/          
        private function validar_eliminacion_grupo_investigacion($id_grupo_investigacion){
            
            // verifica existencia de usuarios cuyo grupo de investigación sea el mismo a eliminar
            $usuario = Usuario::where('id_grupo_investigacion_ucc', '=', $id_grupo_investigacion)->first();
            if($usuario) // si hay al menos un usuario con el grupo
            {
                return false;
            }
            
            // verifica si hay proyecto de investigación con el grupo a eliminar
            $proyecto = Proyecto::where('id_grupo_investigacion_ucc', '=', $id_grupo_investigacion)->first();
            if($proyecto)
            {
                return false;                 
            }
            
            // verifica si hay investigador con el grupo de investigación a eliminar
            $investigador = Investigador::withTrashed()->where('id_grupo_investigacion_ucc', '=', $id_grupo_investigacion)->first();
            if($investigador)
            {
                return false;                                    
            }            
            
            return true;
        }
        
        /*
    	|--------------------------------------------------------------------------
    	| eliminar_grupo_investigacion()
    	|--------------------------------------------------------------------------
    	| Elimina un grupo de investigación ucc aplicando la validación de validar_eliminacion_grupo_inv()
    	*/          
        public function eliminar_grupo_investigacion(){
            
            try{
                DB::transaction(function ()
                {
                    // valida el identificador del grupo de investigación ucc enviado
                    if(is_null(Input::get('id_grupo_investigacion_ucc', null)))
                        throw new Exception('Identificador de grupo de investigación inválido. No se ha enviado tal dato');
                    $validacion = Validator::make(
                        ['id_grupo_investigacion_ucc' => Input::get('id_grupo_investigacion_ucc')],
                        ['id_grupo_investigacion_ucc' => 'required|exists:grupos_investigacion_ucc,id']
                        );
                    if($validacion->fails())
                        throw new Exception('Identificador de grupo de investigación inválido. No se encuentran registros con tal dato');
                            
                    // utiliza la funcion validar_eliminacion_grupo_inv para verificar si se puede o no eliminar
                    $se_puede_eliminar = $this->validar_eliminacion_grupo_investigacion(Input::get('id_grupo_investigacion_ucc'));
                    
                    if($se_puede_eliminar)
                    {
                        $query = 'DELETE FROM lineas_grupos_investigacion_ucc WHERE id_grupo_investigacion_ucc = '.Input::get('id_grupo_investigacion_ucc').';';
                        DB::select(DB::raw($query));
                        GrupoInvestigacionUCC::find(Input::get('id_grupo_investigacion_ucc'))->forceDelete();
                        Session::flash('notify_operacion_previa', 'success');
                        Session::flash('mensaje_operacion_previa', 'Grupo de investigación eliminado');                        
                    }
                    else
                    {
                        Session::flash('notify_operacion_previa', 'error');
                        Session::flash('mensaje_operacion_previa', 'No se puede eliminar el grupo de investigación ya que: un usuario, un investigador o un proyecto de investigación lo está usando');
                    }
                });

            }
            catch(Exception $e){
                Session::flash('notify_operacion_previa', 'error');
                Session::flash('mensaje_operacion_previa', 'Error al intentar eliminar el grupo de investigación. Detalles: '.$e->getMessage());
            }
            return Redirect::to('/grupos/listar');
        }
        
    }
<?php
    
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    
    class LineasInvestigacionController extends BaseController {
        
    	/*
    	|--------------------------------------------------------------------------
    	| listar()
    	|--------------------------------------------------------------------------
    	| Presenta la vista de listado de todas las líneas de investigación
    	*/                
        public function index(){
            
            $styles = [
                'vendor/datatables/dataTables.bootstrap.css',
                'vendor/angular-datatables/css/angular-datatables.min.css',
                'vendor/angular-datatables/plugins/bootstrap/datatables.bootstrap.min.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'vendor/angular-ui/ui-select.css', 
                ];
                
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/angular-datatables/angular-datatables.min.js',
                'vendor/angular-datatables/plugins/bootstrap/angular-datatables.bootstrap.min.js',
                'vendor/angular-ui/ui-select.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                ];
                
            $post_scripts = ['administrador/lineas_investigacion/listar_lineas_inv_controller.js'];
            $angular_sgpi_app_extra_dependencies = ['ui.select', 'ngSanitize', 'datatables', 'datatables.bootstrap'];
            
            return View::make('administrador.lineas_investigacion.listar', array(
                'styles' => $styles,
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ));
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| data_inicial_vista_listar()
    	|--------------------------------------------------------------------------
    	| Retorno json con las líneas d einvestigación pára la vista listar
    	*/           
        public function data_inicial_vista_listar(){
            
            try{
                return json_encode(array(
                    'lineas_investigacion' => LineaInvestigacion::all(),
                    'consultado' => 1
                    ));
            }
            catch(Excepcion $e){
                return json_encode(array(
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode(),
                    'consultado' => 2
                    ));
            }
        }

    	/*
    	|--------------------------------------------------------------------------
    	| guardar_edicion()
    	|--------------------------------------------------------------------------
    	| Retorno json con la notificación de éxito de la operacion de guardado de la edición de una linea de investigación dada
    	*/          
        public function guardar_edicion(){
            
            try{
                $data = Input::all();
                // $linea_investigacion = LineaInvestigacion::find($data['id']);
                // se verifica que el nombre no se encuentre repetido
                $linea_investigacion = LineaInvestigacion::where('nombre', '=', $data['nombre'])->first();
                if($linea_investigacion){
                    // el nombre ya existe, no se guarda
                    return json_encode(array(
                        'consultado' => 2,
                        'mensaje' => 'El nombre de la línea de investigación ya existe'
                        ));
                }
                else{
                    // el nombre no existe, se edita la línea
                    $linea_investigacion = LineaInvestigacion::find($data['id']);
                    $linea_investigacion->nombre = $data['nombre'];
                    $linea_investigacion->save();
                    return json_encode(array(
                        'consultado' => 1,
                        ));
                }
            }
            catch(Excepcion $e){
                // un error inesperado
                return json_encode(array(
                        'consultado' => 2,
                        'mensaje' => $e->getMessage(),
                        'codigo' => $e->getCode()
                        ));
            }
            
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| eliminar()
    	|--------------------------------------------------------------------------
    	| Elimina una linea de investigación dado su id
    	*/          
        public function eliminar(){
            
            try{
                LineaInvestigacion::eliminar_linea_investigacion(Input::all()['id']);
                return json_encode(array(
                    'consultado' => 1
                    ));
            }
            catch(Excepcion $e){
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));
            }
        }
        
    	/*
    	|--------------------------------------------------------------------------
    	| crear()
    	|--------------------------------------------------------------------------
    	| Crea línea de investigación, comprobando si el nombre no existe. Retorna id asociado.
    	*/  
        public function crear(){
            
            try{
                $linea_investigacion = LineaInvestigacion::where('nombre' , '=', Input::all()['nombre'])->first();
                if($linea_investigacion)
                {
                    // el nombre de la línea existe
                    return json_encode(array(
                        'consultado' => 2,
                        'mensaje' => 'El nombre de la linea de investigación ya existe'
                        ));
                }
                else{
                    $linea_investigacion = LineaInvestigacion::create(array('nombre' => Input::all()['nombre']));
                    return json_encode(array(
                        'consultado' => 1,
                        'id' => $linea_investigacion->id,
                        ));
                }
            }
            catch(Excepcion $e){
                return json_encode(array(
                    'consultado' => 2,
                    'mensaje' => $e->getMessage(),
                    'codigo' => $e->getCode()
                    ));
            } 
        }
    }

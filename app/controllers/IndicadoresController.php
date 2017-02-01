<?php

    class IndicadoresController extends BaseController {
        
        /*
        |--------------------------------------------------------------------------
        | indicadores_admin()
        |--------------------------------------------------------------------------
        | Presenta vista de indicadores de administrador
        */        
        public function indicadores_admin(){
            
            // provee estilos personalizados para la vista a cargar
            $styles = [
                'vendor/ngAnimate/ngAnimate.css',
                'vendor/datatables/dataTables.bootstrap.css',
                'vendor/angular-datatables/css/angular-datatables.min.css',
                'vendor/angular-datatables/plugins/bootstrap/datatables.bootstrap.min.css',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.css',
                'vendor/angular-ui/ui-select.css', 
                'vendor/angular-ui/overflow-ui-select.css'                
                ]; 
            
            // provee scripts extras o personalizados para la vista a cargar
            $pre_scripts = [
                'vendor/angular/sanitize/angular-sanitize.js',
                'vendor/angular-ui/ui-select.js',
                'vendor/angular-ui/ui-bootstrap-tpls-2.2.0.min.js',
                'vendor/datatables/jquery.dataTables.min.js',
                'vendor/angular-datatables/angular-datatables.min.js',
                'vendor/angular-datatables/plugins/bootstrap/angular-datatables.bootstrap.min.js',
                'vendor/mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js',
                'vendor/amcharts/amcharts.js', 
                'vendor/amcharts/pie.js',
                'vendor/amcharts/plugins/responsive/responsive.min.js'                
                ];        
            
            $post_scripts = ['administrador/indicadores/listar/general_controller.js', 'administrador/indicadores/listar/mas_info_proyecto_controller.js'];
            
            $angular_sgpi_app_extra_dependencies = ['ngAnimate', 'ngTouch', 'ngSanitize', 'ui.bootstrap', 'datatables', 'datatables.bootstrap', 'ui.select'];

            return View::make('administrador.indicadores.listar', [
                'styles' => $styles, 
                'pre_scripts' => $pre_scripts,
                'post_scripts' => $post_scripts,
                'angular_sgpi_app_extra_dependencies' => $angular_sgpi_app_extra_dependencies
                ]);
        }
        
        /*
        |--------------------------------------------------------------------------
        | data_inicial_admin()
        |--------------------------------------------------------------------------
        | Retorno json con los datos necesarios para la vista de indicadores de proyectos de inv. del administrador
        */          
        public function data_inicial_admin(){
            
            // consulta por la cantidad total de mujeres / hombres en los proyectos de inv
            try{
                $cantidad_mujeres_hombres = Proyecto::cantidad_mujeres_hombres();
                $cantidad_proyectos_final_aprobado = Proyecto::cantidad_proyectos_final_aprobado();
                $sedes_ucc = SedeUCC::all();
                $facultades_x_sedes = FacultadDependenciaUCC::facultades_x_sedes();
                $grupos_inv_x_facultades = GrupoInvestigacionUCC::grupos_inv_x_facultades();
                $cantidad_proys_x_clasificacion = ClasificacionGrupoInvestigacion::cantidad_proys_x_clasificacion();
                $cantidad_proys_x_area = Area::cantidad_proys_x_area();
                
                return json_encode([
                    'consultado' => 1,
                    'cantidad_mujeres_hombres' => $cantidad_mujeres_hombres,
                    'cantidad_proyectos_final_aprobado' => $cantidad_proyectos_final_aprobado,
                    'sedes_ucc' => $sedes_ucc,
                    'facultades_x_sedes' => $facultades_x_sedes,
                    'grupos_inv_x_facultades' => $grupos_inv_x_facultades,
                    'cantidad_proys_x_clasificacion' => $cantidad_proys_x_clasificacion,
                    'cantidad_proys_x_area' => $cantidad_proys_x_area
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
    }
$(document).ready(function() {
	$("#contenedor_tabla_formatos").mCustomScrollbar({
		axis:"x",
		theme: 'dark',
		advanced:{
			autoExpandHorizontalScroll: true
		},
		autoHideScrollbar: true
	});    
})

sgpi_app.controller('formatos_tipos_documentos_controller', function ($scope, Upload) {
    
    $scope.archivo_presupuesto = null;
    $scope.archivo_ppt_proyecto = null;
    $scope.archivo_acta_inicio = null;
    $scope.archivo_informe_avance = null;
    $scope.archivo_desembolso = null;
    $scope.archivo_memoria_academica = null;
    $scope.archivo_acta_finalizacion = null;
    
    // valida el archivo a cargar, retorna true si la validación fue incorrecta
    $scope.validar_archivo = function(nombre_formato){
        if(nombre_formato == 'presupuesto')
            if($scope.archivo_presupuesto == null || $scope.archivo_presupuesto == undefined)
            {
                $scope.presupuesto_invalido = true;
                return true;
            }
            else
            {
                $scope.presupuesto_invalido = false;
                return false;                
            }
        if(nombre_formato == 'presentacion_proyecto')
            if($scope.archivo_ppt_proyecto == null || $scope.archivo_ppt_proyecto == undefined)
            {
                $scope.ppt_proyecto_invalido = true;
                return true;
            }
            else
            {
                $scope.ppt_proyecto_invalido = false;
                return false;                
            }            
        if(nombre_formato == 'acta_inicio')
            if($scope.archivo_acta_inicio == null || $scope.archivo_acta_inicio == undefined)
            {
                $scope.acta_inicio_invalido = true;
                return true;
            }
            else
            {
                $scope.acta_inicio_invalido = false;
                return false;                
            }          
        if(nombre_formato == 'informe_avance')
            if($scope.archivo_informe_avance == null || $scope.archivo_informe_avance == undefined)
            {
                $scope.informe_avance_invalido = true;
                return true;
            }
            else
            {
                $scope.informe_avance_invalido = false;
                return false;                
            }       
        if(nombre_formato == 'desembolso')
            if($scope.archivo_desembolso == null || $scope.archivo_desembolso == undefined)
            {
                $scope.desembolso_invalido = true;
                return true;
            }
            else
            {
                $scope.desembolso_invalido = false;
                return false;                
            }                
        if(nombre_formato == 'memoria_academica')
            if($scope.archivo_memoria_academica == null || $scope.archivo_memoria_academica == undefined)
            {
                $scope.memoria_academica_invalido = true;
                return true;
            }
            else
            {
                $scope.memoria_academica_invalido = false;
                return false;                
            }                
        if(nombre_formato == 'acta_finalizacion')
            if($scope.archivo_acta_finalizacion == null || $scope.archivo_acta_finalizacion == undefined)
            {
                $scope.acta_finalizacion_invalido = true;
                return true;
            }
            else
            {
                $scope.acta_finalizacion_invalido = false;
                return false;                
            }                            
    };
    
    // carga un determinado formato validando el archivo a cargar primero
    $scope.cargar_archivo = function(nombre_formato) {
        
        if($scope.validar_archivo(nombre_formato)) // no es válido el archivo
            return;
        
        var archivo_a_cargar = null;

        $scope.carga_actual = 0;
        $scope.porcentaje_carga = 0;
        archivo_a_cargar = $scope.determinar_archiv_a_cargar(nombre_formato);
        $scope.cargando_algun_archivo = true;
        
        $scope.upload_service = Upload.upload({
            url: '/formatos_tipos_documentos/guardar_nuevo_formato',
            method: 'POST',
            data: {
                archivo: archivo_a_cargar,
                formato: nombre_formato
            }
        });

        // realiza seguimiento de envío a travez del objeto promise
        $scope.upload_service.then(function (response) {
            console.log(response); // imprime respuesta de servidor para propósitos debug
            if(response.data.consultado == 1){
                alertify.success('Formato actualizado');
                $scope.resetear_indicadores_de_carga_archivo();
            }
            else{
                var delay = alertify.get('notifier','delay');
                alertify.set('notifier','delay', 0);
                alertify.error('Error al cargar archivo de formato. Código de error: ' + response.data.codigo);
                alertify.set('notifier','delay', delay);                
                $scope.resetear_indicadores_de_carga_archivo();
            }
        }, function (response) {
            console.log(response);
            var delay = alertify.get('notifier','delay');
            alertify.set('notifier','delay', 0);
            alertify.error('Error XHR o de servidor al cargar archivo de formato. Código de estado: ' + response.status);            
            alertify.set('notifier','delay', delay);                
            $scope.resetear_indicadores_de_carga_archivo();
            
        }, function (evt) {
            // Realiza cáclulo de porcentaje de carga
            $scope.carga_actual = evt.loaded;
            $scope.porcentaje_carga = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
        });                
    };
    
    $scope.determinar_archiv_a_cargar = function(nombre_formato) {
        
        if(nombre_formato == 'presupuesto')
        {
            $scope.total_archivo = $scope.archivo_presupuesto.size;
            $scope.cargando_archivo_presupuesto = true;
            return $scope.archivo_presupuesto;
        }
        else if(nombre_formato == 'presentacion_proyecto')
        {
            $scope.total_archivo = $scope.archivo_ppt_proyecto.size;
            $scope.cargando_archivo_ppt_proyecto = true;
            return $scope.archivo_ppt_proyecto;
        }
        else if(nombre_formato == 'acta_inicio')
        {
            $scope.total_archivo = $scope.archivo_acta_inicio.size;
            $scope.cargando_archivo_ppt_proyecto = true;
            return $scope.archivo_acta_inicio;
        }     
        else if(nombre_formato == 'informe_avance')
        {
            $scope.total_archivo = $scope.archivo_informe_avance.size;
            $scope.cargando_archivo_informe_avance = true;
            return $scope.archivo_informe_avance;
        }             
        else if(nombre_formato == 'desembolso')
        {
            $scope.total_archivo = $scope.archivo_desembolso.size;
            $scope.cargando_archivo_desembolso = true;
            return $scope.archivo_desembolso;
        }                  
        else if(nombre_formato == 'memoria_academica')
        {
            $scope.total_archivo = $scope.archivo_memoria_academica.size;
            $scope.cargando_archivo_memoria_academica = true;
            return $scope.archivo_memoria_academica;
        }                
        else if(nombre_formato == 'acta_finalizacion')
        {
            $scope.total_archivo = $scope.archivo_acta_finalizacion.size;
            $scope.cargando_archivo_acta_finalizacion = true;
            return $scope.archivo_acta_finalizacion;
        }                        
    };
    
    $scope.resetear_indicadores_de_carga_archivo = function() {
        $scope.cargando_algun_archivo = 
        $scope.cargando_archivo_presupuesto = 
        $scope.cargando_archivo_ppt_proyecto = 
        $scope.cargando_archivo_informe_avance = 
        $scope.cargando_archivo_desembolso = 
        $scope.cargando_archivo_acta_finalizacion =
        $scope.cargando_archivo_memoria_academica = false;
    };
    
});
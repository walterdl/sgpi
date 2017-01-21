sgpi_app.controller('editar_grupo_investigacion_controller', function($scope, $http, $window, Alertify){
    
    $scope.data = {
        contador_indice_nuevas_lineas: 0,
        facultades_correspondientes: []
    };
    $scope.visibilidad = {
        cargando_data_inicial: true,
        error_carga_data_inicial: false,
    };
    
    $scope.$watch('data.id_grupo_investigacion', function(){

        // Carga ajax de datos largos necesarios para la vista
        $http({
                method: 'GET',
                url: '/grupos/data_inicial_vista_editar',
                params: {
                    id: $scope.data.id_grupo_investigacion 
                }
            })
            .success(function(data){
                if(data.consultado !== undefined && data.consultado !== null && data.consultado == 1){
                    
                    $scope.data.nombre_original = data.grupo_investigacion.nombre;
                    $scope.data.gran_areas = data.gran_areas;
                    $scope.data.areas = data.areas;
                    $scope.data.lineas_investigacion = data.lineas_investigacion;
                    $scope.data.clasificaciones_grupos_inv = data.clasificaciones_grupos_inv;
                    $scope.data.grupo_investigacion = data.grupo_investigacion;
                    $scope.data.lineas_del_grupo_inv = data.lineas_inv_del_grupo;
                    $scope.data.sedes = data.sedes;
                    $scope.data.facultades = data.facultades;
                    $scope.facultad_original = data.grupo_investigacion.id_facultad_dependencia;
                    $scope.init();
                }
                else{
                    console.log('Error en la carga de datos iniciales, código de error: ' + data.codigo + ', mensaje: ' + data.mensaje);
                    Alertify.error('Error en la carga de datos iniciales, código de error: ' + data.codigo);
                    $scope.visibilidad.error_carga_inicial = true;
                    $scope.visibilidad.error_carga_data_inicial = true;
                    $scope.mensaje_error_carga_inicial = 'Error en la carga de datos iniciales, código de error: ' + data.codigo;
                }
            })
            .error(function(data, status) {
                Alertify.error('Error en la carga de datos iniciales, código de error: ' + status);
                console.log('Error en la carga de datos iniciales, código de error: ' + status);
                console.log(data);
                $scope.visibilidad.error_carga_inicial = true;
                $scope.visibilidad.error_carga_data_inicial = true;
                $scope.mensaje_error_carga_inicial = 'Error en la carga de datos iniciales, código de error: ' + status;
            })
            .finally(function() {
                $scope.visibilidad.cargando_data_inicial = false;
            });
    }); 
    
    $scope.init = function(){
        
        $scope.data.edicion_nombre = $scope.data.grupo_investigacion.nombre;
    
        $scope.data.areas.forEach(function(item) {
            if (item.id == $scope.data.grupo_investigacion.id_area)
                $scope.data.edicion_area = item;
        });
    
        $scope.data.areas.forEach(function(item){
            if (item.id_gran_area == $scope.data.edicion_area.id_gran_area)
                $scope.data.areas_correspondientes.push(item);
        });
    
        $scope.data.gran_areas.forEach(function(item) {
            if (item.id == $scope.data.edicion_area.id_gran_area)
                $scope.data.edicion_gran_area = item;
        });
    
        $scope.data.clasificaciones_grupos_inv.forEach(function(item) {
            if (item.id == $scope.data.grupo_investigacion.id_clasificacion_grupo_investigacion)
                $scope.data.edicion_clasificacion_grupo_inv = item;
        });
    
        $scope.data.sedes.forEach(function(item) {
            if (item.id == $scope.data.grupo_investigacion.id_sede)
                $scope.data.edicion_sede = item;
        });
        
        $scope.data.facultades.forEach(function(item){
            if(item.id_sede == $scope.data.edicion_sede.id){
                $scope.data.facultades_correspondientes.push(item);
                if(item.id == $scope.facultad_original)
                    $scope.data.edicion_facultad = item;
            }
        });
        
        $scope.data.lineas_del_grupo_inv.forEach(function(item1){
            $scope.data.lineas_investigacion.forEach(function(item2){
                if(item2.id == item1.id)
                    $scope.data.edicion_lineas_investigacion.push(item2);
            });
            $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + item1.id + '" name="lineas_existentes[]" value="' + item1.id + '"/>');
        });
    };
    
    $(document).ready(function() {
        // aplica estilos responsivos dependiendo del tamaño actual de la pantalla
        $scope.aplicar_margin_btn_add_linea();
    });
    
    // reigstra controlador de evneto resize de la ventana par aaplicar estilos reponsivos a algunos elementos ui
    $($window).bind('resize', function(){
        $scope.aplicar_margin_btn_add_linea();
    });
    
    // agrega una nueva linea al ui select multiple para su eleccion,
    // valida que no exista la linea en las opciones del multiselect con su nombre
    $scope.btn_add_linea_inv_click = function(){
        
        var existe = false;
        $scope.data.lineas_investigacion.forEach(function(item){
            if($scope.data.nueva_linea_investigacion == item.nombre)
                existe = true;
            else
                existe = false;
        });
        if(!existe){
            
            var nueva_linea = {
                id: $scope.data.contador_indice_nuevas_lineas + 'x',
                nombre: $scope.data.nueva_linea_investigacion
            };
            $scope.data.lineas_investigacion.push(nueva_linea);
            $scope.data.edicion_lineas_investigacion.push(nueva_linea);
            $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + nueva_linea.id + '" name="nuevas_lineas[]" value="' + nueva_linea.nombre + '"/>');
            $scope.data.contador_indice_nuevas_lineas++;
            alertify.success('Línea de investigación agregada');
            $scope.data.nueva_linea_investigacion = null;
        }
        else{
            alertify.notify('La línea de investigación ya está agregada', 'notify');
        }
    };
    
    // controlador de evento para remoción de item de las opciones del multiselect,
    // elimina el inpui hidden que le corresponde a la linea removida
    $scope.eliminacion_linea_inv = function(item){
        console.log('En eliminacion_linea_inv()');
        $('#inputs_lineas_inv input[indice_linea="' + item.id + '"]').remove();
    };
    
    // controlador de evento para seleccion de item de las opciones del multiselect,
    // agreega un input hidden al form para enviar la linea seleccionada
    $scope.seleccion_linea_inv = function(item){
        if(item.id.toString().indexOf('x') != -1)
            $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + item.id + '" name="nuevas_lineas[]" value="' + item.nombre + '"/>');
        else{
            if($scope.data.lineas_del_grupo_inv.length == 0)
                $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + item.id + '" name="lineas_existentes_agregadas[]" value="' + item.id + '"/>');
            else
                $scope.data.lineas_del_grupo_inv.forEach(function(linea_del_grupo_existente){
                    if(linea_del_grupo_existente.id != item.id){
                        $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + item.id + '" name="lineas_existentes_agregadas[]" value="' + item.id + '"/>');
                    }
                    else{
                        if($('#inputs_lineas_inv input[indice_linea="' + item.id + '"]').length == 0){
                            $('#inputs_lineas_inv').append('<input type="text" indice_linea="' + item.id + '" name="lineas_existentes[]" value="' + item.id + '"/>');
                        }
                    }
                });
        }
    };
    
    // aplica estilos para ajustar el input text de otra linea junto con su botón agregar
    $scope.aplicar_margin_btn_add_linea = function(){
        
        if($window.innerWidth < 992){
            $('#btn_add_otra_linea').css('margin-top', '5px');
            $('#col_otra_linea').css('padding-right', '0').css('margin-bottom', '15px');
        }
        else{
            $('#col_otra_linea').css('padding-right', '10px').css('margin-bottom', '0');
            var height_col_otra_linea = $('#col_otra_linea').height();
            var height_btn_add_otra_linea = $('#btn_add_otra_linea').outerHeight(false);
            var margin_top = height_col_otra_linea - height_btn_add_otra_linea;
            $('#btn_add_otra_linea').css('margin-top', margin_top + 'px');
        }
    };
    
    /*
    * validaciones para cada uno de los controles
    */
    $scope.validar_nombre = function(){
        
        if($scope.form_edicion_grupo_inv.nombre.$invalid){
            $scope.visibilidad.show_nombre_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_nombre_invalido = false;
            return false;
        }
    };
    
    $scope.cambia_gran_area = function(){
        
        $scope.data.areas_correspondientes = [];
        $scope.data.edicion_area = null;
        
        $scope.data.areas.forEach(function(item){
            if($scope.data.edicion_gran_area.id == item.id_gran_area)
                $scope.data.areas_correspondientes.push(item);
        });
        
        $scope.validar_gran_area();
        $scope.validar_area();
    };
    
    $scope.validar_gran_area = function(){
        
        if($scope.data.edicion_gran_area == null || $scope.data.edicion_gran_area == undefined){
            $scope.visibilidad.show_gran_area_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_gran_area_invalido = false;
            return false;
        }
    };
    
    $scope.validar_area = function(){
        
        if($scope.data.edicion_area == null || $scope.data.edicion_area == undefined){
            $scope.visibilidad.show_area_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_area_invalido = false;
            return false;
        }
    };
    
    $scope.validar_clasif_grupo_inv = function(){
       
        if($scope.data.edicion_clasificacion_grupo_inv == null || $scope.data.edicion_clasificacion_grupo_inv == undefined){
            $scope.visibilidad.show_clasif_grupo_inv_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_clasif_grupo_inv_invalido = false;
            return false;
        }       
   };
   
    $scope.cambia_sede = function(){
        
        $scope.data.facultades_correspondientes = [];
        $scope.data.edicion_facultad = null;
        if($scope.data.edicion_sede.id != undefined){
            $scope.data.facultades.forEach(function(item){
                if(item.id_sede == $scope.data.edicion_sede.id)
                    $scope.data.facultades_correspondientes.push(item);
            });
        }
        $scope.validar_sede();
        $scope.validar_facultad();
    };   
   
    $scope.validar_sede = function(){
        
        if($scope.data.edicion_sede == null || $scope.data.edicion_sede == undefined){
            $scope.visibilidad.show_sede_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_sede_invalido = false;
            return false;
        }       
   };
   
    $scope.validar_facultad = function(){
        
        if(!$scope.data.edicion_facultad){
            $scope.visibilidad.show_facultad_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_facultad_invalido = false;
            return false;
        }
        
    };
   
    // controlador de evento para click de botoin guiardar nuevo grupo,
    // envia el nombre del grupo de investigacion y el id de la sede para realizar validaciones de logica de negocio antes de enviar los datos editados
    $scope.btn_guardar_click = function(){
        
        if($scope.visibilidad.show_nombre_invalido || $scope.visibilidad.show_gran_area_invalido || 
            $scope.visibilidad.show_area_invalido || $scope.visibilidad.show_clasif_grupo_inv_invalido || 
            $scope.visibilidad.show_sede_invalido || $scope.visibilidad.show_facultad_invalido){
                
            Alertify.error('Los datos provistos no son correctos');
            return;
        }
        
        $scope.visibilidad.show_procesando_envio = true;
        $http({
                method: 'GET',
                url: '/grupos/validar_edicion_grupo_inv',
                params: {
                    nombre_grupo: $scope.data.edicion_nombre,
                    nombre_grupo_cambiado: $scope.data.nombre_original == $scope.data.edicion_nombre ? 0 : 1,
                    id_sede: $scope.data.edicion_sede.id
                }
            })
            .success(function(data){
                if(data.valido == undefined || data.valido == null){
                    console.log('Error en el procesamiento de la edicion del grupo de investigación; ' + data.mensaje + ', ' + data.codigo);
                    console.log(data);
                    Alertify.error('Error de servidor. Codigo de error: '+ data.codigo);
                }
                if(data.valido === 'false')
                    Alertify.error(data.mensaje_validacion);
                else if(data.valido === 'true')
                    $('#guardar').trigger('click');
            })
            .error(function(data, status) {
                Alertify.error('Un error ha ocurrido. Codigo de estado: ' + status);
                console.log('Error realizando ultimas validacione sdel lado del servidor');
                console.log(data);
            })
            .finally(function() {
                $scope.visibilidad.show_procesando_envio = false;
            });
    };
    
});
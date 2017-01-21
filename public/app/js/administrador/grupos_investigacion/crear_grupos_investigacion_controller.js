sgpi_app.controller('crear_grupos_investigacion_controller', function($scope, $http, Alertify, $window){
    
    $scope.data = {
        nueva_linea_investigacion: "",
        lineas_investigacion: [],
        contador_nuevas_lineas: 0, 
        btn_guardar_seleccionado: false,
        facultades_correspondientes: []
    };
    $scope.visibilidad = {
        cargando_data_inicial: true,
        error_carga_data_inicial: false,
        show_procesando_envio: false,
    };
    
    // Carga ajax de datos largos necesarios para la vista
    $http({
            method: 'GET',
            url: '/grupos/data_inicial_vista_crear',
        })
        .success(function(data){
            if(data.consultado !== undefined || data.consultado !== null){
                
                $scope.data.todas_las_areas = data.areas;
                $scope.data.clasificaciones_grupos_inv = data.clasificaciones_grupos_inv;
                $scope.data.lineas_investigacion = data.lineas_investigacion;
                $scope.data.sedes = data.sedes;
                $scope.data.facultades = data.facultades_dependencias;
                $scope.data.gran_areas = data.gran_areas;
                $scope.data.lineas_investigacion_seleccionadas=[];
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
    
    $(document).ready(function(){
        // aplica estilos responsivos dependiendo del tamaño actual de la pantalla
        $scope.aplicar_margin_btn_add_linea();
        $scope.ajustar_ancho_chosen_select();
    });
    
    // reigstra controlador de evneto resize de la ventana par aaplicar estilos reponsivos a algunos elementos ui
    $($window).bind('resize', function(){
        $scope.aplicar_margin_btn_add_linea();
    });
    
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
    
    // agrega las areas al ui select en función de la gran area seleccionada
    $scope.cambia_gran_area = function(){
        
        $scope.data.areas = [];
        $scope.data.area = null;
        $scope.data.todas_las_areas.forEach(function(currentValue){
            if(currentValue.id_gran_area == $scope.data.gran_area.id)
                $scope.data.areas.push(currentValue);
        });
        $scope.validar_gran_area();
    };
    
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
                id: $scope.data.contador_nuevas_lineas + 'x',
                nombre: $scope.data.nueva_linea_investigacion
            };
            $scope.data.lineas_investigacion.push(nueva_linea);
            $scope.data.lineas_investigacion_seleccionadas.push(nueva_linea);
            
            $('#inputs_lineas_inv_seleccionadas').append('<input type="text" indice_linea="' + nueva_linea.id + '" name="nuevas_lineas[]" value="' + nueva_linea.nombre + '"/>');
            $scope.data.contador_nuevas_lineas++;
            
            Alertify.success('Línea de investigación agregada');
            $scope.data.nueva_linea_investigacion = null;
            $scope.validar_lineas_inv();
        }
        else{
            alertify.notify('La línea de investigación ya está agregada', 'notify');
        }
    };
    
    // controlador de evento para seleccion de item de las opciones del multiselect,
    // agrewga un input hidden al form para enviar la linea seleccionada
    $scope.seleccion_linea_inv = function(item){
        if(item.id.toString().indexOf('x') != -1)
            $('#inputs_lineas_inv_seleccionadas').append('<input type="text" indice_linea="' + item.id + '" name="nuevas_lineas[]" value="' + item.nombre + '"/>');
        else
            $('#inputs_lineas_inv_seleccionadas').append('<input type="text" indice_linea="' + item.id + '" name="lineas_existentes[]" value="' + item.id + '"/>');
    };
    
    // controlador de evento para remoción de item de las opciones del multiselect,
    // elimina el inpui hidden que le corresponde a la linea removida
    $scope.eliminacion_linea_inv = function(item){
        if(item.id.toString().indexOf('x') != -1)
            $('#inputs_lineas_inv_seleccionadas input[indice_linea="' + item.id + 'x"]').remove();
        else
            $('#inputs_lineas_inv_seleccionadas input[indice_linea="' + item.id + '"]').remove();
    };
    
    // controlador de evento para click de boton guardar nuevo grupo,
    // aplica validaciones para eviar un envio erroneo de datos
    $scope.btn_guardar_click = function(){
        
        $scope.btn_guardar_seleccionado = true;
        var validaciones = [
                $scope.validar_nombre(), $scope.validar_gran_area(), $scope.validar_area(), $scope.validar_clasif_grupo_inv(), $scope.validar_sede(), $scope.validar_lineas_inv(), $scope.validar_facultad()
            ];
        var validacion_incorrecta = validaciones.indexOf(true);
        if(validacion_incorrecta >= 0){}
        else{
            $scope.visibilidad.show_procesando_envio = true;
            $http({
                    method: 'GET',
                    url: '/grupos/consultar_nombre_grupo',
                    params: {
                        nombre: $scope.data.nombre,
                    }
                })
                .success(function(data){
                    if(data === 'true')
                        Alertify.error('El grupo de investigación con este nombre ya existe');
                    else if(data === 'false')
                        $('#guardar').trigger('click')
                    else{
                        console.log('Error en el procesamiento de nombre de grupo de investigacion duplicado. ' + data.mensaje + ', ' + data.codigo);
                        Alertify.error('Error de servidor. Codigo de error: '+ data.codigo);
                    }
                })
                .error(function(data, status) {
                    Alertify.error('Un error ha ocurrido. Codigo de estado: ' + status);
                    console.log('Error consultando los grupos de investigacion de la sede');
                    console.log(data);
                })
                .finally(function() {
                    $scope.visibilidad.show_procesando_envio = false;
                });
        }
    };
    
    // validaciones de campos
    $scope.validar_nombre = function(){
        if($scope.form_nuevo_grupo_inv.nombre.$invalid){
            $scope.visibilidad.show_nombre_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_nombre_invalido = false;
            return false;
        }
    };
    
    $scope.validar_gran_area = function(){
        if($scope.data.gran_area == null || $scope.data.gran_area == undefined){
            $scope.visibilidad.show_gran_area_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_gran_area_invalido = false;
            return false;
        }
    };
    
    $scope.validar_area = function(){
        if($scope.data.area == null || $scope.data.area == undefined){
            $scope.visibilidad.show_area_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_area_invalido = false;
            return false;
        }
    };
   
    $scope.validar_clasif_grupo_inv = function(){
        if($scope.data.clasificacion_grupo_inv == null || $scope.data.clasificacion_grupo_inv == undefined){
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
        $scope.data.facultad = null;
        if($scope.data.sede.id != undefined){
            $scope.data.facultades.forEach(function(item){
                if(item.id_sede == $scope.data.sede.id)
                    $scope.data.facultades_correspondientes.push(item);
            });
        }
        $scope.validar_sede();
        $scope.validar_facultad();
    };
   
    $scope.validar_sede = function(){
        
        if(!$scope.data.sede){
            $scope.visibilidad.show_sede_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_sede_invalido = false;
            return false;
        }
   };
    
    $scope.validar_lineas_inv = function(){
        if($scope.data.lineas_investigacion_seleccionadas == null || $scope.data.lineas_investigacion_seleccionadas.length == 0){
            $scope.visibilidad.show_lineas_inv_invalido = true;
            return true;
        }
        else{
            $scope.visibilidad.show_lineas_inv_invalido = false;
            return false;
        }
    };
    
    $scope.validar_facultad = function(){
        
        if($scope.data.facultad){
            $scope.visibilidad.show_facultad_invalido = false;
            return false;
        }
        else{
            $scope.visibilidad.show_facultad_invalido = true;
            return true;
        }
        
    };
  
});
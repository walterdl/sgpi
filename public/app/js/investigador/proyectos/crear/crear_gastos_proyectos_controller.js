sgpi_app.controller('crear_gastos_proyectos_controller', function ($scope, $http) {
    
    // Inicialización de variables y/o modelos
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };        
    
    $scope.data.entidades_presupuesto_seleccionadas = [];
    $scope.data.totales_personal = {ucc: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_equipos = [];
    $scope.data.totales_equipos = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_software = [];
    $scope.data.totales_software = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_salidas = [];
    $scope.data.totales_salidas = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_materiales = [];
    $scope.data.totales_materiales = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_servicios_tecnicos = [];
    $scope.data.totales_servicios_tecnicos = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_bibliograficos = [];
    $scope.data.totales_bibliograficos = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};
    
    $scope.data.gastos_digitales = [];
    $scope.data.totales_digitales = {ucc: 0, conadi: 0, otras_entidades_presupuesto: [], total: 0};    
    
    $scope.data.msj_nueva_entidadPresupuesto_incorrecto = '';
    $scope.visibilidad.nueva_entidadPresupuesto_incorrecto = false;
    $scope.data.contador_nuevas_entidades_presupuesto = 0;
    
    /*
	|--------------------------------------------------------------------------
	| agregar_nueva_entidadPresupuesto()
	|--------------------------------------------------------------------------
	| Controlador de evento click para le botón que agrega una nueva entidad de fuente de presuúesto.
	| Añade otra entidad fuente presupuesto a la colección de entidades_fuente_presupuesto.
	| Añade un input hidden para crear la nueva entidad en la BD
	| Llama al metodo que agrega la entidad a la coleccion de otras_entidades_presupuesto de cada tipo de gasto
	*/        
    $scope.agregar_nueva_entidadPresupuesto = function() {
        if($scope.data.nueva_entidad_entidad_presupuesto != null && $scope.data.nueva_entidad_entidad_presupuesto.length > 0){
            var existe_entidadPresupuesto = false;
            $scope.data.entidades_fuente_presupuesto.forEach(function(item) {
                if(item.nombre == $scope.data.nueva_entidad_entidad_presupuesto)
                    existe_entidadPresupuesto = true;
            })
            if(existe_entidadPresupuesto){
                $scope.data.msj_nueva_entidadPresupuesto_incorrecto = 'Entidad de fuente de presupuesto ya agregada';
                $scope.visibilidad.nueva_entidadPresupuesto_incorrecto = true;                            
                return;
            }
            
            var nueva_entidad_presupuesto = {
                id: $scope.data.contador_nuevas_entidades_presupuesto + 'x', // la 'x' significa que se trata de una entidad añadida por el usuario y no se trata de una ya existente en la BD
                nombre: $scope.data.nueva_entidad_entidad_presupuesto
            }
            $scope.data.entidades_fuente_presupuesto.push(nueva_entidad_presupuesto);
            $scope.data.entidades_presupuesto_seleccionadas.push(nueva_entidad_presupuesto);
            $scope.agregar_entidad_presupuesto_a_gastos(nueva_entidad_presupuesto);
            $scope.data.contador_nuevas_entidades_presupuesto++;
            $('#inputs_nuevas_entidades_fuente_presupuesto')
                .append('<input type="text" indice_entidad_presupuesto="' + nueva_entidad_presupuesto.id + '" name="nuevas_entidad_presupuesto[]" value="' + nueva_entidad_presupuesto.id + '_' + nueva_entidad_presupuesto.nombre + '"/>');
            $scope.data.nueva_entidad_entidad_presupuesto = null;
        }
        else{
            $scope.data.msj_nuevo_financiador_incorrecto = 'Dato incorrecto';
            $scope.visibilidad.nueva_entidadPresupuesto_incorrecto = true;            
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_entidad_presupuesto_a_gastos()
	|--------------------------------------------------------------------------
	| Añade la nueva entidad de presupuesto seleccionada en el multiselect de entidades de fuente de presupuesto
	| a la coleccion de otras_entidades_presupuesto de cada tipo de gasto.
	| También añade la entidad de presupuestoa la coleccion otras_entidades_presupuesto de los totales de cada tipo de gasto
	*/      
    $scope.agregar_entidad_presupuesto_a_gastos = function(entidad_presupuesto) {
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de gastos bibliográficos
        $scope.data.gastos_bibliograficos.forEach(function(gasto_bibliografico) {
            gasto_bibliografico.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_bibliograficos.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de gastos de personal
        $scope.data.participantes_proyecto.forEach(function(participante_proyecto) {
            participante_proyecto.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_personal.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;        
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gastos_equipos
        $scope.data.gastos_equipos.forEach(function(gasto_equipo) {
            gasto_equipo.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_equipos.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gastos_software
        $scope.data.gastos_software.forEach(function(gasto_software) {
            gasto_software.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_software.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;        
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gastos_salida
        $scope.data.gastos_salidas.forEach(function(gasto_salida) {
            gasto_salida.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_salidas.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;                
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gasto_material
        $scope.data.gastos_materiales.forEach(function(gasto_material) {
            gasto_material.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_materiales.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gasto_servicio_tecnico
        $scope.data.gastos_servicios_tecnicos.forEach(function(gasto_servicio) {
            gasto_servicio.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_servicios_tecnicos.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;                                
        
        // agrega la otra entidad a la coleccion otras_entidades_presupuesto de cada gasto_digital
        $scope.data.gastos_digitales.forEach(function(gasto_digital) {
            gasto_digital.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;
        });
        $scope.data.totales_digitales.otras_entidades_presupuesto[entidad_presupuesto.id] = 0;                                        
        
    };

    /*
	|--------------------------------------------------------------------------
	| seleccion_entidad_presupuesto()
	|--------------------------------------------------------------------------
	| Controlador de evento para seleccion de item de las opciones del multiselect de entidades de fuente de presupuesto.
    | Añade un input hidden para crear la nueva entidad en la BD
    | Llama al metodo que agrega la entidad a la coleccion de otras_entidades_presupuesto de cada tipo de gasto
	*/       
    $scope.seleccion_entidad_presupuesto = function(entidad_presupuesto) {
        if(entidad_presupuesto.id.toString().indexOf('x') != -1)
            $('#inputs_nuevas_entidades_fuente_presupuesto').append('<input type="text" indice_entidad_presupuesto="' + entidad_presupuesto.id + '" name="nuevas_entidad_presupuesto[]" value="' + entidad_presupuesto.id + '_' + entidad_presupuesto.nombre + '"/>');
        else
            $('#inputs_nuevas_entidades_fuente_presupuesto').append('<input type="text" indice_entidad_presupuesto="' + entidad_presupuesto.id + '" name="entidad_presupuesto_existentes[]" value="' + entidad_presupuesto.id + '"/>');
        $scope.agregar_entidad_presupuesto_a_gastos(entidad_presupuesto);
    };
    
    /*
	|--------------------------------------------------------------------------
	| remocion_entidad_presupuesto()
	|--------------------------------------------------------------------------    
    | Controlador de evento para remoción de item de las opciones del multiselect de entidades de fuente de presupuesto
    | elimina el input hidden que le corresponde a la entidad fuente de presupuesto removida.
	| Tambien remueve el objeto de entidad_presupuesto de la colección de otras_entidades_presupuesto de cada tipo de gasto.
    | Realiza la resta del valor o dinero que daba la respectiva entidad a cada tipo de gasto y al total general.
    | También remueve la entidad_presupuesto de la coleccion de otras_entidades_presupuesto de los totales de cada gasto, restado sus respectivos valores de los totales.
    */
    $scope.remocion_entidad_presupuesto = function(entidad_presupuesto) {
        
        $('#inputs_nuevas_entidades_fuente_presupuesto input[indice_entidad_presupuesto="' + entidad_presupuesto.id + '"]').remove();        
        
        // actualiza totales de gastos bibliograficos con los valores de la entidad_presupuesto a remover
        var totales_bibliograficos = 0;
        $scope.data.gastos_bibliograficos.forEach(function(gasto_bibliografico) {
            gasto_bibliografico.total -= gasto_bibliografico.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_bibliografico.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_bibliografico.otras_entidades_presupuesto.splice(index, 1);        
            totales_bibliograficos += gasto_bibliografico.total;
        });            
        $scope.data.totales_bibliograficos.total = totales_bibliograficos;
        
        // actualiza totales de personal con los valores de la entidad_presupuesto a remover
        var totales_personal = 0;
        $scope.data.participantes_proyecto.forEach(function(participante_proyecto) {
            participante_proyecto.presupuesto_total -= participante_proyecto.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = participante_proyecto.otras_entidades_presupuesto[entidad_presupuesto.id];
            participante_proyecto.otras_entidades_presupuesto.splice(index, 1);        
            totales_personal += participante_proyecto.presupuesto_total;
        });            
        $scope.data.totales_personal.total = totales_personal;        
        
        // actualiza totales de equipos con los valores de la entidad_presupuesto a remover
        var totales_equipos = 0;
        $scope.data.gastos_equipos.forEach(function(gasto_equipo) {
            gasto_equipo.total -= gasto_equipo.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_equipo.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_equipo.otras_entidades_presupuesto.splice(index, 1);        
            totales_equipos += gasto_equipo.total;
        });            
        $scope.data.totales_equipos.total = totales_equipos;
        
        // actualiza totales de software con los valores de la entidad_presupuesto a remover
        var totales_software = 0;
        $scope.data.gastos_software.forEach(function(gasto_software) {
            gasto_software.total -= gasto_software.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_software.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_software.otras_entidades_presupuesto.splice(index, 1);        
            totales_software += gasto_software.total;
        });            
        $scope.data.totales_software.total = totales_software;
        
        // actualiza totales de salidas con los valores de la entidad_presupuesto a remover
        var totales_salidas = 0;
        $scope.data.gastos_salidas.forEach(function(gasto_salida) {
            gasto_salida.total -= gasto_salida.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_salida.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_salida.otras_entidades_presupuesto.splice(index, 1);        
            totales_salidas += gasto_salida.total;
        });            
        $scope.data.totales_salidas.total = totales_salidas;                
        
        
        // actualiza totales de materiales con los valores de la entidad_presupuesto a remover
        var totales_materiales = 0;
        $scope.data.gastos_materiales.forEach(function(gasto_material) {
            gasto_material.total -= gasto_material.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_material.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_material.otras_entidades_presupuesto.splice(index, 1);        
            totales_materiales += gasto_material.total;
        });            
        $scope.data.totales_materiales.total = totales_materiales;
        
        // actualiza totales de servicios tecnicos con los valores de la entidad_presupuesto a remover
        var totales_servicios_tecnicos = 0;
        $scope.data.gastos_servicios_tecnicos.forEach(function(gasto_servicio) {
            gasto_servicio.total -= gasto_servicio.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_servicio.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_servicio.otras_entidades_presupuesto.splice(index, 1);        
            totales_servicios_tecnicos += gasto_servicio.total;
        });            
        $scope.data.totales_servicios_tecnicos.total = totales_servicios_tecnicos;        
        
        
        // actualiza totales de gastos digitales con los valores de la entidad_presupuesto a remover
        var totales_digitales = 0;
        $scope.data.gastos_digitales.forEach(function(gasto_digital) {
            gasto_digital.total -= gasto_digital.otras_entidades_presupuesto[entidad_presupuesto.id];
            var index = gasto_digital.otras_entidades_presupuesto[entidad_presupuesto.id];
            gasto_digital.otras_entidades_presupuesto.splice(index, 1);        
            totales_digitales += gasto_digital.total;
        });            
        $scope.data.totales_digitales.total = totales_digitales;                
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_gasto_equipo()
	|--------------------------------------------------------------------------
	| Añade un gasto de equipo
	*/            
    $scope.agregar_gasto_equipo = function() {
        
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_equipos.push({
            equipo: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });        
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_equipo()
	|--------------------------------------------------------------------------
	| Remueve un gasto de equipo determinado de la colección de gastos_equipos
	*/                
    $scope.remover_gasto_equipo = function(gasto_equipo) {
        var index_gasto_equipo = $scope.data.gastos_equipos.indexOf(gasto_equipo);
        if(index_gasto_equipo != -1){
            $scope.data.totales_equipos.ucc -= gasto_equipo.presupuesto_ucc;
            $scope.data.totales_equipos.conadi -= gasto_equipo.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_equipos.otras_entidades_presupuesto[item.id] -= gasto_equipo.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_equipos.total -= gasto_equipo.total;            
            $scope.data.gastos_equipos.splice(index_gasto_equipo, 1);
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_gasto_software()
	|--------------------------------------------------------------------------
	| Añade un gasto de software
	*/                
    $scope.agregar_gasto_software = function() {
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_software.push({
            software: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            total: 0
        });        
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_software()
	|--------------------------------------------------------------------------
	| Remueve un gasto de software determinado de la colección de gastos_software
	*/                
    $scope.remover_gasto_software = function(gasto_software) {
        var index_gasto_software = $scope.data.gastos_equipos.indexOf(gasto_software);
        if(index_gasto_software != -1){
            $scope.data.totales_software.ucc -= gasto_software.presupuesto_ucc;
            $scope.data.totales_software.conadi -= gasto_software.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_software.otras_entidades_presupuesto[item.id] -= gasto_software.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_software.total -= gasto_software.total;            
            $scope.data.gastos_equipos.splice(index_gasto_software, 1);
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_salida_campo()
	|--------------------------------------------------------------------------
	| Agrega un gasto de salida de campo
	*/                
    $scope.agregar_salida_campo = function() {
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_salidas.push({
            justificacion: null,
            cantidad_salidas: 0,
            valor_unitario: 0,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });        
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_salida()
	|--------------------------------------------------------------------------
	| Remueve un gasto de salida de campo determinada de la colección de gastos_salidas
	*/    
    $scope.remover_gasto_salida = function(gasto_salida) {
        var index_gasto_salida = $scope.data.gastos_salidas.indexOf(gasto_salida);
        if(index_gasto_salida != -1){
            $scope.data.totales_salidas.ucc -= gasto_salida.presupuesto_ucc;
            $scope.data.totales_salidas.conadi -= gasto_salida.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_salidas.otras_entidades_presupuesto[item.id] -= gasto_salida.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_salidas.total -= gasto_salida.total;            
            $scope.data.gastos_salidas.splice(index_gasto_salida, 1);
        }
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_material()
	|--------------------------------------------------------------------------
	| Agrega un gasto de material / suministro
	*/       
    $scope.agregar_material = function() {
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_materiales.push({
            material: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });        
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_material()
	|--------------------------------------------------------------------------
	| Remueve un gasto de material / suministro determinado de la colección de gastos_materiales
	*/           
    $scope.remover_gasto_material = function(gasto_material) {
        var index_gasto_material = $scope.data.gastos_materiales.indexOf(gasto_material);
        if(index_gasto_material != -1){
            $scope.data.totales_materiales.ucc -= gasto_material.presupuesto_ucc;
            $scope.data.totales_materiales.conadi -= gasto_material.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_materiales.otras_entidades_presupuesto[item.id] -= gasto_material.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_materiales.total -= gasto_material.total;                        
            $scope.data.gastos_materiales.splice(index_gasto_material, 1);
        }                        
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_servicio_tecnico()
	|--------------------------------------------------------------------------
	| Agrega un gasto de servicio técnico
	*/           
    $scope.agregar_servicio_tecnico = function() {
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_servicios_tecnicos.push({
            servicio: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });        
    };
   
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_servicio()
	|--------------------------------------------------------------------------
	| Remueve un gasto de servicio técnico determinado de la colección de gastos_servicios_tecnicos
	*/               
    $scope.remover_gasto_servicio = function(gasto_servicio) {
        var index_gasto_servicio = $scope.data.gastos_servicios_tecnicos.indexOf(gasto_servicio);
        if(index_gasto_servicio != -1){
            
            $scope.data.totales_servicios_tecnicos.ucc -= gasto_servicio.presupuesto_ucc;
            $scope.data.totales_servicios_tecnicos.conadi -= gasto_servicio.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_servicios_tecnicos.otras_entidades_presupuesto[item.id] -= gasto_servicio.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_servicios_tecnicos.total -= gasto_servicio.total;                                    
            
            $scope.data.gastos_servicios_tecnicos.splice(index_gasto_servicio, 1);
        }        
    };
    
    /*
	|--------------------------------------------------------------------------
	| agregar_recurso_bibliografico()
	|--------------------------------------------------------------------------
	| Agrega un gasto de recuros bibliografico
	*/               
    $scope.agregar_recurso_bibliografico = function() {
        
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_bibliograficos.push({
            titulo: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });
    };
    
    /*
	|--------------------------------------------------------------------------
	| remover_gasto_bibliografico()
	|--------------------------------------------------------------------------
	| Remueve un gasto de recurso bibliografico determinado de la colección de gastos_bibliograficos.
	| También resta los valores que sumaba el gasto_bibliografico a los totoales generales
	*/                   
    $scope.remover_gasto_bibliografico = function(gasto_bibliografico) {
        
        var index_gasto_bibliografico = $scope.data.gastos_bibliograficos.indexOf(gasto_bibliografico);
        if(index_gasto_bibliografico != -1){
            
            $scope.data.totales_bibliograficos.ucc -= gasto_bibliografico.presupuesto_ucc;
            $scope.data.totales_bibliograficos.conadi -= gasto_bibliografico.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_bibliograficos.otras_entidades_presupuesto[item.id] -= gasto_bibliografico.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_bibliograficos.total -= gasto_bibliografico.total;
            $scope.data.gastos_bibliograficos.splice(index_gasto_bibliografico, 1);
        }                
    };

    /*
	|--------------------------------------------------------------------------
	| agregar_recurso_digital()
	|--------------------------------------------------------------------------
	| Agrega un gasto de recuros digital
	*/     
    $scope.agregar_recurso_digital = function() {
        var otras_entidades_presupuesto = [];
        if($scope.data.entidades_presupuesto_seleccionadas  != null && $scope.data.entidades_presupuesto_seleccionadas != undefined)
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                otras_entidades_presupuesto[item.id] = 0;
            });
        
        $scope.data.gastos_digitales.push({
            titulo: null, 
            justificacion: null,
            presupuesto_ucc: 0,
            presupuesto_conadi: 0,
            otras_entidades_presupuesto: otras_entidades_presupuesto,
            presupuesto_externo_invalido: [],
            fecha_ejecucion: null,
            total: 0
        });        
    };

    /*
	|--------------------------------------------------------------------------
	| remover_gasto_digital()
	|--------------------------------------------------------------------------
	| Remueve un gasto de recurso digital determinado de la colección de gastos_digitales.
	| También resta los valores que sumaba el gasto_digital a los totales generales
	*/            
    $scope.remover_gasto_digital = function(gasto_digital) {
        var index_gasto_digital = $scope.data.gastos_digitales.indexOf(gasto_digital);
        if(index_gasto_digital != -1){
            
            $scope.data.totales_digitales.ucc -= gasto_digital.presupuesto_ucc;
            $scope.data.totales_digitales.conadi -= gasto_digital.presupuesto_conadi;
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(item) {
                $scope.data.totales_digitales.otras_entidades_presupuesto[item.id] -= gasto_digital.otras_entidades_presupuesto[item.id];
            })
            $scope.data.totales_digitales.total -= gasto_digital.total;
            $scope.data.gastos_digitales.splice(index_gasto_digital, 1);
        }                   
    };
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_personal()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de personal.
	| También calcula los totales generales de los gastos de personal
	*/                           
    $scope.suma_totales_personal = function(participante, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el de personal específico
        var total_otras_entidades_gasto_personal = 0;
        
        for(var id in participante.otras_entidades_presupuesto){
            total_otras_entidades_gasto_personal += participante.otras_entidades_presupuesto[id];
        }
        participante.presupuesto_total = participante.presupuesto_ucc + total_otras_entidades_gasto_personal;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_participante(participante);
            var total_ucc = 0;
            $scope.data.participantes_proyecto.forEach(function(participante_proyecto) {
                total_ucc += participante_proyecto.presupuesto_ucc;
            });
            $scope.data.totales_personal.ucc = total_ucc;
        }        
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_participante(participante, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.participantes_proyecto.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_personal.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }        
        
        // Suma el total general        
        $scope.data.totales_personal.total = 0;
        $scope.data.participantes_proyecto.forEach(function(item) {
            $scope.data.totales_personal.total += item.presupuesto_total;
        });        
    };
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_equipos()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de equipo.
	| También calcula los totales generales de los gastos de equipos
	*/                               
    $scope.suma_totales_equipos = function(gasto_equipo, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el gasto_equipo específico
        var total_otras_entidades_gasto_equipo = 0;
        
        for(var id in gasto_equipo.otras_entidades_presupuesto){
            total_otras_entidades_gasto_equipo += gasto_equipo.otras_entidades_presupuesto[id];
        }
        gasto_equipo.total = gasto_equipo.presupuesto_ucc + gasto_equipo.presupuesto_conadi + total_otras_entidades_gasto_equipo;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_equipo(gasto_equipo);
            var total_ucc = 0;
            $scope.data.gastos_equipos.forEach(function(item) {
                total_ucc += item.presupuesto_ucc;
            });
            $scope.data.totales_equipos.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_equipo(gasto_equipo);
            var total_conadi = 0;
            $scope.data.gastos_equipos.forEach(function(item) {
                total_conadi += item.presupuesto_conadi;
            });     
            $scope.data.totales_equipos.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_equipo(gasto_equipo, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_equipos.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_equipos.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_equipos.total = 0;
        $scope.data.gastos_equipos.forEach(function(item) {
            $scope.data.totales_equipos.total += item.total;
        });        
    }
    
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_equipos()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de equipo.
	| También calcula los totales generales de los gastos de equipos
	*/                               
    $scope.suma_totales_software = function(gasto_software, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el gasto_equipo específico
        var total_otras_entidades_gasto_software = 0;
        
        for(var id in gasto_software.otras_entidades_presupuesto){
            total_otras_entidades_gasto_software += gasto_software.otras_entidades_presupuesto[id];
        }
        gasto_software.total = gasto_software.presupuesto_ucc + gasto_software.presupuesto_conadi + total_otras_entidades_gasto_software;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_software(gasto_software);
            var total_ucc = 0;
            $scope.data.gastos_software.forEach(function(item) {
                total_ucc += item.presupuesto_ucc;
            });
            $scope.data.totales_software.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_software(gasto_software);
            var total_conadi = 0;
            $scope.data.gastos_software.forEach(function(item) {
                total_conadi += item.presupuesto_conadi;
            });     
            $scope.data.totales_software.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_software(gasto_software, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_software.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_software.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_software.total = 0;
        $scope.data.gastos_software.forEach(function(item) {
            $scope.data.totales_software.total += item.total;
        });        
    }
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_salidas()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de salida.
	| También calcula los totales generales de los gastos de salidas
	*/                               
    $scope.suma_totales_salidas = function(gasto_salida, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el gasto_equipo específico
        var total_otras_entidades_gasto_software = 0;
        
        for(var id in gasto_salida.otras_entidades_presupuesto){
            total_otras_entidades_gasto_software += gasto_salida.otras_entidades_presupuesto[id];
        }
        gasto_salida.total = gasto_salida.presupuesto_ucc + gasto_salida.presupuesto_conadi + total_otras_entidades_gasto_software;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_salida(gasto_salida);
            var total_ucc = 0;
            $scope.data.gastos_salidas.forEach(function(item) {
                total_ucc += item.presupuesto_ucc;
            });
            $scope.data.totales_salidas.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_salida(gasto_salida);
            var total_conadi = 0;
            $scope.data.gastos_salidas.forEach(function(item) {
                total_conadi += item.presupuesto_conadi;
            });     
            $scope.data.totales_salidas.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_salida(gasto_salida, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_salidas.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_salidas.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_salidas.total = 0;
        $scope.data.gastos_salidas.forEach(function(item) {
            $scope.data.totales_salidas.total += item.total;
        });        
    }    
    
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_materiales()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de material.
	| También calcula los totales generales de los gastos de materiales
	*/                               
    $scope.suma_totales_materiales = function(gasto_material, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el gasto_equipo específico
        var total_otras_entidades_gasto_material = 0;
        
        for(var id in gasto_material.otras_entidades_presupuesto){
            total_otras_entidades_gasto_material += gasto_material.otras_entidades_presupuesto[id];
        }
        gasto_material.total = gasto_material.presupuesto_ucc + gasto_material.presupuesto_conadi + total_otras_entidades_gasto_material;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_material(gasto_material);
            var total_ucc = 0;
            $scope.data.gastos_materiales.forEach(function(item) {
                total_ucc += item.presupuesto_ucc;
            });
            $scope.data.totales_materiales.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_material(gasto_material);
            var total_conadi = 0;
            $scope.data.gastos_materiales.forEach(function(item) {
                total_conadi += item.presupuesto_conadi;
            });     
            $scope.data.totales_materiales.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_material(gasto_material, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_materiales.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_materiales.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_materiales.total = 0;
        $scope.data.gastos_materiales.forEach(function(item) {
            $scope.data.totales_materiales.total += item.total;
        });        
    }        
    
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_servicios_tecnicos()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto de servicio tecnico.
	| También calcula los totales generales de los gastos de servicios tecnicos
	*/                               
    $scope.suma_totales_servicios_tecnicos = function(gasto_servicio, entidad_presupuesto, id_entidad_presupuesto){
        
        // actualiza los totales para el gasto_equipo específico
        var total_otras_entidades_gasto_servicio = 0;
        
        for(var id in gasto_servicio.otras_entidades_presupuesto){
            total_otras_entidades_gasto_servicio += gasto_servicio.otras_entidades_presupuesto[id];
        }
        gasto_servicio.total = gasto_servicio.presupuesto_ucc + gasto_servicio.presupuesto_conadi + total_otras_entidades_gasto_servicio;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_servicio(gasto_servicio);
            var total_ucc = 0;
            $scope.data.gastos_servicios_tecnicos.forEach(function(item) {
                total_ucc += item.presupuesto_ucc;
            });
            $scope.data.totales_servicios_tecnicos.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_servicio(gasto_servicio);
            var total_conadi = 0;
            $scope.data.gastos_servicios_tecnicos.forEach(function(item) {
                total_conadi += item.presupuesto_conadi;
            });     
            $scope.data.totales_servicios_tecnicos.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_servicio(gasto_servicio, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_servicios_tecnicos.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_servicios_tecnicos.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_servicios_tecnicos.total = 0;
        $scope.data.gastos_servicios_tecnicos.forEach(function(item) {
            $scope.data.totales_servicios_tecnicos.total += item.total;
        });        
    }            
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_bibliograficos()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto bibliográfico.
	| También calcula los totales generales de los gastos bibliograficos
	*/                       
    $scope.suma_totales_bibliograficos = function(gasto_bibliografico, entidad_presupuesto, id_entidad_presupuesto) {
        
        // actualiza los totales para el gasto_bibliografico específico
        var total_otras_entidades_gasto_bibliografico = 0;
        
        for(var id in gasto_bibliografico.otras_entidades_presupuesto){
            total_otras_entidades_gasto_bibliografico += gasto_bibliografico.otras_entidades_presupuesto[id];
        }
        gasto_bibliografico.total = gasto_bibliografico.presupuesto_ucc + gasto_bibliografico.presupuesto_conadi + total_otras_entidades_gasto_bibliografico;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_bibliografico(gasto_bibliografico);
            var total_ucc = 0;
            $scope.data.gastos_bibliograficos.forEach(function(gasto_bibliografico) {
                total_ucc += gasto_bibliografico.presupuesto_ucc;
            });
            $scope.data.totales_bibliograficos.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_bibliografico(gasto_bibliografico);
            var total_conadi = 0;
            $scope.data.gastos_bibliograficos.forEach(function(gasto_bibliografico) {
                total_conadi += gasto_bibliografico.presupuesto_conadi;
            });     
            $scope.data.totales_bibliograficos.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_bibliografico(gasto_bibliografico, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_bibliograficos.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_bibliograficos.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_bibliograficos.total = 0;
        $scope.data.gastos_bibliograficos.forEach(function(item) {
            $scope.data.totales_bibliograficos.total += item.total;
        });
        
    };    
    
    
    /*
	|--------------------------------------------------------------------------
	| suma_totales_digitales()
	|--------------------------------------------------------------------------
	| Realiza las sumas de los totales del presupuesto otorgado por cada entidad_presupuesto de cada gasto digital.
	| También calcula los totales generales de los gastos digitales
	*/                       
    $scope.suma_totales_digitales = function(gasto_digital, entidad_presupuesto, id_entidad_presupuesto) {
        
        // actualiza los totales para el gasto_digital específico
        var total_otras_entidades_gasto_digitales = 0;
        
        for(var id in gasto_digital.otras_entidades_presupuesto){
            total_otras_entidades_gasto_digitales += gasto_digital.otras_entidades_presupuesto[id];
        }
        gasto_digital.total = gasto_digital.presupuesto_ucc + gasto_digital.presupuesto_conadi + total_otras_entidades_gasto_digitales;
        
        // actualiza los totales generales
        if(entidad_presupuesto == 'ucc'){
            $scope.validar_presupuesto_ucc_gasto_digital(gasto_digital);
            var total_ucc = 0;
            $scope.data.gastos_digitales.forEach(function(gasto_digital) {
                total_ucc += gasto_digital.presupuesto_ucc;
            });
            $scope.data.totales_digitales.ucc = total_ucc;
        }
        else if(entidad_presupuesto == 'conadi'){
            $scope.validar_presupuesto_conadi_gasto_digital(gasto_digital);
            var total_conadi = 0;
            $scope.data.gastos_digitales.forEach(function(gasto_digital) {
                total_conadi += gasto_digital.presupuesto_conadi;
            });     
            $scope.data.totales_digitales.conadi = total_conadi;
        }
        else if(entidad_presupuesto == 'otro'){
            $scope.validar_presupuesto_externo_gasto_digital(gasto_digital, id_entidad_presupuesto);
            var total_otra_entidad_presupuesto = 0;
            $scope.data.gastos_digitales.forEach(function(item) {
                total_otra_entidad_presupuesto += item.otras_entidades_presupuesto[id_entidad_presupuesto];
            }); 
            $scope.data.totales_digitales.otras_entidades_presupuesto[id_entidad_presupuesto] = total_otra_entidad_presupuesto;
        }
        
        // Suma el total general
        $scope.data.totales_digitales.total = 0;
        $scope.data.gastos_digitales.forEach(function(item) {
            $scope.data.totales_digitales.total += item.total;
        });
        
    };    
    
    
    // Validaciones de campos de cada tipo de gasto
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de personal
	|--------------------------------------------------------------------------
	*/
    $scope.validar_dedicacion_semanal = function(participante) {
        if(participante.dedicacion_semanal && participante.dedicacion_semanal >= 0){
            participante.dedicacion_semanal_invalido = false;
            return false;
        }
        else{
            participante.dedicacion_semanal_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_total_semanas = function(participante) {
        if(participante.total_semanas && participante.total_semanas >= 0){
            participante.total_semanas_invalido = false;
            return false;
        }
        else{
            participante.total_semanas_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_valor_hora = function(participante) {
        if(participante.valor_hora && participante.valor_hora >= 0){
            participante.valor_hora_invalido = false;
            return false;
        }
        else{
            participante.valor_hora_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_presupuesto_ucc_participante = function(participante) {
        if(participante.presupuesto_ucc){
            if(participante.presupuesto_ucc >= 0){
                participante.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                participante.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            participante.presupuesto_ucc_invalido = false;
            return false;            
        }
    };
    
    $scope.validar_presupuesto_externo_participante = function(participante, id_entidad_presupuesto) {
        if(participante.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(participante.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                participante.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                participante.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            participante.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };
    
    $scope.validar_fecha_ejecucion_participante = function(participante) {
        if(participante.fecha_ejecucion){
            participante.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            participante.fecha_ejecucion_invalido = true;
            return true;            
        }
    };
    
    // Realiza validaciones a todos los gastos de participantes
    $scope.validar_todos_los_gastos_participantes = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.participantes_proyecto.forEach(function(participante_proyecto) {
            alguna_validacion_invalida |= $scope.validar_dedicacion_semanal(participante_proyecto);
            alguna_validacion_invalida |= $scope.validar_total_semanas(participante_proyecto);
            alguna_validacion_invalida |= $scope.validar_valor_hora(participante_proyecto);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_participante(participante_proyecto);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_participante(participante_proyecto, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_participante(participante_proyecto)
        });        
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de equipos
	|--------------------------------------------------------------------------
	*/    
    $scope.validar_nombre_equipo = function(gasto_equipo) {
        if(gasto_equipo.equipo && gasto_equipo.equipo.length >= 5 && gasto_equipo.equipo.length < 150){
            gasto_equipo.nombre_equipo_invalido = false;
            return false;
        }
        else{
            gasto_equipo.nombre_equipo_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_justificacion_gasto_equipo = function(gasto_equipo) {
        if(gasto_equipo.justificacion && gasto_equipo.justificacion.length >= 5 && gasto_equipo.justificacion.length < 150){
            gasto_equipo.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_equipo.justificacion_invalido = true;
            return true;
        }
    };
    
    $scope.validar_presupuesto_ucc_gasto_equipo = function(gasto_equipo) {
        if(gasto_equipo.presupuesto_ucc){
            if(gasto_equipo.presupuesto_ucc >= 0){
                gasto_equipo.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_equipo.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_equipo.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };
    
    $scope.validar_presupuesto_conadi_gasto_equipo = function(gasto_equipo) {
        if(gasto_equipo.presupuesto_conadi){
            if(gasto_equipo.presupuesto_conadi >= 0){
                gasto_equipo.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_equipo.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_equipo.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };
    
    $scope.validar_presupuesto_externo_gasto_equipo = function(gasto_equipo, id_entidad_presupuesto) {
        if(gasto_equipo.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_equipo.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_equipo.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_equipo.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_equipo.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };    
    
    $scope.validar_fecha_ejecucion_gasto_equipo = function(gasto_equipo) {
        if(gasto_equipo.fecha_ejecucion){
            gasto_equipo.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_equipo.fecha_ejecucion_invalido = true;
            return true;            
        }
    };
    
    // Realiza validaciones a todos los gastos de equipos
    $scope.validar_todos_los_gastos_equipos = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_equipos.forEach(function(gasto_equipo) {
            alguna_validacion_invalida |= $scope.validar_nombre_equipo(gasto_equipo);
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_equipo(gasto_equipo);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_equipo(gasto_equipo);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_equipo(gasto_equipo);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_equipo(gasto_equipo, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_equipo(gasto_equipo);
        });            
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de software
	|--------------------------------------------------------------------------
	*/        
    $scope.validar_nombre_software = function(gasto_software){
        if(gasto_software.software && gasto_software.software.length >= 5 && gasto_software.software.length < 150){
            gasto_software.nombre_software_invalido = false;
            return false;
        }
        else{
            gasto_software.nombre_software_invalido = true;
            return true;            
        }        
    };
    
    $scope.validar_justificacion_software = function(gasto_software) {
        if(gasto_software.justificacion && gasto_software.justificacion.length >= 5 && gasto_software.justificacion.length < 150){
            gasto_software.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_software.justificacion_invalido = true;
            return true;            
        }           
    };
    
    $scope.validar_presupuesto_ucc_software = function(gasto_software) {
        if(gasto_software.presupuesto_ucc){
            if(gasto_software.presupuesto_ucc >= 0){
                gasto_software.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_software.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_software.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_software = function(gasto_software) {
        if(gasto_software.presupuesto_conadi){
            if(gasto_software.presupuesto_conadi >= 0){
                gasto_software.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_software.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_software.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_software = function(gasto_software, id_entidad_presupuesto) {
        if(gasto_software.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_software.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_software.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_software.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_software.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };        
    
    $scope.validar_fecha_ejecucion_gasto_software = function(gasto_software) {
        if(gasto_software.fecha_ejecucion){
            gasto_software.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_software.fecha_ejecucion_invalido = true;
            return true;            
        }        
    };
    
    // Realiza validaciones a todos los gastos de software    
    $scope.validar_todos_los_gastos_software = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_software.forEach(function(gasto_software) {
            alguna_validacion_invalida |= $scope.validar_nombre_software(gasto_software);
            alguna_validacion_invalida |= $scope.validar_justificacion_software(gasto_software);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_software(gasto_software);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_software(gasto_software);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_software(gasto_software, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_software(gasto_software);
        });    
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de salida de campo
	|--------------------------------------------------------------------------
	*/            
    $scope.validar_justificacion_gasto_salida = function(gasto_salida) {
        if(gasto_salida.justificacion && gasto_salida.justificacion.length >= 5 && gasto_salida.justificacion.length < 150){
            gasto_salida.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_salida.justificacion_invalido = true;
            return true;            
        }                   
    };
    
    $scope.validar_cantidad_salidas = function(gasto_salida) {
        if(gasto_salida.cantidad_salidas && gasto_salida.cantidad_salidas >= 0){
            gasto_salida.cantidad_salidas_invalido = false;
            return false;
        }
        else{
            gasto_salida.cantidad_salidas_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_valor_unitario = function(gasto_salida) {
        if(gasto_salida.valor_unitario && gasto_salida.valor_unitario >= 0){
            gasto_salida.valor_unitario_invalido = false;
            return false;
        }
        else{
            gasto_salida.valor_unitario_invalido = true;
            return true;            
        }        
    };
    
    $scope.validar_presupuesto_ucc_gasto_salida = function(gasto_salida) {
        if(gasto_salida.presupuesto_ucc){
            if(gasto_salida.presupuesto_ucc >= 0){
                gasto_salida.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_salida.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_salida.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_gasto_salida = function(gasto_salida) {
        if(gasto_salida.presupuesto_conadi){
            if(gasto_salida.presupuesto_conadi >= 0){
                gasto_salida.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_salida.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_salida.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_gasto_salida = function(gasto_salida, id_entidad_presupuesto) {
        if(gasto_salida.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_salida.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_salida.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_salida.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_salida.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };
    
    $scope.validar_fecha_ejecucion_gasto_salida = function(gasto_salida) {
        if(gasto_salida.fecha_ejecucion){
            gasto_salida.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_salida.fecha_ejecucion_invalido = true;
            return true;            
        }               
    };
    
    // Realiza validaciones a todos los gastos de salidas de campo
    $scope.validar_todos_los_gastos_salidas = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_salidas.forEach(function(gasto_salida) {
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_salida(gasto_salida);
            alguna_validacion_invalida |= $scope.validar_cantidad_salidas(gasto_salida);
            alguna_validacion_invalida |= $scope.validar_valor_unitario(gasto_salida);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_salida(gasto_salida);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_salida(gasto_salida);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_salida(gasto_salida, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_salida(gasto_salida);
        });            
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de material
	|--------------------------------------------------------------------------
	*/         
    $scope.validar_nombre_material = function(gasto_material) {
        if(gasto_material.material && gasto_material.material.length > 5){
            gasto_material.nombre_material_invalido = false;
            return false;
        }
        else{
            gasto_material.nombre_material_invalido = true;
            return false;           
        }
    };
    
    $scope.validar_justificacion_gasto_material = function(gasto_material) {
        if(gasto_material.justificacion && gasto_material.justificacion.length >= 5){
            gasto_material.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_material.justificacion_invalido = true;
            return false;           
        }
    };
    
    $scope.validar_presupuesto_ucc_gasto_material = function(gasto_material) {
        if(gasto_material.presupuesto_ucc){
            if(gasto_material.presupuesto_ucc >= 0){
                gasto_material.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_material.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_material.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_gasto_material = function(gasto_material) {
        if(gasto_material.presupuesto_conadi){
            if(gasto_material.presupuesto_conadi >= 0){
                gasto_material.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_material.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_material.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_gasto_material = function(gasto_material, id_entidad_presupuesto) {
        if(gasto_material.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_material.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_material.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_material.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_material.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };                
    
    $scope.validar_fecha_ejecucion_gasto_material = function(gasto_material){
        if(gasto_material.fecha_ejecucion){
            gasto_material.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_material.fecha_ejecucion_invalido = true;
            return true;            
        }                       
    };
    
    // Realiza validaciones a todos los gastos de materiales
    $scope.validar_todos_los_gastos_materiales = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_materiales.forEach(function(gasto_material) {
            alguna_validacion_invalida |= $scope.validar_nombre_material(gasto_material);
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_material(gasto_material);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_material(gasto_material);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_material(gasto_material);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_material(gasto_material, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_material(gasto_material);
        });            
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos de servicios técnicos
	|--------------------------------------------------------------------------
	*/         
    $scope.validar_nombre_servicio = function(gasto_servicio) {
        if(gasto_servicio.servicio && gasto_servicio.servicio.length >= 5){
            gasto_servicio.nombre_servicio_invalido = false;
            return false;
        }
        else{
            gasto_servicio.nombre_servicio_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_justificacion_gasto_servicio = function(gasto_servicio) {
        if(gasto_servicio.justificacion && gasto_servicio.justificacion.length >= 5){
            gasto_servicio.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_servicio.justificacion_invalido = true;
            return false;           
        }        
    };
    
    $scope.validar_presupuesto_ucc_gasto_servicio = function(gasto_servicio) {
        if(gasto_servicio.presupuesto_ucc){
            if(gasto_servicio.presupuesto_ucc >= 0){
                gasto_servicio.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_servicio.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_servicio.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_gasto_servicio = function(gasto_servicio) {
        if(gasto_servicio.presupuesto_conadi){
            if(gasto_servicio.presupuesto_conadi >= 0){
                gasto_servicio.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_servicio.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_servicio.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_gasto_servicio = function(gasto_servicio, id_entidad_presupuesto) {
        if(gasto_servicio.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_servicio.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_servicio.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_servicio.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_servicio.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };                    
    
    $scope.validar_fecha_ejecucion_gasto_servicio = function(gasto_servicio){
        if(gasto_servicio.fecha_ejecucion){
            gasto_servicio.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_servicio.fecha_ejecucion_invalido = true;
            return true;            
        }                       
    }; 
    
    // Realiza validaciones a todos los gastos de servicios técnicos
    $scope.validar_todos_los_gastos_servicios_tecnicos = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_servicios_tecnicos.forEach(function(gasto_servicio) {
            alguna_validacion_invalida |= $scope.validar_nombre_servicio(gasto_servicio);
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_servicio(gasto_servicio);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_servicio(gasto_servicio);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_servicio(gasto_servicio);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_servicio(gasto_servicio, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_servicio(gasto_servicio);
        });         
        return alguna_validacion_invalida;
    };

    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos bibliográficos
	|--------------------------------------------------------------------------
	*/      
    $scope.validar_titulo_bibliografico = function(gasto_bibliografico){
        if(gasto_bibliografico.titulo && gasto_bibliografico.titulo.length >= 5 && gasto_bibliografico.titulo.length < 150){
            gasto_bibliografico.titulo_invalido = false;
            return false;
        }
        else{
            gasto_bibliografico.titulo_invalido = true;
            return true;            
        }
    };
    
    $scope.validar_justificacion_gasto_bibliografico = function(gasto_bibliografico) {
        if(gasto_bibliografico.justificacion && gasto_bibliografico.justificacion.length >= 5 && gasto_bibliografico.justificacion.length < 150){
            gasto_bibliografico.justificacion_invalido = false;
            return false;
        }
        else{
            gasto_bibliografico.justificacion_invalido = true;
            return true;            
        }        
    };
    
    $scope.validar_presupuesto_ucc_gasto_bibliografico = function(gasto_bibliografico) {
        if(gasto_bibliografico.presupuesto_ucc){
            if(gasto_bibliografico.presupuesto_ucc >= 0){
                gasto_bibliografico.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_bibliografico.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_bibliografico.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_gasto_bibliografico = function(gasto_bibliografico) {
        if(gasto_bibliografico.presupuesto_conadi){
            if(gasto_bibliografico.presupuesto_conadi >= 0){
                gasto_bibliografico.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_bibliografico.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_bibliografico.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_gasto_bibliografico = function(gasto_bibliografico, id_entidad_presupuesto) {
        if(gasto_bibliografico.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_bibliografico.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_bibliografico.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_bibliografico.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_bibliografico.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };                        
    
    $scope.validar_fecha_ejecucion_gasto_bibliografico = function(gasto_bibliografico){
        if(gasto_bibliografico.fecha_ejecucion){
            gasto_bibliografico.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_bibliografico.fecha_ejecucion_invalido = true;
            return true;            
        }                       
    };        
    
    // Realiza validaciones a todos los gastos bibliográficos
    $scope.validar_todos_los_gastos_bibliograficos = function() {
        
        var alguna_validacion_invalida= false;
        $scope.data.gastos_bibliograficos.forEach(function(gasto_bibliografico) {
            alguna_validacion_invalida |= $scope.validar_titulo_bibliografico(gasto_bibliografico);
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_bibliografico(gasto_bibliografico);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_bibliografico(gasto_bibliografico);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_bibliografico(gasto_bibliografico);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_bibliografico(gasto_bibliografico, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_bibliografico(gasto_bibliografico);
        });             
        return alguna_validacion_invalida;
    };

    /*
	|--------------------------------------------------------------------------
	| Validaciones de gastos digitales
	|--------------------------------------------------------------------------
	*/          
    $scope.validar_titulo_gasto_digital = function(gasto_digital) {
        if(gasto_digital.titulo && gasto_digital.titulo.length >= 5 && gasto_digital.titulo.length < 150){
           gasto_digital.titulo_invalido = false;
           return false;
        }
        else{
           gasto_digital.titulo_invalido = true;
           return true;            
        }
    };
    
    $scope.validar_justificacion_gasto_digital = function(gasto_digital) {
        if(gasto_digital.justificacion && gasto_digital.justificacion.length >= 5 && gasto_digital.justificacion.length < 150){
           gasto_digital.justificacion_invalido = false;
           return false;
        }
        else{
           gasto_digital.justificacion_invalido = true;
           return true;            
        }
    };
    
    $scope.validar_presupuesto_ucc_gasto_digital = function(gasto_digital) {
        if(gasto_digital.presupuesto_ucc){
            if(gasto_digital.presupuesto_ucc >= 0){
                gasto_digital.presupuesto_ucc_invalido = false;
                return false;
            }
            else{
                gasto_digital.presupuesto_ucc_invalido = true;
                return true;
            }
        }
        else{
            gasto_digital.presupuesto_ucc_invalido = false;
            return false;            
        }        
    };    
    
    $scope.validar_presupuesto_conadi_gasto_digital = function(gasto_digital) {
        if(gasto_digital.presupuesto_conadi){
            if(gasto_digital.presupuesto_conadi >= 0){
                gasto_digital.presupuesto_conadi_invalido = false;
                return false;
            }
            else{
                gasto_digital.presupuesto_conadi_invalido = true;
                return true;
            }
        }
        else{
            gasto_digital.presupuesto_conadi_invalido = false;
            return false;            
        }                
    };    
    
    $scope.validar_presupuesto_externo_gasto_digital = function(gasto_digital, id_entidad_presupuesto) {

        if(gasto_digital.otras_entidades_presupuesto[id_entidad_presupuesto]){
            if(gasto_digital.otras_entidades_presupuesto[id_entidad_presupuesto] >= 0){
                gasto_digital.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
                return false;                
            }
            else{
                gasto_digital.presupuesto_externo_invalido[id_entidad_presupuesto] = true;
                return true;
            }
        }
        else{
            gasto_digital.presupuesto_externo_invalido[id_entidad_presupuesto] = false;
            return false;                            
        }
    };                            
    
    $scope.validar_fecha_ejecucion_gasto_digital = function(gasto_digital){
        if(gasto_digital.fecha_ejecucion){
            gasto_digital.fecha_ejecucion_invalido = false;
            return false;
        }
        else{
            gasto_digital.fecha_ejecucion_invalido = true;
            return true;            
        }                       
    };            
    
    // Realiza validaciones a todos los gastos digitales
    $scope.validar_todos_los_gastos_digitales = function() {
        
        var alguna_validacion_invalida = false;
        $scope.data.gastos_digitales.forEach(function(gasto_digital) {
            alguna_validacion_invalida |= $scope.validar_titulo_gasto_digital(gasto_digital);
            alguna_validacion_invalida |= $scope.validar_justificacion_gasto_digital(gasto_digital);
            alguna_validacion_invalida |= $scope.validar_presupuesto_ucc_gasto_digital(gasto_digital);
            alguna_validacion_invalida |= $scope.validar_presupuesto_conadi_gasto_digital(gasto_digital);
            $scope.data.entidades_presupuesto_seleccionadas.forEach(function(entidad_presupuesto) {
                alguna_validacion_invalida |= $scope.validar_presupuesto_externo_gasto_digital(gasto_digital, entidad_presupuesto.id);
            });
            alguna_validacion_invalida |= $scope.validar_fecha_ejecucion_gasto_digital(gasto_digital);
        });            
        return alguna_validacion_invalida;
    };
    
    /*
	|--------------------------------------------------------------------------
	| continuar_a_cargar_documentos()
	|--------------------------------------------------------------------------
	| Continúa a la pestaña de carga de documentos del proyecto, 
	| validando antes todos los campos de cada tipo de gasto
	*/                           
    $scope.continuar_a_cargar_documentos = function() {
        
        var validaciones = [];
        
        validaciones.push($scope.validar_todos_los_gastos_participantes());
        validaciones.push($scope.validar_todos_los_gastos_equipos());
        validaciones.push($scope.validar_todos_los_gastos_software());
        validaciones.push($scope.validar_todos_los_gastos_salidas());
        validaciones.push($scope.validar_todos_los_gastos_materiales());
        validaciones.push($scope.validar_todos_los_gastos_servicios_tecnicos());
        validaciones.push($scope.validar_todos_los_gastos_bibliograficos());
        validaciones.push($scope.validar_todos_los_gastos_digitales());
        
        if(validaciones.indexOf(1) != -1) // alguna validacion es incorrecta
        {
            alertify.error('Validaciones de gastos incorrecta. Verificar los datos ingresados');
        }
        else
        {
            $('a[href="#contenido_adjuntos"]').tab('show');
        }
    };
    
    $scope.regresar_productos = function() {
        $('a[href="#contenido_productos"]').tab('show');
    };    
    
});
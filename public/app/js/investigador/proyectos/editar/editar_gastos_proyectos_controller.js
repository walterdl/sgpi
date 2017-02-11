sgpi_app.controller('editar_gastos_proyectos_controller', function ($scope, $http, id_proyecto) {
    
    // inicializa modelos
    $scope.contador_nuevas_entidades_presupuesto = 0; // simula un autoincrement que identifica las nuevas entidades que se agregan desde el input text de nueva entidad
    $scope.nueva_entidad_presupuesto = null; // modelo para el input text de nueva entidad
    
    // consulta los gastos del proyecto
    $scope.visibilidad.show_velo_general = true;
    $scope.data.msj_operacion_general = '<h3 class="text-center">Cargando gastos del proyecto...<i class="fa fa-circle-o-notch fa-spin fa-2x fa-fw"></i></h3>';
    $http({
        url: '/proyectos/gastos_proyecto',
        params: {
            id_proyecto: id_proyecto
        }
    })
    .success(function(data) {
        console.log(data);
        if(data.consultado == 1)
        {
            // inicializa los modelos con los datos recuperados
            $scope.init(data);
            
            // oculta velo general para permitir la interacción con los controles GUI
            $scope.visibilidad.show_velo_general = false;
        }
        else
        {
            $scope.data.msj_operacion_general = '<h3 class="text-center">Un error ha ocurrido al consultar los gastos del proyecto. Volver a intentarlo. Código de error: ' + data.codigo + '</h3>';
        }
    })
    .error(function(data, status) {
        console.log(data);
        $scope.data.msj_operacion_general = '<h3 class="text-center">Un error ha ocurrido al consultar los gastos del proyecto. Volver a intentarlo. Código de estado: ' + status + '</h3>';
    });
    
    /*
	|--------------------------------------------------------------------------
	| agregar_nueva_entidadPresupuesto()
	|--------------------------------------------------------------------------
	| Controlador de evento click para le botón que agrega una nueva entidad de fuente de presupuesto.
	| Añade otra entidad fuente presupuesto a la colección de entidades_fuente_presupuesto.
	| Añade un input hidden para crear la nueva entidad en la BD
	| Llama al metodo que agrega la entidad a la coleccion de nuevas_entidades_presupuesto de cada tipo de gasto
	*/        
    $scope.agregar_nueva_entidadPresupuesto = function() {
        
        if($scope.nueva_entidad_presupuesto != null && $scope.nueva_entidad_presupuesto.length > 0){ // si el modelo del input text de la nueva entidad tiene datos
        
            // se realiza búsqueda en la colección de entidades fuente de presupuesto del multiselect para prever la adición de una entidad con mismo nombre
            var existe_entidadPresupuesto = false;
            $scope.entidades_fuente_presupuesto.forEach(function(item) {
                if(item.nombre == $scope.nueva_entidad_presupuesto)
                    existe_entidadPresupuesto = true;
            });
            if(existe_entidadPresupuesto){
                $scope.msj_nueva_entidadPresupuesto_incorrecto = 'Entidad de fuente de presupuesto ya agregada';
                $scope.nueva_entidadPresupuesto_incorrecto = true;                            
                return;
            }
            
            // la entidad no existe, se agrega al multiselect
            var nueva_entidad_presupuesto = {
                id: $scope.contador_nuevas_entidades_presupuesto + 'x', // la 'x' significa que se trata de una entidad añadida por el usuario y no se trata de una ya existente en la BD
                nombre: $scope.nueva_entidad_presupuesto
            };
            $scope.entidades_fuente_presupuesto.push(nueva_entidad_presupuesto);
            $scope.entidades_fuente_presupuesto_seleccionadas.push(nueva_entidad_presupuesto);
            
            // agrega la entidad de presupuesto a la colección de entidades fuente presupuesto a cada uno de los tipos de los gastos
            $scope.agregar_nueva_entidad_presupuesto_a_gastos(nueva_entidad_presupuesto);
            $scope.data.contador_nuevas_entidades_presupuesto++;
            
            $('#inputs_nuevas_entidades_fuente_presupuesto')
                .append('<input type="text" indice_entidad_presupuesto="' + nueva_entidad_presupuesto.id + '" name="nuevas_entidades_presupuesto[]" value="' + nueva_entidad_presupuesto.id + '_' + nueva_entidad_presupuesto.nombre + '"/>');
                
            $scope.nueva_entidad_presupuesto = null; // limpia input text de nueva entidad
        }
        else{
            $scope.msj_nuevo_financiador_incorrecto = 'Nombre de entidad incorrecto';
            $scope.nueva_entidadPresupuesto_incorrecto = true;            
        }
    };       

    /*
	|--------------------------------------------------------------------------
	| agregar_nueva_entidad_presupuesto_a_gastos()
	|--------------------------------------------------------------------------    
    | Controlador de evento de selección de entidad de presupuesto del multiselect
    | Esta función también se invoca cuando se agrega una entidad desde el botón agregar nueva entidad
    | Se encarga de añadir la entidad fuente de presupuesto a la colección de entidades de presupuesto de cada tipo de gasto
    */    
    $scope.agregar_nueva_entidad_presupuesto_a_gastos = function(nueva_entidad_presupuesto){
        // // se añade gasto formateado
        $scope.gastos_personal.forEach(function(gasto_personal) {
            gasto_personal.gastos.push({
                id_entidad_fuente_presupuesto: nueva_entidad_presupuesto.id,
                nombre_entidad_fuente_presupuesto: nueva_entidad_presupuesto.nombre,
                valor: 0
            });
        });
        $scope.cacular_totales_gastos();
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
    
    // inicializa con los datos consultados por primera vez en la vista
    $scope.init = function(data) {
        
        // alimenta el multiselect con todas las entidades fuente de presupuesto de la BD y selecciona aquellas que financian el proyecto
        $scope.init_multiselect_entidades_fuente_presupuesto(data);
        
        // inicializa los gastos de personal
        $scope.init_gastos_personal(data);
    };
    
    // alimenta el multiselect con todas las entidades fuente de presupuesto de la BD y selecciona aquellas que financian el proyecto
    $scope.init_multiselect_entidades_fuente_presupuesto = function(data) {
        
        // alimenta las opciones del multiselect de las entidades fuente de presupuesto
        $scope.entidades_fuente_presupuesto = data.todas_las_entidades_fuente_presupuesto;
        
        // selecciona las entidades que financian el proyecto actualmente
        $scope.entidades_fuente_presupuesto_seleccionadas = [];
        data.entidades_fuente_presupuesto_proyecto.entidades.forEach(function(entidad_proy) {
            data.todas_las_entidades_fuente_presupuesto.forEach(function(entidad) {
                if(entidad.id == entidad_proy.id_entidad_fuente_presupuesto)
                    if(entidad.nombre != 'UCC' && entidad.nombre != 'CONADI')
                    $scope.entidades_fuente_presupuesto_seleccionadas.push(entidad);
            });
        });        
    };
    
    // inicializa los gastos de personal
    $scope.init_gastos_personal = function(data) {
        
        // prepara variables que usara en el proceso de formateo del modelo del gasto personal
        $scope.gastos_personal = [];
        $scope.totales_gastos_personal = {ucc: 0, entidades_fuente_presupuesto: {}, total: 0}; // tiene los totales de las columnas de los gastos de personal. Es el total de todos los gastos de personal
        var date = null;
        var userTimezoneOffset = null;
        var total_gasto_personal = 0; // tiene el total de la fila de gasto personal, es decir el total de un solo gasto personal
        
        data.gastos.gastos_personal.forEach(function(gasto_personal) {
            
            // los siguientes ciclos aninados sincronizan o aseguran de que se presente las entidades fuente de presupuesto
            // que estan seleccionadas en el multiselect de entidades fuente presupuesto
            // además edita los nombres de los campos del gasto que usado como modelo para mejor legibilidad 
            
            var gastos = []; // colección con los gastos formateados y sincronizados con el multiselect del gasto_personal
            
            gasto_personal.gastos.forEach(function(gasto) { // itera por la colección orginal de gastos del gasto_personal que se envía desde server
                if(gasto.entidad_fuente_presupuesto == 'UCC') // si se trata del gasto presupuestado por la entidad UCC lo añade manualmente, ya que la UCC no esta en el multiselect
                {
                    // se añade gasto formateado
                    gastos.push({
                        id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                        nombre_entidad_fuente_presupuesto: gasto.entidad_fuente_presupuesto,
                        valor: gasto.valor
                    });
                    total_gasto_personal += Number(gasto.valor);
                    $scope.totales_gastos_personal.ucc += Number(gasto.valor);
                }
                else
                {
                    for(var i = 0; i < $scope.entidades_fuente_presupuesto_seleccionadas.length; i++) // itera por las entidades que estan seleccionadas en el multiselect
                    {
                        if(gasto.id_entidad_fuente_presupuesto == $scope.entidades_fuente_presupuesto_seleccionadas[i].id) // si se encuentra la entidad que esta seleccionada en los gastos del gasto_personal...
                        {
                            // se añade gasto formateado
                            gastos.push({
                                id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                                nombre_entidad_fuente_presupuesto: gasto.entidad_fuente_presupuesto,
                                valor: gasto.valor                                
                            });
                            total_gasto_personal += Number(gasto.valor);
                            if($scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] == undefined)
                                $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] = Number(gasto.valor);
                            else
                                $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] += Number(gasto.valor);
                            break; // encontró la entidad, no necesita seguir iterando por las entidades seleccionadas
                        }
                    }
                }
            });
            
            // Convierte la fecha de ejecución a un objeto tipo Date
            date = new Date(gasto_personal.fecha_ejecucion + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            
            // agrega el modelo gasto personal con solo los campos necesarios
            $scope.gastos_personal.push({
                id_detalle_gasto: gasto_personal.id_detalle_gasto,
                nombre_completo_persona: gasto_personal.nombre_completo,
                formacion: gasto_personal.formacion,
                nombre_rol: gasto_personal.nombre_rol,
                dedicacion_horas_semanales: gasto_personal.dedicacion_horas_semanales,
                total_semanas: gasto_personal.total_semanas,
                valor_hora: gasto_personal.valor_hora,
                gastos: gastos,
                fecha_ejecucion: new Date(date.getTime() + userTimezoneOffset),
                total_gasto_personal: total_gasto_personal
            });
            
            $scope.totales_gastos_personal.total += total_gasto_personal;
            total_gasto_personal = 0; // reinicia total de presupuesto para el siguiente gasto_personal
        });
    };
    
    // Realiza la sumatoria de los totales generales de todos los tipos de gastos
    $scope.cacular_totales_gastos = function() {
        
        // calcula los totales de los gastos personales
        $scope.totales_gastos_personal.ucc = 0;
        for (var id in $scope.totales_gastos_personal.entidades_fuente_presupuesto){
            $scope.totales_gastos_personal.entidades_fuente_presupuesto[id] = 0;
        }
        $scope.gastos_personal.forEach(function(gasto_personal) {
            gasto_personal.gastos.forEach(function(gasto) {
                if(gasto.entidad_fuente_presupuesto == 'UCC')                
                    $scope.totales_gastos_personal.ucc += gasto.valor;
                else
                    if($scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] == undefined)
                        $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] = Number(gasto.valor);
                    else
                        $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] += Number(gasto.valor);                
            });
        });
    };
});
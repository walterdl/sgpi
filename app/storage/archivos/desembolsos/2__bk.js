/*
| Servicio que valida si un valor es numérico y si es mayor a cero
| Retorna true si número es inválido
*/     
sgpi_app.factory('numero_invalido', function(){
	return function(number){
  	if(number == 'null' || number == undefined || number == null || isNaN(number) || Number(number) < 0)
    	return true;
	else
    	return false;
  };
});

sgpi_app.controller('editar_gastos_proyectos_controller', function ($scope, $http, id_proyecto, numero_invalido) {
    
    /*
	| Inicializa modelos
	*/     
    $scope.contador_nuevas_entidades_presupuesto = 0; // simula un autoincrement que identifica las nuevas entidades que se agregan desde el input text de nueva entidad
    $scope.nueva_entidad_presupuesto = null; // modelo para el input text de nueva entidad
    
    /*
	| Consulta los gastos del proyecto
	*/         
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
	| Inicializa con los datos consultados por primera vez en la vista
	*/             
    $scope.init = function(data) {
        
        // alimenta el multiselect con todas las entidades fuente de presupuesto de la BD y selecciona aquellas que financian el proyecto
        $scope.init_multiselect_entidades_fuente_presupuesto(data);
        
        // inicializa los gastos de personal
        $scope.init_gastos_personal(data);
    };
    
    /*
	| Alimenta el multiselect con todas las entidades fuente de presupuesto de la BD y selecciona aquellas que financian el proyecto
	*/     
    $scope.init_multiselect_entidades_fuente_presupuesto = function(data) {
        
        // alimenta las opciones del multiselect de las entidades fuente de presupuesto
        $scope.entidades_fuente_presupuesto = data.todas_las_entidades_fuente_presupuesto;
        
        // selecciona las entidades que financian el proyecto actualmente
        $scope.data.entidades_fuente_presupuesto_seleccionadas = [];
        data.entidades_fuente_presupuesto_proyecto.entidades.forEach(function(entidad_proy) {
            data.todas_las_entidades_fuente_presupuesto.forEach(function(entidad) {
                if(entidad.id == entidad_proy.id_entidad_fuente_presupuesto)
                    if(entidad.nombre != 'UCC' && entidad.nombre != 'CONADI')
                    {
                        $scope.data.entidades_fuente_presupuesto_seleccionadas.push(entidad);
                        // $('#inputs_entidades_fuente_presupuesto_seleccionadas')
                        //     .append('<input type="hidden" indice_entidad_presupuesto="' + entidad.id + '" name="entidades_presupuesto_existentes[]" value="' + entidad.id + '"/>');                                        
                    }
            });
        });
    };
    
    /*
	| Inicializa los gastos de personal
	*/     
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
                        valor: gasto.valor,
                        gasto_invalido: false
                    });
                    total_gasto_personal += Number(gasto.valor);
                    $scope.totales_gastos_personal.ucc += Number(gasto.valor);
                }
                else
                {
                    for(var i = 0; i < $scope.data.entidades_fuente_presupuesto_seleccionadas.length; i++) // itera por las entidades que estan seleccionadas en el multiselect
                    {
                        if(gasto.id_entidad_fuente_presupuesto == $scope.data.entidades_fuente_presupuesto_seleccionadas[i].id) // si se encuentra la entidad que esta seleccionada en los gastos del gasto_personal...
                        {
                            // se añade gasto formateado
                            gastos.push({
                                id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                                nombre_entidad_fuente_presupuesto: gasto.entidad_fuente_presupuesto,
                                valor: gasto.valor, 
                                gasto_invalido: false                          
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
                total_gasto_personal: total_gasto_personal,
                tiene_desembolso_cargado: gasto_personal.tiene_desembolso_cargado
            });
            
            $scope.totales_gastos_personal.total += total_gasto_personal;
            total_gasto_personal = 0; // reinicia total de presupuesto para el siguiente gasto_personal
        });
    };    
    
    /*
	| Controlador de evento click para le botón que agrega una nueva entidad de fuente de presupuesto.
    | Añade otra entidad fuente presupuesto a la colección de entidades_fuente_presupuesto.
    | Añade un input hidden para crear la nueva entidad en la BD
    | Llama al metodo que agrega la entidad a la coleccion de nuevas_entidades_presupuesto de cada tipo de gasto
	*/     
    $scope.agregar_nueva_entidad_presupuesto_a_multiselect = function() {
        
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
            $scope.data.entidades_fuente_presupuesto_seleccionadas.push(nueva_entidad_presupuesto);
            
            // agrega la entidad de presupuesto a la colección de entidades fuente presupuesto a cada uno de los tipos de los gastos
            $scope.agregar_nueva_entidad_presupuesto_a_gastos(nueva_entidad_presupuesto);
            $scope.contador_nuevas_entidades_presupuesto++;
            
            // $('#inputs_nuevas_entidades_fuente_presupuesto')
            //     .append('<input type="text" indice_entidad_presupuesto="' + nueva_entidad_presupuesto.id + '" name="nuevas_entidades_presupuesto[]" value="' + nueva_entidad_presupuesto.id + '_' + nueva_entidad_presupuesto.nombre + '"/>');
                
            $scope.nueva_entidad_presupuesto = null; // limpia input text de nueva entidad
        }
        else{
            $scope.msj_nuevo_financiador_incorrecto = 'Nombre de entidad incorrecto';
            $scope.nueva_entidadPresupuesto_incorrecto = true;            
        }
    };       
    
    /*
	| Controlador de evento para remoción de item de las opciones del multiselect de entidades de fuente de presupuesto
    | elimina el input hidden que le corresponde a la entidad fuente de presupuesto removida.
    | llama las funciones que se encargan de remover el objeto gasto de la colección de gastos que corresponde con la entidad_presupuesto de cada tipo de gasto
    | También remueve la entidad_presupuesto de la coleccion de otras_entidades_presupuesto de los totales de cada gasto, restado sus respectivos valores de los totales.
	*/                 
    $scope.remocion_entidad_presupuesto_multiselect = function(entidad_presupuesto) {
        
        // remueve el gasto patrocinado por la entidad de la colección de gastos de gastos de personal
        $scope.remocion_entidad_presupuesto_gasto_personal(entidad_presupuesto);
        
        // remueve la entidad de las entidades seleccionadas
        var index_entidad_a_eliminar = $scope.data.entidades_fuente_presupuesto_seleccionadas.indexOf(entidad_presupuesto);
        $scope.data.entidades_fuente_presupuesto_seleccionadas.splice(index_entidad_a_eliminar, 1);
        
        // remueve el input hidden de la entidad fuente de presupuesto 
        // $('input[indice_entidad_presupuesto="' + entidad_presupuesto.id + '"]').remove();                
    };    
    
    /*
    | Remueve el objeto de gasto de la colección de gastos de cada tipo de gasto que le corresponde a la entidad_presupuesto a remover.
    | Realiza la resta del valor o dinero que daba la respectiva entidad a cada tipo de gasto y al total general.
	*/      
    $scope.remocion_entidad_presupuesto_gasto_personal = function(entidad_presupuesto){
        $scope.gastos_personal.forEach(function(gasto_personal) {
            for(var i = 0; i < gasto_personal.gastos.length; i++)
            {
                var gasto = gasto_personal.gastos[i];
                if(gasto.id_entidad_fuente_presupuesto == entidad_presupuesto.id)
                {
                    gasto_personal.total_gasto_personal -= numero_invalido(gasto_personal.gastos[i].valor) ? 0 : gasto_personal.gastos[i].valor;
                    gasto_personal.gastos.splice(i, 1);
                    break;
                }
            }
        });
        // remueve la entidad de los totales de los gastos de personal
        for(var id in $scope.totales_gastos_personal.entidades_fuente_presupuesto)
        {
            if(id == entidad_presupuesto.id)
            {
                $scope.totales_gastos_personal.total -= Number($scope.totales_gastos_personal.entidades_fuente_presupuesto[id]);
                delete $scope.totales_gastos_personal.entidades_fuente_presupuesto[id];
                break;
            }
        }        
    };
    
    /*
	| Controlador de evento para la selección de una entidad fuente de presupuesto de la lista que presenta el mulstiselect de entidades
    | agrega la entida seleccionada al modelo de entidades_fuente_presupuesto_seleccionadas
	*/                     
    $scope.seleccion_entidad_presupuesto_multiselect = function(entidad_presupuesto) {
        $scope.data.entidades_fuente_presupuesto_seleccionadas.push({
            id: entidad_presupuesto.id,
            nombre: entidad_presupuesto.nombre
        });
        $scope.agregar_nueva_entidad_presupuesto_a_gastos(entidad_presupuesto);
        
        // if(entidad_presupuesto.id.toString().indexOf("x") != -1)
        //     $('#inputs_nuevas_entidades_fuente_presupuesto')
        //         .append('<input type="hidden" indice_entidad_presupuesto="' + entidad_presupuesto.id + '" name="nuevas_entidades_presupuesto[]" value="' + entidad_presupuesto.id + '_' + entidad_presupuesto.nombre + '"/>');
        // else
        //     $('#inputs_entidades_fuente_presupuesto_seleccionadas')
        //         .append('<input type="hidden" indice_entidad_presupuesto="' + entidad_presupuesto.id + '" name="entidades_presupuesto_existentes[]" value="' + entidad_presupuesto.id + '"/>');
    };
    
    /*
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
                valor: 0,
                gasto_invalido: false
            });
        });
        $scope.cacular_totales_gastos();
    };
    
    /*
	| Realiza la sumatoria de los totales generales de todos los tipos de gastos
	*/             
    $scope.cacular_totales_gastos = function() {
        $scope.calcular_totales_gasto_personal();
    };    
    
    /*
	| Calcula los totales de los valores de los gastos de los gastos de personal
    | Adicional calcula el total de un determinado gasto_personal si el parámetro no es null 
	*/             
    $scope.calcular_totales_gasto_personal = function(gasto_personal=null) {
        
        if(gasto_personal != null) // Calcula el total de un determinado gasto personal
        {
            gasto_personal.total_gasto_personal = 0;
            gasto_personal.gastos.forEach(function(gasto) {
                gasto_personal.total_gasto_personal += numero_invalido(gasto.valor) ? 0 : Number(gasto.valor);
            });            
        }
        
        // calcula los totales de los gastos personales
        $scope.totales_gastos_personal.ucc = 0;
        $scope.totales_gastos_personal.total = 0;
        for (var id in $scope.totales_gastos_personal.entidades_fuente_presupuesto){
            $scope.totales_gastos_personal.entidades_fuente_presupuesto[id] = 0;
        }
        $scope.gastos_personal.forEach(function(gasto_personal) {
            gasto_personal.gastos.forEach(function(gasto) {
                if(gasto.nombre_entidad_fuente_presupuesto == 'UCC')
                {
                    $scope.totales_gastos_personal.ucc += numero_invalido(gasto.valor) ? 0 : Number(gasto.valor);
                }
                else
                {
                    var valor = numero_invalido(gasto.valor) ? 0 : Number(gasto.valor);
                    if($scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] == undefined)
                        $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] = valor;
                    else
                        $scope.totales_gastos_personal.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] += valor;
                }
            });
            $scope.totales_gastos_personal.total += gasto_personal.total_gasto_personal;
        });            
    };    
    
    /*
	| ng-change para modelo de valor de gasto de un gasto_personal
    | valida el presupuesto del gasto y calcula los totales de sus gastos
	*/                         
    $scope.cambia_presupuesto_gasto_personal = function(gasto_personal, gasto_entidad_fuente_presupuesto=null) {
        $scope.validar_presupuesto_gasto_personal(gasto_personal, gasto_entidad_fuente_presupuesto);
        $scope.calcular_totales_gasto_personal(gasto_personal);
    };
    
    /*
	| valida todos o un determinado presupuesto de un determinado gasto_personal
    | si el parámetro gasto_entidad_fuente_presupuesto no es null, se valida solo el presupuesto dado por la entidad dada
	*/                             
    $scope.validar_presupuesto_gasto_personal = function(gasto_personal, gasto_entidad_fuente_presupuesto=null) {
        
        if(gasto_entidad_fuente_presupuesto != null)
        {
            gasto_entidad_fuente_presupuesto.gasto_invalido = numero_invalido(gasto_entidad_fuente_presupuesto.valor);
        }
        else
        {
            
        }
    };
    
    // validación de modelos no presupuesto de gasto personal
    $scope.validar_dedicacion_semanal = function(gasto_personal) {
        gasto_personal.dedicacion_semanal_invalido = numero_invalido(gasto_personal.dedicacion_horas_semanales);
    };
    $scope.validar_total_semanas = function(gasto_personal) {
        gasto_personal.total_semanas_invalido = numero_invalido(gasto_personal.total_semanas);
    };
    $scope.validar_valor_hora = function(gasto_personal) {
        gasto_personal.valor_hora_invalido = numero_invalido(gasto_personal.valor_hora);
    };    
});
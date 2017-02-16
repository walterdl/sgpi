    /*
	| Inicializa los gastos de equipos
	*/    
    $scope.init_gastos_equipos = function(data) {

        // prepara variables que usara en el proceso de formateo del modelo del tipo de gasto
        $scope.gastos_equipos = [];                
        $scope.totales_gastos_equipos = {ucc: 0, conadi: 0, entidades_fuente_presupuesto: {}, total: 0}; // tiene los totales de las columnas del tipo de gasto.
        var date = null;
        var userTimezoneOffset = null;
        var total_gasto_equipo = 0; // tiene el total de la fila de gasto personal, es decir el total de un solo gasto personal        
        var gastos = []; // colección con los gastos formateados y sincronizados con el multiselect del gasto_personal

        // los siguientes ciclos aninados sincronizan o aseguran de que se presente las entidades fuente de presupuesto
        // que estan seleccionadas en el multiselect de entidades fuente presupuesto
        // además edita los nombres de los campos del gasto que usado como modelo para mejor legibilidad 

        data.gastos.gastos_equipos.forEach(function(gasto_equipo) {
            gasto_equipo.gastos.forEach(function(gasto) { // itera por la colección orginal de gastos del gasto_personal que se envía desde server
                if(gasto.nombre_entidad == 'UCC' || gasto.nombre_entidad == 'CONADI') // si se trata del gasto presupuestado por la entidad UCC lo añade manualmente, ya que la UCC no esta en el multiselect
                {
                    // se añade gasto formateado
                    gastos.push({
                        id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                        nombre_entidad_fuente_presupuesto: gasto.nombre_entidad,
                        valor: gasto.valor,
                        gasto_invalido: false
                    });
                    total_gasto_equipo += Number(gasto.valor);

                    if(gasto.nombre_entidad == 'UCC')
                        $scope.totales_gastos_equipos.ucc += Number(gasto.valor);
                    else if(gasto.nombre_entidad == 'CONADI')
                        $scope.totales_gastos_equipos.conadi += Number(gasto.valor);                    
                }
            });        	

            for(var i = 0; i < $scope.data.entidades_fuente_presupuesto_seleccionadas.length; i++) // itera por las entidades que estan seleccionadas en el multiselect
            {
                for(var ii = 0; ii < gasto_equipo.gastos.length; ii++) // itera por la colección orginal de gastos del gasto_personal que se envía desde server
                {
                    var gasto = gasto_equipo.gastos[ii];
                    if(gasto.id_entidad_fuente_presupuesto == $scope.data.entidades_fuente_presupuesto_seleccionadas[i].id) // si se encuentra la entidad que esta seleccionada en los gastos del gasto_personal...
                    {
                        // se añade gasto formateado
                        gastos.push({
                            id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                            nombre_entidad_fuente_presupuesto: gasto.nombre_entidad,
                            valor: gasto.valor, 
                            gasto_invalido: false                          
                        });
                        total_gasto_equipo += Number(gasto.valor);
                        if($scope.totales_gastos_equipos.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] == undefined)
                            $scope.totales_gastos_equipos.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] = Number(gasto.valor);
                        else
                            $scope.totales_gastos_equipos.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] += Number(gasto.valor);
                        break; // encontró la entidad, no necesita seguir iterando por las entidades seleccionadas
                    }            
                }
            }

            // Convierte la fecha de ejecución a un objeto tipo Date
            date = new Date(gasto_equipo.fecha_ejecucion + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            
            // agrega el modelo gasto personal con solo los campos necesarios
            $scope.gastos_equipos.push({
                id_detalle_gasto: gasto_equipo.id_detalle_gasto,
                concepto: gasto_equipo.concepto,
                justificacion: gasto_equipo.justificacion,
                gastos: gastos,
                fecha_ejecucion: new Date(date.getTime() + userTimezoneOffset),
                total: total_gasto_equipo,
                tiene_desembolso_cargado: gasto_equipo.tiene_desembolso_cargado
            });
            
            $scope.totales_gastos_equipos.total += total_gasto_equipo;
            total_gasto_equipo = 0; // reinicia total de presupuesto para el siguiente tipo de gasto
            gastos = [];
        });
    };    
    /*
	| Inicializa los gastos de software
	*/    
    $scope.init_gastos_software = function(data) {

        // prepara variables que usara en el proceso de formateo del modelo del tipo de gasto
        $scope.gastos_software = [];                
        $scope.totales_gastos_software = {ucc: 0, conadi: 0, entidades_fuente_presupuesto: {}, total: 0}; // tiene los totales de las columnas del tipo de gasto.
        var date = null;
        var userTimezoneOffset = null;
        var total_gasto_software = 0; // tiene el total de la fila de gasto personal, es decir el total de un solo gasto personal        
        var gastos = []; // colección con los gastos formateados y sincronizados con el multiselect del gasto_personal

        // los siguientes ciclos aninados sincronizan o aseguran de que se presente las entidades fuente de presupuesto
        // que estan seleccionadas en el multiselect de entidades fuente presupuesto
        // además edita los nombres de los campos del gasto que usado como modelo para mejor legibilidad 

        data.gastos.gastos_software.forEach(function(gasto_software) {
            gasto_software.gastos.forEach(function(gasto) { // itera por la colección orginal de gastos del gasto_personal que se envía desde server
                if(gasto.nombre_entidad == 'UCC' || gasto.nombre_entidad == 'CONADI') // si se trata del gasto presupuestado por la entidad UCC lo añade manualmente, ya que la UCC no esta en el multiselect
                {
                    // se añade gasto formateado
                    gastos.push({
                        id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                        nombre_entidad_fuente_presupuesto: gasto.nombre_entidad,
                        valor: gasto.valor,
                        gasto_invalido: false
                    });
                    total_gasto_software += Number(gasto.valor);

                    if(gasto.nombre_entidad == 'UCC')
                        $scope.totales_gastos_software.ucc += Number(gasto.valor);
                    else if(gasto.nombre_entidad == 'CONADI')
                        $scope.totales_gastos_software.conadi += Number(gasto.valor);                    
                }
            });        	

            for(var i = 0; i < $scope.data.entidades_fuente_presupuesto_seleccionadas.length; i++) // itera por las entidades que estan seleccionadas en el multiselect
            {
                for(var ii = 0; ii < gasto_software.gastos.length; ii++) // itera por la colección orginal de gastos del gasto_personal que se envía desde server
                {
                    var gasto = gasto_software.gastos[ii];
                    if(gasto.id_entidad_fuente_presupuesto == $scope.data.entidades_fuente_presupuesto_seleccionadas[i].id) // si se encuentra la entidad que esta seleccionada en los gastos del gasto_personal...
                    {
                        // se añade gasto formateado
                        gastos.push({
                            id_entidad_fuente_presupuesto: gasto.id_entidad_fuente_presupuesto,
                            nombre_entidad_fuente_presupuesto: gasto.nombre_entidad,
                            valor: gasto.valor, 
                            gasto_invalido: false                          
                        });
                        total_gasto_software += Number(gasto.valor);
                        if($scope.totales_gastos_software.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] == undefined)
                            $scope.totales_gastos_software.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] = Number(gasto.valor);
                        else
                            $scope.totales_gastos_software.entidades_fuente_presupuesto[gasto.id_entidad_fuente_presupuesto] += Number(gasto.valor);
                        break; // encontró la entidad, no necesita seguir iterando por las entidades seleccionadas
                    }            
                }
            }

            // Convierte la fecha de ejecución a un objeto tipo Date
            date = new Date(gasto_software.fecha_ejecucion + 'T00:00:00');
            userTimezoneOffset = new Date().getTimezoneOffset()*60000;
            
            // agrega el modelo gasto personal con solo los campos necesarios
            $scope.gastos_software.push({
                id_detalle_gasto: gasto_software.id_detalle_gasto,
                concepto: gasto_software.concepto,
                justificacion: gasto_software.justificacion,
                gastos: gastos,
                fecha_ejecucion: new Date(date.getTime() + userTimezoneOffset),
                total: total_gasto_software,
                tiene_desembolso_cargado: gasto_software.tiene_desembolso_cargado
            });
            
            $scope.totales_gastos_software.total += total_gasto_software;
            total_gasto_software = 0; // reinicia total de presupuesto para el siguiente tipo de gasto
            gastos = [];
        });
    };        
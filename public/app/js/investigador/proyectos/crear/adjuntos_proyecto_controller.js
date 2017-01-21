sgpi_app.controller('adjuntos_proyecto_controller', function ($scope) {

    $scope.data.documento_presupuesto_invalido = $scope.data.documento_presentacion_proyecto_invalido = $scope.data.documento_acta_inicio_invalido = true;
    
	/*
	|--------------------------------------------------------------------------
	| cambia_documento_presupuesto()
	|--------------------------------------------------------------------------
	| Valida el archivo de presupuesto de proyecto cargado
	*/      
    $scope.cambia_documento_presupuesto = function(files, file, newFiles, duplicateFiles, invalidFiles, event){
        // var arr = [
        //     files,
        //     file,
        //     newFiles,
        //     duplicateFiles,
        //     invalidFiles,
        //     event
        // ];
        if(invalidFiles.length > 0) // se ha cargado archivo invalido
        {
            $scope.data.documento_presupuesto = null;
            alertify.error('Archivo de presupuesto de proyecto inválido. Sólo se permite documentos Excel');
            $scope.data.documento_presupuesto_invalido = true;
        }
        else
        {
            $scope.data.documento_presupuesto_invalido = false;
        }
    }
    
	/*
	|--------------------------------------------------------------------------
	| cambia_documento_presentacion_proyecto()
	|--------------------------------------------------------------------------
	| Valida el archivo de presentación de proyecto cargado
	*/          
    $scope.cambia_documento_presentacion_proyecto = function(files, file, newFiles, duplicateFiles, invalidFiles, event) {
        if(invalidFiles.length > 0) // se ha cargado archivo invalido
        {
            $scope.data.documento_presentacion_proyecto = null;
            alertify.error('Archivo de presentación de proyecto inválido. Sólo se permite documentos Word');
            $scope.data.documento_presentacion_proyecto_invalido = true;
        }        
        else
        {
            $scope.data.documento_presentacion_proyecto_invalido = false;
        }
    };
    
	/*
	|--------------------------------------------------------------------------
	| cambia_documento_acta_inicio()
	|--------------------------------------------------------------------------
	| Valida el archivo de acta de inicio de proyecto cargado
	*/              
    $scope.cambia_documento_acta_inicio = function(files, file, newFiles, duplicateFiles, invalidFiles, event) {
        if(invalidFiles.length > 0) // se ha cargado archivo invalido
        {
            $scope.data.documento_acta_inicio = null;
            alertify.error('Archivo de acta de inicio de proyecto inválido. Sólo se permite documentos Word');
            $scope.data.documento_acta_inicio_invalido = true;
        }    
        else
        {
            $scope.data.documento_acta_inicio_invalido = false;
        }
    };
    
	/*
	|--------------------------------------------------------------------------
	| registrar_proyecto()
	|--------------------------------------------------------------------------
	| Ejecuta el envío del formulario validando primero los documentos adjuntos cargados
	*/                  
    $scope.registrar_proyecto = function() {
        if($scope.data.documento_presupuesto_invalido || $scope.data.documento_presentacion_proyecto_invalido || $scope.data.documento_acta_inicio_invalido)
        {
            // algun documento es inválido
            alertify.error('Los documentos del proyecto no son válidos');
        }
        else
        {
            $('#input_registrar_proyecto').trigger('click');
        }
    };
    
    $scope.regresar_gastos = function() {
        $('a[href="#contenido_gastos"]').tab('show');
    };
});
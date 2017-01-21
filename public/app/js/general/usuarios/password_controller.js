sgpi_app.controller('password_controller', function($scope, $http, idUsuario){
    
    $scope.data = {
        btn_guardar_seleccionado: false,
        error_contrasenia: '',
        contrasenia1: '',
        contrasenia2: '',
        contrasenia3: ''
    };
    $scope.visibilidad = {
        deshabilitar_btn_guardar: false,
        show_contrasenia1_invalido: true,
        show_contrasenia2_invalido: true,
        show_contrasenia3_invalido: true
    };
    
    // Envía al servidor los valores de las contraseñas, pero primero valida localmente
    // que los campos contengan valores adecuados
    $scope.guardar = function(){
        if(!$scope.data.btn_guardar_seleccionado)
            $scope.data.btn_guardar_seleccionado = true;
        
        // si algunos modelos de campos de contraseña están incorrectos entonces se cancela envio de datos
        if
        (
            $scope.visibilidad.show_contrasenia1_invalido || 
            $scope.visibilidad.show_contrasenia2_invalido || 
            $scope.visibilidad.show_contrasenia3_invalido
        )
        {
            $scope.data.error_contrasenia = 'Error: datos incorrectos';
            return;
        }
        
        // se alcanzó este código, quiere decir que los modelos de contraselas estan correctos, se envia datos al server
        $scope.visibilidad.show_velo_msj_operacion = true;
        $scope.visibilidad.deshabilitar_btn_guardar = true;
        $http({
            url: '/usuarios/cambiar_contrasenia',
            method: 'POST',
            params: {
                actual: $scope.data.contrasenia1,
                nueva: $scope.data.contrasenia2,
                confirmada: $scope.data.contrasenia3,
                id_usuario: idUsuario
            }
        })
        .success(function(data) {
            if(data.consultado == 1){
                // consultado igual a 1 quiere decir operación exitosa
                $scope.$close();
            }
            else{
                if(data.error_de_servidor == 1){
                    if(data.mensaje == 'contrasenia1'){
                        $scope.visibilidad.show_contrasenia1_invalido = true;
                        $scope.data.error_contrasenia = 'Error: la contrasela actual no es la correcta';
                    }
                    else if(data.mensaje == 'contrasenia2'){
                        $scope.visibilidad.show_contrasenia2_invalido = true;
                        $scope.data.error_contrasenia = 'Error: nueva contraseña no válida';
                    }
                    else if(data.mensaje == 'contrasenia3'){ // pocas veces ha de suceder esto
                        $scope.visibilidad.show_contrasenia3_invalido = true;
                        $scope.data.error_contrasenia = 'Error: la confirmación de contraseña no coincide';
                    }
                }
                else{
                    console.log(data);
                    $scope.$dismiss(data.mensaje);
                }
            }
        })
        .error(function(data, status){
            console.log(data);
            $scope.$dismiss(status);
        })
        .finally(function() {
            $scope.visibilidad.show_velo_msj_operacion = false;
            $scope.visibilidad.deshabilitar_btn_guardar = false;
        });
    };
    
    $scope.cancelar = function(){
        $scope.$dismiss(null);
    };
    
    // las funciones que analizan los cambios a los modelos 
    // de los campos de contraselas, donde
    // contraseña1 = actual contrasela
    // cntraseña2 = nueva contraseña
    // contraseña3 = confirmar contraseña2
    $scope.cambia_contrasenia1 = function(){
        
        if($scope.data.contrasenia1.length == undefined || $scope.data.contrasenia1.length == 0)
            $scope.visibilidad.show_contrasenia1_invalido = true;
        else
            $scope.visibilidad.show_contrasenia1_invalido = false;
    };
    $scope.cambia_contrasenia2 = function(){
        if($scope.data.contrasenia2.length == undefined || $scope.data.contrasenia2.length == 0){
            $scope.visibilidad.show_contrasenia2_invalido = true;
            return;
        }
        if($scope.data.contrasenia2 == $scope.data.contrasenia3){
            $scope.visibilidad.show_contrasenia2_invalido = false;
            $scope.visibilidad.show_contrasenia3_invalido = false;
        }
        else{
            $scope.visibilidad.show_contrasenia2_invalido = true;
            $scope.visibilidad.show_contrasenia3_invalido = true;
        }
    };
    $scope.cambia_contrasenia3 = function(){
        if($scope.data.contrasenia3.length == undefined || $scope.data.contrasenia3.length == 0){
            $scope.visibilidad.show_contrasenia3_invalido = true;
            return;
        }
        if($scope.data.contrasenia2 == $scope.data.contrasenia3){
            $scope.visibilidad.show_contrasenia2_invalido = false;
            $scope.visibilidad.show_contrasenia3_invalido = false;
        }
        else{
            $scope.visibilidad.show_contrasenia2_invalido = true;
            $scope.visibilidad.show_contrasenia3_invalido = true;
        }
    };
    
});




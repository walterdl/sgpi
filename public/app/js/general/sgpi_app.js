/*
Incialización de app angularjs y cambio de syntaxis '{{}}' por '{$$}' 
para evitar el conflicto de la sintaxis que provee las plantillas blade
*/
var sgpi_app = angular.module('sgpi_app', ['Alertify', 'angular-bind-html-compile'])
    .config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{$').endSymbol('$}');
});

    
sgpi_app.controller('base_controller', function($scope, $http, notify_operacion_previa, mensaje_operacion_previa){
    $(document).ready(function () {
        
        if(notify_operacion_previa !== null && notify_operacion_previa !== undefined){
            
            var delay = alertify.get('notifier','delay');
            alertify.set('notifier','delay', 0);
            
            if(notify_operacion_previa == 'success')
                alertify.success(mensaje_operacion_previa);
                
            else if(notify_operacion_previa == 'error'){
                alertify.error(mensaje_operacion_previa);
                console.log(mensaje_operacion_previa);
            }
            alertify.set('notifier','delay', delay);
        }    
        
        // aplica efecto zoom a la foto de perfil
        $('.imagen_perfil_raiz').magnificPopup({
            type:'image',
            removalDelay: 300,
            mainClass: 'mfp-fade',
            zoom: {
                enabled: true, // By default it's false, so don't forget to enable it
                
                duration: 300, // duration of the effect, in milliseconds
                easing: 'ease-in-out', // CSS transition easing function
                
                // The "opener" function should return the element from which popup will be zoomed in
                // and to which popup will be scaled down
                // By defailt it looks for an image tag:
                opener: function(openerElement) {
                    // openerElement is the element on which popup was initialized, in this case its <a> tag
                    // you don't need to add "opener" option if this code matches your needs, it's defailt one.
                    return openerElement.is('img') ? openerElement : openerElement.find('img');
                }
            }            
        });
    });
    $scope.data = {};
    $scope.visibilidad = {};
});

sgpi_app.filter('capitalizeWords', function() {
    return function(input) {
        if (input != null) {
            if (input.indexOf(' ') !== -1) {
                var inputPieces,i;
                input = input.toLowerCase();
                inputPieces = input.split(' ');
                for (i = 0; i < inputPieces.length; i++) {
                    inputPieces[i] = capitalizeString(inputPieces[i]);
                }
                return inputPieces.toString().replace(/,/g, ' ');
            }
            else {
                input = input.toLowerCase();
                return capitalizeString(input);
            }
            function capitalizeString(inputString) {
                return inputString.substring(0, 1).toUpperCase() + inputString.substring(1);
            }
        }
        else return input;
    };
});

sgpi_app.directive('compile', function($compile) {
    return {
        restrict: 'A',
        replace: true,
        link: function (scope, element, attrs) {
            scope.$watch(attrs.dynamic, function(html) {
                element[0].innerHTML = html;
                $compile(element.contents())(scope);
            });
        }
    };
});